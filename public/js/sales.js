
document.addEventListener('DOMContentLoaded', function() {

    // ===== MAINTAIN ACTIVE TAB =====
    const hash = window.location.hash;
    if (hash) {
        const tabTrigger = document.querySelector(`button[data-bs-target="${hash}"]`);
        if (tabTrigger) { new bootstrap.Tab(tabTrigger).show(); }
    }
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(button => {
        button.addEventListener('shown.bs.tab', function(e) {
            history.replaceState(null, null, e.target.getAttribute('data-bs-target'));
        });
    });

    // ===== ENTRIES PER PAGE =====
    document.getElementById('entriesPerPageTopSelling')?.addEventListener('change', function() {
        window.location.href = '?per_page=' + this.value + '#top-selling';
    });
    document.getElementById('entriesPerPageSales')?.addEventListener('change', function() {
        window.location.href = '?per_page=' + this.value + '#all-bookings';
    });
    document.getElementById('entriesPerPageUnpaid')?.addEventListener('change', function() {
        window.location.href = '?per_page=' + this.value + '#unpaid-bookings';
    });

    // ===== SEARCH =====
    function setupSearch(searchId, tableId) {
        const searchInput = document.getElementById(searchId);
        const table = document.getElementById(tableId);
        if (table && searchInput) {
            const rows = Array.from(table.getElementsByTagName('tr'));
            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase();
                rows.forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
                });
            });
        }
    }
    setupSearch('searchTopSelling', 'topSellingTableBody');
    setupSearch('searchSales', 'salesTableBody');
    setupSearch('searchUnpaid', 'unpaidTableBody');

    // ===== COUNTDOWN TIMER =====
    function updateCountdowns() {
    document.querySelectorAll('.countdown-timer').forEach(timerEl => {
    const timer = /** @type {HTMLElement} */ (timerEl);
    const expiryDate = new Date(timer.dataset.expiry ?? '');
    const diff = expiryDate.getTime() - new Date().getTime();
    if (diff <= 0) {
        timer.innerHTML = '<span class="badge-expired"><i class="bi bi-x-circle me-1"></i>Kedaluwarsa</span>';
        return;
    }
            const hours   = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const hoursEl   = timer.querySelector('.hours');
            const minutesEl = timer.querySelector('.minutes');
            if (hoursEl)   hoursEl.textContent   = hours;
            if (minutesEl) minutesEl.textContent = minutes;
            const badge = timer.querySelector('.badge');
            if (badge) {
                const total = hours * 60 + minutes;
                badge.className = 'badge fs-6';
                if (total < 60)       badge.classList.add('bg-danger', 'pulse-animation');
                else if (total < 180) badge.classList.add('bg-warning', 'text-dark');
                else                  badge.classList.add('bg-secondary');
            }
        });
    }
    updateCountdowns();
    setInterval(updateCountdowns, 1000);

    setTimeout(() => {
        if (window.location.hash === '#unpaid-bookings') location.reload();
    }, 5 * 60 * 1000);
});
