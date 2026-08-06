export const setupSweetAlerts = async () => {
    const { success, error, validation, redirect } = window.SweetAlertMessages || {};

    if (validation?.length) {
        await Swal.fire({
            icon: 'error',
            title: 'Validation error',
            html: validation.map((message) => `<div>${message}</div>`).join(''),
            confirmButtonText: 'OK',
        });
        return;
    }

    if (error) {
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error,
            confirmButtonText: 'OK',
        });
        return;
    }

    if (success) {
        await Swal.fire({
            icon: 'success',
            title: 'Success',
            text: success,
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
        });

        if (redirect) {
            window.location.href = redirect;
        }
    }
};
