$(function () {
    const form = $('#signupForm');
    const formSubmit = $('#submitSignup');

    function submittable() {
        let requiredInputs = $('#signupForm :input[required]')
        let invalidInputs = $('#signupForm .is-invalid')
        let allFieldsFilled = true;

        requiredInputs.each(function() {
            if ($(this).val().length === 0) {
                allFieldsFilled = false;
            }
        });

        console.log(`checkValidity: ${form[0].checkValidity()} | invalidInputs.length: ${invalidInputs.length === 0} | allFieldsFilled: ${allFieldsFilled}`)
        return form[0].checkValidity() && invalidInputs.length === 0 && allFieldsFilled;
    }

    formSubmit.prop('disabled', true);

    $('#usernameSignup').on('blur', function (e) {
        let feedback = $('#usernameSignupFeedback');
        let username = $('#usernameSignup');

        if (username.val() === "") {
            username.addClass('is-invalid')
            feedback.addClass('invalid-feedback')
            feedback.text("Username required!");
            formSubmit.prop('disabled', true);
            return;
        }

        if (username.val().length < 5) {
            username.addClass('is-invalid')
            feedback.addClass('invalid-feedback')
            feedback.text("Username too short!");
            formSubmit.prop('disabled', true);
        } else if (username.val().length > 16) {
            username.addClass('is-invalid')
            feedback.addClass('invalid-feedback')
            feedback.text("Username too long!");
            formSubmit.prop('disabled', true);
        }

        $.ajax("scripts/signup_validator.php", {
            data: {
                'username': username.val()
            },
            type: "POST",
            success: function (data) {
                username.removeClass('is-valid is-invalid')
                feedback.removeClass('valid-feedback invalid-feedback')

                if (data === "true") {
                    username.addClass('is-valid')
                    feedback.addClass('valid-feedback')
                    feedback.text(`Username ${username.val()} is available`)
                    formSubmit.prop('disabled', !submittable())
                } else {
                    username.addClass('is-invalid')
                    feedback.addClass('invalid-feedback')
                    feedback.text(`Username ${username.val()} already in use!`)
                    formSubmit.prop('disabled', true);
                }
            },
            error: function() {
                username.removeClass('is-valid is-invalid')
                feedback.removeClass('valid-feedback invalid-feedback')

                username.addClass('is-invalid')
                feedback.addClass('invalid-feedback')
                feedback.text(`AJAX Error`)
                formSubmit.prop('disabled', true);
            }
        })
    });

    $('#emailSignup').on('blur', function (e) {
        let feedback = $('#emailSignupFeedback');
        let email = $('#emailSignup');

        if (email.val() === "") {
            email.addClass('is-invalid')
            feedback.addClass('invalid-feedback')
            feedback.text("Email required!");
            formSubmit.prop('disabled', true);
            return;
        }

        $.ajax("scripts/signup_validator.php", {
            data: {
                'email': email.val()
            },
            type: "POST",
            success: function (data) {
                email.removeClass('is-valid is-invalid')
                feedback.removeClass('valid-feedback invalid-feedback')

                if (data === "true") {
                    email.addClass('is-valid')
                    feedback.addClass('valid-feedback')
                    feedback.text(`Email ${email.val()} is available`)
                    formSubmit.prop('disabled', !submittable())
                } else if (data === "InvalidEmail") {
                    email.addClass('is-invalid')
                    feedback.addClass('invalid-feedback')
                    feedback.text(`Email ${email.val()} is invalid!`)
                    formSubmit.prop('disabled', true);
                } else {
                    email.addClass('is-invalid')
                    feedback.addClass('invalid-feedback')
                    feedback.text(`Email ${email.val()} already in use!`)
                    formSubmit.prop('disabled', true);
                }
            },
            error: function() {
                email.removeClass('is-valid is-invalid')
                feedback.removeClass('valid-feedback invalid-feedback')

                email.addClass('is-invalid')
                feedback.addClass('invalid-feedback')
                feedback.text(`AJAX Error`)
                formSubmit.prop('disabled', true);
            }
        })
    });

    $('#passwordSignup').on('keyup click', function(e) {
        let strongPasswordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,32}$/;

        let feedback = $('#passwordSignupFeedback');
        let password = $('#passwordSignup');
        let pword = password.val().toString();

        if (pword === "") {
            password.addClass('is-invalid')
            feedback.addClass('invalid-feedback')
            feedback.text("Password required!");
            formSubmit.prop('disabled', true);
            return;
        }

        password.removeClass('is-valid is-invalid')
        feedback.removeClass('valid-feedback invalid-feedback')

        if (pword.length < 8) {
            password.addClass('is-invalid')
            feedback.addClass('invalid-feedback')
            feedback.text(`Password too short!`)
            formSubmit.prop('disabled', true);
            return;
        } else if (pword.length > 32) {
            password.addClass('is-invalid')
            feedback.addClass('invalid-feedback')
            feedback.text(`Password too long!`)
            formSubmit.prop('disabled', true);
            return;
        }

        if (strongPasswordRegex.test(pword)) {
            password.addClass('is-valid')
            feedback.addClass('valid-feedback')
            feedback.text(`Looks good!`)
            formSubmit.prop('disabled', !submittable())
        } else {
            password.addClass('is-invalid')
            feedback.addClass('invalid-feedback')
            feedback.text(`Weak password!`)
            formSubmit.prop('disabled', true);
        }
    });

    form.on('submit', function(e) {
        if (!submittable()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form[0].addClass('was-validated')
    })
});