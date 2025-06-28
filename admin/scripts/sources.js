$(function () {
    $('.edit').on('click', function () {
        let row = $(this).closest('tr');
        let rowId = row.children('.id');
        let rowSource = row.children('.name');

        let modalId = $("#editId");
        let modalSource = $("#editSource");

        modalId.val(rowId.html())
        modalSource.val(rowSource.html())
    })

    $('.delete').on('click', function () {
        let row = $(this).closest('tr');
        let rowId = row.children('.id');
        let rowSource = row.children('.name');

        let modalId = $("#deleteId");
        let modalSource = $("#deleteSource");

        modalId.val(rowId.html())
        modalSource.val(rowSource.html())
    })

    $('.editSubmit').on('click', function () {
        $(':disabled').each(function () {
            $(this).removeAttr('disabled')
        })
    })
})