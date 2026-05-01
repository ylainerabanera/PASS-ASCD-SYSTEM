import './bootstrap';

const toggleRoomField = () => {
    document.querySelectorAll('[data-schedule-form]').forEach((form) => {
        const classTypeSelect = form.querySelector('[data-class-type]');
        const roomWrapper = form.querySelector('[data-room-wrapper]');
        const roomSelect = form.querySelector('select[name="room_id"]');
        const setSelect = form.querySelector('select[name="set_id"]');
        const roomCapacity = form.querySelector('[data-room-capacity]');
        const studentCount = form.querySelector('[data-student-count]');

        //gcode added here
        const gcodeWrapper = form.querySelector('[data-gcode-wrapper]');

        if (!classTypeSelect || !roomWrapper) {
            return;
        }

        const updateVisibility = () => {
            if (classTypeSelect.value === 'online') {
                roomWrapper.classList.add('d-none');
            
                //gcode added here again
                if (gcodeWrapper) {
                    gcodeWrapper.classList.remove('d-none');
                }
            
            } else {
                roomWrapper.classList.remove('d-none');
            
                //and again
                if (gcodeWrapper) {
                    gcodeWrapper.classList.add('d-none');
                }
            }
        };

        classTypeSelect.addEventListener('change', updateVisibility);
        updateVisibility();

        const updateRoomCapacity = () => {
            if (!roomSelect || !roomCapacity) {
                return;
            }
            const selected = roomSelect.options[roomSelect.selectedIndex];
            const capacity = selected?.dataset?.capacity;
            roomCapacity.textContent = capacity ? `Capacity: ${capacity}` : 'Capacity: -';
        };

        const updateStudentCount = () => {
            if (!setSelect || !studentCount) {
                return;
            }
            const selected = setSelect.options[setSelect.selectedIndex];
            const students = selected?.dataset?.students;
            studentCount.textContent = students ? `Students: ${students}` : 'Students: -';
        };

        if (roomSelect) {
            roomSelect.addEventListener('change', updateRoomCapacity);
            updateRoomCapacity();
        }

        if (setSelect) {
            setSelect.addEventListener('change', updateStudentCount);
            updateStudentCount();
        }
    });
};

document.addEventListener('DOMContentLoaded', toggleRoomField);

document.addEventListener('DOMContentLoaded', () => {
    const layout = document.querySelector('.app-layout');
    const toggle = document.querySelector('[data-sidebar-toggle]');
    const reportToggle = document.querySelector('.report-toggle');
    const reportMenu = document.querySelector('#reportMenu');

    if (!layout || !toggle) {
        return;
    }

    const stored = localStorage.getItem('sidebar-collapsed');
    if (stored === '1') {
        layout.classList.add('sidebar-collapsed');
        document.documentElement.classList.add('sidebar-collapsed');
    }

    toggle.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            layout.classList.remove('sidebar-collapsed');
            document.documentElement.classList.remove('sidebar-collapsed');
            layout.classList.toggle('sidebar-open');
            return;
        }

        layout.classList.toggle('sidebar-collapsed');
        const isCollapsed = layout.classList.contains('sidebar-collapsed');
        localStorage.setItem('sidebar-collapsed', isCollapsed ? '1' : '0');
        document.documentElement.classList.toggle('sidebar-collapsed', isCollapsed);
    });

    if (reportToggle) {
        reportToggle.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                layout.classList.remove('sidebar-collapsed');
                document.documentElement.classList.remove('sidebar-collapsed');
                layout.classList.add('sidebar-open');
                return;
            }
            if (layout.classList.contains('sidebar-collapsed')) {
                layout.classList.remove('sidebar-collapsed');
                document.documentElement.classList.remove('sidebar-collapsed');
                localStorage.setItem('sidebar-collapsed', '0');
                if (reportMenu) {
                    reportMenu.classList.add('show');
                    reportToggle.setAttribute('aria-expanded', 'true');
                }
            }
        });
    }

    document.querySelectorAll('.sidebar a').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                layout.classList.remove('sidebar-open');
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
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
});

document.addEventListener('DOMContentLoaded', () => {
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
});

let activeTooltip = null;

const showTooltip = (event, text) => {
    if (!text) {
        return;
    }

    if (!activeTooltip) {
        activeTooltip = document.createElement('div');
        activeTooltip.className = 'floating-tooltip';
        document.body.appendChild(activeTooltip);
    }

    activeTooltip.textContent = text;

    const offsetX = 14;
    const offsetY = -10;
    const x = event.clientX + offsetX;
    const y = event.clientY + offsetY;

    activeTooltip.style.left = `${x}px`;
    activeTooltip.style.top = `${y}px`;
};

const hideTooltip = () => {
    if (activeTooltip) {
        activeTooltip.remove();
        activeTooltip = null;
    }
};

document.addEventListener('mouseover', (event) => {
    const cell = event.target.closest('.timetable-cell.filled');
    if (!cell) {
        return;
    }
    const text = cell.getAttribute('data-tooltip');
    showTooltip(event, text);
});

document.addEventListener('mousemove', (event) => {
    if (!activeTooltip) {
        return;
    }
    const offsetX = 14;
    const offsetY = -10;
    activeTooltip.style.left = `${event.clientX + offsetX}px`;
    activeTooltip.style.top = `${event.clientY + offsetY}px`;
});

document.addEventListener('mouseout', (event) => {
    const cell = event.target.closest('.timetable-cell.filled');
    if (!cell) {
        return;
    }
    hideTooltip();
});
