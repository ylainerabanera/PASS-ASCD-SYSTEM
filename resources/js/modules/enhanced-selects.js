export const setupEnhancedSelects = () => {
    if (!window.Choices) {
        return;
    }

    document.querySelectorAll('select.searchable-select[data-searchable="true"]').forEach((select) => {
        if (select.dataset.choicesApplied) {
            return;
        }

        select.dataset.choicesApplied = 'true';

        new window.Choices(select, {
            searchEnabled: true,
            shouldSort: false,
            placeholder: true,
            placeholderValue: select.getAttribute('data-placeholder') || 'Select option',
            searchPlaceholderValue: 'Type to search...',
            itemSelectText: '',
            searchResultLimit: 999,
            renderChoiceLimit: -1,
            searchFields: ['label', 'value'],
            fuseOptions: {
                threshold: 0.15,
                ignoreLocation: true,
            },
        });
    });
};
