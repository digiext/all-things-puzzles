$(function () {
    const table = $('#table');

    table.on('click', '.picture', function () {
        let row = $(this).closest('tr');
        let rowId = row.children('.id');
        let rowName = row.children('.name');
        let rowPic = row.children('.picture');

        let modalId = $("#picId");
        let modalName = $("#picName");
        let modalPic = $("#picPath");

        modalId.val(rowId.html())
        modalName.text(rowName.html())
        modalPic.attr("src",(rowPic.html()))

    })

})