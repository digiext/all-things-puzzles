$(function () {
    let form = $('#form');
    let submit = $('#submit');

    let puzzleName = $('#puzname');
    let puzzlePieces = $('#pieces');
    let puzzleCost = $('#cost');
    let puzzleUpc = $('#upc');

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

    let nameFeedback = $('#nameFeedback');
    puzzleName.on('change', function() {
        removeClasses(puzzleName, nameFeedback);

        if ($(this).val() === '') {
            invalid(puzzleName, nameFeedback, "Puzzle must have a name!");
        } else {
            valid(puzzleName, nameFeedback);
        }
    })

    let piecesFeedback = $('#piecesFeedback')
    puzzlePieces.on('change', function () {
        let val = $(this).val();
        removeClasses(puzzlePieces, piecesFeedback);

        if ($(this).val().length === 0) {
            invalid(puzzlePieces, piecesFeedback, "Puzzle must have a piece count!");
        } else if (val < 0) {
            invalid(puzzlePieces, piecesFeedback, "Can not have a negative number of pieces!");
        } else {
            valid(puzzlePieces, piecesFeedback);
        }
    })

    let costFeedback = $('#costFeedback')
    puzzleCost.on('change', function () {
        let val = $(this).val();
        removeClasses(puzzleCost, costFeedback);

        if (val < 0) {
            invalid(puzzleCost, costFeedback, "Can not have a negative cost!");
        } else {
            valid(puzzleCost, costFeedback);
        }
    })

    let upcFeedback = $('#upcFeedback')
    puzzleUpc.on('change', function () {
        let val = $(this).val();
        removeClasses(puzzleUpc, upcFeedback);

        if ((val.length < 12 || val.length > 13) && val.length !== 0) {
            invalid(puzzleUpc, upcFeedback, "Invalid UPC / ISBN!")
        } else {
            valid(puzzleUpc, upcFeedback);
        }
    })

    let script = $('script[src="scripts/puzzle_validator.js"]')
    let fromsrc = script.data('from');

    if (fromsrc === 'edit') {
        if (puzzleName.val() === '') {
            invalid(puzzleName, nameFeedback, "Puzzle must have a name!");
        } else {
            valid(puzzleName, nameFeedback);
        }

        if (puzzlePieces.val() < 0) {
            invalid(puzzlePieces, piecesFeedback, "Can not have a negative number of pieces!");
        } else {
            valid(puzzlePieces, piecesFeedback);
        }

        if (puzzleCost.val() < 0) {
            invalid(puzzleCost, costFeedback, "Can not have a negative cost!");
        } else {
            valid(puzzleCost, costFeedback);
        }

        if ((puzzleUpc.val().length < 12 || puzzleUpc.val().length > 13) && puzzleUpc.val().length !== 0) {
            invalid(puzzleUpc, upcFeedback, "Invalid UPC / ISBN!")
        } else {
            valid(puzzleUpc, upcFeedback);
        }
    } else if (fromsrc === 'add') {
        invalid(puzzleName, nameFeedback, "Please set a puzzle name!")
        invalid(puzzlePieces, piecesFeedback, "Please set the piece count!");
        invalid(puzzleCost, costFeedback, "Please set the cost, or 0 if unsure!");
    }
})