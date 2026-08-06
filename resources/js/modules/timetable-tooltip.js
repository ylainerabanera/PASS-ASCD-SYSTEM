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

export const setupTimetableTooltip = () => {
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
};
