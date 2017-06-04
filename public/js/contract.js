$(document).ready(function() {
    // console.log(nextItemz);

    function checkProbability() {
        $('.contract-probability-item').each(function() {
            $(this).addClass('probability-delete');
        });
        var collectionIds = [];
        $.each($(".contract-item-filled"), function() {
            collectionIds.push($(this).children().data("collection"));
        });
        var collectionIdsUnique = collectionIds.filter(onlyUnique);
        // console.log(collectionIds);
        // console.log('--------------------------------------------------------');
        for(var i = 0; i < collectionIdsUnique.length; i++) {
            // console.log('collectionIdsUnique.length: ' + collectionIdsUnique.length);
            var itemsCollection = nextItemz[collectionIdsUnique[i]];
            for(var j = 0; j < itemsCollection.length; j++) {
                var chance = (((countItemInArray(collectionIdsUnique[i], collectionIds) / collectionIds.length) / itemsCollection.length) * 100).toFixed(2);

                // console.log('Chance of ' + itemsCollection[j]['weaponName'] + ' | ' + itemsCollection[j]['weaponPattern'] + ': ' + chance + '%\n');
                if(! $(".contract-probability-item-" + itemsCollection[j]['itemid'] + "").length) {
                    var html = '<div class="col-md-2">\n';
                    html += '<div class="item inventory-item-' + itemsCollection[j]['itemid'] + ' cursor-pointer contract-probability-item contract-probability-item-' + itemsCollection[j]['itemid'] + '" id="contract-probability-item-' + itemsCollection[j]['itemid'] + '" data-itemid="'+ itemsCollection[j]['itemid'] +'">\n';
                    html += '<div class="item-border item-border-' + itemsCollection[j]['rarity'] + ' ">\n';
                    html += '<div class="item-wrap">\n';
                    html += '<div class="item-image-block">\n';
                    html += '<button class="btn btn-info btn-xs center-block"><span class="item-probability-chance">' + chance + '</span>%</button>';
                    html += '<img src="/storage/images/itemz/' + itemsCollection[j]['image'] + '"\n';
                    html += 'alt="' + itemsCollection[j]['weaponName'] + ' | ' + itemsCollection[j]['weaponPattern'] + '"\n';
                    html += 'class="item-image center-block"\n';
                    html += '>\n';
                    html += '<div class="item-image-shadow"></div>\n';
                    html += '</div>\n';
                    html += '<div class="item-title item-background-' + itemsCollection[j]['rarity'] + '">\n';
                    html += '<p class="item-title-weapon text-center">\n';
                    html += '' + itemsCollection[j]['weaponName'] + '\n';
                    html += '</p>\n';
                    html += '<p class="item-title-pattern text-center">\n';
                    html += '' + itemsCollection[j]['weaponPattern'] + '\n';
                    html += '</p>\n';
                    html += '</div>\n';
                    html += '</div>\n';
                    html += '</div>\n';
                    html += '</div></div>\n';
                    $("#contract-probability-inner").append(html);
                }

                // if()//there are no such block - remove it
                $("#contract-probability-item-" + itemsCollection[j]['itemid'] + "").removeClass('probability-delete').find(".item-probability-chance").text(chance);
            }
            // $('.contract-probability-item').each(function() {
            //     if($.inArray($(this).data("itemid"), itemsCollection[j]))
            //         $(this).remove();
            // });
        }
        // console.log('--------------------------------------------------------\n\n');
        $('.probability-delete').each(function() {
            $(this).parent().remove();
        });
    // console.log(nextItemz[$(this).data("collection")]);

    }

    $(document).on('click', ".inventory-item", function () {
        var contractItemsAmount = $(".contract-item-filled").length + 1;
        if(contractItemsAmount <= 10) {
            $("#btn-contract").prop('disabled', true);
            removeItem($(this));
            checkProbability();
            if(contractItemsAmount == 10) {
                $("#btn-contract").prop('disabled', false);
            }
        }
        if(contractItemsAmount >= 1) {
            $("#contract-probability-btn").prop('disabled', false);
        }

    });

    function countItemInArray(item, array) {
        var arrayLength = array.length;
        var itemCount = 0;
        for(var i = 0; i < arrayLength; i++) {
            if(array[i] == item)
                itemCount++;
        }
        return itemCount;
    }

    // $(document).on('mouseenter', ".inventory-item", function () {
    //     console.log('hovered');
    // });

    $(document).on('click', ".contract-item-filled", function() {
        var contractItemsAmount = $(".contract-item-filled").length - 1;
        if(contractItemsAmount < 10) {
            $("#btn-contract").prop('disabled', true);
        }
        if(contractItemsAmount == 0) {
            $("#contract-probability-btn").prop('disabled', true);
        }
        returnItemToInventory($(this));
        checkProbability();

    });

    $("#btn-contract").click(function() {

        var contractItems = [];
        $(".contract-item-filled").each(function() {
            contractItems.push($(this).children().data("itemid"));
        });

        console.log(contractItems);

        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: '/contract/exchange',
            data: {
                "items": contractItems
            },
            dataType: 'json',
            success: function (data) {
                console.log('Success: ', data);
                displayItemModal(data);
                $('#myModal').modal('show');

                var html = '';
                for(var i = 0; i < 10; i++) {
                    html += '<div class="contract-item contract-item-empty col-md-5ths"></div>\n';
                }
                var itemsSum = 0;
                $(".contract-item").each(function() {
                    itemsSum += parseFloat($(this).find('.item-price').text());
                    $(this).fadeTo(2000, 0, function() {
                            $(".contract-items").replaceWith('<div class="contract-items col-md-10 col-md-offset-1">' + html + '</div>');
                        }
                    );
                });
                $("#contract-probability-inner").empty();
                $("#contract-probability").collapse('hide');
                $("#contract-probability-btn").attr('disabled', true);
                console.log('Wasted for 10 items: ' + itemsSum.toFixed(2) + '$');
                console.log('Got: ' + data['price'] + '$');
                // $("#contract-probaability").fadeTo(2000, 0);

            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    $('body').on('mouseenter', '.inventory-item:not(tooltipstered)', function() {
        var nextItems = nextItemz[$(this).data("collection")];
        var html = '<h4 class="text-center">Items from collection </h4><ul class="list-group">';
        for(var i = 0; i < nextItems.length; i++) {
            html += '<li class="list-group-item item-background-' + nextItems[i]['rarity'] + ' contract-tooltip-item">' + nextItems[i]['weaponName'] + ' | ' + nextItems[i]['weaponPattern'] + '</li>\n';
        }
        html += '</ul>';

        $(this).tooltipster({
            theme: 'tooltipster-light',
            debug: false,
            contentAsHTML: true,
            delay: [1000, 100],
            content: html,
            trigger: 'hover'
        }).tooltipster('open');
    });

    function onlyUnique(value, index, self) {
        return self.indexOf(value) === index;
    }
	
	$(document).on('click', "#add-ten-items", function () {
        for (var i = 0; i < 10; i++) {
			$(".inventory-item").first().click();
		}

    });
//     $(".inventory-item").hover(function() {
//         var itemid = $(this).data('itemid');

    /* AJAX TOOLTIP WITH DELEGATE MOUSEENTER
     $('body').on('mouseenter', '.inventory-item:not(tooltipstered)', function() {
     $(this).tooltipster({
     theme: 'tooltipster-light',
     contentAsHTML: true,
     animation: 'fade',
     delay: [1000, 100],
     // debug: false,
     // content: 'Loading...',
     content: '',
     // 'instance' is basically the tooltip. More details in the "Object-oriented Tooltipster" section.
     functionBefore: function(instance, helper) {
     var $origin = $(helper.origin);
     // we set a variable so the data is only loaded once via Ajax, not every time the tooltip opens
     if ($origin.data('loaded') !== true) {

     $.ajaxSetup({
     headers: {
     'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
     }
     });
     $.ajax({
     type: "POST",
     url: '/contract/collection',
     data: {
     "itemid": $origin.data("itemid")
     },
     dataType: 'json',
     success: function (data) {
     var html = '<h4 class="text-center">Items from collection </h4><ul class="list-group">';
     for(var i = 0; i < data.length; i++) {
     html += '<li class="list-group-item item-background-' + data[i]['rarity'] + ' contract-tooltip-item">' + data[i]['weaponName'] + ' ' + data[i]['weaponPattern'] + '</li>\n';
     }
     html += '</ul>';
     // html = '<p>Test</p><br><p>Annother test</p>';
     console.log(html);
     // console.log(instance);
     instance.content(html);
     // instance.content( $('<div />').html(html));
     $origin.data('loaded', true);
     },
     error: function (data) {
     console.log('Error:', data);
     }
     });
     }
     }
     }).tooltipster('open');

     });
     */

    /* TOOLTIP WITHOUT MOUSEOVER DELEGATE
     $('.inventory-item').tooltipster({
     theme: 'tooltipster-light',

     // content: 'Loading...',
     content: '',
     // 'instance' is basically the tooltip. More details in the "Object-oriented Tooltipster" section.
     functionBefore: function(instance, helper) {
     var $origin = $(helper.origin);
     // we set a variable so the data is only loaded once via Ajax, not every time the tooltip opens
     if ($origin.data('loaded') !== true) {

     $.ajaxSetup({
     headers: {
     'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
     }
     });
     $.ajax({
     type: "POST",
     url: '/contract/collection',
     data: {
     "itemid": $origin.data("itemid")
     },
     dataType: 'json',
     success: function (data) {
     console.log(data);
     var html = 'Items might dropped: ';
     for(var i = 0; i < data.length; i++) {
     html += '<p>' + data[i]['weaponName'] + ' ' + data[i]['weaponPattern'] + '</p>\n';
     }
     console.log(instance);
     instance.content(decodeURI(html));
     // instance.content( $('<div />').html(html));
     $origin.data('loaded', true);
     },
     error: function (data) {
     console.log('Error:', data);
     }
     });
     }
     }
     });
     */
    // });

});
