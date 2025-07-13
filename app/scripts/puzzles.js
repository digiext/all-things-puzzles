$(function () {
    const allCards = $('.card');

    allCards.on('click', '.delete', function () {
        let card = $(this).closest('.card')
        let cardId = card.attr('data-id');
        let cardName = card.attr('data-name');

        let modalId = $("#deleteId");
        let modalPuzzle = $("#deletePuzzle");

        modalId.val(cardId)
        modalPuzzle.val(cardName)
    })
})