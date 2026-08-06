export const setupGlobalSearch = () => {
    const input = document.querySelector('[data-global-search]');

    if (!input) {
        return;
    }

    const rows = Array.from(document.querySelectorAll('table tbody tr'));
    const listItems = Array.from(document.querySelectorAll('.list-group-item'));

    const filterItems = () => {
        const term = input.value.trim().toLowerCase();

        rows.forEach((row) => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });

        listItems.forEach((item) => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(term) ? '' : 'none';
        });
    };

    input.addEventListener('input', filterItems);
};
