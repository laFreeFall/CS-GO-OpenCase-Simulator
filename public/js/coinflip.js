$(document).ready(function() {
    var items = [];
    var coinButtonsEnable = true;
    // CLICK ON INVENTORY ITEM
    $(document).on('click', ".inventory-item", function () {
        var contractItemsAmount = $(".contract-item-filled").length + 1;
        if(contractItemsAmount <= 10) {
            // $("#btn-contract").prop('disabled', true);
            removeItem($(this));
            if(contractItemsAmount >= 1) {
                $("#btn-contract").prop('disabled', false);
            }
            var itemsMoney = parseFloat($("#user-items-price").text());
            var newItemsMoney = (itemsMoney + parseFloat($(this).find(".item-price").first().text())).toFixed(2);
            $("#user-items-price").text(newItemsMoney);
            $("#user-items-amount").text(contractItemsAmount);
        }

    });

    // // CLICK ON COINFLIP BLOCK ITEM
    $(document).on('click', ".contract-item-filled", function() {
        var contractItemsAmount = $(".contract-item-filled").length - 1;
        if(contractItemsAmount == 0) {
            $("#btn-contract").prop('disabled', true);
        }
        var itemsMoney = parseFloat($("#user-items-price").text());
        var newItemsMoney = (itemsMoney - parseFloat($(this).find(".item-price").first().text())).toFixed(2);
        $("#user-items-price").text(newItemsMoney);
        $("#user-items-amount").text(parseInt($("#user-items-amount").text()) - 1);
        returnItemToInventory($(this));

    });
    //
    // CLICK ON COINFLIP BUTTON
    $(document).on('click', "#btn-contract", function() {
        coinButtonsEnable = false;
        $("#btn-contract").prop('disabled', true);
        $(".contract-inventory-items").first().hide(100);
        var contractItemBlock = $(".contract-item-filled");
        $("#coinflip-user-items-amount").text(contractItemBlock.length);
        $("#user-items-word").text(contractItemBlock.length == 1 ? 'item' : 'items');
        var itemsMoney = parseFloat($("#user-items-price").text());
        $("#coinflip-user-sum").text(itemsMoney);
        var itemIds = [];
        var items = [];
        contractItemBlock.each(function() {
            itemIds.push(parseInt($(this).children().attr("data-itemid")));
            items.push({
                itemid: parseInt($(this).children().attr("data-itemid")),
                rarity: $(this).children().attr("data-rarity"),
                stattrak: ($(this).find(".item-stattrak-btn").length == 1) ? 'ST&#8482; ' : '',
                title: $(this).find(".item-title-weapon").first().text() + ' ' + $(this).find(".item-title-pattern").first().text(),
                condition: $(this).find(".item-condition-btn").first().text(),
                price: (parseFloat($(this).find(".item-price").first().text())).toFixed(2)
            });
        });
        console.log(itemIds);
        var html = '<ul class="list-group coinflip-items-list" id="coinflip-user-items-list">';
        var itemsAmount = items.length;
        for(var i = 0; i < itemsAmount; i++) {
            html += '\t<li class="list-group-item coinflip-list-item item-background-' + items[i]['rarity'] + '">';
                html += '\t\t' + items[i]['title'] + " " + items[i]['condition'] + '\n';
            html += '\t\t<div class="btn btn-default btn-sm coinflip-item-price pull-right">' + items[i]['price'] + '$</div>\n';
            html += '\t</li>\n';
        }
        html += '</ul>';
        $("#coinflip-main-user").append(html);
        $("#contract-items-block").hide(500);
        $("#coinflip-user-items-list").hide().slideDown(300);
        var tSide = $("#coinflip-main-user").find(".coinflip-side-image").hasClass("coinflip-side-image-t");
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: '/coinflip/flip',
            data: {
                "itemIds": itemIds,
                "tSide": tSide
            },
            dataType: 'json',
            success: function (data) {
                $("#btn-contract").hide();
                var randDelay = (Math.floor(Math.random() * (5 - 2)) + 2) * 1000;
                $("#enemy-avatar").fadeTo(1000, 0.3);
                $("#cssload").show();
                console.log('t side: ' + data[4]);
                console.log('win: ' + data[1]);

                setTimeout(function() {
                    $("#cssload").hide();
                    $("#enemy-avatar").fadeOut(100, function() {
                        $("#enemy-avatar").attr("src", "/storage/images/avatars/" + data[3]['avatar']);
                    }).fadeIn(300);
                    $("#enemy-avatar").fadeTo(200, 1);
                    $("#enemy-username").text(data[3]['username']);
                    $("#coinflip-coin-mix").remove();

                    setTimeout(function() {
                        $("#coinflip-coin-block").css("transform", "rotateY(" + data[2] + "deg)");
                    }, 1000);
                    var enemyItemsAmount = data[0].length;
                    console.log(enemyItemsAmount);
                    var enemyItemsPrice = 0;
                    var html = '<ul class="list-group coinflip-items-list" id="coinflip-enemy-items-list">';
                    for(var i = 0; i < enemyItemsAmount; i++) {
                        html += '\t<li class="list-group-item coinflip-list-item item-background-' + data[0][i].rarity + '">';
                        html += '\t\t' + data[0][i].title + '\n';
                        html += '\t\t<div class="btn btn-default btn-sm coinflip-item-price pull-right">' + data[0][i].price.toFixed(2) + '$</div>\n';
                        html += '\t</li>\n';

                        enemyItemsPrice += data[0][i].price;
                    }
                    html += '</ul>';
                    $("#coinflip-enemy-items-amount").text(enemyItemsAmount);
                    $("#enemy-money").text(enemyItemsPrice);
                    var userItemsPrice = parseFloat($("#coinflip-user-sum").text());
                    var enemyPercentage = (enemyItemsPrice / (enemyItemsPrice + userItemsPrice) * 100).toFixed(2);
                    $("#enemy-percentage").text(enemyPercentage);
                    $("#user-percentage").text((100.00 - enemyPercentage).toFixed(2));
                    var enemyItemsWord = enemyItemsAmount == 1 ? 'item' : 'items';
                    var userItemsAmount = parseInt($("#coinflip-user-items-amount").text());
                    var userItemsWord = userItemsAmount == 1 ? 'item' : 'items';
                    $("#enemy-items-word").text(enemyItemsWord);
                    $("#coinflip-main-enemy").append(html);
                    $("#coinflip-enemy-items-list").hide().slideDown(300);
                    if(data[1]) {
                        $("#coinflip-modal-title").text("Success!");
                        $("#coinflip-modal-header").addClass("modal-header-success");
                        $("#coinflip-modal-sum").text('You won\n' + enemyItemsPrice + '$!');
                        $("#coinflip-modal-items").text(enemyItemsAmount + ' ' + enemyItemsWord + ' were added to your account');
                        var userResultHtml = '<div class="alert result-info alert-success text-center"> <h3>Won <span id="result-enemy">' + userItemsPrice + '</span>$</h3>';
                        userResultHtml += '<p>with <span id="result-user-change">' + $("#user-percentage").text() + '</span>% chance</p></div>';
                        var enemyResultHtml = '<div class="alert result-info alert-danger text-center"> <h3>Lost <span id="result-enemy">' + enemyItemsPrice + '</span>$</h3>';
                        enemyResultHtml += '<p>with <span id="result-user-change">' + $("#enemy-percentage").text() + '</span>% chance</p></div>';
                    }
                    else {
                        $("#coinflip-modal-title").text("Fail!");
                        $("#coinflip-modal-header").addClass("modal-header-danger");
                        $("#coinflip-modal-sum").text('You lose\n' + $("#coinflip-user-sum").text() + '$!');
                        $("#coinflip-modal-items").text(userItemsAmount + ' ' + userItemsWord + ' were removed from your account');
                        var userResultHtml = '<div class="alert result-info alert-danger text-center"><h3>Lost <span id="result-user">' + userItemsPrice + '</span>$</h3>';
                        userResultHtml += '<p>with <span id="result-user-change">' + $("#user-percentage").text() + '</span>% chance</p></div>';
                        var enemyResultHtml = '<div class="alert result-info alert-success text-center"><h3>Won <span id="result-user">' + enemyItemsPrice + '</span>$</h3>';
                        enemyResultHtml += '<p>with <span id="result-user-change">' + $("#enemy-percentage").text() + '</span>% chance</p></div>';
                    }
                    // var userResultHtml = '<h3 class="alert ' + data[1] ? 'alert-success' : 'alert-danger' + ' text-center">' + data[1] ? ' Won' : ' Lost' + ' <span id="result-user">' + userItemsPrice + '</span>$</h3>';
                    // var enemyResultHtml = '<h3 class="alert ' + data[1] ? 'alert-danger' : 'alert-success' + ' text-center">' + data[1] ? ' Lost' : ' Won' + '  <span id="result-enemy">' + enemyItemsPrice + '</span>$</h3>';
                    $("#coinflip-main-user").append(userResultHtml);
                    $("#coinflip-main-enemy").append(enemyResultHtml);

                    setTimeout(function() {
                        $("#myModal").modal("show");
                        var oldUserMoney = parseInt($("#top-user-money").text());
                        var newUserMoney = data[1] ? oldUserMoney + userItemsPrice : oldUserMoney - userItemsPrice;
                        $("#top-user-money").text(newUserMoney.toFixed(2));
                        $(".result-info").each(function() {
                            $(this).slideDown(500);
                        });
                        $("#btn-try-again").show(500);
                    }, 6200);
                }, randDelay);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    $(document).on('click', ".coinflip-side-image", function() {
        if(coinButtonsEnable) {
            if($(this).hasClass("coinflip-side-image-ct")) {
                $(".coinflip-side-image-t").first().attr("src", "storage/images/coinflip/ct.png").removeClass("coinflip-side-image-t").addClass("coinflip-side-image-ct");
                $(this).attr("src", "storage/images/coinflip/t.png").removeClass("coinflip-side-image-ct").addClass("coinflip-side-image-t");
            }
            else if($(this).hasClass("coinflip-side-image-t")) {
                $(".coinflip-side-image-ct").first().attr("src", "storage/images/coinflip/t.png").removeClass("coinflip-side-image-ct").addClass("coinflip-side-image-t");
                $(this).attr("src", "storage/images/coinflip/ct.png").removeClass("coinflip-side-image-t").addClass("coinflip-side-image-ct");
            }
            else {
                console.log("Something went wrong ):");
            }

        }
    });
});
