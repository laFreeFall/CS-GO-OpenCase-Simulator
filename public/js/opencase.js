$(document).ready(function() {
    var openedCases = 0;
    var totalSum = 0;

    function insertDataInHtml(html, data) {

    }

    $("#btn-open-case").click(function() {
		$('#btn-open-case').prop('disabled', true);
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: '/case/open',
            data: {
                "caseid": $("#case-title").data("caseid")
            },
            dataType: 'json',
            success: function (data) {
                openedCases++;
                var wonIndex = data[1];
                totalSum += data[0][wonIndex]['price'];
                var html = '';
                var htmlWinner = '';
                html += '<div id="items-roulette-spinnable-wrap">\n';
                $.each(data[0], function(index, value) {
                    var stattrak = value['stattrak'] ? 'Stattrak&#8482; ' : '';
                    var souvenir = value['souvenir'] ? 'Souvenir ' : '';
                    html += '\t<div class="item items-roulette-item" id="items-roulette-item-' + index + '">\n';
                    html += '\t\t<div class="item-image-block">\n';
                    html += '\t\t\t<img src="/storage/images/itemz/' + value['image'] + '" alt="' + value['weaponName'] + ' | ' + value['patternName'] + '" class="item-image item-abstract-image center-block">\n';
                    html += '\t\t\t<div class="item-image-shadow"></div>\n';
                    html += '\t\t</div>\n';
                    html += '\t\t<div class="item-title item-background-' + value['rarity'] + '">\n';
                    html += '\t\t\t<p class="item-title-weapon text-center">' + stattrak + souvenir + value['weaponName'] + '</p>\n';
                    html += '\t\t\t<p class="item-title-pattern text-center">' + value['patternName'] + '</p>\n';
                    html += '\t\t</div>\n';
                    html += '\t</div>\n';
                });
                html += '</div>\n';

                $("#items-roulette-spinnable-wrap").replaceWith(html);

                // var stattrakModal = data[0][wonIndex]['stattrak'] ? 'ST&#8482; ' : '';
                // var htmlModal = '';
                // htmlModal += '<div class="modal-body modal-item-background-' + data[0][wonIndex]['rarity'] + '" id="opencase-modal-new-item-body" data-itemid="' + data[0][wonIndex]['id'] + '">';
                // htmlModal += '\t<div class="modal-star-bg-wrap item-color-' + data[0][wonIndex]['rarity'] + '">';
                // htmlModal += '\t<i class="fa fa-star fa-spin modal-star-bg" aria-hidden="true"></i></div>';
                // htmlModal += '\t<img src="/storage/images/itemz/' + data[0][wonIndex]['image'] + '" alt="' + data[0][wonIndex]['weaponName'] + ' | ' + data[0][wonIndex]['weaponPattern'] + '" class="modal-item-image center-block">\n';
                //     htmlModal += '\t<p class="modal-item-name text-center item-background-' +data[0][wonIndex]['rarity'] + '">' + stattrakModal + data[0][wonIndex]['weaponName'] + ' | ' + data[0][wonIndex]['weaponPattern'] + '<br>' + data[0][wonIndex]['condition'] + ' | <strong><span id="modal-item-price">' + data[0][wonIndex]['price'] + '</span>$</strong> </p>\n';
                // htmlModal += '</div>\n';
                // console.log(data[0][wonIndex]);
                // var item = {
                //     id: data[0][wonIndex]['id'],
                //     blockId: 1,
                //     weaponName: data[0][wonIndex]['weaponName'],
                //     patternName: data[0][wonIndex]['weaponPattern'],
                //     rarity: data[0][wonIndex]['rarity'],
                //     condition: data[0][wonIndex]['condition'],
                //     stattrak: data[0][wonIndex]['stattrak'],
                //     image: data[0][wonIndex]['image'],
                //     price: data[0][wonIndex]['price']
                // };
                // console.log(data[0][wonIndex]);


                var rouletteSpinnable = $("#items-roulette-spinnable");
                rouletteSpinnable.css("margin-left", "0");
                var randomMargin = ((130 + 3) * wonIndex) + Math.floor(Math.random() * ((-96) - (-222) + 1)) + (-222);
                // var randomMargin = ((130 + 3) * wonIndex) + -96;
                // console.log('Index: ' + wonIndex + ' Margin MAX: ' + randomMargin);

                rouletteSpinnable.animate({
                    'marginLeft' : "-=" + randomMargin + "px"
                }, {
                    duration: 5000,
                    specialEasing: {
                        marginLeft: "easeOutQuart"
                    },
                    step: function() {
                        $('#btn-open-case').prop('disabled', true);
                    },
                    complete: function() {
                        $("#top-user-money").text((parseFloat($("#top-user-money").text()) + data[0][wonIndex]['price']).toFixed(2));
                        displayItemModal(data[0][wonIndex]);
                        var stattrak = data[0][wonIndex]['stattrak'] ? 'StatTrak ' : '';
                        var souvenir = data[0][wonIndex]['souvenir'] ? 'Souvenir ' : '';
                        $("#modal-confirm-item-name").replaceWith('<span id="modal-confirm-item-name">' + stattrak + souvenir + data[0][wonIndex]['weaponName'] + ' | ' + data[0][wonIndex]['patternName'] + '</span>');
                        $("#modal-confirm-item-price").replaceWith('<span id="modal-confirm-item-price">' + Math.floor(data[0][wonIndex]['price']) + '</span>');
                        $("#modal-confirm-item-image").attr('src', '/storage/images/itemz/' + data[0][wonIndex]['image']);
                        console.log('Success. Case #' + openedCases + ' opened. Item: ' + data[0][wonIndex]['weaponName'] + ' | ' + data[0][wonIndex]['patternName'] + " (" + data[0][wonIndex]['price'] + "$) [" + totalSum.toFixed(2) + "/" + ((2.49 + 0.07) * openedCases).toFixed(2) + "].");

                        $('#myModal').modal('show');
                        $('#btn-open-case').prop('disabled', false);
                    }
                });

            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    // $(document).on('click', "#sell-item-btn", function() {
    $("#sell-inventory-item-btn, #delete-inventory-item-btn").click(function() {
        if($(this).attr('id') == 'delete-inventory-item-btn') {
            $("#modal-confirm-text").html("Do you really want to delete <br> <strong>" + $("#modal-itemname").text() + "</strong>?")
        }
        $('#modal-confirm').modal({backdrop: 'static', keyboard: false})
            .one('click', "#btn-confirm-sell-inventory-item", function () {
                // console.log($("#opencase-modal-new-item-body").data("itemid"));
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '/sell-item',
                    data: {
                        "itemid": $("#myModal-body").data("itemid")
                    },
                    dataType: 'json',
                    success: function (data) {
                        $("#opencase-modal-new-item").modal('hide');
                        var itemPrice = $("#myModal-body").data("price");
                        var itemPriceFloor = Math.floor(itemPrice);
                        console.log(itemPrice);
                        if (itemPrice > 1) {
                            var topUserPointsBlock = $("#top-user-points");
                            var topUserPoints = parseInt(topUserPointsBlock.text());
                            topUserPointsBlock.prop('number', topUserPoints).animateNumber({number: (topUserPoints + itemPriceFloor)});
                            console.log('Points: ' + topUserPoints + ' - ' + itemPriceFloor + ' = ' + (topUserPoints + itemPriceFloor));
                        }

                            var topUserMoneyBlock = $("#top-user-money");
                            var topUserMoney = parseFloat(topUserMoneyBlock.text());
                            topUserMoneyBlock.text((topUserMoney - itemPrice).toFixed(2));
                            console.log('Money: ' + topUserMoney + ' + ' + itemPrice + ' = ' + (topUserMoney + itemPrice));

                        $('#myModal').modal('hide');

                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
    });
});
