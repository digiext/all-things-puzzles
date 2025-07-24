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
    let pictureDelete = $('#deleteImageButton');
    let cardPicture = $('#cardpicture')
    let currpicture = $('#currpicture');
    let deleteoldpic = $('#deleteoldpic');

    let brandCheckbox = $('#createNewBrand');
    let brandDiv = $('#newBrandMenu');
    let categoryCheckbox = $('#createNewCategory');
    let categoryDiv = $('#newCategoryMenu');
    let sourceCheckbox = $('#createNewSource');
    let sourceDiv = $('#newSourceMenu');
    let dispositionCheckbox = $('#createNewDisposition');
    let dispositionDiv = $('#newDispositionMenu');
    let locationCheckbox = $('#createNewLocation');
    let locationDiv = $('#newLocationMenu');

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

        deleteoldpic.val("true");
    })

    pictureClear.on('click', function() {
        picture.val(null);
        cardPicture.attr('src', '/images/uploads/thumbnails/' + currpicture.val());
        deleteoldpic.val("false");
    })

    pictureDelete.on('click', function() {
        var location = window.location.href;
        var directoryPath = location.substring(0,location.lastIndexOf("/")+1);
        picture.val(null);
        cardPicture.attr('src', directoryPath + '/images/no-image-dark.svg');
        deleteoldpic.val("true");
    })

    puzzleName.on('keyup', function() {
        if (puzzleName.val() !== '') {
            cardName.text(puzzleName.val());
        } else {
            cardName.text('');
        }
    })

    puzzlePieces.on('keyup', function() {
        if (puzzlePieces.val() !== '') {
            cardPieces.text(puzzlePieces.val());
        } else {
            cardPieces.text('');
        }
    })

    puzzleBrand.on('change', function() {
        cardBrand.text($(this).find('option:selected').text());
    })

    newBrand.on('keyup', function() {
        if (brandCheckbox.prop('checked') === true) {
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
            cardCost.text(puzzleCost.val());
        } else {
            cardCost.text('');
        }
    })

    puzzleCostCurrency.on('change', function() {
        cardCurrency.text($(this).find('option:selected').text());
    })

    puzzleSource.on('change', function() {
        cardSource.text($(this).find('option:selected').text());
    })

    newSource.on('keyup', function() {
        if (sourceCheckbox.prop('checked') === true) {
            cardSource.text(newSource.val());
        }
    })

    puzzleUpc.on('keyup', function() {
        if (puzzleUpc.val() !== '') {
            cardUpc.text(puzzleUpc.val());
        } else {
            cardUpc.html("<i class='text-body-secondary'>None</i>");
        }
    })

    brandCheckbox.on('change', function() {
        if (brandCheckbox.prop('checked') === true) {
            brandDiv.show(200);
            if (newBrand.val() !== '') {
                cardBrand.text(newBrand.val());
            } else {
                cardBrand.text('');
            }
        } else {
            brandDiv.hide(200);
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
                cardSource.text(newSource.val());
            } else {
                cardSource.text('');
            }
        } else {
            sourceDiv.hide(200);
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
})