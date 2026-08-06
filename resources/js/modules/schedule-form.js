export const toggleRoomField = () => {
    document.querySelectorAll('[data-schedule-form]').forEach((form) => {
        const classTypeSelect = form.querySelector('[data-class-type]');
        const roomWrapper = form.querySelector('[data-room-wrapper]');
        const roomSelect = form.querySelector('select[name="room_id"]');
        const setSelect = form.querySelector('select[name="set_id"]');
        const roomCapacity = form.querySelector('[data-room-capacity]');
        const studentCount = form.querySelector('[data-student-count]');
        const gcodeWrapper = form.querySelector('[data-gcode-wrapper]');

        if (!classTypeSelect || !roomWrapper) {
            return;
        }

        const updateVisibility = () => {
            const isOnline = classTypeSelect.value === 'online';

            roomWrapper.classList.toggle('d-none', isOnline);

            if (gcodeWrapper) {
                gcodeWrapper.classList.toggle('d-none', !isOnline);
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
