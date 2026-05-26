@extends('layouts.admin')

@section('title', 'Manage Moods')

@push('styles')
<style>
    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .search-filter {
        display: flex;
        gap: 12px;
        flex: 1;
        max-width: 600px;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-color);
        color: white;
        padding: 10px 16px;
        border-radius: 8px;
        outline: none;
        transition: all 0.3s;
        width: 100%;
        font-family: inherit;
    }

    .form-control:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 2px rgba(29, 185, 84, 0.2);
    }

    .mood-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
    }

    .mood-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .mood-card:hover {
        transform: translateY(-5px);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .mood-image {
        width: 100%;
        height: 160px;
        object-fit: cover;
        background: #222;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mood-content {
        padding: 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .mood-genre {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--accent);
        font-weight: 600;
        margin-bottom: 4px;
    }

    .mood-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .mood-desc {
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin-bottom: 16px;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .mood-actions {
        display: flex;
        gap: 8px;
        border-top: 1px solid var(--border-color);
        padding-top: 16px;
    }

    .mood-actions .btn {
        flex: 1;
        justify-content: center;
        padding: 8px;
        font-size: 0.85rem;
    }

    .empty-state {
        grid-column: 1 / -1;
        background: var(--bg-card);
        border: 1px dashed var(--border-color);
        border-radius: 16px;
        padding: 48px 24px;
        text-align: center;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 3rem;
        color: var(--accent);
        opacity: 0.5;
        margin-bottom: 16px;
    }

    .pagination-wrapper {
        margin-top: 32px;
        display: flex;
        justify-content: center;
    }

    /* Override Laravel Pagination to look dark & modern */
    .pagination {
        display: flex;
        gap: 4px;
        list-style: none;
    }
    .page-link {
        padding: 8px 12px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .page-link:hover {
        background: var(--bg-card-hover);
        border-color: var(--text-secondary);
    }
    .page-item.active .page-link {
        background: var(--accent);
        color: #000;
        border-color: var(--accent);
    }
    .page-item.disabled .page-link {
        opacity: 0.5;
        pointer-events: none;
    }

    /* Modal */
    .modal-backdrop {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .modal-backdrop.show {
        display: flex;
        opacity: 1;
    }
    .modal-content {
        background: #18181b;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 32px;
        max-width: 400px;
        width: 90%;
        text-align: center;
        transform: translateY(20px);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .modal-backdrop.show .modal-content {
        transform: translateY(0);
    }
    .modal-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 16px;
    }
</style>
@endpush

@section('content')

<div class="header-actions">
    <form action="{{ route('admin.moods.index') }}" method="GET" class="search-filter">
        <input type="text" name="search" class="form-control" placeholder="Search moods..." value="{{ request('search') }}">
        
        <select name="genre" class="form-control" style="max-width: 200px;" onchange="this.form.submit()">
            <option value="">All Genres</option>
            @foreach($genres as $g)
                @if($g)
                <option value="{{ $g }}" {{ request('genre') == $g ? 'selected' : '' }}>{{ $g }}</option>
                @endif
            @endforeach
        </select>
        
        @if(request('search') || request('genre'))
            <a href="{{ route('admin.moods.index') }}" class="btn btn-secondary" title="Clear Filters">
                <i class="ph ph-x"></i>
            </a>
        @endif
        
        <button type="submit" style="display: none;"></button>
    </form>
    
    <a href="{{ route('admin.moods.create') }}" class="btn btn-primary">
        <i class="ph-bold ph-plus"></i> Add New Mood
    </a>
</div>

<div class="mood-grid">
    @forelse($moods as $mood)
        <div class="mood-card">
            @if($mood->image)
                <img src="{{ asset('images/'.$mood->image) }}" alt="{{ $mood->nama }}" class="mood-image" loading="lazy">
            @else
                <div class="mood-image">
                    <i class="ph ph-image" style="font-size: 3rem; color: var(--text-secondary);"></i>
                </div>
            @endif
            
            <div class="mood-content">
                <div class="mood-genre">{{ $mood->genre }}</div>
                <h3 class="mood-title">{{ $mood->nama }}</h3>
                <p class="mood-desc">{{ $mood->description ?? 'No description provided.' }}</p>
                
                <div class="mood-actions">
                    <a href="{{ route('admin.moods.edit', $mood->id) }}" class="btn btn-secondary">
                        <i class="ph ph-pencil-simple"></i> Edit
                    </a>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $mood->id }}, '{{ addslashes($mood->nama) }}')">
                        <i class="ph ph-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="ph-fill ph-ghost"></i>
            <h3>No moods found</h3>
            <p>Try adjusting your search or add a new mood.</p>
        </div>
    @endforelse
</div>

<div class="pagination-wrapper">
    {{ $moods->links('pagination::bootstrap-4') }}
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal-backdrop">
    <div class="modal-content">
        <div class="modal-icon">
            <i class="ph-fill ph-warning"></i>
        </div>
        <h3 style="margin-bottom: 8px;">Delete Mood</h3>
        <p style="color: var(--text-secondary); margin-bottom: 24px;">Are you sure you want to delete "<span id="deleteMoodName" style="color: white; font-weight: bold;"></span>"? This action cannot be undone.</p>
        
        <form id="deleteForm" method="POST" style="display: flex; gap: 12px;">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-secondary" style="flex: 1; justify-content: center;" onclick="closeModal()">Cancel</button>
            <button type="submit" class="btn btn-danger" style="flex: 1; justify-content: center;">Delete</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function confirmDelete(id, name) {
        document.getElementById('deleteMoodName').textContent = name;
        document.getElementById('deleteForm').action = `/admin/moods/${id}`;
        
        const modal = document.getElementById('deleteModal');
        modal.classList.add('show');
    }
    
    function closeModal() {
        document.getElementById('deleteModal').classList.remove('show');
    }

    // Close on click outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if(e.target === this) closeModal();
    });
</script>
@endpush
