export const setupExportLoading = () => {
    document.querySelectorAll('a[href*="/exports/"], a.btn-download').forEach((link) => {
        if (link.dataset.exportLoadingApplied) {
            return;
        }

        link.dataset.exportLoadingApplied = 'true';

        link.addEventListener('click', () => {
            const originalHtml = link.innerHTML;
            const originalWidth = link.offsetWidth;

            link.style.minWidth = `${originalWidth}px`;
            link.classList.add('is-loading');
            link.innerHTML = '<span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>Preparing...';

            setTimeout(() => {
                link.classList.remove('is-loading');
                link.innerHTML = originalHtml;
                link.style.minWidth = '';
            }, 8000);
        });
    });
};
