/**
 * Core Application JavaScript Engine - WorkFlow Pro
 * Handles IST live clock, Shift Start / End management, timer persistence, notifications, and search.
 */

let shiftTimerInterval = null;
let shiftElapsedSeconds = 0;
let shiftStartTimestamp = null;

document.addEventListener('DOMContentLoaded', () => {
    initGlobalSearch();
    initClock();
    initNotifications();
    initStatCounterAnimation();
    initShiftStatus();
    startPeriodicIpCheck();
    initMobileSidebar();
    initGlobalMeetingMonitor();
});

// Helper to format date in IST (DD/MM/YYYY)
function getISTDateString(date = new Date()) {
    return new Intl.DateTimeFormat('en-GB', {
        timeZone: 'Asia/Kolkata',
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    }).format(date);
}

// Helper to format time in IST (hh:mm:ss AM/PM (IST))
function getISTTimeString(date = new Date()) {
    const formatted = new Intl.DateTimeFormat('en-US', {
        timeZone: 'Asia/Kolkata',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true
    }).format(date);
    return `${formatted} (IST)`;
}

// Format seconds into HH:MM:SS
function formatSecondsToHMS(secs) {
    const h = Math.floor(secs / 3600);
    const m = Math.floor((secs % 3600) / 60);
    const s = secs % 60;
    return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
}

// Animated Stat Counter
function initStatCounterAnimation() {
    const statValues = document.querySelectorAll('.stat-value');
    statValues.forEach(counter => {
        const text = counter.innerText.trim();
        if (/^\d+$/.test(text)) {
            const target = parseInt(text, 10);
            let count = 0;
            const speed = Math.max(1, Math.floor(target / 25));
            counter.innerText = '0';
            
            const updateCount = () => {
                count += speed;
                if (count < target) {
                    counter.innerText = count;
                    setTimeout(updateCount, 25);
                } else {
                    counter.innerText = target;
                }
            };
            updateCount();
        }
    });
}

// Top Navbar Live IST Clock
function initClock() {
    const clockEl = document.getElementById('navLiveClock');
    if (!clockEl) return;
    
    function updateTime() {
        clockEl.textContent = getISTTimeString(new Date());
    }
    updateTime();
    setInterval(updateTime, 1000);
}

// Check Active Shift Status & Maintain Live Shift Timer across page reloads
let lastKnownIpAuthorized = null;
let ipCheckInterval = null;

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[m]);
}

function applyIpAuthorizationVisibility(isAuthorized, data) {
    lastKnownIpAuthorized = isAuthorized;
    const shiftBtns = document.querySelectorAll('.shift-toggle-btn, [data-shift-toggle], .wifi-restricted-btn');
    shiftBtns.forEach(btn => {
        if (isAuthorized) {
            btn.style.removeProperty('display');
            btn.classList.remove('d-none');
        } else {
            btn.style.setProperty('display', 'none', 'important');
            btn.classList.add('d-none');
        }
    });

    const unauthNotices = document.querySelectorAll('.wifi-unauth-notice');
    unauthNotices.forEach(el => {
        if (isAuthorized) {
            el.style.setProperty('display', 'none', 'important');
            el.classList.add('d-none');
        } else {
            el.style.removeProperty('display');
            el.classList.remove('d-none');
        }
    });

    const wifiBadges = document.querySelectorAll('.wifi-status-badge');
    wifiBadges.forEach(b => {
        if (isAuthorized) {
            const netName = (data && data.network_name) ? data.network_name : 'Office Wi-Fi';
            b.className = 'badge bg-success-subtle text-success border border-success px-2 py-1 fs-9 fw-bold wifi-status-badge';
            b.innerHTML = `<i class="fas fa-wifi me-1"></i>${escapeHtml(netName)}`;
        } else {
            b.className = 'badge bg-danger-subtle text-danger border border-danger px-2 py-1 fs-9 fw-bold wifi-status-badge';
            b.innerHTML = `<i class="fas fa-wifi-slash me-1"></i>Office Wi-Fi Required`;
        }
    });
}

function checkClientIpAuthorization() {
    if (typeof BASE_URL === 'undefined') return;
    const url = typeof window.resolveApiUrl === 'function' ? window.resolveApiUrl('/api/attendance?action=check_ip') : `${BASE_URL}/api/attendance.php?action=check_ip`;
    fetch(url)
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data) {
                const isIpAuth = Boolean(res.data.ip_authorized);
                applyIpAuthorizationVisibility(isIpAuth, res.data);
            }
        })
        .catch(err => console.warn("Periodic IP check notice:", err));
}

function startPeriodicIpCheck() {
    if (ipCheckInterval) clearInterval(ipCheckInterval);
    // Periodic Wi-Fi IP validation every 20 seconds
    ipCheckInterval = setInterval(checkClientIpAuthorization, 20000);

    // Event-driven recheck on tab focus, network reconnection, or mobile app wake
    window.addEventListener('online', checkClientIpAuthorization);
    window.addEventListener('focus', checkClientIpAuthorization);
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) checkClientIpAuthorization();
    });
}

function initShiftStatus() {
    if (typeof BASE_URL === 'undefined') return;
    const url = typeof window.resolveApiUrl === 'function' ? window.resolveApiUrl('/api/attendance?action=status') : `${BASE_URL}/api/attendance.php?action=status`;
    fetch(url)
        .then(res => res.json())
        .then(res => {
            const isIpAuth = (res.data && res.data.ip_authorized !== undefined) ? Boolean(res.data.ip_authorized) : true;
            applyIpAuthorizationVisibility(isIpAuth, res.data);

            if (res.success && res.data && res.data.active) {
                shiftStartTimestamp = res.data.start_timestamp;
                shiftElapsedSeconds = res.data.elapsed_seconds;
                
                updateShiftUIState(true, res.data);
                startShiftTimerCounter();
            } else {
                stopShiftTimerCounter();
                updateShiftUIState(false, res.data ? (res.data.completed_today || res.data) : null);
            }
        })
        .catch(err => console.error("Error checking shift status:", err));
}

// Check if current IST time is at or past 07:00 PM (19:00 IST)
function isPast7PMIST() {
    try {
        const istHour = parseInt(new Intl.DateTimeFormat('en-GB', {
            timeZone: 'Asia/Kolkata',
            hour: '2-digit',
            hour12: false
        }).format(new Date()), 10);
        return (istHour >= 19);
    } catch (e) {
        return false;
    }
}

function startShiftTimerCounter() {
    stopShiftTimerCounter();
    updateShiftTimerDisplays(shiftElapsedSeconds);
    shiftTimerInterval = setInterval(() => {
        shiftElapsedSeconds++;
        updateShiftTimerDisplays(shiftElapsedSeconds);

        // Automatic shift end at 07:00 PM IST if user hasn't clocked out
        if (isPast7PMIST()) {
            stopShiftTimerCounter();
            initShiftStatus();
            if (typeof Swal !== 'undefined') {
                const toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 5000 });
                toast.fire({
                    icon: 'info',
                    title: 'Auto Shift Ended (07:00 PM IST)',
                    text: 'Your shift has reached 07:00 PM and was automatically closed as Full Day.'
                });
            }
        }
    }, 1000);
}

function stopShiftTimerCounter() {
    if (shiftTimerInterval) {
        clearInterval(shiftTimerInterval);
        shiftTimerInterval = null;
    }
}

// Check if current IST time is within Lunch Hour (02:00 PM - 03:00 PM)
function isCurrentISTLunchTime() {
    try {
        const istHour = parseInt(new Intl.DateTimeFormat('en-GB', {
            timeZone: 'Asia/Kolkata',
            hour: '2-digit',
            hour12: false
        }).format(new Date()), 10);
        return (istHour === 14); // 14:00 to 14:59 is 2:00 PM to 3:00 PM
    } catch (e) {
        return false;
    }
}

function updateShiftTimerDisplays(secs) {
    const formatted = formatSecondsToHMS(secs);
    const elements = document.querySelectorAll('.live-shift-timer-clock, #empLiveShiftTimer, #workdayTimeWorkedClock');
    elements.forEach(el => {
        el.textContent = formatted;
    });

    const remainingEl = document.getElementById('empRemainingHoursVal');
    if (remainingEl) {
        const remainingSecs = Math.max(0, 31800 - secs); // 8h 50m = 31,800s
        remainingEl.textContent = formatSecondsToHMS(remainingSecs);
    }

    // Dynamic Live Lunch Timing Badge Update
    const lunchBadges = document.querySelectorAll('.live-lunch-status-badge');
    const inLunch = isCurrentISTLunchTime();
    lunchBadges.forEach(badge => {
        if (inLunch) {
            badge.className = 'badge bg-warning text-dark border border-warning px-2.5 py-1 fw-bold live-lunch-status-badge shadow-sm animate-pulse';
            badge.innerHTML = '<i class="fas fa-utensils me-1"></i>Lunch Break Active (02:00 PM - 03:00 PM)';
        } else {
            badge.className = 'badge bg-light text-secondary border px-2.5 py-1 fw-semibold live-lunch-status-badge';
            badge.innerHTML = '<i class="fas fa-utensils me-1 text-warning"></i>Lunch: 02:00 PM - 03:00 PM';
        }
    });
}

// Dynamic UI update for shift buttons and status banners across dashboard & pages
function updateShiftUIState(isActive, data) {
    const shiftBtns = document.querySelectorAll('.shift-toggle-btn, [data-shift-toggle]');
    shiftBtns.forEach(btn => {
        const isNav = btn.closest('header') || btn.classList.contains('fs-8') || btn.classList.contains('btn-sm');
        if (isActive) {
            btn.classList.remove('btn-outline-success', 'btn-success', 'btn-primary');
            btn.classList.add('btn-danger');
            if (isNav) {
                btn.innerHTML = '<i class="fas fa-stop me-1"></i><span class="d-none d-sm-inline">End Shift</span><span class="d-inline d-sm-none">End</span>';
            } else {
                btn.innerHTML = '<i class="fas fa-stop me-1"></i>End Shift';
            }
            btn.onclick = (e) => { e.preventDefault(); handleClockInOut('clock_out'); };
            btn.removeAttribute('disabled');
        } else {
            btn.classList.remove('btn-danger', 'btn-dark');
            btn.classList.add('btn-outline-success');
            if (isNav) {
                btn.innerHTML = '<i class="fas fa-fingerprint me-1"></i><span class="d-none d-sm-inline">Start Shift</span><span class="d-inline d-sm-none">Shift</span>';
            } else {
                btn.innerHTML = '<i class="fas fa-play me-1"></i>Start Shift';
            }
            btn.onclick = (e) => { e.preventDefault(); handleClockInOut('clock_in'); };
            btn.removeAttribute('disabled');
        }
    });

    // Update active shift details card / banner on dashboard and attendance view
    const shiftWidget = document.getElementById('activeShiftWidget');
    if (shiftWidget) {
        if (isActive && data) {
            shiftWidget.style.display = 'block';
            const startTimeEl = document.getElementById('shiftStartTimeText');
            if (startTimeEl && data.clock_in_formatted) startTimeEl.textContent = data.clock_in_formatted;
        } else {
            shiftWidget.style.display = 'none';
        }
    }

    if (data) {
        const empShiftStartVal = document.getElementById('empShiftStartVal');
        if (empShiftStartVal && data.clock_in_time) empShiftStartVal.textContent = data.clock_in_time;

        const empShiftEndVal = document.getElementById('empShiftEndVal');
        if (empShiftEndVal && data.clock_out_time) empShiftEndVal.textContent = data.clock_out_time;

        const empLateMinutesVal = document.getElementById('empLateMinutesVal');
        if (empLateMinutesVal && data.late_text) empLateMinutesVal.textContent = data.late_text;

        const empEarlyExitVal = document.getElementById('empEarlyExitVal');
        if (empEarlyExitVal && data.early_exit_text) empEarlyExitVal.textContent = data.early_exit_text;

        const pBadge = document.getElementById('employeePunctualityBadge');
        if (pBadge && data.punctuality_badge) pBadge.innerHTML = data.punctuality_badge;

        const dBadge = document.getElementById('employeeDayBadge');
        if (dBadge && data.day_badge) dBadge.innerHTML = data.day_badge;
    }
}

// Attendance Clock In / Clock Out Quick Action
function handleClockInOut(action) {
    const isStart = (action === 'clock_in' || action === 'start_shift' || action === 'start');
    const apiAction = isStart ? 'clock_in' : 'clock_out';
    const csrf = (typeof CSRF_TOKEN !== 'undefined' && CSRF_TOKEN) ? CSRF_TOKEN : '';

    const executeAction = () => {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: isStart ? 'Starting Shift...' : 'Ending Shift...',
                text: 'Please wait while your attendance is recorded.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
        }

        const formData = new URLSearchParams();
        formData.append('action', apiAction);
        formData.append('csrf_token', csrf);

        const attendanceApiUrl = typeof window.resolveApiUrl === 'function' ? window.resolveApiUrl('/api/attendance') : `${BASE_URL}/api/attendance.php`;
        fetch(attendanceApiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData.toString()
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: isStart ? 'Shift Started!' : 'Shift Ended!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        initShiftStatus();
                        location.reload();
                    });
                } else {
                    alert(res.message);
                    location.reload();
                }
            } else {
                if (res.data && res.data.ip_authorized === false) {
                    applyIpAuthorizationVisibility(false, res.data);
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Office Wi-Fi Required',
                        text: res.message
                    });
                } else {
                    alert(res.message);
                }
            }
        })
        .catch(err => {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error', 'Shift action failed: ' + err.message, 'error');
            } else {
                alert('Shift action failed: ' + err.message);
            }
        });
    };

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: isStart ? 'Start Shift Now?' : 'End Shift Now?',
            text: isStart ? 'Record current Indian Standard Time (IST) and start live shift timer.' : 'Record end time in IST and calculate total shift duration.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: isStart ? 'Yes, Start Shift' : 'Yes, End Shift',
            confirmButtonColor: isStart ? '#10b981' : '#ef4444',
            cancelButtonColor: '#64748b'
        }).then(result => {
            if (result.isConfirmed) {
                executeAction();
            }
        });
    } else {
        if (confirm(isStart ? 'Start Shift Now?' : 'End Shift Now?')) {
            executeAction();
        }
    }
}

// Global Alias for Workday shift handler
window.handleClockInOut = handleClockInOut;
window.handleWorkdayShift = handleClockInOut;

// Global Search AJAX
function initGlobalSearch() {
    const searchInput = document.getElementById('globalSearchInput');
    const searchResults = document.getElementById('globalSearchResults');
    if (!searchInput || !searchResults) return;

    let debounceTimer;
    searchInput.addEventListener('input', (e) => {
        clearTimeout(debounceTimer);
        const query = e.target.value.trim();
        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`${BASE_URL}/api/search?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(res => {
                    if (res.success && res.data.length > 0) {
                        let html = '<div class="list-group list-group-flush rounded-3">';
                        res.data.forEach(item => {
                            html += `
                                <a href="${item.link}" class="list-group-item list-group-item-action bg-white text-dark border-bottom d-flex align-items-center justify-content-between p-3">
                                    <div>
                                        <i class="${item.icon} me-2 text-primary"></i>
                                        <strong class="text-dark">${item.title}</strong>
                                        <small class="d-block text-muted">${item.subtitle}</small>
                                    </div>
                                    <span class="badge bg-light text-dark border">${item.type}</span>
                                </a>
                            `;
                        });
                        html += '</div>';
                        searchResults.innerHTML = html;
                        searchResults.style.display = 'block';
                    } else {
                        searchResults.innerHTML = '<div class="p-3 text-muted">No results found</div>';
                        searchResults.style.display = 'block';
                    }
                })
                .catch(() => {
                    searchResults.style.display = 'none';
                });
        }, 300);
    });

    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
}

// Notifications Dispatcher
function initNotifications() {
    const notifBtn = document.getElementById('notificationsDropdown');
    if (!notifBtn) return;
    
    fetchNotifications();
    setInterval(fetchNotifications, 30000);
}

function fetchNotifications() {
    const badge = document.getElementById('notifBadgeDot');
    const container = document.getElementById('notifContainer');
    if (!container) return;

    fetch(`${BASE_URL}/api/notifications?action=fetch`)
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                if (res.unread_count > 0) {
                    if (badge) badge.style.display = 'block';
                } else {
                    if (badge) badge.style.display = 'none';
                }
                
                if (res.data.length > 0) {
                    let html = '';
                    res.data.forEach(n => {
                        html += `
                            <a class="dropdown-item py-2 border-bottom ${n.is_read ? 'opacity-75' : 'fw-bold'}" href="${n.link || '#'}">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-bell text-warning me-2"></i>
                                    <div>
                                        <div class="small text-dark">${n.title}</div>
                                        <div class="smaller text-secondary">${n.message}</div>
                                        <div class="smaller text-muted">${n.time_ago}</div>
                                    </div>
                                </div>
                            </a>
                        `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="dropdown-item text-muted text-center py-3">No notifications</div>';
                }
            }
        });
}

// Global Mobile Sidebar Controls (Fail-Safe)
window.toggleMobileSidebar = function(e) {
    if (e) { e.preventDefault(); e.stopPropagation(); }
    const sidebar = document.getElementById('appSidebar');
    if (!sidebar) return;

    if (sidebar.classList.contains('show')) {
        window.closeMobileSidebar(e);
    } else {
        window.openMobileSidebar(e);
    }
};

window.openMobileSidebar = function(e) {
    if (e) { e.preventDefault(); e.stopPropagation(); }
    const sidebar = document.getElementById('appSidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    if (sidebar) sidebar.classList.add('show');
    if (backdrop) backdrop.classList.add('show');
    document.body.classList.add('sidebar-open');
};

window.closeMobileSidebar = function(e) {
    if (e && e.stopPropagation) e.stopPropagation();
    const sidebar = document.getElementById('appSidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    if (sidebar) sidebar.classList.remove('show');
    if (backdrop) backdrop.classList.remove('show');
    document.body.classList.remove('sidebar-open');
};

// Responsive Mobile Off-Canvas Sidebar Controller
function initMobileSidebar() {
    const backdrop = document.getElementById('sidebarBackdrop');
    if (backdrop) {
        backdrop.addEventListener('click', function(e) {
            window.closeMobileSidebar(e);
        });
    }

    const sidebar = document.getElementById('appSidebar');
    if (sidebar) {
        const navLinks = sidebar.querySelectorAll('.nav-link-custom');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    window.closeMobileSidebar();
                }
            });
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.closeMobileSidebar();
        }
    });
}

// Global Real-Time Meeting Alert Monitor (Section 6 & 23)
let activeGlobalMeetingAlertId = null;

function initGlobalMeetingMonitor() {
    if (typeof BASE_URL === 'undefined') return;

    function pollMeetings() {
        if (window.location.pathname.includes('/workday')) return;

        fetch(`${BASE_URL}/api/meetings?action=active_request`)
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data.has_pending_request && res.data.pending_meeting) {
                    const m = res.data.pending_meeting;
                    if (activeGlobalMeetingAlertId !== m.id) {
                        activeGlobalMeetingAlertId = m.id;
                        promptGlobalMeetingModal(m);
                    }
                }
            })
            .catch(() => {});
    }

    pollMeetings();
    setInterval(pollMeetings, 12000);
}

function promptGlobalMeetingModal(m) {
    Swal.fire({
        title: '🔔 Meeting Request',
        html: `
            <div class="text-start p-3 bg-light rounded-3 border mb-3 fs-7">
                <div class="mb-1"><small class="text-muted">Caller:</small> <strong>${m.caller_name || 'Boss / Admin'}</strong></div>
                <div class="mb-1"><small class="text-muted">Topic:</small> <strong class="text-primary">${m.title}</strong></div>
                <div class="mb-1"><small class="text-muted">Time:</small> <strong>${m.start_time}</strong> (${m.duration} mins)</div>
                ${m.meeting_link ? `<div class="mb-1"><small class="text-muted">Location / Link:</small> <a href="${m.meeting_link}" target="_blank" class="small text-truncate d-block">${m.meeting_link}</a></div>` : ''}
            </div>
            <input type="text" id="globalMeetingNotesInput" class="form-control form-control-sm" placeholder="Response note (optional)...">
        `,
        icon: 'info',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: '<i class="fas fa-check me-1"></i>Accept & Join',
        denyButtonText: "<i class=\"fas fa-clock me-1\"></i>I'm Busy",
        cancelButtonText: 'Decline',
        confirmButtonColor: '#10b981',
        denyButtonColor: '#f59e0b',
        cancelButtonColor: '#64748b',
        allowOutsideClick: false
    }).then(result => {
        const notes = document.getElementById('globalMeetingNotesInput')?.value || '';
        let resp = 'decline';
        if (result.isConfirmed) resp = 'accept';
        else if (result.isDenied) resp = 'busy';

        fetch(`${BASE_URL}/api/meetings`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=respond&meeting_id=${m.id}&response=${resp}&response_notes=${encodeURIComponent(notes)}&csrf_token=${CSRF_TOKEN}`
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                if (resp === 'accept') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Meeting Accepted',
                        text: 'Your status has been updated to In Meeting.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = `${BASE_URL}/workday`;
                    });
                } else {
                    Swal.fire('Updated', res.message, 'info');
                }
            }
        });
    });
}

// Global Task Approval & Completion Helpers
function approveTaskPrompt(taskId) {
    Swal.fire({
        title: 'Approve Task Completion?',
        text: 'Are you sure you want to approve this completed task and change status to Done?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-check-circle me-1"></i>Yes, Approve & Mark Done',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b'
    }).then(result => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('action', 'approve_task');
            formData.append('task_id', taskId);
            formData.append('csrf_token', window.CSRF_TOKEN || '');

            fetch(`${window.BASE_URL || ''}/api/tasks`, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Swal.fire({
                        title: 'Approved!',
                        text: res.message,
                        icon: 'success',
                        timer: 2000
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Failed to approve task: ' + err.message, 'error');
            });
        }
    });
}

function rejectApprovalPrompt(taskId) {
    Swal.fire({
        title: 'Request Task Revision?',
        text: 'Enter feedback or revisions required for the employee:',
        icon: 'warning',
        input: 'textarea',
        inputPlaceholder: 'State what changes or fixes are needed before approval...',
        showCancelButton: true,
        confirmButtonText: 'Return for Revision',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        inputValidator: (value) => {
            if (!value || !value.trim()) {
                return 'Please provide a reason or revision instructions.';
            }
        }
    }).then(result => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('action', 'reject_approval');
            formData.append('task_id', taskId);
            formData.append('reason', result.value.trim());
            formData.append('csrf_token', window.CSRF_TOKEN || '');

            fetch(`${window.BASE_URL || ''}/api/tasks`, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Swal.fire({
                        title: 'Returned for Revision',
                        text: res.message,
                        icon: 'info',
                        timer: 2000
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Failed to return task: ' + err.message, 'error');
            });
        }
    });
}

function submitTaskCompletionPrompt(taskId) {
    Swal.fire({
        title: 'Submit Task for Approval?',
        text: 'Wrap up and submit this task to Manager & Product Manager for completion approval?',
        icon: 'question',
        input: 'textarea',
        inputPlaceholder: 'Enter summary of work completed (deliverables, links, notes)...',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-paper-plane me-1"></i>Submit for Approval',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b'
    }).then(result => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('action', 'submit_completion');
            formData.append('task_id', taskId);
            formData.append('notes', result.value || 'Work completed successfully.');
            formData.append('csrf_token', window.CSRF_TOKEN || '');

            fetch(`${window.BASE_URL || ''}/api/tasks`, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Swal.fire({
                        title: 'Submitted for Approval!',
                        text: res.message,
                        icon: 'success',
                        timer: 2000
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Failed to submit task: ' + err.message, 'error');
            });
        }
    });
}
