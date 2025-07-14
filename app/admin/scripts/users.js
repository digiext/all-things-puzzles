$(function () {
    
    table.on('click', '.delete', function () {
        let row = $(this).closest('tr');
        let rowId = row.children('.id');
        let rowSource = row.children('.name');

        let modalId = $("#deleteId");
        let modalSource = $("#deleteUser");

        modalId.val(rowId.html())
        modalSource.val(rowSource.html())
    })
})