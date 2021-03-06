!function (r, i) {
    function t() {
        if (l)h = "filter"; else {
            var i = r('<div id="iris-gradtest" />'), t = "linear-gradient(top,#fff,#000)";
            r.each(p, function (r, e) {
                return i.css("backgroundImage", e + t), i.css("backgroundImage").match("gradient") ? (h = r, !1) : void 0
            }), r.browser.webkit && h === !1 && (i.css("background", "-webkit-gradient(linear,0% 0%,0% 100%,from(#fff),to(#000))"), i.css("backgroundImage").match("gradient") && (h = "webkit")), i.remove()
        }
    }

    function e(i, t) {
        return i = "top" === i ? "top" : "left", t = r.isArray(t) ? t : Array.prototype.slice.call(arguments, 1), "webkit" === h ? o(i, t) : p[h] + "linear-gradient(" + i + ", " + t.join(", ") + ")"
    }

    function s(i, t) {
        i = "top" === i ? "top" : "left", t = r.isArray(t) ? t : Array.prototype.slice.call(arguments, 1);
        var e = "top" === i ? 0 : 1, s = r(this), o = t.length - 1, n = parseInt(r.browser.version, 10) >= 8 ? "-ms-filter" : "filter";
        n = "filter";
        var c = 1 === e ? "left" : "top", l = 1 === e ? "right" : "bottom", h = 1 === e ? "height" : "width", p = '<div class="iris-ie-gradient-shim" style="position:absolute;' + h + ":100%;" + c + ":%start%;" + l + ":%end%;" + n + ':%filter%;" data-color:"%color%"></div>', u = "";
        "static" === s.css("position") && s.css({position: "relative"}), t = a(t), r.each(t, function (r, i) {
            if (r === o)return !1;
            var s = t[r + 1];
            if (i.stop !== s.stop) {
                var a = 100 - parseFloat(s.stop) + "%";
                i.octoHex = new Color(i.color).toIEOctoHex(), s.octoHex = new Color(s.color).toIEOctoHex();
                var n = "progid:DXImageTransform.Microsoft.Gradient(GradientType=" + e + ", StartColorStr='" + i.octoHex + "', EndColorStr='" + s.octoHex + "')";
                u += p.replace("%start%", i.stop).replace("%end%", a).replace("%filter%", n)
            }
        }), s.find(".iris-ie-gradient-shim").remove(), r(u).prependTo(s)
    }

    function o(i, t) {
        var e = [];
        return i = "top" === i ? "0% 0%,0% 100%," : "0% 100%,100% 100%,", t = a(t), r.each(t, function (r, i) {
            e.push("color-stop(" + parseFloat(i.stop) / 100 + ", " + i.color + ")")
        }), "-webkit-gradient(linear," + i + e.join(",") + ")"
    }

    function a(i) {
        var t = [], e = [], s = [], o = i.length - 1;
        return r.each(i, function (r, i) {
            var s = i, o = !1, a = i.match(/1?[0-9]{1,2}%$/);
            a && (s = i.replace(/\s?1?[0-9]{1,2}%$/, ""), o = a.shift()), t.push(s), e.push(o)
        }), e[0] === !1 && (e[0] = "0%"), e[o] === !1 && (e[o] = "100%"), e = n(e), r.each(e, function (r) {
            s[r] = {color: t[r], stop: e[r]}
        }), s
    }

    function n(i) {
        var t = 0, e = i.length - 1, s = 0, o = !1, a, c, l, h;
        if (i.length <= 2 || r.inArray(!1, i) < 0)return i;
        for (; s < i.length - 1;)o || i[s] !== !1 ? o && i[s] !== !1 && (e = s, s = i.length) : (t = s - 1, o = !0), s++;
        for (c = e - t, h = parseInt(i[t].replace("%"), 10), a = (parseFloat(i[e].replace("%")) - h) / c, s = t + 1, l = 1; e > s;)i[s] = h + l * a + "%", l++, s++;
        return n(i)
    }

    var c = '<div class="iris-picker"><div class="iris-picker-inner"><div class="iris-square"><a class="iris-square-value" href="#"><span class="iris-square-handle ui-slider-handle"></span></a><div class="iris-square-inner iris-square-horiz"></div><div class="iris-square-inner iris-square-vert"></div></div><div class="iris-slider iris-strip"><div class="iris-slider-offset"></div></div></div></div>', l = !!(r.browser.msie && parseInt(r.browser.version, 10) < 10), h = !1, p = ["-moz-", "-webkit-", "-o-", "-ms-"], u = '.iris-picker{display:block;position:relative}.iris-error{background-color:#ffafaf}.iris-border{border-radius:3px;border:1px solid #aaa;width:200px;background-color:#fff}.iris-picker-inner{position:absolute;top:0;right:0;left:0;bottom:0}.iris-border .iris-picker-inner{top:10px;right:10px;left:10px;bottom:10px}.iris-picker .iris-square-inner{position:absolute;left:0;right:0;top:0;bottom:0}.iris-picker .iris-square,.iris-picker .iris-slider,.iris-picker .iris-square-inner,.iris-picker .iris-palette{border-radius:3px;box-shadow:inset 0 0 5px rgba(0,0,0,0.4);height:100%;width:12.5%;float:left;margin-right:5%}.iris-picker .iris-square{width:76%;margin-right:10%;position:relative}.iris-picker .iris-square-inner{width:auto;margin:0}.iris-ie-9 .iris-square,.iris-ie-9 .iris-slider,.iris-ie-9 .iris-square-inner,.iris-ie-9 .iris-palette{box-shadow:none;border-radius:0}.iris-ie-9 .iris-square,.iris-ie-9 .iris-slider,.iris-ie-9 .iris-palette{outline:1px solid rgba(0,0,0,.1)}.iris-ie-lt9 .iris-square,.iris-ie-lt9 .iris-slider,.iris-ie-lt9 .iris-square-inner,.iris-ie-lt9 .iris-palette{outline:1px solid #aaa}.iris-ie-lt9 .iris-square .ui-slider-handle{outline:1px solid #aaa;background-color:#fff;-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=30)"}.iris-ie-lt9 .iris-square .iris-square-handle{background:none;border:3px solid #fff;-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)"}.iris-picker .iris-strip{margin-right:0;position:relative}.iris-picker .iris-strip .ui-slider-handle{position:absolute;background:none;right:-3px;left:-3px;border:4px solid #aaa;border-width:4px 3px;width:auto;height:6px;border-radius:4px;box-shadow:0 1px 2px rgba(0,0,0,.2);opacity:.9;z-index:5;cursor:ns-resize}.iris-strip .ui-slider-handle:before{content:" ";position:absolute;left:-2px;right:-2px;top:-3px;bottom:-3px;border:2px solid #fff;border-radius:3px}.iris-picker .iris-slider-offset{position:absolute;top:11px;left:0;right:0;bottom:-3px}.iris-picker .iris-square-handle{background:transparent;border:5px solid #aaa;border-radius:50%;border-color:rgba(128,128,128,.5);box-shadow:none;width:12px;height:12px;position:absolute;left:-10px;top:-10px;cursor:move;opacity:1;z-index:10}.iris-picker .ui-state-focus .iris-square-handle{opacity:.8}.iris-picker .iris-square-handle:hover{border-color:#999}.iris-picker .iris-square-value:focus .iris-square-handle{box-shadow:0 0 2px rgba(0,0,0,.75);opacity:.8}.iris-picker .iris-square-handle:hover::after{border-color:#fff}.iris-picker .iris-square-handle::after{position:absolute;bottom:-4px;right:-4px;left:-4px;top:-4px;border:3px solid #f9f9f9;border-color:rgba(255,255,255,.8);border-radius:50%;content:" "}.iris-picker .iris-square-value{width:8px;height:8px;position:absolute}.iris-ie-lt9 .iris-square-value,.iris-mozilla .iris-square-value{width:1px;height:1px}.iris-palette-container{position:absolute;bottom:0;left:0;margin:0;padding:0}.iris-border .iris-palette-container{left:10px;bottom:10px}.iris-picker .iris-palette{margin:0;cursor:pointer}';
    if (l && parseInt(r.browser.version, 10) <= 7)return r.fn.iris = r.noop;
    r.fn.gradient = function (i) {
        var t = arguments;
        return this.each(function () {
            l ? s.apply(this, t) : r(this).css("backgroundImage", e.apply(this, t))
        })
    }, r.fn.raninbowGradient = function (i, t) {
        i = i || "top";
        for (var e = r.extend({}, {
            s: 100,
            l: 50
        }, t), s = "hsl(%h%," + e.s + "%," + e.l + "%)", o = 0, a = []; 360 >= o;)a.push(s.replace("%h%", o)), o += 30;
        return this.each(function () {
            r(this).gradient(i, a)
        })
    };
    var f = {
        options: {
            color: !1,
            mode: "hsl",
            controls: {horiz: "s", vert: "l", strip: "h"},
            hide: !0,
            border: !0,
            target: !1,
            width: 200,
            palettes: !1
        },
        _palettes: ["#000", "#fff", "#d33", "#d93", "#ee2", "#81d742", "#1e73be", "#8224e3"],
        _inited: !1,
        _scale: {h: 360, s: 100, l: 100, v: 100},
        _create: function () {
            var i = this, e = i.element, s = i.options.color || e.val(), o;
            if (h === !1 && t(), e.is("input") ? (i.picker = i.options.target ? r(c).appendTo(i.options.target) : r(c).insertAfter(e), i._addInputListeners(e)) : (e.append(c), i.picker = e.find(".iris-picker")), r.browser.mozilla)i.picker.addClass("iris-mozilla"); else if (r.browser.msie) {
                var a = parseInt(r.browser.version, 10);
                9 === a ? i.picker.addClass("iris-ie-9") : 8 >= a && i.picker.addClass("iris-ie-lt9")
            }
            i.options.palettes && i._addPalettes(), i.color = new Color(s).setHSpace(i.options.mode), i.options.color = i.color.toString(), i.controls = {
                square: i.picker.find(".iris-square"),
                squareDrag: i.picker.find(".iris-square-value"),
                horiz: i.picker.find(".iris-square-horiz"),
                vert: i.picker.find(".iris-square-vert"),
                strip: i.picker.find(".iris-strip"),
                stripSlider: i.picker.find(".iris-strip .iris-slider-offset")
            }, "hsv" === i.options.mode && "l" === i.options.controls.vert && (i.options.controls = {
                horiz: "h",
                vert: "v",
                strip: "s"
            }), i.hue = i.color.h(), i.options.hide && i.picker.hide(), i.options.border && i.picker.addClass("iris-border"), i._initControls(), i.active = "external", i._dimensions(), i._change()
        },
        _addPalettes: function () {
            var i = r("<div class='iris-palette-container' />"), t = r("<a class='iris-palette' tabindex='0' />"), e = r.isArray(this.options.palettes) ? this.options.palettes : this._palettes;
            r.each(e, function (r, e) {
                t.clone().data("color", e).css("backgroundColor", e).appendTo(i).height(10).width(10)
            }), this.picker.append(i)
        },
        _paint: function () {
            var r = this;
            r._paintDimension("top", "strip"), r._paintDimension("top", "vert"), r._paintDimension("left", "horiz")
        },
        _paintDimension: function (r, i) {
            var t = this, e = t.color, s = t.options.mode, o = t._getHSpaceColor(), a = t.controls[i], n = t.options.controls, c;
            if (i !== t.active && ("square" !== t.active || "strip" === i))switch (n[i]) {
                case"h":
                    if ("hsv" === s) {
                        switch (o = e.clone(), i) {
                            case"horiz":
                                o[n.vert](100);
                                break;
                            case"vert":
                                o[n.horiz](100);
                                break;
                            case"strip":
                                o.setHSpace("hsl")
                        }
                        c = o.toHsl()
                    } else c = "strip" === i ? {s: o.s, l: o.l} : {s: 100, l: o.l};
                    a.raninbowGradient(r, c);
                    break;
                case"s":
                    "hsv" === s ? "vert" === i ? c = [e.clone().a(0).s(0).toCSS("rgba"), e.clone().a(1).s(0).toCSS("rgba")] : "strip" === i ? c = [e.clone().s(100).toCSS("hsl"), e.clone().s(0).toCSS("hsl")] : "horiz" === i && (c = ["#fff", "hsl(" + o.h + ",100%,50%)"]) : c = "vert" === i && "h" === t.options.controls.horiz ? ["hsla(0, 0%, " + o.l + "%, 0)", "hsla(0, 0%, " + o.l + "%, 1)"] : ["hsl(" + o.h + ",0%,50%)", "hsl(" + o.h + ",100%,50%)"], a.gradient(r, c);
                    break;
                case"l":
                    c = "strip" === i ? ["hsl(" + o.h + ",100%,100%)", "hsl(" + o.h + ", " + o.s + "%,50%)", "hsl(" + o.h + ",100%,0%)"] : ["#fff", "rgba(255,255,255,0) 50%", "rgba(0,0,0,0) 50%", "rgba(0,0,0,1)"], a.gradient(r, c);
                    break;
                case"v":
                    c = "strip" === i ? [e.clone().v(100).toCSS(), e.clone().v(0).toCSS()] : ["rgba(0,0,0,0)", "#000"], a.gradient(r, c)
            }
        },
        _getHSpaceColor: function () {
            return "hsv" === this.options.mode ? this.color.toHsv() : this.color.toHsl()
        },
        _dimensions: function (i) {
            var t = this, e = t.options, s = t.picker.find(".iris-picker-inner"), o = t.controls, a = o.square, n = t.picker.find(".iris-strip"), c = "77.5%", l = "12%", h = 20, p = e.border ? e.width - h : e.width, u, f = r.isArray(e.palettes) ? e.palettes.length : t._palettes.length, d, g, v;
            i && (a.css("width", ""), n.css("width", ""), t.picker.removeAttr("style")), c = p * (parseFloat(c) / 100), l = p * (parseFloat(l) / 100), u = e.border ? c + h : c, a.width(c).height(c), n.height(c).width(l), t.picker.css({
                width: e.width,
                height: u
            }), e.palettes && (d = 2 * c / 100, v = c - (f - 1) * d, g = v / f, t.picker.find(".iris-palette").each(function (i, t) {
                var e = 0 === i ? 0 : d;
                r(this).css({width: g, height: g, marginLeft: e})
            }), t.picker.css("paddingBottom", g + d), n.height(g + d + c))
        },
        _addInputListeners: function (r) {
            var i = this, t = 100, e = function (t) {
                var e = new Color(r.val()), s = r.val().replace(/^#/, "");
                r.removeClass("iris-error"), e.error ? "" !== s && r.addClass("iris-error") : e.toString() !== i.color.toString() && ("keyup" !== t.type || !s.match(/^[0-9a-fA-F]{3}$/)) && i._setOption("color", e.toString())
            };
            r.on("change", e).on("keyup", i._debounce(e, t))
        },
        _initControls: function () {
            var i = this, t = i.controls, e = t.square, s = i.options.controls, o = i._scale[s.strip];
            t.stripSlider.slider({
                orientation: "vertical", max: o, slide: function (r, t) {
                    i.active = "strip", "h" === s.strip && (t.value = o - t.value), i.color[s.strip](t.value), i._change.apply(i, arguments)
                }
            }), t.squareDrag.draggable({
                containment: "parent", zIndex: 1e3, cursor: "move", drag: function (r, t) {
                    i._squareDrag(r, t)
                }, start: function () {
                    e.addClass("iris-dragging"), r(this).addClass("ui-state-focus")
                }, stop: function () {
                    e.removeClass("iris-dragging"), r(this).removeClass("ui-state-focus")
                }
            }).on("mousedown mouseup", function (t) {
                t.preventDefault();
                var e = "ui-state-focus";
                "mousedown" === t.type ? (i.picker.find("." + e).removeClass(e).blur(), r(this).addClass(e).focus()) : r(this).removeClass(e)
            }).on("keydown", function (r) {
                var e = t.square, s = t.squareDrag, o = s.position(), a = i.options.width / 100;
                switch (r.altKey && (a *= 10), r.keyCode) {
                    case 37:
                        o.left -= a;
                        break;
                    case 38:
                        o.top -= a;
                        break;
                    case 39:
                        o.left += a;
                        break;
                    case 40:
                        o.top += a;
                        break;
                    default:
                        return !0
                }
                o.left = Math.max(0, Math.min(o.left, e.width())), o.top = Math.max(0, Math.min(o.top, e.height())), s.css(o), i._squareDrag(r, {position: o}), r.preventDefault()
            }), e.mousedown(function (t) {
                if (1 === t.which && r(t.target).is("div")) {
                    var e = i.controls.square.offset(), s = {top: t.pageY - e.top, left: t.pageX - e.left};
                    t.preventDefault(), i._squareDrag(t, {position: s}), t.target = i.controls.squareDrag.get(0), i.controls.squareDrag.css(s).trigger(t)
                }
            }), i.options.palettes && i.picker.find(".iris-palette-container").on("click", ".iris-palette", function (t) {
                i.color.fromCSS(r(this).data("color")), i.active = "external", i._change()
            }).on("keydown", ".iris-palette", function (i) {
                return 13 !== i.keyCode && 32 !== i.keyCode ? !0 : (i.stopPropagation(), void r(this).click())
            })
        },
        _squareDrag: function (r, i) {
            var t = this, e = t.options.controls, s = t._squareDimensions(), o = Math.round((s.h - i.position.top) / s.h * t._scale[e.vert]), a = t._scale[e.horiz] - Math.round((s.w - i.position.left) / s.w * t._scale[e.horiz]);
            t.color[e.horiz](a)[e.vert](o), t.active = "square", t._change.apply(t, arguments)
        },
        _setOption: function (r, i) {
            var t = this.options[r];
            if ("color" === r) {
                i = "" + i;
                var e = i.replace(/^#/, ""), s = new Color(i).setHSpace(this.options.mode);
                s.error || (this.color = s, this.options.color = this.options[r] = this.color.toString(), this.active = "external", this._change())
            }
        },
        _squareDimensions: function (r) {
            var t = this.controls.square, e, s;
            return r !== i && t.data("dimensions") ? t.data("dimensions") : (s = this.controls.squareDrag, e = {
                w: t.width(),
                h: t.height()
            }, t.data("dimensions", e), e)
        },
        _isNonHueControl: function (r, i) {
            return "square" === r && "h" === this.options.controls.strip ? !0 : "external" === i || "h" === i && "strip" === r ? !1 : !0
        },
        _change: function (i, t) {
            var e = this, s = e.controls, o = e._getHSpaceColor(), a = e.color.toString(), n = ["square", "strip"], c = e.options.controls, l = c[e.active] || "external", h = e.hue;
            "strip" === e.active ? n = [] : "external" !== e.active && n.pop(), r.each(n, function (r, i) {
                var t;
                if (i !== e.active)switch (i) {
                    case"strip":
                        t = "h" === c.strip ? e._scale[c.strip] - o[c.strip] : o[c.strip], s.stripSlider.slider("value", t);
                        break;
                    case"square":
                        var a = e._squareDimensions(), n = {
                            left: o[c.horiz] / e._scale[c.horiz] * a.w,
                            top: a.h - o[c.vert] / e._scale[c.vert] * a.h
                        };
                        e.controls.squareDrag.css(n)
                }
            }), o.h !== h && e._isNonHueControl(e.active, l) && e.color.h(h), e.hue = e.color.h(), e.options.color = e.color.toString(), e._inited && e._trigger("change", {type: e.active}, {color: e.color}), e.element.is(":input") && !e.color.error && (e.element.removeClass("iris-error"), e.element.val() !== e.color.toString() && e.element.val(e.color.toString())), e._paint(), e._inited = !0, e.active = !1
        },
        _debounce: function (r, i, t) {
            var e, s;
            return function () {
                var o = this, a = arguments, n = function () {
                    e = null, t || (s = r.apply(o, a))
                }, c = t && !e;
                return clearTimeout(e), e = setTimeout(n, i), c && (s = r.apply(o, a)), s
            }
        },
        show: function () {
            this.picker.show()
        },
        hide: function () {
            this.picker.hide()
        },
        toggle: function () {
            this.picker.toggle()
        }
    };
    r.widget("a8c.iris", f), r('<style id="iris-css">' + u + "</style>").appendTo("head")
}(jQuery), function (r, i) {
    var t = function (r, i) {
        return this instanceof t ? this._init(r, i) : new t(r, i)
    };
    t.fn = t.prototype = {
        _color: 0,
        _alpha: 1,
        error: !1,
        _hsl: {h: 0, s: 0, l: 0},
        _hsv: {h: 0, s: 0, v: 0},
        _hSpace: "hsl",
        _init: function (r) {
            var t = "noop";
            switch (typeof r) {
                case"object":
                    return r.a !== i && this.a(r.a), t = r.r !== i ? "fromRgb" : r.l !== i ? "fromHsl" : r.v !== i ? "fromHsv" : t, this[t](r);
                case"string":
                    return this.fromCSS(r);
                case"number":
                    return this.fromInt(parseInt(r, 10))
            }
            return this
        },
        _error: function () {
            return this.error = !0, this
        },
        clone: function () {
            for (var r = new t(this.toInt()), i = ["_alpha", "_hSpace", "_hsl", "_hsv", "error"], e = i.length - 1; e >= 0; e--)r[i[e]] = this[i[e]];
            return r
        },
        setHSpace: function (r) {
            return this._hSpace = "hsv" === r ? r : "hsl", this
        },
        noop: function () {
            return this
        },
        fromCSS: function (r) {
            var i, t, e = /^(rgb|hs(l|v))a?\(/;
            if (this.error = !1, r = r.replace(/^\s+/, "").replace(/\s+$/, "").replace(/;$/, ""), r.match(e) && r.match(/\)$/)) {
                if (t = r.replace(/(\s|%)/g, "").replace(e, "").replace(/,?\);?$/, "").split(","), t.length < 3)return this._error();
                if (4 === t.length && (this.a(parseFloat(t.pop())), this.error))return this;
                for (var s = t.length - 1; s >= 0; s--)if (t[s] = parseInt(t[s], 10), isNaN(t[s]))return this._error();
                return r.match(/^rgb/) ? this.fromRgb({
                    r: t[0],
                    g: t[1],
                    b: t[2]
                }) : r.match(/^hsv/) ? this.fromHsv({h: t[0], s: t[1], v: t[2]}) : this.fromHsl({
                    h: t[0],
                    s: t[1],
                    l: t[2]
                })
            }
            return this.fromHex(r)
        },
        fromRgb: function (r, t) {
            return "object" != typeof r || r.r === i || r.g === i || r.b === i ? this._error() : (this.error = !1, this.fromInt(parseInt((r.r << 16) + (r.g << 8) + r.b, 10), t))
        },
        fromHex: function (r) {
            return r = r.replace(/^#/, "").replace(/^0x/, ""), 3 === r.length && (r = r[0] + r[0] + r[1] + r[1] + r[2] + r[2]), this.error = !/^[0-9A-F]{6}$/i.test(r), this.fromInt(parseInt(r, 16))
        },
        fromHsl: function (r) {
            var t, e, s, o, a, n, c, l;
            return "object" != typeof r || r.h === i || r.s === i || r.l === i ? this._error() : (this._hsl = r, this._hSpace = "hsl", n = r.h / 360, c = r.s / 100, l = r.l / 100, 0 === c ? t = e = s = l : (o = .5 > l ? l * (1 + c) : l + c - l * c, a = 2 * l - o, t = this.hue2rgb(a, o, n + 1 / 3), e = this.hue2rgb(a, o, n), s = this.hue2rgb(a, o, n - 1 / 3)), this.fromRgb({
                r: 255 * t,
                g: 255 * e,
                b: 255 * s
            }, !0))
        },
        fromHsv: function (r) {
            var t, e, s, o, a, n, c, l, h, p, u;
            if ("object" != typeof r || r.h === i || r.s === i || r.v === i)return this._error();
            switch (this._hsv = r, this._hSpace = "hsv", t = r.h / 360, e = r.s / 100, s = r.v / 100, c = Math.floor(6 * t), l = 6 * t - c, h = s * (1 - e), p = s * (1 - l * e), u = s * (1 - (1 - l) * e), c % 6) {
                case 0:
                    o = s, a = u, n = h;
                    break;
                case 1:
                    o = p, a = s, n = h;
                    break;
                case 2:
                    o = h, a = s, n = u;
                    break;
                case 3:
                    o = h, a = p, n = s;
                    break;
                case 4:
                    o = u, a = h, n = s;
                    break;
                case 5:
                    o = s, a = h, n = p
            }
            return this.fromRgb({r: 255 * o, g: 255 * a, b: 255 * n}, !0)
        },
        fromInt: function (r, t) {
            return this._color = parseInt(r, 10), isNaN(this._color) && (this._color = 0), this._color > 16777215 ? this._color = 16777215 : this._color < 0 && (this._color = 0), t === i && (this._hsv.h = this._hsv.s = this._hsl.h = this._hsl.s = 0), this
        },
        hue2rgb: function (r, i, t) {
            return 0 > t && (t += 1), t > 1 && (t -= 1), 1 / 6 > t ? r + 6 * (i - r) * t : .5 > t ? i : 2 / 3 > t ? r + (i - r) * (2 / 3 - t) * 6 : r
        },
        toString: function () {
            var r = parseInt(this._color, 10).toString(16);
            if (this.error)return "";
            if (r.length < 6)for (var i = 6 - r.length - 1; i >= 0; i--)r = "0" + r;
            return "#" + r
        },
        toCSS: function (r, i) {
            switch (r = r || "hex", i = parseFloat(i || this._alpha), r) {
                case"rgb":
                case"rgba":
                    var t = this.toRgb();
                    return 1 > i ? "rgba( " + t.r + ", " + t.g + ", " + t.b + ", " + i + " )" : "rgb( " + t.r + ", " + t.g + ", " + t.b + " )";
                case"hsl":
                case"hsla":
                    var e = this.toHsl();
                    return 1 > i ? "hsla( " + e.h + ", " + e.s + "%, " + e.l + "%, " + i + " )" : "hsl( " + e.h + ", " + e.s + "%, " + e.l + "% )";
                default:
                    return this.toString()
            }
        },
        toRgb: function () {
            return {r: 255 & this._color >> 16, g: 255 & this._color >> 8, b: 255 & this._color}
        },
        toHsl: function () {
            var r = this.toRgb(), i = r.r / 255, t = r.g / 255, e = r.b / 255, s = Math.max(i, t, e), o = Math.min(i, t, e), a, n, c = (s + o) / 2;
            if (s === o)a = n = 0; else {
                var l = s - o;
                switch (n = c > .5 ? l / (2 - s - o) : l / (s + o), s) {
                    case i:
                        a = (t - e) / l + (e > t ? 6 : 0);
                        break;
                    case t:
                        a = (e - i) / l + 2;
                        break;
                    case e:
                        a = (i - t) / l + 4
                }
                a /= 6
            }
            return a = Math.round(360 * a), 0 === a && this._hsl.h !== a && (a = this._hsl.h), n = Math.round(100 * n), 0 === n && this._hsl.s && (n = this._hsl.s), {
                h: a,
                s: n,
                l: Math.round(100 * c)
            }
        },
        toHsv: function () {
            var r = this.toRgb(), i = r.r / 255, t = r.g / 255, e = r.b / 255, s = Math.max(i, t, e), o = Math.min(i, t, e), a, n, c = s, l = s - o;
            if (n = 0 === s ? 0 : l / s, s === o)a = n = 0; else {
                switch (s) {
                    case i:
                        a = (t - e) / l + (e > t ? 6 : 0);
                        break;
                    case t:
                        a = (e - i) / l + 2;
                        break;
                    case e:
                        a = (i - t) / l + 4
                }
                a /= 6
            }
            return a = Math.round(360 * a), 0 === a && this._hsv.h !== a && (a = this._hsv.h), n = Math.round(100 * n), 0 === n && this._hsv.s && (n = this._hsv.s), {
                h: a,
                s: n,
                v: Math.round(100 * c)
            }
        },
        toInt: function () {
            return this._color
        },
        toIEOctoHex: function () {
            var r = this.toString(), i = parseInt(255 * this._alpha, 10).toString(16);
            return 1 === i.length && (i = "0" + i), "#" + i + r.replace(/^#/, "")
        },
        toLuminosity: function () {
            var r = this.toRgb();
            return .2126 * Math.pow(r.r / 255, 2.2) + .7152 * Math.pow(r.g / 255, 2.2) + .0722 * Math.pow(r.b / 255, 2.2)
        },
        getDistanceLuminosityFrom: function (r) {
            if (r instanceof t) {
                var i = this.toLuminosity(), e = r.toLuminosity();
                return i > e ? (i + .05) / (e + .05) : (e + .05) / (i + .05)
            }
            throw"getDistanceLuminosityFrom requires a Color object"
        },
        getMaxContrastColor: function () {
            var r = this.toLuminosity(), i = r >= .5 ? "000000" : "ffffff";
            return new t(i)
        },
        getGrayscaleContrastingColor: function (r) {
            if (!r)return this.getMaxContrastColor();
            var i = 5 > r ? 5 : r, t = this.getMaxContrastColor();
            if (r = t.getDistanceLuminosityFrom(this), i >= r)return t;
            for (var e = 0 === t.toInt() ? 1 : -1; r > i;)t = t.incrementLightness(e), r = t.getDistanceLuminosityFrom(this);
            return t
        },
        getReadableContrastingColor: function (r, e) {
            if (!r instanceof t)return this;
            var s = e === i ? 5 : e, o = r.getDistanceLuminosityFrom(this), a = r.getMaxContrastColor(), n = a.getDistanceLuminosityFrom(r);
            if (s >= n)return a;
            if (o >= s)return this;
            for (var c = 0 === a.toInt() ? -1 : 1; s > o && (this.incrementLightness(c), o = this.getDistanceLuminosityFrom(r), 0 !== this._color && 16777215 !== this._color););
            return this
        },
        a: function (r) {
            if (r === i)return this._alpha;
            var t = parseFloat(r);
            return isNaN(t) ? this._error() : (this._alpha = t, this)
        },
        darken: function (r) {
            return r = r || 5, this.l(-r, !0)
        },
        lighten: function (r) {
            return r = r || 5, this.l(r, !0)
        },
        saturate: function (r) {
            return r = r || 15, this.s(r, !0)
        },
        desaturate: function (r) {
            return r = r || 15, this.s(-r, !0)
        },
        toGrayscale: function () {
            return this.setHSpace("hsl").s(0)
        },
        getComplement: function () {
            return this.h(180, !0)
        },
        getSplitComplement: function (r) {
            r = r || 1;
            var i = 180 + 30 * r;
            return this.h(i, !0)
        },
        getAnalog: function (r) {
            r = r || 1;
            var i = 30 * r;
            return this.h(i, !0)
        },
        getTetrad: function (r) {
            r = r || 1;
            var i = 60 * r;
            return this.h(i, !0)
        },
        getTriad: function (r) {
            r = r || 1;
            var i = 120 * r;
            return this.h(i, !0)
        },
        _partial: function (r) {
            var t = e[r];
            return function (e, s) {
                var o = this._spaceFunc("to", t.space);
                return e === i ? o[r] : (s === !0 && (e = o[r] + e), t.mod && (e %= t.mod), t.range && (e = e < t.range[0] ? t.range[0] : e > t.range[1] ? t.range[1] : e), o[r] = e, this._spaceFunc("from", t.space, o))
            }
        },
        _spaceFunc: function (r, i, t) {
            var e = i || this._hSpace, s = r + e.charAt(0).toUpperCase() + e.substr(1);
            return this[s](t)
        }
    };
    var e = {
        h: {mod: 360},
        s: {range: [0, 100]},
        l: {space: "hsl", range: [0, 100]},
        v: {space: "hsv", range: [0, 100]},
        r: {space: "rgb", range: [0, 255]},
        g: {space: "rgb", range: [0, 255]},
        b: {space: "rgb", range: [0, 255]}
    };
    for (var s in e)e.hasOwnProperty(s) && (t.fn[s] = t.fn._partial(s));
    r.Color = t
}("object" == typeof exports && exports || this);