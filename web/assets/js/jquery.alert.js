/**
 * Created by leo on 15/3/15.
 */


+function ($) {

    $.alert = function (param) {

        var _default = {
            ctx: null,
            msg: null,
            style: 'success',
            empty: true,
            autoclose: true,
            closebtn: true,
            timeout: 3000,
            animate: true,
            width: 200
        };

        $.extend(_default, param);

        if (_default.empty && _default.ctx) {
            _default.ctx.empty();
        }

        var _class = "alert-" + _default.style;
        var html = $('<div class="alert" role="alert"></div>');
        html.html(_default.msg);
        html.addClass(_class);
        if (_default.ctx == null) {
            var w = $(document).width();
            var h = $(document).height();
            $("body").append(html);
            html.css({
                "position": "fixed",
                "top": ((h - html.height())/3) + "px",
                "left": ((w - _default.width)/2) + "px",
                "text-align": "center",
                "width": _default.width + "px"
            }).hide();

        } else {
            _default.ctx.html(html.hide());
        }

        if (_default.animate) {
            html.fadeIn();
        } else {
            html.show();
        }

        if (_default.autoclose) {
            setTimeout(function () {
                if (_default.animate) {
                    html.fadeOut(null, function () {
                        html.remove();
                    });
                } else {
                    html.remove();
                }
            }, _default.timeout);
        }
    }


}(jQuery);