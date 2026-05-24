@extends('layouts.admin')

@section('title', 'Overview')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at top right, rgba(29, 185, 84, 0.1), transparent);
        pointer-events: none;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(29, 185, 84, 0.1);
        color: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 16px;
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .stat-sub {
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin-top: 8px;
    }

    .latest-section {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
</style>
@endpush

@section('content')

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="ph-fill ph-music-notes"></i>
        </div>
        <div class="stat-label">Total Moods</div>
        <div class="stat-value">{{ number_format($totalMoods) }}</div>
        <div class="stat-sub">Available in library</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="ph-fill ph-heart"></i>
        </div>
        <div class="stat-label">Total Favorites</div>
        <div class="stat-value">{{ number_format($totalFavorites) }}</div>
        <div class="stat-sub">
            @if($latestFavorite)
                <span style="color: var(--accent);">Latest:</span> {{ \Illuminate\Support\Str::limit($latestFavorite->track_name, 20) }} by {{ \Illuminate\Support\Str::limit($latestFavorite->artist_name, 15) }}
            @else
                Saved by users
            @endif
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="ph-fill ph-users"></i>
        </div>
        <div class="stat-label">Total Users</div>
        <div class="stat-value">{{ number_format($totalUsers) }}</div>
        <div class="stat-sub">Registered accounts</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <i class="ph-fill ph-fire"></i>
        </div>
        <div class="stat-label">Popular Genre</div>
        <div class="stat-value" style="font-size: 1.5rem;">{{ $popularGenre->genre ?? 'N/A' }}</div>
        <div class="stat-sub">{{ $popularGenre ? $popularGenre->count . ' moods' : 'No data' }}</div>
    </div>
</div>

<div class="latest-section">
    <div class="section-header">
        <h3>Latest Mood Added</h3>
        <a href="{{ route('admin.moods.index') }}" class="btn btn-secondary">View All</a>
    </div>
    
    @if($latestMood)
    <div style="display: flex; gap: 24px; align-items: center; padding: 16px; background: rgba(0,0,0,0.3); border-radius: 12px;">
        @if($latestMood->image)
            <img src="{{ asset('images/moods/'.$latestMood->image) }}" alt="Cover" style="width: 80px; height: 80px; border-radius: 8px; object-fit: cover;">
        @else
            <div style="width: 80px; height: 80px; border-radius: 8px; background: var(--bg-card); display: flex; align-items: center; justify-content: center;">
                <i class="ph ph-image" style="font-size: 2rem; color: var(--text-secondary);"></i>
            </div>
        @endif
        
        <div>
            <h4 style="font-size: 1.25rem; margin-bottom: 4px;">{{ $latestMood->nama }}</h4>
            <div style="color: var(--accent); font-size: 0.9rem; font-weight: 500; margin-bottom: 8px;">{{ $latestMood->genre }}</div>
            <div style="color: var(--text-secondary); font-size: 0.85rem;">Added {{ $latestMood->created_at->diffForHumans() }}</div>
        </div>
    </div>
    @else
    <p style="color: var(--text-secondary);">Belum ada mood yang ditambahkan.</p>
    @endif
</div>

@endsection
