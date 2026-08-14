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

        <!-- Private Chat Bar -->
        <div id="privateContactBar" class="border-bottom" style="display: none; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; position: relative;">
            <div class="d-flex align-items-center justify-content-between px-3 pt-2 pb-1">
                <span class="text-xs font-weight-bold text-warning text-uppercase" style="letter-spacing:0.5px;">
                    <i class="fas fa-user-lock mr-1"></i> Chat Privado
                </span>
                <span class="text-white-50" id="privateUsersCountText" style="font-size: 10.5px;">
                    <i class="fas fa-users mr-1"></i> Miembros
                </span>
            </div>

            <!-- Custom User Picker -->
            <div id="privateChatUserPicker" class="px-3 pb-2" style="position: relative;">
                <!-- Trigger button -->
                <button type="button" id="privateUserPickerBtn"
                        class="d-flex align-items-center justify-content-between w-100"
                        style="background: rgba(255,255,255,0.10); border: 1px solid rgba(255,255,255,0.2);
                               border-radius: 10px; padding: 7px 12px; color: #f1f5f9;
                               cursor: pointer; transition: all 0.2s; font-size: 12.5px; font-weight: 600;
                               outline: none;">
                    <div class="d-flex align-items-center text-truncate" style="flex:1; min-width:0;">
                        <div id="privatePickerAvatarMini"
                             style="width:24px; height:24px; border-radius:50%; background:rgba(255,255,255,0.2);
                                    display:flex; align-items:center; justify-content:center;
                                    font-size:10px; font-weight:700; flex-shrink:0; margin-right:8px;">
                            <i class="fas fa-user" style="font-size:10px;"></i>
                        </div>
                        <span id="privatePickerLabel" class="text-truncate" style="color:#cbd5e1;">
                            Seleccionar integrante...
                        </span>
                    </div>
                    <i class="fas fa-chevron-down ml-2" id="privatePickerChevron" style="font-size:10px; flex-shrink:0; transition: transform 0.2s; color:#94a3b8;"></i>
                </button>

                <!-- Dropdown panel -->
                <div id="privateUserPickerDropdown"
                     style="display:none; position:absolute; left:12px; right:12px; top:calc(100% + 4px);
                            background:#ffffff; border-radius:12px; z-index:1080;
                            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
                            border:1px solid #e2e8f0;">
                    <div class="d-flex align-items-center px-3 py-2" style="border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                        <i class="fas fa-search text-muted mr-2" style="font-size:11px;"></i>
                        <input type="text" id="privatePickerSearch"
                               placeholder="Buscar integrante..."
                               autocomplete="off"
                               style="border:none; outline:none; background:transparent; font-size:12px;
                                      color:#0f172a; width:100%; font-weight:500;">
                        <button type="button" id="privatePickerClearSearch"
                                style="display:none; background:none; border:none; color:#94a3b8; cursor:pointer; padding:0; margin-left:4px;">
                            <i class="fas fa-times" style="font-size:10px;"></i>
                        </button>
                    </div>
                    <div id="privatePickerList" style="max-height:200px; overflow-y:auto;">
                        <div class="text-center text-muted py-3" style="font-size:12px;">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Cargando...
                        </div>
                    </div>
                </div>


            </div>

            <!-- Profile Info Widget -->
            <div id="contactProfileWidget" class="d-flex align-items-center px-3 pb-2" style="display: none !important;">
                <div id="contactAvatarCircle"
                     class="mr-2 d-flex align-items-center justify-content-center font-weight-bold text-white shadow-sm"
                     style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #8b5cf6);
                            font-size: 13px; border: 2px solid rgba(255,255,255,0.3); flex-shrink: 0;">--</div>
                <div style="flex:1; min-width:0;">
                    <div class="d-flex align-items-center">
                        <h6 id="contactNameHeading" class="mb-0 font-weight-bold text-white text-truncate mr-2" style="font-size: 12px; max-width: 130px;">Seleccionar Integrante</h6>
                        <span id="contactPointsBadge" class="badge badge-warning text-dark font-weight-bold" style="font-size: 9px; border-radius: 8px; padding: 2px 6px; flex-shrink:0;">&#11088; 0 pts</span>
                    </div>
                    <div class="d-flex align-items-center" style="font-size: 10px; margin-top: 2px;">
                        <span id="contactRoleText" class="text-truncate mr-2" style="max-width: 110px; color: #94a3b8;">Integrante</span>
                        <span id="contactLevelBadge" class="badge font-weight-normal px-2" style="font-size: 9px; background: rgba(255,255,255,0.12); color:#e2e8f0; border: 1px solid rgba(255,255,255,0.2);">Aprendiz</span>
                    </div>
                </div>
                <button type="button" class="btn btn-warning btn-sm font-weight-bold ml-2 shadow-sm rounded-pill" id="btnOpenDonateModal"
                        title="Regalar puntos" style="font-size: 10px; padding: 3px 8px; background: linear-gradient(135deg, #f59e0b, #d97706); border: none; color: #fff; flex-shrink:0;">
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
        <div id="peiChatMessagesBody" class="pei-chat-body" style="flex: 1; overflow-y: auto; overflow-x: hidden;">
            <div class="text-center text-muted py-5" id="peiChatLoading">
                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                <div class="small mt-2">Cargando mensajes...</div>
            </div>
            <div id="peiChatMessagesList" class="d-flex flex-column" style="min-height: 0;"></div>
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

        <!-- Reply Preview Bar (Facebook Messenger Style) -->
        <div id="peiChatReplyBar" class="pei-chat-reply-bar" style="display: none;">
            <div class="d-flex justify-content-between align-items-center" style="min-width: 0;">
                <div class="text-truncate mr-2" style="font-size: 12px; min-width: 0; flex: 1;">
                    <i class="fas fa-reply text-indigo mr-1" style="font-size:11px; color:#4f46e5;"></i>
                    <span class="font-weight-bold text-dark" id="peiChatReplyUser" style="color: #1c1e21 !important;"></span>: 
                    <span id="peiChatReplyText" class="text-muted" style="color: #65676b !important;"></span>
                </div>
                <button type="button" id="cancelPeiChatReply" title="Cancelar respuesta"
                        style="width:24px; height:24px; border-radius:50%; background:#e4e6eb; border:none; color:#65676b; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; transition:all 0.15s;">
                    <i class="fas fa-times" style="font-size:11px;"></i>
                </button>
            </div>
        </div>

        <!-- Attachments Preview Bar -->
        <div id="peiChatAttachmentPreview" class="pei-chat-attachment-preview" style="display: none;">
            <div id="peiChatAttachmentList" class="d-flex flex-wrap gap-1 p-2"></div>
        </div>

        <!-- Input Footer -->
        <div class="pei-chat-footer">
            <form id="peiChatForm" class="d-flex align-items-end" enctype="multipart/form-data">
                <!-- Botón + (siempre visible, colapsa emoji/adjuntar) -->
                <button type="button" id="btnChatPlus" title="Más acciones"
                        style="width:32px; height:32px; border-radius:50%; background:#f1f5f9; border:1px solid #cbd5e1;
                               color:#475569; flex-shrink:0; display:flex; align-items:center; justify-content:center;
                               cursor:pointer; transition:all 0.2s; margin-right:6px; margin-bottom:4px;">
                    <i class="fas fa-plus" style="font-size:13px;"></i>
                </button>

                <!-- Panel expandido (emoji + adjuntar) — oculto por defecto -->
                <div id="peiChatMoreActions" style="display:none; align-items:center; margin-right:6px; margin-bottom:4px; gap:4px;">
                    <button type="button" id="btnToggleEmojiPicker" title="Emoticones"
                            style="width:30px; height:30px; border-radius:50%; background:#f1f5f9; border:1px solid #cbd5e1;
                                   color:#f59e0b; flex-shrink:0; display:flex; align-items:center; justify-content:center; cursor:pointer;">
                        <i class="far fa-smile" style="font-size:14px;"></i>
                    </button>
                    <label for="peiChatFileInput" title="Adjuntar"
                           style="width:30px; height:30px; border-radius:50%; background:#f1f5f9; border:1px solid #cbd5e1;
                                  color:#475569; flex-shrink:0; display:flex; align-items:center; justify-content:center; cursor:pointer; margin:0;">
                        <i class="fas fa-paperclip" style="font-size:13px;"></i>
                        <input type="file" id="peiChatFileInput" multiple hidden>
                    </label>
                </div>

                <textarea id="peiChatMessageInput" class="form-control form-control-sm"
                          placeholder="Escribir mensaje..." rows="1"
                          style="resize:none; flex:1; min-width:0; box-sizing:border-box; background:#f8fafc; border:1px solid #cbd5e1 !important;
                                 border-radius:20px; padding:7px 12px; font-size:13px; color:#1e293b;
                                 min-height:36px; max-height:120px; overflow-y:auto; transition:all 0.2s;"></textarea>

                <button type="submit" id="sendPeiChatBtn"
                        style="width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,#4f46e5,#7c3aed);
                               color:#fff; border:none; flex-shrink:0; display:flex; align-items:center; justify-content:center;
                               box-shadow:0 3px 10px rgba(79,70,229,0.35); margin-left:6px; margin-bottom:4px; cursor:pointer;">
                    <i class="fas fa-paper-plane" style="font-size:12px;"></i>
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
            overflow-x: hidden;
            background-color: #f8f9fc;
            display: flex;
            flex-direction: column;
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
            padding: 8px 12px;
            background: #ffffff;
            border-top: 1px solid #e3e6f0;
        }
        #peiChatForm {
            align-items: flex-end;
        }
        #peiChatForm .bmd-form-group {
            flex: 1 !important;
            min-width: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 0 !important;
        }
        /* Input que se expande automáticamente */
        #peiChatMessageInput {
            transition: border-color 0.2s, box-shadow 0.2s;
            overflow-y: auto;
        }
        #peiChatMessageInput:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 2px rgba(79,70,229,0.15) !important;
            outline: none;
        }
        /* Panel de acciones extra inline */
        #peiChatMoreActions {
            display: none;
        }
        #peiChatMoreActions.show {
            display: flex !important;
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
            background: #f1f5f9;
            border-left: 4px solid #6366f1;
            color: #1e293b !important;
            padding: 5px 10px;
            margin-bottom: 4px;
            font-size: 11px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }
        .msg-reply-ref:hover {
            background: #e2e8f0;
            border-left-color: #4f46e5;
        }
        .msg-reply-ref strong {
            color: #4338ca !important;
        }
        .msg-bubble-container.mine .msg-reply-ref {
            background: #f1f5f9;
            border-left-color: #eab308;
            color: #0f172a !important;
        }
        .msg-bubble-container.mine .msg-reply-ref strong {
            color: #854d0e !important;
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

        /* Animación de destello al hacer clic en Cita */
        @keyframes messageHighlightPulse {
            0% { background-color: #fef08a !important; box-shadow: 0 0 12px rgba(234, 179, 8, 0.8) !important; transform: scale(1.02); }
            50% { background-color: #fef08a !important; box-shadow: 0 0 8px rgba(234, 179, 8, 0.5) !important; }
            100% { background-color: transparent; box-shadow: none; transform: scale(1); }
        }
        .highlight-message {
            animation: messageHighlightPulse 2.2s ease-in-out !important;
            border-radius: 12px;
        }

        /* Facebook Messenger Style Hover Action Buttons */
        .msg-row {
            position: relative;
            width: 100%;
        }
        .msg-hover-actions {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease-in-out;
        }
        .msg-row:hover .msg-hover-actions {
            opacity: 1;
            pointer-events: auto;
        }
        .btn-messenger-action {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #f0f2f5;
            border: 1px solid #e4e6eb;
            color: #65676b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.15s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            padding: 0;
        }
        .btn-messenger-action:hover {
            background: #e4e6eb;
            color: #050505;
            transform: scale(1.1);
        }
        .emoji-opt {
            display: inline-block;
            transition: transform 0.15s ease;
        }
        .emoji-opt:hover {
            transform: scale(1.35) !important;
        }

        /* Badges de Reacciones Emoji */
        .chat-reactions-container {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }
        .chat-reaction-badge {
            display: inline-flex;
            align-items: center;
            font-size: 11px;
            padding: 1px 7px;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #334155;
            cursor: pointer;
            user-select: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            transition: all 0.15s ease;
        }
        .chat-reaction-badge:hover {
            transform: scale(1.08);
            border-color: #4f46e5;
        }
        .chat-reaction-badge.active-reaction {
            background: #eef2ff;
            border-color: #6366f1;
            color: #4338ca;
            font-weight: bold;
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
                if (inputChat) {
                    inputChat.placeholder = 'Escribir consulta al grupo...';
                }

                drawer.classList.add('open');
                fetchMessages();
                markRead();
                startPolling();
                
                // Scroll al fondo después de abrir el chat
                setTimeout(() => {
                    scrollToBottom(true);
                }, 200);
            };

            window.handleContextNavigation = function(event, url) {
                if (!url || url === '#') return;
                event.preventDefault();
                try {
                    const targetUrlObj = new URL(url, window.location.origin);
                    const currentUrlObj = new URL(window.location.href);

                    if (targetUrlObj.pathname === currentUrlObj.pathname && targetUrlObj.hash) {
                        drawer.classList.remove('open');
                        setTimeout(() => scrollToElement(targetUrlObj.hash.substring(1)), 300);
                    } else {
                        window.location.href = url;
                    }
                } catch(e) {
                    window.location.href = url;
                }
            };

            window.scrollToElement = function(elementId) {
                function doScroll() {
                    const element = document.getElementById(elementId);
                    if (!element) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({ icon: 'warning', title: 'Elemento no encontrado', text: 'El elemento referenciado fue eliminado o modificado.', toast: true, position: 'top-end', timer: 3500, showConfirmButton: false });
                        }
                        return;
                    }
                    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    element.style.transition = 'all 0.4s ease';
                    element.style.boxShadow = '0 0 0 3px #22c55e, 0 0 20px rgba(34,197,94,0.5)';
                    element.style.borderRadius = '8px';
                    element.style.outline = '2px solid #16a34a';
                    setTimeout(() => { element.style.boxShadow = 'none'; element.style.outline = 'none'; }, 3500);
                }

                function expandAncestorsAndScroll(targetEl) {
                    const target = targetEl || document.getElementById(elementId);
                    if (!target) {
                        doScroll();
                        return;
                    }

                    const collapsesToOpen = [];
                    let current = target.parentElement;
                    while (current) {
                        if (current.classList && current.classList.contains('collapse') && !current.classList.contains('show')) {
                            collapsesToOpen.push(current);
                        }
                        current = current.parentElement;
                    }

                    const uniqueCollapses = collapsesToOpen.filter(function (collapseEl, index, array) {
                        return array.indexOf(collapseEl) === index;
                    }).reverse();

                    if (uniqueCollapses.length === 0) {
                        doScroll();
                        return;
                    }

                    let pending = uniqueCollapses.length;
                    uniqueCollapses.forEach(function (collapseEl) {
                        if ($(collapseEl).hasClass('show')) {
                            pending--;
                            if (pending === 0) setTimeout(doScroll, 50);
                            return;
                        }

                        $(collapseEl).one('shown.bs.collapse', function () {
                            pending--;
                            if (pending === 0) setTimeout(doScroll, 50);
                        });
                        $(collapseEl).collapse('show');
                    });
                }

                const targetElement = document.getElementById(elementId);
                if (!targetElement) {
                    setTimeout(function () {
                        expandAncestorsAndScroll();
                    }, 250);
                    return;
                }

                expandAncestorsAndScroll(targetElement);
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

            function updateContactProfileWidget(userId) {
                const widget = document.getElementById('contactProfileWidget');
                if (!userId) {
                    widget.style.setProperty('display', 'none', 'important');
                    if (inputChat) {
                        inputChat.placeholder = 'Selecciona un integrante para chatear en privado...';
                    }
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
                    if (inputChat) inputChat.placeholder = 'Escribir mensaje al grupo...';
                } else {
                    tabChannelPrivate.style.borderBottom = '3px solid #ffc107';
                    tabChannelPrivate.classList.remove('text-white-50');
                    tabChannelPrivate.classList.add('text-warning', 'font-weight-bold');

                    tabChannelGroup.style.borderBottom = 'none';
                    tabChannelGroup.classList.remove('text-white', 'font-weight-bold');
                    tabChannelGroup.classList.add('text-white-50');

                    privateContactBar.style.display = 'block';

                    if (!activeRecipientId && allLoadedMessages.length > 0) {
                        const currentUserId = "{{ auth()->id() }}";
                        const lastPrivateMsg = allLoadedMessages.slice().reverse().find(m => {
                            if (!m.is_private) return false;
                            return m.recipient_id == currentUserId || m.user_id == currentUserId;
                        });
                        if (lastPrivateMsg) {
                            const targetId = (lastPrivateMsg.user_id == currentUserId) ? lastPrivateMsg.recipient_id : lastPrivateMsg.user_id;
                            if (targetId) activeRecipientId = String(targetId);
                        }
                    }

                    if (activeRecipientId) {
                        updateContactProfileWidget(activeRecipientId);
                        if (inputChat) inputChat.placeholder = `Escribir mensaje privado a ${activeRecipientName || 'Usuario'}...`;
                    } else {
                        updateContactProfileWidget(null);
                        if (inputChat) inputChat.placeholder = 'Selecciona un integrante para chatear en privado...';
                    }
                }
                renderFilteredMessages();
            }

            window.setPrivateRecipient = function(id, name) {
                activeRecipientId = String(id);
                activeRecipientName = name;
                participantsPanel.style.display = 'none';

                updateContactProfileWidget(activeRecipientId);
                if (window._refreshPrivatePicker) window._refreshPrivatePicker();
                switchChannelMode('private');
                if (inputChat) {
                    inputChat.placeholder = `Mensaje privado a ${activeRecipientName || 'Usuario'}...`;
                }
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

            // Input que se expande automáticamente + botón +
            const inputChat = input;
            const moreActions = document.getElementById('peiChatMoreActions');
            const btnPlus = document.getElementById('btnChatPlus');

            function showExpandedActions() {
                btnPlus.style.display = 'none';
                moreActions.style.display = 'flex';
            }
            function showCollapsedActions() {
                moreActions.style.display = 'none';
                btnPlus.style.display = 'flex';
            }

            // Estado inicial: botones visibles, plus oculto
            showExpandedActions();

            input.addEventListener('focus', function() {
                showCollapsedActions();
            });

            input.addEventListener('blur', function() {
                // Solo expandir si el input está vacío
                if (!input.value.trim()) {
                    showExpandedActions();
                }
            });

            btnPlus.addEventListener('click', function(e) {
                e.stopPropagation();
                showExpandedActions();
                input.focus();
            });

            function autoResizeTextarea() {
                input.style.height = 'auto';
                const hasText = input.value.trim().length > 0;
                input.style.height = Math.min(input.scrollHeight, 120) + 'px';
                if (!hasText) {
                    input.style.height = '36px';
                    // Al borrar todo, colapsar el panel si estaba abierto por texto
                }
            }

            input.addEventListener('input', autoResizeTextarea);

            // Cerrar panel + al hacer click fuera
            document.addEventListener('click', function(e) {
                if (moreActions.style.display === 'flex' && !btnPlus.contains(e.target) && !moreActions.contains(e.target)) {
                    showCollapsedActions();
                }
            });

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
                            inputChat.value += emoji;
                            inputChat.focus();
                            autoResizeTextarea();
                            emojiPicker.style.display = 'none';
                        }
                    });
                }
            }

            // Custom Private User Picker
            (function() {
                const pickerBtn    = document.getElementById('privateUserPickerBtn');
                const pickerDrop   = document.getElementById('privateUserPickerDropdown');
                const pickerSearch = document.getElementById('privatePickerSearch');
                const pickerClear  = document.getElementById('privatePickerClearSearch');
                const pickerList   = document.getElementById('privatePickerList');
                const pickerLabel  = document.getElementById('privatePickerLabel');
                const pickerAvatar = document.getElementById('privatePickerAvatarMini');
                const pickerChevron= document.getElementById('privatePickerChevron');

                function openPicker() {
                    pickerDrop.style.display = 'block';
                    pickerChevron.style.transform = 'rotate(180deg)';
                    pickerSearch.value = '';
                    pickerClear.style.display = 'none';
                    renderPickerList('');
                    setTimeout(() => pickerSearch.focus(), 50);
                }
                function closePicker() {
                    pickerDrop.style.display = 'none';
                    pickerChevron.style.transform = 'rotate(0deg)';
                }

                pickerBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    pickerDrop.style.display === 'none' ? openPicker() : closePicker();
                });

                pickerSearch.addEventListener('input', function() {
                    pickerClear.style.display = this.value ? 'block' : 'none';
                    renderPickerList(this.value.toLowerCase());
                });
                pickerClear.addEventListener('click', function() {
                    pickerSearch.value = '';
                    this.style.display = 'none';
                    renderPickerList('');
                    pickerSearch.focus();
                });

                document.addEventListener('click', function(e) {
                    if (!pickerBtn.closest('#privateChatUserPicker').contains(e.target)) closePicker();
                });

                function renderPickerList(query) {
                    if (!currentParticipants || currentParticipants.length === 0) {
                        pickerList.innerHTML = '<div class="text-center text-muted py-3" style="font-size:12px;">Sin integrantes disponibles</div>';
                        return;
                    }
                    const filtered = query
                        ? currentParticipants.filter(u => u.name.toLowerCase().includes(query))
                        : currentParticipants;

                    if (filtered.length === 0) {
                        pickerList.innerHTML = '<div class="text-center text-muted py-3" style="font-size:12px;">Sin resultados</div>';
                        return;
                    }
                    pickerList.innerHTML = filtered.map(u => {
                        const initials = u.initials || u.name.substring(0,2).toUpperCase();
                        const role = u.role || 'Integrante';
                        const pts  = u.points || 0;
                        const sel  = String(u.id) === String(activeRecipientId);
                        return `<div class="picker-item" 
                                     data-id="${u.id}" data-name="${u.name.replace(/"/g,'&quot;')}" data-initials="${initials}"
                                     style="cursor:pointer; display:flex; align-items:center; padding:8px 12px;
                                            border-bottom:1px solid #f1f5f9; background:${sel?'#e0f2fe':'#fff'};
                                            transition:background 0.15s;">
                            <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c3aed);
                                        display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;
                                        color:#fff;flex-shrink:0;margin-right:8px;pointer-events:none;">${initials}</div>
                            <div style="flex:1;min-width:0;pointer-events:none;">
                                <div style="font-size:12px;font-weight:600;color:#0f172a;">${u.name}</div>
                                <div style="font-size:10px;color:#64748b;">${role}</div>
                            </div>
                            <span style="font-size:10px;background:#fef3c7;color:#92400e;border-radius:8px;padding:2px 6px;flex-shrink:0;pointer-events:none;">⭐ ${pts}</span>
                        </div>`;
                    }).join('');
                }

                // Event delegation — un solo listener en el contenedor, nunca se pierde
                pickerList.addEventListener('click', function(e) {
                    const item = e.target.closest('.picker-item');
                    if (!item) return;
                    e.stopPropagation();

                    const uid  = item.dataset.id;
                    const uname= item.dataset.name;
                    const uini = item.dataset.initials;

                    activeRecipientId   = uid;
                    activeRecipientName = uname;

                    pickerLabel.textContent = uname;
                    pickerLabel.style.color = '#f1f5f9';
                    pickerAvatar.innerHTML  = `<span style="font-size:10px;font-weight:700;color:#fff;">${uini}</span>`;
                    pickerAvatar.style.background = 'linear-gradient(135deg,#4f46e5,#7c3aed)';

                    updateContactProfileWidget(uid);
                    inputChat.placeholder = `Mensaje privado a ${uname}...`;
                    renderFilteredMessages();
                    closePicker();
                });

                // Exponer para que renderParticipants pueda refrescar el picker
                window._refreshPrivatePicker = function() {
                    if (pickerDrop.style.display !== 'none') renderPickerList(pickerSearch.value.toLowerCase());
                    // Actualizar label si ya hay seleccionado
                    if (activeRecipientId && currentParticipants.length) {
                        const u = currentParticipants.find(p => String(p.id) === String(activeRecipientId));
                        if (u) {
                            const ini = u.initials || u.name.substring(0,2).toUpperCase();
                            pickerLabel.textContent = u.name;
                            pickerLabel.style.color = '#f1f5f9';
                            pickerAvatar.innerHTML  = `<span style="font-size:10px;font-weight:700;color:#fff;">${ini}</span>`;
                            pickerAvatar.style.background = 'linear-gradient(135deg,#4f46e5,#7c3aed)';
                        }
                    }
                };
            })();

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
                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Saldo Insuficiente',
                                        text: data.error,
                                        confirmButtonText: 'Entendido',
                                        confirmButtonColor: '#f59e0b',
                                        customClass: {
                                            popup: 'shadow-lg border-0'
                                        }
                                    });
                                } else {
                                    alert(data.error);
                                }
                                return;
                            }
                            donatePopover.style.display = 'none';
                            playCoinChime();
                            triggerCoinBurstAnimation();

                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Donación Enviada!',
                                    html: `Has transferido <strong class="text-warning">${amount} pts</strong> correctamente.`,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });
                            }

                            if (data.message) {
                                allLoadedMessages.push(data.message);
                                renderFilteredMessages();
                            }
                            fetchMessages();
                        })
                        .catch(err => {
                            btnSubmitDonate.disabled = false;
                            btnSubmitDonate.innerHTML = '<i class="fas fa-paper-plane mr-1"></i> Confirmar Donación';
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error de Comunicación',
                                    text: 'No se pudo procesar la transferencia de puntos. Intente nuevamente.',
                                    confirmButtonColor: '#ef4444'
                                });
                            }
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

                        renderFilteredMessages(isPolling);

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
                        // Solo actualizar participantes si el chat está abierto o el tab privado está visible
                        if (drawer.classList.contains('open') || privateContactBar.style.display !== 'none') {
                            renderParticipants(data.participants);
                        }
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

            function renderFilteredMessages(isPolling = false) {
                // 1. Si el usuario tiene un menú desplegable de emojis abierto, no tocamos el DOM durante el polling
                const openDropdown = document.querySelector('#peiChatMessagesBody .dropdown-menu.show');
                if (isPolling && openDropdown) {
                    return;
                }

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

                // 2. Si no es polling (cambio de filtro o carga manual), renderizado completo desde cero
                if (!isPolling) {
                    messagesList.innerHTML = '';
                    toRender.forEach(msg => renderSingleMessageBubble(msg));
                    setTimeout(() => { scrollToBottom(); }, 50);
                    return;
                }

                // 3. Si es polling, sincronizado incremental inteligente: solo se agregan mensajes nuevos o se actualizan reacciones
                toRender.forEach(msg => {
                    const existingMsgEl = document.getElementById(`msg-${msg.id}`);
                    if (!existingMsgEl) {
                        // Mensaje nuevo: renderizar y agregar al final
                        renderSingleMessageBubble(msg);
                        setTimeout(() => { scrollToBottom(); }, 50);
                    } else {
                        // Mensaje existente: actualizar solo las reacciones si han cambiado
                        const reactionsEl = document.getElementById(`reactions-${msg.id}`);
                        if (reactionsEl) {
                            const updatedReactionsHtml = renderReactionBadgesHtml(msg.id, msg.reactions);
                            if (reactionsEl.innerHTML.trim() !== updatedReactionsHtml.trim()) {
                                reactionsEl.innerHTML = updatedReactionsHtml;
                            }
                        }
                    }
                });
            }

            window.scrollToChatMessage = function(msgId) {
                const target = document.getElementById(`msg-${msgId}`);
                const body = document.getElementById('peiChatMessagesBody');
                if (target && body) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    target.classList.remove('highlight-message');
                    void target.offsetWidth; // trigger reflow
                    target.classList.add('highlight-message');
                    setTimeout(() => {
                        target.classList.remove('highlight-message');
                    }, 2200);
                }
            };

            window.setReplyMessage = function(msgId, userName, textSnippet) {
                currentReplyId = msgId;
                const replyBar = document.getElementById('peiChatReplyBar');
                const replyUser = document.getElementById('peiChatReplyUser');
                const replyText = document.getElementById('peiChatReplyText');
                if (replyBar && replyUser && replyText) {
                    replyUser.textContent = 'Respondiendo a ' + userName;
                    replyText.textContent = textSnippet;
                    replyBar.style.display = 'block';
                }
                const input = document.getElementById('peiChatMessageInput');
                if (input) input.focus();
            };

            window.cancelReplyMessage = function() {
                currentReplyId = null;
                const replyBar = document.getElementById('peiChatReplyBar');
                if (replyBar) replyBar.style.display = 'none';
            };

            const cancelReplyBtn = document.getElementById('cancelPeiChatReply');
            if (cancelReplyBtn) {
                cancelReplyBtn.addEventListener('click', function() {
                    window.cancelReplyMessage();
                });
            }

            window.toggleChatReaction = function(msgId, emoji) {
                fetch(`{{ url('pei-profiles') }}/${peiProfileId}/chat/messages/${msgId}/react`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ emoji: emoji })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const msgObj = allLoadedMessages.find(m => m.id == msgId);
                        if (msgObj) {
                            msgObj.reactions = data.reactions;
                        }
                        const container = document.getElementById(`reactions-${msgId}`);
                        if (container) {
                            container.innerHTML = renderReactionBadgesHtml(msgId, data.reactions);
                        }
                    }
                })
                .catch(err => console.error('Error al reaccionar:', err));
            };

            function renderReactionBadgesHtml(msgId, reactions) {
                if (!reactions || reactions.length === 0) return '';
                let html = '';
                reactions.forEach(r => {
                    const usersStr = r.users ? r.users.join(', ') : 'Usuarios';
                    const activeClass = r.has_mine ? 'active-reaction' : '';
                    const escapedEmoji = (r.emoji || '').replace(/'/g, "\\'");
                    html += `<span class="chat-reaction-badge ${activeClass}" onclick="toggleChatReaction('${msgId}', '${escapedEmoji}')" title="${r.users ? r.users.length : 1} personas: ${usersStr}">
                                ${r.emoji} <small class="font-weight-bold ml-1">${r.count}</small>
                             </span>`;
                });
                return html;
            }

            function renderSingleMessageBubble(msg) {
                const container = document.createElement('div');
                container.id = `msg-${msg.id}`;
                container.className = `msg-bubble-container ${msg.is_mine ? 'mine' : 'other'}`;

                const escapedSender = (msg.user_name || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
                const rawText = msg.message ? msg.message.replace(/<[^>]*>?/gm, '') : '';
                const escapedText = rawText.substring(0, 60).replace(/'/g, "\\'").replace(/"/g, '&quot;').replace(/\n/g, ' ');

                let html = '';

                // Row container for bubble + side hover icons (Facebook Messenger Style)
                html += `<div class="msg-row d-flex align-items-center ${msg.is_mine ? 'flex-row-reverse' : 'flex-row'}" style="gap: 6px;">`;

                // Main Bubble Content Stack
                html += `<div class="msg-content-stack" style="max-width: 82%;">`;

                if (!msg.is_mine) {
                    html += `<div class="msg-sender-name mb-0.5" style="cursor:pointer;" title="Clic para abrir chat privado" onclick="setPrivateRecipient('${msg.user_id}', '${escapedSender}')">
                                ${msg.user_name} <i class="fas fa-comment-dots text-muted ml-1" style="font-size:10px;"></i>`;
                    if (msg.is_private) {
                        html += ` <span class="badge badge-warning text-dark ml-1" style="font-size:9px;"><i class="fas fa-lock mr-1"></i>Privado</span>`;
                    }
                    html += `</div>`;
                } else if (msg.is_private) {
                    html += `<div class="text-right text-xs font-weight-bold text-warning mb-1" style="font-size:10px;"><i class="fas fa-lock mr-1"></i>Privado para ${msg.recipient_name || 'Usuario'}</div>`;
                }

                // Cita / Mensaje Padre Referenciado
                if (msg.parent) {
                    html += `<div class="msg-reply-ref mb-1" onclick="scrollToChatMessage('${msg.parent.id}')" title="Clic para ver mensaje original">
                                <i class="fas fa-reply text-indigo mr-1" style="font-size:10px;"></i>
                                <strong>${msg.parent.user_name}</strong>: ${msg.parent.message}
                             </div>`;
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

                // Message Bubble
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

                // Badges de Reacciones Emoji debajo de la burbuja
                html += `<div id="reactions-${msg.id}" class="chat-reactions-container mt-1">
                            ${renderReactionBadgesHtml(msg.id, msg.reactions)}
                         </div>`;

                // Timestamp & Module Origin
                html += `<div class="d-flex align-items-center ${msg.is_mine ? 'justify-content-end' : 'justify-content-start'} mt-0.5" style="gap:6px;">
                            <span class="msg-meta text-muted" style="font-size:9.5px;">${msg.time_ago}</span>
                         </div>`;

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

                html += `</div>`; // Close msg-content-stack

                // Hover Actions Beside Message (Facebook Messenger Style)
                if (!msg.is_system) {
                    html += `<div class="msg-hover-actions d-flex align-items-center" style="gap: 3px;">
                                <button type="button" class="btn-messenger-action" onclick="setReplyMessage('${msg.id}', '${escapedSender}', '${escapedText}')" title="Responder">
                                    <i class="fas fa-reply"></i>
                                </button>
                                <div class="dropdown d-inline-block">
                                    <button type="button" class="btn-messenger-action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Reaccionar">
                                        <i class="far fa-smile"></i>
                                    </button>
                                    <div class="dropdown-menu ${msg.is_mine ? 'dropdown-menu-right' : ''} p-1 shadow-lg border-0" style="min-width:auto; white-space:nowrap; background:#ffffff; border-radius:24px; box-shadow: 0 4px 20px rgba(0,0,0,0.18) !important;">
                                        <div class="d-flex px-2 py-1" style="gap:8px; font-size:19px;">
                                            <span style="cursor:pointer;" class="emoji-opt" onclick="toggleChatReaction('${msg.id}', '👍')">👍</span>
                                            <span style="cursor:pointer;" class="emoji-opt" onclick="toggleChatReaction('${msg.id}', '❤️')">❤️</span>
                                            <span style="cursor:pointer;" class="emoji-opt" onclick="toggleChatReaction('${msg.id}', '💡')">💡</span>
                                            <span style="cursor:pointer;" class="emoji-opt" onclick="toggleChatReaction('${msg.id}', '👏')">👏</span>
                                            <span style="cursor:pointer;" class="emoji-opt" onclick="toggleChatReaction('${msg.id}', '✔️')">✔️</span>
                                            <span style="cursor:pointer;" class="emoji-opt" onclick="toggleChatReaction('${msg.id}', '🔥')">🔥</span>
                                            <span style="cursor:pointer;" class="emoji-opt" onclick="toggleChatReaction('${msg.id}', '😮')">😮</span>
                                        </div>
                                    </div>
                                </div>
                             </div>`;
                }

                html += `</div>`; // Close msg-row

                container.innerHTML = html;
                messagesList.appendChild(container);
            }

            function renderParticipants(list) {
                currentParticipants = list;

                // Update member count badge
                const countText = document.getElementById('privateUsersCountText');
                if (countText) {
                    countText.innerHTML = `<i class="fas fa-users mr-1"></i> ${list ? list.length : 0} Miembro${(list && list.length !== 1) ? 's' : ''}`;
                }

                if (!list || list.length === 0) {
                    participantsList.innerHTML = '<div class="text-muted text-center small py-2">Sin otros miembros</div>';
                    return;
                }

                if (activeRecipientId) {
                    updateContactProfileWidget(activeRecipientId);
                    if (inputChat) inputChat.placeholder = `Mensaje privado a ${activeRecipientName || 'Usuario'}...`;
                } else if (inputChat) {
                    inputChat.placeholder = 'Selecciona un integrante...';
                }

                if (window._refreshPrivatePicker) window._refreshPrivatePicker();

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

            function scrollToBottom(force = false) {
                const body = document.getElementById('peiChatMessagesBody');
                if (!body) return;
                const distanceFromBottom = body.scrollHeight - body.scrollTop - body.clientHeight;
                if (force || distanceFromBottom < 80) {
                    requestAnimationFrame(() => { body.scrollTop = body.scrollHeight; });
                }
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
                    input.style.height = '36px';
                    if (moreActions) { showCollapsedActions(); }
                    if (btnPlus) { btnPlus.style.transform='rotate(0deg)'; btnPlus.style.background='#f1f5f9'; btnPlus.style.color='#475569'; btnPlus.style.borderColor='#cbd5e1'; }
                    fileInput.value = '';
                    if (window.cancelReplyMessage) window.cancelReplyMessage();
                    currentContext = null;
                    const contextBanner = document.getElementById('peiChatContextBanner');
                    if (contextBanner) contextBanner.style.display = 'none';
                    playMessageChime();

                    if (data.message) {
                        allLoadedMessages.push(data.message);
                        renderFilteredMessages();
                        scrollToBottom(true);
                    }
                })
                .catch(err => {
                    sendBtn.disabled = false;
                    console.error('Error enviando mensaje:', err);
                });
            });

            // Initial load
            fetchMessages();

            // Ultra-responsive polling every 1.5s (solo cuando el chat está abierto)
            setInterval(() => {
                if (drawer.classList.contains('open')) {
                    fetchMessages(true);
                }
            }, 1500);

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
                    pollInterval = setInterval(() => {
                        if (drawer.classList.contains('open')) {
                            fetchMessages(true);
                        }
                    }, 1500);
                }
            }

            function stopPolling() {
                if (pollInterval) {
                    clearInterval(pollInterval);
                    pollInterval = null;
                }
            }

            // Initial unread check and frequent poll every 2s (solo cuando el chat está abierto)
            updateUnreadBadge();
            setInterval(() => {
                if (drawer.classList.contains('open')) {
                    updateUnreadBadge();
                }
            }, 2000);
        });
    </script>
@endif
