$(function() {
    let puzzleName = $('#puzname');
    let cardName = $('#cardname');
    let puzzlePieces = $('#pieces');
    let cardPieces = $('#cardpieces');
    let puzzleBrand = $('#brand');
    let newBrand = $('#brandName');
    let cardBrand = $('#cardbrand')
    let puzzleCategory = $('#category')
    let newCategory = $('#categoryDesc');
    let cardCategory = $('#cardcategory')
    let puzzleCost = $('#cost');
    let cardCost = $('#cardcost');
    let puzzleCostCurrency = $('#costCurrency')
    let cardCurrency = $('#cardcurrency');
    let puzzleSource = $('#source');
    let newSource = $('#sourceDesc')
    let cardSource = $('#cardsource');
    let puzzleUpc = $('#upc');
    let cardUpc = $('#cardupc');
    let picture = $('#picture');
    let pictureClear = $('#pictureclear');
    let cardPicture = $('#cardpicture');

    let brandCheckbox = $('#createNewBrand');
    let brandDiv = $('#newBrandMenu');
    let sourceCheckbox = $('#createNewSource');
    let sourceDiv = $('#newSourceMenu');
    let dispositionCheckbox = $('#createNewDisposition');
    let dispositionDiv = $('#newDispositionMenu');
    let locationCheckbox = $('#createNewLocation');
    let locationDiv = $('#newLocationMenu');
    let categoryCheckbox = $('#createNewCategory');
    let categoryDiv = $('#newCategoryMenu');

    puzzleName.on('keyup', function() {
        if (puzzleName.val() !== '') {
            cardName.removeClass('placeholder col-12');
            cardName.text(puzzleName.val());
        } else {
            cardName.addClass('placeholder col-12');
            cardName.text('');
        }
    })

    puzzlePieces.on('keyup', function() {
        if (puzzlePieces.val() !== '') {
            cardPieces.removeClass('placeholder col-2');
            cardPieces.text(puzzlePieces.val());
        } else {
            cardPieces.addClass('placeholder col-2');
            cardPieces.text('');
        }
    })

    puzzleBrand.on('change', function() {
        cardBrand.removeClass('placeholder col-12');
        cardBrand.text($(this).find('option:selected').text());
    })

    newBrand.on('keyup', function() {
        if (brandCheckbox.prop('checked') === true) {
            cardBrand.removeClass('placeholder col-12');
            cardBrand.text(newBrand.val());
        }
    })

    puzzleCategory.on('change', function() {
        cardCategory.removeClass('placeholder');
        let sel = $(this).find('option:selected');
        let desc = [];

        sel.each(function() {
            desc.push($(this).text());
            console.log($(this).val() + " = " + $(this).text());
        });

        if (categoryCheckbox.prop('checked') === true) {
            newCategory.val().toString().split(',').forEach(s => {
                desc.push(s.trim());
            })
        }

        cardCategory.text(desc.join(", "));
    })

    newCategory.on('keyup', function() {
        if (categoryCheckbox.prop('checked') === true) {
            cardCategory.removeClass('placeholder');
            let sel = puzzleCategory.find('option:selected');
            let desc = [];

            sel.each(function() {
                desc.push($(this).text());
                console.log($(this).val() + " = " + $(this).text());
            });

            $(this).val().split(',').forEach(s => {
                desc.push(s.trim());
            })

            cardCategory.text(desc.join(", "));
        }
    })

    puzzleCost.on('keyup', function() {
        if (puzzleCost.val() !== '') {
            cardCost.removeClass('placeholder col-1');
            cardCost.text(puzzleCost.val());
        } else {
            cardCost.addClass('placeholder col-1');
            cardCost.text('');
        }
    })

    puzzleCostCurrency.on('change', function() {
        cardCurrency.text($(this).find('option:selected').text());
    })

    puzzleSource.on('change', function() {
        cardSource.removeClass('placeholder col-3');
        cardSource.text($(this).find('option:selected').text());
    })

    newSource.on('keyup', function() {
        if (sourceCheckbox.prop('checked') === true) {
            cardSource.removeClass('placeholder col-3');
            cardSource.text(newSource.val());
        }
    })

    puzzleUpc.on('keyup', function() {
        if (puzzleUpc.val() !== '') {
            cardUpc.removeClass('placeholder col-3');
            cardUpc.text(puzzleUpc.val());
        } else {
            cardUpc.addClass('placeholder col-3');
            cardUpc.text('');
        }
    })

    brandCheckbox.on('change', function() {
        if (brandCheckbox.prop('checked') === true) {
            brandDiv.show(200);
            if (newBrand.val() !== '') {
                cardBrand.removeClass('placeholder col-12');
                cardBrand.text(newBrand.val());
            } else {
                cardBrand.addClass('placeholder col-12');
                cardBrand.text('');
            }
        } else {
            brandDiv.hide(200);
            cardBrand.removeClass('placeholder col-12');
            cardBrand.text(puzzleBrand.find('option:selected').text());
        }
    })

    categoryCheckbox.on('change', function() {
        if (categoryCheckbox.prop('checked') === true) {
            categoryDiv.show(200);
            if (newCategory.val() !== '') {
                let sel = puzzleCategory.find('option:selected');
                let desc = [];

                sel.each(function() {
                    desc.push($(this).text());
                    console.log($(this).val() + " = " + $(this).text());
                });

                newCategory.val().toString().split(',').forEach(s => {
                    desc.push(s.trim());
                })

                cardCategory.text(desc.join(", "));
            }
        } else {
            categoryDiv.hide(200);

            let sel = puzzleCategory.find('option:selected');
            let desc = [];

            sel.each(function() {
                desc.push($(this).text());
                console.log($(this).val() + " = " + $(this).text());
            });

            cardCategory.text(desc.join(", "));
        }
    })

    sourceCheckbox.on('change', function() {
        if (sourceCheckbox.prop('checked') === true) {
            sourceDiv.show(200);
            if (newSource.val() !== '') {
                cardSource.removeClass('placeholder col-3');
                cardSource.text(newSource.val());
            } else {
                cardSource.addClass('placeholder col-3');
                cardSource.text('');
            }
        } else {
            sourceDiv.hide(200);
            cardSource.removeClass('placeholder col-3');
            cardSource.text(puzzleSource.find('option:selected').text());
        }
    })

    dispositionCheckbox.on('change', function() {
        if (dispositionCheckbox.prop('checked') === true) {
            dispositionDiv.show(200);
        } else {
            dispositionDiv.hide(200);
        }
    })

    locationCheckbox.on('change', function() {
        if (locationCheckbox.prop('checked') === true) {
            locationDiv.show(200);
        } else {
            locationDiv.hide(200);
        }
    })

    picture.on('change', function() {
        if (this.files && this.files[0]) {
            let file = this.files[0];
            let reader = new FileReader();

            reader.onload = function(e) {
                cardPicture.attr('src', e.target.result)
            }

            reader.readAsDataURL(file);
        } else {
            cardPicture.attr('src', '/images/no-image-dark.svg');
        }
    })

    pictureClear.on('click', function() {
        picture.val(null);
        cardPicture.attr('src', '/images/no-image-dark.svg');
    })
})