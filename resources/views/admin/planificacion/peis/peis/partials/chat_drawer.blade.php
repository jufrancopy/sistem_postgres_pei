<!-- Modern PEI Team Chat Drawer -->
@php
    $targetProfile = $profile ?? ($peiProfile ?? ($pei ?? ($activity->peiProfile ?? null)));
    if (!$targetProfile && isset($profileId)) {
        $targetProfile = \App\Admin\Planificacion\Pei\PeiProfile::find($profileId);
    }
    if (!$targetProfile && isset($peiProfileId)) {
        $targetProfile = \App\Admin\Planificacion\Pei\PeiProfile::find($peiProfileId);
    }
    if (!$targetProfile && isset($activity) && !empty($activity->pei_profile_id)) {
        $targetProfile = \App\Admin\Planificacion\Pei\PeiProfile::find($activity->pei_profile_id);
    }
    if (!$targetProfile) {
        $config = \App\Models\HomeConfiguration::first();
        if ($config && $config->pei_profile_id) {
            $targetProfile = \App\Admin\Planificacion\Pei\PeiProfile::find($config->pei_profile_id);
        }
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

        <!-- Channel Switcher Tabs -->
        <div class="d-flex border-bottom" style="font-size: 12px; background: #1e2746;">
            <button type="button" id="tabChannelGroup" class="btn btn-link text-white flex-fill py-2 px-1 text-center font-weight-bold rounded-0" style="border-bottom: 3px solid #4e73df; text-decoration: none; font-size:12px;">
                <i class="fas fa-globe mr-1"></i> Canal del Grupo
            </button>
            <button type="button" id="tabChannelPrivate" class="btn btn-link text-white-50 flex-fill py-2 px-1 text-center rounded-0" style="text-decoration: none; font-size:12px;">
                <i class="fas fa-user-lock mr-1"></i> Mensaje Privado
            </button>
        </div>

        <!-- Origin Filter Bar -->
        <div class="d-flex align-items-center justify-content-between px-3 py-1.5" style="font-size: 10.5px; background: #0f172a; border-bottom: 1px solid rgba(255,255,255,0.08); color: #94a3b8;">
            <div>
                <i class="fas fa-layer-group text-warning mr-1"></i> Origen:
            </div>
            <div class="d-flex" style="gap: 4px;">
                <button type="button" class="btn btn-xs font-weight-bold btn-origin-filter active" data-origin="all" style="font-size: 10px; padding: 1px 8px; border-radius: 12px; background: #38bdf8; color: #0f172a; border: none;">Todos</button>
                <button type="button" class="btn btn-xs font-weight-bold btn-origin-filter text-white-50" data-origin="PEI" style="font-size: 10px; padding: 1px 8px; border-radius: 12px; background: rgba(255,255,255,0.1); border: none;">🎯 PEI</button>
                <button type="button" class="btn btn-xs font-weight-bold btn-origin-filter text-white-50" data-origin="Actividades" style="font-size: 10px; padding: 1px 8px; border-radius: 12px; background: rgba(255,255,255,0.1); border: none;">📋 Actividades</button>
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

        <!-- Modern Contact Profile Header Card (Visible in Private Tab) -->
        <div id="privateContactBar" class="p-3 border-bottom shadow-sm" style="display: none; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white;">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-xs font-weight-bold text-warning text-uppercase" style="letter-spacing:0.5px;">
                    <i class="fas fa-user-lock mr-1"></i> Chat Privado Directo
                </span>
                <select id="privateUserSelect" class="form-control form-control-sm border-0 font-weight-bold" style="max-width: 55%; height: 26px; padding: 2px 8px; background: rgba(255,255,255,0.92); font-size:11px; border-radius: 12px; color: #0f172a;">
                    <option value="">-- Elegir contacto --</option>
                </select>
            </div>

            <!-- Profile Info Widget -->
            <div id="contactProfileWidget" class="d-flex align-items-center mt-2 p-2 rounded justify-content-between" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); display: none !important;">
                <div class="d-flex align-items-center" style="min-width: 0;">
                    <div id="contactAvatarCircle" class="mr-2 d-flex align-items-center justify-content-center font-weight-bold text-white shadow-sm" style="width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #8b5cf6); font-size: 15px; border: 2px solid rgba(255,255,255,0.3); flex-shrink: 0;">
                        --
                    </div>
                    <div style="flex:1; min-width:0;" class="ml-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 id="contactNameHeading" class="mb-0 font-weight-bold text-white text-truncate" style="font-size: 13px;">
                                Seleccionar Integrante
                            </h6>
                            <span id="contactPointsBadge" class="badge badge-warning text-dark font-weight-bold ml-1" style="font-size: 10px; border-radius: 10px; padding: 3px 7px;">
                                ⭐ 0 pts
                            </span>
                        </div>
                        <div class="d-flex align-items-center mt-1 text-white-50" style="font-size: 10.5px;">
                            <span id="contactRoleText" class="text-truncate mr-2" style="max-width: 120px; color: #cbd5e1;">
                                Integrante
                            </span>
                            <span id="contactLevelBadge" class="badge text-white font-weight-normal px-2 py-0" style="font-size: 9.5px; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);">
                                Aprendiz
                            </span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-warning btn-sm font-weight-bold ml-2 shadow-sm rounded-pill" id="btnOpenDonateModal" title="Regalar puntos de reputación" style="font-size: 11px; padding: 4px 10px; background: linear-gradient(135deg, #f59e0b, #d97706); border: none; color: #fff;">
                    <i class="fas fa-gift mr-1"></i> Donar
                </button>
            </div>
        </div>

        <!-- Celebration Overlay Layer -->
        <div id="peiChatCelebrationArea" style="position: absolute; top:0; left:0; width:100%; height:100%; pointer-events:none; z-index: 1090; overflow:hidden;"></div>

        <!-- Point Donation Popover Panel -->
        <div id="peiDonatePopover" class="shadow-lg border rounded-lg p-3" style="display: none; position: absolute; top: 110px; left: 12px; right: 12px; z-index: 1085; background: #ffffff; border-radius: 16px !important; box-shadow: 0 20px 40px rgba(15, 23, 42, 0.22) !important; border: 1px solid #e2e8f0;">
            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                <span class="font-weight-bold text-dark text-xs"><i class="fas fa-gift text-warning mr-1"></i> Regalar Puntos de Reputación</span>
                <button type="button" class="btn btn-sm text-secondary p-0 d-flex align-items-center justify-content-center" id="btnCloseDonatePopover" style="width: 26px; height: 26px; border-radius: 50%; background: #f1f5f9; border: none;"><i class="fas fa-times" style="font-size: 12px;"></i></button>
            </div>
            <p class="text-muted text-xs mb-2">Selecciona la cantidad de puntos a transferir a <strong id="donateRecipientLabel" class="text-dark"></strong>:</p>
            
            <!-- Grid 2x2 para mobile sin desbordamiento -->
            <div class="mb-3" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px;">
                <button type="button" class="btn btn-outline-warning btn-sm font-weight-bold btn-donate-amount py-2 text-truncate" data-amount="5" style="border-radius: 10px; border-width: 1.5px; font-size: 11px;">⭐ 5 PTS</button>
                <button type="button" class="btn btn-outline-warning btn-sm font-weight-bold btn-donate-amount py-2 text-truncate" data-amount="10" style="border-radius: 10px; border-width: 1.5px; font-size: 11px;">⭐ 10 PTS</button>
                <button type="button" class="btn btn-outline-warning btn-sm font-weight-bold btn-donate-amount py-2 text-truncate" data-amount="25" style="border-radius: 10px; border-width: 1.5px; font-size: 11px;">⭐ 25 PTS</button>
                <button type="button" class="btn btn-outline-warning btn-sm font-weight-bold btn-donate-amount py-2 text-truncate" data-amount="50" style="border-radius: 10px; border-width: 1.5px; font-size: 11px;">⭐ 50 PTS</button>
            </div>

            <div class="form-group mb-2">
                <input type="number" id="customDonateInput" class="form-control form-control-sm text-center font-weight-bold text-dark rounded-pill" placeholder="O escribe otra cantidad (ej. 15)" min="1" max="500" style="background: #f8fafc; border: 1px solid #cbd5e1;">
            </div>
            <button type="button" class="btn btn-warning btn-block btn-sm font-weight-bold text-dark shadow-sm rounded-pill py-2" id="btnSubmitDonate" style="background: linear-gradient(135deg, #f59e0b, #d97706); border: none; color: #fff !important;">
                <i class="fas fa-paper-plane mr-1"></i> Confirmar Donación
            </button>
        </div>

        <!-- Emoji Picker Panel -->
        <div id="peiEmojiPicker" class="shadow-lg border rounded-lg p-2 bg-white" style="display: none; position: absolute; bottom: 65px; left: 12px; right: 12px; z-index: 1080; max-height: 180px; overflow-y: auto; border-radius: 14px !important; box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;">
            <div class="d-flex justify-content-between align-items-center mb-1 pb-1 border-bottom">
                <span class="text-xs font-weight-bold text-secondary">Emoticones Rápidos</span>
                <button type="button" class="btn btn-xs text-muted p-0" id="btnCloseEmojiPicker"><i class="fas fa-times"></i></button>
            </div>
            <div class="d-flex flex-wrap gap-1" id="peiEmojiGrid" style="font-size: 20px; cursor: pointer;">
                <span class="emoji-item p-1">👍</span>
                <span class="emoji-item p-1">❤️</span>
                <span class="emoji-item p-1">🚀</span>
                <span class="emoji-item p-1">🎉</span>
                <span class="emoji-item p-1">🔥</span>
                <span class="emoji-item p-1">⭐</span>
                <span class="emoji-item p-1">💡</span>
                <span class="emoji-item p-1">👏</span>
                <span class="emoji-item p-1">😂</span>
                <span class="emoji-item p-1">🙏</span>
                <span class="emoji-item p-1">💯</span>
                <span class="emoji-item p-1">🎯</span>
                <span class="emoji-item p-1">💪</span>
                <span class="emoji-item p-1">✅</span>
                <span class="emoji-item p-1">💬</span>
                <span class="emoji-item p-1">🏆</span>
                <span class="emoji-item p-1">🙌</span>
                <span class="emoji-item p-1">🤝</span>
                <span class="emoji-item p-1">🎁</span>
                <span class="emoji-item p-1">✨</span>
                <span class="emoji-item p-1">🥳</span>
                <span class="emoji-item p-1">👑</span>
                <span class="emoji-item p-1">💎</span>
                <span class="emoji-item p-1">🤩</span>
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

        <!-- Context Reference Active Banner -->
        <div id="peiChatContextBanner" class="p-2 border-top border-bottom bg-white" style="display: none; border-left: 4px solid #4f46e5 !important; font-size: 11px; background: #eef2ff !important; overflow: hidden;">
            <div class="d-flex justify-content-between align-items-center" style="min-width: 0;">
                <div class="text-truncate mr-2" style="min-width: 0; flex: 1;">
                    <span class="font-weight-bold" style="color: #4338ca;"><i class="fas fa-bookmark mr-1"></i> Consulta Vinculada:</span>
                    <span id="peiChatContextTitle" class="text-dark font-weight-bold ml-1"></span>
                </div>
                <button type="button" class="btn btn-xs text-danger p-0" id="clearPeiChatContext" title="Quitar referencia" style="flex-shrink: 0;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
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

        <!-- Input Footer -->
        <div class="pei-chat-footer" style="background: #ffffff; padding: 10px 14px; border-top: 1px solid #e2e8f0;">
            <form id="peiChatForm" class="d-flex align-items-center" enctype="multipart/form-data">
                <label for="peiChatFileInput" class="btn btn-sm mb-0 mr-1 text-slate d-flex align-items-center justify-content-center" title="Adjuntar Archivo" style="width: 36px; height: 36px; border-radius: 50%; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; cursor: pointer; transition: all 0.2s ease;">
                    <i class="fas fa-paperclip" style="font-size: 14px;"></i>
                    <input type="file" id="peiChatFileInput" multiple hidden>
                </label>

                <button type="button" class="btn btn-sm mb-0 mr-2 text-warning d-flex align-items-center justify-content-center" id="btnToggleEmojiPicker" title="Insertar Emoticones" style="width: 36px; height: 36px; border-radius: 50%; background: #fef3c7; color: #d97706; border: 1px solid #fde68a; cursor: pointer; transition: all 0.2s ease;">
                    <i class="far fa-smile" style="font-size: 16px;"></i>
                </button>

                <textarea id="peiChatMessageInput" class="form-control form-control-sm border-0 mr-2" 
                          placeholder="Escribir mensaje al grupo..." rows="1" style="resize: none; background: #f8fafc; border: 1px solid #cbd5e1 !important; border-radius: 20px; padding: 8px 14px; font-size: 13px; color: #1e293b;"></textarea>

                <button type="submit" class="btn btn-sm shadow-sm d-flex align-items-center justify-content-center" id="sendPeiChatBtn" style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #4f46e5, #7c3aed); color: #ffffff; border: none; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35); flex-shrink: 0;">
                    <i class="fas fa-paper-plane" style="font-size: 13px;"></i>
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
            width: 400px;
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
        .msg-reference-badge {
            max-width: 85% !important;
            width: 100%;
            box-sizing: border-box;
            overflow: hidden;
            border: 1px solid rgba(79, 70, 229, 0.25);
        }
        .msg-bubble-container.mine .msg-reference-badge {
            background: #eef2ff;
            border-radius: 12px 12px 2px 12px;
        }
        .msg-bubble-container.other .msg-reference-badge {
            background: #f8fafc;
            border-radius: 12px 12px 12px 2px;
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

            const tabChannelGroup = document.getElementById('tabChannelGroup');
            const tabChannelPrivate = document.getElementById('tabChannelPrivate');
            const privateContactBar = document.getElementById('privateContactBar');
            const privateUserSelect = document.getElementById('privateUserSelect');

            let currentChannelMode = 'group'; // 'group' or 'private'
            let activeRecipientId = null;
            let activeRecipientName = null;
            let allLoadedMessages = [];
            let currentParticipants = [];

            let lastMessageTime = null;
            let currentReplyId = null;
            let pollInterval = null;
            let currentContext = null;

            window.openChatWithContext = function(type, id, title, url) {
                currentContext = { type, id, title, url: url || window.location.href };
                const banner = document.getElementById('peiChatContextBanner');
                const titleEl = document.getElementById('peiChatContextTitle');
                if (banner && titleEl) {
                    titleEl.textContent = title;
                    banner.style.display = 'block';
                }

                switchChannelMode('group');
                if (input) input.placeholder = 'Escribir consulta al grupo...';

                drawer.classList.add('open');
                fetchMessages();
                markRead();
                startPolling();
            };

            window.handleContextNavigation = function(event, url) {
                if (!url || url === '#') return;
                try {
                    const targetUrlObj = new URL(url, window.location.origin);
                    const currentUrlObj = new URL(window.location.href);

                    if (targetUrlObj.pathname === currentUrlObj.pathname && targetUrlObj.hash) {
                        event.preventDefault();
                        const elementId = targetUrlObj.hash.substring(1);
                        const targetEl = document.getElementById(elementId);

                        if (targetEl) {
                            drawer.classList.remove('open');
                            targetEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            targetEl.style.transition = 'all 0.5s ease';
                            targetEl.style.boxShadow = '0 0 20px rgba(245, 158, 11, 0.8)';
                            targetEl.style.borderRadius = '8px';
                            setTimeout(() => {
                                targetEl.style.boxShadow = 'none';
                            }, 3000);
                        } else {
                            window.location.href = url;
                        }
                    }
                } catch(e) {}
            };

            const clearContextBtn = document.getElementById('clearPeiChatContext');
            if (clearContextBtn) {
                clearContextBtn.addEventListener('click', function() {
                    currentContext = null;
                    document.getElementById('peiChatContextBanner').style.display = 'none';
                });
            }

            // Tabs Switch
            tabChannelGroup.addEventListener('click', function() {
                switchChannelMode('group');
            });

            tabChannelPrivate.addEventListener('click', function() {
                switchChannelMode('private');
            });

            privateUserSelect.addEventListener('change', function() {
                activeRecipientId = this.value || null;
                const opt = this.options[this.selectedIndex];
                activeRecipientName = opt ? opt.text : null;
                updateContactProfileWidget(activeRecipientId);
                if (activeRecipientId) {
                    input.placeholder = `Escribir mensaje privado a ${activeRecipientName}...`;
                } else {
                    input.placeholder = 'Selecciona un integrante para chatear en privado...';
                }
                renderFilteredMessages();
            });

            function updateContactProfileWidget(userId) {
                const widget = document.getElementById('contactProfileWidget');
                if (!userId) {
                    widget.style.setProperty('display', 'none', 'important');
                    return;
                }

                let u = currentParticipants.find(p => String(p.id) === String(userId));

                const nameHeading = document.getElementById('contactNameHeading');
                const avatarCircle = document.getElementById('contactAvatarCircle');
                const roleText = document.getElementById('contactRoleText');
                const pointsBadge = document.getElementById('contactPointsBadge');
                const levelBadge = document.getElementById('contactLevelBadge');

                const displayName = (u && u.name) ? u.name : (activeRecipientName || 'Usuario');
                const displayInitials = (u && u.initials) ? u.initials : (displayName ? displayName.substring(0, 2).toUpperCase() : 'US');
                const displayRole = (u && u.role) ? u.role : 'Integrante del Equipo';
                const displayPoints = (u && u.points !== undefined) ? u.points : 0;
                const displayLevel = (u && (u.level_badge || u.level_name)) ? (u.level_badge || u.level_name) : 'Colaborador';

                nameHeading.textContent = displayName;
                avatarCircle.textContent = displayInitials;
                roleText.textContent = displayRole;
                pointsBadge.textContent = `⭐ ${displayPoints} pts`;
                levelBadge.textContent = displayLevel;

                widget.style.setProperty('display', 'flex', 'important');
            }

            function switchChannelMode(mode) {
                currentChannelMode = mode;
                if (mode === 'group') {
                    tabChannelGroup.style.borderBottom = '3px solid #4e73df';
                    tabChannelGroup.classList.remove('text-white-50');
                    tabChannelGroup.classList.add('text-white', 'font-weight-bold');

                    tabChannelPrivate.style.borderBottom = 'none';
                    tabChannelPrivate.classList.remove('text-warning', 'font-weight-bold');
                    tabChannelPrivate.classList.add('text-white-50');

                    privateContactBar.style.display = 'none';
                    activeRecipientId = null;
                    activeRecipientName = null;
                    privateUserSelect.value = '';
                    input.placeholder = 'Escribir mensaje al grupo...';
                } else {
                    tabChannelPrivate.style.borderBottom = '3px solid #ffc107';
                    tabChannelPrivate.classList.remove('text-white-50');
                    tabChannelPrivate.classList.add('text-warning', 'font-weight-bold');

                    tabChannelGroup.style.borderBottom = 'none';
                    tabChannelGroup.classList.remove('text-white', 'font-weight-bold');
                    tabChannelGroup.classList.add('text-white-50');

                    privateContactBar.style.display = 'block';

                    // Si hay un destinatario activo, asegurar la selección en el dropdown
                    if (activeRecipientId) {
                        privateUserSelect.value = String(activeRecipientId);
                    } else if (privateUserSelect.value) {
                        activeRecipientId = privateUserSelect.value;
                    } else if (allLoadedMessages.length > 0) {
                        const currentUserId = "{{ auth()->id() }}";
                        const lastPrivateMsg = allLoadedMessages.slice().reverse().find(m => {
                            if (!m.is_private) return false;
                            return m.recipient_id == currentUserId || m.user_id == currentUserId;
                        });
                        if (lastPrivateMsg) {
                            const targetId = (lastPrivateMsg.user_id == currentUserId) ? lastPrivateMsg.recipient_id : lastPrivateMsg.user_id;
                            if (targetId) {
                                activeRecipientId = String(targetId);
                                privateUserSelect.value = activeRecipientId;
                            }
                        }
                    }

                    if (activeRecipientId) {
                        const opt = privateUserSelect.options[privateUserSelect.selectedIndex];
                        if (opt && opt.value) {
                            activeRecipientName = opt.text.split('(')[0].trim();
                        }
                        updateContactProfileWidget(activeRecipientId);
                        input.placeholder = `Escribir mensaje privado a ${activeRecipientName || 'Usuario'}...`;
                    } else {
                        updateContactProfileWidget(null);
                        input.placeholder = 'Selecciona un integrante para chatear en privado...';
                    }
                }
                renderFilteredMessages();
            }

            window.setPrivateRecipient = function(id, name) {
                activeRecipientId = String(id);
                activeRecipientName = name;
                participantsPanel.style.display = 'none';
                
                privateUserSelect.value = activeRecipientId;
                updateContactProfileWidget(activeRecipientId);
                switchChannelMode('private');
            };

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

            // Audio de celebración para donaciones (Web Audio API - Tono triple estilo Mario Coin)
            function playCoinChime() {
                try {
                    const AudioCtx = window.AudioContext || window.webkitAudioContext;
                    if (!AudioCtx) return;
                    const ctx = new AudioCtx();
                    
                    const notes = [987.77, 1318.51, 1567.98];
                    notes.forEach((freq, idx) => {
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.type = 'triangle';
                        osc.frequency.setValueAtTime(freq, ctx.currentTime + (idx * 0.08));
                        
                        gain.gain.setValueAtTime(0.25, ctx.currentTime + (idx * 0.08));
                        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + (idx * 0.08) + 0.25);
                        
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        
                        osc.start(ctx.currentTime + (idx * 0.08));
                        osc.stop(ctx.currentTime + (idx * 0.08) + 0.25);
                    });
                } catch (e) {}
            }

            // Animación de partículas doradas y confeti de estrellas
            function triggerCoinBurstAnimation() {
                const container = document.getElementById('peiChatCelebrationArea');
                if (!container) return;
                
                const particleCount = 28;
                const symbols = ['⭐', '💰', '✨', '🎁', '🌟'];
                
                for (let i = 0; i < particleCount; i++) {
                    const p = document.createElement('div');
                    p.className = 'gold-particle';
                    p.textContent = symbols[Math.floor(Math.random() * symbols.length)];
                    
                    const startX = 180 + (Math.random() * 40 - 20);
                    const startY = 300 + (Math.random() * 40 - 20);
                    const dx = (Math.random() - 0.5) * 260;
                    const dy = -(120 + Math.random() * 220);
                    const endx = dx + (Math.random() - 0.5) * 80;
                    
                    p.style.left = startX + 'px';
                    p.style.top = startY + 'px';
                    p.style.setProperty('--dx', dx + 'px');
                    p.style.setProperty('--dy', dy + 'px');
                    p.style.setProperty('--endx', endx + 'px');
                    p.style.animationDelay = (Math.random() * 0.2) + 's';
                    
                    container.appendChild(p);
                    setTimeout(() => p.remove(), 1800);
                }
            }

            // Emoji Picker Handlers
            const emojiBtn = document.getElementById('btnToggleEmojiPicker');
            const emojiPicker = document.getElementById('peiEmojiPicker');
            const emojiClose = document.getElementById('btnCloseEmojiPicker');
            const emojiGrid = document.getElementById('peiEmojiGrid');

            if (emojiBtn && emojiPicker) {
                emojiBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    emojiPicker.style.display = (emojiPicker.style.display === 'none') ? 'block' : 'none';
                });

                if (emojiClose) {
                    emojiClose.addEventListener('click', function() {
                        emojiPicker.style.display = 'none';
                    });
                }

                if (emojiGrid) {
                    emojiGrid.addEventListener('click', function(e) {
                        if (e.target.classList.contains('emoji-item')) {
                            const emoji = e.target.textContent;
                            input.value += emoji;
                            input.focus();
                            emojiPicker.style.display = 'none';
                        }
                    });
                }
            }

            // Point Donation Handlers
            const btnOpenDonateModal = document.getElementById('btnOpenDonateModal');
            const donatePopover = document.getElementById('peiDonatePopover');
            const btnCloseDonatePopover = document.getElementById('btnCloseDonatePopover');
            const donateRecipientLabel = document.getElementById('donateRecipientLabel');
            const customDonateInput = document.getElementById('customDonateInput');
            const btnSubmitDonate = document.getElementById('btnSubmitDonate');

            let selectedDonateAmount = 10;

            if (btnOpenDonateModal && donatePopover) {
                btnOpenDonateModal.addEventListener('click', function() {
                    if (!activeRecipientId) return;
                    donateRecipientLabel.textContent = activeRecipientName || 'Integrante';
                    donatePopover.style.display = 'block';
                });

                if (btnCloseDonatePopover) {
                    btnCloseDonatePopover.addEventListener('click', function() {
                        donatePopover.style.display = 'none';
                    });
                }

                document.querySelectorAll('.btn-donate-amount').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.btn-donate-amount').forEach(b => b.classList.remove('active', 'btn-warning', 'text-dark'));
                        this.classList.add('active', 'btn-warning', 'text-dark');
                        selectedDonateAmount = parseInt(this.dataset.amount);
                        customDonateInput.value = '';
                    });
                });

                if (btnSubmitDonate) {
                    btnSubmitDonate.addEventListener('click', function() {
                        const customVal = parseInt(customDonateInput.value);
                        const amount = (customVal && customVal > 0) ? customVal : selectedDonateAmount;

                        if (!activeRecipientId) return;
                        if (amount <= 0) return;

                        btnSubmitDonate.disabled = true;
                        btnSubmitDonate.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Donando...';

                        fetch(`{{ url('pei-profiles') }}/${peiProfileId}/chat/donate`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                recipient_id: activeRecipientId,
                                points: amount
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            btnSubmitDonate.disabled = false;
                            btnSubmitDonate.innerHTML = '<i class="fas fa-paper-plane mr-1"></i> Confirmar Donación';
                            if (data.error) {
                                alert(data.error);
                                return;
                            }
                            donatePopover.style.display = 'none';
                            playCoinChime();
                            triggerCoinBurstAnimation();
                            if (data.message) {
                                allLoadedMessages.push(data.message);
                                renderFilteredMessages();
                            }
                            fetchMessages();
                        })
                        .catch(err => {
                            btnSubmitDonate.disabled = false;
                            btnSubmitDonate.innerHTML = '<i class="fas fa-paper-plane mr-1"></i> Confirmar Donación';
                            console.error('Error procesando donación:', err);
                        });
                    });
                }
            }

            // Sonido de notificación sintetizado (Web Audio API)
            function playMessageChime() {
                try {
                    const AudioCtx = window.AudioContext || window.webkitAudioContext;
                    if (!AudioCtx) return;
                    const ctx = new AudioCtx();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();

                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(587.33, ctx.currentTime);
                    osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.12);

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

                    if (data.messages) {
                        const prevLength = allLoadedMessages.length;
                        allLoadedMessages = data.messages;
                        if (allLoadedMessages.length > prevLength) {
                            hasNewOtherMessage = true;
                        }

                        const currentUserId = "{{ auth()->id() }}";
                        const unreadPrivateCount = allLoadedMessages.filter(m => m.is_private && m.recipient_id == currentUserId).length;
                        if (unreadPrivateCount > 0) {
                            tabChannelPrivate.innerHTML = `<i class="fas fa-user-lock mr-1"></i> Mensaje Privado <span class="badge badge-warning text-dark font-weight-bold ml-1">🔴 ${unreadPrivateCount}</span>`;
                        } else {
                            tabChannelPrivate.innerHTML = `<i class="fas fa-user-lock mr-1"></i> Mensaje Privado`;
                        }

                        renderFilteredMessages();

                        if (isPolling && hasNewOtherMessage) {
                            const latestNewMsg = allLoadedMessages[allLoadedMessages.length - 1];
                            if (latestNewMsg && (latestNewMsg.is_donation || (latestNewMsg.message && latestNewMsg.message.includes('🎁')))) {
                                playCoinChime();
                                triggerCoinBurstAnimation();
                            } else {
                                playMessageChime();
                            }
                        }
                    }

                    if (data.participants) {
                        renderParticipants(data.participants);
                    }
                })
                .catch(err => console.error('Error fetching chat messages:', err));
            }

            let activeOriginFilter = 'all';

            document.querySelectorAll('.btn-origin-filter').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.btn-origin-filter').forEach(b => {
                        b.style.background = 'rgba(255,255,255,0.1)';
                        b.style.color = 'rgba(255,255,255,0.7)';
                        b.classList.remove('active');
                    });
                    this.style.background = '#38bdf8';
                    this.style.color = '#0f172a';
                    this.classList.add('active');
                    activeOriginFilter = this.dataset.origin;
                    renderFilteredMessages();
                });
            });

            function renderFilteredMessages() {
                messagesList.innerHTML = '';
                let toRender = [];

                if (currentChannelMode === 'group') {
                    toRender = allLoadedMessages.filter(m => !m.is_private);
                    if (toRender.length === 0) {
                        messagesList.innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-globe fa-2x text-primary mb-2" style="opacity:0.4;"></i><div class="small font-weight-bold">Canal General del Grupo</div><div class="text-xs mt-1">Mensajes compartidos para todos los miembros.</div></div>';
                        return;
                    }
                } else {
                    if (!activeRecipientId) {
                        messagesList.innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-user-lock fa-2x text-warning mb-2" style="opacity:0.6;"></i><div class="small font-weight-bold">Ventana de Chat Privado 1 a 1</div><div class="text-xs mt-1">Selecciona a un integrante arriba para abrir la conversación.</div></div>';
                        return;
                    }

                    const currentUserId = "{{ auth()->id() }}";
                    toRender = allLoadedMessages.filter(m => {
                        if (!m.is_private) return false;
                        const iSentToThem = (m.user_id == currentUserId && m.recipient_id == activeRecipientId);
                        const theySentToMe = (m.user_id == activeRecipientId && m.recipient_id == currentUserId);
                        return iSentToThem || theySentToMe;
                    });

                    if (toRender.length === 0) {
                        messagesList.innerHTML = `<div class="text-center text-muted py-5"><i class="fas fa-lock fa-2x text-warning mb-2" style="opacity:0.6;"></i><div class="small">Sin mensajes privados con <strong>${activeRecipientName || 'el usuario'}</strong>.</div><div class="text-xs mt-1">Escribe abajo para enviar un mensaje privado.</div></div>`;
                        return;
                    }
                }

                if (activeOriginFilter !== 'all') {
                    toRender = toRender.filter(m => m.origin_module === activeOriginFilter);
                }

                if (toRender.length === 0) {
                    messagesList.innerHTML = `<div class="text-center text-muted py-5"><i class="fas fa-filter fa-2x text-info mb-2" style="opacity:0.5;"></i><div class="small">Sin mensajes para el origen <strong>${activeOriginFilter}</strong>.</div></div>`;
                    return;
                }

                toRender.forEach(msg => renderSingleMessageBubble(msg));
                scrollToBottom();
            }

            function renderSingleMessageBubble(msg) {
                const container = document.createElement('div');
                container.id = `msg-${msg.id}`;
                container.className = `msg-bubble-container ${msg.is_mine ? 'mine' : 'other'}`;

                let html = '';
                if (!msg.is_mine) {
                    const escapedSender = msg.user_name.replace(/'/g, "\\'");
                    html += `<div class="msg-sender-name" style="cursor:pointer;" title="Clic para abrir chat privado" onclick="setPrivateRecipient('${msg.user_id}', '${escapedSender}')">
                                ${msg.user_name} <i class="fas fa-comment-dots text-muted ml-1" style="font-size:10px;"></i>`;
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

                if (msg.reference_title) {
                    const refUrl = msg.reference_url || '#';
                    html += `<div class="msg-reference-badge p-2 mb-1">
                                <div class="d-flex align-items-center justify-content-between" style="min-width: 0;">
                                    <div class="text-truncate mr-2 font-weight-bold" style="font-size: 11px; color: #3730a3; min-width: 0; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <i class="fas fa-bookmark mr-1"></i> ${msg.reference_title}
                                    </div>
                                    <a href="${refUrl}" class="btn btn-xs font-weight-bold ml-1 rounded-pill px-2" style="font-size: 10px; background: #4f46e5; color: #fff; text-decoration: none; flex-shrink: 0;" onclick="handleContextNavigation(event, '${refUrl}')">
                                        <i class="fas fa-external-link-alt mr-1"></i> Ir al elemento
                                    </a>
                                </div>
                             </div>`;
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

                if (msg.origin_module) {
                    const isAct = msg.origin_module === 'Actividades';
                    const icon = isAct ? 'fa-tasks' : 'fa-bullseye';
                    const badgeStyle = isAct ? 'background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd;' : 'background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0;';
                    const linkUrl = msg.origin_url || '#';
                    
                    html += `<div class="msg-origin-badge mt-1 text-xs" style="font-size: 9.5px;">
                                <a href="${linkUrl}" target="_blank" class="d-inline-flex align-items-center rounded-pill px-2 py-0.5" style="${badgeStyle} text-decoration:none;" title="Ir a la pantalla de origen del emisor">
                                    <i class="fas ${icon} mr-1"></i> Desde: ${msg.origin_module} ${msg.origin_title ? '— ' + msg.origin_title : ''} <i class="fas fa-external-link-alt ml-1" style="font-size:8px;"></i>
                                </a>
                             </div>`;
                }

                container.innerHTML = html;
                messagesList.appendChild(container);
            }

            function renderParticipants(list) {
                currentParticipants = list;
                if (!list || list.length === 0) {
                    participantsList.innerHTML = '<div class="text-muted text-center small py-2">Sin otros miembros</div>';
                    privateUserSelect.innerHTML = '<option value="">-- Sin otros miembros --</option>';
                    return;
                }

                const currVal = privateUserSelect.value;
                let selectHtml = '<option value="">-- Seleccionar Integrante --</option>';
                list.forEach(u => {
                    selectHtml += `<option value="${u.id}" ${u.id == currVal ? 'selected' : ''}>${u.name} (${u.role || 'Integrante'})</option>`;
                });
                privateUserSelect.innerHTML = selectHtml;

                if (activeRecipientId) {
                    updateContactProfileWidget(activeRecipientId);
                }

                let html = '<div class="text-muted text-xs mb-2 font-weight-bold">Integrantes del Equipo:</div>';
                list.forEach(u => {
                    const isSel = activeRecipientId == u.id;
                    const escapedName = u.name.replace(/'/g, "\\'");
                    const initials = u.initials || u.name.substring(0, 2).toUpperCase();
                    const points = u.points || 0;
                    const role = u.role || 'Integrante';

                    html += `<div class="d-flex align-items-center justify-content-between mb-1.5 p-2 rounded border ${isSel ? 'bg-warning text-dark font-weight-bold border-warning' : 'bg-white shadow-sm'}" style="cursor:pointer;" onclick="setPrivateRecipient('${u.id}', '${escapedName}')">
                                <div class="d-flex align-items-center" style="min-width: 0;">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-2 font-weight-bold text-white shadow-sm" style="width:32px; height:32px; font-size:11px; background: linear-gradient(135deg, #4f46e5, #7c3aed); flex-shrink: 0;">
                                        ${initials}
                                    </div>
                                    <div style="min-width:0;">
                                        <div class="font-weight-bold text-xs text-truncate" style="max-width:140px;">${u.name}</div>
                                        <div class="text-muted text-truncate" style="font-size:9.5px; max-width:140px;">${role}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-warning text-dark font-weight-bold" style="font-size:9.5px; border-radius: 8px;">⭐ ${points} pts</span>
                                </div>
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
                if (currentChannelMode === 'private' && activeRecipientId) {
                    formData.append('recipient_id', activeRecipientId);
                }

                if (currentContext) {
                    formData.append('reference_type', currentContext.type);
                    formData.append('reference_id', currentContext.id);
                    formData.append('reference_title', currentContext.title);
                    formData.append('reference_url', currentContext.url);
                }

                let originMod = 'PEI';
                if (window.location.href.includes('/activities')) {
                    originMod = 'Actividades';
                } else if (window.location.href.includes('/indicadores')) {
                    originMod = 'Indicadores';
                } else if (window.location.href.includes('/proyectos')) {
                    originMod = 'Proyectos';
                }

                formData.append('origin_module', originMod);
                formData.append('origin_title', document.title ? document.title.split('-')[0].trim() : originMod);
                formData.append('origin_url', window.location.href);

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
                    currentContext = null;
                    const contextBanner = document.getElementById('peiChatContextBanner');
                    if (contextBanner) contextBanner.style.display = 'none';

                    if (data.message) {
                        allLoadedMessages.push(data.message);
                        renderFilteredMessages();
                    }
                })
                .catch(err => {
                    sendBtn.disabled = false;
                    console.error('Error enviando mensaje:', err);
                });
            });

            // Initial load
            fetchMessages();

            // Ultra-responsive polling every 1.5s
            setInterval(() => fetchMessages(true), 1500);

            let lastUnreadCount = 0;

            // Unread badge poll every 2s
            function updateUnreadBadge() {
                fetch(`{{ url('pei-profiles') }}/${peiProfileId}/chat/unread`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.unread > 0) {
                            unreadBadge.textContent = data.unread;
                            unreadBadge.style.display = 'inline-block';
                            if (data.unread > lastUnreadCount) {
                                playMessageChime();
                            }
                            lastUnreadCount = data.unread;
                        } else {
                            unreadBadge.style.display = 'none';
                            lastUnreadCount = 0;
                        }
                    });
            }

            function markRead() {
                unreadBadge.style.display = 'none';
                lastUnreadCount = 0;
                fetch(`{{ url('pei-profiles') }}/${peiProfileId}/chat/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).catch(err => console.error('Error marking read:', err));
            }

            function startPolling() {
                if (!pollInterval) {
                    pollInterval = setInterval(() => fetchMessages(true), 1500);
                }
            }

            function stopPolling() {
                if (pollInterval) {
                    clearInterval(pollInterval);
                    pollInterval = null;
                }
            }

            // Initial unread check and frequent poll every 2s
            updateUnreadBadge();
            setInterval(updateUnreadBadge, 2000);
        });
    </script>
@endif
