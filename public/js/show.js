document.addEventListener('DOMContentLoaded', () => {
    const trackContainer = document.getElementById('track-container');
    const moodId = document.getElementById('mood-data').dataset.id;
    
    // On load, fetch tracks automatically
    fetchRecommendations(moodId);
});

async function fetchRecommendations(moodId) {
    const trackContainer = document.getElementById('track-container');
    
    // Render skeleton loaders initially
    renderSkeletons(5);

    try {
        const response = await fetch(`/spotify/recommendation/${moodId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        // Remove skeletons
        trackContainer.innerHTML = '';

        if (response.ok && data.success) {
            if (data.data && data.data.length > 0) {
                renderTracks(data.data);
            } else {
                renderEmptyState('Tidak ada lagu ditemukan', 'Maaf, kami tidak dapat menemukan lagu untuk mood ini saat ini.');
            }
        } else {
            // Handle specific errors like 401 Unauthorized
            if (response.status === 401) {
                renderErrorState('Sesi Berakhir', 'Sesi Spotify tidak valid atau telah habis. Silakan login kembali.', true);
            } else {
                renderErrorState('Gagal Memuat', data.message || 'Terjadi kesalahan saat memuat lagu.');
            }
        }

    } catch (error) {
        console.error('Fetch error:', error);
        trackContainer.innerHTML = '';
        renderErrorState('Koneksi Bermasalah', 'Gagal terhubung ke server. Periksa koneksi internet Anda.');
    }
}

function renderSkeletons(count) {
    const container = document.getElementById('track-container');
    let html = '';
    for (let i = 0; i < count; i++) {
        html += `
            <div class="sk-card skeleton">
                <div class="sk-cover"></div>
                <div class="sk-text">
                    <div class="sk-title"></div>
                    <div class="sk-subtitle"></div>
                </div>
            </div>
        `;
    }
    container.innerHTML = html;
}

function renderTracks(tracks) {
    const container = document.getElementById('track-container');
    
    // Update count title if exists
    const countSpan = document.getElementById('track-count');
    if(countSpan) countSpan.innerText = `${tracks.length} lagu`;

    let html = '<div class="track-grid">';
    
    tracks.forEach(track => {
        const coverUrl = track.album.images[2]?.url || track.album.images[0]?.url || '';
        const artists = track.artists.map(a => a.name).join(', ');
        const albumName = track.album.name;
        
        html += `
            <div class="track-card">
                <div class="track-left">
                    ${coverUrl ? `<img src="${coverUrl}" alt="Cover" class="track-cover" loading="lazy">` : `<div class="track-cover skeleton"></div>`}
                    <div class="track-info">
                        <h4 class="track-name">${track.name}</h4>
                        <p class="track-artist">${artists} • ${albumName}</p>
                    </div>
                </div>
                <div class="track-right">
                    <a href="${track.external_urls.spotify}" target="_blank" class="btn-play" title="Play di Spotify">
                        <i class="ph-fill ph-play"></i>
                    </a>
                    <button class="btn-save" data-track-id="${track.id}" onclick="saveTrack(this)">
                        <i class="ph ph-heart"></i> Save
                    </button>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    container.innerHTML = html;
}

function renderEmptyState(title, message) {
    const container = document.getElementById('track-container');
    container.innerHTML = `
        <div class="empty-state">
            <i class="ph-fill ph-ghost"></i>
            <h3>${title}</h3>
            <p>${message}</p>
        </div>
    `;
}

function renderErrorState(title, message, isAuthError = false) {
    const container = document.getElementById('track-container');
    let actionHtml = '';
    
    if (isAuthError) {
        actionHtml = `<a href="/auth/spotify" class="btn-primary" style="margin-top: 15px;">Login ke Spotify</a>`;
    }

    container.innerHTML = `
        <div class="empty-state">
            <i class="ph-fill ph-warning-circle" style="color: var(--error);"></i>
            <h3>${title}</h3>
            <p>${message}</p>
            ${actionHtml}
        </div>
    `;
}

async function saveTrack(btn) {
    const trackId = btn.getAttribute('data-track-id');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    if (btn.classList.contains('saved') || btn.disabled) return;
    
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="ph ph-spinner ph-spin"></i> Saving...';
    btn.disabled = true;

    try {
        const response = await fetch('/spotify/save-track', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ track_id: trackId })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            btn.innerHTML = '<i class="ph-fill ph-check-circle"></i> Saved';
            btn.classList.add('saved');
            showToast(data.message || 'Lagu berhasil disimpan!', 'success');
        } else {
            btn.innerHTML = originalContent;
            btn.disabled = false;
            showToast(data.message || 'Gagal menyimpan lagu', 'error');
            
            if (response.status === 401) {
                setTimeout(() => window.location.href = '/auth/spotify', 2000);
            }
        }
    } catch (error) {
        btn.innerHTML = originalContent;
        btn.disabled = false;
        showToast('Terjadi kesalahan jaringan', 'error');
    }
}

function showToast(message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    const icon = type === 'success' ? 'ph-check-circle' : 'ph-warning-circle';
        
    toast.innerHTML = `<i class="ph-fill ${icon}"></i> <span>${message}</span>`;
    container.appendChild(toast);

    // Trigger animation
    setTimeout(() => toast.classList.add('show'), 10);
    
    // Auto remove
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}