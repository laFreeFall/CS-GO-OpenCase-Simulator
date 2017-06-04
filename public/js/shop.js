$(document).on('click', ".shop-item-price", function() {
    var itemName = $(this).parent().find(".shop-item-title").html();
    var itemImage = $(this).closest(".shop-item").data("img");
    var itemId = $(this).closest(".shop-item").data("itemid");
    var itemPrice = Math.ceil(parseFloat($(this).html()));
    $("#modal-confirm-item-image").attr('src', itemImage);
    $("#modal-confirm-item-name").html(itemName);
    $("#modal-confirm-item-price").html(itemPrice);
    $("#modal-confirm-modal-body").attr("data-itemid", itemId);
    $('#modal-confirm').modal('show');

    $('#modal-confirm').modal({ backdrop: 'static', keyboard: false })
    .one('click', "#btn-confirm-sell-inventory-item", function () {
        var item = {
            'id': $('#modal-confirm-modal-body').data("itemid"),
            'name': $(this).parent().find(".shop-item-title").html(),
            'price': parseFloat($(this).html())
        };
        console.log(item['id']);
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: '/buy-shop-item',
            data: {
                "itemid": item['id']
            },
            dataType: 'json',
            success: function (data) {
                $("#myModal").modal('hide');
                var itemPrice = parseFloat($("#modal-confirm-item-price").text());
                var topUserPointsBlock = $("#top-user-points");
                var topUserPoints = parseInt(topUserPointsBlock.text());
                topUserPointsBlock.prop('number', topUserPoints).animateNumber({ number: (topUserPoints - Math.ceil(itemPrice))});
                console.log('Points: ' + topUserPoints + ' - ' + Math.ceil(itemPrice) + ' = ' + (topUserPoints - Math.ceil(itemPrice)));

                var topUserMoneyBlock = $("#top-user-money");
                var topUserMoney = parseFloat(topUserMoneyBlock.text());
                topUserMoneyBlock.text((topUserMoney + itemPrice).toFixed(2));
                console.log('Money: ' + topUserMoney + ' + ' + itemPrice + ' = ' + (topUserMoney + itemPrice));
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });


});