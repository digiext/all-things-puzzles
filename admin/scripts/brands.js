$(function () {
    $('.edit').on('click', function () {
        let row = $(this).closest('tr');
        let rowId = row.children('.id');
        let rowBrand = row.children('.name');

        let modalId = $("#editId");
        let modalBrand = $("#editBrand");

        modalId.val(rowId.html())
        modalBrand.val(rowBrand.html())
    })

    $('.delete').on('click', function () {
        let row = $(this).closest('tr');
        let rowId = row.children('.id');
        let rowBrand = row.children('.name');

        let modalId = $("#deleteId");
        let modalBrand = $("#deleteBrand");

        modalId.val(rowId.html())
        modalBrand.val(rowBrand.html())
    })

    $('.editSubmit').on('click', function () {
        $(':disabled').each(function () {
            $(this).removeAttr('disabled')
        })
    })
})