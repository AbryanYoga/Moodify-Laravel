/**
 * show.js
 * Handles AJAX requests and skeleton loading for the Spotify Recommendation System
 */

document.addEventListener('DOMContentLoaded', () => {
    // Initial skeleton removal if images are already cached/loaded fast
    const images = document.querySelectorAll('.track-cover');
    images.forEach(img => {
        if (img.complete) {
            removeSkeleton(img);
        }
    });
});

/**
 * Removes the skeleton class from the parent card when the image has loaded
 * @param {HTMLImageElement} imgElement 
 */
function removeSkeleton(imgElement) {
    const row = imgElement.closest('.track-row');
    if (row) {
        row.classList.remove('skeleton');
    }
}

/**
 * Handles saving a track to Spotify via AJAX
 * @param {HTMLButtonElement} btn 
 */
async function saveTrack(btn) {
    const trackId = btn.getAttribute('data-track-id');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Prevent multiple clicks
    if (btn.classList.contains('saved') || btn.disabled) return;
    
    // UI Loading state
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="ph ph-spinner ph-spin"></i>';
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
            // Success UI update
            btn.innerHTML = '<i class="ph-fill ph-heart"></i>';
            btn.classList.add('saved');
            showToast(data.message || 'Lagu berhasil disimpan ke Spotify', 'success');
        } else {
            // Revert UI on failure
            btn.innerHTML = originalContent;
            btn.disabled = false;
            showToast(data.message || 'Gagal menyimpan lagu', 'error');
            
            if (response.status === 401) {
                // If unauthorized, redirect to login
                setTimeout(() => window.location.href = '/auth/spotify', 2000);
            }
        }
    } catch (error) {
        console.error('Error saving track:', error);
        btn.innerHTML = originalContent;
        btn.disabled = false;
        showToast('Terjadi kesalahan jaringan', 'error');
    }
}

/**
 * Displays a modern toast notification
 * @param {string} message 
 * @param {string} type 'success' | 'error'
 */
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    const icon = type === 'success' 
        ? '<i class="ph-fill ph-check-circle"></i>' 
        : '<i class="ph-fill ph-warning-circle"></i>';
        
    toast.innerHTML = `
        ${icon}
        <span>${message}</span>
    `;

    container.appendChild(toast);

    // Trigger animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);

    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 400); // Wait for transition out
    }, 3000);
}