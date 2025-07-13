$(function () {
    const table = $('#table');

    table.on('click', '.edit', function () {
        let row = $(this).closest('tr');
        let rowId = row.children('.id');
        let rowSource = row.children('.name');

        let modalId = $("#editId");
        let modalSource = $("#editOwnership");

        modalId.val(rowId.html())
        modalSource.val(rowSource.html())
    })

    table.on('click', '.delete', function () {
        let row = $(this).closest('tr');
        let rowId = row.children('.id');
        let rowSource = row.children('.name');

        let modalId = $("#deleteId");
        let modalSource = $("#deleteOwnership");

        modalId.val(rowId.html())
        modalSource.val(rowSource.html())
    })
})