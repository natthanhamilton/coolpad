jQuery.noConflict(), jQuery(document).ready(function ($) {
    function e(e) {
        return decodeURI((RegExp(e + "=(.+?)(&|$)").exec(location.search) || [, ""])[1])
    }

    function t(e) {
        var t = e;
        return this.timer && clearTimeout(t.timer), this.timer = setTimeout(function () {
            $(t).parent().prev().find("strong").text(t.value)
        }, 100), !0
    }

    function i(e, t) {
        var i = $(e).val(), a = "style_link_" + t, o = t + "_ggf_previewer";
        if (i)if ($("." + o).fadeIn(), "none" !== i && "Select a font" !== i) {
            $("." + a).remove();
            var n = i.replace(/\s+/g, "+");
            $("head").append('<link href="http://fonts.googleapis.com/css?family=' + n + '" rel="stylesheet" type="text/css" class="' + a + '">'), $("." + o).css("font-family", i + ", sans-serif")
        } else $("." + o).css("font-family", ""), $("." + o).fadeOut()
    }

    function a(e, t) {
        var i = $(".uploaded-file"), a, o = $(this);
        return e.preventDefault(), a ? void a.open() : (a = wp.media({
            title: o.data("choose"),
            button: {text: o.data("update"), close: !1}
        }), a.on("select", function () {
            var e = a.state().get("selection").first();
            a.close(), t.find(".upload").val(e.attributes.url), "image" == e.attributes.type && t.find(".screenshot").empty().hide().append('<img class="of-option-image" src="' + e.attributes.url + '">').slideDown("fast"), t.find(".media_upload_button").unbind(), t.find(".remove-image").show().removeClass("hide"), t.find(".of-background-properties").slideDown(), n()
        }), void a.open())
    }

    function o(e) {
        e.find(".remove-image").hide().addClass("hide"), e.find(".upload").val(""), e.find(".of-background-properties").hide(), e.find(".screenshot").slideUp(), e.find(".remove-file").unbind(), $(".section-upload .upload-notice").length > 0 && $(".media_upload_button").remove(), n()
    }

    function n() {
        $(".remove-image, .remove-file").on("click", function () {
            o($(this).parents(".section-upload, .section-media, .slide_body"))
        }), $(".media_upload_button").unbind("click").click(function (e) {
            a(e, $(this).parents(".section-upload, .section-media, .slide_body"))
        })
    }

    if (jQuery(".fld").click(function () {
            var e = ".f_" + this.id;
            $(e).slideToggle("normal", "swing")
        }), $(".of-color").wpColorPicker(), $("#js-warning").hide(), $(".group").hide(), "" != e("tab") && $.cookie("of_current_opt", "#" + e("tab"), {
            expires: 7,
            path: "/"
        }), null === $.cookie("of_current_opt"))$(".group:first").fadeIn("fast"), $("#of-nav li:first").addClass("current"); else {
        var r = $("#hooks").html();
        r = jQuery.parseJSON(r), $.each(r, function (e, t) {
            $.cookie("of_current_opt") == "#of-option-" + t && ($(".group#of-option-" + t).fadeIn(), $("#of-nav li." + t).addClass("current"))
        })
    }
    $("#of-nav li a").click(function (e) {
        $("#of-nav li").removeClass("current"), $(this).parent().addClass("current");
        var t = $(this).attr("href");
        return $.cookie("of_current_opt", t, {expires: 7, path: "/"}), $(".group").hide(), $(t).fadeIn("fast"), !1
    }), $(".of-radio-img-img").click(function () {
        $(this).parent().parent().find(".of-radio-img-img").removeClass("of-radio-img-selected"), $(this).addClass("of-radio-img-selected")
    }), $(".of-radio-img-label").hide(), $(".of-radio-img-img").show(), $(".of-radio-img-radio").hide(), $(".of-radio-tile-img").click(function () {
        $(this).parent().parent().find(".of-radio-tile-img").removeClass("of-radio-tile-selected"), $(this).addClass("of-radio-tile-selected")
    }), $(".of-radio-tile-label").hide(), $(".of-radio-tile-img").show(), $(".of-radio-tile-radio").hide(), $(".slide_body").hide(), $(".slide_edit_button").live("click", function () {
        return $(this).parent().toggleClass("active").next().slideToggle("fast"), !1
    }), $(".of-slider-title").live("keyup", function () {
        t(this)
    }), $(".slide_delete_button").live("click", function () {
        var e = confirm("Are you sure you wish to delete this slide?");
        if (e) {
            var t = $(this).parents("li");
            return t.animate({opacity: .25, height: 0}, 500, function () {
                $(this).remove()
            }), !1
        }
        return !1
    }), $(".slide_add_button").live("click", function () {
        var e = $(this).prev(), t = e.attr("id"), i = $("#" + t + " li").find(".order").map(function () {
            var e = this.id;
            return e = e.replace(/\D/g, ""), e = parseFloat(e)
        }).get(), a = Math.max.apply(Math, i);
        1 > a && (a = 0);
        var o = a + 1, r = '<li class="temphide"><div class="slide_header"><strong>Slide ' + o + '</strong><input type="hidden" class="slide of-input order" name="' + t + "[" + o + '][order]" id="' + t + "_slide_order-" + o + '" value="' + o + '"><a class="slide_edit_button" href="#">Edit</a></div><div class="slide_body" style="display: none; "><label>Title</label><input class="slide of-input of-slider-title" name="' + t + "[" + o + '][title]" id="' + t + "_" + o + '_slide_title" value=""><label>Image URL</label><input class="upload slide of-input" name="' + t + "[" + o + '][url]" id="' + t + "_" + o + '_slide_url" value=""><div class="upload_button_div"><span class="button media_upload_button" id="' + t + "_" + o + '">Upload</span><span class="button remove-image hide" id="reset_' + t + "_" + o + '" title="' + t + "_" + o + '">Remove</span></div><div class="screenshot"></div><label>Link URL (optional)</label><input class="slide of-input" name="' + t + "[" + o + '][link]" id="' + t + "_" + o + '_slide_link" value=""><label>Description (optional)</label><textarea class="slide of-input" name="' + t + "[" + o + '][description]" id="' + t + "_" + o + '_slide_description" cols="8" rows="8"></textarea><a class="slide_delete_button" href="#">Delete</a><div class="clear"></div></div></li>';
        e.append(r);
        var s = e.find(".temphide");
        return s.fadeIn("fast", function () {
            $(this).removeClass("temphide")
        }), n(), !1
    }), jQuery(".slider").find("ul").each(function () {
        var e = jQuery(this).attr("id");
        $("#" + e).sortable({placeholder: "placeholder", opacity: .6, handle: ".slide_header", cancel: "a"})
    }), jQuery(".sorter").each(function () {
        var e = jQuery(this).attr("id");
        $("#" + e).find("ul").sortable({
            items: "li",
            placeholder: "placeholder",
            connectWith: ".sortlist_" + e,
            opacity: .6,
            update: function () {
                $(this).find(".position").each(function () {
                    var t = $(this).parent().attr("id"), i = $(this).parent().parent().attr("id");
                    i = i.replace(e + "_", "");
                    var a = $(this).parent().parent().parent().attr("id");
                    $(this).prop("name", a + "[" + i + "][" + t + "]")
                })
            }
        })
    }), $("#of_backup_button").live("click", function () {
        var e = confirm("Click OK to backup your current saved options.");
        if (e) {
            var t = $(this), i = $(this).attr("id"), a = $("#security").val(), o = {
                action: "of_ajax_post_action",
                type: "backup_options",
                security: a
            };
            $.post(ajaxurl, o, function (e) {
                if (-1 == e) {
                    var t = $("#of-popup-fail");
                    t.fadeIn(), window.setTimeout(function () {
                        t.fadeOut()
                    }, 2e3)
                } else {
                    var i = $("#of-popup-save");
                    i.fadeIn(), window.setTimeout(function () {
                        location.reload()
                    }, 1e3)
                }
            })
        }
        return !1
    }), $("#of_restore_button").live("click", function () {
        var e = confirm("'Warning: All of your current options will be replaced with the data from your last backup! Proceed?");
        if (e) {
            var t = $(this), i = $(this).attr("id"), a = $("#security").val(), o = {
                action: "of_ajax_post_action",
                type: "restore_options",
                security: a
            };
            $.post(ajaxurl, o, function (e) {
                if (-1 == e) {
                    var t = $("#of-popup-fail");
                    t.fadeIn(), window.setTimeout(function () {
                        t.fadeOut()
                    }, 2e3)
                } else {
                    var i = $("#of-popup-save");
                    i.fadeIn(), window.setTimeout(function () {
                        location.reload()
                    }, 1e3)
                }
            })
        }
        return !1
    }), $("#of_import_button").live("click", function () {
        var e = confirm("Click OK to import options.");
        if (e) {
            var t = $(this), i = $(this).attr("id"), a = $("#security").val(), o = $("#export_data").val(), n = {
                action: "of_ajax_post_action",
                type: "import_options",
                security: a,
                data: o
            };
            $.post(ajaxurl, n, function (e) {
                var t = $("#of-popup-fail"), i = $("#of-popup-save");
                -1 == e ? (t.fadeIn(), window.setTimeout(function () {
                    t.fadeOut()
                }, 2e3)) : (i.fadeIn(), window.setTimeout(function () {
                    location.reload()
                }, 1e3))
            })
        }
        return !1
    }), $("#of_save").live("click", function () {
        var e = $("#security").val();
        $(".ajax-loading-img").fadeIn();
        var t = $('#of_form :input[name][name!="security"][name!="of_reset"]').serialize();
        $("#of_form :input[type=checkbox]").each(function () {
            this.checked || (t += "&" + this.name + "=0")
        });
        var i = {type: "save", action: "of_ajax_post_action", security: e, data: t};
        return $.post(ajaxurl, i, function (e) {
            var t = $("#of-popup-save"), i = $("#of-popup-fail"), a = $(".ajax-loading-img");
            a.fadeOut(), 1 == e ? t.fadeIn() : i.fadeIn(), window.setTimeout(function () {
                t.fadeOut(), i.fadeOut()
            }, 2e3)
        }), !1
    }), $("#of_reset").click(function () {
        var e = confirm("Click OK to reset. All settings will be lost and replaced with default settings!");
        if (e) {
            var t = $("#security").val();
            $(".ajax-reset-loading-img").fadeIn();
            var i = {type: "reset", action: "of_ajax_post_action", security: t};
            $.post(ajaxurl, i, function (e) {
                var t = $("#of-popup-reset"), i = $("#of-popup-fail"), a = $(".ajax-reset-loading-img");
                a.fadeOut(), 1 == e ? (t.fadeIn(), window.setTimeout(function () {
                    location.reload()
                }, 1e3)) : (i.fadeIn(), window.setTimeout(function () {
                    i.fadeOut()
                }, 2e3))
            })
        }
        return !1
    }), jQuery().tipsy && $(".tooltip, .typography-size, .typography-height, .typography-face, .typography-style, .of-typography-color").tipsy({
        fade: !0,
        gravity: "s",
        opacity: .7
    }), jQuery(".smof_sliderui").each(function () {
        var e = jQuery(this), t = "#" + e.data("id"), i = parseInt(e.data("val")), a = parseInt(e.data("min")), o = parseInt(e.data("max")), n = parseInt(e.data("step"));
        e.slider({
            value: i, min: a, max: o, step: n, range: "min", slide: function (e, i) {
                jQuery(t).val(i.value)
            }
        })
    }), jQuery(".cb-enable").click(function () {
        var e = $(this).parents(".switch-options");
        jQuery(".cb-disable", e).removeClass("selected"), jQuery(this).addClass("selected"), jQuery(".main_checkbox", e).attr("checked", !0);
        var t = jQuery(this), i = ".f_" + t.data("id");
        jQuery(i).slideDown("normal", "swing")
    }), jQuery(".cb-disable").click(function () {
        var e = $(this).parents(".switch-options");
        jQuery(".cb-enable", e).removeClass("selected"), jQuery(this).addClass("selected"), jQuery(".main_checkbox", e).attr("checked", !1);
        var t = jQuery(this), i = ".f_" + t.data("id");
        jQuery(i).slideUp("normal", "swing")
    }), ($.browser.msie && $.browser.version < 10 || $.browser.opera) && $(".cb-enable span, .cb-disable span").find().attr("unselectable", "on"), jQuery(".google_font_select").each(function () {
        var e = jQuery(this).attr("id");
        i(this, e)
    }), jQuery(".google_font_select").change(function () {
        var e = jQuery(this).attr("id");
        i(this, e)
    }), n()
});