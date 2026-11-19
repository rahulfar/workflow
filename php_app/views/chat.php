<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$user = getCurrentUser();
$activeRoleView = $GLOBALS['activeRoleView'] ?? (hasRole('admin') ? 'admin' : (hasRole(['manager', 'product_manager']) ? 'manager' : 'employee'));
?>

<div class="main-wrapper theme-<?= $activeRoleView ?>">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="content-body p-3 p-md-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark mb-1 font-heading"><i class="fas fa-comments text-info me-2"></i>Internal Workspace Chat & AI</h3>
                <p class="text-secondary small mb-0">Role-secured department channels, 1-on-1 private direct messaging & 24/7 AI workplace intelligence.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2 rounded-pill fw-bold">
                    <i class="fas fa-shield-alt me-1"></i><?= sanitize($user['role_title'] ?? 'Staff') ?> Scope
                </span>
                <span class="badge bg-info-subtle text-info border border-info px-3 py-2 rounded-pill fw-bold">
                    <i class="fas fa-building me-1"></i><?= sanitize($user['department_name'] ?? 'Department') ?>
                </span>
            </div>
        </div>

        <!-- Slack / Teams Style Split-Screen Chat Interface -->
        <div class="glass-card p-0 overflow-hidden shadow-lg border-0" style="min-height: 720px; display: flex;">
            <div class="row g-0 w-100">
                <!-- ========================================================= -->
                <!-- LEFT PANEL: CHANNELS & DIRECT CONTACTS ROSTER             -->
                <!-- ========================================================= -->
                <div class="col-lg-4 col-xl-3 border-end bg-light bg-opacity-50 d-flex flex-column" style="max-height: 720px;">
                    <!-- Roster Header & Search -->
                    <div class="p-3 border-bottom bg-white">
                        <div class="position-relative mb-2">
                            <i class="fas fa-search position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                            <input type="text" id="chatSearchInput" class="form-control form-control-sm ps-5 rounded-pill" placeholder="Search contacts & channels..." onkeyup="filterContacts()">
                        </div>
                        <div class="d-flex justify-content-between align-items-center fs-8 text-muted fw-bold text-uppercase">
                            <span>Workspace Directory</span>
                            <span class="pulse-dot-indicator"></span>
                        </div>
                    </div>

                    <!-- Channels & Contacts List -->
                    <div class="flex-grow-1 overflow-y-auto p-2" id="contactsRosterContainer">
                        <!-- AI Assistant Category -->
                        <div class="menu-category text-uppercase fs-9 fw-bold text-muted px-2 py-1 mb-1">
                            <i class="fas fa-robot text-primary me-1"></i> AI Intelligence
                        </div>
                        <div class="mb-3">
                            <div class="nav-link-custom py-2.5 px-3 rounded-3 cursor-pointer d-flex justify-content-between align-items-center bg-primary bg-opacity-10 border border-primary-subtle active" id="aiAssistantNavItem" onclick="openAIChat()">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="p-2 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                                        <i class="fas fa-robot fs-6"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-primary fs-7 d-block">AI Smart Assistant</span>
                                        <small class="text-muted fs-9">24/7 Workspace Agent</small>
                                    </div>
                                </div>
                                <span class="badge bg-primary text-white rounded-pill fs-9 px-2">AI Bot</span>
                            </div>
                        </div>

                        <!-- Department Channels Category -->
                        <div class="menu-category text-uppercase fs-9 fw-bold text-muted px-2 py-1 mb-1">
                            <i class="fas fa-hashtag me-1"></i> Department Channels
                        </div>
                        <div id="departmentChannelsList" class="d-flex flex-column gap-1 mb-3">
                            <div class="text-muted small px-3 py-2">Loading channels...</div>
                        </div>

                        <!-- Direct Messages Category -->
                        <div class="menu-category text-uppercase fs-9 fw-bold text-muted px-2 py-1 mb-1">
                            <i class="fas fa-user-friends me-1"></i> Direct Messages
                        </div>
                        <div id="directContactsList" class="d-flex flex-column gap-1">
                            <div class="text-muted small px-3 py-2">Loading contacts...</div>
                        </div>
                    </div>
                </div>

                <!-- ========================================================= -->
                <!-- RIGHT MAIN PANEL: ACTIVE CHAT STREAM & ACTION BAR        -->
                <!-- ========================================================= -->
                <div class="col-lg-8 col-xl-9 d-flex flex-column bg-white" style="max-height: 720px;">
                    <!-- Chat Room Header -->
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-white" id="activeChatHeader">
                        <div class="d-flex align-items-center gap-3">
                            <div class="position-relative">
                                <img src="https://ui-avatars.com/api/?name=AI&background=6366f1&color=fff" id="chatHeaderAvatar" class="rounded-circle border" width="44" height="44" alt="Avatar">
                                <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle p-1" id="chatHeaderStatusDot"></span>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0 fs-6" id="chatHeaderTitle">🤖 AI Smart Assistant</h5>
                                <small class="text-muted fs-8" id="chatHeaderSub">24/7 Intelligent Workspace Agent</small>
                            </div>
                        </div>

                        <div class="d-flex gap-2 align-items-center">
                            <button class="btn btn-sm btn-outline-secondary rounded-circle" title="Search Messages" onclick="toggleMessageSearch()">
                                <i class="fas fa-search"></i>
                            </button>
                            <span class="badge bg-success-subtle text-success border border-success fs-8">
                                <i class="fas fa-shield-alt me-1"></i>Encrypted
                            </span>
                        </div>
                    </div>

                    <!-- Message Search Bar (Hidden by default) -->
                    <div class="p-2 bg-light border-bottom" id="msgSearchContainer" style="display:none;">
                        <input type="text" id="msgQueryInput" class="form-control form-control-sm" placeholder="Filter message history..." onkeyup="filterMessages()">
                    </div>

                    <!-- Message Stream Area -->
                    <div class="flex-grow-1 p-3 overflow-y-auto bg-light bg-opacity-25 d-flex flex-column gap-3" id="chatMessagesStream" style="min-height: 480px;">
                        <!-- Dynamically populated -->
                    </div>

                    <!-- Chat Input Footer -->
                    <div class="p-3 border-top bg-white">
                        <form id="sendMessageForm" class="d-flex flex-column gap-2" enctype="multipart/form-data">
                            <input type="hidden" id="activeReceiverId" name="receiver_id" value="">
                            <input type="hidden" id="activeDepartmentId" name="department_id" value="">

                            <!-- Quick Action / Emoji Selector Pills -->
                            <div class="d-flex gap-1 overflow-x-auto pb-1" id="quickPillsBar">
                                <!-- Loaded dynamically based on chat type -->
                            </div>

                            <div class="d-flex gap-2 align-items-center">
                                <!-- Attachment Button -->
                                <label class="btn btn-outline-secondary btn-icon rounded-circle mb-0" id="chatAttachmentBtn" title="Attach file or photo" style="display: none;">
                                    <i class="fas fa-paperclip"></i>
                                    <input type="file" name="attachment" id="chatAttachmentInput" style="display:none;" onchange="showAttachmentName(this)">
                                </label>

                                <span class="badge bg-info-subtle text-info border fs-9" id="attachmentNameBadge" style="display:none;"></span>

                                <!-- Text Input Area -->
                                <textarea name="message" id="chatMessageInput" class="form-control form-control-sm rounded-3" rows="1" placeholder="Ask AI assistant or send message..." style="resize:none;" onkeydown="handleChatSubmitKey(event)"></textarea>

                                <!-- Send Button -->
                                <button type="button" id="chatSendBtn" onclick="handleSendMessage()" class="btn btn-primary px-3 py-2 rounded-3 fw-bold">
                                    <i class="fas fa-paper-plane me-1"></i>Send
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
let currentTargetType = 'ai'; // 'ai', 'channel', or 'user'
let currentTargetId = 'ai';
let currentTargetName = 'WorkFlow AI';
let aiChatHistory = [];
let pollInterval = null;

const myId = <?= (int)$user['id'] ?>;
const myName = "<?= sanitize($user['name'] ?? 'User') ?>";

document.addEventListener('DOMContentLoaded', function() {
    loadChatContacts();
    openAIChat();
    pollInterval = setInterval(pollCurrentChat, 3000);
});

// 1. Load Contacts and Channels Roster
function loadChatContacts() {
    fetch('<?= BASE_URL ?>/api/chat?action=get_contacts')
    .then(res => res.json())
    .then(res => {
        if (!res.success) return;
        const depts = res.data.departments || [];
        const contacts = res.data.contacts || [];

        // Render Department Channels
        const deptList = document.getElementById('departmentChannelsList');
        if (depts.length > 0) {
            deptList.innerHTML = depts.map(d => `
                <div class="nav-link-custom py-2 px-3 rounded-3 cursor-pointer d-flex justify-content-between align-items-center channel-nav-item ${currentTargetType === 'channel' && currentTargetId == d.id ? 'active bg-primary-subtle' : ''}" id="channelItem_${d.id}" onclick="openChannelChat(${d.id}, '${d.name.replace(/'/g, "\\'")}')">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-hashtag text-primary"></i>
                        <span class="fw-semibold text-dark fs-7">${d.name} Channel</span>
                    </div>
                    <span class="badge bg-light text-muted border fs-9">${d.code}</span>
                </div>
            `).join('');
        } else {
            deptList.innerHTML = '<div class="text-muted small px-3">No channels available</div>';
        }

        // Render Direct Messages Contacts
        const contactList = document.getElementById('directContactsList');
        if (contacts.length > 0) {
            contactList.innerHTML = contacts.map(c => `
                <div class="nav-link-custom py-2 px-3 rounded-3 cursor-pointer d-flex align-items-center gap-2.5 user-nav-item ${currentTargetType === 'user' && currentTargetId == c.id ? 'active bg-primary-subtle' : ''}" id="userItem_${c.id}" onclick="openUserChat(${c.id}, '${c.name.replace(/'/g, "\\'")}', '${c.designation || 'Staff'}')">
                    <div class="position-relative">
                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(c.name)}&background=4f46e5&color=fff" class="rounded-circle border" width="36" height="36">
                        <span class="position-absolute bottom-0 end-0 p-1 rounded-circle ${c.status === 'active' ? 'bg-success' : 'bg-secondary'} border border-white"></span>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-bold text-dark fs-7 text-truncate">${c.name}</div>
                        <small class="text-muted fs-8 d-block text-truncate">${c.designation || 'Staff'} (${c.dept_name || 'Department'})</small>
                    </div>
                </div>
            `).join('');
        } else {
            contactList.innerHTML = '<div class="text-muted small px-3">No contacts available</div>';
        }
    })
    .catch(err => console.error("Error loading chat contacts:", err));
}

function clearActiveRosterHighlight() {
    document.querySelectorAll('.nav-link-custom').forEach(el => el.classList.remove('active', 'bg-primary-subtle'));
}

// 2. Open AI Chat Assistant
function openAIChat() {
    currentTargetType = 'ai';
    currentTargetId = 'ai';
    currentTargetName = 'WorkFlow AI';
    clearActiveRosterHighlight();
    const aiNav = document.getElementById('aiAssistantNavItem');
    if (aiNav) aiNav.classList.add('active', 'bg-primary-subtle');

    document.getElementById('activeDepartmentId').value = '';
    document.getElementById('activeReceiverId').value = '';
    document.getElementById('chatHeaderTitle').textContent = '🤖 WorkFlow AI Assistant';
    document.getElementById('chatHeaderSub').textContent = '24/7 Intelligent Workspace Agent (Ask questions, shifts, tasks, leaves)';
    document.getElementById('chatHeaderAvatar').src = 'https://ui-avatars.com/api/?name=AI&background=6366f1&color=fff';
    document.getElementById('chatAttachmentBtn').style.display = 'none';
    document.getElementById('chatMessageInput').placeholder = 'Ask AI assistant anything...';

    const pills = document.getElementById('quickPillsBar');
    pills.innerHTML = `
        <button type="button" class="btn btn-sm btn-outline-primary py-0.5 px-2.5 rounded-pill fs-8" onclick="sendQuickAIPrompt('Show my pending tasks')">📊 My Tasks</button>
        <button type="button" class="btn btn-sm btn-outline-success py-0.5 px-2.5 rounded-pill fs-8" onclick="sendQuickAIPrompt('What is my shift status & worked hours today?')">⏱️ Today's Shift & Hours</button>
        <button type="button" class="btn btn-sm btn-outline-warning text-dark py-0.5 px-2.5 rounded-pill fs-8" onclick="sendQuickAIPrompt('When is lunch timing?')">🍽️ Lunch Timing</button>
        <button type="button" class="btn btn-sm btn-outline-danger py-0.5 px-2.5 rounded-pill fs-8" onclick="sendQuickAIPrompt('Show overdue tasks')">⚠️ Overdue Tasks</button>
        <button type="button" class="btn btn-sm btn-outline-info py-0.5 px-2.5 rounded-pill fs-8" onclick="sendQuickAIPrompt('Check my paid leave quota and balance')">🏖️ Leave Balance</button>
    `;

    renderAIChatStream();
}

function renderAIChatStream() {
    const stream = document.getElementById('chatMessagesStream');
    if (aiChatHistory.length === 0) {
        stream.innerHTML = `
            <div class="p-4 bg-white rounded-3 border shadow-sm mb-3">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-3 bg-primary text-white rounded-circle fs-4">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Hello, ${myName}! 👋 I am WorkFlow AI</h5>
                        <small class="text-secondary">Your intelligent workspace assistant for real-time analytics, tasks, shifts & policies.</small>
                    </div>
                </div>
                <p class="text-muted small mb-3">How can I assist you right now? Pick a suggestion below or type your query:</p>
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-sm btn-light border fw-semibold" onclick="sendQuickAIPrompt('Show my pending tasks')"><i class="fas fa-tasks text-primary me-1"></i>Show Pending Tasks</button>
                    <button class="btn btn-sm btn-light border fw-semibold" onclick="sendQuickAIPrompt('What is my shift work duration today?')"><i class="fas fa-clock text-success me-1"></i>Shift & Worked Hours</button>
                    <button class="btn btn-sm btn-light border fw-semibold" onclick="sendQuickAIPrompt('What is the office lunch timing?')"><i class="fas fa-utensils text-warning me-1"></i>Lunch Timing (2PM-3PM)</button>
                    <button class="btn btn-sm btn-light border fw-semibold" onclick="sendQuickAIPrompt('Check my paid leave balance')"><i class="fas fa-plane-departure text-info me-1"></i>My Leave Balance</button>
                    <button class="btn btn-sm btn-light border fw-semibold" onclick="sendQuickAIPrompt('Show top performers leaderboard')"><i class="fas fa-trophy text-warning me-1"></i>Top Performers</button>
                </div>
            </div>
        `;
        return;
    }

    stream.innerHTML = aiChatHistory.map(msg => {
        const isMe = (msg.sender === 'user');
        return `
            <div class="d-flex gap-2.5 mb-2 ${isMe ? 'justify-content-end' : ''}">
                ${!isMe ? `<div class="rounded-circle p-2 bg-primary text-white shadow-sm mt-1 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;"><i class="fas fa-robot fs-7"></i></div>` : ''}
                <div style="max-width: 75%;">
                    <div class="d-flex align-items-center gap-2 mb-1 ${isMe ? 'justify-content-end' : ''}">
                        <small class="fw-bold text-dark fs-8">${isMe ? 'You' : 'WorkFlow AI Assistant'}</small>
                        <small class="text-muted fs-9">${msg.time}</small>
                    </div>
                    <div class="p-3 shadow-sm ${isMe ? 'bg-primary text-white' : 'bg-white text-dark border'}" style="border-radius: ${isMe ? '1rem 1rem 0 1rem' : '1rem 1rem 1rem 0'} !important; font-family: 'Outfit', 'Inter', sans-serif; font-size: 0.875rem; line-height: 1.6;">
                        <div>${formatMarkdownText(msg.text)}</div>
                    </div>
                </div>
            </div>
        `;
    }).join('');

    stream.scrollTop = stream.scrollHeight;
}

function sendQuickAIPrompt(text) {
    document.getElementById('chatMessageInput').value = text;
    handleSendMessage();
}

// 3. Open Department Channel
function openChannelChat(deptId, deptName) {
    currentTargetType = 'channel';
    currentTargetId = parseInt(deptId);
    currentTargetName = deptName;
    clearActiveRosterHighlight();
    const nav = document.getElementById(`channelItem_${deptId}`);
    if (nav) nav.classList.add('active', 'bg-primary-subtle');

    document.getElementById('activeDepartmentId').value = deptId;
    document.getElementById('activeReceiverId').value = '';
    document.getElementById('chatHeaderTitle').textContent = `# ${deptName} Department Channel`;
    document.getElementById('chatHeaderSub').textContent = `Official ${deptName} team discussion channel`;
    document.getElementById('chatHeaderAvatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(deptName)}&background=10b981&color=fff`;
    document.getElementById('chatAttachmentBtn').style.display = 'inline-block';
    document.getElementById('chatMessageInput').placeholder = `Message #${deptName} channel...`;

    // Clear stream and show loading indicator
    document.getElementById('chatMessagesStream').innerHTML = `
        <div class="my-auto text-center py-5 text-muted">
            <i class="fas fa-spinner fa-spin fs-2 text-primary mb-2"></i>
            <p class="small mb-0">Loading #${deptName} channel messages...</p>
        </div>`;

    resetEmojiPills();
    loadChatMessages();
}

// 4. Open Direct 1-on-1 User Chat
function openUserChat(userId, userName, designation) {
    currentTargetType = 'user';
    currentTargetId = parseInt(userId);
    currentTargetName = userName;
    clearActiveRosterHighlight();
    const nav = document.getElementById(`userItem_${userId}`);
    if (nav) nav.classList.add('active', 'bg-primary-subtle');

    document.getElementById('activeReceiverId').value = userId;
    document.getElementById('activeDepartmentId').value = '';
    document.getElementById('chatHeaderTitle').textContent = userName;
    document.getElementById('chatHeaderSub').textContent = `Direct Chat • ${designation}`;
    document.getElementById('chatHeaderAvatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(userName)}&background=4f46e5&color=fff`;
    document.getElementById('chatAttachmentBtn').style.display = 'inline-block';
    document.getElementById('chatMessageInput').placeholder = `Send private message to ${userName}...`;

    // Clear stream and show loading indicator
    document.getElementById('chatMessagesStream').innerHTML = `
        <div class="my-auto text-center py-5 text-muted">
            <i class="fas fa-spinner fa-spin fs-2 text-primary mb-2"></i>
            <p class="small mb-0">Loading direct conversation with ${userName}...</p>
        </div>`;

    resetEmojiPills();
    loadChatMessages();
}

function resetEmojiPills() {
    const pills = document.getElementById('quickPillsBar');
    pills.innerHTML = `
        <button type="button" class="btn btn-sm btn-light border py-0 px-2 fs-7" onclick="appendEmoji('👍')">👍</button>
        <button type="button" class="btn btn-sm btn-light border py-0 px-2 fs-7" onclick="appendEmoji('🚀')">🚀</button>
        <button type="button" class="btn btn-sm btn-light border py-0 px-2 fs-7" onclick="appendEmoji('✅')">✅</button>
        <button type="button" class="btn btn-sm btn-light border py-0 px-2 fs-7" onclick="appendEmoji('🔥')">🔥</button>
        <button type="button" class="btn btn-sm btn-light border py-0 px-2 fs-7" onclick="appendEmoji('🎯')">🎯</button>
        <button type="button" class="btn btn-sm btn-light border py-0 px-2 fs-7" onclick="appendEmoji('👏')">👏</button>
    `;
}

// 5. Load Real-Time Messages with Target Isolation
function loadChatMessages() {
    if (currentTargetType === 'ai') return;
    
    const requestType = currentTargetType;
    const requestId = currentTargetId;
    let url = '<?= BASE_URL ?>/api/chat?action=get_messages';
    
    if (requestType === 'channel') {
        url += `&department_id=${requestId}`;
    } else if (requestType === 'user') {
        url += `&receiver_id=${requestId}`;
    } else {
        return;
    }

    fetch(url)
    .then(res => res.json())
    .then(res => {
        // Discard response if user already switched to another chat
        if (currentTargetType !== requestType || currentTargetId !== requestId) return;

        const stream = document.getElementById('chatMessagesStream');

        if (!res.success) {
            stream.innerHTML = `
                <div class="my-auto text-center py-5 text-danger">
                    <i class="fas fa-exclamation-circle fs-2 mb-2"></i>
                    <p class="small mb-0">${res.message || 'Failed to load messages'}</p>
                </div>`;
            return;
        }

        const msgs = res.data || [];

        if (msgs.length === 0) {
            const emptyText = (requestType === 'channel')
                ? `No messages in this channel yet. Be the first to start the discussion!`
                : `No private messages yet. Say hello to start conversation!`;

            stream.innerHTML = `
                <div class="my-auto text-center py-5 text-muted">
                    <i class="fas fa-comment-dots fs-1 mb-2 text-secondary"></i>
                    <h6 class="fw-bold text-dark">No messages yet</h6>
                    <p class="small mb-0 text-secondary">${emptyText}</p>
                </div>`;
            return;
        }

        stream.innerHTML = msgs.map(m => {
            const isMe = (parseInt(m.sender_id) === myId);
            let fileAttachment = '';
            if (m.file_path) {
                const isImg = m.file_type && m.file_type.startsWith('image/');
                if (isImg) {
                    fileAttachment = `<div class="mt-2"><a href="${BASE_URL}${m.file_path}" target="_blank"><img src="${BASE_URL}${m.file_path}" class="img-fluid rounded border shadow-sm" style="max-height: 200px;"></a></div>`;
                } else {
                    fileAttachment = `<div class="mt-2"><a href="${BASE_URL}${m.file_path}" target="_blank" class="btn btn-sm btn-light border"><i class="fas fa-paperclip me-1"></i>${m.file_name || 'Download File'}</a></div>`;
                }
            }

            return `
                <div class="d-flex gap-2.5 mb-2 ${isMe ? 'justify-content-end' : ''}">
                    ${!isMe ? `<img src="https://ui-avatars.com/api/?name=${encodeURIComponent(m.sender_name)}&background=4f46e5&color=fff" class="rounded-circle mt-1 shadow-sm" width="36" height="36">` : ''}
                    <div style="max-width: 70%;">
                        <div class="d-flex align-items-center gap-2 mb-1 ${isMe ? 'justify-content-end' : ''}">
                            <small class="fw-bold text-dark fs-8">${m.sender_name}</small>
                            <small class="text-muted fs-9">${new Date(m.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</small>
                        </div>
                        <div class="p-3 shadow-sm ${isMe ? 'bg-primary text-white' : 'bg-white text-dark border'}" style="border-radius: ${isMe ? '1rem 1rem 0 1rem' : '1rem 1rem 1rem 0'} !important; font-family: 'Outfit', 'Inter', sans-serif; font-size: 0.875rem; line-height: 1.6;">
                            <div>${formatMarkdownText(m.message)}</div>
                            ${fileAttachment}
                        </div>
                    </div>
                </div>`;
        }).join('');

        stream.scrollTop = stream.scrollHeight;
    })
    .catch(err => {
        if (currentTargetType !== requestType || currentTargetId !== requestId) return;
        console.error("Error loading chat messages:", err);
    });
}

function pollCurrentChat() {
    if (currentTargetType === 'channel' || currentTargetType === 'user') {
        loadChatMessages();
    }
}

// 6. Handle Send Message (Direct, Channel, or AI)
function handleSendMessage() {
    const input = document.getElementById('chatMessageInput');
    const msgText = input.value.trim();
    const hasFile = document.getElementById('chatAttachmentInput').files[0];
    if (!msgText && !hasFile) return;

    if (currentTargetType === 'ai') {
        // AI Chat Flow
        const timeNow = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        aiChatHistory.push({ sender: 'user', text: msgText, time: timeNow });
        input.value = '';
        renderAIChatStream();

        // Typing indicator
        const stream = document.getElementById('chatMessagesStream');
        const typingEl = document.createElement('div');
        typingEl.id = 'aiTypingIndicator';
        typingEl.className = 'd-flex gap-2.5 mb-2';
        typingEl.innerHTML = `
            <div class="rounded-circle p-2 bg-primary text-white shadow-sm mt-1 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;"><i class="fas fa-robot fs-7"></i></div>
            <div class="p-3 bg-white text-muted border rounded-3 shadow-sm small">
                <i class="fas fa-spinner fa-spin me-2 text-primary"></i>WorkFlow AI is analyzing...
            </div>
        `;
        stream.appendChild(typingEl);
        stream.scrollTop = stream.scrollHeight;

        fetch('<?= BASE_URL ?>/api/ai', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `query=${encodeURIComponent(msgText)}`
        })
        .then(res => res.json())
        .then(res => {
            const typing = document.getElementById('aiTypingIndicator');
            if (typing) typing.remove();

            const replyText = res.success ? (res.reply || 'No response generated') : (res.message || 'AI error');
            aiChatHistory.push({ sender: 'ai', text: replyText, time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) });
            renderAIChatStream();
        })
        .catch(err => {
            const typing = document.getElementById('aiTypingIndicator');
            if (typing) typing.remove();
            aiChatHistory.push({ sender: 'ai', text: '⚠️ Connection error. Please try again.', time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) });
            renderAIChatStream();
        });

    } else {
        // Channel or Direct User Chat Flow
        const formData = new FormData(document.getElementById('sendMessageForm'));
        formData.append('action', 'send_message');

        // Strictly enforce target IDs
        if (currentTargetType === 'channel') {
            formData.set('department_id', currentTargetId);
            formData.set('receiver_id', '');
        } else if (currentTargetType === 'user') {
            formData.set('receiver_id', currentTargetId);
            formData.set('department_id', '');
        }

        fetch('<?= BASE_URL ?>/api/chat', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                document.getElementById('chatMessageInput').value = '';
                document.getElementById('chatAttachmentInput').value = '';
                document.getElementById('attachmentNameBadge').style.display = 'none';
                loadChatMessages();
            } else {
                Swal.fire({ icon: 'error', title: 'Message Error', text: res.message });
            }
        })
        .catch(err => {
            Swal.fire({ icon: 'error', title: 'Network Error', text: 'Failed to send message.' });
        });
    }
}

function appendEmoji(emoji) {
    const input = document.getElementById('chatMessageInput');
    input.value += emoji;
    input.focus();
}

function showAttachmentName(input) {
    const badge = document.getElementById('attachmentNameBadge');
    if (input.files && input.files[0]) {
        badge.textContent = input.files[0].name;
        badge.style.display = 'inline-block';
    }
}

function handleChatSubmitKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        handleSendMessage();
    }
}

function filterContacts() {
    const query = document.getElementById('chatSearchInput').value.toLowerCase();
    document.querySelectorAll('.channel-nav-item, .user-nav-item').forEach(el => {
        const text = el.innerText.toLowerCase();
        el.style.display = text.includes(query) ? 'flex' : 'none';
    });
}

function toggleMessageSearch() {
    const box = document.getElementById('msgSearchContainer');
    box.style.display = (box.style.display === 'none') ? 'block' : 'none';
    if (box.style.display === 'block') {
        document.getElementById('msgQueryInput').focus();
    }
}

function filterMessages() {
    const query = document.getElementById('msgQueryInput').value.toLowerCase();
    const stream = document.getElementById('chatMessagesStream');
    stream.querySelectorAll('.d-flex.gap-2.5').forEach(el => {
        const text = el.innerText.toLowerCase();
        el.style.display = text.includes(query) ? 'flex' : 'none';
    });
}

function formatMarkdownText(text) {
    if (!text) return '';
    return escapeHtml(text)
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/`([^`]+)`/g, '<code class="bg-light px-1 py-0.5 rounded text-danger border font-monospace">$1</code>')
        .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" class="text-primary fw-semibold text-decoration-underline">$1</a>')
        .replace(/\n/g, '<br>');
}

function escapeHtml(str) {
    return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}
</script>
