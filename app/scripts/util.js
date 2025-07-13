$(function () {
    $('#successAlert').fadeTo(5000, 500).slideUp(500, function() {
        $('#successAlert').slideUp(500);
    })

    $('#failAlert').fadeTo(5000, 500).slideUp(500, function() {
        $('#failAlert').slideUp(500);
    })

    $('#warnAlert').fadeTo(5000, 500).slideUp(500, function() {
        $('#warnAlert').slideUp(500);
    })
})