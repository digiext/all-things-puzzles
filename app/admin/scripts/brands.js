$(function () {
    const table = $('#table');

    table.on('click', '.edit', function () {
        let row = $(this).closest('tr');
        let rowId = row.children('.id');
        let rowBrand = row.children('.name');

        let modalId = $("#editId");
        let modalBrand = $("#editBrand");

        modalId.val(rowId.html())
        modalBrand.val(rowBrand.html())
    })

    table.on('click', '.delete', function () {
        let row = $(this).closest('tr');
        let rowId = row.children('.id');
        let rowBrand = row.children('.name');

        let modalId = $("#deleteId");
        let modalBrand = $("#deleteBrand");

        modalId.val(rowId.html())
        modalBrand.val(rowBrand.html())
    })
})