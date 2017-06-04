$(document).ready(function() {


// SHOWING MODAL WINDOW WITH INVENTORY ITEM MENU
    $(".inventory-item").click(function() {
        if(! $(this).hasClass('item-disabled')) {

            var item = {
                id: $(this).attr("data-itemid"),
                blockId: $(this).attr("data-blockid"),
                weaponName: $(this).find(".item-title-weapon").text(),
                patternName: $(this).find(".item-title-pattern").text(),
                rarity: $(this).attr("data-rarity"),
                condition: $(this).attr("data-condition"),
                stattrak: $(this).find(".item-stattrak").text() == 'ST',
                souvenir: $(this).find(".item-stattrak").text() == 'SV',
                // image: $(this).find(".item-image").attr('src'),
                image: $(this).attr("data-image"),
                price: parseFloat($(this).find(".item-price").text())
            };
            console.log(item['image']);
            displayItemModal(item);

            $("#modal-confirm-item-name").replaceWith('<span id="modal-confirm-item-name">' + item['weaponName'] + ' | ' + item['patternName'] + '</span>');
            $("#modal-confirm-item-price").replaceWith('<span id="modal-confirm-item-price">' + Math.floor(item['price']) + '</span>');
            $("#modal-confirm-item-image").attr('src', '/storage/images/itemz/' + item['image']);

            $('#myModal').modal('show');
        }

    });



// AJAX SELLING INVENTORY ITEM
//
    $("#sell-inventory-item-btn, #delete-inventory-item-btn").click(function() {
    if($(this).attr('id') == 'delete-inventory-item-btn') {
        $("#modal-confirm-text").html("Do you really want to delete <br> <strong>" + $("#modal-itemname").text() + "</strong>?")
    }
    $('#modal-confirm').modal({ backdrop: 'static', keyboard: false })

        .one('click', "#btn-confirm-sell-inventory-item, #delete-inventory-item-btn", function () {

            var modalBody = $("#myModal-body");
            var item = {
                'id': modalBody.attr('data-itemid'),
                'blockId': modalBody.attr('data-blockid'),
                'price': modalBody.attr('data-price')
            };
            console.log(item['id']);
            sellItem($("#item-" + item['id']));

            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '/sell-item',
                data: {
                    "itemid": item['id']
                },
                dataType: 'json',
                success: function (data) {

                    $("#myModal").modal('hide');
                    var topUserPointsBlock = $("#top-user-points");
                    var userPoints = parseInt(topUserPointsBlock.text());
                    topUserPointsBlock.prop('number', userPoints).animateNumber({ number: userPoints + data['profit'] });

                    var userNewBalance = (parseFloat($("#top-user-money").text()) - item['price']).toFixed(2);
                    $("#top-user-money").text(userNewBalance);

                    var userItemsAmount = $("#inventory-user-items-amount");
                    userItemsAmount.text(parseInt(userItemsAmount.text() - 1));

                    var userTotalPrice = $("#inventory-user-total-price");
                    userTotalPrice.text((parseFloat(userTotalPrice.text()) - data['profit'] / 1000).toFixed(2) + '$');

                    console.log('Success. ' + data['profit'] + ' points were added. Current balance: ' + (parseInt(topUserPointsBlock.text()) + data['profit']) + '.');
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });

    });

});

/*
 // SHOWING MODAL WINDOW WITH INVENTORY ITEM MENU
 $(".inventory-item").click(function() {
 if(! $(this).hasClass('disabled-inventory-item')) {
 // console.log($(this));

 var item = {
 id: $(this).attr("data-itemid"),
 blockId: $(this).attr("data-blockid"),
 weaponName: $(this).find(".case-item-weapon-name").text(),
 patternName: $(this).find(".case-item-weapon-pattern").text(),
 rarity: $(this).attr("data-rarity"),
 condition: $(this).attr("data-condition"),
 // stattrak: $(this).find(".inventory-item-stattrak").text().length > 1 ? '[ST] ' : '',
 stattrak: $(this).find(".inventory-item-stattrak").text(),
 image: $(this).find(".inventory-item-image").attr('src'),
 price: parseFloat($(this).find(".inventory-item-price").text())
 };
 // console.log($(this));
 //            console.log($(this).find(".case-item-weapon-name").text());
 var htmlModal = '';
 htmlModal += '<div class="modal-body modal-item-background-' + item['rarity'] + '" id="myModal-body" data-itemid="' + item['id'] + '" data-blockid="' + item['blockId'] + '">';
 htmlModal += '\t<img src="' + item['image']  + '" alt="' + item['weaponName'] + ' | ' + item['patternName'] + '" class="case-item-image modal-item-image center-block">\n';
 htmlModal += '\t<p class="case-item-weapon modal-item-name text-center item-background-' + item['rarity'] + '">' + item['stattrak'] + item['weaponName'] + ' | ' + item['patternName'] + '<br>' + item['condition'] + ' | <strong>' + item['price'] + '$</strong> </p>\n';
 htmlModal += '</div>\n';
 $("#myModal-body").replaceWith(htmlModal);
 // $("#modal-title").replaceWith('<h4 class="modal-title text-center" id="modal-title">' + item['stattrak'] + item['weaponName'] + ' | ' + item['patternName'] + '</h4>');
 $("#modal-title").text(item['stattrak'] + item['weaponName'] + ' | ' + item['patternName']);
 // $("#sell-points-price").replaceWith('<span id="sell-points-price">' + Math.floor(item['price']) + '</span>');
 if(item['price'] < 1) {
 $("#sell-points-price").parent().removeClass("btn-info").addClass("btn-danger").attr("id", "delete-inventory-item").text("Delete item");
 console.log('Item is lower than 1$');
 } else {
 $("#sell-points-price").text(Math.floor(item['price']));
 }

 $("#confirm-delete-item-name").replaceWith('<span id="confirm-delete-item-name">' + item['stattrak'] + item['weaponName'] + ' | ' + item['patternName'] + '</span>');
 $("#confirm-delete-item-price").replaceWith('<span id="confirm-delete-item-price">' + Math.ceil(item['price']) + '</span>');

 $('#myModal').modal('show');
 }

 });

 // AJAX SELLING INVENTORY ITEM
 //
 $(document).on('click', '#baq', function() {
 // $("#baq").click(function() {
 console.log('clicked');
 console.log($(this));
 $('#confirm-delete-modal').modal({ backdrop: 'static', keyboard: false })
 .one('click', "#btn-confirm-sell-inventory-item", function () {
 var modalBody = $("#myModal-body");
 var item = {
 'id': modalBody.attr('data-itemid'),
 'blockId': modalBody.attr('data-blockid')
 };
 $.ajaxSetup({
 headers: {
 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
 }
 });
 $.ajax({
 type: "POST",
 url: '/sell-inventory-item',
 data: {
 // "_token": "{{ csrf_token() }}",
 "itemid": item['id']
 },
 dataType: 'json',
 success: function (data) {
 //                        $("#inventory-item-" + item['blockId']).hide(500);
 //                        $("#inventory-item-" + item['blockId']).css({'opacity': '0.5', 'cursor': 'no-drop'});
 $("#inventory-item-" + item['blockId']).fadeTo("slow", 0.2).removeClass('cursor-pointer').addClass('disabled-inventory-item');
 $("#inventory-item-" + item['blockId']).fadeTo("slow", 0.2);
 $("#myModal").modal('hide');
 var topUserPointsBlock = $("#top-user-points");
 var userPoints = parseInt(topUserPointsBlock.text());
 topUserPointsBlock.prop('number', userPoints).animateNumber({ number: userPoints + data['profit'] });

 var userItemsAmount = $("#inventory-user-items-amount");
 userItemsAmount.text(parseInt(userItemsAmount.text() - 1));

 var userTotalPrice = $("#inventory-user-total-price");
 //                        $(userTotalPrice)
 //                            .animateNumber(
 //                                {
 //                                    number: 5 * decimal_factor,
 //                                    numberStep: function(now, tween) {
 //                                        var floored_number = Math.floor(now) / decimal_factor,
 //                                            target = $(tween.elem);
 //                                        if (decimal_places > 0) {
 //                                            // force decimal places even if they are 0
 //                                            floored_number = floored_number.toFixed(decimal_places);
 //                                            // replace '.' separator with ','
 //                                            floored_number = floored_number.toString().replace('.', ',');
 //                                        }
 //                                        target.text(floored_number + '$');
 //                                    }
 //                                },
 //                                2000
 //                            );
 userTotalPrice.text((parseFloat(userTotalPrice.text()) - data['profit'] / 1000).toFixed(2) + '$');

 console.log('Success. ' + data['profit'] + ' points were added. Current balance: ' + (parseInt(topUserPointsBlock.text()) + data['profit']) + '.');
 },
 error: function (data) {
 console.log('Error:', data);
 }
 });
 });

 });
 */