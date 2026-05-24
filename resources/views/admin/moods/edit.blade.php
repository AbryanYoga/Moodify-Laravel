@extends('layouts.admin')

@section('title', 'Edit Mood')

@push('styles')
<style>
    .form-container {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 32px;
        max-width: 800px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--text-secondary);
    }

    .form-control {
        width: 100%;
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid var(--border-color);
        color: white;
        padding: 12px 16px;
        border-radius: 8px;
        outline: none;
        transition: all 0.3s;
        font-family: inherit;
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 2px rgba(29, 185, 84, 0.2);
        background: rgba(0, 0, 0, 0.4);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    /* Custom File Upload */
    .image-upload-wrapper {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }

    .image-preview {
        width: 160px;
        height: 160px;
        border-radius: 12px;
        background: rgba(0, 0, 0, 0.3);
        border: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .upload-btn-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
    }

    .upload-btn-wrapper input[type=file] {
        font-size: 100px;
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        cursor: pointer;
        height: 100%;
    }
    
    .invalid-feedback {
        color: var(--danger);
        font-size: 0.85rem;
        margin-top: 6px;
        display: block;
    }

    .form-control.is-invalid {
        border-color: var(--danger);
    }
    
    .submit-btn {
        width: 100%;
        justify-content: center;
        padding: 14px;
        font-size: 1.05rem;
        margin-top: 16px;
    }
</style>
@endpush

@section('content')

<div class="form-container">
    <form action="{{ route('admin.moods.update', $mood->id) }}" method="POST" enctype="multipart/form-data" id="moodForm">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">Image Cover</label>
            <div class="image-upload-wrapper">
                <div class="image-preview" id="imagePreviewContainer">
                    @if($mood->image)
                        <img id="previewImage" src="{{ asset('images/moods/'.$mood->image) }}" alt="Preview">
                    @else
                        <i class="ph ph-image image-preview-icon" id="previewIcon" style="font-size: 2.5rem; color: var(--text-secondary);"></i>
                        <img id="previewImage" src="" alt="Preview" style="display: none;">
                    @endif
                </div>
                <div style="flex: 1;">
                    <div class="upload-btn-wrapper">
                        <button type="button" class="btn btn-secondary">
                            <i class="ph ph-upload-simple"></i> Change Image
                        </button>
                        <input type="file" name="image" id="imageInput" accept="image/jpeg,image/png,image/webp,image/jpg">
                    </div>
                    <p style="color: var(--text-secondary); font-size: 0.85rem; margin-top: 12px;">
                        Leave empty if you don't want to change the image.<br>
                        Recommended size: 800x800px. Max size: 2MB. Format: JPG, PNG, WEBP.
                    </p>
                    @error('image')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div class="form-group">
                <label class="form-label" for="nama">Mood Name</label>
                <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $mood->nama) }}" required placeholder="e.g. Focus Time">
                @error('nama')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="genre">Genre Keyword</label>
                <input type="text" id="genre" name="genre" class="form-control @error('genre') is-invalid @enderror" value="{{ old('genre', $mood->genre) }}" required placeholder="e.g. lo-fi, pop, acoustic">
                @error('genre')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="color_theme">Color Theme (Optional)</label>
            <input type="text" id="color_theme" name="color_theme" class="form-control @error('color_theme') is-invalid @enderror" value="{{ old('color_theme', $mood->color_theme) }}" placeholder="e.g. #1db954">
            @error('color_theme')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label" for="description">Description</label>
            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Describe the vibe of this mood...">{{ old('description', $mood->description) }}</textarea>
            @error('description')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary submit-btn" id="submitBtn">
            <i class="ph-bold ph-floppy-disk"></i> Update Mood
        </button>
    </form>
</div>

@endsection

@push('scripts')
<script>
    const imageInput = document.getElementById('imageInput');
    const previewImage = document.getElementById('previewImage');
    const container = document.getElementById('imagePreviewContainer');
    const previewIcon = document.getElementById('previewIcon');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                if(previewIcon) previewIcon.style.display = 'none';
                container.style.borderColor = 'var(--accent)';
            }
            reader.readAsDataURL(file);
        }
    });

    const form = document.getElementById('moodForm');
    const submitBtn = document.getElementById('submitBtn');
    
    form.addEventListener('submit', function() {
        submitBtn.innerHTML = '<i class="ph ph-spinner ph-spin"></i> Updating...';
        submitBtn.style.opacity = '0.7';
        submitBtn.style.pointerEvents = 'none';
    });
</script>
@endpush
