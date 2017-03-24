var itSelectInput, itClearInputRow, itChangeCss;
(function ($) {
    "use strict";
    itClearInputRow = function ($grid, inputClass, css) {
        if (css.length) {
            $grid.find('.' + inputClass).each(function () {
                $(this).closest('tr').removeClass(css);
            });
        }
    };
    itSelectInput = function (gridId, name, inputClass, css) {
        css = css || '';
        //cache itChangeCss for ActionColumn usage
        if (itChangeCss == null) {
            itChangeCss = css;
        }
        var $grid = $('#' + gridId), $input = $grid.find("*[name='" + name + "']"), $el;
        $input.on('change', function () {
            $el = $(this);
            //add css class
            var $row = $el.parent().closest('tr');
            if (css.length) {
                $row.addClass(css);
            }
            updatePageSummary($el);
        });
    };
})(window.jQuery);