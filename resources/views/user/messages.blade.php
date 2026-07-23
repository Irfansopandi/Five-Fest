@extends('v_layouts.app')
@php($hideFooter = true)

@section('title', 'Pesan Bantuan')

@push('styles')
<style>
    body { overflow: hidden; }
    html { overflow: hidden;}

    .bg-main {
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1;
        isolation: auto;
    }

    .chat-breadcrumb {
        padding: 10px 20px;
        flex-shrink: 0;
    }

    .chat-outer {
        flex: 1;
        min-height: 0;
        display: flex;
        align-items: stretch;
        padding: 0 16px 16px;
    }

    .chat-wrapper {
        width: 100%;
        max-width: 960px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        min-height: 0;
        overflow: visible;
    }

    .chat-header {
        background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
        overflow: hidden;
        border-radius: 20px 20px 0 0;
    }
    .chat-header-avatar {
        width: 42px; height: 42px;
        background: white; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .chat-body {
        flex: 1;
        min-height: 0;
        overflow-y: auto;
        padding: 20px 24px;
        background: #f0e6ff;
        display: flex;
        flex-direction: column;
    }
    .chat-body::-webkit-scrollbar { width: 4px; }
    .chat-body::-webkit-scrollbar-thumb { background: #c4b5fd; border-radius: 10px; }

    .chat-date-badge { text-align: center; margin: 8px 0; }
    .chat-date-badge span {
        background: rgba(0,0,0,0.08); color: #666;
        font-size: 0.7rem; padding: 3px 12px; border-radius: 50px;
    }

    .bubble-row { display: flex; margin-bottom: 6px; }
    .bubble-row.right { justify-content: flex-end; }
    .bubble-row.left  { justify-content: flex-start; }

    .bubble {
        max-width: 72%;
        padding: 9px 13px;
        font-size: 0.88rem;
        line-height: 1.5;
    }
    .bubble.user {
        background: #7c3aed; color: white;
        border-radius: 18px 18px 4px 18px;
    }
    .bubble.admin {
        background: white; color: #1e293b;
        border-radius: 18px 18px 18px 4px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }
    .bubble.system {
        background: white; color: #555;
        border-radius: 12px;
        font-size: 0.82rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        max-width: 80%; line-height: 1.6;
    }
    .bubble-meta { font-size: 0.62rem; color: #999; margin-top: 3px; }
    .bubble-row.right .bubble-meta { text-align: right; }

    .bubble-avatar {
        width: 26px; height: 26px;
        background: white; border-radius: 50%;
        border: 2px solid #7c3aed;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; align-self: flex-end; margin-right: 7px;
    }
    .subject-tag { font-size: 0.62rem; opacity: 0.75; margin-bottom: 3px; text-transform: capitalize; }

    .chat-footer {
        background: white;
        border-top: 1px solid #f1f5f9;
        flex-shrink: 0;
        border-radius: 0 0 20px 20px;
        overflow: visible;
        position: relative;
    }
    .chat-footer-category { padding: 10px 16px 0; }
    .chat-footer-category select {
        font-size: 0.72rem; border-color: #e2e8f0; color: #64748b;
        border-radius: 50px; padding: 4px 12px; max-width: 200px;
    }
    .chat-input-row {
        display: flex; align-items: flex-end;
        gap: 8px; padding: 10px 16px 18px;
    }
    .chat-attach-btn {
        width: 38px; height: 38px;
        background: #f3e8ff; color: #7c3aed;
        border-radius: 50%; border: none;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; cursor: pointer; font-size: 1.1rem; transition: 0.2s;
    }
    .chat-attach-btn:hover { background: #ede9fe; }
    .chat-textarea {
        flex: 1; border: 1.5px solid #e2e8f0;
        border-radius: 20px; padding: 9px 16px;
        font-size: 0.88rem; resize: none;
        overflow: hidden; max-height: 120px;
        line-height: 1.4; transition: border-color 0.2s;
    }
    .chat-textarea:focus { outline: none; border-color: #a855f7; }
    .chat-send-btn {
        width: 40px; height: 40px;
        border-radius: 50%; border: none; flex-shrink: 0;
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        color: white; font-size: 0.9rem;
        display: flex; align-items: center; justify-content: center;
        transition: 0.2s;
    }
    .chat-send-btn:hover { transform: scale(1.08); }
    #msgPhotoPreview { padding: 0 12px; }

    @media (max-width: 768px) {
        .chat-breadcrumb { display: none !important; }
        .chat-wrapper { border-radius: 0; box-shadow: none; }
        .chat-header { padding: 10px 14px !important; }
        .chat-header-avatar { width: 36px; height: 36px; }
        .bubble { max-width: 85% !important; }
        .chat-footer-category { padding: 8px 12px 0 !important; }
        .chat-footer-category select { max-width: 140px !important; font-size: 0.72rem !important; }
        .chat-input-row { padding: 8px 10px 20px !important; }
        .chat-header .text-white.opacity-60 { display: none !important; }
        .chat-outer {
            padding: 0 !important;
            min-height: 0 !important;
            overflow: visible !important;
        }
        .chat-body {
            padding: 12px 14px 20px !important;
            -webkit-overflow-scrolling: touch !important;
        }
        .bg-main { top: 0; }
    }
</style>
@endpush

@section('content')
<section class="bg-main">
    <div class="chat-outer">
        <div class="chat-wrapper">

            {{-- HEADER --}}
            <div class="chat-header">
                <div class="chat-header-avatar">
                    <i class="bi bi-headset" style="color:#7c3aed; font-size:1.1rem;"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="text-white fw-bold" style="font-size:0.92rem;">Admin FiveFest</div>
                    <div class="text-white opacity-75" style="font-size:0.68rem;">
                        <i class="bi bi-circle-fill me-1" style="font-size:0.4rem; color:#4ade80;"></i>Online · Pusat Bantuan
                    </div>
                </div>
                <div class="text-white opacity-60" style="font-size:0.72rem; text-align:right;">
                    <div>{{ Auth::user()->name }}</div>
                    <div style="font-size:0.65rem;">{{ ucfirst(Auth::user()->role ?? 'member') }}</div>
                </div>
            </div>

            {{-- CHAT BODY --}}
            <div class="chat-body" id="chatArea">

                @if(session('success'))
                    <div class="chat-date-badge mb-2">
                        <span style="background:#dcfce7; color:#16a34a;">
                            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                        </span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="chat-date-badge mb-2">
                        <span style="background:#fee2e2; color:#dc2626;">
                            @foreach($errors->all() as $e){{ $e }} @endforeach
                        </span>
                    </div>
                @endif

                <div class="chat-date-badge">
                    <span>{{ now()->format('d M Y') }}</span>
                </div>

                {{-- Sambutan --}}
                <div class="bubble-row left mt-2">
                    <div class="bubble-avatar">
                        <i class="bi bi-headset" style="color:#7c3aed; font-size:0.7rem;"></i>
                    </div>
                    <div>
                        <div class="bubble system">
                            👋 Halo, <strong>{{ Auth::user()->name }}</strong>! Selamat datang di Pusat Bantuan FiveFest.<br><br>
                            Kami siap membantu kamu 24/7. Silakan ketik pesanmu di bawah ya!
                        </div>
                        <div class="bubble-meta">Admin FiveFest · Otomatis</div>
                    </div>
                </div>

                @forelse($messages as $msg)
                    <div class="bubble-row right">
                        <div>
                            <div class="bubble user">
                                @if($msg->subject && $msg->subject !== 'other')
                                    <div class="subject-tag">{{ ucfirst($msg->subject) }}</div>
                                @endif
                                {{ $msg->message }}
                                @if($msg->photo_path)
                                    <img src="{{ Storage::url($msg->photo_path) }}"
                                         class="rounded mt-2 d-block"
                                         style="max-width:180px; max-height:180px; object-fit:cover;">
                                @endif
                            </div>
                            <div class="bubble-meta">
                                {{ $msg->created_at->format('H:i') }}
                                @if($msg->status == 'replied')
                                    <i class="bi bi-check-all" style="color:#a78bfa;"></i>
                                @elseif($msg->status == 'read')
                                    <i class="bi bi-check-all text-info"></i>
                                @else
                                    <i class="bi bi-check text-muted"></i>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($msg->status == 'replied' && $msg->admin_notes)
                        <div class="bubble-row left">
                            <div class="bubble-avatar">
                                <i class="bi bi-headset" style="color:#7c3aed; font-size:0.7rem;"></i>
                            </div>
                            <div>
                                <div class="bubble admin">{{ $msg->admin_notes }}</div>
                                <div class="bubble-meta">
                                    Admin · {{ $msg->replied_at ? $msg->replied_at->format('H:i') : '' }}
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                @endforelse

            </div>

            {{-- FOOTER --}}
            <div class="chat-footer">
                <div id="msgPhotoPreview"></div>

                {{-- Emoji Picker --}}
                <div id="emojiPicker" style="display:none; position:absolute; bottom:90px; left:50%; transform:translateX(-50%); z-index:999;">
                    <emoji-picker></emoji-picker>
                </div>

                <div class="chat-footer-category">
                    <form action="{{ route('contact.send') }}" method="POST"
                          enctype="multipart/form-data" id="chatForm">
                        @csrf
                        <input type="hidden" name="name"        value="{{ Auth::user()->name }}">
                        <input type="hidden" name="email"       value="{{ Auth::user()->email }}">
                        <input type="hidden" name="redirect_to" value="my-messages">

                        <select name="subject" class="form-select form-select-sm">
                            <option value="other">Umum</option>
                            <option value="booking">Masalah Booking</option>
                            <option value="refund">Refund Dana</option>
                            <option value="partnership">Kerjasama</option>
                        </select>
                </div>

                <div class="chat-input-row">
                    {{-- Emoji button --}}
                    <button type="button" onclick="toggleEmoji()"
                        class="chat-attach-btn mb-0" title="Emoji">
                        😊
                    </button>

                    {{-- File button --}}
                    <label for="photoInput" class="chat-attach-btn mb-0" title="Lampirkan foto">
                        <i class="bi bi-paperclip"></i>
                        <input type="file" id="photoInput" name="photo"
                               class="d-none" accept="image/*" onchange="previewMsgPhoto(event)">
                    </label>

                    <textarea name="message" id="chatInput" rows="1"
                        class="chat-textarea"
                        placeholder="Ketik pesan..." required
                        oninput="autoResize(this)"></textarea>

                    <button type="submit" class="chat-send-btn" title="Kirim">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
                    </form>
            </div>

        </div>
    </div>
</section>
@endsection

@push('scripts')
<script type="module">
import 'https://cdn.jsdelivr.net/npm/emoji-picker-element@1/index.js';

document.querySelector('emoji-picker')?.addEventListener('emoji-click', e => {
    const ta = document.getElementById('chatInput');
    const pos = ta.selectionStart;
    ta.value = ta.value.slice(0, pos) + e.detail.unicode + ta.value.slice(pos);
    ta.focus();
    document.getElementById('emojiPicker').style.display = 'none';
    autoResize(ta);
});
</script>

<script>
// ===== EMOJI =====
function toggleEmoji() {
    const picker = document.getElementById('emojiPicker');
    picker.style.display = picker.style.display === 'none' ? 'block' : 'none';
}

document.addEventListener('click', function(e) {
    const picker = document.getElementById('emojiPicker');
    if (!picker) return;
    if (!picker.contains(e.target) && !e.target.closest('[onclick="toggleEmoji()"]')) {
        picker.style.display = 'none';
    }
});

// ===== AUTO RESIZE =====
function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}

// ===== FILE PREVIEW =====
function previewMsgPhoto(event) {
    const preview = document.getElementById('msgPhotoPreview');
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML = `
                <div class="position-relative d-inline-block m-2">
                    <img src="${e.target.result}" class="rounded-3"
                         style="max-width:80px; max-height:80px; object-fit:cover;">
                    <button type="button" onclick="clearMsgPhoto()"
                        class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle"
                        style="width:18px;height:18px;padding:0;font-size:0.55rem;">
                        <i class="bi bi-x"></i>
                    </button>
                </div>`;
        };
        reader.readAsDataURL(file);
    }
}

function clearMsgPhoto() {
    document.getElementById('photoInput').value = '';
    document.getElementById('msgPhotoPreview').innerHTML = '';
}

// ===== AUTO SCROLL =====
window.addEventListener('load', () => {
    const c = document.getElementById('chatArea');
    if (c) c.scrollTop = c.scrollHeight;
});

// ===== ENTER TO SEND & FORM SUBMIT LOADER =====
const chatForm = document.getElementById('chatForm');
if (chatForm) {
    chatForm.addEventListener('submit', function(e) {
        const btn = this.querySelector('.chat-send-btn');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        }
    });
}

document.getElementById('chatInput')?.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        const form = document.getElementById('chatForm');
        if (form.requestSubmit) {
            form.requestSubmit();
        } else {
            form.submit();
        }
    }
});

// ===== NAVBAR OFFSET =====
function setNavbarOffset() {
    const navbar = document.querySelector('nav.navbar')
                || document.querySelector('header')
                || document.querySelector('.navbar');
    if (navbar) {
        const isMobile = window.innerWidth <= 768;
        const gap = isMobile ? 0 : 12;
        const h = navbar.offsetHeight + gap;
        document.querySelector('.bg-main').style.top = h + 'px';
    }
}
setNavbarOffset();
window.addEventListener('resize', setNavbarOffset);
</script>
@endpush