/**
 * WorkFlow Pro - Kanban Board Controller
 * Handles drag-and-drop workflow status transitions and task details navigation.
 */

document.addEventListener('DOMContentLoaded', () => {
    initKanbanBoard();
});

function initKanbanBoard() {
    const board = document.querySelector('.kanban-board');
    if (!board) return;

    let draggedCard = null;
    let originalColumn = null;
    let isDragging = false;
    let dragStartTime = 0;

    const cards = board.querySelectorAll('.kanban-card');
    const columns = board.querySelectorAll('.kanban-cards');

    // Setup cards for dragging and clicking
    cards.forEach(card => {
        card.setAttribute('draggable', 'true');

        card.addEventListener('dragstart', (e) => {
            isDragging = true;
            dragStartTime = Date.now();
            draggedCard = card;
            originalColumn = card.closest('.kanban-cards');
            card.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', card.dataset.taskId);
        });

        card.addEventListener('dragend', () => {
            card.classList.remove('dragging');
            columns.forEach(col => col.classList.remove('drag-over'));
            setTimeout(() => {
                isDragging = false;
                draggedCard = null;
                originalColumn = null;
            }, 100);
        });

        // Navigate to task details on click if not dragging
        card.addEventListener('click', (e) => {
            if (isDragging || (Date.now() - dragStartTime < 200 && dragStartTime > 0)) {
                return;
            }
            const taskId = card.dataset.taskId;
            if (taskId) {
                const baseUrl = window.BASE_URL || '';
                window.location.href = `${baseUrl}/task-details?id=${taskId}`;
            }
        });
    });

    // Setup drop zones
    columns.forEach(column => {
        column.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            if (!column.classList.contains('drag-over')) {
                column.classList.add('drag-over');
            }
        });

        column.addEventListener('dragenter', (e) => {
            e.preventDefault();
            column.classList.add('drag-over');
        });

        column.addEventListener('dragleave', (e) => {
            if (e.relatedTarget && !column.contains(e.relatedTarget)) {
                column.classList.remove('drag-over');
            }
        });

        column.addEventListener('drop', async (e) => {
            e.preventDefault();
            column.classList.remove('drag-over');

            if (!draggedCard) return;

            const targetStatus = column.dataset.status;
            const sourceColumn = originalColumn || draggedCard.closest('.kanban-cards');
            const sourceStatus = sourceColumn ? sourceColumn.dataset.status : null;
            const taskId = draggedCard.dataset.taskId;

            if (!targetStatus || !taskId) return;
            if (sourceStatus === targetStatus) return; // Dropped in the same column

            // Optimistically move card
            column.appendChild(draggedCard);
            updateColumnBadges();

            // Submit status change to API
            const baseUrl = window.BASE_URL || '';
            const formData = new FormData();
            formData.append('action', 'update_status');
            formData.append('task_id', taskId);
            formData.append('status', targetStatus);

            try {
                const response = await fetch(`${baseUrl}/api/tasks`, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (!result.success) {
                    throw new Error(result.message || 'Failed to update task status');
                }

                // Show toast notification
                if (typeof Swal !== 'undefined') {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    });
                    const statusTitles = {
                        'pending': 'To Do / Pending',
                        'running': 'In Progress',
                        'in_review': 'Awaiting Approval',
                        'pending_approval': 'Awaiting Approval',
                        'completed': 'Done'
                    };
                    Toast.fire({
                        icon: 'success',
                        title: `Task #${taskId} moved to ${statusTitles[targetStatus] || targetStatus}`
                    });
                }
            } catch (err) {
                // Revert card back to original column
                if (sourceColumn && draggedCard) {
                    sourceColumn.appendChild(draggedCard);
                    updateColumnBadges();
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Status Update Failed',
                        text: err.message || 'Could not move the task card.',
                        confirmButtonColor: '#4f46e5'
                    });
                } else {
                    alert('Error: ' + err.message);
                }
            }
        });
    });

    function updateColumnBadges() {
        columns.forEach(col => {
            const count = col.querySelectorAll('.kanban-card').length;
            const colWrapper = col.closest('.kanban-column');
            if (colWrapper) {
                const badge = colWrapper.querySelector('.kanban-header .badge');
                if (badge) {
                    badge.textContent = count;
                }
            }
        });
    }
}
