<!-- Modern PEI Team Chat Drawer -->
@php
    $targetProfile = $profile ?? ($peiProfile ?? ($activity->peiProfile ?? null));
    if (!$targetProfile && isset($activity) && !empty($activity->pei_profile_id)) {
        $targetProfile = \App\Admin\Planificacion\Pei\PeiProfile::find($activity->pei_profile_id);
    }
@endphp

@if(isset($targetProfile) && $targetProfile && $targetProfile->id)
    @php
        $chatPeiProfileId = $targetProfile->id;
    @endphp

    <!-- Floating Chat Trigger Button -->
    <div id="peiChatTrigger" class="pei-chat-trigger shadow-lg" title="Chat del Equipo PEI">
        <i class="fas fa-comments fa-lg"></i>
        <span id="peiChatUnreadBadge" class="badge badge-danger badge-counter pei-chat-badge" style="display: none;">0</span>
    </div>

    <!-- Slide-in Chat Drawer -->
    <div id="peiChatDrawer" class="pei-chat-drawer">
        <!-- Header -->
        <div class="pei-chat-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="pei-chat-avatar mr-2">
                    <i class="fas fa-users text-white"></i>
                </div>
                <div>
                    <h6 class="mb-0 font-weight-bold text-white text-truncate" style="max-width: 220px;">
                        {!! strip_tags($targetProfile->name) !!}
                    </h6>
                    <small class="text-white-50" id="peiChatPresenceText">
                        <i class="fas fa-circle text-success mr-1" style="font-size: 8px;"></i> Chat de Equipo
                    </small>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-link text-white p-1 mr-2" id="togglePeiChatParticipants" title="Ver Miembros">
                    <i class="fas fa-user-friends"></i>
                </button>
                <button type="button" class="btn btn-sm btn-link text-white p-1" id="closePeiChatDrawer" title="Cerrar Chat">
                    <i class="fas fa-times fa-lg"></i>
                </button>
            </div>
        </div>

        <!-- Participants Panel (Hidden by default) -->
        <div id="peiChatParticipantsPanel" class="pei-chat-participants-panel" style="display: none;">
            <div class="p-2 bg-light border-bottom font-weight-bold text-xs text-uppercase text-secondary">
                Miembros del Grupo de Trabajo
            </div>
            <div id="peiChatParticipantsList" class="p-2" style="max-height: 150px; overflow-y: auto;">
                <div class="text-muted text-center small py-2">Cargando miembros...</div>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="peiChatMessagesBody" class="pei-chat-body">
            <div class="text-center text-muted py-5" id="peiChatLoading">
                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                <div class="small mt-2">Cargando mensajes...</div>
            </div>
            <div id="peiChatMessagesList" class="d-flex flex-column"></div>
        </div>

        <!-- Reply Preview Bar -->
        <div id="peiChatReplyBar" class="pei-chat-reply-bar" style="display: none;">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-truncate mr-2 small">
                    <span class="font-weight-bold" id="peiChatReplyUser"></span>: 
                    <span id="peiChatReplyText" class="text-muted"></span>
                </div>
                <button type="button" class="btn btn-xs text-danger" id="cancelPeiChatReply">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Attachments Preview Bar -->
        <div id="peiChatAttachmentPreview" class="pei-chat-attachment-preview" style="display: none;">
            <div id="peiChatAttachmentList" class="d-flex flex-wrap gap-1 p-2"></div>
        </div>

        <!-- Private Recipient Bar -->
        <div id="peiChatPrivateBar" class="bg-warning text-dark px-3 py-1 text-xs d-flex justify-content-between align-items-center" style="display: none;">
            <span><i class="fas fa-lock mr-1"></i> Mensaje privado para: <strong id="peiChatRecipientName"></strong></span>
            <button type="button" class="btn btn-link btn-xs text-dark p-0" id="clearPeiChatRecipient" title="Volver al canal público">
                <i class="fas fa-times-circle"></i>
            </button>
        </div>

        <!-- Input Footer -->
        <div class="pei-chat-footer">
            <form id="peiChatForm" class="d-flex align-items-center" enctype="multipart/form-data">
                <label for="peiChatFileInput" class="btn btn-light btn-circle btn-sm mb-0 mr-2 text-secondary" title="Adjuntar Archivo">
                    <i class="fas fa-paperclip"></i>
                    <input type="file" id="peiChatFileInput" multiple hidden>
                </label>

                <textarea id="peiChatMessageInput" class="form-control form-control-sm border-0 bg-light rounded-lg mr-2" 
                          placeholder="Escribe un mensaje al equipo..." rows="1" style="resize: none;"></textarea>

                <button type="submit" class="btn btn-primary btn-circle btn-sm shadow-sm" id="sendPeiChatBtn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Styles -->
    <style>
        .pei-chat-trigger {
            position: fixed;
            bottom: 25px;
            right: 25px;
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1050;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .pei-chat-trigger:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(78, 115, 223, 0.4);
        }
        .pei-chat-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            border: 2px solid #fff;
            padding: 4px 6px;
            font-size: 10px;
        }
        .pei-chat-drawer {
            position: fixed;
            top: 0;
            right: -420px;
            width: 380px;
            height: 100vh;
            background: #ffffff;
            box-shadow: -5px 0 25px rgba(0, 0, 0, 0.15);
            z-index: 1060;
            display: flex;
            flex-direction: column;
            transition: right 0.3s ease-in-out;
        }
        .pei-chat-drawer.open {
            right: 0;
        }
        .pei-chat-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            padding: 15px;
            color: white;
        }
        .pei-chat-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pei-chat-body {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background-color: #f8f9fc;
        }
        .pei-chat-reply-bar {
            background: #eef2f7;
            padding: 8px 12px;
            border-left: 3px solid #4e73df;
            font-size: 12px;
        }
        .pei-chat-attachment-preview {
            background: #f1f3f9;
            border-top: 1px solid #e3e6f0;
        }
        .pei-chat-footer {
            padding: 12px;
            background: #ffffff;
            border-top: 1px solid #e3e6f0;
        }
        .msg-bubble-container {
            margin-bottom: 12px;
            display: flex;
            flex-direction: column;
        }
        .msg-bubble-container.mine {
            align-items: flex-end;
        }
        .msg-bubble-container.other {
            align-items: flex-start;
        }
        .msg-bubble {
            max-width: 82%;
            padding: 10px 14px;
            font-size: 13px;
            line-height: 1.4;
            word-wrap: break-word;
        }
        .msg-bubble-container.mine .msg-bubble {
            background: linear-gradient(135deg, #4e73df, #224abe);
            color: #ffffff;
            border-radius: 16px 16px 2px 16px;
        }
        .msg-bubble-container.other .msg-bubble {
            background: #ffffff;
            color: #2e384d;
            border: 1px solid #e3e6f0;
            border-radius: 16px 16px 16px 2px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        }
        .msg-meta {
            font-size: 10px;
            margin-top: 3px;
            color: #858796;
        }
        .msg-sender-name {
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 2px;
            color: #4e73df;
        }
        .msg-reply-ref {
            background: rgba(0,0,0,0.05);
            border-left: 2px solid #4e73df;
            padding: 2px 6px;
            margin-bottom: 4px;
            font-size: 11px;
            border-radius: 3px;
        }
        .msg-attachment-item {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 4px 8px;
            border-radius: 6px;
            margin-top: 4px;
            font-size: 11px;
            color: inherit;
            text-decoration: none !important;
        }
        .msg-bubble-container.other .msg-attachment-item {
            background: #eaecf4;
            color: #4e73df;
        }
    </style>

    <!-- Script Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const peiProfileId = "{{ $chatPeiProfileId }}";
            const trigger = document.getElementById('peiChatTrigger');
            const drawer = document.getElementById('peiChatDrawer');
            const closeBtn = document.getElementById('closePeiChatDrawer');
            const messagesList = document.getElementById('peiChatMessagesList');
            const loading = document.getElementById('peiChatLoading');
            const form = document.getElementById('peiChatForm');
            const input = document.getElementById('peiChatMessageInput');
            const fileInput = document.getElementById('peiChatFileInput');
            const unreadBadge = document.getElementById('peiChatUnreadBadge');
            const participantsToggle = document.getElementById('togglePeiChatParticipants');
            const participantsPanel = document.getElementById('peiChatParticipantsPanel');
            const participantsList = document.getElementById('peiChatParticipantsList');

            let lastMessageTime = null;
            let currentReplyId = null;
            let pollInterval = null;
            let activeRecipientId = null;
            let activeRecipientName = null;

            const privateBar = document.getElementById('peiChatPrivateBar');
            const recipientNameSpan = document.getElementById('peiChatRecipientName');
            const clearRecipientBtn = document.getElementById('clearPeiChatRecipient');

            if (clearRecipientBtn) {
                clearRecipientBtn.addEventListener('click', function () {
                    clearPrivateRecipient();
                });
            }

            window.setPrivateRecipient = function(id, name) {
                activeRecipientId = id;
                activeRecipientName = name;
                recipientNameSpan.textContent = name;
                privateBar.style.display = 'flex';
                input.placeholder = `Escribir mensaje privado a ${name}...`;
                participantsPanel.style.display = 'none';
            };

            function clearPrivateRecipient() {
                activeRecipientId = null;
                activeRecipientName = null;
                privateBar.style.display = 'none';
                input.placeholder = 'Escribe un mensaje al equipo...';
            }

            // Toggle drawer
            trigger.addEventListener('click', function () {
                drawer.classList.add('open');
                fetchMessages();
                markRead();
                startPolling();
            });

            closeBtn.addEventListener('click', function () {
                drawer.classList.remove('open');
                stopPolling();
            });

            // Participants toggle
            participantsToggle.addEventListener('click', function () {
                if (participantsPanel.style.display === 'none') {
                    participantsPanel.style.display = 'block';
                } else {
                    participantsPanel.style.display = 'none';
                }
            });

            // Sonido de notificación sintetizado (Web Audio API)
            function playMessageChime() {
                try {
                    const AudioCtx = window.AudioContext || window.webkitAudioContext;
                    if (!AudioCtx) return;
                    const ctx = new AudioCtx();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();

                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(587.33, ctx.currentTime); // Re (D5)
                    osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.12); // La (A5)

                    gain.gain.setValueAtTime(0.2, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.35);

                    osc.connect(gain);
                    gain.connect(ctx.destination);

                    osc.start();
                    osc.stop(ctx.currentTime + 0.35);
                } catch (e) {}
            }

            // Fetch messages from server
            function fetchMessages(isPolling = false) {
                let url = `{{ url('pei-profiles') }}/${peiProfileId}/chat/messages`;
                if (isPolling && lastMessageTime) {
                    url += `?since=${encodeURIComponent(lastMessageTime)}`;
                }

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    loading.style.display = 'none';
                    let hasNewOtherMessage = false;

                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(msg => {
                            // Check if message already rendered
                            if (!document.getElementById(`msg-${msg.id}`)) {
                                renderMessage(msg);
                                lastMessageTime = msg.created_at;
                                if (!msg.is_mine) {
                                    hasNewOtherMessage = true;
                                }
                            }
                        });
                        scrollToBottom();

                        if (isPolling && hasNewOtherMessage) {
                            playMessageChime();
                        }
                    }

                    if (data.participants) {
                        renderParticipants(data.participants);
                    }
                })
                .catch(err => console.error('Error fetching chat messages:', err));
            }

            function renderMessage(msg) {
                const container = document.createElement('div');
                container.id = `msg-${msg.id}`;
                container.className = `msg-bubble-container ${msg.is_mine ? 'mine' : 'other'}`;

                let html = '';
                if (!msg.is_mine) {
                    html += `<div class="msg-sender-name">${msg.user_name}`;
                    if (msg.is_private) {
                        html += ` <span class="badge badge-warning text-dark ml-1" style="font-size:9px;"><i class="fas fa-lock mr-1"></i>Privado</span>`;
                    }
                    html += `</div>`;
                } else if (msg.is_private) {
                    html += `<div class="text-right text-xs font-weight-bold text-warning mb-1" style="font-size:10px;"><i class="fas fa-lock mr-1"></i>Privado para ${msg.recipient_name || 'Usuario'}</div>`;
                }

                if (msg.parent) {
                    html += `<div class="msg-reply-ref"><strong>${msg.parent.user_name}</strong>: ${msg.parent.message}</div>`;
                }

                html += `<div class="msg-bubble">${msg.message}`;

                if (msg.attachments && msg.attachments.length > 0) {
                    html += `<div class="mt-1">`;
                    msg.attachments.forEach(att => {
                        html += `<a href="${att.url}" target="_blank" class="msg-attachment-item">
                                    <i class="fas fa-file-download mr-1"></i>${att.name} (${att.size})
                                 </a><br>`;
                    });
                    html += `</div>`;
                }

                html += `</div>`;
                html += `<div class="msg-meta">${msg.time_ago}</div>`;

                container.innerHTML = html;
                messagesList.appendChild(container);
            }

            function renderParticipants(list) {
                if (!list || list.length === 0) {
                    participantsList.innerHTML = '<div class="text-muted text-center small py-2">Sin otros miembros</div>';
                    return;
                }
                let html = '<div class="text-muted text-xs mb-2 font-weight-bold">Integrantes (Clic para mensaje privado):</div>';
                list.forEach(u => {
                    const isSel = activeRecipientId === u.id;
                    const escapedName = u.name.replace(/'/g, "\\'");
                    html += `<div class="d-flex justify-content-between align-items-center mb-1 text-xs p-2 rounded border ${isSel ? 'bg-warning text-dark font-weight-bold border-warning' : 'bg-light'}" style="cursor:pointer;" onclick="setPrivateRecipient('${u.id}', '${escapedName}')">
                                <span><i class="fas fa-user-circle ${isSel ? 'text-dark' : 'text-primary'} mr-1"></i> ${u.name}</span>
                                <span class="badge ${isSel ? 'badge-dark' : 'badge-primary'}" style="font-size:9px;">
                                    ${isSel ? 'Activo' : 'Privado'}
                                </span>
                             </div>`;
                });
                participantsList.innerHTML = html;
            }

            function scrollToBottom() {
                const body = document.getElementById('peiChatMessagesBody');
                body.scrollTop = body.scrollHeight;
            }

            // Enviar mensaje con tecla Enter (Shift+Enter para salto de línea)
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                }
            });

            // Send message
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const text = input.value.trim();
                const files = fileInput.files;

                if (!text && files.length === 0) return;

                const formData = new FormData();
                formData.append('message', text);
                if (currentReplyId) {
                    formData.append('parent_id', currentReplyId);
                }
                if (activeRecipientId) {
                    formData.append('recipient_id', activeRecipientId);
                }

                for (let i = 0; i < files.length; i++) {
                    formData.append('files[]', files[i]);
                }

                const sendBtn = document.getElementById('sendPeiChatBtn');
                sendBtn.disabled = true;

                fetch(`{{ url('pei-profiles') }}/${peiProfileId}/chat/messages`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    sendBtn.disabled = false;
                    input.value = '';
                    fileInput.value = '';
                    currentReplyId = null;
                    cancelReply();
                    if (data.message) {
                        renderMessage(data.message);
                        lastMessageTime = data.message.created_at;
                        scrollToBottom();
                    }
                })
                .catch(err => {
                    sendBtn.disabled = false;
                    console.error('Error enviando mensaje:', err);
                });
            });

            // Initial load
            fetchMessages();

            // Poll every 4s
            setInterval(() => fetchMessages(true), 4000);

            // Unread badge poll
            function updateUnreadBadge() {
                fetch(`{{ url('pei-profiles') }}/${peiProfileId}/chat/unread`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.unread > 0) {
                            unreadBadge.textContent = data.unread;
                            unreadBadge.style.display = 'inline-block';
                        } else {
                            unreadBadge.style.display = 'none';
                        }
                    });
            }

            function markRead() {
                unreadBadge.style.display = 'none';
            }

            function startPolling() {
                if (!pollInterval) {
                    pollInterval = setInterval(() => fetchMessages(true), 3000);
                }
            }

            function stopPolling() {
                if (pollInterval) {
                    clearInterval(pollInterval);
                    pollInterval = null;
                }
            }

            // Initial unread check
            checkUnread();
            setInterval(checkUnread, 15000);
        });
    </script>
@endif
