// Preview foto event
document.getElementById('imageInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        let preview = document.getElementById('foto-preview');
        if (!preview) {
            preview = document.createElement('img');
            preview.id = 'foto-preview';
            preview.style.cssText = 'width:100%; max-height:200px; object-fit:cover; border-radius:8px; margin-top:10px;';
            document.getElementById('imageInput').closest('div').appendChild(preview);
        }
        preview.src = e.target.result;
    };
    reader.readAsDataURL(file);
});