@extends('admin.layouts.app')

@section('title', 'Chat - {{ $user["name"] }}')

@section('content')
<div class="d-flex flex-column" style="height: 100vh; overflow:hidden;">

    {{-- ===== HEADER ===== --}}
    <div class="d-flex align-items-center gap-3 px-4 py-3 bg-white border-bottom flex-shrink-0">
        <a href="{{ route('admin.contact.index') }}"
           class="btn btn-sm btn-light rounded-circle d-flex align-items-center justify-content-center"
           style="width:36px;height:36px;">
            <i class="bi bi-arrow-left"></i>
        </a>

        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
             style="width:40px;height:40px;background:linear-gradient(135deg,#7c3aed,#a855f7);font-size:1rem;">
            {{ strtoupper(substr($user['name'], 0, 1)) }}
        </div>

        <div class="flex-grow-1">
            <div class="fw-bold lh-1">{{ $user['name'] }}</div>
            <small class="text-muted">{{ $user['email'] }} · {{ $thread->count() }} pesan</small>
        </div>

        @if($thread->where('status','unread')->count() > 0)
            <span class="badge rounded-pill" style="background:#fef3c7;color:#92400e;font-size:0.72rem;">
                {{ $thread->where('status','unread')->count() }} belum dibaca
            </span>
        @else
            <span class="badge rounded-pill" style="background:#f0fdf4;color:#166534;font-size:0.72rem;">
                <i class="bi bi-check-all me-1"></i>Semua terbaca
            </span>
        @endif

        <form action="{{ route('admin.contact.destroy', $message->id) }}" method="POST" class="ms-2">
            @csrf @method('DELETE')
            <button type="submit"
                    class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1"
                    onclick="return confirm('Hapus semua pesan dari user ini?')">
                <i class="bi bi-trash"></i>
                <span class="d-none d-sm-inline">Hapus</span>
            </button>
        </form>
    </div>

    {{-- ===== ALERT ===== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-0 mb-0 py-2 px-4" style="font-size:0.85rem;">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ===== CHAT AREA ===== --}}
    <div id="chatArea" class="flex-grow-1 overflow-y-auto px-4 py-3"
         style="background:#f5f0ff; min-height:0;">

        @foreach($thread as $msg)
            @if($loop->first || $msg->created_at->format('Y-m-d') !== $thread[$loop->index - 1]->created_at->format('Y-m-d'))
                <div class="text-center my-3">
                    <span class="badge rounded-pill px-3 py-2"
                          style="background:rgba(0,0,0,0.07);color:#666;font-size:0.7rem;">
                        {{ $msg->created_at->translatedFormat('d M Y') }}
                    </span>
                </div>
            @endif

            {{-- Bubble User (kiri) --}}
            <div class="d-flex justify-content-start mb-2 align-items-end gap-2">
                <div class="rounded-circle bg-white d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:28px;height:28px;border:2px solid #7c3aed;">
                    <i class="bi bi-person-fill" style="color:#7c3aed;font-size:0.7rem;"></i>
                </div>
                <div style="max-width:65%;">
                    @if($msg->subject && $msg->subject !== 'other')
                        <div style="font-size:0.65rem;color:#888;margin-bottom:2px;padding-left:4px;">
                            {{ ucfirst($msg->subject) }}
                        </div>
                    @endif
                    <div class="px-3 py-2 shadow-sm"
                         style="background:white;color:#1e293b;border-radius:18px 18px 18px 4px;font-size:0.875rem;line-height:1.45;">
                        {{ $msg->message }}
                        @if($msg->photo_path)
                            <div class="mt-2">
                                <img src="{{ Storage::url($msg->photo_path) }}"
                                     class="rounded" style="max-width:200px;max-height:200px;object-fit:cover;">
                                <div class="mt-1">
                                    <a href="{{ route('admin.contact.download', $msg->id) }}"
                                       class="btn btn-xs btn-outline-secondary"
                                       style="font-size:0.7rem;padding:2px 8px;">
                                        <i class="bi bi-download me-1"></i>Download
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div style="font-size:0.62rem;color:#aaa;margin-top:3px;padding-left:4px;">
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

            {{-- Bubble Admin (kanan) --}}
            @if($msg->admin_notes)
                <div class="d-flex justify-content-end mb-3 align-items-end gap-2">
                    <div style="max-width:65%;">
                        <div class="px-3 py-2 shadow-sm"
                             style="background:linear-gradient(135deg,#7c3aed,#9333ea);color:white;border-radius:18px 18px 4px 18px;font-size:0.875rem;line-height:1.45;">
                            {{ $msg->admin_notes }}
                        </div>
                        <div class="text-end" style="font-size:0.62rem;color:#aaa;margin-top:3px;padding-right:4px;">
                            Admin · {{ $msg->replied_at ? $msg->replied_at->format('H:i') : '' }}
                            <i class="bi bi-check-all" style="color:#a78bfa;"></i>
                        </div>
                    </div>
                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:28px;height:28px;border:2px solid #7c3aed;">
                        <i class="bi bi-headset" style="color:#7c3aed;font-size:0.7rem;"></i>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- ===== INPUT BALAS ===== --}}
    <div class="flex-shrink-0 bg-white border-top px-4 py-3"
         style="margin:0 8px 8px; border-radius:16px; box-shadow:0 -2px 12px rgba(0,0,0,0.05);">

        @if($errors->any())
            <div class="alert alert-danger border-0 py-2 small mb-2 rounded-3">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        @endif

        {{-- Emoji Picker --}}
        <div id="emojiPicker" style="display:none; position:absolute; bottom:90px; left:50%; transform:translateX(-50%); z-index:999;">
            <emoji-picker></emoji-picker>
        </div>

        {{-- Preview file --}}
        <div id="filePreview" class="mb-2 p-2 rounded-3 border d-flex align-items-center gap-2"
             style="display:none !important; background:#f8f5ff;">
            <i class="bi bi-paperclip text-muted"></i>
            <span id="fileName" class="small text-muted flex-grow-1 text-truncate"></span>
            <button type="button" onclick="removeFile()" class="btn btn-sm btn-link text-danger p-0">
                <i class="bi bi-x-circle"></i>
            </button>
        </div>

        <form action="{{ route('admin.contact.update', $message->id) }}"
              method="POST" id="replyForm" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="d-flex align-items-end gap-2">
                {{-- Emoji button --}}
                <button type="button" onclick="toggleEmoji()"
                    class="btn d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width:38px;height:38px;border-radius:50%;background:#f5f0ff;border:none;font-size:1.1rem;"
                    title="Emoji">😊</button>

                {{-- File button --}}
                <button type="button" onclick="document.getElementById('fileInput').click()"
                    class="btn d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width:38px;height:38px;border-radius:50%;background:#f5f0ff;border:none;"
                    title="Kirim File">
                    <i class="bi bi-paperclip" style="color:#7c3aed;font-size:1rem;"></i>
                </button>

                {{-- Textarea --}}
                <textarea name="admin_notes" id="replyInput" rows="1"
                    class="form-control rounded-4 flex-grow-1"
                    style="resize:none;border-color:#e2e8f0;font-size:0.88rem;padding:10px 16px;overflow:hidden;max-height:120px;"
                    placeholder="Tulis balasan..."
                    oninput="autoResize(this)"></textarea>

                {{-- Send button --}}
                <button type="submit"
                    class="btn rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                    style="width:42px;height:42px;background:linear-gradient(135deg,#7c3aed,#a855f7);color:white;border:none;">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </form>
    </div>

</div>

{{-- Input file di luar semua container --}}
<input type="file" id="fileInput" name="attachment"
    style="position:fixed;top:-9999px;left:-9999px;opacity:0;"
    accept="image/*,.pdf,.doc,.docx" onchange="previewFile(this)">

@push('scripts')
<script type="module">
import 'https://cdn.jsdelivr.net/npm/emoji-picker-element@1/index.js';

document.querySelector('emoji-picker')?.addEventListener('emoji-click', e => {
    const ta = document.getElementById('replyInput');
    const pos = ta.selectionStart;
    ta.value = ta.value.slice(0, pos) + e.detail.unicode + ta.value.slice(pos);
    ta.focus();
    document.getElementById('emojiPicker').style.display = 'none';
    autoResize(ta);
});
</script>

<script>
// ===== LAYOUT =====
document.getElementById('mainContent').classList.add('no-padding');
document.body.style.overflow = 'hidden';

// ===== AUTO SCROLL =====
window.addEventListener('load', () => {
    const c = document.getElementById('chatArea');
    if (c) c.scrollTop = c.scrollHeight;
});

// ===== AUTO RESIZE TEXTAREA =====
function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}

// ===== ENTER TO SEND =====
document.getElementById('replyInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('replyForm').submit();
    }
});

// ===== EMOJI PICKER TOGGLE =====
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

// ===== FILE PREVIEW =====
function previewFile(input) {
    if (input.files && input.files[0]) {
        // Pindahkan file ke dalam form sebelum submit
        const form = document.getElementById('replyForm');
        const existingInput = form.querySelector('input[type="file"]');
        if (existingInput) existingInput.remove();

        const cloned = input.cloneNode();
        cloned.style = '';
        cloned.hidden = true;
        form.appendChild(cloned);

        // Transfer file
        const dt = new DataTransfer();
        dt.items.add(input.files[0]);
        cloned.files = dt.files;

        document.getElementById('fileName').textContent = input.files[0].name;
        document.getElementById('filePreview').style.display = 'flex';
    }
}

function removeFile() {
    document.getElementById('fileInput').value = '';
    const form = document.getElementById('replyForm');
    const cloned = form.querySelector('input[type="file"]');
    if (cloned) cloned.remove();
    document.getElementById('filePreview').style.display = 'none';
}
</script>
@endpush
@endsection