/**
 * Advanced Time Tracking Engine - WorkFlow Pro
 */

let activeTimerInterval = null;
let syncInterval = null;
let currentTimerSeconds = 0;
let currentTaskId = null;

document.addEventListener('DOMContentLoaded', () => {
    checkActiveTimer();
    requestNotificationPermission();
});

// Request Browser Notifications for Deadline Reminders
function requestNotificationPermission() {
    if ("Notification" in window && Notification.permission === "default") {
        Notification.requestPermission();
    }
}

function sendDeadlineNotification(title, message) {
    if ("Notification" in window && Notification.permission === "granted") {
        new Notification(title, { body: message, icon: '/favicon.ico' });
    }
}

// Check active timer state on load
function checkActiveTimer() {
    fetch(`${BASE_URL}/api/time_tracker?action=status`)
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data.active) {
                currentTaskId = res.data.task_id;
                currentTimerSeconds = res.data.elapsed_seconds;
                showFloatingTimer(res.data.task_title, res.data.elapsed_seconds);
                startTimerCounter();
                startAutoSync();
            } else {
                hideFloatingTimer();
                stopAutoSync();
            }
        })
        .catch(err => console.error("Timer check error:", err));
}

function startTaskTimer(taskId) {
    fetch(`${BASE_URL}/api/time_tracker`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=start&task_id=${taskId}&csrf_token=${CSRF_TOKEN}`
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            const toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
            toast.fire({ icon: 'success', title: 'Live Task Timer Started!' });
            checkActiveTimer();
        } else {
            Swal.fire('Timer Alert', res.message, 'warning');
        }
    });
}

function pauseTaskTimer(taskId) {
    fetch(`${BASE_URL}/api/time_tracker`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=pause&task_id=${taskId}&csrf_token=${CSRF_TOKEN}`
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            stopTimerCounter();
            stopAutoSync();
            const toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
            toast.fire({ icon: 'info', title: 'Task Timer Paused' });
            checkActiveTimer();
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    });
}

function stopTaskTimer(taskId) {
    Swal.fire({
        title: 'Complete & Submit Task?',
        text: 'Wrap up work and submit this completed task for Manager & Product Manager approval?',
        icon: 'question',
        input: 'textarea',
        inputPlaceholder: 'Enter summary of completed deliverables & work notes...',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-paper-plane me-1"></i>Submit for Approval',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b'
    }).then(result => {
        if (result.isConfirmed) {
            const logText = result.value || 'Work completed successfully.';
            fetch(`${BASE_URL}/api/time_tracker`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=stop&task_id=${taskId}&log_text=${encodeURIComponent(logText)}&csrf_token=${CSRF_TOKEN}`
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    stopTimerCounter();
                    stopAutoSync();
                    hideFloatingTimer();
                    Swal.fire({
                        title: 'Submitted for Approval!',
                        text: res.message,
                        icon: 'success'
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
        }
    });
}

function saveInlineWorkNote() {
    const input = document.getElementById('floatingWorkNoteInput');
    if (!input || !input.value.trim()) return;

    const note = input.value.trim();
    fetch(`${BASE_URL}/api/time_tracker`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=save_note&task_id=${currentTaskId}&note=${encodeURIComponent(note)}&csrf_token=${CSRF_TOKEN}`
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            input.value = '';
            const toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });
            toast.fire({ icon: 'success', title: 'Work note attached!' });
        }
    });
}

function startTimerCounter() {
    stopTimerCounter();
    activeTimerInterval = setInterval(() => {
        currentTimerSeconds++;
        updateTimerDisplays(currentTimerSeconds);
    }, 1000);
}

function stopTimerCounter() {
    if (activeTimerInterval) {
        clearInterval(activeTimerInterval);
        activeTimerInterval = null;
    }
}

// 10-second Heartbeat Auto-Sync
function startAutoSync() {
    stopAutoSync();
    syncInterval = setInterval(() => {
        if (currentTaskId && activeTimerInterval) {
            fetch(`${BASE_URL}/api/time_tracker`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=sync&task_id=${currentTaskId}&elapsed_seconds=${currentTimerSeconds}&csrf_token=${CSRF_TOKEN}`
            }).catch(e => console.log("Sync error:", e));
        }
    }, 10000);
}

function stopAutoSync() {
    if (syncInterval) {
        clearInterval(syncInterval);
        syncInterval = null;
    }
}

function updateTimerDisplays(totalSeconds) {
    const formatted = formatSeconds(totalSeconds);
    const floatDisplay = document.getElementById('floatingTimerClock');
    if (floatDisplay) floatDisplay.textContent = formatted;
    
    const inlineDisplay = document.getElementById(`inlineTaskTimer_${currentTaskId}`);
    if (inlineDisplay) inlineDisplay.textContent = formatted;
}

function formatSeconds(secs) {
    const h = Math.floor(secs / 3600);
    const m = Math.floor((secs % 3600) / 60);
    const s = secs % 60;
    return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
}

function showFloatingTimer(title, elapsed) {
    let widget = document.getElementById('floatingTimerWidget');
    if (!widget) {
        widget = document.createElement('div');
        widget.id = 'floatingTimerWidget';
        widget.className = 'timer-widget';
        document.body.appendChild(widget);
    }
    widget.innerHTML = `
        <div class="d-flex flex-column gap-1">
            <div class="d-flex align-items-center gap-2">
                <span class="spinner-grow spinner-grow-sm text-primary" role="status"></span>
                <div>
                    <div class="small fw-bold text-dark text-truncate" style="max-width:180px;">${title}</div>
                    <div class="timer-display" id="floatingTimerClock">${formatSeconds(elapsed)}</div>
                </div>
            </div>
            <div class="input-group input-group-sm mt-1">
                <input type="text" id="floatingWorkNoteInput" class="form-control form-control-sm" placeholder="Add live work note...">
                <button class="btn btn-outline-primary" type="button" onclick="saveInlineWorkNote()"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
        <div class="d-flex flex-column gap-1 ms-2">
            <button class="btn btn-sm btn-warning" onclick="pauseTaskTimer(${currentTaskId})" title="Pause Timer"><i class="fas fa-pause"></i></button>
            <button class="btn btn-sm btn-success" onclick="stopTaskTimer(${currentTaskId})" title="Complete Task"><i class="fas fa-check"></i></button>
        </div>
    `;
    widget.style.display = 'flex';
}

function hideFloatingTimer() {
    const widget = document.getElementById('floatingTimerWidget');
    if (widget) widget.style.display = 'none';
}
