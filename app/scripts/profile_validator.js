$(function () {
    $('#updateUsername').on('blur', function () {
        let feedback = $('#usernameFeedback');
        let username = $('#updateUsername');
        let submit = $('#updateUsernameSubmit');

        username.removeClass('is-valid')
        feedback.removeClass('valid-feedback')
        submit.prop('disabled', true);

        if (username.val() === '') {
            username.addClass('is-invalid');
            feedback.addClass('invalid-feedback')
            feedback.text('Username required!');
        }

        if (username.val().length < 5) {
            username.addClass('is-invalid');
            feedback.addClass('invalid-feedback');
            feedback.text('Username too short!');
        } else if (username.val().length > 16) {
            username.addClass('is-invalid');
            feedback.addClass('invalid-feedback')
            feedback.text('Username too long!');
        }

        $.ajax("scripts/validator.php", {
            data: {
                'username': username.val()
            },
            type: "POST",
            success: function (data) {
                feedback.text("");
                username.removeClass('is-valid is-invalid')
                feedback.removeClass('valid-feedback invalid-feedback')

                if (data === "true") {
                    username.addClass('is-valid')
                    feedback.addClass('valid-feedback')
                    feedback.text(`Username ${username.val()} is available`)
                    submit.prop('disabled', false)
                } else if (data !== "same") {
                    username.addClass('is-invalid')
                    feedback.addClass('invalid-feedback')
                    feedback.text(`Username ${username.val()} already in use!`)
                    submit.prop('disabled', true);
                }
            },
            error: function() {
                username.removeClass('is-valid is-invalid')
                feedback.removeClass('valid-feedback invalid-feedback')

                username.addClass('is-invalid')
                feedback.addClass('invalid-feedback')
                feedback.text(`AJAX Error`)
                submit.prop('disabled', true);
            }
        })
    })

    $('#updateFullname').on('blur', function () {
        let feedback = $('#fullnameFeedback');
        let fullname = $('#updateFullname');
        let submit = $('#updateFullnameSubmit');

        fullname.removeClass('is-valid')
        feedback.removeClass('valid-feedback')
        submit.prop('disabled', true);

        if (fullname.val().length > 32) {
            fullname.addClass('is-invalid');
            feedback.addClass('invalid-feedback')
            feedback.text('Display name too long!');
            submit.prop('disabled', true);
        } else {
            fullname.removeClass('is-valid is-invalid')
            feedback.removeClass('valid-feedback invalid-feedback')
            feedback.text('')

            fullname.addClass('is-valid')
            feedback.addClass('valid-feedback')
            submit.prop('disabled', false);
        }
    })


    $('#updateEmail').on('blur', function () {
        let feedback = $('#emailFeedback');
        let email = $('#updateEmail');
        let submit = $('#updateEmailSubmit');

        email.removeClass('is-valid')
        feedback.removeClass('valid-feedback')
        submit.prop('disabled', true);

        if (email.val() === '') {
            email.addClass('is-invalid');
            feedback.addClass('invalid-feedback')
            feedback.text('Email required!');
        }

        $.ajax("scripts/validator.php", {
            data: {
                'email': email.val()
            },
            type: "POST",
            success: function (data) {
                feedback.text('')
                email.removeClass('is-valid is-invalid')
                feedback.removeClass('valid-feedback invalid-feedback')

                if (data === "true") {
                    email.addClass('is-valid')
                    feedback.addClass('valid-feedback')
                    feedback.text(`Email ${email.val()} is available`)
                    submit.prop('disabled', false)
                } else if (data !== "same") {
                    email.addClass('is-invalid')
                    feedback.addClass('invalid-feedback')
                    feedback.text(`Email ${email.val()} already in use!`)
                    submit.prop('disabled', true);
                }
            },
            error: function() {
                email.removeClass('is-valid is-invalid')
                feedback.removeClass('valid-feedback invalid-feedback')

                email.addClass('is-invalid')
                feedback.addClass('invalid-feedback')
                feedback.text(`AJAX Error`)
                submit.prop('disabled', true);
            }
        })
    })

    $('#updatePassword').on('blur', function () {
        let strongPasswordRegex = /^(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]).{8,32}$/

        let feedback = $('#passwordFeedback');
        let password = $('#updatePassword');
        let submit = $('#updatePasswordSubmit');

        let pword = password.val().toString();

        if (pword === "") {
            password.addClass('is-invalid')
            feedback.addClass('invalid-feedback')
            feedback.text("Password required!");
            submit.prop('disabled', true);
            return;
        }

        password.removeClass('is-valid is-invalid')
        feedback.removeClass('valid-feedback invalid-feedback')

        if (pword.length < 8) {
            password.addClass('is-invalid')
            feedback.addClass('invalid-feedback')
            feedback.text(`Password too short!`)
            submit.prop('disabled', true);
            return;
        } else if (pword.length > 32) {
            password.addClass('is-invalid')
            feedback.addClass('invalid-feedback')
            feedback.text(`Password too long!`)
            submit.prop('disabled', true);
            return;
        }

        if (strongPasswordRegex.test(pword)) {
            password.addClass('is-valid')
            feedback.addClass('valid-feedback')
            feedback.text(``)
            submit.prop('disabled', false)
        } else {
            password.addClass('is-invalid')
            feedback.addClass('invalid-feedback')
            feedback.text(`Weak password!`)
            submit.prop('disabled', true);
        }
    })

    $('#updateTheme').on('change', function () {
        $('#updateThemeSubmit').prop('disabled', false);
    })
})