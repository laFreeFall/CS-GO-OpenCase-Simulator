jQuery.fn.outerHTML = function() {
    return jQuery('<div />').append(this.eq(0).clone());
};

function decreaseItemsAmount(itemBlock) {
    var itemsAmount = parseInt($(itemBlock).find('.item-amount').text());
    if(itemsAmount == 2) {
        $(itemBlock).find('.item-amount-btn').remove();
    } else {
        $(itemBlock).find('.item-amount').text(itemsAmount - 1);
    }

}

// for selling item in the inventory
function sellItem(itemBlock) {
    if($(itemBlock).find('.item-amount-btn').length > 0) {
        decreaseItemsAmount(itemBlock);
        console.log('There are many items: ' + $(itemBlock).find('.item-amount').text());
    } else {
        $(itemBlock).fadeTo("slow", 0.2).removeClass('cursor-pointer').addClass('item-disabled');
        // $(itemBlock).fadeTo("slow", 0.2);
    }
}


// for selecting item in the contracts & coinflip games
function removeItem(itemBlock) {
    var newContractItem = $(itemBlock).clone().appendTo(".contract-item-empty:first");
    newContractItem.removeClass("inventory-item")
        .addClass("contract-inner-item")
        .unwrap()
        .wrap( "<div class='contract-item contract-item-filled col-md-5ths'></div>" )
        .find(".item-amount-btn").remove();
    if($(itemBlock).find('.item-amount-btn').length > 0) {
        decreaseItemsAmount(itemBlock);
    } else {
        $(itemBlock).parent().remove()
    }
    // $(".contract-item-empty:first").remove();
}

// for removing items from contracts & coinflip games
function returnItemToInventory(itemBlock) {
    var sameItemInInventory = $(".inventory-item[data-itemid=" + itemBlock.children().data("itemid") + "]");
    // var sameItemInInventory = $(".item-" + itemBlock.children().data("itemid"));
    // var sameItemInInventory = $(".item-" + itemBlock.children().data("itemid"));
    console.log(sameItemInInventory.length);
    if(sameItemInInventory.length) {
        // sameItemInInventory = sameItemInInventory.first();
        if(sameItemInInventory.find(".item-amount-btn").length) {
            console.log('item amount yes');
            var itemsAmount = sameItemInInventory.find(".item-amount");
            itemsAmount.text(parseInt(itemsAmount.text()) + 1);
        } else {
            sameItemInInventory.find(".item-price-btn")
                .after('<button class="btn btn-xs btn-default pull-right item-amount-btn">x<span class="item-amount">2</span> </button>');
        }
    } else {
        itemBlock.removeClass("contract-item contract-item-filled col-md-5ths").addClass('col-md-2');
        itemBlock.children().removeClass('contract-inner-item').addClass('inventory-item');
        itemBlock.clone().prependTo("#inventory-items");
        // $("#inventory-items").clone(itemBlock);
        // var itemBlockBackup = itemBlock.clone();
    }
    itemBlock.replaceWith('<div class="contract-item contract-item-empty col-md-5ths"></div>');
}


function displaySellOrDeleteButton(itemPrice) {
    if(itemPrice < 1) {
        $("#sell-inventory-item-btn").hide();
        $("#delete-inventory-item-btn").show();
    } else {
        $("#sell-points-price").text(Math.floor(itemPrice));

        $("#delete-inventory-item-btn").hide();
        $("#sell-inventory-item-btn").show();
    }
}

function displayItemModal(item) {
    var htmlModal = '';
    var stattrak = item['stattrak'] ? 'StatTrak ' : '';
    var souvenir = item['souvenir'] ? 'Souvenir ' : '';
    htmlModal += '<div class="modal-body modal-item-background-' + item['rarity'] + '" id="myModal-body" data-itemid="' + item['id'] + '" data-blockid="' + item['blockId'] + '" data-price="' + item['price'] + '">';
    htmlModal += '\t<img src="/storage/images/itemz/' + item['image']  + '" alt="' + item['weaponName'] + ' | ' + item['patternName'] + '" class="case-item-image modal-item-image center-block">\n';
    htmlModal += '\t<p class="case-item-weapon modal-item-name text-center item-background-' + item['rarity'] + '"><span id="modal-itemname">' + stattrak + souvenir + item['weaponName'] + ' | ' + item['patternName'] + '</span><br>' + item['condition'] + ' | <strong>' + item['price'] + '$</strong> </p>\n';
    htmlModal += '</div>\n';
    $("#myModal-body").replaceWith(htmlModal);
    $("#modal-title").text(item['weaponName'] + ' | ' + item['patternName']);
    displaySellOrDeleteButton(item['price']);

}

