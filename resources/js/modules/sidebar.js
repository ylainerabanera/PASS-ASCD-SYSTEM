export const setupSidebar = () => {
    const layout = document.querySelector('.app-layout');
    const toggle = document.querySelector('[data-sidebar-toggle]');
    const reportToggle = document.querySelector('.report-toggle');
    const reportMenu = document.querySelector('#reportMenu');
    const sidebarBreakpoint = 992;

    if (!layout || !toggle) {
        return;
    }

    const isCompactLayout = () => window.innerWidth <= sidebarBreakpoint;

    const clearCollapsedSidebar = () => {
        layout.classList.remove('sidebar-collapsed');
        document.documentElement.classList.remove('sidebar-collapsed');
    };

    const closeSidebar = () => {
        if (isCompactLayout()) {
            layout.classList.remove('sidebar-open');
            toggle.setAttribute('aria-expanded', 'false');
            return;
        }

        layout.classList.add('sidebar-collapsed');
        document.documentElement.classList.add('sidebar-collapsed');
        toggle.setAttribute('aria-expanded', 'false');
    };

    const openSidebar = () => {
        if (isCompactLayout()) {
            clearCollapsedSidebar();
            layout.classList.add('sidebar-open');
            toggle.setAttribute('aria-expanded', 'true');
            return;
        }

        clearCollapsedSidebar();
        toggle.setAttribute('aria-expanded', 'true');
    };

    const syncSidebarForViewport = () => {
        if (isCompactLayout()) {
            clearCollapsedSidebar();
            toggle.setAttribute('aria-expanded', layout.classList.contains('sidebar-open') ? 'true' : 'false');
            return;
        }

        clearCollapsedSidebar();
        layout.classList.remove('sidebar-open');
        localStorage.setItem('sidebar-collapsed', '0');
        toggle.setAttribute('aria-expanded', 'true');
    };

    syncSidebarForViewport();

    toggle.addEventListener('click', () => {
        if (isCompactLayout()) {
            if (layout.classList.contains('sidebar-open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
            return;
        }

        if (layout.classList.contains('sidebar-collapsed')) {
            openSidebar();
        } else {
            closeSidebar();
        }
    });

    if (reportToggle) {
        reportToggle.addEventListener('click', () => {
            if (isCompactLayout()) {
                clearCollapsedSidebar();
                layout.classList.add('sidebar-open');
                toggle.setAttribute('aria-expanded', 'true');
                return;
            }

            if (layout.classList.contains('sidebar-collapsed')) {
                openSidebar();

                if (reportMenu) {
                    reportMenu.classList.add('show');
                    reportToggle.setAttribute('aria-expanded', 'true');
                }
            }
        });
    }

    document.querySelectorAll('.sidebar a').forEach((link) => {
        link.addEventListener('click', () => {
            if (isCompactLayout()) {
                closeSidebar();
            }
        });
    });

    document.addEventListener('click', (event) => {
        if (!isCompactLayout() || !layout.classList.contains('sidebar-open')) {
            return;
        }

        const clickedSidebar = event.target.closest('.sidebar');
        const clickedToggle = event.target.closest('[data-sidebar-toggle]');

        if (!clickedSidebar && !clickedToggle) {
            closeSidebar();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });

    window.addEventListener('resize', () => {
        layout.classList.remove('sidebar-open');
        syncSidebarForViewport();
    });
};
