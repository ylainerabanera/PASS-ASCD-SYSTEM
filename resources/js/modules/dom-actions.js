export const setupConfirmActions = () => {
    document.querySelectorAll('[data-confirm]').forEach((element) => {
        element.addEventListener('click', (event) => {
            if (!window.confirm(element.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });
};

export const setupAutoSubmit = () => {
    document.querySelectorAll('[data-auto-submit]').forEach((element) => {
        element.addEventListener('change', () => {
            element.form?.submit();
        });
    });
};

export const setupLogoutLinks = () => {
    document.querySelectorAll('[data-logout-form]').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();

            const form = document.getElementById(link.dataset.logoutForm);
            form?.submit();
        });
    });
};

export const setupOverviewBars = () => {
    document.querySelectorAll('[data-overview-width]').forEach((bar) => {
        bar.style.width = `${bar.dataset.overviewWidth}%`;
    });
};
