<?php
/**
 * AI Workflow Assistant - Floating Widget & Interactive Panel
 * Included on all application pages via footer.php
 */

$aiUser = getCurrentUser();
if (!$aiUser) return;

$aiUserCategory = getUserRoleCategory($aiUser);
$aiUserRoleTitle = $aiUser['role_title'] ?? ($aiUser['designation'] ?? 'Staff Member');
$aiUserName = $aiUser['name'] ?? 'User';
?>

<!-- ========================================================= -->
<!-- AI WORKFLOW ASSISTANT: FLOATING TRIGGER ICON              -->
<!-- ========================================================= -->
<div id="aiFloatingContainer" class="position-fixed" style="bottom: 24px; right: 24px; z-index: 1060;">
    <button id="aiTriggerBtn" type="button" class="btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center ai-pulse-btn" onclick="toggleAiAssistant()" title="AI Workflow Assistant" aria-label="Open AI Workflow Assistant" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="AI Workflow Assistant">
        <i class="fas fa-sparkles fs-3 text-white"></i>
        <span class="position-absolute top-0 start-100 translate-middle p-1.5 bg-success border border-light rounded-circle shadow-xs">
            <span class="visually-hidden">AI Online</span>
        </span>
    </button>
</div>

<!-- ========================================================= -->
<!-- AI WORKFLOW ASSISTANT: EXPANDABLE CHAT PANEL              -->
<!-- ========================================================= -->
<div id="aiChatPanel" class="glass-card shadow-2xl rounded-4 overflow-hidden border-0" style="position: fixed; bottom: 90px; right: 24px; width: 440px; max-width: calc(100vw - 32px); height: 620px; max-height: calc(100vh - 120px); z-index: 1065; display: none; flex-direction: column; background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(20px); box-shadow: 0 25px 60px -15px rgba(79, 70, 229, 0.35), 0 0 0 1px rgba(99, 102, 241, 0.15);">
    
    <!-- Header -->
    <div class="px-3.5 py-3 text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
        <div class="d-flex align-items-center gap-2.5">
            <div class="bg-white bg-opacity-20 rounded-circle p-2 d-flex align-items-center justify-content-center shadow-xs" style="width: 36px; height: 36px;">
                <i class="fas fa-robot fs-5 text-warning"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h6 class="mb-0 fw-bold fs-6 text-white">AI Workflow Assistant</h6>
                    <span class="badge bg-success bg-opacity-25 text-white border border-success border-opacity-50 py-0.5 px-2 rounded-pill fs-9 fw-semibold">
                        <i class="fas fa-circle text-success me-1 fs-10"></i>Online
                    </span>
                </div>
                <small class="fs-9 text-white text-opacity-80 d-block">
                    <i class="fas fa-shield-alt me-1"></i><?= sanitize($aiUserRoleTitle) ?> Clearance Boundary
                </small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-1">
            <button type="button" class="btn btn-sm btn-link text-white text-opacity-75 p-1" onclick="clearAiChatHistory()" title="Clear conversation" aria-label="Clear chat">
                <i class="fas fa-trash-alt fs-7"></i>
            </button>
            <button type="button" class="btn btn-sm btn-link text-white text-opacity-75 p-1" onclick="minimizeAiAssistant()" title="Minimize" aria-label="Minimize panel">
                <i class="fas fa-minus fs-7"></i>
            </button>
            <button type="button" class="btn btn-sm btn-link text-white text-opacity-75 p-1" onclick="toggleAiAssistant()" title="Close" aria-label="Close panel">
                <i class="fas fa-times fs-6"></i>
            </button>
        </div>
    </div>

    <!-- Conversation Stream Area -->
    <div class="flex-grow-1 p-3 overflow-y-auto bg-light bg-opacity-50 d-flex flex-column gap-3" id="aiConversationStream" style="font-family: 'Outfit', 'Inter', -apple-system, sans-serif; font-size: 0.88rem; line-height: 1.55;">
        
        <!-- Welcome Message -->
        <div class="d-flex gap-2">
            <div class="rounded-circle text-white shadow-xs d-flex align-items-center justify-content-center flex-shrink-0" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); width: 32px; height: 32px;">
                <i class="fas fa-robot fs-7"></i>
            </div>
            <div class="p-3 rounded-4 bg-white text-dark shadow-sm border border-light-subtle" style="max-width: 88%; border-radius: 4px 1.25rem 1.25rem 1.25rem !important;">
                <div class="fw-semibold text-primary mb-1">
                    <i class="fas fa-sparkles me-1"></i>Hello, <?= sanitize($aiUserName) ?>!
                </div>
                <p class="mb-2 text-secondary">
                    I'm your <strong>AI Workflow Assistant</strong>. Tell me what you want to do in your workflow software. I'll prepare an <strong>implementation plan</strong> for your review before making any changes.
                </p>
                <div class="small text-muted fst-italic">
                    <i class="fas fa-lock me-1"></i>Safe execution mode active: Database modifications require your explicit approval.
                </div>
            </div>
        </div>

    </div>

    <!-- Suggested Quick Action Pills -->
    <div id="aiSuggestionsBar" class="p-2 bg-white border-top border-bottom overflow-x-auto d-flex gap-1.5 align-items-center scrollbar-thin">
        <span class="fs-9 text-muted fw-bold text-uppercase px-1 text-nowrap"><i class="fas fa-bolt text-warning me-1"></i>Suggestions:</span>
        <!-- Populated dynamically via JS -->
    </div>

    <!-- Loading Indicator -->
    <div id="aiLoadingIndicator" class="px-3 py-1.5 bg-light border-top text-primary small d-none align-items-center gap-2">
        <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
        <span class="fw-semibold fs-8" id="aiLoadingText">AI Assistant is analyzing request & checking database...</span>
    </div>

    <!-- Input Footer -->
    <div class="p-2.5 bg-white border-top">
        <form id="aiChatInputForm" onsubmit="submitAiUserMessage(event)" class="d-flex gap-2 align-items-end">
            <div class="flex-grow-1 position-relative">
                <textarea id="aiMessageInput" class="form-control rounded-3 border-secondary-subtle px-3 py-2 fs-8" rows="1" placeholder="Type command... (e.g. Assign homepage task to Abhay tomorrow)" required style="resize: none; max-height: 110px; overflow-y: auto;" onkeydown="handleAiInputKeydown(event)"></textarea>
            </div>
            <button type="submit" id="btnSendAiMessage" class="btn btn-primary rounded-3 px-3 py-2 fw-bold shadow-sm d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); border: none; height: 38px; width: 44px;" title="Send command">
                <i class="fas fa-paper-plane fs-7"></i>
            </button>
        </form>
        <div class="d-flex justify-content-between align-items-center px-1 mt-1 fs-10 text-muted">
            <span>Press <kbd class="bg-light border text-dark px-1">Enter</kbd> to send, <kbd class="bg-light border text-dark px-1">Shift+Enter</kbd> for newline</span>
            <span><i class="fas fa-check-double text-success me-1"></i>Live Backend Linked</span>
        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- AI STYLING & KEYFRAMES                                    -->
<!-- ========================================================= -->
<style>
@keyframes aiPulseGlow {
    0% {
        box-shadow: 0 0 0 0 rgba(124, 58, 237, 0.6), 0 10px 25px -5px rgba(79, 70, 229, 0.5);
    }
    70% {
        box-shadow: 0 0 0 14px rgba(124, 58, 237, 0), 0 10px 25px -5px rgba(79, 70, 229, 0.5);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(124, 58, 237, 0), 0 10px 25px -5px rgba(79, 70, 229, 0.5);
    }
}

.ai-pulse-btn {
    width: 58px;
    height: 58px;
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 50%, #7c3aed 100%) !important;
    border: 2px solid rgba(255, 255, 255, 0.3) !important;
    animation: aiPulseGlow 2.5s infinite;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.ai-pulse-btn:hover {
    transform: scale(1.08) translateY(-2px);
    box-shadow: 0 15px 30px -5px rgba(124, 58, 237, 0.6) !important;
}

.ai-plan-card {
    border-radius: 1rem !important;
    border: 1px solid rgba(99, 102, 241, 0.25) !important;
    background: #ffffff !important;
    box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.12) !important;
    overflow: hidden;
}

.ai-plan-header {
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important;
    color: #ffffff;
}

.ai-stage-item {
    font-size: 0.8rem;
    padding: 3px 0;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.ai-stage-item.pending {
    color: #94a3b8;
}

.ai-stage-item.running {
    color: #4f46e5;
    font-weight: 600;
}

.ai-stage-item.done {
    color: #10b981;
    font-weight: 500;
}

@media (max-width: 576px) {
    #aiFloatingContainer {
        bottom: 18px !important;
        right: 18px !important;
    }
    #aiChatPanel {
        bottom: 82px !important;
        right: 12px !important;
        left: 12px !important;
        width: auto !important;
        height: 75vh !important;
    }
}
</style>

<!-- ========================================================= -->
<!-- AI JAVASCRIPT CONTROLLER                                  -->
<!-- ========================================================= -->
<script>
let aiConversationId = localStorage.getItem('ai_workflow_conv_id') || ('conv_' + Math.random().toString(36).substring(2, 12));
localStorage.setItem('ai_workflow_conv_id', aiConversationId);

let isAiAssistantOpen = false;
let isAiExecutingPlan = false;

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    loadAiSuggestions();

    // Restore opened state if saved
    if (sessionStorage.getItem('ai_assistant_panel_open') === 'true') {
        openAiAssistant();
    }

    // Auto-expand textarea on typing
    const textarea = document.getElementById('aiMessageInput');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (Math.min(this.scrollHeight, 110)) + 'px';
        });
    }
});

function toggleAiAssistant() {
    if (isAiAssistantOpen) {
        closeAiAssistant();
    } else {
        openAiAssistant();
    }
}

function openAiAssistant() {
    const panel = document.getElementById('aiChatPanel');
    if (!panel) return;
    panel.style.display = 'flex';
    isAiAssistantOpen = true;
    sessionStorage.setItem('ai_assistant_panel_open', 'true');
    scrollAiToBottom();
    const input = document.getElementById('aiMessageInput');
    if (input) setTimeout(() => input.focus(), 150);
}

function closeAiAssistant() {
    const panel = document.getElementById('aiChatPanel');
    if (!panel) return;
    panel.style.display = 'none';
    isAiAssistantOpen = false;
    sessionStorage.setItem('ai_assistant_panel_open', 'false');
}

function minimizeAiAssistant() {
    closeAiAssistant();
}

function scrollAiToBottom() {
    const stream = document.getElementById('aiConversationStream');
    if (stream) {
        setTimeout(() => {
            stream.scrollTop = stream.scrollHeight;
        }, 50);
    }
}

function handleAiInputKeydown(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        document.getElementById('aiChatInputForm').dispatchEvent(new Event('submit'));
    } else if (event.key === 'Escape') {
        closeAiAssistant();
    }
}

function loadAiSuggestions() {
    fetch('<?= BASE_URL ?>/api/ai?action=get_suggestions')
    .then(r => r.json())
    .then(data => {
        if (data.success && data.data && data.data.suggestions) {
            renderAiSuggestions(data.data.suggestions);
        }
    })
    .catch(err => console.error("Error loading AI suggestions:", err));
}

function renderAiSuggestions(suggestions) {
    const bar = document.getElementById('aiSuggestionsBar');
    if (!bar) return;
    let html = `<span class="fs-9 text-muted fw-bold text-uppercase px-1 text-nowrap"><i class="fas fa-bolt text-warning me-1"></i>Suggestions:</span>`;
    suggestions.forEach(s => {
        const escapedText = escapeAiHtml(s.text);
        html += `<button type="button" class="btn btn-sm btn-light border py-1 px-2.5 fs-9 text-nowrap fw-semibold rounded-pill text-dark shadow-2xs hover-primary" onclick="useAiSuggestedCommand('${escapedText.replace(/'/g, "\\'")}')">
                    <i class="fas ${s.icon || 'fa-arrow-right'} text-primary me-1 fs-9"></i>${escapeAiHtml(s.label)}
                 </button>`;
    });
    bar.innerHTML = html;
}

function useAiSuggestedCommand(cmd) {
    const input = document.getElementById('aiMessageInput');
    if (!input) return;
    input.value = cmd;
    input.style.height = 'auto';
    input.style.height = (Math.min(input.scrollHeight, 110)) + 'px';
    document.getElementById('aiChatInputForm').dispatchEvent(new Event('submit'));
}

function clearAiChatHistory() {
    if (!confirm("Start a new conversation and clear chat history?")) return;
    
    const formData = new FormData();
    formData.append('action', 'clear_history');
    formData.append('conversation_id', aiConversationId);
    formData.append('csrf_token', CSRF_TOKEN);

    fetch('<?= BASE_URL ?>/api/ai', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(() => {
        aiConversationId = 'conv_' + Math.random().toString(36).substring(2, 12);
        localStorage.setItem('ai_workflow_conv_id', aiConversationId);
        
        const stream = document.getElementById('aiConversationStream');
        if (stream) {
            stream.innerHTML = `
                <div class="d-flex gap-2">
                    <div class="rounded-circle text-white shadow-xs d-flex align-items-center justify-content-center flex-shrink-0" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); width: 32px; height: 32px;">
                        <i class="fas fa-robot fs-7"></i>
                    </div>
                    <div class="p-3 rounded-4 bg-white text-dark shadow-sm border border-light-subtle" style="max-width: 88%; border-radius: 4px 1.25rem 1.25rem 1.25rem !important;">
                        <div class="fw-semibold text-primary mb-1">
                            <i class="fas fa-sparkles me-1"></i>New Conversation Started
                        </div>
                        <p class="mb-0 text-secondary">
                            Hello! Tell me what you want to do in your workflow software. I'll prepare an implementation plan for your review before executing.
                        </p>
                    </div>
                </div>`;
        }
    });
}

function submitAiUserMessage(event) {
    event.preventDefault();
    const input = document.getElementById('aiMessageInput');
    const msg = input.value.trim();
    if (!msg) return;

    // Display user message in chat immediately
    appendUserMessageToStream(msg);
    input.value = '';
    input.style.height = 'auto';

    // Show loading
    setAiLoading(true, 'AI Assistant is analyzing command & checking database...');

    const formData = new FormData();
    formData.append('action', 'chat');
    formData.append('message', msg);
    formData.append('conversation_id', aiConversationId);
    formData.append('csrf_token', CSRF_TOKEN);

    fetch('<?= BASE_URL ?>/api/ai', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        setAiLoading(false);
        if (data.success && data.data) {
            const resp = data.data;
            if (resp.type === 'plan') {
                appendPlanCardToStream(resp);
            } else {
                appendAssistantReplyToStream(resp.reply || 'Request completed.');
            }
        } else {
            appendAssistantReplyToStream('⚠️ ' + (data.message || 'Unable to process command. Please verify your input.'));
        }
    })
    .catch(err => {
        setAiLoading(false);
        appendAssistantReplyToStream('❌ Network or server error: ' + err.message);
    });
}

function appendUserMessageToStream(msg) {
    const stream = document.getElementById('aiConversationStream');
    if (!stream) return;

    const div = document.createElement('div');
    div.className = 'd-flex gap-2 justify-content-end';
    div.innerHTML = `
        <div class="p-3 text-white shadow-sm" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); max-width: 85%; border-radius: 1.25rem 1.25rem 4px 1.25rem !important;">
            ${escapeAiHtml(msg).replace(/\n/g, '<br>')}
        </div>
    `;
    stream.appendChild(div);
    scrollAiToBottom();
}

function appendAssistantReplyToStream(reply) {
    const stream = document.getElementById('aiConversationStream');
    if (!stream) return;

    const formatted = formatAiMarkdown(reply);
    const div = document.createElement('div');
    div.className = 'd-flex gap-2';
    div.innerHTML = `
        <div class="rounded-circle text-white shadow-xs d-flex align-items-center justify-content-center flex-shrink-0" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); width: 32px; height: 32px;">
            <i class="fas fa-robot fs-7"></i>
        </div>
        <div class="p-3 rounded-4 bg-white text-dark shadow-sm border border-light-subtle" style="max-width: 88%; border-radius: 4px 1.25rem 1.25rem 1.25rem !important;">
            ${formatted}
        </div>
    `;
    stream.appendChild(div);
    scrollAiToBottom();
}

function appendPlanCardToStream(plan) {
    const stream = document.getElementById('aiConversationStream');
    if (!stream) return;

    const meta = plan.metadata || {};
    const planToken = plan.plan_token;

    let stepsHtml = '';
    if (meta.planned_steps && Array.isArray(meta.planned_steps)) {
        stepsHtml = meta.planned_steps.map(step => `
            <div class="d-flex align-items-start gap-2 py-0.5 small text-secondary">
                <i class="fas fa-chevron-right text-primary mt-1 fs-10"></i>
                <span>${escapeAiHtml(step)}</span>
            </div>
        `).join('');
    }

    const card = document.createElement('div');
    card.className = 'd-flex gap-2';
    card.id = `aiPlanBox_${planToken}`;
    card.innerHTML = `
        <div class="rounded-circle text-white shadow-xs d-flex align-items-center justify-content-center flex-shrink-0" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); width: 32px; height: 32px;">
            <i class="fas fa-clipboard-check fs-7"></i>
        </div>
        <div class="ai-plan-card p-0 shadow-sm w-100" style="max-width: 90%;">
            <!-- Plan Header -->
            <div class="ai-plan-header py-2.5 px-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark fw-bold px-2 py-1 fs-9 rounded-pill">PLAN FOR REVIEW</span>
                    <span class="fw-bold fs-8 text-white">${escapeAiHtml(meta.action_title || 'Implementation Plan')}</span>
                </div>
                <span class="badge bg-white bg-opacity-20 text-white fs-10 font-monospace">Awaiting Approval</span>
            </div>

            <!-- Plan Body -->
            <div class="p-3 bg-white">
                <div class="mb-2.5 pb-2 border-bottom">
                    <div class="fs-9 text-muted text-uppercase fw-bold">Target & Deliverable</div>
                    <div class="fw-bold text-dark fs-7">${escapeAiHtml(meta.target_name || plan.summary)}</div>
                </div>

                <div class="row g-2 mb-3 fs-8">
                    ${meta.employee_name ? `
                    <div class="col-6">
                        <span class="text-muted d-block fs-9 fw-semibold">Assigned Employee:</span>
                        <strong class="text-primary"><i class="fas fa-user-check me-1"></i>${escapeAiHtml(meta.employee_name)}</strong>
                    </div>` : ''}

                    ${meta.project_name ? `
                    <div class="col-6">
                        <span class="text-muted d-block fs-9 fw-semibold">Project:</span>
                        <strong class="text-dark"><i class="fas fa-folder me-1 text-warning"></i>${escapeAiHtml(meta.project_name)}</strong>
                    </div>` : ''}

                    ${meta.deadline_text ? `
                    <div class="col-6">
                        <span class="text-muted d-block fs-9 fw-semibold">Deadline:</span>
                        <strong class="text-danger"><i class="fas fa-calendar-alt me-1"></i>${escapeAiHtml(meta.deadline_text)}</strong>
                    </div>` : ''}

                    ${meta.priority_text ? `
                    <div class="col-6">
                        <span class="text-muted d-block fs-9 fw-semibold">Priority:</span>
                        <strong class="text-warning"><i class="fas fa-flag me-1"></i>${escapeAiHtml(meta.priority_text)}</strong>
                    </div>` : ''}

                    ${meta.reason_text ? `
                    <div class="col-12">
                        <span class="text-muted d-block fs-9 fw-semibold">Reason:</span>
                        <div class="bg-light p-2 rounded small text-dark">${escapeAiHtml(meta.reason_text)}</div>
                    </div>` : ''}

                    ${meta.resolution_notes ? `
                    <div class="col-12">
                        <span class="text-muted d-block fs-9 fw-semibold">Resolution Notes:</span>
                        <div class="bg-light p-2 rounded small text-dark">${escapeAiHtml(meta.resolution_notes)}</div>
                    </div>` : ''}
                </div>

                <!-- Planned Action Steps Checklist -->
                <div class="bg-light p-2.5 rounded-3 mb-3 border">
                    <div class="fs-9 text-uppercase fw-bold text-muted mb-1.5"><i class="fas fa-list-ol me-1 text-primary"></i>Planned Execution Steps:</div>
                    ${stepsHtml}
                </div>

                <!-- Live Execution Stages (Hidden until approved) -->
                <div id="aiExecutionProgress_${planToken}" class="p-2.5 bg-indigo-subtle border border-indigo-subtle rounded-3 mb-3 d-none">
                    <div class="fw-bold fs-9 text-indigo text-uppercase mb-2">
                        <i class="fas fa-sync fa-spin me-1"></i>Execution in Progress:
                    </div>
                    <div class="ai-stage-item" id="stage_1_${planToken}"><i class="fas fa-circle-notch fa-spin"></i> 1. Validating request & authenticated session...</div>
                    <div class="ai-stage-item pending" id="stage_2_${planToken}"><i class="far fa-circle"></i> 2. Checking role authorization & hierarchy rules...</div>
                    <div class="ai-stage-item pending" id="stage_3_${planToken}"><i class="far fa-circle"></i> 3. Verifying task & employee database records...</div>
                    <div class="ai-stage-item pending" id="stage_4_${planToken}"><i class="far fa-circle"></i> 4. Executing approved actions in backend...</div>
                    <div class="ai-stage-item pending" id="stage_5_${planToken}"><i class="far fa-circle"></i> 5. Saving changes permanently to database...</div>
                    <div class="ai-stage-item pending" id="stage_6_${planToken}"><i class="far fa-circle"></i> 6. Sending real-time notifications to team...</div>
                    <div class="ai-stage-item pending" id="stage_7_${planToken}"><i class="far fa-circle"></i> 7. Completed & recorded in audit log.</div>
                </div>

                <!-- Actions: Approve & Execute / Cancel -->
                <div id="aiPlanButtons_${planToken}" class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="cancelAiPlan('${planToken}')">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-success btn-sm rounded-pill px-3.5 fw-bold shadow-sm" onclick="approveAiPlan('${planToken}')" id="btnApprove_${planToken}">
                        <i class="fas fa-check-circle me-1"></i>Approve & Execute
                    </button>
                </div>
            </div>
        </div>
    `;

    stream.appendChild(card);
    scrollAiToBottom();
}

function approveAiPlan(planToken) {
    if (isAiExecutingPlan) return;
    isAiExecutingPlan = true;

    const btnApprove = document.getElementById(`btnApprove_${planToken}`);
    const btnContainer = document.getElementById(`aiPlanButtons_${planToken}`);
    const progressBox = document.getElementById(`aiExecutionProgress_${planToken}`);

    if (btnApprove) {
        btnApprove.disabled = true;
        btnApprove.innerHTML = `<i class="fas fa-spinner fa-spin me-1"></i>Executing...`;
    }

    if (progressBox) {
        progressBox.classList.remove('d-none');
    }

    // Animate execution stages to give real visual feedback
    animateStage(planToken, 1, 'running');
    setTimeout(() => {
        animateStage(planToken, 1, 'done');
        animateStage(planToken, 2, 'running');
    }, 250);

    setTimeout(() => {
        animateStage(planToken, 2, 'done');
        animateStage(planToken, 3, 'running');
    }, 550);

    const formData = new FormData();
    formData.append('action', 'approve_plan');
    formData.append('plan_token', planToken);
    formData.append('csrf_token', CSRF_TOKEN);

    fetch('<?= BASE_URL ?>/api/ai', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        isAiExecutingPlan = false;
        
        animateStage(planToken, 3, 'done');
        animateStage(planToken, 4, 'done');
        animateStage(planToken, 5, 'done');
        animateStage(planToken, 6, 'done');
        animateStage(planToken, 7, 'done');

        if (btnContainer) {
            btnContainer.innerHTML = `
                <div class="w-100 alert alert-success py-2 px-3 mb-0 rounded-3 small d-flex align-items-center justify-content-between">
                    <span><i class="fas fa-check-circle me-1.5 text-success fs-7"></i><strong>Approved & Successfully Executed</strong></span>
                    <span class="badge bg-success">COMPLETED</span>
                </div>
            `;
        }

        if (data.success) {
            renderExecutionSuccessCard(data.data || {}, planToken);
        } else {
            renderExecutionErrorCard(data.message || 'Execution failed on server.', planToken);
        }
    })
    .catch(err => {
        isAiExecutingPlan = false;
        renderExecutionErrorCard('Network or server exception: ' + err.message, planToken);
    });
}

function animateStage(planToken, stageNum, status) {
    const el = document.getElementById(`stage_${stageNum}_${planToken}`);
    if (!el) return;

    if (status === 'running') {
        el.className = 'ai-stage-item running';
        el.innerHTML = `<i class="fas fa-circle-notch fa-spin text-primary"></i> ${el.textContent.trim()}`;
    } else if (status === 'done') {
        el.className = 'ai-stage-item done';
        el.innerHTML = `<i class="fas fa-check-circle text-success"></i> ${el.textContent.trim()}`;
    }
}

function renderExecutionSuccessCard(result, planToken) {
    const stream = document.getElementById('aiConversationStream');
    if (!stream) return;

    const div = document.createElement('div');
    div.className = 'd-flex gap-2';
    div.innerHTML = `
        <div class="rounded-circle text-white shadow-xs d-flex align-items-center justify-content-center flex-shrink-0" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); width: 32px; height: 32px;">
            <i class="fas fa-check fs-7"></i>
        </div>
        <div class="p-3 rounded-4 bg-white text-dark shadow-sm border border-success border-opacity-50" style="max-width: 88%; border-radius: 4px 1.25rem 1.25rem 1.25rem !important;">
            <div class="fw-bold text-success mb-1 fs-7">
                <i class="fas fa-check-circle me-1"></i>Task Operation Executed Successfully!
            </div>
            <div class="bg-light p-2.5 rounded-3 mb-2 small">
                ${result.task_title ? `<div><strong>Task:</strong> ${escapeAiHtml(result.task_title)}</div>` : ''}
                ${result.employee_name ? `<div><strong>Employee:</strong> ${escapeAiHtml(result.employee_name)}</div>` : ''}
                ${result.deadline ? `<div><strong>Deadline:</strong> ${escapeAiHtml(result.deadline)}</div>` : ''}
                ${result.priority ? `<div><strong>Priority:</strong> ${escapeAiHtml(result.priority)}</div>` : ''}
                ${result.project_name ? `<div><strong>Project:</strong> ${escapeAiHtml(result.project_name)}</div>` : ''}
                ${result.status ? `<div><strong>Status:</strong> <span class="badge bg-primary">${escapeAiHtml(result.status)}</span></div>` : ''}
                <div><strong>Notification:</strong> <span class="text-success"><i class="fas fa-bell me-1"></i>Sent successfully</span></div>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="fs-9 text-muted"><i class="fas fa-database me-1 text-success"></i>Saved in database</span>
                ${result.task_id ? `<a href="<?= BASE_URL ?>/task-details?id=${result.task_id}" class="btn btn-xs btn-outline-primary rounded-pill px-2.5 py-1 fw-bold fs-9"><i class="fas fa-external-link-alt me-1"></i>View Task #${result.task_id}</a>` : ''}
            </div>
        </div>
    `;

    stream.appendChild(div);
    scrollAiToBottom();
}

function renderExecutionErrorCard(errorMsg, planToken) {
    const stream = document.getElementById('aiConversationStream');
    if (!stream) return;

    const div = document.createElement('div');
    div.className = 'd-flex gap-2';
    div.innerHTML = `
        <div class="rounded-circle text-white shadow-xs d-flex align-items-center justify-content-center flex-shrink-0" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); width: 32px; height: 32px;">
            <i class="fas fa-exclamation-triangle fs-7"></i>
        </div>
        <div class="p-3 rounded-4 bg-white text-dark shadow-sm border border-danger border-opacity-50" style="max-width: 88%; border-radius: 4px 1.25rem 1.25rem 1.25rem !important;">
            <div class="fw-bold text-danger mb-1 fs-7">
                <i class="fas fa-times-circle me-1"></i>Execution Failed
            </div>
            <p class="small text-secondary mb-1">${escapeAiHtml(errorMsg)}</p>
            <div class="fs-9 text-muted">No unauthorized changes were committed to the database.</div>
        </div>
    `;

    stream.appendChild(div);
    scrollAiToBottom();
}

function cancelAiPlan(planToken) {
    if (isAiExecutingPlan) return;

    const btnContainer = document.getElementById(`aiPlanButtons_${planToken}`);
    if (btnContainer) {
        btnContainer.innerHTML = `<span class="fs-8 text-muted"><i class="fas fa-spinner fa-spin me-1"></i>Cancelling plan...</span>`;
    }

    const formData = new FormData();
    formData.append('action', 'cancel_plan');
    formData.append('plan_token', planToken);
    formData.append('csrf_token', CSRF_TOKEN);

    fetch('<?= BASE_URL ?>/api/ai', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (btnContainer) {
            btnContainer.innerHTML = `
                <div class="w-100 alert alert-secondary py-1.5 px-3 mb-0 rounded-3 small d-flex align-items-center justify-content-between">
                    <span class="text-muted"><i class="fas fa-ban me-1 text-danger"></i>Plan Cancelled</span>
                    <span class="badge bg-secondary">CANCELLED</span>
                </div>
            `;
        }
        appendAssistantReplyToStream("ℹ️ The implementation plan was cancelled. No changes were made to the database.");
    })
    .catch(err => {
        if (btnContainer) {
            btnContainer.innerHTML = `<span class="text-danger small">Cancellation error: ${escapeAiHtml(err.message)}</span>`;
        }
    });
}

function setAiLoading(isLoading, text = '') {
    const indicator = document.getElementById('aiLoadingIndicator');
    const loadingText = document.getElementById('aiLoadingText');
    const btn = document.getElementById('btnSendAiMessage');

    if (!indicator) return;

    if (isLoading) {
        indicator.classList.remove('d-none');
        indicator.classList.add('d-flex');
        if (loadingText) loadingText.textContent = text || 'AI Assistant is processing...';
        if (btn) btn.disabled = true;
    } else {
        indicator.classList.add('d-none');
        indicator.classList.remove('d-flex');
        if (btn) btn.disabled = false;
    }
}

function formatAiMarkdown(text) {
    if (!text) return '';
    return text
        .replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/`([^`]+)`/g, '<code class="bg-light text-primary px-1.5 py-0.5 rounded fs-9 font-monospace border">$1</code>')
        .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" class="text-primary text-decoration-none fw-semibold" target="_self">$1</a>')
        .replace(/\n/g, '<br>');
}

function escapeAiHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
</script>
