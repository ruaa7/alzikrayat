/**
 * validate.js
 *
 * Lightweight client-side validation layer. Adds Bootstrap's
 * "was-validated" class on submit so invalid-feedback messages show,
 * and blocks submission until native HTML5 constraints pass. This is
 * a convenience/UX layer only -- the server re-validates everything.
 */
(function () {
    'use strict';

    const forms = document.querySelectorAll('#loginForm, #registerForm, #uploadForm, #commentForm');

    forms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Extra password confirmation UX for the register form, if a
    // confirm field is present.
    const pwd = document.getElementById('password');
    const confirm = document.getElementById('password_confirm');
    if (pwd && confirm) {
        confirm.addEventListener('input', function () {
            confirm.setCustomValidity(confirm.value === pwd.value ? '' : 'Passwords do not match.');
        });
    }
})();
