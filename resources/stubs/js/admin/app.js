import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;

Alpine.start();

document.querySelectorAll('.js-delete-button').forEach((button) => {
	button.addEventListener('click', () => {
		Swal.fire({
            title: typeof SWAL_TITLE !== 'undefined' ? SWAL_TITLE : 'Naozaj chcete odstrániť tento záznam?',
            text: typeof SWAL_TEXT !== 'undefined' ? SWAL_TEXT : 'Túto akciu nebudete môcť vrátiť späť!',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            cancelButtonText: typeof SWAL_CANCEL_BTN !== 'undefined' ? SWAL_CANCEL_BTN : 'Zrušiť',
            confirmButtonText: typeof SWAL_CONFIRM_BTN !== 'undefined' ? SWAL_CONFIRM_BTN : 'Áno, odstrániť!',
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
	});
});
