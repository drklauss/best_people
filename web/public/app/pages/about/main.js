define(['jquery'], function ($) {
    var main = {
        init: function () {
            var $block = $('.num');
            if ($block.length > 0) {
                main.smoothIncreaseNumbers(0, $block);
            }
        },

        /**
         * Smoothly increase numbers
         * @param  {int} startNum [start from number]
         * @param  {jquery object} $block   [block with number]
         */
        smoothIncreaseNumbers: function (startNum, $block) {
            var flagShow = true;
            $(window).on('scroll', function () {
                var scrollTop = $(window).scrollTop() + $(window).height();
                var blockOffset = $block.parent().offset().top;
                if (scrollTop >= blockOffset && flagShow) {
                    $block.each(function (index, el) {
                        var getNum = $(this).text();
                        $(this).animate({num: getNum - startNum}, {
                            duration: 5000,
                            step: function (num) {
                                this.innerHTML = (num + startNum).toFixed();
                            }
                        });
                        flagShow = false;
                    });
                }
            });
        }
    }
    return main;
})