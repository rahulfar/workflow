<?php
$pageTitle = "Help & Support";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$user = requireLogin();
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <div class="mb-4">
            <h3 class="fw-bold text-dark mb-1"><i class="fas fa-question-circle text-info me-2"></i>Help & Support Center</h3>
            <p class="text-secondary small mb-0">Frequently asked questions, system usage documentation, and technical support ticket submittal.</p>
        </div>

        <div class="row g-4">
            <!-- Left Column: FAQ Accordion -->
            <div class="col-lg-8">
                <div class="glass-card mb-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-book me-2 text-primary"></i>Frequently Asked Questions</h5>
                    
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item mb-2 border rounded">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                    How does the Live Task Timer work?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small text-secondary">
                                    Click <strong>Start Timer</strong> on any assigned task card to initiate working hours. The timer automatically ticks and syncs with the server every 10 seconds. You can type live work notes inside the floating widget. Starting a new task timer automatically pauses any existing active timer.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-2 border rounded">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                    What are the task permissions for regular employees?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small text-secondary">
                                    Regular employees can view assigned tasks, start/pause/resume/complete timers, attach work notes, submit daily progress logs, and reject tasks with a reason. Task creation, editing, deletion, and reassignment can only be performed by Admins and Managers.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border rounded">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                    How do I clock in and out for my shift?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small text-secondary">
                                    Click the <strong>Clock In</strong> button located in the top navbar at the start of your shift. At the end of your working day, click <strong>Clock Out</strong> to finalize your daily attendance entry.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Submit Support Ticket -->
            <div class="col-lg-4">
                <div class="glass-card">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-life-ring me-2 text-warning"></i>Contact System Support</h5>
                    <form id="supportTicketForm">
                        <div class="mb-3">
                            <label class="form-label text-secondary small">Issue Subject</label>
                            <input type="text" class="form-control" placeholder="e.g., Timer synchronization inquiry" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary small">Priority</label>
                            <select class="form-select">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary small">Message Details</label>
                            <textarea class="form-control" rows="4" placeholder="Describe your issue or question..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Submit Ticket</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    document.getElementById('supportTicketForm').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            icon: 'success',
            title: 'Support Ticket Submitted',
            text: 'Our IT administration team will respond shortly.',
            confirmButtonColor: '#4f46e5'
        }).then(() => this.reset());
    });
</script>
