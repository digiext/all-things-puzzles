$(function () {
    let form = $('#useredit')
    let submit = $('#submit');

    let pieces = $('#pieces');
    let missingPieces = $('#missingPieces');
    let missingPiecesFeedback = $('#missingPiecesFeedback');
    let difficulty = $('#difficulty');
    let difficultyFeedback = $('#difficultyFeedback');
    let quality = $('#quality');
    let qualityFeedback = $('#qualityFeedback');
    let overall = $('#overall');
    let overallFeedback = $('#overallFeedback');

    function isStep(num, step) {
        if (typeof num !== 'number' || !Number.isFinite(num)) {
            return false;
        }

        return Number.isInteger(num * (1 / step));
    }


    function formDisableStatus() {
        let requiredInputs = form.find(':input[required]')
        let invalidInputs = form.find('.is-invalid')
        let noFieldsFilled = false;

        requiredInputs.each(function() {
            noFieldsFilled |= ($(this).val().length === 0);
        });

        return !(form[0].checkValidity() && invalidInputs.length === 0 && !noFieldsFilled);
    }

    function removeClasses(field, feedback) {
        field.removeClass('is-valid is-invalid');
        feedback.removeClass('valid-feedback invalid-feedback');
    }

    function invalid(field, feedback, msg) {
        field.addClass('is-invalid');
        feedback.addClass('invalid-feedback');
        feedback.text(msg);
        submit.attr('disabled', "true");
    }

    function valid(field, feedback) {
        field.addClass('is-valid');
        feedback.addClass('valid-feedback');
        feedback.text("")
        submit.attr('disabled', formDisableStatus());
    }

    missingPieces.on('change', function() {
        removeClasses(missingPieces, missingPiecesFeedback);

        if (missingPieces.val() < 0) {
            invalid(missingPieces, missingPiecesFeedback, "Missing pieces can not be negative!");
        } else if (missingPieces.val() > pieces.val()) {
            invalid(missingPieces, missingPiecesFeedback, "Missing pieces can not exceed the puzzle piece count!")
        } else valid(missingPieces, missingPiecesFeedback)
    })

    difficulty.on('change', function() {
        removeClasses(difficulty, difficultyFeedback);

        if (difficulty.val() < 0) {
            invalid(difficulty, difficultyFeedback, "Difficulty can not be negative!");
        } else if (difficulty.val() > 5) {
            invalid(difficulty, difficultyFeedback, "Difficulty must be less than 5!");
        } else if (!isStep(parseFloat(difficulty.val()), 1)) {
            invalid(difficulty, difficultyFeedback, "Difficulty must be a whole number!");
        } else valid(difficulty, difficultyFeedback);
    })


    quality.on('change', function() {
        removeClasses(quality, qualityFeedback);

        if (quality.val() < 0) {
            invalid(quality, qualityFeedback, "Quality can not be negative!");
        } else if (quality.val() > 5) {
            invalid(quality, qualityFeedback, "Quality must be less than 5!");
        } else if (!isStep(parseFloat(quality.val()), 1)) {
            invalid(quality, qualityFeedback, "Quality must be a whole number!");
        } else valid(quality, qualityFeedback);
    })

    overall.on('change', function() {
        removeClasses(overall, overallFeedback);

        if (overall.val() < 0) {
            invalid(overall, overallFeedback, "Overall can not be negative!");
        } else if (overall.val() > 5) {
            invalid(overall, overallFeedback, "Overall must be less than 5!");
        } else if (!isStep(parseFloat(overall.val()), 0.5)) {
            invalid(overall, overallFeedback, "Overall must be a half-step (0, 0.5, 1, etc.)!");
        } else valid(overall, overallFeedback);
    })
})