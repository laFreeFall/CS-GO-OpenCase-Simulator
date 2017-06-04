$(document).ready(function() {
    function insertDataInHtml(html, data) {

    }
    $.fn.digits = function(){
        return this.each(function(){
            $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.") );
        })
    };

    var userInputBet = $("#user-input-bet");

    $(".user-input-button").click(function() {
        var betValue = (userInputBet.val() < 0) ? 0 : userInputBet.val();
        userInputBet.val(betValue);
    });

    $("#user-input-clear").click(function() {
        userInputBet.val("");
    });
    $("#user-input-1").click(function() {
        var userInputBetValue = 0;
        Number.isInteger(parseInt(userInputBet.val())) ? userInputBetValue = parseInt(userInputBet.val()) : 0;
        var userInputBalance = parseInt($("#user-input-balance").text());
        if(userInputBetValue + 1000 > userInputBalance)
            userInputBet.val(userInputBalance);
        else
            userInputBet.val(userInputBetValue + 1);
    });
    $("#user-input-10").click(function() {
        var userInputBetValue = 0;
        Number.isInteger(parseInt(userInputBet.val())) ? userInputBetValue = parseInt(userInputBet.val()) : 0;
        var userInputBalance = parseInt($("#user-input-balance").text());
        if(userInputBetValue + 10 > userInputBalance)
            userInputBet.val(userInputBalance);
        else
            userInputBet.val(userInputBetValue + 10);
    });
    $("#user-input-100").click(function() {
        var userInputBetValue = 0;
        Number.isInteger(parseInt(userInputBet.val())) ? userInputBetValue = parseInt(userInputBet.val()) : 0;
        var userInputBalance = parseInt($("#user-input-balance").text());
        if(userInputBetValue + 100 > userInputBalance)
            userInputBet.val(userInputBalance);
        else
            userInputBet.val(userInputBetValue + 100);
    });
    $("#user-input-1000").click(function() {
        var userInputBetValue = 0;
        Number.isInteger(parseInt(userInputBet.val())) ? userInputBetValue = parseInt(userInputBet.val()) : 0;
        var userInputBalance = parseInt($("#user-input-balance").text());
        if(userInputBetValue + 1000 > userInputBalance)
            userInputBet.val(userInputBalance);
        else
            userInputBet.val(userInputBetValue + 1000);
    });
    $("#user-input-half").click(function() {
        var userInputBalance = parseInt($("#user-input-balance").text());

        userInputBet.val(Math.floor(userInputBalance / 2));
    });
    $("#user-input-twice").click(function() {
        var userInputBetValue = 0;
        var userInputBalance = parseInt($("#user-input-balance").text());
        Number.isInteger(parseInt(userInputBet.val())) ? userInputBetValue = parseInt(userInputBet.val()) : 0;
        if(userInputBetValue * 2 > userInputBalance)
            userInputBet.val(userInputBalance);
        else
            userInputBet.val(userInputBetValue * 2);
    });
    $("#user-input-max").click(function() {
        var userInputBalance = parseInt($("#user-input-balance").text());

        userInputBet.val(userInputBalance);
    });

    $(".bets-col-head").click(function() {

        var currentBet = parseInt(userInputBet.val());
        if(Number.isInteger(currentBet)) {
            $("#top-status").text('Bets are taking...');
            if(currentBet > parseInt($("#top-user-points").text()))
                currentBet = parseInt($("#top-user-points").text());
            if(currentBet < 0)
                currentBet = 0;
            var baseBlock = $(this).closest('.bets-col');
            var oldBet = parseInt(baseBlock.find('.bets-col-own-bet').text());
            baseBlock.find('.bets-col-own-bet').text(oldBet + currentBet);
            baseBlock.find('.bets-col-total-betq').text(oldBet + currentBet);

            $("#user-input-balance").text(parseInt($("#user-input-balance").text()) - currentBet);
            $("#top-user-points").text(parseInt($("#top-user-points").text()) - currentBet);

            if(currentBet > parseInt($("#top-user-points").text()))
                userInputBet.val(parseInt($("#top-user-points").text()));
        }
    });

    $("#btn-roulette-spin").click(function() {
        var colors = {
            red: parseInt($("#user-bet-red").text()),
            black: parseInt($("#user-bet-black").text()),
            green: parseInt($("#user-bet-green").text())
        };
        function getColor(number) {
            var color = '';
            if((number >= 1) && (number <= 7)) { // RED
                color = 'red';
            }
            else if((number >= 8) && (number <= 14)) { // BLACK
                color = 'black';
            }
            else { // GREEN
                color = 'green';
            }
            return color;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: '/get-roulette-numbers',
            data: {
                colors: colors
            },
            dataType: 'json',
            success: function (data) {
                $("#top-status").text('Rolling...');
                $("#diamonds-roulette-window-line").show("slow");
                var html = '';
                html += '<div id="roulette-spinnable-wrap">\n';
                var numbersCount = data[0].length;
                for (var i = 0; i < numbersCount; i++) {
                    html += '\t<div class="diamonds-roulette-item diamonds-roulette-bg-' + getColor(data[0][i]) + '" id="diamonds-roulette-case-item-"' + i + '>\n';
                    html += '\t\<span class="diamonds-roulette-inner-text">' + data[0][i] + '</span> \n';
                    html += '\t</div>\n';
                }
                $.each(data, function(index, value) {

                });
                html += '</div>\n';

                $("#diamonds-roulette-spinnable-wrap").replaceWith(html);
                var rouletteSpinnable = $("#diamonds-roulette-spinnable");
                rouletteSpinnable.css("margin-left", "0");
                var randomMargin = (data[1]['index'] * 70 + Math.floor(Math.random() * (66 - 2 + 1) + (2))) - 70 * 7.5;

                rouletteSpinnable.animate({
                    'marginLeft' : "-=" + randomMargin + "px"
                }, {
                    duration: 6000,
                    specialEasing: {
                        marginLeft: "easeOutQuart"
                    },
                    step: function() {
                        $('#btn-roulette-spin').prop('disabled', true);
                        //costil'
                        $('.bets-col-head').each(function() {
                            $(this).prop('disabled', true);

                        })
                    },
                    complete: function() {
                        var rolledGreen = data[1]['number'] == 0 ? 'WOW! ': '';
                        $("#top-status").text(rolledGreen + 'Rolled ' + data[1]['number'] + '!');
                        if($(".last-roll").length == 10)
                            $(".last-roll").first().remove();
                        $("#last-rolls").append('<div class="last-roll diamonds-roulette-bg-' + data[1]['color'] + ' diamonds-roulette-inner-text"> ' + data[1]['number'] + ' </div>');
                        if(data[2]['total'] >= 0) {
                            data[2]['total'] = 0;
                            data[2]['total'] += (parseInt(data[2]['red']) < 0) ? 0 : parseInt(data[2]['red']);
                            data[2]['total'] += (parseInt(data[2]['black']) < 0) ? 0 : parseInt(data[2]['black']);
                            data[2]['total'] += (parseInt(data[2]['green']) < 0) ? 0 : parseInt(data[2]['green']) / 2;
                            var userCurrentBalance = parseInt($("#user-input-balance").text());
                            var userNewBalance = userCurrentBalance + data[2]['total'] * 2;
                            $("#user-input-balance").prop('number', userCurrentBalance).animateNumber({ number: userNewBalance });
                            $("#top-user-points").prop('number', userCurrentBalance).animateNumber({ number: userNewBalance });

                        }

                        if(data[2]['red'] != 0) {
                            var oldRedBet = parseInt($("#user-bet-red").text());
                            if(data[2]['red'] > 0) {
                                var newRedBet = oldRedBet + parseInt(data[2]['red']);
                                $("#user-bet-red-sign").text('+');
                                $("#user-bet-red").addClass('roulette-won').prop('number', oldRedBet).animateNumber({ number: newRedBet });
                            }
                            else {
                                $("#user-bet-red").addClass('roulette-lost').prop('number', oldRedBet).animateNumber({ number: (oldRedBet * -1) });

                            }
                        }
                        if(data[2]['black'] != 0) {
                            var oldBlackBet = parseInt($("#user-bet-black").text());
                            if(data[2]['black'] > 0) {
                                var newBlackBet = oldBlackBet + parseInt(data[2]['black']);
                                $("#user-bet-black-sign").text('+');
                                $("#user-bet-black").addClass('roulette-won').prop('number', oldBlackBet).animateNumber({ number: newBlackBet });
                            }
                            else {
                                $("#user-bet-black").addClass('roulette-lost').prop('number', oldBlackBet).animateNumber({ number: (oldBlackBet * -1) });
                            }
                        }
                        if(data[2]['green'] != 0) {
                            var oldGreenBet = parseInt($("#user-bet-green").text());
                            if(data[2]['green'] > 0) {
                                var newGreenBet = oldGreenBet + parseInt(data[2]['green']);
                                $("#user-bet-green-sign").text('+');
                                $("#user-bet-green").addClass('roulette-won').prop('number', oldGreenBet).animateNumber({ number: newGreenBet });
                            }
                            else {
                                $("#user-bet-green").addClass('roulette-lost').prop('number', oldGreenBet).animateNumber({ number: (oldGreenBet * -1) });
                            }
                        }

                        setTimeout(function() {
                            $('.bets-col-own-bet').text('0');
                            $('.bets-col-total-betq').text('0');
                            $('#btn-roulette-spin').prop('disabled', false);
                            //costil'
                            $('.bets-col-head').each(function() {
                                $(this).prop('disabled', false);
                            });
                            $('.user-bet-sign').each(function() {
                                $(this).text('');
                            });
                            $("#top-status").text('Waiting for bets...');
                            $('.bets-col-own-bet').each(function() {
                               $(this).removeClass('roulette-won roulette-lost');
                            });
                        }, 3000)
                    }
                });


            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

});