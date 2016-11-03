/* CONTENT:
 - Flickity Slider
 - Magnific Popup
 - Scroll to top
 - hoverIntent
 - Waypoints
 - Packery
 - Tooltipster
 - ImagesLoaded
 - Parallax
 - EasyZoom
 - QTY Buttons
 - jQuery Cookies
 - FastClick
 - Hammer.JS
 - Arrive.js

 */

;(function ($) {

    /*!
     * Flickity PACKAGED v1.2.1
     * Touch, responsive, flickable galleries
     *
     * Licensed GPLv3 for open source use
     * or Flickity Commercial License for commercial use
     *
     * http://flickity.metafizzy.co
     * Copyright 2015 Metafizzy
     */

    !function (t) {
        function e() {
        }

        function i(t) {
            function i(e) {
                e.prototype.option || (e.prototype.option = function (e) {
                    t.isPlainObject(e) && (this.options = t.extend(!0, this.options, e))
                })
            }

            function o(e, i) {
                t.fn[e] = function (o) {
                    if ("string" == typeof o) {
                        for (var s = n.call(arguments, 1), a = 0, l = this.length; l > a; a++) {
                            var h = this[a], c = t.data(h, e);
                            if (c)if (t.isFunction(c[o]) && "_" !== o.charAt(0)) {
                                var p = c[o].apply(c, s);
                                if (void 0 !== p)return p
                            } else r("no such method '" + o + "' for " + e + " instance"); else r("cannot call methods on " + e + " prior to initialization; attempted to call '" + o + "'")
                        }
                        return this
                    }
                    return this.each(function () {
                        var n = t.data(this, e);
                        n ? (n.option(o), n._init()) : (n = new i(this, o), t.data(this, e, n))
                    })
                }
            }

            if (t) {
                var r = "undefined" == typeof console ? e : function (t) {
                    console.error(t)
                };
                return t.bridget = function (t, e) {
                    i(e), o(t, e)
                }, t.bridget
            }
        }

        var n = Array.prototype.slice;
        "function" == typeof define && define.amd ? define("jquery-bridget/jquery.bridget", ["jquery"], i) : i("object" == typeof exports ? require("jquery") : t.jQuery)
    }(window), function (t) {
        function e(t) {
            return new RegExp("(^|\\s+)" + t + "(\\s+|$)")
        }

        function i(t, e) {
            var i = n(t, e) ? r : o;
            i(t, e)
        }

        var n, o, r;
        "classList" in document.documentElement ? (n = function (t, e) {
            return t.classList.contains(e)
        }, o = function (t, e) {
            t.classList.add(e)
        }, r = function (t, e) {
            t.classList.remove(e)
        }) : (n = function (t, i) {
            return e(i).test(t.className)
        }, o = function (t, e) {
            n(t, e) || (t.className = t.className + " " + e)
        }, r = function (t, i) {
            t.className = t.className.replace(e(i), " ")
        });
        var s = {hasClass: n, addClass: o, removeClass: r, toggleClass: i, has: n, add: o, remove: r, toggle: i};
        "function" == typeof define && define.amd ? define("classie/classie", s) : "object" == typeof exports ? module.exports = s : t.classie = s
    }(window), function () {
        "use strict";
        function t() {
        }

        function e(t, e) {
            for (var i = t.length; i--;)if (t[i].listener === e)return i;
            return -1
        }

        function i(t) {
            return function () {
                return this[t].apply(this, arguments)
            }
        }

        var n = t.prototype, o = this, r = o.EventEmitter;
        n.getListeners = function (t) {
            var e, i, n = this._getEvents();
            if (t instanceof RegExp) {
                e = {};
                for (i in n)n.hasOwnProperty(i) && t.test(i) && (e[i] = n[i])
            } else e = n[t] || (n[t] = []);
            return e
        }, n.flattenListeners = function (t) {
            var e, i = [];
            for (e = 0; e < t.length; e += 1)i.push(t[e].listener);
            return i
        }, n.getListenersAsObject = function (t) {
            var e, i = this.getListeners(t);
            return i instanceof Array && (e = {}, e[t] = i), e || i
        }, n.addListener = function (t, i) {
            var n, o = this.getListenersAsObject(t), r = "object" == typeof i;
            for (n in o)o.hasOwnProperty(n) && -1 === e(o[n], i) && o[n].push(r ? i : {listener: i, once: !1});
            return this
        }, n.on = i("addListener"), n.addOnceListener = function (t, e) {
            return this.addListener(t, {listener: e, once: !0})
        }, n.once = i("addOnceListener"), n.defineEvent = function (t) {
            return this.getListeners(t), this
        }, n.defineEvents = function (t) {
            for (var e = 0; e < t.length; e += 1)this.defineEvent(t[e]);
            return this
        }, n.removeListener = function (t, i) {
            var n, o, r = this.getListenersAsObject(t);
            for (o in r)r.hasOwnProperty(o) && (n = e(r[o], i), -1 !== n && r[o].splice(n, 1));
            return this
        }, n.off = i("removeListener"), n.addListeners = function (t, e) {
            return this.manipulateListeners(!1, t, e)
        }, n.removeListeners = function (t, e) {
            return this.manipulateListeners(!0, t, e)
        }, n.manipulateListeners = function (t, e, i) {
            var n, o, r = t ? this.removeListener : this.addListener, s = t ? this.removeListeners : this.addListeners;
            if ("object" != typeof e || e instanceof RegExp)for (n = i.length; n--;)r.call(this, e, i[n]); else for (n in e)e.hasOwnProperty(n) && (o = e[n]) && ("function" == typeof o ? r.call(this, n, o) : s.call(this, n, o));
            return this
        }, n.removeEvent = function (t) {
            var e, i = typeof t, n = this._getEvents();
            if ("string" === i)delete n[t]; else if (t instanceof RegExp)for (e in n)n.hasOwnProperty(e) && t.test(e) && delete n[e]; else delete this._events;
            return this
        }, n.removeAllListeners = i("removeEvent"), n.emitEvent = function (t, e) {
            var i, n, o, r, s = this.getListenersAsObject(t);
            for (o in s)if (s.hasOwnProperty(o))for (n = s[o].length; n--;)i = s[o][n], i.once === !0 && this.removeListener(t, i.listener), r = i.listener.apply(this, e || []), r === this._getOnceReturnValue() && this.removeListener(t, i.listener);
            return this
        }, n.trigger = i("emitEvent"), n.emit = function (t) {
            var e = Array.prototype.slice.call(arguments, 1);
            return this.emitEvent(t, e)
        }, n.setOnceReturnValue = function (t) {
            return this._onceReturnValue = t, this
        }, n._getOnceReturnValue = function () {
            return this.hasOwnProperty("_onceReturnValue") ? this._onceReturnValue : !0
        }, n._getEvents = function () {
            return this._events || (this._events = {})
        }, t.noConflict = function () {
            return o.EventEmitter = r, t
        }, "function" == typeof define && define.amd ? define("eventEmitter/EventEmitter", [], function () {
            return t
        }) : "object" == typeof module && module.exports ? module.exports = t : o.EventEmitter = t
    }.call(this), function (t) {
        function e(e) {
            var i = t.event;
            return i.target = i.target || i.srcElement || e, i
        }

        var i = document.documentElement, n = function () {
        };
        i.addEventListener ? n = function (t, e, i) {
            t.addEventListener(e, i, !1)
        } : i.attachEvent && (n = function (t, i, n) {
            t[i + n] = n.handleEvent ? function () {
                var i = e(t);
                n.handleEvent.call(n, i)
            } : function () {
                var i = e(t);
                n.call(t, i)
            }, t.attachEvent("on" + i, t[i + n])
        });
        var o = function () {
        };
        i.removeEventListener ? o = function (t, e, i) {
            t.removeEventListener(e, i, !1)
        } : i.detachEvent && (o = function (t, e, i) {
            t.detachEvent("on" + e, t[e + i]);
            try {
                delete t[e + i]
            } catch (n) {
                t[e + i] = void 0
            }
        });
        var r = {bind: n, unbind: o};
        "function" == typeof define && define.amd ? define("eventie/eventie", r) : "object" == typeof exports ? module.exports = r : t.eventie = r
    }(window), function (t) {
        function e(t) {
            if (t) {
                if ("string" == typeof n[t])return t;
                t = t.charAt(0).toUpperCase() + t.slice(1);
                for (var e, o = 0, r = i.length; r > o; o++)if (e = i[o] + t, "string" == typeof n[e])return e
            }
        }

        var i = "Webkit Moz ms Ms O".split(" "), n = document.documentElement.style;
        "function" == typeof define && define.amd ? define("get-style-property/get-style-property", [], function () {
            return e
        }) : "object" == typeof exports ? module.exports = e : t.getStyleProperty = e
    }(window), function (t, e) {
        function i(t) {
            var e = parseFloat(t), i = -1 === t.indexOf("%") && !isNaN(e);
            return i && e
        }

        function n() {
        }

        function o() {
            for (var t = {
                width: 0,
                height: 0,
                innerWidth: 0,
                innerHeight: 0,
                outerWidth: 0,
                outerHeight: 0
            }, e = 0, i = a.length; i > e; e++) {
                var n = a[e];
                t[n] = 0
            }
            return t
        }

        function r(e) {
            function n() {
                if (!d) {
                    d = !0;
                    var n = t.getComputedStyle;
                    if (h = function () {
                            var t = n ? function (t) {
                                return n(t, null)
                            } : function (t) {
                                return t.currentStyle
                            };
                            return function (e) {
                                var i = t(e);
                                return i || s("Style returned " + i + ". Are you running this code in a hidden iframe on Firefox? See http://bit.ly/getsizebug1"), i
                            }
                        }(), c = e("boxSizing")) {
                        var o = document.createElement("div");
                        o.style.width = "200px", o.style.padding = "1px 2px 3px 4px", o.style.borderStyle = "solid", o.style.borderWidth = "1px 2px 3px 4px", o.style[c] = "border-box";
                        var r = document.body || document.documentElement;
                        r.appendChild(o);
                        var a = h(o);
                        p = 200 === i(a.width), r.removeChild(o)
                    }
                }
            }

            function r(t) {
                if (n(), "string" == typeof t && (t = document.querySelector(t)), t && "object" == typeof t && t.nodeType) {
                    var e = h(t);
                    if ("none" === e.display)return o();
                    var r = {};
                    r.width = t.offsetWidth, r.height = t.offsetHeight;
                    for (var s = r.isBorderBox = !(!c || !e[c] || "border-box" !== e[c]), d = 0, u = a.length; u > d; d++) {
                        var f = a[d], v = e[f];
                        v = l(t, v);
                        var y = parseFloat(v);
                        r[f] = isNaN(y) ? 0 : y
                    }
                    var g = r.paddingLeft + r.paddingRight, m = r.paddingTop + r.paddingBottom, b = r.marginLeft + r.marginRight, x = r.marginTop + r.marginBottom, S = r.borderLeftWidth + r.borderRightWidth, C = r.borderTopWidth + r.borderBottomWidth, w = s && p, E = i(e.width);
                    E !== !1 && (r.width = E + (w ? 0 : g + S));
                    var P = i(e.height);
                    return P !== !1 && (r.height = P + (w ? 0 : m + C)), r.innerWidth = r.width - (g + S), r.innerHeight = r.height - (m + C), r.outerWidth = r.width + b, r.outerHeight = r.height + x, r
                }
            }

            function l(e, i) {
                if (t.getComputedStyle || -1 === i.indexOf("%"))return i;
                var n = e.style, o = n.left, r = e.runtimeStyle, s = r && r.left;
                return s && (r.left = e.currentStyle.left), n.left = i, i = n.pixelLeft, n.left = o, s && (r.left = s), i
            }

            var h, c, p, d = !1;
            return r
        }

        var s = "undefined" == typeof console ? n : function (t) {
            console.error(t)
        }, a = ["paddingLeft", "paddingRight", "paddingTop", "paddingBottom", "marginLeft", "marginRight", "marginTop", "marginBottom", "borderLeftWidth", "borderRightWidth", "borderTopWidth", "borderBottomWidth"];
        "function" == typeof define && define.amd ? define("get-size/get-size", ["get-style-property/get-style-property"], r) : "object" == typeof exports ? module.exports = r(require("desandro-get-style-property")) : t.getSize = r(t.getStyleProperty)
    }(window), function (t) {
        function e(t) {
            "function" == typeof t && (e.isReady ? t() : s.push(t))
        }

        function i(t) {
            var i = "readystatechange" === t.type && "complete" !== r.readyState;
            e.isReady || i || n()
        }

        function n() {
            e.isReady = !0;
            for (var t = 0, i = s.length; i > t; t++) {
                var n = s[t];
                n()
            }
        }

        function o(o) {
            return "complete" === r.readyState ? n() : (o.bind(r, "DOMContentLoaded", i), o.bind(r, "readystatechange", i), o.bind(t, "load", i)), e
        }

        var r = t.document, s = [];
        e.isReady = !1, "function" == typeof define && define.amd ? define("doc-ready/doc-ready", ["eventie/eventie"], o) : "object" == typeof exports ? module.exports = o(require("eventie")) : t.docReady = o(t.eventie)
    }(window), function (t) {
        "use strict";
        function e(t, e) {
            return t[s](e)
        }

        function i(t) {
            if (!t.parentNode) {
                var e = document.createDocumentFragment();
                e.appendChild(t)
            }
        }

        function n(t, e) {
            i(t);
            for (var n = t.parentNode.querySelectorAll(e), o = 0, r = n.length; r > o; o++)if (n[o] === t)return !0;
            return !1
        }

        function o(t, n) {
            return i(t), e(t, n)
        }

        var r, s = function () {
            if (t.matches)return "matches";
            if (t.matchesSelector)return "matchesSelector";
            for (var e = ["webkit", "moz", "ms", "o"], i = 0, n = e.length; n > i; i++) {
                var o = e[i], r = o + "MatchesSelector";
                if (t[r])return r
            }
        }();
        if (s) {
            var a = document.createElement("div"), l = e(a, "div");
            r = l ? e : o
        } else r = n;
        "function" == typeof define && define.amd ? define("matches-selector/matches-selector", [], function () {
            return r
        }) : "object" == typeof exports ? module.exports = r : window.matchesSelector = r
    }(Element.prototype), function (t, e) {
        "use strict";
        "function" == typeof define && define.amd ? define("fizzy-ui-utils/utils", ["doc-ready/doc-ready", "matches-selector/matches-selector"], function (i, n) {
            return e(t, i, n)
        }) : "object" == typeof exports ? module.exports = e(t, require("doc-ready"), require("desandro-matches-selector")) : t.fizzyUIUtils = e(t, t.docReady, t.matchesSelector)
    }(window, function (t, e, i) {
        var n = {};
        n.extend = function (t, e) {
            for (var i in e)t[i] = e[i];
            return t
        }, n.modulo = function (t, e) {
            return (t % e + e) % e
        };
        var o = Object.prototype.toString;
        n.isArray = function (t) {
            return "[object Array]" == o.call(t)
        }, n.makeArray = function (t) {
            var e = [];
            if (n.isArray(t))e = t; else if (t && "number" == typeof t.length)for (var i = 0, o = t.length; o > i; i++)e.push(t[i]); else e.push(t);
            return e
        }, n.indexOf = Array.prototype.indexOf ? function (t, e) {
            return t.indexOf(e)
        } : function (t, e) {
            for (var i = 0, n = t.length; n > i; i++)if (t[i] === e)return i;
            return -1
        }, n.removeFrom = function (t, e) {
            var i = n.indexOf(t, e);
            -1 != i && t.splice(i, 1)
        }, n.isElement = "function" == typeof HTMLElement || "object" == typeof HTMLElement ? function (t) {
            return t instanceof HTMLElement
        } : function (t) {
            return t && "object" == typeof t && 1 == t.nodeType && "string" == typeof t.nodeName
        }, n.setText = function () {
            function t(t, i) {
                e = e || (void 0 !== document.documentElement.textContent ? "textContent" : "innerText"), t[e] = i
            }

            var e;
            return t
        }(), n.getParent = function (t, e) {
            for (; t != document.body;)if (t = t.parentNode, i(t, e))return t
        }, n.getQueryElement = function (t) {
            return "string" == typeof t ? document.querySelector(t) : t
        }, n.handleEvent = function (t) {
            var e = "on" + t.type;
            this[e] && this[e](t)
        }, n.filterFindElements = function (t, e) {
            t = n.makeArray(t);
            for (var o = [], r = 0, s = t.length; s > r; r++) {
                var a = t[r];
                if (n.isElement(a))if (e) {
                    i(a, e) && o.push(a);
                    for (var l = a.querySelectorAll(e), h = 0, c = l.length; c > h; h++)o.push(l[h])
                } else o.push(a)
            }
            return o
        }, n.debounceMethod = function (t, e, i) {
            var n = t.prototype[e], o = e + "Timeout";
            t.prototype[e] = function () {
                var t = this[o];
                t && clearTimeout(t);
                var e = arguments, r = this;
                this[o] = setTimeout(function () {
                    n.apply(r, e), delete r[o]
                }, i || 100)
            }
        }, n.toDashed = function (t) {
            return t.replace(/(.)([A-Z])/g, function (t, e, i) {
                return e + "-" + i
            }).toLowerCase()
        };
        var r = t.console;
        return n.htmlInit = function (i, o) {
            e(function () {
                for (var e = n.toDashed(o), s = document.querySelectorAll(".js-" + e), a = "data-" + e + "-options", l = 0, h = s.length; h > l; l++) {
                    var c, p = s[l], d = p.getAttribute(a);
                    try {
                        c = d && JSON.parse(d)
                    } catch (u) {
                        r && r.error("Error parsing " + a + " on " + p.nodeName.toLowerCase() + (p.id ? "#" + p.id : "") + ": " + u);
                        continue
                    }
                    var f = new i(p, c), v = t.jQuery;
                    v && v.data(p, o, f)
                }
            })
        }, n
    }), function (t, e) {
        "use strict";
        "function" == typeof define && define.amd ? define("flickity/js/cell", ["get-size/get-size"], function (i) {
            return e(t, i)
        }) : "object" == typeof exports ? module.exports = e(t, require("get-size")) : (t.Flickity = t.Flickity || {}, t.Flickity.Cell = e(t, t.getSize))
    }(window, function (t, e) {
        function i(t, e) {
            this.element = t, this.parent = e, this.create()
        }

        var n = "attachEvent" in t;
        return i.prototype.create = function () {
            this.element.style.position = "absolute", n && this.element.setAttribute("unselectable", "on"), this.x = 0, this.shift = 0
        }, i.prototype.destroy = function () {
            this.element.style.position = "";
            var t = this.parent.originSide;
            this.element.style[t] = ""
        }, i.prototype.getSize = function () {
            this.size = e(this.element)
        }, i.prototype.setPosition = function (t) {
            this.x = t, this.setDefaultTarget(), this.renderPosition(t)
        }, i.prototype.setDefaultTarget = function () {
            var t = "left" == this.parent.originSide ? "marginLeft" : "marginRight";
            this.target = this.x + this.size[t] + this.size.width * this.parent.cellAlign
        }, i.prototype.renderPosition = function (t) {
            var e = this.parent.originSide;
            this.element.style[e] = this.parent.getPositionValue(t)
        }, i.prototype.wrapShift = function (t) {
            this.shift = t, this.renderPosition(this.x + this.parent.slideableWidth * t)
        }, i.prototype.remove = function () {
            this.element.parentNode.removeChild(this.element)
        }, i
    }), function (t, e) {
        "use strict";
        "function" == typeof define && define.amd ? define("flickity/js/animate", ["get-style-property/get-style-property", "fizzy-ui-utils/utils"], function (i, n) {
            return e(t, i, n)
        }) : "object" == typeof exports ? module.exports = e(t, require("desandro-get-style-property"), require("fizzy-ui-utils")) : (t.Flickity = t.Flickity || {}, t.Flickity.animatePrototype = e(t, t.getStyleProperty, t.fizzyUIUtils))
    }(window, function (t, e, i) {
        for (var n, o = 0, r = "webkit moz ms o".split(" "), s = t.requestAnimationFrame, a = t.cancelAnimationFrame, l = 0; l < r.length && (!s || !a); l++)n = r[l], s = s || t[n + "RequestAnimationFrame"], a = a || t[n + "CancelAnimationFrame"] || t[n + "CancelRequestAnimationFrame"];
        s && a || (s = function (e) {
            var i = (new Date).getTime(), n = Math.max(0, 16 - (i - o)), r = t.setTimeout(function () {
                e(i + n)
            }, n);
            return o = i + n, r
        }, a = function (e) {
            t.clearTimeout(e)
        });
        var h = {};
        h.startAnimation = function () {
            this.isAnimating || (this.isAnimating = !0, this.restingFrames = 0, this.animate())
        }, h.animate = function () {
            this.applyDragForce(), this.applySelectedAttraction();
            var t = this.x;
            if (this.integratePhysics(), this.positionSlider(), this.settle(t), this.isAnimating) {
                var e = this;
                s(function () {
                    e.animate()
                })
            }
        };
        var c = e("transform"), p = !!e("perspective");
        return h.positionSlider = function () {
            var t = this.x;
            this.options.wrapAround && this.cells.length > 1 && (t = i.modulo(t, this.slideableWidth), t -= this.slideableWidth, this.shiftWrapCells(t)), t += this.cursorPosition, t = this.options.rightToLeft && c ? -t : t;
            var e = this.getPositionValue(t);
            c ? this.slider.style[c] = p && this.isAnimating ? "translate3d(" + e + ",0,0)" : "translateX(" + e + ")" : this.slider.style[this.originSide] = e
        }, h.positionSliderAtSelected = function () {
            if (this.cells.length) {
                var t = this.cells[this.selectedIndex];
                this.x = -t.target, this.positionSlider()
            }
        }, h.getPositionValue = function (t) {
            return this.options.percentPosition ? .01 * Math.round(t / this.size.innerWidth * 1e4) + "%" : Math.round(t) + "px"
        }, h.settle = function (t) {
            this.isPointerDown || Math.round(100 * this.x) != Math.round(100 * t) || this.restingFrames++, this.restingFrames > 2 && (this.isAnimating = !1, delete this.isFreeScrolling, p && this.positionSlider(), this.dispatchEvent("settle"))
        }, h.shiftWrapCells = function (t) {
            var e = this.cursorPosition + t;
            this._shiftCells(this.beforeShiftCells, e, -1);
            var i = this.size.innerWidth - (t + this.slideableWidth + this.cursorPosition);
            this._shiftCells(this.afterShiftCells, i, 1)
        }, h._shiftCells = function (t, e, i) {
            for (var n = 0, o = t.length; o > n; n++) {
                var r = t[n], s = e > 0 ? i : 0;
                r.wrapShift(s), e -= r.size.outerWidth
            }
        }, h._unshiftCells = function (t) {
            if (t && t.length)for (var e = 0, i = t.length; i > e; e++)t[e].wrapShift(0)
        }, h.integratePhysics = function () {
            this.velocity += this.accel, this.x += this.velocity, this.velocity *= this.getFrictionFactor(), this.accel = 0
        }, h.applyForce = function (t) {
            this.accel += t
        }, h.getFrictionFactor = function () {
            return 1 - this.options[this.isFreeScrolling ? "freeScrollFriction" : "friction"]
        }, h.getRestingPosition = function () {
            return this.x + this.velocity / (1 - this.getFrictionFactor())
        }, h.applyDragForce = function () {
            if (this.isPointerDown) {
                var t = this.dragX - this.x, e = t - this.velocity;
                this.applyForce(e)
            }
        }, h.applySelectedAttraction = function () {
            var t = this.cells.length;
            if (!this.isPointerDown && !this.isFreeScrolling && t) {
                var e = this.cells[this.selectedIndex], i = this.options.wrapAround && t > 1 ? this.slideableWidth * Math.floor(this.selectedIndex / t) : 0, n = -1 * (e.target + i) - this.x, o = n * this.options.selectedAttraction;
                this.applyForce(o)
            }
        }, h
    }), function (t, e) {
        "use strict";
        if ("function" == typeof define && define.amd)define("flickity/js/flickity", ["classie/classie", "eventEmitter/EventEmitter", "eventie/eventie", "get-size/get-size", "fizzy-ui-utils/utils", "./cell", "./animate"], function (i, n, o, r, s, a, l) {
            return e(t, i, n, o, r, s, a, l)
        }); else if ("object" == typeof exports)module.exports = e(t, require("desandro-classie"), require("wolfy87-eventemitter"), require("eventie"), require("get-size"), require("fizzy-ui-utils"), require("./cell"), require("./animate")); else {
            var i = t.Flickity;
            t.Flickity = e(t, t.classie, t.EventEmitter, t.eventie, t.getSize, t.fizzyUIUtils, i.Cell, i.animatePrototype)
        }
    }(window, function (t, e, i, n, o, r, s, a) {
        function l(t, e) {
            for (t = r.makeArray(t); t.length;)e.appendChild(t.shift())
        }

        function h(t, e) {
            var i = r.getQueryElement(t);
            return i ? (this.element = i, c && (this.$element = c(this.element)), this.options = r.extend({}, this.constructor.defaults), this.option(e), void this._create()) : void(d && d.error("Bad element for Flickity: " + (i || t)))
        }

        var c = t.jQuery, p = t.getComputedStyle, d = t.console, u = 0, f = {};
        h.defaults = {
            accessibility: !0,
            cellAlign: "center",
            freeScrollFriction: .075,
            friction: .28,
            percentPosition: !0,
            resize: !0,
            selectedAttraction: .025,
            setGallerySize: !0
        }, h.createMethods = [], r.extend(h.prototype, i.prototype), h.prototype._create = function () {
            var e = this.guid = ++u;
            this.element.flickityGUID = e, f[e] = this, this.selectedIndex = 0, this.restingFrames = 0, this.x = 0, this.velocity = 0, this.accel = 0, this.originSide = this.options.rightToLeft ? "right" : "left", this.viewport = document.createElement("div"), this.viewport.className = "flickity-viewport", h.setUnselectable(this.viewport), this._createSlider(), (this.options.resize || this.options.watchCSS) && (n.bind(t, "resize", this), this.isResizeBound = !0);
            for (var i = 0, o = h.createMethods.length; o > i; i++) {
                var r = h.createMethods[i];
                this[r]()
            }
            this.options.watchCSS ? this.watchCSS() : this.activate()
        }, h.prototype.option = function (t) {
            r.extend(this.options, t)
        }, h.prototype.activate = function () {
            if (!this.isActive) {
                this.isActive = !0, e.add(this.element, "flickity-enabled"), this.options.rightToLeft && e.add(this.element, "flickity-rtl"), this.getSize();
                var t = this._filterFindCellElements(this.element.children);
                l(t, this.slider), this.viewport.appendChild(this.slider), this.element.appendChild(this.viewport), this.reloadCells(), this.options.accessibility && (this.element.tabIndex = 0, n.bind(this.element, "keydown", this)), this.emit("activate");
                var i, o = this.options.initialIndex;
                i = this.isInitActivated ? this.selectedIndex : void 0 !== o && this.cells[o] ? o : 0, this.select(i, !1, !0), this.isInitActivated = !0
            }
        }, h.prototype._createSlider = function () {
            var t = document.createElement("div");
            t.className = "flickity-slider", t.style[this.originSide] = 0, this.slider = t
        }, h.prototype._filterFindCellElements = function (t) {
            return r.filterFindElements(t, this.options.cellSelector)
        }, h.prototype.reloadCells = function () {
            this.cells = this._makeCells(this.slider.children), this.positionCells(), this._getWrapShiftCells(), this.setGallerySize()
        }, h.prototype._makeCells = function (t) {
            for (var e = this._filterFindCellElements(t), i = [], n = 0, o = e.length; o > n; n++) {
                var r = e[n], a = new s(r, this);
                i.push(a)
            }
            return i
        }, h.prototype.getLastCell = function () {
            return this.cells[this.cells.length - 1]
        }, h.prototype.positionCells = function () {
            this._sizeCells(this.cells), this._positionCells(0)
        }, h.prototype._positionCells = function (t) {
            t = t || 0, this.maxCellHeight = t ? this.maxCellHeight || 0 : 0;
            var e = 0;
            if (t > 0) {
                var i = this.cells[t - 1];
                e = i.x + i.size.outerWidth
            }
            for (var n, o = this.cells.length, r = t; o > r; r++)n = this.cells[r], n.setPosition(e), e += n.size.outerWidth, this.maxCellHeight = Math.max(n.size.outerHeight, this.maxCellHeight);
            this.slideableWidth = e, this._containCells()
        }, h.prototype._sizeCells = function (t) {
            for (var e = 0, i = t.length; i > e; e++) {
                var n = t[e];
                n.getSize()
            }
        }, h.prototype._init = h.prototype.reposition = function () {
            this.positionCells(), this.positionSliderAtSelected()
        }, h.prototype.getSize = function () {
            this.size = o(this.element), this.setCellAlign(), this.cursorPosition = this.size.innerWidth * this.cellAlign
        };
        var v = {center: {left: .5, right: .5}, left: {left: 0, right: 1}, right: {right: 0, left: 1}};
        h.prototype.setCellAlign = function () {
            var t = v[this.options.cellAlign];
            this.cellAlign = t ? t[this.originSide] : this.options.cellAlign
        }, h.prototype.setGallerySize = function () {
            this.options.setGallerySize && (this.viewport.style.height = this.maxCellHeight + "px")
        }, h.prototype._getWrapShiftCells = function () {
            if (this.options.wrapAround) {
                this._unshiftCells(this.beforeShiftCells), this._unshiftCells(this.afterShiftCells);
                var t = this.cursorPosition, e = this.cells.length - 1;
                this.beforeShiftCells = this._getGapCells(t, e, -1), t = this.size.innerWidth - this.cursorPosition, this.afterShiftCells = this._getGapCells(t, 0, 1)
            }
        }, h.prototype._getGapCells = function (t, e, i) {
            for (var n = []; t > 0;) {
                var o = this.cells[e];
                if (!o)break;
                n.push(o), e += i, t -= o.size.outerWidth
            }
            return n
        }, h.prototype._containCells = function () {
            if (this.options.contain && !this.options.wrapAround && this.cells.length)for (var t = this.options.rightToLeft ? "marginRight" : "marginLeft", e = this.options.rightToLeft ? "marginLeft" : "marginRight", i = this.cells[0].size[t], n = this.getLastCell(), o = this.slideableWidth - n.size[e], r = o - this.size.innerWidth * (1 - this.cellAlign), s = o < this.size.innerWidth, a = 0, l = this.cells.length; l > a; a++) {
                var h = this.cells[a];
                h.setDefaultTarget(), s ? h.target = o * this.cellAlign : (h.target = Math.max(h.target, this.cursorPosition + i), h.target = Math.min(h.target, r))
            }
        }, h.prototype.dispatchEvent = function (t, e, i) {
            var n = [e].concat(i);
            if (this.emitEvent(t, n), c && this.$element)if (e) {
                var o = c.Event(e);
                o.type = t, this.$element.trigger(o, i)
            } else this.$element.trigger(t, i)
        }, h.prototype.select = function (t, e, i) {
            if (this.isActive) {
                t = parseInt(t, 10);
                var n = this.cells.length;
                this.options.wrapAround && n > 1 && (0 > t ? this.x -= this.slideableWidth : t >= n && (this.x += this.slideableWidth)), (this.options.wrapAround || e) && (t = r.modulo(t, n)), this.cells[t] && (this.selectedIndex = t, this.setSelectedCell(), i ? this.positionSliderAtSelected() : this.startAnimation(), this.dispatchEvent("cellSelect"))
            }
        }, h.prototype.previous = function (t) {
            this.select(this.selectedIndex - 1, t)
        }, h.prototype.next = function (t) {
            this.select(this.selectedIndex + 1, t)
        }, h.prototype.setSelectedCell = function () {
            this._removeSelectedCellClass(), this.selectedCell = this.cells[this.selectedIndex], this.selectedElement = this.selectedCell.element, e.add(this.selectedElement, "is-selected")
        }, h.prototype._removeSelectedCellClass = function () {
            this.selectedCell && e.remove(this.selectedCell.element, "is-selected")
        }, h.prototype.getCell = function (t) {
            for (var e = 0, i = this.cells.length; i > e; e++) {
                var n = this.cells[e];
                if (n.element == t)return n
            }
        }, h.prototype.getCells = function (t) {
            t = r.makeArray(t);
            for (var e = [], i = 0, n = t.length; n > i; i++) {
                var o = t[i], s = this.getCell(o);
                s && e.push(s)
            }
            return e
        }, h.prototype.getCellElements = function () {
            for (var t = [], e = 0, i = this.cells.length; i > e; e++)t.push(this.cells[e].element);
            return t
        }, h.prototype.getParentCell = function (t) {
            var e = this.getCell(t);
            return e ? e : (t = r.getParent(t, ".flickity-slider > *"), this.getCell(t))
        }, h.prototype.getAdjacentCellElements = function (t, e) {
            if (!t)return [this.selectedElement];
            e = void 0 === e ? this.selectedIndex : e;
            var i = this.cells.length;
            if (1 + 2 * t >= i)return this.getCellElements();
            for (var n = [], o = e - t; e + t >= o; o++) {
                var s = this.options.wrapAround ? r.modulo(o, i) : o, a = this.cells[s];
                a && n.push(a.element)
            }
            return n
        }, h.prototype.uiChange = function () {
            this.emit("uiChange")
        }, h.prototype.childUIPointerDown = function (t) {
            this.emitEvent("childUIPointerDown", [t])
        }, h.prototype.onresize = function () {
            this.watchCSS(), this.resize()
        }, r.debounceMethod(h, "onresize", 150), h.prototype.resize = function () {
            this.isActive && (this.getSize(), this.options.wrapAround && (this.x = r.modulo(this.x, this.slideableWidth)), this.positionCells(), this._getWrapShiftCells(), this.setGallerySize(), this.positionSliderAtSelected())
        };
        var y = h.supportsConditionalCSS = function () {
            var t;
            return function () {
                if (void 0 !== t)return t;
                if (!p)return void(t = !1);
                var e = document.createElement("style"), i = document.createTextNode('body:after { content: "foo"; display: none; }');
                e.appendChild(i), document.head.appendChild(e);
                var n = p(document.body, ":after").content;
                return t = -1 != n.indexOf("foo"), document.head.removeChild(e), t
            }
        }();
        h.prototype.watchCSS = function () {
            var t = this.options.watchCSS;
            if (t) {
                var e = y();
                if (!e) {
                    var i = "fallbackOn" == t ? "activate" : "deactivate";
                    return void this[i]()
                }
                var n = p(this.element, ":after").content;
                -1 != n.indexOf("flickity") ? this.activate() : this.deactivate()
            }
        }, h.prototype.onkeydown = function (t) {
            if (this.options.accessibility && (!document.activeElement || document.activeElement == this.element))if (37 == t.keyCode) {
                var e = this.options.rightToLeft ? "next" : "previous";
                this.uiChange(), this[e]()
            } else if (39 == t.keyCode) {
                var i = this.options.rightToLeft ? "previous" : "next";
                this.uiChange(), this[i]()
            }
        }, h.prototype.deactivate = function () {
            if (this.isActive) {
                e.remove(this.element, "flickity-enabled"), e.remove(this.element, "flickity-rtl");
                for (var t = 0, i = this.cells.length; i > t; t++) {
                    var o = this.cells[t];
                    o.destroy()
                }
                this._removeSelectedCellClass(), this.element.removeChild(this.viewport), l(this.slider.children, this.element), this.options.accessibility && (this.element.removeAttribute("tabIndex"), n.unbind(this.element, "keydown", this)), this.isActive = !1, this.emit("deactivate")
            }
        }, h.prototype.destroy = function () {
            this.deactivate(), this.isResizeBound && n.unbind(t, "resize", this), this.emit("destroy"), c && this.$element && c.removeData(this.element, "flickity"), delete this.element.flickityGUID, delete f[this.guid]
        }, r.extend(h.prototype, a);
        var g = "attachEvent" in t;
        return h.setUnselectable = function (t) {
            g && t.setAttribute("unselectable", "on")
        }, h.data = function (t) {
            t = r.getQueryElement(t);
            var e = t && t.flickityGUID;
            return e && f[e]
        }, r.htmlInit(h, "flickity"), c && c.bridget && c.bridget("flickity", h), h.Cell = s, h
    }), function (t, e) {
        "use strict";
        "function" == typeof define && define.amd ? define("unipointer/unipointer", ["eventEmitter/EventEmitter", "eventie/eventie"], function (i, n) {
            return e(t, i, n)
        }) : "object" == typeof exports ? module.exports = e(t, require("wolfy87-eventemitter"), require("eventie")) : t.Unipointer = e(t, t.EventEmitter, t.eventie)
    }(window, function (t, e, i) {
        function n() {
        }

        function o() {
        }

        o.prototype = new e, o.prototype.bindStartEvent = function (t) {
            this._bindStartEvent(t, !0)
        }, o.prototype.unbindStartEvent = function (t) {
            this._bindStartEvent(t, !1)
        }, o.prototype._bindStartEvent = function (e, n) {
            n = void 0 === n ? !0 : !!n;
            var o = n ? "bind" : "unbind";
            t.navigator.pointerEnabled ? i[o](e, "pointerdown", this) : t.navigator.msPointerEnabled ? i[o](e, "MSPointerDown", this) : (i[o](e, "mousedown", this), i[o](e, "touchstart", this))
        }, o.prototype.handleEvent = function (t) {
            var e = "on" + t.type;
            this[e] && this[e](t)
        }, o.prototype.getTouch = function (t) {
            for (var e = 0, i = t.length; i > e; e++) {
                var n = t[e];
                if (n.identifier == this.pointerIdentifier)return n
            }
        }, o.prototype.onmousedown = function (t) {
            var e = t.button;
            e && 0 !== e && 1 !== e || this._pointerDown(t, t)
        }, o.prototype.ontouchstart = function (t) {
            this._pointerDown(t, t.changedTouches[0])
        }, o.prototype.onMSPointerDown = o.prototype.onpointerdown = function (t) {
            this._pointerDown(t, t)
        }, o.prototype._pointerDown = function (t, e) {
            this.isPointerDown || (this.isPointerDown = !0, this.pointerIdentifier = void 0 !== e.pointerId ? e.pointerId : e.identifier, this.pointerDown(t, e))
        }, o.prototype.pointerDown = function (t, e) {
            this._bindPostStartEvents(t), this.emitEvent("pointerDown", [t, e])
        };
        var r = {
            mousedown: ["mousemove", "mouseup"],
            touchstart: ["touchmove", "touchend", "touchcancel"],
            pointerdown: ["pointermove", "pointerup", "pointercancel"],
            MSPointerDown: ["MSPointerMove", "MSPointerUp", "MSPointerCancel"]
        };
        return o.prototype._bindPostStartEvents = function (e) {
            if (e) {
                for (var n = r[e.type], o = e.preventDefault ? t : document, s = 0, a = n.length; a > s; s++) {
                    var l = n[s];
                    i.bind(o, l, this)
                }
                this._boundPointerEvents = {events: n, node: o}
            }
        }, o.prototype._unbindPostStartEvents = function () {
            var t = this._boundPointerEvents;
            if (t && t.events) {
                for (var e = 0, n = t.events.length; n > e; e++) {
                    var o = t.events[e];
                    i.unbind(t.node, o, this)
                }
                delete this._boundPointerEvents
            }
        }, o.prototype.onmousemove = function (t) {
            this._pointerMove(t, t)
        }, o.prototype.onMSPointerMove = o.prototype.onpointermove = function (t) {
            t.pointerId == this.pointerIdentifier && this._pointerMove(t, t)
        }, o.prototype.ontouchmove = function (t) {
            var e = this.getTouch(t.changedTouches);
            e && this._pointerMove(t, e)
        }, o.prototype._pointerMove = function (t, e) {
            this.pointerMove(t, e)
        }, o.prototype.pointerMove = function (t, e) {
            this.emitEvent("pointerMove", [t, e])
        }, o.prototype.onmouseup = function (t) {
            this._pointerUp(t, t)
        }, o.prototype.onMSPointerUp = o.prototype.onpointerup = function (t) {
            t.pointerId == this.pointerIdentifier && this._pointerUp(t, t)
        }, o.prototype.ontouchend = function (t) {
            var e = this.getTouch(t.changedTouches);
            e && this._pointerUp(t, e)
        }, o.prototype._pointerUp = function (t, e) {
            this._pointerDone(), this.pointerUp(t, e)
        }, o.prototype.pointerUp = function (t, e) {
            this.emitEvent("pointerUp", [t, e])
        }, o.prototype._pointerDone = function () {
            this.isPointerDown = !1, delete this.pointerIdentifier, this._unbindPostStartEvents(), this.pointerDone()
        }, o.prototype.pointerDone = n, o.prototype.onMSPointerCancel = o.prototype.onpointercancel = function (t) {
            t.pointerId == this.pointerIdentifier && this._pointerCancel(t, t)
        }, o.prototype.ontouchcancel = function (t) {
            var e = this.getTouch(t.changedTouches);
            e && this._pointerCancel(t, e)
        }, o.prototype._pointerCancel = function (t, e) {
            this._pointerDone(), this.pointerCancel(t, e)
        }, o.prototype.pointerCancel = function (t, e) {
            this.emitEvent("pointerCancel", [t, e])
        }, o.getPointerPoint = function (t) {
            return {x: void 0 !== t.pageX ? t.pageX : t.clientX, y: void 0 !== t.pageY ? t.pageY : t.clientY}
        }, o
    }), function (t, e) {
        "use strict";
        "function" == typeof define && define.amd ? define("unidragger/unidragger", ["eventie/eventie", "unipointer/unipointer"], function (i, n) {
            return e(t, i, n)
        }) : "object" == typeof exports ? module.exports = e(t, require("eventie"), require("unipointer")) : t.Unidragger = e(t, t.eventie, t.Unipointer)
    }(window, function (t, e, i) {
        function n() {
        }

        function o(t) {
            t.preventDefault ? t.preventDefault() : t.returnValue = !1
        }

        function r() {
        }

        function s() {
            return !1
        }

        r.prototype = new i, r.prototype.bindHandles = function () {
            this._bindHandles(!0)
        }, r.prototype.unbindHandles = function () {
            this._bindHandles(!1)
        };
        var a = t.navigator;
        r.prototype._bindHandles = function (t) {
            t = void 0 === t ? !0 : !!t;
            var i;
            i = a.pointerEnabled ? function (e) {
                e.style.touchAction = t ? "none" : ""
            } : a.msPointerEnabled ? function (e) {
                e.style.msTouchAction = t ? "none" : ""
            } : function () {
                t && h(s)
            };
            for (var n = t ? "bind" : "unbind", o = 0, r = this.handles.length; r > o; o++) {
                var s = this.handles[o];
                this._bindStartEvent(s, t), i(s), e[n](s, "click", this)
            }
        };
        var l = "attachEvent" in document.documentElement, h = l ? function (t) {
            "IMG" == t.nodeName && (t.ondragstart = s);
            for (var e = t.querySelectorAll("img"), i = 0, n = e.length; n > i; i++) {
                var o = e[i];
                o.ondragstart = s
            }
        } : n;
        r.prototype.pointerDown = function (i, n) {
            if ("INPUT" == i.target.nodeName && "range" == i.target.type)return this.isPointerDown = !1, void delete this.pointerIdentifier;
            this._dragPointerDown(i, n);
            var o = document.activeElement;
            o && o.blur && o.blur(), this._bindPostStartEvents(i), this.pointerDownScroll = r.getScrollPosition(), e.bind(t, "scroll", this), this.emitEvent("pointerDown", [i, n])
        }, r.prototype._dragPointerDown = function (t, e) {
            this.pointerDownPoint = i.getPointerPoint(e);
            var n = "touchstart" == t.type, r = t.target.nodeName;
            n || "SELECT" == r || o(t)
        }, r.prototype.pointerMove = function (t, e) {
            var i = this._dragPointerMove(t, e);
            this.emitEvent("pointerMove", [t, e, i]), this._dragMove(t, e, i)
        }, r.prototype._dragPointerMove = function (t, e) {
            var n = i.getPointerPoint(e), o = {x: n.x - this.pointerDownPoint.x, y: n.y - this.pointerDownPoint.y};
            return !this.isDragging && this.hasDragStarted(o) && this._dragStart(t, e), o
        }, r.prototype.hasDragStarted = function (t) {
            return Math.abs(t.x) > 3 || Math.abs(t.y) > 3
        }, r.prototype.pointerUp = function (t, e) {
            this.emitEvent("pointerUp", [t, e]), this._dragPointerUp(t, e)
        }, r.prototype._dragPointerUp = function (t, e) {
            this.isDragging ? this._dragEnd(t, e) : this._staticClick(t, e)
        }, r.prototype.pointerDone = function () {
            e.unbind(t, "scroll", this)
        }, r.prototype._dragStart = function (t, e) {
            this.isDragging = !0, this.dragStartPoint = r.getPointerPoint(e), this.isPreventingClicks = !0, this.dragStart(t, e)
        }, r.prototype.dragStart = function (t, e) {
            this.emitEvent("dragStart", [t, e])
        }, r.prototype._dragMove = function (t, e, i) {
            this.isDragging && this.dragMove(t, e, i)
        }, r.prototype.dragMove = function (t, e, i) {
            o(t), this.emitEvent("dragMove", [t, e, i])
        }, r.prototype._dragEnd = function (t, e) {
            this.isDragging = !1;
            var i = this;
            setTimeout(function () {
                delete i.isPreventingClicks
            }), this.dragEnd(t, e)
        }, r.prototype.dragEnd = function (t, e) {
            this.emitEvent("dragEnd", [t, e])
        }, r.prototype.pointerDone = function () {
            e.unbind(t, "scroll", this), delete this.pointerDownScroll
        }, r.prototype.onclick = function (t) {
            this.isPreventingClicks && o(t)
        }, r.prototype._staticClick = function (t, e) {
            if (!this.isIgnoringMouseUp || "mouseup" != t.type) {
                var i = t.target.nodeName;
                if (("INPUT" == i || "TEXTAREA" == i) && t.target.focus(), this.staticClick(t, e), "mouseup" != t.type) {
                    this.isIgnoringMouseUp = !0;
                    var n = this;
                    setTimeout(function () {
                        delete n.isIgnoringMouseUp
                    }, 400)
                }
            }
        }, r.prototype.staticClick = function (t, e) {
            this.emitEvent("staticClick", [t, e])
        }, r.prototype.onscroll = function () {
            var t = r.getScrollPosition(), e = this.pointerDownScroll.x - t.x, i = this.pointerDownScroll.y - t.y;
            (Math.abs(e) > 3 || Math.abs(i) > 3) && this._pointerDone()
        }, r.getPointerPoint = function (t) {
            return {x: void 0 !== t.pageX ? t.pageX : t.clientX, y: void 0 !== t.pageY ? t.pageY : t.clientY}
        };
        var c = void 0 !== t.pageYOffset;
        return r.getScrollPosition = function () {
            return {x: c ? t.pageXOffset : document.body.scrollLeft, y: c ? t.pageYOffset : document.body.scrollTop}
        }, r.getPointerPoint = i.getPointerPoint, r
    }), function (t, e) {
        "use strict";
        "function" == typeof define && define.amd ? define("flickity/js/drag", ["classie/classie", "eventie/eventie", "./flickity", "unidragger/unidragger", "fizzy-ui-utils/utils"], function (i, n, o, r, s) {
            return e(t, i, n, o, r, s)
        }) : "object" == typeof exports ? module.exports = e(t, require("desandro-classie"), require("eventie"), require("./flickity"), require("unidragger"), require("fizzy-ui-utils")) : t.Flickity = e(t, t.classie, t.eventie, t.Flickity, t.Unidragger, t.fizzyUIUtils)
    }(window, function (t, e, i, n, o, r) {
        function s(t) {
            t.preventDefault ? t.preventDefault() : t.returnValue = !1
        }

        r.extend(n.defaults, {draggable: !0}), n.createMethods.push("_createDrag"), r.extend(n.prototype, o.prototype), n.prototype._createDrag = function () {
            this.on("activate", this.bindDrag), this.on("uiChange", this._uiChangeDrag), this.on("childUIPointerDown", this._childUIPointerDownDrag), this.on("deactivate", this.unbindDrag)
        }, n.prototype.bindDrag = function () {
            this.options.draggable && !this.isDragBound && (e.add(this.element, "is-draggable"), this.handles = [this.viewport], this.bindHandles(), this.isDragBound = !0)
        }, n.prototype.unbindDrag = function () {
            this.isDragBound && (e.remove(this.element, "is-draggable"), this.unbindHandles(), delete this.isDragBound)
        }, n.prototype._uiChangeDrag = function () {
            delete this.isFreeScrolling
        }, n.prototype._childUIPointerDownDrag = function (t) {
            s(t), this.pointerDownFocus(t)
        }, n.prototype.pointerDown = function (n, r) {
            if ("INPUT" == n.target.nodeName && "range" == n.target.type)return this.isPointerDown = !1, void delete this.pointerIdentifier;
            this._dragPointerDown(n, r);
            var s = document.activeElement;
            s && s.blur && s != this.element && s != document.body && s.blur(), this.pointerDownFocus(n), this.dragX = this.x, e.add(this.viewport, "is-pointer-down"), this._bindPostStartEvents(n), this.pointerDownScroll = o.getScrollPosition(), i.bind(t, "scroll", this), this.dispatchEvent("pointerDown", n, [r])
        };
        var a = {touchstart: !0, MSPointerDown: !0}, l = {INPUT: !0, SELECT: !0};
        return n.prototype.pointerDownFocus = function (e) {
            if (this.options.accessibility && !a[e.type] && !l[e.target.nodeName]) {
                var i = t.pageYOffset;
                this.element.focus(), t.pageYOffset != i && t.scrollTo(t.pageXOffset, i)
            }
        }, n.prototype.hasDragStarted = function (t) {
            return Math.abs(t.x) > 3
        }, n.prototype.pointerUp = function (t, i) {
            e.remove(this.viewport, "is-pointer-down"), this.dispatchEvent("pointerUp", t, [i]), this._dragPointerUp(t, i)
        }, n.prototype.pointerDone = function () {
            i.unbind(t, "scroll", this), delete this.pointerDownScroll
        }, n.prototype.dragStart = function (t, e) {
            this.dragStartPosition = this.x, this.startAnimation(), this.dispatchEvent("dragStart", t, [e])
        }, n.prototype.dragMove = function (t, e, i) {
            s(t), this.previousDragX = this.dragX;
            var n = this.options.rightToLeft ? -1 : 1, o = this.dragStartPosition + i.x * n;
            if (!this.options.wrapAround && this.cells.length) {
                var r = Math.max(-this.cells[0].target, this.dragStartPosition);
                o = o > r ? .5 * (o + r) : o;
                var a = Math.min(-this.getLastCell().target, this.dragStartPosition);
                o = a > o ? .5 * (o + a) : o
            }
            this.dragX = o, this.dragMoveTime = new Date, this.dispatchEvent("dragMove", t, [e, i])
        }, n.prototype.dragEnd = function (t, e) {
            this.options.freeScroll && (this.isFreeScrolling = !0);
            var i = this.dragEndRestingSelect();
            if (this.options.freeScroll && !this.options.wrapAround) {
                var n = this.getRestingPosition();
                this.isFreeScrolling = -n > this.cells[0].target && -n < this.getLastCell().target
            } else this.options.freeScroll || i != this.selectedIndex || (i += this.dragEndBoostSelect());
            delete this.previousDragX, this.select(i), this.dispatchEvent("dragEnd", t, [e])
        }, n.prototype.dragEndRestingSelect = function () {
            var t = this.getRestingPosition(), e = Math.abs(this.getCellDistance(-t, this.selectedIndex)), i = this._getClosestResting(t, e, 1), n = this._getClosestResting(t, e, -1), o = i.distance < n.distance ? i.index : n.index;
            return o
        }, n.prototype._getClosestResting = function (t, e, i) {
            for (var n = this.selectedIndex, o = 1 / 0, r = this.options.contain && !this.options.wrapAround ? function (t, e) {
                return e >= t
            } : function (t, e) {
                return e > t
            }; r(e, o) && (n += i, o = e, e = this.getCellDistance(-t, n), null !== e);)e = Math.abs(e);
            return {distance: o, index: n - i}
        }, n.prototype.getCellDistance = function (t, e) {
            var i = this.cells.length, n = this.options.wrapAround && i > 1, o = n ? r.modulo(e, i) : e, s = this.cells[o];
            if (!s)return null;
            var a = n ? this.slideableWidth * Math.floor(e / i) : 0;
            return t - (s.target + a)
        }, n.prototype.dragEndBoostSelect = function () {
            if (void 0 === this.previousDragX || !this.dragMoveTime || new Date - this.dragMoveTime > 100)return 0;
            var t = this.getCellDistance(-this.dragX, this.selectedIndex), e = this.previousDragX - this.dragX;
            return t > 0 && e > 0 ? 1 : 0 > t && 0 > e ? -1 : 0
        }, n.prototype.staticClick = function (t, e) {
            var i = this.getParentCell(t.target), n = i && i.element, o = i && r.indexOf(this.cells, i);
            this.dispatchEvent("staticClick", t, [e, n, o])
        }, n
    }), function (t, e) {
        "function" == typeof define && define.amd ? define("tap-listener/tap-listener", ["unipointer/unipointer"], function (i) {
            return e(t, i)
        }) : "object" == typeof exports ? module.exports = e(t, require("unipointer")) : t.TapListener = e(t, t.Unipointer)
    }(window, function (t, e) {
        function i(t) {
            this.bindTap(t)
        }

        i.prototype = new e, i.prototype.bindTap = function (t) {
            t && (this.unbindTap(), this.tapElement = t, this._bindStartEvent(t, !0))
        }, i.prototype.unbindTap = function () {
            this.tapElement && (this._bindStartEvent(this.tapElement, !0), delete this.tapElement)
        };
        var n = void 0 !== t.pageYOffset;
        return i.prototype.pointerUp = function (i, o) {
            if (!this.isIgnoringMouseUp || "mouseup" != i.type) {
                var r = e.getPointerPoint(o), s = this.tapElement.getBoundingClientRect(), a = n ? t.pageXOffset : document.body.scrollLeft, l = n ? t.pageYOffset : document.body.scrollTop, h = r.x >= s.left + a && r.x <= s.right + a && r.y >= s.top + l && r.y <= s.bottom + l;
                h && this.emitEvent("tap", [i, o]), "mouseup" != i.type && (this.isIgnoringMouseUp = !0, setTimeout(function () {
                    delete this.isIgnoringMouseUp
                }.bind(this), 320))
            }
        }, i.prototype.destroy = function () {
            this.pointerDone(), this.unbindTap()
        }, i
    }), function (t, e) {
        "use strict";
        "function" == typeof define && define.amd ? define("flickity/js/prev-next-button", ["eventie/eventie", "./flickity", "tap-listener/tap-listener", "fizzy-ui-utils/utils"], function (i, n, o, r) {
            return e(t, i, n, o, r)
        }) : "object" == typeof exports ? module.exports = e(t, require("eventie"), require("./flickity"), require("tap-listener"), require("fizzy-ui-utils")) : e(t, t.eventie, t.Flickity, t.TapListener, t.fizzyUIUtils)
    }(window, function (t, e, i, n, o) {
        function r(t, e) {
            this.direction = t, this.parent = e, this._create()
        }

        function s(t) {
            return "string" == typeof t ? t : "M " + t.x0 + ",50 L " + t.x1 + "," + (t.y1 + 50) + " L " + t.x2 + "," + (t.y2 + 50) + " L " + t.x3 + ",50  L " + t.x2 + "," + (50 - t.y2) + " L " + t.x1 + "," + (50 - t.y1) + " Z"
        }

        var a = "http://www.w3.org/2000/svg", l = function () {
            function t() {
                if (void 0 !== e)return e;
                var t = document.createElement("div");
                return t.innerHTML = "<svg/>", e = (t.firstChild && t.firstChild.namespaceURI) == a
            }

            var e;
            return t
        }();
        return r.prototype = new n, r.prototype._create = function () {
            this.isEnabled = !0, this.isPrevious = -1 == this.direction;
            var t = this.parent.options.rightToLeft ? 1 : -1;
            this.isLeft = this.direction == t;
            var e = this.element = document.createElement("button");
            if (e.className = "flickity-prev-next-button", e.className += this.isPrevious ? " previous" : " next", e.setAttribute("type", "button"), this.disable(), e.setAttribute("aria-label", this.isPrevious ? "previous" : "next"), i.setUnselectable(e), l()) {
                var n = this.createSVG();
                e.appendChild(n)
            } else this.setArrowText(), e.className += " no-svg";
            var o = this;
            this.onCellSelect = function () {
                o.update()
            }, this.parent.on("cellSelect", this.onCellSelect), this.on("tap", this.onTap), this.on("pointerDown", function (t, e) {
                o.parent.childUIPointerDown(e)
            })
        }, r.prototype.activate = function () {
            this.bindTap(this.element), e.bind(this.element, "click", this), this.parent.element.appendChild(this.element)
        }, r.prototype.deactivate = function () {
            this.parent.element.removeChild(this.element), n.prototype.destroy.call(this), e.unbind(this.element, "click", this)
        }, r.prototype.createSVG = function () {
            var t = document.createElementNS(a, "svg");
            t.setAttribute("viewBox", "0 0 100 100");
            var e = document.createElementNS(a, "path"), i = s(this.parent.options.arrowShape);
            return e.setAttribute("d", i), e.setAttribute("class", "arrow"), this.isLeft || e.setAttribute("transform", "translate(100, 100) rotate(180) "), t.appendChild(e), t
        }, r.prototype.setArrowText = function () {
            var t = this.parent.options, e = this.isLeft ? t.leftArrowText : t.rightArrowText;
            o.setText(this.element, e)
        }, r.prototype.onTap = function () {
            if (this.isEnabled) {
                this.parent.uiChange();
                var t = this.isPrevious ? "previous" : "next";
                this.parent[t]()
            }
        }, r.prototype.handleEvent = o.handleEvent, r.prototype.onclick = function () {
            var t = document.activeElement;
            t && t == this.element && this.onTap()
        }, r.prototype.enable = function () {
            this.isEnabled || (this.element.disabled = !1, this.isEnabled = !0)
        }, r.prototype.disable = function () {
            this.isEnabled && (this.element.disabled = !0, this.isEnabled = !1)
        }, r.prototype.update = function () {
            var t = this.parent.cells;
            if (this.parent.options.wrapAround && t.length > 1)return void this.enable();
            var e = t.length ? t.length - 1 : 0, i = this.isPrevious ? 0 : e, n = this.parent.selectedIndex == i ? "disable" : "enable";
            this[n]()
        }, r.prototype.destroy = function () {
            this.deactivate()
        }, o.extend(i.defaults, {
            prevNextButtons: !0,
            leftArrowText: "‹",
            rightArrowText: "›",
            arrowShape: {x0: 10, x1: 60, y1: 50, x2: 70, y2: 40, x3: 30}
        }), i.createMethods.push("_createPrevNextButtons"), i.prototype._createPrevNextButtons = function () {
            this.options.prevNextButtons && (this.prevButton = new r(-1, this), this.nextButton = new r(1, this), this.on("activate", this.activatePrevNextButtons))
        }, i.prototype.activatePrevNextButtons = function () {
            this.prevButton.activate(), this.nextButton.activate(), this.on("deactivate", this.deactivatePrevNextButtons)
        }, i.prototype.deactivatePrevNextButtons = function () {
            this.prevButton.deactivate(), this.nextButton.deactivate(), this.off("deactivate", this.deactivatePrevNextButtons)
        }, i.PrevNextButton = r, i
    }), function (t, e) {
        "use strict";
        "function" == typeof define && define.amd ? define("flickity/js/page-dots", ["eventie/eventie", "./flickity", "tap-listener/tap-listener", "fizzy-ui-utils/utils"], function (i, n, o, r) {
            return e(t, i, n, o, r)
        }) : "object" == typeof exports ? module.exports = e(t, require("eventie"), require("./flickity"), require("tap-listener"), require("fizzy-ui-utils")) : e(t, t.eventie, t.Flickity, t.TapListener, t.fizzyUIUtils)
    }(window, function (t, e, i, n, o) {
        function r(t) {
            this.parent = t, this._create()
        }

        return r.prototype = new n, r.prototype._create = function () {
            this.holder = document.createElement("ol"), this.holder.className = "flickity-page-dots", i.setUnselectable(this.holder), this.dots = [];
            var t = this;
            this.onCellSelect = function () {
                t.updateSelected()
            }, this.parent.on("cellSelect", this.onCellSelect), this.on("tap", this.onTap), this.on("pointerDown", function (e, i) {
                t.parent.childUIPointerDown(i)
            })
        }, r.prototype.activate = function () {
            this.setDots(), this.bindTap(this.holder), this.parent.element.appendChild(this.holder)
        }, r.prototype.deactivate = function () {
            this.parent.element.removeChild(this.holder), n.prototype.destroy.call(this)
        }, r.prototype.setDots = function () {
            var t = this.parent.cells.length - this.dots.length;
            t > 0 ? this.addDots(t) : 0 > t && this.removeDots(-t)
        }, r.prototype.addDots = function (t) {
            for (var e = document.createDocumentFragment(), i = []; t;) {
                var n = document.createElement("li");
                n.className = "dot", e.appendChild(n), i.push(n), t--
            }
            this.holder.appendChild(e), this.dots = this.dots.concat(i)
        }, r.prototype.removeDots = function (t) {
            for (var e = this.dots.splice(this.dots.length - t, t), i = 0, n = e.length; n > i; i++) {
                var o = e[i];
                this.holder.removeChild(o)
            }
        }, r.prototype.updateSelected = function () {
            this.selectedDot && (this.selectedDot.className = "dot"), this.dots.length && (this.selectedDot = this.dots[this.parent.selectedIndex], this.selectedDot.className = "dot is-selected")
        }, r.prototype.onTap = function (t) {
            var e = t.target;
            if ("LI" == e.nodeName) {
                this.parent.uiChange();
                var i = o.indexOf(this.dots, e);
                this.parent.select(i)
            }
        }, r.prototype.destroy = function () {
            this.deactivate()
        }, i.PageDots = r, o.extend(i.defaults, {pageDots: !0}), i.createMethods.push("_createPageDots"), i.prototype._createPageDots = function () {
            this.options.pageDots && (this.pageDots = new r(this), this.on("activate", this.activatePageDots), this.on("cellAddedRemoved", this.onCellAddedRemovedPageDots), this.on("deactivate", this.deactivatePageDots))
        }, i.prototype.activatePageDots = function () {
            this.pageDots.activate()
        }, i.prototype.onCellAddedRemovedPageDots = function () {
            this.pageDots.setDots()
        }, i.prototype.deactivatePageDots = function () {
            this.pageDots.deactivate()
        }, i.PageDots = r, i
    }), function (t, e) {
        "use strict";
        "function" == typeof define && define.amd ? define("flickity/js/player", ["eventEmitter/EventEmitter", "eventie/eventie", "fizzy-ui-utils/utils", "./flickity"], function (t, i, n, o) {
            return e(t, i, n, o)
        }) : "object" == typeof exports ? module.exports = e(require("wolfy87-eventemitter"), require("eventie"), require("fizzy-ui-utils"), require("./flickity")) : e(t.EventEmitter, t.eventie, t.fizzyUIUtils, t.Flickity)
    }(window, function (t, e, i, n) {
        function o(t) {
            if (this.parent = t, this.state = "stopped", s) {
                var e = this;
                this.onVisibilityChange = function () {
                    e.visibilityChange()
                }
            }
        }

        var r, s;
        return "hidden" in document ? (r = "hidden", s = "visibilitychange") : "webkitHidden" in document && (r = "webkitHidden", s = "webkitvisibilitychange"), o.prototype = new t, o.prototype.play = function () {
            "playing" != this.state && (this.state = "playing", s && document.addEventListener(s, this.onVisibilityChange, !1), this.tick())
        }, o.prototype.tick = function () {
            if ("playing" == this.state) {
                var t = this.parent.options.autoPlay;
                t = "number" == typeof t ? t : 3e3;
                var e = this;
                this.clear(), this.timeout = setTimeout(function () {
                    e.parent.next(!0), e.tick()
                }, t)
            }
        }, o.prototype.stop = function () {
            this.state = "stopped", this.clear(), s && document.removeEventListener(s, this.onVisibilityChange, !1)
        }, o.prototype.clear = function () {
            clearTimeout(this.timeout)
        }, o.prototype.pause = function () {
            "playing" == this.state && (this.state = "paused", this.clear())
        }, o.prototype.unpause = function () {
            "paused" == this.state && this.play()
        }, o.prototype.visibilityChange = function () {
            var t = document[r];
            this[t ? "pause" : "unpause"]()
        }, i.extend(n.defaults, {pauseAutoPlayOnHover: !0}), n.createMethods.push("_createPlayer"), n.prototype._createPlayer = function () {
            this.player = new o(this), this.on("activate", this.activatePlayer), this.on("uiChange", this.stopPlayer), this.on("pointerDown", this.stopPlayer), this.on("deactivate", this.deactivatePlayer)
        }, n.prototype.activatePlayer = function () {
            this.options.autoPlay && (this.player.play(), e.bind(this.element, "mouseenter", this), this.isMouseenterBound = !0)
        }, n.prototype.playPlayer = function () {
            this.player.play()
        }, n.prototype.stopPlayer = function () {
            this.player.stop()
        }, n.prototype.pausePlayer = function () {
            this.player.pause()
        }, n.prototype.unpausePlayer = function () {
            this.player.unpause()
        }, n.prototype.deactivatePlayer = function () {
            this.player.stop(), this.isMouseenterBound && (e.unbind(this.element, "mouseenter", this), delete this.isMouseenterBound)
        }, n.prototype.onmouseenter = function () {
            this.options.pauseAutoPlayOnHover && (this.player.pause(), e.bind(this.element, "mouseleave", this))
        }, n.prototype.onmouseleave = function () {
            this.player.unpause(), e.unbind(this.element, "mouseleave", this)
        }, n.Player = o, n
    }), function (t, e) {
        "use strict";
        "function" == typeof define && define.amd ? define("flickity/js/add-remove-cell", ["./flickity", "fizzy-ui-utils/utils"], function (i, n) {
            return e(t, i, n)
        }) : "object" == typeof exports ? module.exports = e(t, require("./flickity"), require("fizzy-ui-utils")) : e(t, t.Flickity, t.fizzyUIUtils)
    }(window, function (t, e, i) {
        function n(t) {
            for (var e = document.createDocumentFragment(), i = 0, n = t.length; n > i; i++) {
                var o = t[i];
                e.appendChild(o.element)
            }
            return e
        }

        return e.prototype.insert = function (t, e) {
            var i = this._makeCells(t);
            if (i && i.length) {
                var o = this.cells.length;
                e = void 0 === e ? o : e;
                var r = n(i), s = e == o;
                if (s)this.slider.appendChild(r); else {
                    var a = this.cells[e].element;
                    this.slider.insertBefore(r, a)
                }
                if (0 === e)this.cells = i.concat(this.cells); else if (s)this.cells = this.cells.concat(i); else {
                    var l = this.cells.splice(e, o - e);
                    this.cells = this.cells.concat(i).concat(l)
                }
                this._sizeCells(i);
                var h = e > this.selectedIndex ? 0 : i.length;
                this._cellAddedRemoved(e, h)
            }
        }, e.prototype.append = function (t) {
            this.insert(t, this.cells.length)
        }, e.prototype.prepend = function (t) {
            this.insert(t, 0)
        }, e.prototype.remove = function (t) {
            var e, n, o, r = this.getCells(t), s = 0;
            for (e = 0, n = r.length; n > e; e++) {
                o = r[e];
                var a = i.indexOf(this.cells, o) < this.selectedIndex;
                s -= a ? 1 : 0
            }
            for (e = 0, n = r.length; n > e; e++)o = r[e], o.remove(), i.removeFrom(this.cells, o);
            r.length && this._cellAddedRemoved(0, s)
        }, e.prototype._cellAddedRemoved = function (t, e) {
            e = e || 0, this.selectedIndex += e, this.selectedIndex = Math.max(0, Math.min(this.cells.length - 1, this.selectedIndex)), this.emitEvent("cellAddedRemoved", [t, e]), this.cellChange(t, !0)
        }, e.prototype.cellSizeChange = function (t) {
            var e = this.getCell(t);
            if (e) {
                e.getSize();
                var n = i.indexOf(this.cells, e);
                this.cellChange(n)
            }
        }, e.prototype.cellChange = function (t, e) {
            var i = this.slideableWidth;
            if (this._positionCells(t), this._getWrapShiftCells(), this.setGallerySize(), this.options.freeScroll) {
                var n = i - this.slideableWidth;
                this.x += n * this.cellAlign, this.positionSlider()
            } else e && this.positionSliderAtSelected(), this.select(this.selectedIndex)
        }, e
    }), function (t, e) {
        "use strict";
        "function" == typeof define && define.amd ? define("flickity/js/lazyload", ["classie/classie", "eventie/eventie", "./flickity", "fizzy-ui-utils/utils"], function (i, n, o, r) {
            return e(t, i, n, o, r)
        }) : "object" == typeof exports ? module.exports = e(t, require("desandro-classie"), require("eventie"), require("./flickity"), require("fizzy-ui-utils")) : e(t, t.classie, t.eventie, t.Flickity, t.fizzyUIUtils)
    }(window, function (t, e, i, n, o) {
        "use strict";
        function r(t) {
            if ("IMG" == t.nodeName && t.getAttribute("data-flickity-lazyload"))return [t];
            var e = t.querySelectorAll("img[data-flickity-lazyload]");
            return o.makeArray(e)
        }

        function s(t, e) {
            this.img = t, this.flickity = e, this.load()
        }

        return n.createMethods.push("_createLazyload"), n.prototype._createLazyload = function () {
            this.on("cellSelect", this.lazyLoad)
        }, n.prototype.lazyLoad = function () {
            var t = this.options.lazyLoad;
            if (t) {
                for (var e = "number" == typeof t ? t : 0, i = this.getAdjacentCellElements(e), n = [], o = 0, a = i.length; a > o; o++) {
                    var l = i[o], h = r(l);
                    n = n.concat(h)
                }
                for (o = 0, a = n.length; a > o; o++) {
                    var c = n[o];
                    new s(c, this)
                }
            }
        }, s.prototype.handleEvent = o.handleEvent, s.prototype.load = function () {
            i.bind(this.img, "load", this), i.bind(this.img, "error", this), this.img.src = this.img.getAttribute("data-flickity-lazyload"), this.img.removeAttribute("data-flickity-lazyload")
        }, s.prototype.onload = function (t) {
            this.complete(t, "flickity-lazyloaded")
        }, s.prototype.onerror = function (t) {
            this.complete(t, "flickity-lazyerror")
        }, s.prototype.complete = function (t, n) {
            i.unbind(this.img, "load", this), i.unbind(this.img, "error", this);
            var o = this.flickity.getParentCell(this.img), r = o && o.element;
            this.flickity.cellSizeChange(r), e.add(this.img, n), this.flickity.dispatchEvent("lazyLoad", t, r)
        }, n.LazyLoader = s, n
    }), function (t, e) {
        "use strict";
        "function" == typeof define && define.amd ? define("flickity/js/index", ["./flickity", "./drag", "./prev-next-button", "./page-dots", "./player", "./add-remove-cell", "./lazyload"], e) : "object" == typeof exports && (module.exports = e(require("./flickity"), require("./drag"), require("./prev-next-button"), require("./page-dots"), require("./player"), require("./add-remove-cell"), require("./lazyload")))
    }(window, function (t) {
        return t
    }), function (t, e) {
        "use strict";
        "function" == typeof define && define.amd ? define("flickity-as-nav-for/as-nav-for", ["classie/classie", "flickity/js/index", "fizzy-ui-utils/utils"], function (i, n, o) {
            return e(t, i, n, o)
        }) : "object" == typeof exports ? module.exports = e(t, require("desandro-classie"), require("flickity"), require("fizzy-ui-utils")) : t.Flickity = e(t, t.classie, t.Flickity, t.fizzyUIUtils)
    }(window, function (t, e, i, n) {
        return i.createMethods.push("_createAsNavFor"), i.prototype._createAsNavFor = function () {
            this.on("activate", this.activateAsNavFor), this.on("deactivate", this.deactivateAsNavFor), this.on("destroy", this.destroyAsNavFor);
            var t = this.options.asNavFor;
            if (t) {
                var e = this;
                setTimeout(function () {
                    e.setNavCompanion(t)
                })
            }
        }, i.prototype.setNavCompanion = function (t) {
            t = n.getQueryElement(t);
            var e = i.data(t);
            if (e && e != this) {
                this.navCompanion = e;
                var o = this;
                this.onNavCompanionSelect = function () {
                    o.navCompanionSelect()
                }, e.on("cellSelect", this.onNavCompanionSelect), this.on("staticClick", this.onNavStaticClick), this.navCompanionSelect()
            }
        }, i.prototype.navCompanionSelect = function () {
            if (this.navCompanion) {
                var t = this.navCompanion.selectedIndex;
                this.select(t), this.removeNavSelectedElement(), this.selectedIndex == t && (this.navSelectedElement = this.cells[t].element, e.add(this.navSelectedElement, "is-nav-selected"))
            }
        }, i.prototype.activateAsNavFor = function () {
            this.navCompanionSelect()
        }, i.prototype.removeNavSelectedElement = function () {
            this.navSelectedElement && (e.remove(this.navSelectedElement, "is-nav-selected"), delete this.navSelectedElement)
        }, i.prototype.onNavStaticClick = function (t, e, i, n) {
            "number" == typeof n && this.navCompanion.select(n)
        }, i.prototype.deactivateAsNavFor = function () {
            this.removeNavSelectedElement()
        }, i.prototype.destroyAsNavFor = function () {
            this.navCompanion && (this.navCompanion.off("cellSelect", this.onNavCompanionSelect), this.off("staticClick", this.onNavStaticClick), delete this.navCompanion)
        }, i
    }), function (t, e) {
        "use strict";
        "function" == typeof define && define.amd ? define("imagesloaded/imagesloaded", ["eventEmitter/EventEmitter", "eventie/eventie"], function (i, n) {
            return e(t, i, n)
        }) : "object" == typeof module && module.exports ? module.exports = e(t, require("wolfy87-eventemitter"), require("eventie")) : t.imagesLoaded = e(t, t.EventEmitter, t.eventie)
    }(window, function (t, e, i) {
        function n(t, e) {
            for (var i in e)t[i] = e[i];
            return t
        }

        function o(t) {
            return "[object Array]" == p.call(t)
        }

        function r(t) {
            var e = [];
            if (o(t))e = t; else if ("number" == typeof t.length)for (var i = 0; i < t.length; i++)e.push(t[i]); else e.push(t);
            return e
        }

        function s(t, e, i) {
            if (!(this instanceof s))return new s(t, e, i);
            "string" == typeof t && (t = document.querySelectorAll(t)), this.elements = r(t), this.options = n({}, this.options), "function" == typeof e ? i = e : n(this.options, e), i && this.on("always", i), this.getImages(), h && (this.jqDeferred = new h.Deferred);
            var o = this;
            setTimeout(function () {
                o.check()
            })
        }

        function a(t) {
            this.img = t
        }

        function l(t, e) {
            this.url = t, this.element = e, this.img = new Image
        }

        var h = t.jQuery, c = t.console, p = Object.prototype.toString;
        s.prototype = new e, s.prototype.options = {}, s.prototype.getImages = function () {
            this.images = [];
            for (var t = 0; t < this.elements.length; t++) {
                var e = this.elements[t];
                this.addElementImages(e)
            }
        }, s.prototype.addElementImages = function (t) {
            "IMG" == t.nodeName && this.addImage(t), this.options.background === !0 && this.addElementBackgroundImages(t);
            var e = t.nodeType;
            if (e && d[e]) {
                for (var i = t.querySelectorAll("img"), n = 0; n < i.length; n++) {
                    var o = i[n];
                    this.addImage(o)
                }
                if ("string" == typeof this.options.background) {
                    var r = t.querySelectorAll(this.options.background);
                    for (n = 0; n < r.length; n++) {
                        var s = r[n];
                        this.addElementBackgroundImages(s)
                    }
                }
            }
        };
        var d = {1: !0, 9: !0, 11: !0};
        s.prototype.addElementBackgroundImages = function (t) {
            for (var e = u(t), i = /url\(['"]*([^'"\)]+)['"]*\)/gi, n = i.exec(e.backgroundImage); null !== n;) {
                var o = n && n[1];
                o && this.addBackground(o, t), n = i.exec(e.backgroundImage)
            }
        };
        var u = t.getComputedStyle || function (t) {
                return t.currentStyle
            };
        return s.prototype.addImage = function (t) {
            var e = new a(t);
            this.images.push(e)
        }, s.prototype.addBackground = function (t, e) {
            var i = new l(t, e);
            this.images.push(i)
        }, s.prototype.check = function () {
            function t(t, i, n) {
                setTimeout(function () {
                    e.progress(t, i, n)
                })
            }

            var e = this;
            if (this.progressedCount = 0, this.hasAnyBroken = !1, !this.images.length)return void this.complete();
            for (var i = 0; i < this.images.length; i++) {
                var n = this.images[i];
                n.once("progress", t), n.check()
            }
        }, s.prototype.progress = function (t, e, i) {
            this.progressedCount++, this.hasAnyBroken = this.hasAnyBroken || !t.isLoaded, this.emit("progress", this, t, e), this.jqDeferred && this.jqDeferred.notify && this.jqDeferred.notify(this, t), this.progressedCount == this.images.length && this.complete(), this.options.debug && c && c.log("progress: " + i, t, e)
        }, s.prototype.complete = function () {
            var t = this.hasAnyBroken ? "fail" : "done";
            if (this.isComplete = !0, this.emit(t, this), this.emit("always", this), this.jqDeferred) {
                var e = this.hasAnyBroken ? "reject" : "resolve";
                this.jqDeferred[e](this)
            }
        }, a.prototype = new e, a.prototype.check = function () {
            var t = this.getIsImageComplete();
            return t ? void this.confirm(0 !== this.img.naturalWidth, "naturalWidth") : (this.proxyImage = new Image, i.bind(this.proxyImage, "load", this), i.bind(this.proxyImage, "error", this), i.bind(this.img, "load", this), i.bind(this.img, "error", this), void(this.proxyImage.src = this.img.src))
        }, a.prototype.getIsImageComplete = function () {
            return this.img.complete && void 0 !== this.img.naturalWidth
        }, a.prototype.confirm = function (t, e) {
            this.isLoaded = t, this.emit("progress", this, this.img, e)
        }, a.prototype.handleEvent = function (t) {
            var e = "on" + t.type;
            this[e] && this[e](t)
        }, a.prototype.onload = function () {
            this.confirm(!0, "onload"), this.unbindEvents()
        }, a.prototype.onerror = function () {
            this.confirm(!1, "onerror"), this.unbindEvents()
        }, a.prototype.unbindEvents = function () {
            i.unbind(this.proxyImage, "load", this), i.unbind(this.proxyImage, "error", this), i.unbind(this.img, "load", this), i.unbind(this.img, "error", this)
        }, l.prototype = new a, l.prototype.check = function () {
            i.bind(this.img, "load", this), i.bind(this.img, "error", this), this.img.src = this.url;
            var t = this.getIsImageComplete();
            t && (this.confirm(0 !== this.img.naturalWidth, "naturalWidth"), this.unbindEvents())
        }, l.prototype.unbindEvents = function () {
            i.unbind(this.img, "load", this), i.unbind(this.img, "error", this)
        }, l.prototype.confirm = function (t, e) {
            this.isLoaded = t, this.emit("progress", this, this.element, e)
        }, s.makeJQueryPlugin = function (e) {
            e = e || t.jQuery, e && (h = e, h.fn.imagesLoaded = function (t, e) {
                var i = new s(this, t, e);
                return i.jqDeferred.promise(h(this))
            })
        }, s.makeJQueryPlugin(), s
    }), function (t, e) {
        "use strict";
        "function" == typeof define && define.amd ? define(["flickity/js/index", "imagesloaded/imagesloaded"], function (i, n) {
            return e(t, i, n)
        }) : "object" == typeof exports ? module.exports = e(t, require("flickity"), require("imagesloaded")) : t.Flickity = e(t, t.Flickity, t.imagesLoaded)
    }(window, function (t, e, i) {
        "use strict";
        return e.createMethods.push("_createImagesLoaded"), e.prototype._createImagesLoaded = function () {
            this.on("activate", this.imagesLoaded)
        }, e.prototype.imagesLoaded = function () {
            function t(t, i) {
                var n = e.getParentCell(i.img);
                e.cellSizeChange(n && n.element), e.options.freeScroll || e.positionSliderAtSelected()
            }

            if (this.options.imagesLoaded) {
                var e = this;
                i(this.slider).on("progress", t)
            }
        }, e
    });


    /*! Magnific Popup - v1.1.0 - 2016-02-20
     * http://dimsemenov.com/plugins/magnific-popup/
     * Copyright (c) 2016 Dmitry Semenov; */
    !function (a) {
        "function" == typeof define && define.amd ? define(["jquery"], a) : a("object" == typeof exports ? require("jquery") : window.jQuery || window.Zepto)
    }(function (a) {
        var b, c, d, e, f, g, h = "Close", i = "BeforeClose", j = "AfterClose", k = "BeforeAppend", l = "MarkupParse", m = "Open", n = "Change", o = "mfp", p = "." + o, q = "mfp-ready", r = "mfp-removing", s = "mfp-prevent-close", t = function () {
        }, u = !!window.jQuery, v = a(window), w = function (a, c) {
            b.ev.on(o + a + p, c)
        }, x = function (b, c, d, e) {
            var f = document.createElement("div");
            return f.className = "mfp-" + b, d && (f.innerHTML = d), e ? c && c.appendChild(f) : (f = a(f), c && f.appendTo(c)), f
        }, y = function (c, d) {
            b.ev.triggerHandler(o + c, d), b.st.callbacks && (c = c.charAt(0).toLowerCase() + c.slice(1), b.st.callbacks[c] && b.st.callbacks[c].apply(b, a.isArray(d) ? d : [d]))
        }, z = function (c) {
            return c === g && b.currTemplate.closeBtn || (b.currTemplate.closeBtn = a(b.st.closeMarkup.replace("%title%", b.st.tClose)), g = c), b.currTemplate.closeBtn
        }, A = function () {
            a.magnificPopup.instance || (b = new t, b.init(), a.magnificPopup.instance = b)
        }, B = function () {
            var a = document.createElement("p").style, b = ["ms", "O", "Moz", "Webkit"];
            if (void 0 !== a.transition)return !0;
            for (; b.length;)if (b.pop() + "Transition" in a)return !0;
            return !1
        };
        t.prototype = {
            constructor: t, init: function () {
                var c = navigator.appVersion;
                b.isLowIE = b.isIE8 = document.all && !document.addEventListener, b.isAndroid = /android/gi.test(c), b.isIOS = /iphone|ipad|ipod/gi.test(c), b.supportsTransition = B(), b.probablyMobile = b.isAndroid || b.isIOS || /(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent), d = a(document), b.popupsCache = {}
            }, open: function (c) {
                var e;
                if (c.isObj === !1) {
                    b.items = c.items.toArray(), b.index = 0;
                    var g, h = c.items;
                    for (e = 0; e < h.length; e++)if (g = h[e], g.parsed && (g = g.el[0]), g === c.el[0]) {
                        b.index = e;
                        break
                    }
                } else b.items = a.isArray(c.items) ? c.items : [c.items], b.index = c.index || 0;
                if (b.isOpen)return void b.updateItemHTML();
                b.types = [], f = "", c.mainEl && c.mainEl.length ? b.ev = c.mainEl.eq(0) : b.ev = d, c.key ? (b.popupsCache[c.key] || (b.popupsCache[c.key] = {}), b.currTemplate = b.popupsCache[c.key]) : b.currTemplate = {}, b.st = a.extend(!0, {}, a.magnificPopup.defaults, c), b.fixedContentPos = "auto" === b.st.fixedContentPos ? !b.probablyMobile : b.st.fixedContentPos, b.st.modal && (b.st.closeOnContentClick = !1, b.st.closeOnBgClick = !1, b.st.showCloseBtn = !1, b.st.enableEscapeKey = !1), b.bgOverlay || (b.bgOverlay = x("bg").on("click" + p, function () {
                    b.close()
                }), b.wrap = x("wrap").attr("tabindex", -1).on("click" + p, function (a) {
                    b._checkIfClose(a.target) && b.close()
                }), b.container = x("container", b.wrap)), b.contentContainer = x("content"), b.st.preloader && (b.preloader = x("preloader", b.container, b.st.tLoading));
                var i = a.magnificPopup.modules;
                for (e = 0; e < i.length; e++) {
                    var j = i[e];
                    j = j.charAt(0).toUpperCase() + j.slice(1), b["init" + j].call(b)
                }
                y("BeforeOpen"), b.st.showCloseBtn && (b.st.closeBtnInside ? (w(l, function (a, b, c, d) {
                    c.close_replaceWith = z(d.type)
                }), f += " mfp-close-btn-in") : b.wrap.append(z())), b.st.alignTop && (f += " mfp-align-top"), b.fixedContentPos ? b.wrap.css({
                    overflow: b.st.overflowY,
                    overflowX: "hidden",
                    overflowY: b.st.overflowY
                }) : b.wrap.css({
                    top: v.scrollTop(),
                    position: "absolute"
                }), (b.st.fixedBgPos === !1 || "auto" === b.st.fixedBgPos && !b.fixedContentPos) && b.bgOverlay.css({
                    height: d.height(),
                    position: "absolute"
                }), b.st.enableEscapeKey && d.on("keyup" + p, function (a) {
                    27 === a.keyCode && b.close()
                }), v.on("resize" + p, function () {
                    b.updateSize()
                }), b.st.closeOnContentClick || (f += " mfp-auto-cursor"), f && b.wrap.addClass(f);
                var k = b.wH = v.height(), n = {};
                if (b.fixedContentPos && b._hasScrollBar(k)) {
                    var o = b._getScrollbarSize();
                    o && (n.marginRight = o)
                }
                b.fixedContentPos && (b.isIE7 ? a("body, html").css("overflow", "hidden") : n.overflow = "hidden");
                var r = b.st.mainClass;
                return b.isIE7 && (r += " mfp-ie7"), r && b._addClassToMFP(r), b.updateItemHTML(), y("BuildControls"), a("html").css(n), b.bgOverlay.add(b.wrap).prependTo(b.st.prependTo || a(document.body)), b._lastFocusedEl = document.activeElement, setTimeout(function () {
                    b.content ? (b._addClassToMFP(q), b._setFocus()) : b.bgOverlay.addClass(q), d.on("focusin" + p, b._onFocusIn)
                }, 16), b.isOpen = !0, b.updateSize(k), y(m), c
            }, close: function () {
                b.isOpen && (y(i), b.isOpen = !1, b.st.removalDelay && !b.isLowIE && b.supportsTransition ? (b._addClassToMFP(r), setTimeout(function () {
                    b._close()
                }, b.st.removalDelay)) : b._close())
            }, _close: function () {
                y(h);
                var c = r + " " + q + " ";
                if (b.bgOverlay.detach(), b.wrap.detach(), b.container.empty(), b.st.mainClass && (c += b.st.mainClass + " "), b._removeClassFromMFP(c), b.fixedContentPos) {
                    var e = {marginRight: ""};
                    b.isIE7 ? a("body, html").css("overflow", "") : e.overflow = "", a("html").css(e)
                }
                d.off("keyup" + p + " focusin" + p), b.ev.off(p), b.wrap.attr("class", "mfp-wrap").removeAttr("style"), b.bgOverlay.attr("class", "mfp-bg"), b.container.attr("class", "mfp-container"), !b.st.showCloseBtn || b.st.closeBtnInside && b.currTemplate[b.currItem.type] !== !0 || b.currTemplate.closeBtn && b.currTemplate.closeBtn.detach(), b.st.autoFocusLast && b._lastFocusedEl && a(b._lastFocusedEl).focus(), b.currItem = null, b.content = null, b.currTemplate = null, b.prevHeight = 0, y(j)
            }, updateSize: function (a) {
                if (b.isIOS) {
                    var c = document.documentElement.clientWidth / window.innerWidth, d = window.innerHeight * c;
                    b.wrap.css("height", d), b.wH = d
                } else b.wH = a || v.height();
                b.fixedContentPos || b.wrap.css("height", b.wH), y("Resize")
            }, updateItemHTML: function () {
                var c = b.items[b.index];
                b.contentContainer.detach(), b.content && b.content.detach(), c.parsed || (c = b.parseEl(b.index));
                var d = c.type;
                if (y("BeforeChange", [b.currItem ? b.currItem.type : "", d]), b.currItem = c, !b.currTemplate[d]) {
                    var f = b.st[d] ? b.st[d].markup : !1;
                    y("FirstMarkupParse", f), f ? b.currTemplate[d] = a(f) : b.currTemplate[d] = !0
                }
                e && e !== c.type && b.container.removeClass("mfp-" + e + "-holder");
                var g = b["get" + d.charAt(0).toUpperCase() + d.slice(1)](c, b.currTemplate[d]);
                b.appendContent(g, d), c.preloaded = !0, y(n, c), e = c.type, b.container.prepend(b.contentContainer), y("AfterChange")
            }, appendContent: function (a, c) {
                b.content = a, a ? b.st.showCloseBtn && b.st.closeBtnInside && b.currTemplate[c] === !0 ? b.content.find(".mfp-close").length || b.content.append(z()) : b.content = a : b.content = "", y(k), b.container.addClass("mfp-" + c + "-holder"), b.contentContainer.append(b.content)
            }, parseEl: function (c) {
                var d, e = b.items[c];
                if (e.tagName ? e = {el: a(e)} : (d = e.type, e = {data: e, src: e.src}), e.el) {
                    for (var f = b.types, g = 0; g < f.length; g++)if (e.el.hasClass("mfp-" + f[g])) {
                        d = f[g];
                        break
                    }
                    e.src = e.el.attr("data-mfp-src"), e.src || (e.src = e.el.attr("href"))
                }
                return e.type = d || b.st.type || "inline", e.index = c, e.parsed = !0, b.items[c] = e, y("ElementParse", e), b.items[c]
            }, addGroup: function (a, c) {
                var d = function (d) {
                    d.mfpEl = this, b._openClick(d, a, c)
                };
                c || (c = {});
                var e = "click.magnificPopup";
                c.mainEl = a, c.items ? (c.isObj = !0, a.off(e).on(e, d)) : (c.isObj = !1, c.delegate ? a.off(e).on(e, c.delegate, d) : (c.items = a, a.off(e).on(e, d)))
            }, _openClick: function (c, d, e) {
                var f = void 0 !== e.midClick ? e.midClick : a.magnificPopup.defaults.midClick;
                if (f || !(2 === c.which || c.ctrlKey || c.metaKey || c.altKey || c.shiftKey)) {
                    var g = void 0 !== e.disableOn ? e.disableOn : a.magnificPopup.defaults.disableOn;
                    if (g)if (a.isFunction(g)) {
                        if (!g.call(b))return !0
                    } else if (v.width() < g)return !0;
                    c.type && (c.preventDefault(), b.isOpen && c.stopPropagation()), e.el = a(c.mfpEl), e.delegate && (e.items = d.find(e.delegate)), b.open(e)
                }
            }, updateStatus: function (a, d) {
                if (b.preloader) {
                    c !== a && b.container.removeClass("mfp-s-" + c), d || "loading" !== a || (d = b.st.tLoading);
                    var e = {status: a, text: d};
                    y("UpdateStatus", e), a = e.status, d = e.text, b.preloader.html(d), b.preloader.find("a").on("click", function (a) {
                        a.stopImmediatePropagation()
                    }), b.container.addClass("mfp-s-" + a), c = a
                }
            }, _checkIfClose: function (c) {
                if (!a(c).hasClass(s)) {
                    var d = b.st.closeOnContentClick, e = b.st.closeOnBgClick;
                    if (d && e)return !0;
                    if (!b.content || a(c).hasClass("mfp-close") || b.preloader && c === b.preloader[0])return !0;
                    if (c === b.content[0] || a.contains(b.content[0], c)) {
                        if (d)return !0
                    } else if (e && a.contains(document, c))return !0;
                    return !1
                }
            }, _addClassToMFP: function (a) {
                b.bgOverlay.addClass(a), b.wrap.addClass(a)
            }, _removeClassFromMFP: function (a) {
                this.bgOverlay.removeClass(a), b.wrap.removeClass(a)
            }, _hasScrollBar: function (a) {
                return (b.isIE7 ? d.height() : document.body.scrollHeight) > (a || v.height())
            }, _setFocus: function () {
                (b.st.focus ? b.content.find(b.st.focus).eq(0) : b.wrap).focus()
            }, _onFocusIn: function (c) {
                return c.target === b.wrap[0] || a.contains(b.wrap[0], c.target) ? void 0 : (b._setFocus(), !1)
            }, _parseMarkup: function (b, c, d) {
                var e;
                d.data && (c = a.extend(d.data, c)), y(l, [b, c, d]), a.each(c, function (c, d) {
                    if (void 0 === d || d === !1)return !0;
                    if (e = c.split("_"), e.length > 1) {
                        var f = b.find(p + "-" + e[0]);
                        if (f.length > 0) {
                            var g = e[1];
                            "replaceWith" === g ? f[0] !== d[0] && f.replaceWith(d) : "img" === g ? f.is("img") ? f.attr("src", d) : f.replaceWith(a("<img>").attr("src", d).attr("class", f.attr("class"))) : f.attr(e[1], d)
                        }
                    } else b.find(p + "-" + c).html(d)
                })
            }, _getScrollbarSize: function () {
                if (void 0 === b.scrollbarSize) {
                    var a = document.createElement("div");
                    a.style.cssText = "width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;", document.body.appendChild(a), b.scrollbarSize = a.offsetWidth - a.clientWidth, document.body.removeChild(a)
                }
                return b.scrollbarSize
            }
        }, a.magnificPopup = {
            instance: null,
            proto: t.prototype,
            modules: [],
            open: function (b, c) {
                return A(), b = b ? a.extend(!0, {}, b) : {}, b.isObj = !0, b.index = c || 0, this.instance.open(b)
            },
            close: function () {
                return a.magnificPopup.instance && a.magnificPopup.instance.close()
            },
            registerModule: function (b, c) {
                c.options && (a.magnificPopup.defaults[b] = c.options), a.extend(this.proto, c.proto), this.modules.push(b)
            },
            defaults: {
                disableOn: 0,
                key: null,
                midClick: !1,
                mainClass: "",
                preloader: !0,
                focus: "",
                closeOnContentClick: !1,
                closeOnBgClick: !0,
                closeBtnInside: !0,
                showCloseBtn: !0,
                enableEscapeKey: !0,
                modal: !1,
                alignTop: !1,
                removalDelay: 0,
                prependTo: null,
                fixedContentPos: "auto",
                fixedBgPos: "auto",
                overflowY: "auto",
                closeMarkup: '<button title="%title%" type="button" class="mfp-close">&#215;</button>',
                tClose: "Close (Esc)",
                tLoading: "Loading...",
                autoFocusLast: !0
            }
        }, a.fn.magnificPopup = function (c) {
            A();
            var d = a(this);
            if ("string" == typeof c)if ("open" === c) {
                var e, f = u ? d.data("magnificPopup") : d[0].magnificPopup, g = parseInt(arguments[1], 10) || 0;
                f.items ? e = f.items[g] : (e = d, f.delegate && (e = e.find(f.delegate)), e = e.eq(g)), b._openClick({mfpEl: e}, d, f)
            } else b.isOpen && b[c].apply(b, Array.prototype.slice.call(arguments, 1)); else c = a.extend(!0, {}, c), u ? d.data("magnificPopup", c) : d[0].magnificPopup = c, b.addGroup(d, c);
            return d
        };
        var C, D, E, F = "inline", G = function () {
            E && (D.after(E.addClass(C)).detach(), E = null)
        };
        a.magnificPopup.registerModule(F, {
            options: {hiddenClass: "hide", markup: "", tNotFound: "Content not found"},
            proto: {
                initInline: function () {
                    b.types.push(F), w(h + "." + F, function () {
                        G()
                    })
                }, getInline: function (c, d) {
                    if (G(), c.src) {
                        var e = b.st.inline, f = a(c.src);
                        if (f.length) {
                            var g = f[0].parentNode;
                            g && g.tagName && (D || (C = e.hiddenClass, D = x(C), C = "mfp-" + C), E = f.after(D).detach().removeClass(C)), b.updateStatus("ready")
                        } else b.updateStatus("error", e.tNotFound), f = a("<div>");
                        return c.inlineElement = f, f
                    }
                    return b.updateStatus("ready"), b._parseMarkup(d, {}, c), d
                }
            }
        });
        var H, I = "ajax", J = function () {
            H && a(document.body).removeClass(H)
        }, K = function () {
            J(), b.req && b.req.abort()
        };
        a.magnificPopup.registerModule(I, {
            options: {
                settings: null,
                cursor: "mfp-ajax-cur",
                tError: '<a href="%url%">The content</a> could not be loaded.'
            }, proto: {
                initAjax: function () {
                    b.types.push(I), H = b.st.ajax.cursor, w(h + "." + I, K), w("BeforeChange." + I, K)
                }, getAjax: function (c) {
                    H && a(document.body).addClass(H), b.updateStatus("loading");
                    var d = a.extend({
                        url: c.src, success: function (d, e, f) {
                            var g = {data: d, xhr: f};
                            y("ParseAjax", g), b.appendContent(a(g.data), I), c.finished = !0, J(), b._setFocus(), setTimeout(function () {
                                b.wrap.addClass(q)
                            }, 16), b.updateStatus("ready"), y("AjaxContentAdded")
                        }, error: function () {
                            J(), c.finished = c.loadError = !0, b.updateStatus("error", b.st.ajax.tError.replace("%url%", c.src))
                        }
                    }, b.st.ajax.settings);
                    return b.req = a.ajax(d), ""
                }
            }
        });
        var L, M = function (c) {
            if (c.data && void 0 !== c.data.title)return c.data.title;
            var d = b.st.image.titleSrc;
            if (d) {
                if (a.isFunction(d))return d.call(b, c);
                if (c.el)return c.el.attr(d) || ""
            }
            return ""
        };
        a.magnificPopup.registerModule("image", {
            options: {
                markup: '<div class="mfp-figure"><div class="mfp-close"></div><figure><div class="mfp-img"></div><figcaption><div class="mfp-bottom-bar"><div class="mfp-title"></div><div class="mfp-counter"></div></div></figcaption></figure></div>',
                cursor: "mfp-zoom-out-cur",
                titleSrc: "title",
                verticalFit: !0,
                tError: '<a href="%url%">The image</a> could not be loaded.'
            }, proto: {
                initImage: function () {
                    var c = b.st.image, d = ".image";
                    b.types.push("image"), w(m + d, function () {
                        "image" === b.currItem.type && c.cursor && a(document.body).addClass(c.cursor)
                    }), w(h + d, function () {
                        c.cursor && a(document.body).removeClass(c.cursor), v.off("resize" + p)
                    }), w("Resize" + d, b.resizeImage), b.isLowIE && w("AfterChange", b.resizeImage)
                }, resizeImage: function () {
                    var a = b.currItem;
                    if (a && a.img && b.st.image.verticalFit) {
                        var c = 0;
                        b.isLowIE && (c = parseInt(a.img.css("padding-top"), 10) + parseInt(a.img.css("padding-bottom"), 10)), a.img.css("max-height", b.wH - c)
                    }
                }, _onImageHasSize: function (a) {
                    a.img && (a.hasSize = !0, L && clearInterval(L), a.isCheckingImgSize = !1, y("ImageHasSize", a), a.imgHidden && (b.content && b.content.removeClass("mfp-loading"), a.imgHidden = !1))
                }, findImageSize: function (a) {
                    var c = 0, d = a.img[0], e = function (f) {
                        L && clearInterval(L), L = setInterval(function () {
                            return d.naturalWidth > 0 ? void b._onImageHasSize(a) : (c > 200 && clearInterval(L), c++, void(3 === c ? e(10) : 40 === c ? e(50) : 100 === c && e(500)))
                        }, f)
                    };
                    e(1)
                }, getImage: function (c, d) {
                    var e = 0, f = function () {
                        c && (c.img[0].complete ? (c.img.off(".mfploader"), c === b.currItem && (b._onImageHasSize(c), b.updateStatus("ready")), c.hasSize = !0, c.loaded = !0, y("ImageLoadComplete")) : (e++, 200 > e ? setTimeout(f, 100) : g()))
                    }, g = function () {
                        c && (c.img.off(".mfploader"), c === b.currItem && (b._onImageHasSize(c), b.updateStatus("error", h.tError.replace("%url%", c.src))), c.hasSize = !0, c.loaded = !0, c.loadError = !0)
                    }, h = b.st.image, i = d.find(".mfp-img");
                    if (i.length) {
                        var j = document.createElement("img");
                        j.className = "mfp-img", c.el && c.el.find("img").length && (j.alt = c.el.find("img").attr("alt")), c.img = a(j).on("load.mfploader", f).on("error.mfploader", g), j.src = c.src, i.is("img") && (c.img = c.img.clone()), j = c.img[0], j.naturalWidth > 0 ? c.hasSize = !0 : j.width || (c.hasSize = !1)
                    }
                    return b._parseMarkup(d, {
                        title: M(c),
                        img_replaceWith: c.img
                    }, c), b.resizeImage(), c.hasSize ? (L && clearInterval(L), c.loadError ? (d.addClass("mfp-loading"), b.updateStatus("error", h.tError.replace("%url%", c.src))) : (d.removeClass("mfp-loading"), b.updateStatus("ready")), d) : (b.updateStatus("loading"), c.loading = !0, c.hasSize || (c.imgHidden = !0, d.addClass("mfp-loading"), b.findImageSize(c)), d)
                }
            }
        });
        var N, O = function () {
            return void 0 === N && (N = void 0 !== document.createElement("p").style.MozTransform), N
        };
        a.magnificPopup.registerModule("zoom", {
            options: {
                enabled: !1,
                easing: "ease-in-out",
                duration: 300,
                opener: function (a) {
                    return a.is("img") ? a : a.find("img")
                }
            }, proto: {
                initZoom: function () {
                    var a, c = b.st.zoom, d = ".zoom";
                    if (c.enabled && b.supportsTransition) {
                        var e, f, g = c.duration, j = function (a) {
                            var b = a.clone().removeAttr("style").removeAttr("class").addClass("mfp-animated-image"), d = "all " + c.duration / 1e3 + "s " + c.easing, e = {
                                position: "fixed",
                                zIndex: 9999,
                                left: 0,
                                top: 0,
                                "-webkit-backface-visibility": "hidden"
                            }, f = "transition";
                            return e["-webkit-" + f] = e["-moz-" + f] = e["-o-" + f] = e[f] = d, b.css(e), b
                        }, k = function () {
                            b.content.css("visibility", "visible")
                        };
                        w("BuildControls" + d, function () {
                            if (b._allowZoom()) {
                                if (clearTimeout(e), b.content.css("visibility", "hidden"), a = b._getItemToZoom(), !a)return void k();
                                f = j(a), f.css(b._getOffset()), b.wrap.append(f), e = setTimeout(function () {
                                    f.css(b._getOffset(!0)), e = setTimeout(function () {
                                        k(), setTimeout(function () {
                                            f.remove(), a = f = null, y("ZoomAnimationEnded")
                                        }, 16)
                                    }, g)
                                }, 16)
                            }
                        }), w(i + d, function () {
                            if (b._allowZoom()) {
                                if (clearTimeout(e), b.st.removalDelay = g, !a) {
                                    if (a = b._getItemToZoom(), !a)return;
                                    f = j(a)
                                }
                                f.css(b._getOffset(!0)), b.wrap.append(f), b.content.css("visibility", "hidden"), setTimeout(function () {
                                    f.css(b._getOffset())
                                }, 16)
                            }
                        }), w(h + d, function () {
                            b._allowZoom() && (k(), f && f.remove(), a = null)
                        })
                    }
                }, _allowZoom: function () {
                    return "image" === b.currItem.type
                }, _getItemToZoom: function () {
                    return b.currItem.hasSize ? b.currItem.img : !1
                }, _getOffset: function (c) {
                    var d;
                    d = c ? b.currItem.img : b.st.zoom.opener(b.currItem.el || b.currItem);
                    var e = d.offset(), f = parseInt(d.css("padding-top"), 10), g = parseInt(d.css("padding-bottom"), 10);
                    e.top -= a(window).scrollTop() - f;
                    var h = {width: d.width(), height: (u ? d.innerHeight() : d[0].offsetHeight) - g - f};
                    return O() ? h["-moz-transform"] = h.transform = "translate(" + e.left + "px," + e.top + "px)" : (h.left = e.left, h.top = e.top), h
                }
            }
        });
        var P = "iframe", Q = "//about:blank", R = function (a) {
            if (b.currTemplate[P]) {
                var c = b.currTemplate[P].find("iframe");
                c.length && (a || (c[0].src = Q), b.isIE8 && c.css("display", a ? "block" : "none"))
            }
        };
        a.magnificPopup.registerModule(P, {
            options: {
                markup: '<div class="mfp-iframe-scaler"><div class="mfp-close"></div><iframe class="mfp-iframe" src="//about:blank" frameborder="0" allowfullscreen></iframe></div>',
                srcAction: "iframe_src",
                patterns: {
                    youtube: {index: "youtube.com", id: "v=", src: "//www.youtube.com/embed/%id%?autoplay=1"},
                    vimeo: {index: "vimeo.com/", id: "/", src: "//player.vimeo.com/video/%id%?autoplay=1"},
                    gmaps: {index: "//maps.google.", src: "%id%&output=embed"}
                }
            }, proto: {
                initIframe: function () {
                    b.types.push(P), w("BeforeChange", function (a, b, c) {
                        b !== c && (b === P ? R() : c === P && R(!0))
                    }), w(h + "." + P, function () {
                        R()
                    })
                }, getIframe: function (c, d) {
                    var e = c.src, f = b.st.iframe;
                    a.each(f.patterns, function () {
                        return e.indexOf(this.index) > -1 ? (this.id && (e = "string" == typeof this.id ? e.substr(e.lastIndexOf(this.id) + this.id.length, e.length) : this.id.call(this, e)), e = this.src.replace("%id%", e), !1) : void 0
                    });
                    var g = {};
                    return f.srcAction && (g[f.srcAction] = e), b._parseMarkup(d, g, c), b.updateStatus("ready"), d
                }
            }
        });
        var S = function (a) {
            var c = b.items.length;
            return a > c - 1 ? a - c : 0 > a ? c + a : a
        }, T = function (a, b, c) {
            return a.replace(/%curr%/gi, b + 1).replace(/%total%/gi, c)
        };
        a.magnificPopup.registerModule("gallery", {
            options: {
                enabled: !1,
                arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
                preload: [0, 2],
                navigateByImgClick: !0,
                arrows: !0,
                tPrev: "Previous (Left arrow key)",
                tNext: "Next (Right arrow key)",
                tCounter: "%curr% of %total%"
            }, proto: {
                initGallery: function () {
                    var c = b.st.gallery, e = ".mfp-gallery";
                    return b.direction = !0, c && c.enabled ? (f += " mfp-gallery", w(m + e, function () {
                        c.navigateByImgClick && b.wrap.on("click" + e, ".mfp-img", function () {
                            return b.items.length > 1 ? (b.next(), !1) : void 0
                        }), d.on("keydown" + e, function (a) {
                            37 === a.keyCode ? b.prev() : 39 === a.keyCode && b.next()
                        })
                    }), w("UpdateStatus" + e, function (a, c) {
                        c.text && (c.text = T(c.text, b.currItem.index, b.items.length))
                    }), w(l + e, function (a, d, e, f) {
                        var g = b.items.length;
                        e.counter = g > 1 ? T(c.tCounter, f.index, g) : ""
                    }), w("BuildControls" + e, function () {
                        if (b.items.length > 1 && c.arrows && !b.arrowLeft) {
                            var d = c.arrowMarkup, e = b.arrowLeft = a(d.replace(/%title%/gi, c.tPrev).replace(/%dir%/gi, "left")).addClass(s), f = b.arrowRight = a(d.replace(/%title%/gi, c.tNext).replace(/%dir%/gi, "right")).addClass(s);
                            e.click(function () {
                                b.prev()
                            }), f.click(function () {
                                b.next()
                            }), b.container.append(e.add(f))
                        }
                    }), w(n + e, function () {
                        b._preloadTimeout && clearTimeout(b._preloadTimeout), b._preloadTimeout = setTimeout(function () {
                            b.preloadNearbyImages(), b._preloadTimeout = null
                        }, 16)
                    }), void w(h + e, function () {
                        d.off(e), b.wrap.off("click" + e), b.arrowRight = b.arrowLeft = null
                    })) : !1
                }, next: function () {
                    b.direction = !0, b.index = S(b.index + 1), b.updateItemHTML()
                }, prev: function () {
                    b.direction = !1, b.index = S(b.index - 1), b.updateItemHTML()
                }, goTo: function (a) {
                    b.direction = a >= b.index, b.index = a, b.updateItemHTML()
                }, preloadNearbyImages: function () {
                    var a, c = b.st.gallery.preload, d = Math.min(c[0], b.items.length), e = Math.min(c[1], b.items.length);
                    for (a = 1; a <= (b.direction ? e : d); a++)b._preloadItem(b.index + a);
                    for (a = 1; a <= (b.direction ? d : e); a++)b._preloadItem(b.index - a)
                }, _preloadItem: function (c) {
                    if (c = S(c), !b.items[c].preloaded) {
                        var d = b.items[c];
                        d.parsed || (d = b.parseEl(c)), y("LazyLoad", d), "image" === d.type && (d.img = a('<img class="mfp-img" />').on("load.mfploader", function () {
                            d.hasSize = !0
                        }).on("error.mfploader", function () {
                            d.hasSize = !0, d.loadError = !0, y("LazyLoadError", d)
                        }).attr("src", d.src)), d.preloaded = !0
                    }
                }
            }
        });
        var U = "retina";
        a.magnificPopup.registerModule(U, {
            options: {
                replaceSrc: function (a) {
                    return a.src.replace(/\.\w+$/, function (a) {
                        return "@2x" + a
                    })
                }, ratio: 1
            }, proto: {
                initRetina: function () {
                    if (window.devicePixelRatio > 1) {
                        var a = b.st.retina, c = a.ratio;
                        c = isNaN(c) ? c() : c, c > 1 && (w("ImageHasSize." + U, function (a, b) {
                            b.img.css({"max-width": b.img[0].naturalWidth / c, width: "100%"})
                        }), w("ElementParse." + U, function (b, d) {
                            d.src = a.replaceSrc(d, c)
                        }))
                    }
                }
            }
        }), A()
    });


    /* SCROLL TO TOP */

    /**
     * Copyright (c) 2007-2012 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
     * Dual licensed under MIT and GPL.
     * @author Ariel Flesler
     * @version 1.4.3.1
     */
    ;
    (function ($) {
        var h = $.scrollTo = function (a, b, c) {
            $(window).scrollTo(a, b, c)
        };
        h.defaults = {axis: 'xy', duration: parseFloat($.fn.$) >= 1.3 ? 0 : 1, limit: true};
        h.window = function (a) {
            return $(window)._scrollable()
        };
        $.fn._scrollable = function () {
            return this.map(function () {
                var a = this, isWin = !a.nodeName || $.inArray(a.nodeName.toLowerCase(), ['iframe', '#document', 'html', 'body']) != -1;
                if (!isWin)return a;
                var b = (a.contentWindow || a).document || a.ownerDocument || a;
                return /webkit/i.test(navigator.userAgent) || b.compatMode == 'BackCompat' ? b.body : b.documentElement
            })
        };
        $.fn.scrollTo = function (e, f, g) {
            if (typeof f == 'object') {
                g = f;
                f = 0
            }
            if (typeof g == 'function')g = {onAfter: g};
            if (e == 'max')e = 9e9;
            g = $.extend({}, h.defaults, g);
            f = f || g.duration;
            g.queue = g.queue && g.axis.length > 1;
            if (g.queue)f /= 2;
            g.offset = both(g.offset);
            g.over = both(g.over);
            return this._scrollable().each(function () {
                if (e == null)return;
                var d = this, $elem = $(d), targ = e, toff, attr = {}, win = $elem.is('html,body');
                switch (typeof targ) {
                    case'number':
                    case'string':
                        if (/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(targ)) {
                            targ = both(targ);
                            break
                        }
                        targ = $(targ, this);
                        if (!targ.length)return;
                    case'object':
                        if (targ.is || targ.style)toff = (targ = $(targ)).offset()
                }
                $.each(g.axis.split(''), function (i, a) {
                    var b = a == 'x' ? 'Left' : 'Top', pos = b.toLowerCase(), key = 'scroll' + b, old = d[key], max = h.max(d, a);
                    if (toff) {
                        attr[key] = toff[pos] + (win ? 0 : old - $elem.offset()[pos]);
                        if (g.margin) {
                            attr[key] -= parseInt(targ.css('margin' + b)) || 0;
                            attr[key] -= parseInt(targ.css('border' + b + 'Width')) || 0
                        }
                        attr[key] += g.offset[pos] || 0;
                        if (g.over[pos])attr[key] += targ[a == 'x' ? 'width' : 'height']() * g.over[pos]
                    } else {
                        var c = targ[pos];
                        attr[key] = c.slice && c.slice(-1) == '%' ? parseFloat(c) / 100 * max : c
                    }
                    if (g.limit && /^\d+$/.test(attr[key]))attr[key] = attr[key] <= 0 ? 0 : Math.min(attr[key], max);
                    if (!i && g.queue) {
                        if (old != attr[key])animate(g.onAfterFirst);
                        delete attr[key]
                    }
                });
                animate(g.onAfter);
                function animate(a) {
                    $elem.animate(attr, f, g.easing, a && function () {
                            a.call(this, e, g)
                        })
                }
            }).end()
        };
        h.max = function (a, b) {
            var c = b == 'x' ? 'Width' : 'Height', scroll = 'scroll' + c;
            if (!$(a).is('html,body'))return a[scroll] - $(a)[c.toLowerCase()]();
            var d = 'client' + c, html = a.ownerDocument.documentElement, body = a.ownerDocument.body;
            return Math.max(html[scroll], body[scroll]) - Math.min(html[d], body[d])
        };
        function both(a) {
            return typeof a == 'object' ? a : {top: a, left: a}
        }
    })($);
}(jQuery));


/**
 * hoverIntent r7 // 2013.03.11 // jQuery 1.9.1+
 **/
(function ($) {
    $.fn.hoverIntent = function (handlerIn, handlerOut, selector) {

        // default configuration values
        var cfg = {
            interval: 50,
            sensitivity: 20,
            timeout: 200
        };

        if (typeof handlerIn === "object") {
            cfg = $.extend(cfg, handlerIn);
        } else if ($.isFunction(handlerOut)) {
            cfg = $.extend(cfg, {over: handlerIn, out: handlerOut, selector: selector});
        } else {
            cfg = $.extend(cfg, {over: handlerIn, out: handlerIn, selector: handlerOut});
        }

        // instantiate variables
        // cX, cY = current X and Y position of mouse, updated by mousemove event
        // pX, pY = previous X and Y position of mouse, set by mouseover and polling interval
        var cX, cY, pX, pY;

        // A private function for getting mouse position
        var track = function (ev) {
            cX = ev.pageX;
            cY = ev.pageY;
        };

        // A private function for comparing current and previous mouse position
        var compare = function (ev, ob) {
            ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
            // compare mouse positions to see if they've crossed the threshold
            if (( Math.abs(pX - cX) + Math.abs(pY - cY) ) < cfg.sensitivity) {
                $(ob).off("mousemove.hoverIntent", track);
                // set hoverIntent state to true (so mouseOut can be called)
                ob.hoverIntent_s = 1;
                return cfg.over.apply(ob, [ev]);
            } else {
                // set previous coordinates for next time
                pX = cX;
                pY = cY;
                // use self-calling timeout, guarantees intervals are spaced out properly (avoids JavaScript timer bugs)
                ob.hoverIntent_t = setTimeout(function () {
                    compare(ev, ob);
                }, cfg.interval);
            }
        };

        // A private function for delaying the mouseOut function
        var delay = function (ev, ob) {
            ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
            ob.hoverIntent_s = 0;
            return cfg.out.apply(ob, [ev]);
        };

        // A private function for handling mouse 'hovering'
        var handleHover = function (e) {
            // copy objects to be passed into t (required for event object to be passed in IE)
            var ev = jQuery.extend({}, e);
            var ob = this;

            // cancel hoverIntent timer if it exists
            if (ob.hoverIntent_t) {
                ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
            }

            // if e.type == "mouseenter"
            if (e.type == "mouseenter") {
                // set "previous" X and Y position based on initial entry point
                pX = ev.pageX;
                pY = ev.pageY;
                // update "current" X and Y position based on mousemove
                $(ob).on("mousemove.hoverIntent", track);
                // start polling interval (self-calling timeout) to compare mouse coordinates over time
                if (ob.hoverIntent_s != 1) {
                    ob.hoverIntent_t = setTimeout(function () {
                        compare(ev, ob);
                    }, cfg.interval);
                }

                // else e.type == "mouseleave"
            } else {
                // unbind expensive mousemove event
                $(ob).off("mousemove.hoverIntent", track);
                // if hoverIntent state is true, then call the mouseOut function after the specified delay
                if (ob.hoverIntent_s == 1) {
                    ob.hoverIntent_t = setTimeout(function () {
                        delay(ev, ob);
                    }, cfg.timeout);
                }
            }
        };

        // listen for mouseenter and mouseleave
        return this.on({'mouseenter.hoverIntent': handleHover, 'mouseleave.hoverIntent': handleHover}, cfg.selector);
    };
})(jQuery);


/*
 /*!
 jQuery Waypoints - v2.0.5
 Copyright (c) 2011-2014 Caleb Troughton
 Licensed under the MIT license.
 https://github.com/imakewebthings/jquery-waypoints/blob/master/licenses.txt
 */
(function () {
    var t = [].indexOf || function (t) {
            for (var e = 0, n = this.length; e < n; e++) {
                if (e in this && this[e] === t)return e
            }
            return -1
        }, e = [].slice;
    (function (t, e) {
        if (typeof define === "function" && define.amd) {
            return define("waypoints", ["jquery"], function (n) {
                return e(n, t)
            })
        } else {
            return e(t.jQuery, t)
        }
    })(window, function (n, r) {
        var i, o, l, s, f, u, c, a, h, d, p, y, v, w, g, m;
        i = n(r);
        a = t.call(r, "ontouchstart") >= 0;
        s = {horizontal: {}, vertical: {}};
        f = 1;
        c = {};
        u = "waypoints-context-id";
        p = "resize.waypoints";
        y = "scroll.waypoints";
        v = 1;
        w = "waypoints-waypoint-ids";
        g = "waypoint";
        m = "waypoints";
        o = function () {
            function t(t) {
                var e = this;
                this.$element = t;
                this.element = t[0];
                this.didResize = false;
                this.didScroll = false;
                this.id = "context" + f++;
                this.oldScroll = {x: t.scrollLeft(), y: t.scrollTop()};
                this.waypoints = {horizontal: {}, vertical: {}};
                this.element[u] = this.id;
                c[this.id] = this;
                t.bind(y, function () {
                    var t;
                    if (!(e.didScroll || a)) {
                        e.didScroll = true;
                        t = function () {
                            e.doScroll();
                            return e.didScroll = false
                        };
                        return r.setTimeout(t, n[m].settings.scrollThrottle)
                    }
                });
                t.bind(p, function () {
                    var t;
                    if (!e.didResize) {
                        e.didResize = true;
                        t = function () {
                            n[m]("refresh");
                            return e.didResize = false
                        };
                        return r.setTimeout(t, n[m].settings.resizeThrottle)
                    }
                })
            }

            t.prototype.doScroll = function () {
                var t, e = this;
                t = {
                    horizontal: {
                        newScroll: this.$element.scrollLeft(),
                        oldScroll: this.oldScroll.x,
                        forward: "right",
                        backward: "left"
                    },
                    vertical: {
                        newScroll: this.$element.scrollTop(),
                        oldScroll: this.oldScroll.y,
                        forward: "down",
                        backward: "up"
                    }
                };
                if (a && (!t.vertical.oldScroll || !t.vertical.newScroll)) {
                    n[m]("refresh")
                }
                n.each(t, function (t, r) {
                    var i, o, l;
                    l = [];
                    o = r.newScroll > r.oldScroll;
                    i = o ? r.forward : r.backward;
                    n.each(e.waypoints[t], function (t, e) {
                        var n, i;
                        if (r.oldScroll < (n = e.offset) && n <= r.newScroll) {
                            return l.push(e)
                        } else if (r.newScroll < (i = e.offset) && i <= r.oldScroll) {
                            return l.push(e)
                        }
                    });
                    l.sort(function (t, e) {
                        return t.offset - e.offset
                    });
                    if (!o) {
                        l.reverse()
                    }
                    return n.each(l, function (t, e) {
                        if (e.options.continuous || t === l.length - 1) {
                            return e.trigger([i])
                        }
                    })
                });
                return this.oldScroll = {x: t.horizontal.newScroll, y: t.vertical.newScroll}
            };
            t.prototype.refresh = function () {
                var t, e, r, i = this;
                r = n.isWindow(this.element);
                e = this.$element.offset();
                this.doScroll();
                t = {
                    horizontal: {
                        contextOffset: r ? 0 : e.left,
                        contextScroll: r ? 0 : this.oldScroll.x,
                        contextDimension: this.$element.width(),
                        oldScroll: this.oldScroll.x,
                        forward: "right",
                        backward: "left",
                        offsetProp: "left"
                    },
                    vertical: {
                        contextOffset: r ? 0 : e.top,
                        contextScroll: r ? 0 : this.oldScroll.y,
                        contextDimension: r ? n[m]("viewportHeight") : this.$element.height(),
                        oldScroll: this.oldScroll.y,
                        forward: "down",
                        backward: "up",
                        offsetProp: "top"
                    }
                };
                return n.each(t, function (t, e) {
                    return n.each(i.waypoints[t], function (t, r) {
                        var i, o, l, s, f;
                        i = r.options.offset;
                        l = r.offset;
                        o = n.isWindow(r.element) ? 0 : r.$element.offset()[e.offsetProp];
                        if (n.isFunction(i)) {
                            i = i.apply(r.element)
                        } else if (typeof i === "string") {
                            i = parseFloat(i);
                            if (r.options.offset.indexOf("%") > -1) {
                                i = Math.ceil(e.contextDimension * i / 100)
                            }
                        }
                        r.offset = o - e.contextOffset + e.contextScroll - i;
                        if (r.options.onlyOnScroll && l != null || !r.enabled) {
                            return
                        }
                        if (l !== null && l < (s = e.oldScroll) && s <= r.offset) {
                            return r.trigger([e.backward])
                        } else if (l !== null && l > (f = e.oldScroll) && f >= r.offset) {
                            return r.trigger([e.forward])
                        } else if (l === null && e.oldScroll >= r.offset) {
                            return r.trigger([e.forward])
                        }
                    })
                })
            };
            t.prototype.checkEmpty = function () {
                if (n.isEmptyObject(this.waypoints.horizontal) && n.isEmptyObject(this.waypoints.vertical)) {
                    this.$element.unbind([p, y].join(" "));
                    return delete c[this.id]
                }
            };
            return t
        }();
        l = function () {
            function t(t, e, r) {
                var i, o;
                if (r.offset === "bottom-in-view") {
                    r.offset = function () {
                        var t;
                        t = n[m]("viewportHeight");
                        if (!n.isWindow(e.element)) {
                            t = e.$element.height()
                        }
                        return t - n(this).outerHeight()
                    }
                }
                this.$element = t;
                this.element = t[0];
                this.axis = r.horizontal ? "horizontal" : "vertical";
                this.callback = r.handler;
                this.context = e;
                this.enabled = r.enabled;
                this.id = "waypoints" + v++;
                this.offset = null;
                this.options = r;
                e.waypoints[this.axis][this.id] = this;
                s[this.axis][this.id] = this;
                i = (o = this.element[w]) != null ? o : [];
                i.push(this.id);
                this.element[w] = i
            }

            t.prototype.trigger = function (t) {
                if (!this.enabled) {
                    return
                }
                if (this.callback != null) {
                    this.callback.apply(this.element, t)
                }
                if (this.options.triggerOnce) {
                    return this.destroy()
                }
            };
            t.prototype.disable = function () {
                return this.enabled = false
            };
            t.prototype.enable = function () {
                this.context.refresh();
                return this.enabled = true
            };
            t.prototype.destroy = function () {
                delete s[this.axis][this.id];
                delete this.context.waypoints[this.axis][this.id];
                return this.context.checkEmpty()
            };
            t.getWaypointsByElement = function (t) {
                var e, r;
                r = t[w];
                if (!r) {
                    return []
                }
                e = n.extend({}, s.horizontal, s.vertical);
                return n.map(r, function (t) {
                    return e[t]
                })
            };
            return t
        }();
        d = {
            init: function (t, e) {
                var r;
                e = n.extend({}, n.fn[g].defaults, e);
                if ((r = e.handler) == null) {
                    e.handler = t
                }
                this.each(function () {
                    var t, r, i, s;
                    t = n(this);
                    i = (s = e.context) != null ? s : n.fn[g].defaults.context;
                    if (!n.isWindow(i)) {
                        i = t.closest(i)
                    }
                    i = n(i);
                    r = c[i[0][u]];
                    if (!r) {
                        r = new o(i)
                    }
                    return new l(t, r, e)
                });
                n[m]("refresh");
                return this
            }, disable: function () {
                return d._invoke.call(this, "disable")
            }, enable: function () {
                return d._invoke.call(this, "enable")
            }, destroy: function () {
                return d._invoke.call(this, "destroy")
            }, prev: function (t, e) {
                return d._traverse.call(this, t, e, function (t, e, n) {
                    if (e > 0) {
                        return t.push(n[e - 1])
                    }
                })
            }, next: function (t, e) {
                return d._traverse.call(this, t, e, function (t, e, n) {
                    if (e < n.length - 1) {
                        return t.push(n[e + 1])
                    }
                })
            }, _traverse: function (t, e, i) {
                var o, l;
                if (t == null) {
                    t = "vertical"
                }
                if (e == null) {
                    e = r
                }
                l = h.aggregate(e);
                o = [];
                this.each(function () {
                    var e;
                    e = n.inArray(this, l[t]);
                    return i(o, e, l[t])
                });
                return this.pushStack(o)
            }, _invoke: function (t) {
                this.each(function () {
                    var e;
                    e = l.getWaypointsByElement(this);
                    return n.each(e, function (e, n) {
                        n[t]();
                        return true
                    })
                });
                return this
            }
        };
        n.fn[g] = function () {
            var t, r;
            r = arguments[0], t = 2 <= arguments.length ? e.call(arguments, 1) : [];
            if (d[r]) {
                return d[r].apply(this, t)
            } else if (n.isFunction(r)) {
                return d.init.apply(this, arguments)
            } else if (n.isPlainObject(r)) {
                return d.init.apply(this, [null, r])
            } else if (!r) {
                return n.error("jQuery Waypoints needs a callback function or handler option.")
            } else {
                return n.error("The " + r + " method does not exist in jQuery Waypoints.")
            }
        };
        n.fn[g].defaults = {
            context: r,
            continuous: true,
            enabled: true,
            horizontal: false,
            offset: 0,
            triggerOnce: false
        };
        h = {
            refresh: function () {
                return n.each(c, function (t, e) {
                    return e.refresh()
                })
            }, viewportHeight: function () {
                var t;
                return (t = r.innerHeight) != null ? t : i.height()
            }, aggregate: function (t) {
                var e, r, i;
                e = s;
                if (t) {
                    e = (i = c[n(t)[0][u]]) != null ? i.waypoints : void 0
                }
                if (!e) {
                    return []
                }
                r = {horizontal: [], vertical: []};
                n.each(r, function (t, i) {
                    n.each(e[t], function (t, e) {
                        return i.push(e)
                    });
                    i.sort(function (t, e) {
                        return t.offset - e.offset
                    });
                    r[t] = n.map(i, function (t) {
                        return t.element
                    });
                    return r[t] = n.unique(r[t])
                });
                return r
            }, above: function (t) {
                if (t == null) {
                    t = r
                }
                return h._filter(t, "vertical", function (t, e) {
                    return e.offset <= t.oldScroll.y
                })
            }, below: function (t) {
                if (t == null) {
                    t = r
                }
                return h._filter(t, "vertical", function (t, e) {
                    return e.offset > t.oldScroll.y
                })
            }, left: function (t) {
                if (t == null) {
                    t = r
                }
                return h._filter(t, "horizontal", function (t, e) {
                    return e.offset <= t.oldScroll.x
                })
            }, right: function (t) {
                if (t == null) {
                    t = r
                }
                return h._filter(t, "horizontal", function (t, e) {
                    return e.offset > t.oldScroll.x
                })
            }, enable: function () {
                return h._invoke("enable")
            }, disable: function () {
                return h._invoke("disable")
            }, destroy: function () {
                return h._invoke("destroy")
            }, extendFn: function (t, e) {
                return d[t] = e
            }, _invoke: function (t) {
                var e;
                e = n.extend({}, s.vertical, s.horizontal);
                return n.each(e, function (e, n) {
                    n[t]();
                    return true
                })
            }, _filter: function (t, e, r) {
                var i, o;
                i = c[n(t)[0][u]];
                if (!i) {
                    return []
                }
                o = [];
                n.each(i.waypoints[e], function (t, e) {
                    if (r(i, e)) {
                        return o.push(e)
                    }
                });
                o.sort(function (t, e) {
                    return t.offset - e.offset
                });
                return n.map(o, function (t) {
                    return t.element
                })
            }
        };
        n[m] = function () {
            var t, n;
            n = arguments[0], t = 2 <= arguments.length ? e.call(arguments, 1) : [];
            if (h[n]) {
                return h[n].apply(null, t)
            } else {
                return h.aggregate.call(null, n)
            }
        };
        n[m].settings = {resizeThrottle: 100, scrollThrottle: 30};
        return i.on("load.waypoints", function () {
            return n[m]("refresh")
        })
    })
}).call(this);

/*
 Sticky Elements Shortcut for jQuery Waypoints - v2.0.3
 Copyright (c) 2011-2013 Caleb Troughton
 Dual licensed under the MIT license and GPL license.
 https://github.com/imakewebthings/jquery-waypoints/blob/master/licenses.txt
 */
(function () {
    (function (t, n) {
        if (typeof define === "function" && define.amd) {
            return define(["jquery", "waypoints"], n)
        } else {
            return n(t.jQuery)
        }
    })(this, function (t) {
        var n, s;
        n = {wrapper: '<div class="sticky-wrapper" />', stuckClass: "stuck"};
        s = function (t, n) {
            t.wrap(n.wrapper);
            return t.parent()
        };
        t.waypoints("extendFn", "sticky", function (e) {
            var i, r, a;
            r = t.extend({}, t.fn.waypoint.defaults, n, e);
            i = s(this, r);
            a = r.handler;
            r.handler = function (n) {
                var s, e;
                s = t(this).children(":first");
                e = n === "down" || n === "right";
                s.toggleClass(r.stuckClass, e);
                i.height(e ? s.outerHeight() : "");
                if (a != null) {
                    return a.call(this, n)
                }
            };
            i.waypoint(r);
            return this.data("stuckClass", r.stuckClass)
        });
        return t.waypoints("extendFn", "unsticky", function () {
            this.parent().waypoint("destroy");
            this.unwrap();
            return this.removeClass(this.data("stuckClass"))
        })
    })
}).call(this);


/*!
 * Packery PACKAGED v2.0.0
 * Gapless, draggable grid layouts
 *
 * Licensed GPLv3 for open source use
 * or Packery Commercial License for commercial use
 *
 * http://packery.metafizzy.co
 * Copyright 2016 Metafizzy
 */

!function (t, e) {
    "use strict";
    "function" == typeof define && define.amd ? define("jquery-bridget/jquery-bridget", ["jquery"], function (i) {
        e(t, i)
    }) : "object" == typeof module && module.exports ? module.exports = e(t, require("jquery")) : t.jQueryBridget = e(t, t.jQuery)
}(window, function (t, e) {
    "use strict";
    function i(i, s, a) {
        function h(t, e, n) {
            var o, s = "$()." + i + '("' + e + '")';
            return t.each(function (t, h) {
                var u = a.data(h, i);
                if (!u)return void r(i + " not initialized. Cannot call methods, i.e. " + s);
                var c = u[e];
                if (!c || "_" == e.charAt(0))return void r(s + " is not a valid method");
                var d = c.apply(u, n);
                o = void 0 === o ? d : o
            }), void 0 !== o ? o : t
        }

        function u(t, e) {
            t.each(function (t, n) {
                var o = a.data(n, i);
                o ? (o.option(e), o._init()) : (o = new s(n, e), a.data(n, i, o))
            })
        }

        a = a || e || t.jQuery, a && (s.prototype.option || (s.prototype.option = function (t) {
            a.isPlainObject(t) && (this.options = a.extend(!0, this.options, t))
        }), a.fn[i] = function (t) {
            if ("string" == typeof t) {
                var e = o.call(arguments, 1);
                return h(this, t, e)
            }
            return u(this, t), this
        }, n(a))
    }

    function n(t) {
        !t || t && t.bridget || (t.bridget = i)
    }

    var o = Array.prototype.slice, s = t.console, r = "undefined" == typeof s ? function () {
    } : function (t) {
        s.error(t)
    };
    return n(e || t.jQuery), i
}), function (t, e) {
    "use strict";
    "function" == typeof define && define.amd ? define("get-size/get-size", [], function () {
        return e()
    }) : "object" == typeof module && module.exports ? module.exports = e() : t.getSize = e()
}(window, function () {
    "use strict";
    function t(t) {
        var e = parseFloat(t), i = -1 == t.indexOf("%") && !isNaN(e);
        return i && e
    }

    function e() {
    }

    function i() {
        for (var t = {
            width: 0,
            height: 0,
            innerWidth: 0,
            innerHeight: 0,
            outerWidth: 0,
            outerHeight: 0
        }, e = 0; u > e; e++) {
            var i = h[e];
            t[i] = 0
        }
        return t
    }

    function n(t) {
        var e = getComputedStyle(t);
        return e || a("Style returned " + e + ". Are you running this code in a hidden iframe on Firefox? See http://bit.ly/getsizebug1"), e
    }

    function o() {
        if (!c) {
            c = !0;
            var e = document.createElement("div");
            e.style.width = "200px", e.style.padding = "1px 2px 3px 4px", e.style.borderStyle = "solid", e.style.borderWidth = "1px 2px 3px 4px", e.style.boxSizing = "border-box";
            var i = document.body || document.documentElement;
            i.appendChild(e);
            var o = n(e);
            s.isBoxSizeOuter = r = 200 == t(o.width), i.removeChild(e)
        }
    }

    function s(e) {
        if (o(), "string" == typeof e && (e = document.querySelector(e)), e && "object" == typeof e && e.nodeType) {
            var s = n(e);
            if ("none" == s.display)return i();
            var a = {};
            a.width = e.offsetWidth, a.height = e.offsetHeight;
            for (var c = a.isBorderBox = "border-box" == s.boxSizing, d = 0; u > d; d++) {
                var f = h[d], l = s[f], p = parseFloat(l);
                a[f] = isNaN(p) ? 0 : p
            }
            var m = a.paddingLeft + a.paddingRight, g = a.paddingTop + a.paddingBottom, y = a.marginLeft + a.marginRight, v = a.marginTop + a.marginBottom, _ = a.borderLeftWidth + a.borderRightWidth, x = a.borderTopWidth + a.borderBottomWidth, b = c && r, E = t(s.width);
            E !== !1 && (a.width = E + (b ? 0 : m + _));
            var T = t(s.height);
            return T !== !1 && (a.height = T + (b ? 0 : g + x)), a.innerWidth = a.width - (m + _), a.innerHeight = a.height - (g + x), a.outerWidth = a.width + y, a.outerHeight = a.height + v, a
        }
    }

    var r, a = "undefined" == typeof console ? e : function (t) {
        console.error(t)
    }, h = ["paddingLeft", "paddingRight", "paddingTop", "paddingBottom", "marginLeft", "marginRight", "marginTop", "marginBottom", "borderLeftWidth", "borderRightWidth", "borderTopWidth", "borderBottomWidth"], u = h.length, c = !1;
    return s
}), function (t, e) {
    "function" == typeof define && define.amd ? define("ev-emitter/ev-emitter", e) : "object" == typeof module && module.exports ? module.exports = e() : t.EvEmitter = e()
}(this, function () {
    function t() {
    }

    var e = t.prototype;
    return e.on = function (t, e) {
        if (t && e) {
            var i = this._events = this._events || {}, n = i[t] = i[t] || [];
            return -1 == n.indexOf(e) && n.push(e), this
        }
    }, e.once = function (t, e) {
        if (t && e) {
            this.on(t, e);
            var i = this._onceEvents = this._onceEvents || {}, n = i[t] = i[t] || {};
            return n[e] = !0, this
        }
    }, e.off = function (t, e) {
        var i = this._events && this._events[t];
        if (i && i.length) {
            var n = i.indexOf(e);
            return -1 != n && i.splice(n, 1), this
        }
    }, e.emitEvent = function (t, e) {
        var i = this._events && this._events[t];
        if (i && i.length) {
            var n = 0, o = i[n];
            e = e || [];
            for (var s = this._onceEvents && this._onceEvents[t]; o;) {
                var r = s && s[o];
                r && (this.off(t, o), delete s[o]), o.apply(this, e), n += r ? 0 : 1, o = i[n]
            }
            return this
        }
    }, t
}), function (t, e) {
    "use strict";
    "function" == typeof define && define.amd ? define("desandro-matches-selector/matches-selector", e) : "object" == typeof module && module.exports ? module.exports = e() : t.matchesSelector = e()
}(window, function () {
    "use strict";
    var t = function () {
        var t = Element.prototype;
        if (t.matches)return "matches";
        if (t.matchesSelector)return "matchesSelector";
        for (var e = ["webkit", "moz", "ms", "o"], i = 0; i < e.length; i++) {
            var n = e[i], o = n + "MatchesSelector";
            if (t[o])return o
        }
    }();
    return function (e, i) {
        return e[t](i)
    }
}), function (t, e) {
    "function" == typeof define && define.amd ? define("fizzy-ui-utils/utils", ["desandro-matches-selector/matches-selector"], function (i) {
        return e(t, i)
    }) : "object" == typeof module && module.exports ? module.exports = e(t, require("desandro-matches-selector")) : t.fizzyUIUtils = e(t, t.matchesSelector)
}(window, function (t, e) {
    var i = {};
    i.extend = function (t, e) {
        for (var i in e)t[i] = e[i];
        return t
    }, i.modulo = function (t, e) {
        return (t % e + e) % e
    }, i.makeArray = function (t) {
        var e = [];
        if (Array.isArray(t))e = t; else if (t && "number" == typeof t.length)for (var i = 0; i < t.length; i++)e.push(t[i]); else e.push(t);
        return e
    }, i.removeFrom = function (t, e) {
        var i = t.indexOf(e);
        -1 != i && t.splice(i, 1)
    }, i.getParent = function (t, i) {
        for (; t != document.body;)if (t = t.parentNode, e(t, i))return t
    }, i.getQueryElement = function (t) {
        return "string" == typeof t ? document.querySelector(t) : t
    }, i.handleEvent = function (t) {
        var e = "on" + t.type;
        this[e] && this[e](t)
    }, i.filterFindElements = function (t, n) {
        t = i.makeArray(t);
        var o = [];
        return t.forEach(function (t) {
            if (t instanceof HTMLElement) {
                if (!n)return void o.push(t);
                e(t, n) && o.push(t);
                for (var i = t.querySelectorAll(n), s = 0; s < i.length; s++)o.push(i[s])
            }
        }), o
    }, i.debounceMethod = function (t, e, i) {
        var n = t.prototype[e], o = e + "Timeout";
        t.prototype[e] = function () {
            var t = this[o];
            t && clearTimeout(t);
            var e = arguments, s = this;
            this[o] = setTimeout(function () {
                n.apply(s, e), delete s[o]
            }, i || 100)
        }
    }, i.docReady = function (t) {
        "complete" == document.readyState ? t() : document.addEventListener("DOMContentLoaded", t)
    }, i.toDashed = function (t) {
        return t.replace(/(.)([A-Z])/g, function (t, e, i) {
            return e + "-" + i
        }).toLowerCase()
    };
    var n = t.console;
    return i.htmlInit = function (e, o) {
        i.docReady(function () {
            var s = i.toDashed(o), r = "data-" + s, a = document.querySelectorAll("[" + r + "]"), h = document.querySelectorAll(".js-" + s), u = i.makeArray(a).concat(i.makeArray(h)), c = r + "-options", d = t.jQuery;
            u.forEach(function (t) {
                var i, s = t.getAttribute(r) || t.getAttribute(c);
                try {
                    i = s && JSON.parse(s)
                } catch (a) {
                    return void(n && n.error("Error parsing " + r + " on " + t.className + ": " + a))
                }
                var h = new e(t, i);
                d && d.data(t, o, h)
            })
        })
    }, i
}), function (t, e) {
    "function" == typeof define && define.amd ? define("outlayer/item", ["ev-emitter/ev-emitter", "get-size/get-size"], e) : "object" == typeof module && module.exports ? module.exports = e(require("ev-emitter"), require("get-size")) : (t.Outlayer = {}, t.Outlayer.Item = e(t.EvEmitter, t.getSize))
}(window, function (t, e) {
    "use strict";
    function i(t) {
        for (var e in t)return !1;
        return e = null, !0
    }

    function n(t, e) {
        t && (this.element = t, this.layout = e, this.position = {x: 0, y: 0}, this._create())
    }

    function o(t) {
        return t.replace(/([A-Z])/g, function (t) {
            return "-" + t.toLowerCase()
        })
    }

    var s = document.documentElement.style, r = "string" == typeof s.transition ? "transition" : "WebkitTransition", a = "string" == typeof s.transform ? "transform" : "WebkitTransform", h = {
        WebkitTransition: "webkitTransitionEnd",
        transition: "transitionend"
    }[r], u = {
        transform: a,
        transition: r,
        transitionDuration: r + "Duration",
        transitionProperty: r + "Property"
    }, c = n.prototype = Object.create(t.prototype);
    c.constructor = n, c._create = function () {
        this._transn = {ingProperties: {}, clean: {}, onEnd: {}}, this.css({position: "absolute"})
    }, c.handleEvent = function (t) {
        var e = "on" + t.type;
        this[e] && this[e](t)
    }, c.getSize = function () {
        this.size = e(this.element)
    }, c.css = function (t) {
        var e = this.element.style;
        for (var i in t) {
            var n = u[i] || i;
            e[n] = t[i]
        }
    }, c.getPosition = function () {
        var t = getComputedStyle(this.element), e = this.layout._getOption("originLeft"), i = this.layout._getOption("originTop"), n = t[e ? "left" : "right"], o = t[i ? "top" : "bottom"], s = this.layout.size, r = -1 != n.indexOf("%") ? parseFloat(n) / 100 * s.width : parseInt(n, 10), a = -1 != o.indexOf("%") ? parseFloat(o) / 100 * s.height : parseInt(o, 10);
        r = isNaN(r) ? 0 : r, a = isNaN(a) ? 0 : a, r -= e ? s.paddingLeft : s.paddingRight, a -= i ? s.paddingTop : s.paddingBottom, this.position.x = r, this.position.y = a
    }, c.layoutPosition = function () {
        var t = this.layout.size, e = {}, i = this.layout._getOption("originLeft"), n = this.layout._getOption("originTop"), o = i ? "paddingLeft" : "paddingRight", s = i ? "left" : "right", r = i ? "right" : "left", a = this.position.x + t[o];
        e[s] = this.getXValue(a), e[r] = "";
        var h = n ? "paddingTop" : "paddingBottom", u = n ? "top" : "bottom", c = n ? "bottom" : "top", d = this.position.y + t[h];
        e[u] = this.getYValue(d), e[c] = "", this.css(e), this.emitEvent("layout", [this])
    }, c.getXValue = function (t) {
        var e = this.layout._getOption("horizontal");
        return this.layout.options.percentPosition && !e ? t / this.layout.size.width * 100 + "%" : t + "px"
    }, c.getYValue = function (t) {
        var e = this.layout._getOption("horizontal");
        return this.layout.options.percentPosition && e ? t / this.layout.size.height * 100 + "%" : t + "px"
    }, c._transitionTo = function (t, e) {
        this.getPosition();
        var i = this.position.x, n = this.position.y, o = parseInt(t, 10), s = parseInt(e, 10), r = o === this.position.x && s === this.position.y;
        if (this.setPosition(t, e), r && !this.isTransitioning)return void this.layoutPosition();
        var a = t - i, h = e - n, u = {};
        u.transform = this.getTranslate(a, h), this.transition({
            to: u,
            onTransitionEnd: {transform: this.layoutPosition},
            isCleaning: !0
        })
    }, c.getTranslate = function (t, e) {
        var i = this.layout._getOption("originLeft"), n = this.layout._getOption("originTop");
        return t = i ? t : -t, e = n ? e : -e, "translate3d(" + t + "px, " + e + "px, 0)"
    }, c.goTo = function (t, e) {
        this.setPosition(t, e), this.layoutPosition()
    }, c.moveTo = c._transitionTo, c.setPosition = function (t, e) {
        this.position.x = parseInt(t, 10), this.position.y = parseInt(e, 10)
    }, c._nonTransition = function (t) {
        this.css(t.to), t.isCleaning && this._removeStyles(t.to);
        for (var e in t.onTransitionEnd)t.onTransitionEnd[e].call(this)
    }, c.transition = function (t) {
        if (!parseFloat(this.layout.options.transitionDuration))return void this._nonTransition(t);
        var e = this._transn;
        for (var i in t.onTransitionEnd)e.onEnd[i] = t.onTransitionEnd[i];
        for (i in t.to)e.ingProperties[i] = !0, t.isCleaning && (e.clean[i] = !0);
        if (t.from) {
            this.css(t.from);
            var n = this.element.offsetHeight;
            n = null
        }
        this.enableTransition(t.to), this.css(t.to), this.isTransitioning = !0
    };
    var d = "opacity," + o(a);
    c.enableTransition = function () {
        this.isTransitioning || (this.css({
            transitionProperty: d,
            transitionDuration: this.layout.options.transitionDuration
        }), this.element.addEventListener(h, this, !1))
    }, c.onwebkitTransitionEnd = function (t) {
        this.ontransitionend(t)
    }, c.onotransitionend = function (t) {
        this.ontransitionend(t)
    };
    var f = {"-webkit-transform": "transform"};
    c.ontransitionend = function (t) {
        if (t.target === this.element) {
            var e = this._transn, n = f[t.propertyName] || t.propertyName;
            if (delete e.ingProperties[n], i(e.ingProperties) && this.disableTransition(), n in e.clean && (this.element.style[t.propertyName] = "", delete e.clean[n]), n in e.onEnd) {
                var o = e.onEnd[n];
                o.call(this), delete e.onEnd[n]
            }
            this.emitEvent("transitionEnd", [this])
        }
    }, c.disableTransition = function () {
        this.removeTransitionStyles(), this.element.removeEventListener(h, this, !1), this.isTransitioning = !1
    }, c._removeStyles = function (t) {
        var e = {};
        for (var i in t)e[i] = "";
        this.css(e)
    };
    var l = {transitionProperty: "", transitionDuration: ""};
    return c.removeTransitionStyles = function () {
        this.css(l)
    }, c.removeElem = function () {
        this.element.parentNode.removeChild(this.element), this.css({display: ""}), this.emitEvent("remove", [this])
    }, c.remove = function () {
        return r && parseFloat(this.layout.options.transitionDuration) ? (this.once("transitionEnd", function () {
            this.removeElem()
        }), void this.hide()) : void this.removeElem()
    }, c.reveal = function () {
        delete this.isHidden, this.css({display: ""});
        var t = this.layout.options, e = {}, i = this.getHideRevealTransitionEndProperty("visibleStyle");
        e[i] = this.onRevealTransitionEnd, this.transition({
            from: t.hiddenStyle,
            to: t.visibleStyle,
            isCleaning: !0,
            onTransitionEnd: e
        })
    }, c.onRevealTransitionEnd = function () {
        this.isHidden || this.emitEvent("reveal")
    }, c.getHideRevealTransitionEndProperty = function (t) {
        var e = this.layout.options[t];
        if (e.opacity)return "opacity";
        for (var i in e)return i
    }, c.hide = function () {
        this.isHidden = !0, this.css({display: ""});
        var t = this.layout.options, e = {}, i = this.getHideRevealTransitionEndProperty("hiddenStyle");
        e[i] = this.onHideTransitionEnd, this.transition({
            from: t.visibleStyle,
            to: t.hiddenStyle,
            isCleaning: !0,
            onTransitionEnd: e
        })
    }, c.onHideTransitionEnd = function () {
        this.isHidden && (this.css({display: "none"}), this.emitEvent("hide"))
    }, c.destroy = function () {
        this.css({position: "", left: "", right: "", top: "", bottom: "", transition: "", transform: ""})
    }, n
}), function (t, e) {
    "use strict";
    "function" == typeof define && define.amd ? define("outlayer/outlayer", ["ev-emitter/ev-emitter", "get-size/get-size", "fizzy-ui-utils/utils", "./item"], function (i, n, o, s) {
        return e(t, i, n, o, s)
    }) : "object" == typeof module && module.exports ? module.exports = e(t, require("ev-emitter"), require("get-size"), require("fizzy-ui-utils"), require("./item")) : t.Outlayer = e(t, t.EvEmitter, t.getSize, t.fizzyUIUtils, t.Outlayer.Item)
}(window, function (t, e, i, n, o) {
    "use strict";
    function s(t, e) {
        var i = n.getQueryElement(t);
        if (!i)return void(a && a.error("Bad element for " + this.constructor.namespace + ": " + (i || t)));
        this.element = i, h && (this.$element = h(this.element)), this.options = n.extend({}, this.constructor.defaults), this.option(e);
        var o = ++c;
        this.element.outlayerGUID = o, d[o] = this, this._create();
        var s = this._getOption("initLayout");
        s && this.layout()
    }

    function r(t) {
        function e() {
            t.apply(this, arguments)
        }

        return e.prototype = Object.create(t.prototype), e.prototype.constructor = e, e
    }

    var a = t.console, h = t.jQuery, u = function () {
    }, c = 0, d = {};
    s.namespace = "outlayer", s.Item = o, s.defaults = {
        containerStyle: {position: "relative"},
        initLayout: !0,
        originLeft: !0,
        originTop: !0,
        resize: !0,
        resizeContainer: !0,
        transitionDuration: "0.4s",
        hiddenStyle: {opacity: 0, transform: "scale(0.001)"},
        visibleStyle: {opacity: 1, transform: "scale(1)"}
    };
    var f = s.prototype;
    return n.extend(f, e.prototype), f.option = function (t) {
        n.extend(this.options, t)
    }, f._getOption = function (t) {
        var e = this.constructor.compatOptions[t];
        return e && void 0 !== this.options[e] ? this.options[e] : this.options[t]
    }, s.compatOptions = {
        initLayout: "isInitLayout",
        horizontal: "isHorizontal",
        layoutInstant: "isLayoutInstant",
        originLeft: "isOriginLeft",
        originTop: "isOriginTop",
        resize: "isResizeBound",
        resizeContainer: "isResizingContainer"
    }, f._create = function () {
        this.reloadItems(), this.stamps = [], this.stamp(this.options.stamp), n.extend(this.element.style, this.options.containerStyle);
        var t = this._getOption("resize");
        t && this.bindResize()
    }, f.reloadItems = function () {
        this.items = this._itemize(this.element.children)
    }, f._itemize = function (t) {
        for (var e = this._filterFindItemElements(t), i = this.constructor.Item, n = [], o = 0; o < e.length; o++) {
            var s = e[o], r = new i(s, this);
            n.push(r)
        }
        return n
    }, f._filterFindItemElements = function (t) {
        return n.filterFindElements(t, this.options.itemSelector)
    }, f.getItemElements = function () {
        return this.items.map(function (t) {
            return t.element
        })
    }, f.layout = function () {
        this._resetLayout(), this._manageStamps();
        var t = this._getOption("layoutInstant"), e = void 0 !== t ? t : !this._isLayoutInited;
        this.layoutItems(this.items, e), this._isLayoutInited = !0
    }, f._init = f.layout, f._resetLayout = function () {
        this.getSize()
    }, f.getSize = function () {
        this.size = i(this.element)
    }, f._getMeasurement = function (t, e) {
        var n, o = this.options[t];
        o ? ("string" == typeof o ? n = this.element.querySelector(o) : o instanceof HTMLElement && (n = o), this[t] = n ? i(n)[e] : o) : this[t] = 0
    }, f.layoutItems = function (t, e) {
        t = this._getItemsForLayout(t), this._layoutItems(t, e), this._postLayout()
    }, f._getItemsForLayout = function (t) {
        return t.filter(function (t) {
            return !t.isIgnored
        })
    }, f._layoutItems = function (t, e) {
        if (this._emitCompleteOnItems("layout", t), t && t.length) {
            var i = [];
            t.forEach(function (t) {
                var n = this._getItemLayoutPosition(t);
                n.item = t, n.isInstant = e || t.isLayoutInstant, i.push(n)
            }, this), this._processLayoutQueue(i)
        }
    }, f._getItemLayoutPosition = function () {
        return {x: 0, y: 0}
    }, f._processLayoutQueue = function (t) {
        t.forEach(function (t) {
            this._positionItem(t.item, t.x, t.y, t.isInstant)
        }, this)
    }, f._positionItem = function (t, e, i, n) {
        n ? t.goTo(e, i) : t.moveTo(e, i)
    }, f._postLayout = function () {
        this.resizeContainer()
    }, f.resizeContainer = function () {
        var t = this._getOption("resizeContainer");
        if (t) {
            var e = this._getContainerSize();
            e && (this._setContainerMeasure(e.width, !0), this._setContainerMeasure(e.height, !1))
        }
    }, f._getContainerSize = u, f._setContainerMeasure = function (t, e) {
        if (void 0 !== t) {
            var i = this.size;
            i.isBorderBox && (t += e ? i.paddingLeft + i.paddingRight + i.borderLeftWidth + i.borderRightWidth : i.paddingBottom + i.paddingTop + i.borderTopWidth + i.borderBottomWidth), t = Math.max(t, 0), this.element.style[e ? "width" : "height"] = t + "px"
        }
    }, f._emitCompleteOnItems = function (t, e) {
        function i() {
            o.dispatchEvent(t + "Complete", null, [e])
        }

        function n() {
            r++, r == s && i()
        }

        var o = this, s = e.length;
        if (!e || !s)return void i();
        var r = 0;
        e.forEach(function (e) {
            e.once(t, n)
        })
    }, f.dispatchEvent = function (t, e, i) {
        var n = e ? [e].concat(i) : i;
        if (this.emitEvent(t, n), h)if (this.$element = this.$element || h(this.element), e) {
            var o = h.Event(e);
            o.type = t, this.$element.trigger(o, i)
        } else this.$element.trigger(t, i)
    }, f.ignore = function (t) {
        var e = this.getItem(t);
        e && (e.isIgnored = !0)
    }, f.unignore = function (t) {
        var e = this.getItem(t);
        e && delete e.isIgnored
    }, f.stamp = function (t) {
        t = this._find(t), t && (this.stamps = this.stamps.concat(t), t.forEach(this.ignore, this))
    }, f.unstamp = function (t) {
        t = this._find(t), t && t.forEach(function (t) {
            n.removeFrom(this.stamps, t), this.unignore(t)
        }, this)
    }, f._find = function (t) {
        return t ? ("string" == typeof t && (t = this.element.querySelectorAll(t)), t = n.makeArray(t)) : void 0
    }, f._manageStamps = function () {
        this.stamps && this.stamps.length && (this._getBoundingRect(), this.stamps.forEach(this._manageStamp, this))
    }, f._getBoundingRect = function () {
        var t = this.element.getBoundingClientRect(), e = this.size;
        this._boundingRect = {
            left: t.left + e.paddingLeft + e.borderLeftWidth,
            top: t.top + e.paddingTop + e.borderTopWidth,
            right: t.right - (e.paddingRight + e.borderRightWidth),
            bottom: t.bottom - (e.paddingBottom + e.borderBottomWidth)
        }
    }, f._manageStamp = u, f._getElementOffset = function (t) {
        var e = t.getBoundingClientRect(), n = this._boundingRect, o = i(t), s = {
            left: e.left - n.left - o.marginLeft,
            top: e.top - n.top - o.marginTop,
            right: n.right - e.right - o.marginRight,
            bottom: n.bottom - e.bottom - o.marginBottom
        };
        return s
    }, f.handleEvent = n.handleEvent, f.bindResize = function () {
        t.addEventListener("resize", this), this.isResizeBound = !0
    }, f.unbindResize = function () {
        t.removeEventListener("resize", this), this.isResizeBound = !1
    }, f.onresize = function () {
        this.resize()
    }, n.debounceMethod(s, "onresize", 100), f.resize = function () {
        this.isResizeBound && this.needsResizeLayout() && this.layout()
    }, f.needsResizeLayout = function () {
        var t = i(this.element), e = this.size && t;
        return e && t.innerWidth !== this.size.innerWidth
    }, f.addItems = function (t) {
        var e = this._itemize(t);
        return e.length && (this.items = this.items.concat(e)), e
    }, f.appended = function (t) {
        var e = this.addItems(t);
        e.length && (this.layoutItems(e, !0), this.reveal(e))
    }, f.prepended = function (t) {
        var e = this._itemize(t);
        if (e.length) {
            var i = this.items.slice(0);
            this.items = e.concat(i), this._resetLayout(), this._manageStamps(), this.layoutItems(e, !0), this.reveal(e), this.layoutItems(i)
        }
    }, f.reveal = function (t) {
        this._emitCompleteOnItems("reveal", t), t && t.length && t.forEach(function (t) {
            t.reveal()
        })
    }, f.hide = function (t) {
        this._emitCompleteOnItems("hide", t), t && t.length && t.forEach(function (t) {
            t.hide()
        })
    }, f.revealItemElements = function (t) {
        var e = this.getItems(t);
        this.reveal(e)
    }, f.hideItemElements = function (t) {
        var e = this.getItems(t);
        this.hide(e)
    }, f.getItem = function (t) {
        for (var e = 0; e < this.items.length; e++) {
            var i = this.items[e];
            if (i.element == t)return i
        }
    }, f.getItems = function (t) {
        t = n.makeArray(t);
        var e = [];
        return t.forEach(function (t) {
            var i = this.getItem(t);
            i && e.push(i)
        }, this), e
    }, f.remove = function (t) {
        var e = this.getItems(t);
        this._emitCompleteOnItems("remove", e), e && e.length && e.forEach(function (t) {
            t.remove(), n.removeFrom(this.items, t)
        }, this)
    }, f.destroy = function () {
        var t = this.element.style;
        t.height = "", t.position = "", t.width = "", this.items.forEach(function (t) {
            t.destroy()
        }), this.unbindResize();
        var e = this.element.outlayerGUID;
        delete d[e], delete this.element.outlayerGUID, h && h.removeData(this.element, this.constructor.namespace)
    }, s.data = function (t) {
        t = n.getQueryElement(t);
        var e = t && t.outlayerGUID;
        return e && d[e]
    }, s.create = function (t, e) {
        var i = r(s);
        return i.defaults = n.extend({}, s.defaults), n.extend(i.defaults, e), i.compatOptions = n.extend({}, s.compatOptions), i.namespace = t, i.data = s.data, i.Item = r(o), n.htmlInit(i, t), h && h.bridget && h.bridget(t, i), i
    }, s.Item = o, s
}), function (t, e) {
    "function" == typeof define && define.amd ? define("packery/rect", e) : "object" == typeof module && module.exports ? module.exports = e() : (t.Packery = t.Packery || {}, t.Packery.Rect = e())
}(window, function () {
    "use strict";
    function t(e) {
        for (var i in t.defaults)this[i] = t.defaults[i];
        for (i in e)this[i] = e[i]
    }

    t.defaults = {x: 0, y: 0, width: 0, height: 0};
    var e = t.prototype;
    return e.contains = function (t) {
        var e = t.width || 0, i = t.height || 0;
        return this.x <= t.x && this.y <= t.y && this.x + this.width >= t.x + e && this.y + this.height >= t.y + i
    }, e.overlaps = function (t) {
        var e = this.x + this.width, i = this.y + this.height, n = t.x + t.width, o = t.y + t.height;
        return this.x < n && e > t.x && this.y < o && i > t.y
    }, e.getMaximalFreeRects = function (e) {
        if (!this.overlaps(e))return !1;
        var i, n = [], o = this.x + this.width, s = this.y + this.height, r = e.x + e.width, a = e.y + e.height;
        return this.y < e.y && (i = new t({
            x: this.x,
            y: this.y,
            width: this.width,
            height: e.y - this.y
        }), n.push(i)), o > r && (i = new t({
            x: r,
            y: this.y,
            width: o - r,
            height: this.height
        }), n.push(i)), s > a && (i = new t({
            x: this.x,
            y: a,
            width: this.width,
            height: s - a
        }), n.push(i)), this.x < e.x && (i = new t({
            x: this.x,
            y: this.y,
            width: e.x - this.x,
            height: this.height
        }), n.push(i)), n
    }, e.canFit = function (t) {
        return this.width >= t.width && this.height >= t.height
    }, t
}), function (t, e) {
    if ("function" == typeof define && define.amd)define("packery/packer", ["./rect"], e); else if ("object" == typeof module && module.exports)module.exports = e(require("./rect")); else {
        var i = t.Packery = t.Packery || {};
        i.Packer = e(i.Rect)
    }
}(window, function (t) {
    "use strict";
    function e(t, e, i) {
        this.width = t || 0, this.height = e || 0, this.sortDirection = i || "downwardLeftToRight", this.reset()
    }

    var i = e.prototype;
    i.reset = function () {
        this.spaces = [];
        var e = new t({x: 0, y: 0, width: this.width, height: this.height});
        this.spaces.push(e), this.sorter = n[this.sortDirection] || n.downwardLeftToRight
    }, i.pack = function (t) {
        for (var e = 0; e < this.spaces.length; e++) {
            var i = this.spaces[e];
            if (i.canFit(t)) {
                this.placeInSpace(t, i);
                break
            }
        }
    }, i.columnPack = function (t) {
        for (var e = 0; e < this.spaces.length; e++) {
            var i = this.spaces[e], n = i.x <= t.x && i.x + i.width >= t.x + t.width && i.height >= t.height - .01;
            if (n) {
                t.y = i.y, this.placed(t);
                break
            }
        }
    }, i.rowPack = function (t) {
        for (var e = 0; e < this.spaces.length; e++) {
            var i = this.spaces[e], n = i.y <= t.y && i.y + i.height >= t.y + t.height && i.width >= t.width - .01;
            if (n) {
                t.x = i.x, this.placed(t);
                break
            }
        }
    }, i.placeInSpace = function (t, e) {
        t.x = e.x, t.y = e.y, this.placed(t)
    }, i.placed = function (t) {
        for (var e = [], i = 0; i < this.spaces.length; i++) {
            var n = this.spaces[i], o = n.getMaximalFreeRects(t);
            o ? e.push.apply(e, o) : e.push(n)
        }
        this.spaces = e, this.mergeSortSpaces()
    }, i.mergeSortSpaces = function () {
        e.mergeRects(this.spaces), this.spaces.sort(this.sorter)
    }, i.addSpace = function (t) {
        this.spaces.push(t), this.mergeSortSpaces()
    }, e.mergeRects = function (t) {
        var e = 0, i = t[e];
        t:for (; i;) {
            for (var n = 0, o = t[e + n]; o;) {
                if (o == i)n++; else {
                    if (o.contains(i)) {
                        t.splice(e, 1), i = t[e];
                        continue t
                    }
                    i.contains(o) ? t.splice(e + n, 1) : n++
                }
                o = t[e + n]
            }
            e++, i = t[e]
        }
        return t
    };
    var n = {
        downwardLeftToRight: function (t, e) {
            return t.y - e.y || t.x - e.x
        }, rightwardTopToBottom: function (t, e) {
            return t.x - e.x || t.y - e.y
        }
    };
    return e
}), function (t, e) {
    "function" == typeof define && define.amd ? define("packery/item", ["outlayer/outlayer", "./rect"], e) : "object" == typeof module && module.exports ? module.exports = e(require("outlayer"), require("./rect")) : t.Packery.Item = e(t.Outlayer, t.Packery.Rect)
}(window, function (t, e) {
    "use strict";
    var i = document.documentElement.style, n = "string" == typeof i.transform ? "transform" : "WebkitTransform", o = function () {
        t.Item.apply(this, arguments)
    }, s = o.prototype = Object.create(t.Item.prototype), r = s._create;
    s._create = function () {
        r.call(this), this.rect = new e
    };
    var a = s.moveTo;
    return s.moveTo = function (t, e) {
        var i = Math.abs(this.position.x - t), n = Math.abs(this.position.y - e), o = this.layout.dragItemCount && !this.isPlacing && !this.isTransitioning && 1 > i && 1 > n;
        return o ? void this.goTo(t, e) : void a.apply(this, arguments)
    }, s.enablePlacing = function () {
        this.removeTransitionStyles(), this.isTransitioning && n && (this.element.style[n] = "none"), this.isTransitioning = !1, this.getSize(), this.layout._setRectSize(this.element, this.rect), this.isPlacing = !0
    }, s.disablePlacing = function () {
        this.isPlacing = !1
    }, s.removeElem = function () {
        this.element.parentNode.removeChild(this.element), this.layout.packer.addSpace(this.rect), this.emitEvent("remove", [this])
    }, s.showDropPlaceholder = function () {
        var t = this.dropPlaceholder;
        t || (t = this.dropPlaceholder = document.createElement("div"), t.className = "packery-drop-placeholder", t.style.position = "absolute"), t.style.width = this.size.width + "px", t.style.height = this.size.height + "px", this.positionDropPlaceholder(), this.layout.element.appendChild(t)
    }, s.positionDropPlaceholder = function () {
        this.dropPlaceholder.style[n] = "translate(" + this.rect.x + "px, " + this.rect.y + "px)"
    }, s.hideDropPlaceholder = function () {
        this.layout.element.removeChild(this.dropPlaceholder)
    }, o
}), function (t, e) {
    "function" == typeof define && define.amd ? define(["get-size/get-size", "outlayer/outlayer", "./rect", "./packer", "./item"], e) : "object" == typeof module && module.exports ? module.exports = e(require("get-size"), require("outlayer"), require("./rect"), require("./packer"), require("./item")) : t.Packery = e(t.getSize, t.Outlayer, t.Packery.Rect, t.Packery.Packer, t.Packery.Item)
}(window, function (t, e, i, n, o) {
    "use strict";
    function s(t, e) {
        return t.position.y - e.position.y || t.position.x - e.position.x
    }

    function r(t, e) {
        return t.position.x - e.position.x || t.position.y - e.position.y
    }

    function a(t, e) {
        var i = e.x - t.x, n = e.y - t.y;
        return Math.sqrt(i * i + n * n)
    }

    i.prototype.canFit = function (t) {
        return this.width >= t.width - 1 && this.height >= t.height - 1
    };
    var h = e.create("packery");
    h.Item = o;
    var u = h.prototype;
    u._create = function () {
        e.prototype._create.call(this), this.packer = new n, this.shiftPacker = new n, this.isEnabled = !0, this.dragItemCount = 0;
        var t = this;
        this.handleDraggabilly = {
            dragStart: function () {
                t.itemDragStart(this.element)
            }, dragMove: function () {
                t.itemDragMove(this.element, this.position.x, this.position.y)
            }, dragEnd: function () {
                t.itemDragEnd(this.element)
            }
        }, this.handleUIDraggable = {
            start: function (e, i) {
                i && t.itemDragStart(e.currentTarget)
            }, drag: function (e, i) {
                i && t.itemDragMove(e.currentTarget, i.position.left, i.position.top)
            }, stop: function (e, i) {
                i && t.itemDragEnd(e.currentTarget)
            }
        }
    }, u._resetLayout = function () {
        this.getSize(), this._getMeasurements();
        var t, e, i;
        this._getOption("horizontal") ? (t = 1 / 0, e = this.size.innerHeight + this.gutter, i = "rightwardTopToBottom") : (t = this.size.innerWidth + this.gutter, e = 1 / 0, i = "downwardLeftToRight"), this.packer.width = this.shiftPacker.width = t, this.packer.height = this.shiftPacker.height = e, this.packer.sortDirection = this.shiftPacker.sortDirection = i, this.packer.reset(), this.maxY = 0, this.maxX = 0
    }, u._getMeasurements = function () {
        this._getMeasurement("columnWidth", "width"), this._getMeasurement("rowHeight", "height"), this._getMeasurement("gutter", "width")
    }, u._getItemLayoutPosition = function (t) {
        if (this._setRectSize(t.element, t.rect), this.isShifting || this.dragItemCount > 0) {
            var e = this._getPackMethod();
            this.packer[e](t.rect)
        } else this.packer.pack(t.rect);
        return this._setMaxXY(t.rect), t.rect
    }, u.shiftLayout = function () {
        this.isShifting = !0, this.layout(), delete this.isShifting
    }, u._getPackMethod = function () {
        return this._getOption("horizontal") ? "rowPack" : "columnPack"
    }, u._setMaxXY = function (t) {
        this.maxX = Math.max(t.x + t.width, this.maxX), this.maxY = Math.max(t.y + t.height, this.maxY)
    }, u._setRectSize = function (e, i) {
        var n = t(e), o = n.outerWidth, s = n.outerHeight;
        (o || s) && (o = this._applyGridGutter(o, this.columnWidth), s = this._applyGridGutter(s, this.rowHeight)), i.width = Math.min(o, this.packer.width), i.height = Math.min(s, this.packer.height)
    }, u._applyGridGutter = function (t, e) {
        if (!e)return t + this.gutter;
        e += this.gutter;
        var i = t % e, n = i && 1 > i ? "round" : "ceil";
        return t = Math[n](t / e) * e
    }, u._getContainerSize = function () {
        return this._getOption("horizontal") ? {width: this.maxX - this.gutter} : {height: this.maxY - this.gutter}
    }, u._manageStamp = function (t) {
        var e, n = this.getItem(t);
        if (n && n.isPlacing)e = n.rect; else {
            var o = this._getElementOffset(t);
            e = new i({
                x: this._getOption("originLeft") ? o.left : o.right,
                y: this._getOption("originTop") ? o.top : o.bottom
            })
        }
        this._setRectSize(t, e), this.packer.placed(e), this._setMaxXY(e)
    }, u.sortItemsByPosition = function () {
        var t = this._getOption("horizontal") ? r : s;
        this.items.sort(t)
    }, u.fit = function (t, e, i) {
        var n = this.getItem(t);
        n && (this.stamp(n.element), n.enablePlacing(), this.updateShiftTargets(n), e = void 0 === e ? n.rect.x : e, i = void 0 === i ? n.rect.y : i, this.shift(n, e, i), this._bindFitEvents(n), n.moveTo(n.rect.x, n.rect.y), this.shiftLayout(), this.unstamp(n.element), this.sortItemsByPosition(), n.disablePlacing())
    }, u._bindFitEvents = function (t) {
        function e() {
            n++, 2 == n && i.dispatchEvent("fitComplete", null, [t])
        }

        var i = this, n = 0;
        t.once("layout", e), this.once("layoutComplete", e)
    }, u.resize = function () {
        this.isResizeBound && this.needsResizeLayout() && (this.options.shiftPercentResize ? this.resizeShiftPercentLayout() : this.layout())
    }, u.needsResizeLayout = function () {
        var e = t(this.element), i = this._getOption("horizontal") ? "innerHeight" : "innerWidth";
        return e[i] != this.size[i]
    }, u.resizeShiftPercentLayout = function () {
        var e = this._getItemsForLayout(this.items), i = this._getOption("horizontal"), n = i ? "y" : "x", o = i ? "height" : "width", s = i ? "rowHeight" : "columnWidth", r = i ? "innerHeight" : "innerWidth", a = this[s];
        if (a = a && a + this.gutter) {
            this._getMeasurements();
            var h = this[s] + this.gutter;
            e.forEach(function (t) {
                var e = Math.round(t.rect[n] / a);
                t.rect[n] = e * h
            })
        } else {
            var u = t(this.element)[r] + this.gutter, c = this.packer[o];
            e.forEach(function (t) {
                t.rect[n] = t.rect[n] / c * u
            })
        }
        this.shiftLayout()
    }, u.itemDragStart = function (t) {
        if (this.isEnabled) {
            this.stamp(t);
            var e = this.getItem(t);
            e && (e.enablePlacing(), e.showDropPlaceholder(), this.dragItemCount++, this.updateShiftTargets(e))
        }
    }, u.updateShiftTargets = function (t) {
        this.shiftPacker.reset(), this._getBoundingRect();
        var e = this._getOption("originLeft"), n = this._getOption("originTop");
        this.stamps.forEach(function (t) {
            var o = this.getItem(t);
            if (!o || !o.isPlacing) {
                var s = this._getElementOffset(t), r = new i({x: e ? s.left : s.right, y: n ? s.top : s.bottom});
                this._setRectSize(t, r), this.shiftPacker.placed(r)
            }
        }, this);
        var o = this._getOption("horizontal"), s = o ? "rowHeight" : "columnWidth", r = o ? "height" : "width";
        this.shiftTargetKeys = [], this.shiftTargets = [];
        var a, h = this[s];
        if (h = h && h + this.gutter) {
            var u = Math.ceil(t.rect[r] / h), c = Math.floor((this.shiftPacker[r] + this.gutter) / h);
            a = (c - u) * h;
            for (var d = 0; c > d; d++)this._addShiftTarget(d * h, 0, a)
        } else a = this.shiftPacker[r] + this.gutter - t.rect[r], this._addShiftTarget(0, 0, a);
        var f = this._getItemsForLayout(this.items), l = this._getPackMethod();
        f.forEach(function (t) {
            var e = t.rect;
            this._setRectSize(t.element, e), this.shiftPacker[l](e), this._addShiftTarget(e.x, e.y, a);
            var i = o ? e.x + e.width : e.x, n = o ? e.y : e.y + e.height;
            if (this._addShiftTarget(i, n, a), h)for (var s = Math.round(e[r] / h), u = 1; s > u; u++) {
                var c = o ? i : e.x + h * u, d = o ? e.y + h * u : n;
                this._addShiftTarget(c, d, a)
            }
        }, this)
    }, u._addShiftTarget = function (t, e, i) {
        var n = this._getOption("horizontal") ? e : t;
        if (!(0 !== n && n > i)) {
            var o = t + "," + e, s = -1 != this.shiftTargetKeys.indexOf(o);
            s || (this.shiftTargetKeys.push(o), this.shiftTargets.push({x: t, y: e}))
        }
    }, u.shift = function (t, e, i) {
        var n, o = 1 / 0, s = {x: e, y: i};
        this.shiftTargets.forEach(function (t) {
            var e = a(t, s);
            o > e && (n = t, o = e)
        }), t.rect.x = n.x, t.rect.y = n.y
    };
    var c = 120;
    u.itemDragMove = function (t, e, i) {
        function n() {
            s.shift(o, e, i), o.positionDropPlaceholder(), s.layout()
        }

        var o = this.isEnabled && this.getItem(t);
        if (o) {
            e -= this.size.paddingLeft, i -= this.size.paddingTop;
            var s = this, r = new Date;
            this._itemDragTime && r - this._itemDragTime < c ? (clearTimeout(this.dragTimeout), this.dragTimeout = setTimeout(n, c)) : (n(), this._itemDragTime = r)
        }
    }, u.itemDragEnd = function (t) {
        function e() {
            n++, 2 == n && (i.element.classList.remove("is-positioning-post-drag"), i.hideDropPlaceholder(), o.dispatchEvent("dragItemPositioned", null, [i]))
        }

        var i = this.isEnabled && this.getItem(t);
        if (i) {
            clearTimeout(this.dragTimeout), i.element.classList.add("is-positioning-post-drag");
            var n = 0, o = this;
            i.once("layout", e), this.once("layoutComplete", e), i.moveTo(i.rect.x, i.rect.y), this.layout(), this.dragItemCount = Math.max(0, this.dragItemCount - 1),
                this.sortItemsByPosition(), i.disablePlacing(), this.unstamp(i.element)
        }
    }, u.bindDraggabillyEvents = function (t) {
        this._bindDraggabillyEvents(t, "on")
    }, u.unbindDraggabillyEvents = function (t) {
        this._bindDraggabillyEvents(t, "off")
    }, u._bindDraggabillyEvents = function (t, e) {
        var i = this.handleDraggabilly;
        t[e]("dragStart", i.dragStart), t[e]("dragMove", i.dragMove), t[e]("dragEnd", i.dragEnd)
    }, u.bindUIDraggableEvents = function (t) {
        this._bindUIDraggableEvents(t, "on")
    }, u.unbindUIDraggableEvents = function (t) {
        this._bindUIDraggableEvents(t, "off")
    }, u._bindUIDraggableEvents = function (t, e) {
        var i = this.handleUIDraggable;
        t[e]("dragstart", i.start)[e]("drag", i.drag)[e]("dragstop", i.stop)
    };
    var d = u.destroy;
    return u.destroy = function () {
        d.apply(this, arguments), this.isEnabled = !1
    }, h.Rect = i, h.Packer = n, h
});


/* Tooltipster v3.3.0 */
;(function (e, t, n) {
    function s(t, n) {
        this.bodyOverflowX;
        this.callbacks = {hide: [], show: []};
        this.checkInterval = null;
        this.Content;
        this.$el = e(t);
        this.$elProxy;
        this.elProxyPosition;
        this.enabled = true;
        this.options = e.extend({}, i, n);
        this.mouseIsOverProxy = false;
        this.namespace = "tooltipster-" + Math.round(Math.random() * 1e5);
        this.Status = "hidden";
        this.timerHide = null;
        this.timerShow = null;
        this.$tooltip;
        this.options.iconTheme = this.options.iconTheme.replace(".", "");
        this.options.theme = this.options.theme.replace(".", "");
        this._init()
    }

    function o(t, n) {
        var r = true;
        e.each(t, function (e, i) {
            if (typeof n[e] === "undefined" || t[e] !== n[e]) {
                r = false;
                return false
            }
        });
        return r
    }

    function f() {
        return !a && u
    }

    function l() {
        var e = n.body || n.documentElement, t = e.style, r = "transition";
        if (typeof t[r] == "string") {
            return true
        }
        v = ["Moz", "Webkit", "Khtml", "O", "ms"], r = r.charAt(0).toUpperCase() + r.substr(1);
        for (var i = 0; i < v.length; i++) {
            if (typeof t[v[i] + r] == "string") {
                return true
            }
        }
        return false
    }

    var r = "tooltipster", i = {
        animation: "fade",
        arrow: true,
        arrowColor: "",
        autoClose: true,
        content: null,
        contentAsHTML: false,
        contentCloning: true,
        debug: true,
        delay: 200,
        minWidth: 0,
        maxWidth: null,
        functionInit: function (e, t) {
        },
        functionBefore: function (e, t) {
            t()
        },
        functionReady: function (e, t) {
        },
        functionAfter: function (e) {
        },
        hideOnClick: false,
        icon: "(?)",
        iconCloning: true,
        iconDesktop: false,
        iconTouch: false,
        iconTheme: "tooltipster-icon",
        interactive: false,
        interactiveTolerance: 350,
        multiple: false,
        offsetX: 0,
        offsetY: 0,
        onlyOne: false,
        position: "top",
        positionTracker: false,
        positionTrackerCallback: function (e) {
            if (this.option("trigger") == "hover" && this.option("autoClose")) {
                this.hide()
            }
        },
        restoration: "current",
        speed: 350,
        timer: 0,
        theme: "tooltipster-default",
        touchDevices: true,
        trigger: "hover",
        updateAnimation: true
    };
    s.prototype = {
        _init: function () {
            var t = this;
            if (n.querySelector) {
                var r = null;
                if (t.$el.data("tooltipster-initialTitle") === undefined) {
                    r = t.$el.attr("title");
                    if (r === undefined)r = null;
                    t.$el.data("tooltipster-initialTitle", r)
                }
                if (t.options.content !== null) {
                    t._content_set(t.options.content)
                } else {
                    t._content_set(r)
                }
                var i = t.options.functionInit.call(t.$el, t.$el, t.Content);
                if (typeof i !== "undefined")t._content_set(i);
                t.$el.removeAttr("title").addClass("tooltipstered");
                if (!u && t.options.iconDesktop || u && t.options.iconTouch) {
                    if (typeof t.options.icon === "string") {
                        t.$elProxy = e('<span class="' + t.options.iconTheme + '"></span>');
                        t.$elProxy.text(t.options.icon)
                    } else {
                        if (t.options.iconCloning)t.$elProxy = t.options.icon.clone(true); else t.$elProxy = t.options.icon
                    }
                    t.$elProxy.insertAfter(t.$el)
                } else {
                    t.$elProxy = t.$el
                }
                if (t.options.trigger == "hover") {
                    t.$elProxy.on("mouseenter." + t.namespace, function () {
                        if (!f() || t.options.touchDevices) {
                            t.mouseIsOverProxy = true;
                            t._show()
                        }
                    }).on("mouseleave." + t.namespace, function () {
                        if (!f() || t.options.touchDevices) {
                            t.mouseIsOverProxy = false
                        }
                    });
                    if (u && t.options.touchDevices) {
                        t.$elProxy.on("touchstart." + t.namespace, function () {
                            t._showNow()
                        })
                    }
                } else if (t.options.trigger == "click") {
                    t.$elProxy.on("click." + t.namespace, function () {
                        if (!f() || t.options.touchDevices) {
                            t._show()
                        }
                    })
                }
            }
        }, _show: function () {
            var e = this;
            if (e.Status != "shown" && e.Status != "appearing") {
                if (e.options.delay) {
                    e.timerShow = setTimeout(function () {
                        if (e.options.trigger == "click" || e.options.trigger == "hover" && e.mouseIsOverProxy) {
                            e._showNow()
                        }
                    }, e.options.delay)
                } else e._showNow()
            }
        }, _showNow: function (n) {
            var r = this;
            r.options.functionBefore.call(r.$el, r.$el, function () {
                if (r.enabled && r.Content !== null) {
                    if (n)r.callbacks.show.push(n);
                    r.callbacks.hide = [];
                    clearTimeout(r.timerShow);
                    r.timerShow = null;
                    clearTimeout(r.timerHide);
                    r.timerHide = null;
                    if (r.options.onlyOne) {
                        e(".tooltipstered").not(r.$el).each(function (t, n) {
                            var r = e(n), i = r.data("tooltipster-ns");
                            e.each(i, function (e, t) {
                                var n = r.data(t), i = n.status(), s = n.option("autoClose");
                                if (i !== "hidden" && i !== "disappearing" && s) {
                                    n.hide()
                                }
                            })
                        })
                    }
                    var i = function () {
                        r.Status = "shown";
                        e.each(r.callbacks.show, function (e, t) {
                            t.call(r.$el)
                        });
                        r.callbacks.show = []
                    };
                    if (r.Status !== "hidden") {
                        var s = 0;
                        if (r.Status === "disappearing") {
                            r.Status = "appearing";
                            if (l()) {
                                r.$tooltip.clearQueue().removeClass("tooltipster-dying").addClass("tooltipster-" + r.options.animation + "-show");
                                if (r.options.speed > 0)r.$tooltip.delay(r.options.speed);
                                r.$tooltip.queue(i)
                            } else {
                                r.$tooltip.stop().fadeIn(i)
                            }
                        } else if (r.Status === "shown") {
                            i()
                        }
                    } else {
                        r.Status = "appearing";
                        var s = r.options.speed;
                        r.bodyOverflowX = e("body").css("overflow-x");
                        e("body").css("overflow-x", "hidden");
                        var o = "tooltipster-" + r.options.animation, a = "-webkit-transition-duration: " + r.options.speed + "ms; -webkit-animation-duration: " + r.options.speed + "ms; -moz-transition-duration: " + r.options.speed + "ms; -moz-animation-duration: " + r.options.speed + "ms; -o-transition-duration: " + r.options.speed + "ms; -o-animation-duration: " + r.options.speed + "ms; -ms-transition-duration: " + r.options.speed + "ms; -ms-animation-duration: " + r.options.speed + "ms; transition-duration: " + r.options.speed + "ms; animation-duration: " + r.options.speed + "ms;", f = r.options.minWidth ? "min-width:" + Math.round(r.options.minWidth) + "px;" : "", c = r.options.maxWidth ? "max-width:" + Math.round(r.options.maxWidth) + "px;" : "", h = r.options.interactive ? "pointer-events: auto;" : "";
                        r.$tooltip = e('<div class="tooltipster-base ' + r.options.theme + '" style="' + f + " " + c + " " + h + " " + a + '"><div class="tooltipster-content"></div></div>');
                        if (l())r.$tooltip.addClass(o);
                        r._content_insert();
                        r.$tooltip.appendTo("body");
                        r.reposition();
                        r.options.functionReady.call(r.$el, r.$el, r.$tooltip);
                        if (l()) {
                            r.$tooltip.addClass(o + "-show");
                            if (r.options.speed > 0)r.$tooltip.delay(r.options.speed);
                            r.$tooltip.queue(i)
                        } else {
                            r.$tooltip.css("display", "none").fadeIn(r.options.speed, i)
                        }
                        r._interval_set();
                        e(t).on("scroll." + r.namespace + " resize." + r.namespace, function () {
                            r.reposition()
                        });
                        if (r.options.autoClose) {
                            e("body").off("." + r.namespace);
                            if (r.options.trigger == "hover") {
                                if (u) {
                                    setTimeout(function () {
                                        e("body").on("touchstart." + r.namespace, function () {
                                            r.hide()
                                        })
                                    }, 0)
                                }
                                if (r.options.interactive) {
                                    if (u) {
                                        r.$tooltip.on("touchstart." + r.namespace, function (e) {
                                            e.stopPropagation()
                                        })
                                    }
                                    var p = null;
                                    r.$elProxy.add(r.$tooltip).on("mouseleave." + r.namespace + "-autoClose", function () {
                                        clearTimeout(p);
                                        p = setTimeout(function () {
                                            r.hide()
                                        }, r.options.interactiveTolerance)
                                    }).on("mouseenter." + r.namespace + "-autoClose", function () {
                                        clearTimeout(p)
                                    })
                                } else {
                                    r.$elProxy.on("mouseleave." + r.namespace + "-autoClose", function () {
                                        r.hide()
                                    })
                                }
                                if (r.options.hideOnClick) {
                                    r.$elProxy.on("click." + r.namespace + "-autoClose", function () {
                                        r.hide()
                                    })
                                }
                            } else if (r.options.trigger == "click") {
                                setTimeout(function () {
                                    e("body").on("click." + r.namespace + " touchstart." + r.namespace, function () {
                                        r.hide()
                                    })
                                }, 0);
                                if (r.options.interactive) {
                                    r.$tooltip.on("click." + r.namespace + " touchstart." + r.namespace, function (e) {
                                        e.stopPropagation()
                                    })
                                }
                            }
                        }
                    }
                    if (r.options.timer > 0) {
                        r.timerHide = setTimeout(function () {
                            r.timerHide = null;
                            r.hide()
                        }, r.options.timer + s)
                    }
                }
            })
        }, _interval_set: function () {
            var t = this;
            t.checkInterval = setInterval(function () {
                if (e("body").find(t.$el).length === 0 || e("body").find(t.$elProxy).length === 0 || t.Status == "hidden" || e("body").find(t.$tooltip).length === 0) {
                    if (t.Status == "shown" || t.Status == "appearing")t.hide();
                    t._interval_cancel()
                } else {
                    if (t.options.positionTracker) {
                        var n = t._repositionInfo(t.$elProxy), r = false;
                        if (o(n.dimension, t.elProxyPosition.dimension)) {
                            if (t.$elProxy.css("position") === "fixed") {
                                if (o(n.position, t.elProxyPosition.position))r = true
                            } else {
                                if (o(n.offset, t.elProxyPosition.offset))r = true
                            }
                        }
                        if (!r) {
                            t.reposition();
                            t.options.positionTrackerCallback.call(t, t.$el)
                        }
                    }
                }
            }, 200)
        }, _interval_cancel: function () {
            clearInterval(this.checkInterval);
            this.checkInterval = null
        }, _content_set: function (e) {
            if (typeof e === "object" && e !== null && this.options.contentCloning) {
                e = e.clone(true)
            }
            this.Content = e
        }, _content_insert: function () {
            var e = this, t = this.$tooltip.find(".tooltipster-content");
            if (typeof e.Content === "string" && !e.options.contentAsHTML) {
                t.text(e.Content)
            } else {
                t.empty().append(e.Content)
            }
        }, _update: function (e) {
            var t = this;
            t._content_set(e);
            if (t.Content !== null) {
                if (t.Status !== "hidden") {
                    t._content_insert();
                    t.reposition();
                    if (t.options.updateAnimation) {
                        if (l()) {
                            t.$tooltip.css({
                                width: "",
                                "-webkit-transition": "all " + t.options.speed + "ms, width 0ms, height 0ms, left 0ms, top 0ms",
                                "-moz-transition": "all " + t.options.speed + "ms, width 0ms, height 0ms, left 0ms, top 0ms",
                                "-o-transition": "all " + t.options.speed + "ms, width 0ms, height 0ms, left 0ms, top 0ms",
                                "-ms-transition": "all " + t.options.speed + "ms, width 0ms, height 0ms, left 0ms, top 0ms",
                                transition: "all " + t.options.speed + "ms, width 0ms, height 0ms, left 0ms, top 0ms"
                            }).addClass("tooltipster-content-changing");
                            setTimeout(function () {
                                if (t.Status != "hidden") {
                                    t.$tooltip.removeClass("tooltipster-content-changing");
                                    setTimeout(function () {
                                        if (t.Status !== "hidden") {
                                            t.$tooltip.css({
                                                "-webkit-transition": t.options.speed + "ms",
                                                "-moz-transition": t.options.speed + "ms",
                                                "-o-transition": t.options.speed + "ms",
                                                "-ms-transition": t.options.speed + "ms",
                                                transition: t.options.speed + "ms"
                                            })
                                        }
                                    }, t.options.speed)
                                }
                            }, t.options.speed)
                        } else {
                            t.$tooltip.fadeTo(t.options.speed, .5, function () {
                                if (t.Status != "hidden") {
                                    t.$tooltip.fadeTo(t.options.speed, 1)
                                }
                            })
                        }
                    }
                }
            } else {
                t.hide()
            }
        }, _repositionInfo: function (e) {
            return {
                dimension: {height: e.outerHeight(false), width: e.outerWidth(false)},
                offset: e.offset(),
                position: {left: parseInt(e.css("left")), top: parseInt(e.css("top"))}
            }
        }, hide: function (n) {
            var r = this;
            if (n)r.callbacks.hide.push(n);
            r.callbacks.show = [];
            clearTimeout(r.timerShow);
            r.timerShow = null;
            clearTimeout(r.timerHide);
            r.timerHide = null;
            var i = function () {
                e.each(r.callbacks.hide, function (e, t) {
                    t.call(r.$el)
                });
                r.callbacks.hide = []
            };
            if (r.Status == "shown" || r.Status == "appearing") {
                r.Status = "disappearing";
                var s = function () {
                    r.Status = "hidden";
                    if (typeof r.Content == "object" && r.Content !== null) {
                        r.Content.detach()
                    }
                    r.$tooltip.remove();
                    r.$tooltip = null;
                    e(t).off("." + r.namespace);
                    e("body").off("." + r.namespace).css("overflow-x", r.bodyOverflowX);
                    e("body").off("." + r.namespace);
                    r.$elProxy.off("." + r.namespace + "-autoClose");
                    r.options.functionAfter.call(r.$el, r.$el);
                    i()
                };
                if (l()) {
                    r.$tooltip.clearQueue().removeClass("tooltipster-" + r.options.animation + "-show").addClass("tooltipster-dying");
                    if (r.options.speed > 0)r.$tooltip.delay(r.options.speed);
                    r.$tooltip.queue(s)
                } else {
                    r.$tooltip.stop().fadeOut(r.options.speed, s)
                }
            } else if (r.Status == "hidden") {
                i()
            }
            return r
        }, show: function (e) {
            this._showNow(e);
            return this
        }, update: function (e) {
            return this.content(e)
        }, content: function (e) {
            if (typeof e === "undefined") {
                return this.Content
            } else {
                this._update(e);
                return this
            }
        }, reposition: function () {
            var n = this;
            if (e("body").find(n.$tooltip).length !== 0) {
                n.$tooltip.css("width", "");
                n.elProxyPosition = n._repositionInfo(n.$elProxy);
                var r = null, i = e(t).width(), s = n.elProxyPosition, o = n.$tooltip.outerWidth(false), u = n.$tooltip.innerWidth() + 1, a = n.$tooltip.outerHeight(false);
                if (n.$elProxy.is("area")) {
                    var f = n.$elProxy.attr("shape"), l = n.$elProxy.parent().attr("name"), c = e('img[usemap="#' + l + '"]'), h = c.offset().left, p = c.offset().top, d = n.$elProxy.attr("coords") !== undefined ? n.$elProxy.attr("coords").split(",") : undefined;
                    if (f == "circle") {
                        var v = parseInt(d[0]), m = parseInt(d[1]), g = parseInt(d[2]);
                        s.dimension.height = g * 2;
                        s.dimension.width = g * 2;
                        s.offset.top = p + m - g;
                        s.offset.left = h + v - g
                    } else if (f == "rect") {
                        var v = parseInt(d[0]), m = parseInt(d[1]), y = parseInt(d[2]), b = parseInt(d[3]);
                        s.dimension.height = b - m;
                        s.dimension.width = y - v;
                        s.offset.top = p + m;
                        s.offset.left = h + v
                    } else if (f == "poly") {
                        var w = [], E = [], S = 0, x = 0, T = 0, N = 0, C = "even";
                        for (var k = 0; k < d.length; k++) {
                            var L = parseInt(d[k]);
                            if (C == "even") {
                                if (L > T) {
                                    T = L;
                                    if (k === 0) {
                                        S = T
                                    }
                                }
                                if (L < S) {
                                    S = L
                                }
                                C = "odd"
                            } else {
                                if (L > N) {
                                    N = L;
                                    if (k == 1) {
                                        x = N
                                    }
                                }
                                if (L < x) {
                                    x = L
                                }
                                C = "even"
                            }
                        }
                        s.dimension.height = N - x;
                        s.dimension.width = T - S;
                        s.offset.top = p + x;
                        s.offset.left = h + S
                    } else {
                        s.dimension.height = c.outerHeight(false);
                        s.dimension.width = c.outerWidth(false);
                        s.offset.top = p;
                        s.offset.left = h
                    }
                }
                var A = 0, O = 0, M = 0, _ = parseInt(n.options.offsetY), D = parseInt(n.options.offsetX), P = n.options.position;

                function H() {
                    var n = e(t).scrollLeft();
                    if (A - n < 0) {
                        r = A - n;
                        A = n
                    }
                    if (A + o - n > i) {
                        r = A - (i + n - o);
                        A = i + n - o
                    }
                }

                function B(n, r) {
                    if (s.offset.top - e(t).scrollTop() - a - _ - 12 < 0 && r.indexOf("top") > -1) {
                        P = n
                    }
                    if (s.offset.top + s.dimension.height + a + 12 + _ > e(t).scrollTop() + e(t).height() && r.indexOf("bottom") > -1) {
                        P = n;
                        M = s.offset.top - a - _ - 12
                    }
                }

                if (P == "top") {
                    var j = s.offset.left + o - (s.offset.left + s.dimension.width);
                    A = s.offset.left + D - j / 2;
                    M = s.offset.top - a - _ - 12;
                    H();
                    B("bottom", "top")
                }
                if (P == "top-left") {
                    A = s.offset.left + D;
                    M = s.offset.top - a - _ - 12;
                    H();
                    B("bottom-left", "top-left")
                }
                if (P == "top-right") {
                    A = s.offset.left + s.dimension.width + D - o;
                    M = s.offset.top - a - _ - 12;
                    H();
                    B("bottom-right", "top-right")
                }
                if (P == "bottom") {
                    var j = s.offset.left + o - (s.offset.left + s.dimension.width);
                    A = s.offset.left - j / 2 + D;
                    M = s.offset.top + s.dimension.height + _ + 12;
                    H();
                    B("top", "bottom")
                }
                if (P == "bottom-left") {
                    A = s.offset.left + D;
                    M = s.offset.top + s.dimension.height + _ + 12;
                    H();
                    B("top-left", "bottom-left")
                }
                if (P == "bottom-right") {
                    A = s.offset.left + s.dimension.width + D - o;
                    M = s.offset.top + s.dimension.height + _ + 12;
                    H();
                    B("top-right", "bottom-right")
                }
                if (P == "left") {
                    A = s.offset.left - D - o - 12;
                    O = s.offset.left + D + s.dimension.width + 12;
                    var F = s.offset.top + a - (s.offset.top + s.dimension.height);
                    M = s.offset.top - F / 2 - _;
                    if (A < 0 && O + o > i) {
                        var I = parseFloat(n.$tooltip.css("border-width")) * 2, q = o + A - I;
                        n.$tooltip.css("width", q + "px");
                        a = n.$tooltip.outerHeight(false);
                        A = s.offset.left - D - q - 12 - I;
                        F = s.offset.top + a - (s.offset.top + s.dimension.height);
                        M = s.offset.top - F / 2 - _
                    } else if (A < 0) {
                        A = s.offset.left + D + s.dimension.width + 12;
                        r = "left"
                    }
                }
                if (P == "right") {
                    A = s.offset.left + D + s.dimension.width + 12;
                    O = s.offset.left - D - o - 12;
                    var F = s.offset.top + a - (s.offset.top + s.dimension.height);
                    M = s.offset.top - F / 2 - _;
                    if (A + o > i && O < 0) {
                        var I = parseFloat(n.$tooltip.css("border-width")) * 2, q = i - A - I;
                        n.$tooltip.css("width", q + "px");
                        a = n.$tooltip.outerHeight(false);
                        F = s.offset.top + a - (s.offset.top + s.dimension.height);
                        M = s.offset.top - F / 2 - _
                    } else if (A + o > i) {
                        A = s.offset.left - D - o - 12;
                        r = "right"
                    }
                }
                if (n.options.arrow) {
                    var R = "tooltipster-arrow-" + P;
                    if (n.options.arrowColor.length < 1) {
                        var U = n.$tooltip.css("background-color")
                    } else {
                        var U = n.options.arrowColor
                    }
                    if (!r) {
                        r = ""
                    } else if (r == "left") {
                        R = "tooltipster-arrow-right";
                        r = ""
                    } else if (r == "right") {
                        R = "tooltipster-arrow-left";
                        r = ""
                    } else {
                        r = "left:" + Math.round(r) + "px;"
                    }
                    if (P == "top" || P == "top-left" || P == "top-right") {
                        var z = parseFloat(n.$tooltip.css("border-bottom-width")), W = n.$tooltip.css("border-bottom-color")
                    } else if (P == "bottom" || P == "bottom-left" || P == "bottom-right") {
                        var z = parseFloat(n.$tooltip.css("border-top-width")), W = n.$tooltip.css("border-top-color")
                    } else if (P == "left") {
                        var z = parseFloat(n.$tooltip.css("border-right-width")), W = n.$tooltip.css("border-right-color")
                    } else if (P == "right") {
                        var z = parseFloat(n.$tooltip.css("border-left-width")), W = n.$tooltip.css("border-left-color")
                    } else {
                        var z = parseFloat(n.$tooltip.css("border-bottom-width")), W = n.$tooltip.css("border-bottom-color")
                    }
                    if (z > 1) {
                        z++
                    }
                    var X = "";
                    if (z !== 0) {
                        var V = "", J = "border-color: " + W + ";";
                        if (R.indexOf("bottom") !== -1) {
                            V = "margin-top: -" + Math.round(z) + "px;"
                        } else if (R.indexOf("top") !== -1) {
                            V = "margin-bottom: -" + Math.round(z) + "px;"
                        } else if (R.indexOf("left") !== -1) {
                            V = "margin-right: -" + Math.round(z) + "px;"
                        } else if (R.indexOf("right") !== -1) {
                            V = "margin-left: -" + Math.round(z) + "px;"
                        }
                        X = '<span class="tooltipster-arrow-border" style="' + V + " " + J + ';"></span>'
                    }
                    n.$tooltip.find(".tooltipster-arrow").remove();
                    var K = '<div class="' + R + ' tooltipster-arrow" style="' + r + '">' + X + '<span style="border-color:' + U + ';"></span></div>';
                    n.$tooltip.append(K)
                }
                n.$tooltip.css({top: Math.round(M) + "px", left: Math.round(A) + "px"})
            }
            return n
        }, enable: function () {
            this.enabled = true;
            return this
        }, disable: function () {
            this.hide();
            this.enabled = false;
            return this
        }, destroy: function () {
            var t = this;
            t.hide();
            if (t.$el[0] !== t.$elProxy[0]) {
                t.$elProxy.remove()
            }
            t.$el.removeData(t.namespace).off("." + t.namespace);
            var n = t.$el.data("tooltipster-ns");
            if (n.length === 1) {
                var r = null;
                if (t.options.restoration === "previous") {
                    r = t.$el.data("tooltipster-initialTitle")
                } else if (t.options.restoration === "current") {
                    r = typeof t.Content === "string" ? t.Content : e("<div></div>").append(t.Content).html()
                }
                if (r) {
                    t.$el.attr("title", r)
                }
                t.$el.removeClass("tooltipstered").removeData("tooltipster-ns").removeData("tooltipster-initialTitle")
            } else {
                n = e.grep(n, function (e, n) {
                    return e !== t.namespace
                });
                t.$el.data("tooltipster-ns", n)
            }
            return t
        }, elementIcon: function () {
            return this.$el[0] !== this.$elProxy[0] ? this.$elProxy[0] : undefined
        }, elementTooltip: function () {
            return this.$tooltip ? this.$tooltip[0] : undefined
        }, option: function (e, t) {
            if (typeof t == "undefined")return this.options[e]; else {
                this.options[e] = t;
                return this
            }
        }, status: function () {
            return this.Status
        }
    };
    e.fn[r] = function () {
        var t = arguments;
        if (this.length === 0) {
            if (typeof t[0] === "string") {
                var n = true;
                switch (t[0]) {
                    case"setDefaults":
                        e.extend(i, t[1]);
                        break;
                    default:
                        n = false;
                        break
                }
                if (n)return true; else return this
            } else {
                return this
            }
        } else {
            if (typeof t[0] === "string") {
                var r = "#*$~&";
                this.each(function () {
                    var n = e(this).data("tooltipster-ns"), i = n ? e(this).data(n[0]) : null;
                    if (i) {
                        if (typeof i[t[0]] === "function") {
                            var s = i[t[0]](t[1], t[2])
                        } else {
                            throw new Error('Unknown method .tooltipster("' + t[0] + '")')
                        }
                        if (s !== i) {
                            r = s;
                            return false
                        }
                    } else {
                        throw new Error("You called Tooltipster's \"" + t[0] + '" method on an uninitialized element')
                    }
                });
                return r !== "#*$~&" ? r : this
            } else {
                var o = [], u = t[0] && typeof t[0].multiple !== "undefined", a = u && t[0].multiple || !u && i.multiple, f = t[0] && typeof t[0].debug !== "undefined", l = f && t[0].debug || !f && i.debug;
                this.each(function () {
                    var n = false, r = e(this).data("tooltipster-ns"), i = null;
                    if (!r) {
                        n = true
                    } else if (a) {
                        n = true
                    } else if (l) {
                        console.log('Tooltipster: one or more tooltips are already attached to this element: ignoring. Use the "multiple" option to attach more tooltips.')
                    }
                    if (n) {
                        i = new s(this, t[0]);
                        if (!r)r = [];
                        r.push(i.namespace);
                        e(this).data("tooltipster-ns", r);
                        e(this).data(i.namespace, i)
                    }
                    o.push(i)
                });
                if (a)return o; else return this
            }
        }
    };
    var u = !!("ontouchstart" in t);
    var a = false;
    e("body").one("mousemove", function () {
        a = true
    })
})(jQuery, window, document);

/*!
 * imagesLoaded PACKAGED v4.1.0
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

!function (t, e) {
    "function" == typeof define && define.amd ? define("ev-emitter/ev-emitter", e) : "object" == typeof module && module.exports ? module.exports = e() : t.EvEmitter = e()
}(this, function () {
    function t() {
    }

    var e = t.prototype;
    return e.on = function (t, e) {
        if (t && e) {
            var i = this._events = this._events || {}, n = i[t] = i[t] || [];
            return -1 == n.indexOf(e) && n.push(e), this
        }
    }, e.once = function (t, e) {
        if (t && e) {
            this.on(t, e);
            var i = this._onceEvents = this._onceEvents || {}, n = i[t] = i[t] || [];
            return n[e] = !0, this
        }
    }, e.off = function (t, e) {
        var i = this._events && this._events[t];
        if (i && i.length) {
            var n = i.indexOf(e);
            return -1 != n && i.splice(n, 1), this
        }
    }, e.emitEvent = function (t, e) {
        var i = this._events && this._events[t];
        if (i && i.length) {
            var n = 0, o = i[n];
            e = e || [];
            for (var r = this._onceEvents && this._onceEvents[t]; o;) {
                var s = r && r[o];
                s && (this.off(t, o), delete r[o]), o.apply(this, e), n += s ? 0 : 1, o = i[n]
            }
            return this
        }
    }, t
}), function (t, e) {
    "use strict";
    "function" == typeof define && define.amd ? define(["ev-emitter/ev-emitter"], function (i) {
        return e(t, i)
    }) : "object" == typeof module && module.exports ? module.exports = e(t, require("ev-emitter")) : t.imagesLoaded = e(t, t.EvEmitter)
}(window, function (t, e) {
    function i(t, e) {
        for (var i in e)t[i] = e[i];
        return t
    }

    function n(t) {
        var e = [];
        if (Array.isArray(t))e = t; else if ("number" == typeof t.length)for (var i = 0; i < t.length; i++)e.push(t[i]); else e.push(t);
        return e
    }

    function o(t, e, r) {
        return this instanceof o ? ("string" == typeof t && (t = document.querySelectorAll(t)), this.elements = n(t), this.options = i({}, this.options), "function" == typeof e ? r = e : i(this.options, e), r && this.on("always", r), this.getImages(), h && (this.jqDeferred = new h.Deferred), void setTimeout(function () {
            this.check()
        }.bind(this))) : new o(t, e, r)
    }

    function r(t) {
        this.img = t
    }

    function s(t, e) {
        this.url = t, this.element = e, this.img = new Image
    }

    var h = t.jQuery, a = t.console;
    o.prototype = Object.create(e.prototype), o.prototype.options = {}, o.prototype.getImages = function () {
        this.images = [], this.elements.forEach(this.addElementImages, this)
    }, o.prototype.addElementImages = function (t) {
        "IMG" == t.nodeName && this.addImage(t), this.options.background === !0 && this.addElementBackgroundImages(t);
        var e = t.nodeType;
        if (e && d[e]) {
            for (var i = t.querySelectorAll("img"), n = 0; n < i.length; n++) {
                var o = i[n];
                this.addImage(o)
            }
            if ("string" == typeof this.options.background) {
                var r = t.querySelectorAll(this.options.background);
                for (n = 0; n < r.length; n++) {
                    var s = r[n];
                    this.addElementBackgroundImages(s)
                }
            }
        }
    };
    var d = {1: !0, 9: !0, 11: !0};
    return o.prototype.addElementBackgroundImages = function (t) {
        var e = getComputedStyle(t);
        if (e)for (var i = /url\((['"])?(.*?)\1\)/gi, n = i.exec(e.backgroundImage); null !== n;) {
            var o = n && n[2];
            o && this.addBackground(o, t), n = i.exec(e.backgroundImage)
        }
    }, o.prototype.addImage = function (t) {
        var e = new r(t);
        this.images.push(e)
    }, o.prototype.addBackground = function (t, e) {
        var i = new s(t, e);
        this.images.push(i)
    }, o.prototype.check = function () {
        function t(t, i, n) {
            setTimeout(function () {
                e.progress(t, i, n)
            })
        }

        var e = this;
        return this.progressedCount = 0, this.hasAnyBroken = !1, this.images.length ? void this.images.forEach(function (e) {
            e.once("progress", t), e.check()
        }) : void this.complete()
    }, o.prototype.progress = function (t, e, i) {
        this.progressedCount++, this.hasAnyBroken = this.hasAnyBroken || !t.isLoaded, this.emitEvent("progress", [this, t, e]), this.jqDeferred && this.jqDeferred.notify && this.jqDeferred.notify(this, t), this.progressedCount == this.images.length && this.complete(), this.options.debug && a && a.log("progress: " + i, t, e)
    }, o.prototype.complete = function () {
        var t = this.hasAnyBroken ? "fail" : "done";
        if (this.isComplete = !0, this.emitEvent(t, [this]), this.emitEvent("always", [this]), this.jqDeferred) {
            var e = this.hasAnyBroken ? "reject" : "resolve";
            this.jqDeferred[e](this)
        }
    }, r.prototype = Object.create(e.prototype), r.prototype.check = function () {
        var t = this.getIsImageComplete();
        return t ? void this.confirm(0 !== this.img.naturalWidth, "naturalWidth") : (this.proxyImage = new Image, this.proxyImage.addEventListener("load", this), this.proxyImage.addEventListener("error", this), this.img.addEventListener("load", this), this.img.addEventListener("error", this), void(this.proxyImage.src = this.img.src))
    }, r.prototype.getIsImageComplete = function () {
        return this.img.complete && void 0 !== this.img.naturalWidth
    }, r.prototype.confirm = function (t, e) {
        this.isLoaded = t, this.emitEvent("progress", [this, this.img, e])
    }, r.prototype.handleEvent = function (t) {
        var e = "on" + t.type;
        this[e] && this[e](t)
    }, r.prototype.onload = function () {
        this.confirm(!0, "onload"), this.unbindEvents()
    }, r.prototype.onerror = function () {
        this.confirm(!1, "onerror"), this.unbindEvents()
    }, r.prototype.unbindEvents = function () {
        this.proxyImage.removeEventListener("load", this), this.proxyImage.removeEventListener("error", this), this.img.removeEventListener("load", this), this.img.removeEventListener("error", this)
    }, s.prototype = Object.create(r.prototype), s.prototype.check = function () {
        this.img.addEventListener("load", this), this.img.addEventListener("error", this), this.img.src = this.url;
        var t = this.getIsImageComplete();
        t && (this.confirm(0 !== this.img.naturalWidth, "naturalWidth"), this.unbindEvents())
    }, s.prototype.unbindEvents = function () {
        this.img.removeEventListener("load", this), this.img.removeEventListener("error", this)
    }, s.prototype.confirm = function (t, e) {
        this.isLoaded = t, this.emitEvent("progress", [this, this.element, e])
    }, o.makeJQueryPlugin = function (e) {
        e = e || t.jQuery, e && (h = e, h.fn.imagesLoaded = function (t, e) {
            var i = new o(this, t, e);
            return i.jqDeferred.promise(h(this))
        })
    }, o.makeJQueryPlugin(), o
});

/**
 * Parallax effects in ux_banners.
 */
if (!/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
    jQuery(function ($) {

        $elements = $('.has-parallax').find('[data-velocity]');

        var topOffset = $('.header-wrapper').outerHeight() + $('#wpadminbar').outerHeight();

        $(window).bind('resize.ux-parallax', function () {
            var windowHeight = $(window).height();
            var scrollTop = $(window).scrollTop();
            $elements.each(function (i) {
                var $this = $(this);
                var $banner = $this.parents('.has-parallax');

                if (!$this.hasClass('parallax_text') && !$this.hasClass('parallax_img_inner')) {
                    // Parallax elements needs to have same height
                    // as the browser window. Unless the window is
                    // lower than the banner.
                    if ($banner.outerHeight() < $(window).height()) {
                        $this.css('height', windowHeight);
                    } else {
                        $this.css('height', '');
                    }
                    // Center elements verticaly inside banner.
                    var marginTop = (($banner.outerHeight()) / 2) - ($banner.outerHeight() / 2);
                    $this.css('margin-top', -marginTop);

                    // Set visible when loaded
                }
                $this.css('opacity', '1');
            });
        });

        $(window).bind('scroll.ux-parallax', function () {
            var windowHeight = $(window).height();
            var scrollTop = $(window).scrollTop();

            $elements.each(function (i) {
                var $this = $(this);
                var $banner = $this.parents('.has-parallax');
                var toTop = $banner.offset().top - scrollTop;

                // Just stop here if the banner is out of screen.
                if (toTop > windowHeight || toTop + $banner.outerHeight() + topOffset < 0) {
                    return;
                }

                var ratio = 1 - $this.data('velocity');
                var status = (toTop - topOffset) / windowHeight * 0.7;
                var bgTop = -toTop + (toTop * ratio);
                var textTop = -toTop + topOffset + ((toTop - topOffset) * ratio);
                var parallaxTop = $this.hasClass('parallax_text') ? textTop : bgTop

                // Add transofrms.
                $this.css({webkitTransform: 'translate3d(0px, ' + parallaxTop + 'px , 0px)'});
                $this.css({mozTransform: 'translate3d(0px, ' + parallaxTop + 'px , 0px)'});
                $this.css({Transform: 'translate3d(0px, ' + parallaxTop + 'px , 0px)'});


                if ($this.hasClass('parallax_text')) {
                    $this.css('opacity', 1 - Math.abs(status));
                }

            });
        });

        // Trigger a resize event if DOM is changed.
        $(window).bind("DOMNodeInserted", function (e) {
            $(window).trigger('resize.ux-parallax');
        });

        // Trigger resize and scroll events to get elements
        // positioned right at initialization.
        $(window).trigger('resize.ux-parallax');
        $(window).trigger('scroll.ux-parallax');

    });
}


/*!
 * @name        EasyZoom
 * @author      Matt Hinchliffe <>
 * @modified    Wednesday, November 4th, 2015
 * @version     2.3.1
 */
!function (a) {
    "use strict";
    function b(b, c) {
        this.$target = a(b), this.opts = a.extend({}, i, c, this.$target.data()), void 0 === this.isOpen && this._init()
    }

    var c, d, e, f, g, h, i = {
        loadingNotice: "Loading image",
        errorNotice: "The image could not be loaded",
        errorDuration: 2500,
        preventClicks: !0,
        onShow: a.noop,
        onHide: a.noop,
        onMove: a.noop
    };
    b.prototype._init = function () {
        this.$link = this.$target.find("a"), this.$image = this.$target.find("img"), this.$flyout = a('<div class="easyzoom-flyout" />'), this.$notice = a('<div class="easyzoom-notice" />'), this.$target.on({
            "mousemove.easyzoom touchmove.easyzoom": a.proxy(this._onMove, this),
            "mouseleave.easyzoom touchend.easyzoom": a.proxy(this._onLeave, this),
            "mouseenter.easyzoom touchstart.easyzoom": a.proxy(this._onEnter, this)
        }), this.opts.preventClicks && this.$target.on("click.easyzoom", function (a) {
            a.preventDefault()
        })
    }, b.prototype.show = function (a, b) {
        var g, h, i, j, k = this;
        return this.isReady ? (this.$target.append(this.$flyout), g = this.$target.width(), h = this.$target.height(), i = this.$flyout.width(), j = this.$flyout.height(), c = this.$zoom.width() - i, d = this.$zoom.height() - j, e = c / g, f = d / h, this.isOpen = !0, this.opts.onShow.call(this), void(a && this._move(a))) : this._loadImage(this.$link.attr("href"), function () {
            (k.isMouseOver || !b) && k.show(a)
        })
    }, b.prototype._onEnter = function (a) {
        var b = a.originalEvent.touches;
        this.isMouseOver = !0, b && 1 != b.length || (a.preventDefault(), this.show(a, !0))
    }, b.prototype._onMove = function (a) {
        this.isOpen && (a.preventDefault(), this._move(a))
    }, b.prototype._onLeave = function () {
        this.isMouseOver = !1, this.isOpen && this.hide()
    }, b.prototype._onLoad = function (a) {
        a.currentTarget.width && (this.isReady = !0, this.$notice.detach(), this.$flyout.html(this.$zoom), this.$target.removeClass("is-loading").addClass("is-ready"), a.data.call && a.data())
    }, b.prototype._onError = function () {
        var a = this;
        this.$notice.text(this.opts.errorNotice), this.$target.removeClass("is-loading").addClass("is-error"), this.detachNotice = setTimeout(function () {
            a.$notice.detach(), a.detachNotice = null
        }, this.opts.errorDuration)
    }, b.prototype._loadImage = function (b, c) {
        var d = new Image;
        this.$target.addClass("is-loading").append(this.$notice.text(this.opts.loadingNotice)), this.$zoom = a(d).on("error", a.proxy(this._onError, this)).on("load", c, a.proxy(this._onLoad, this)), d.style.position = "absolute", d.src = b
    }, b.prototype._move = function (a) {
        if (0 === a.type.indexOf("touch")) {
            var b = a.touches || a.originalEvent.touches;
            g = b[0].pageX, h = b[0].pageY
        } else g = a.pageX || g, h = a.pageY || h;
        var i = this.$target.offset(), j = h - i.top, k = g - i.left, l = Math.ceil(j * f), m = Math.ceil(k * e);
        if (0 > m || 0 > l || m > c || l > d)this.hide(); else {
            var n = -1 * l, o = -1 * m;
            this.$zoom.css({top: n, left: o}), this.opts.onMove.call(this, n, o)
        }
    }, b.prototype.hide = function () {
        this.isOpen && (this.$flyout.detach(), this.isOpen = !1, this.opts.onHide.call(this))
    }, b.prototype.swap = function (b, c, d) {
        this.hide(), this.isReady = !1, this.detachNotice && clearTimeout(this.detachNotice), this.$notice.parent().length && this.$notice.detach(), this.$target.removeClass("is-loading is-ready is-error"), this.$image.attr({
            src: b,
            srcset: a.isArray(d) ? d.join() : d
        }), this.$link.attr("href", c)
    }, b.prototype.teardown = function () {
        this.hide(), this.$target.off(".easyzoom").removeClass("is-loading is-ready is-error"), this.detachNotice && clearTimeout(this.detachNotice), delete this.$link, delete this.$zoom, delete this.$image, delete this.$notice, delete this.$flyout, delete this.isOpen, delete this.isReady
    }, a.fn.easyZoom = function (c) {
        return this.each(function () {
            var d = a.data(this, "easyZoom");
            d ? void 0 === d.isOpen && d._init() : a.data(this, "easyZoom", new b(this, c))
        })
    }, "function" == typeof define && define.amd ? define(function () {
        return b
    }) : "undefined" != typeof module && module.exports && (module.exports = b)
}(jQuery);

/*
 WooCommerce QTY Buttons */

(function ($) {
    function createQTYButtons(target) {
        // Quantity buttons
        $(target).find('div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)').addClass('buttons_added').append('<input type="button" value="+" class="plus" />').prepend('<input type="button" value="-" class="minus" />');
        // Target quantity inputs on product pages
        $(target).find('input.qty:not(.product-quantity input.qty)').each(function () {
            var min = parseFloat($(this).attr('min'));
            if (min && min > 0 && parseFloat($(this).val()) < min) {
                $(this).val(min);
            }
        });
        $(target).on('click', '.plus, .minus', function () {
            // Get values
            var $qty = $(this).closest('.quantity').find('.qty'),
                currentVal = parseFloat($qty.val()),
                max = parseFloat($qty.attr('max')),
                min = parseFloat($qty.attr('min')),
                step = $qty.attr('step');
            // Format values
            if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
            if (max === '' || max === 'NaN') max = '';
            if (min === '' || min === 'NaN') min = 0;
            if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;
            // Change the value
            if ($(this).is('.plus')) {
                if (max && (max == currentVal || currentVal > max)) {
                    $qty.val(max);
                } else {
                    $qty.val(currentVal + parseFloat(step));
                }
            } else {
                if (min && (min == currentVal || currentVal < min)) {
                    $qty.val(min);
                } else if (currentVal > 0) {
                    $qty.val(currentVal - parseFloat(step));
                }
            }
            // Trigger change event
            $qty.trigger('change');
        });
    }

    // jQuery plugin.
    $.fn.addQty = function () {
        return this.each(function (i, el) {
            createQTYButtons(el);
        });
    }
})(jQuery);


/*! jquery.cookie v1.4.1 | MIT */
!function (a) {
    "function" == typeof define && define.amd ? define(["jquery"], a) : "object" == typeof exports ? a(require("jquery")) : a(jQuery)
}(function (a) {
    function b(a) {
        return h.raw ? a : encodeURIComponent(a)
    }

    function c(a) {
        return h.raw ? a : decodeURIComponent(a)
    }

    function d(a) {
        return b(h.json ? JSON.stringify(a) : String(a))
    }

    function e(a) {
        0 === a.indexOf('"') && (a = a.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, "\\"));
        try {
            return a = decodeURIComponent(a.replace(g, " ")), h.json ? JSON.parse(a) : a
        } catch (b) {
        }
    }

    function f(b, c) {
        var d = h.raw ? b : e(b);
        return a.isFunction(c) ? c(d) : d
    }

    var g = /\+/g, h = a.cookie = function (e, g, i) {
        if (void 0 !== g && !a.isFunction(g)) {
            if (i = a.extend({}, h.defaults, i), "number" == typeof i.expires) {
                var j = i.expires, k = i.expires = new Date;
                k.setTime(+k + 864e5 * j)
            }
            return document.cookie = [b(e), "=", d(g), i.expires ? "; expires=" + i.expires.toUTCString() : "", i.path ? "; path=" + i.path : "", i.domain ? "; domain=" + i.domain : "", i.secure ? "; secure" : ""].join("")
        }
        for (var l = e ? void 0 : {}, m = document.cookie ? document.cookie.split("; ") : [], n = 0, o = m.length; o > n; n++) {
            var p = m[n].split("="), q = c(p.shift()), r = p.join("=");
            if (e && e === q) {
                l = f(r, g);
                break
            }
            e || void 0 === (r = f(r)) || (l[q] = r)
        }
        return l
    };
    h.defaults = {}, a.removeCookie = function (b, c) {
        return void 0 === a.cookie(b) ? !1 : (a.cookie(b, "", a.extend({}, c, {expires: -1})), !a.cookie(b))
    }
});


/*! Hammer.JS - v2.0.4 - 2014-09-28
 * http://hammerjs.github.io/
 *
 * Copyright (c) 2014 Jorik Tangelder;
 * Licensed under the MIT license */
!function (a, b, c, d) {
    "use strict";
    function e(a, b, c) {
        return setTimeout(k(a, c), b)
    }

    function f(a, b, c) {
        return Array.isArray(a) ? (g(a, c[b], c), !0) : !1
    }

    function g(a, b, c) {
        var e;
        if (a)if (a.forEach)a.forEach(b, c); else if (a.length !== d)for (e = 0; e < a.length;)b.call(c, a[e], e, a), e++; else for (e in a)a.hasOwnProperty(e) && b.call(c, a[e], e, a)
    }

    function h(a, b, c) {
        for (var e = Object.keys(b), f = 0; f < e.length;)(!c || c && a[e[f]] === d) && (a[e[f]] = b[e[f]]), f++;
        return a
    }

    function i(a, b) {
        return h(a, b, !0)
    }

    function j(a, b, c) {
        var d, e = b.prototype;
        d = a.prototype = Object.create(e), d.constructor = a, d._super = e, c && h(d, c)
    }

    function k(a, b) {
        return function () {
            return a.apply(b, arguments)
        }
    }

    function l(a, b) {
        return typeof a == kb ? a.apply(b ? b[0] || d : d, b) : a
    }

    function m(a, b) {
        return a === d ? b : a
    }

    function n(a, b, c) {
        g(r(b), function (b) {
            a.addEventListener(b, c, !1)
        })
    }

    function o(a, b, c) {
        g(r(b), function (b) {
            a.removeEventListener(b, c, !1)
        })
    }

    function p(a, b) {
        for (; a;) {
            if (a == b)return !0;
            a = a.parentNode
        }
        return !1
    }

    function q(a, b) {
        return a.indexOf(b) > -1
    }

    function r(a) {
        return a.trim().split(/\s+/g)
    }

    function s(a, b, c) {
        if (a.indexOf && !c)return a.indexOf(b);
        for (var d = 0; d < a.length;) {
            if (c && a[d][c] == b || !c && a[d] === b)return d;
            d++
        }
        return -1
    }

    function t(a) {
        return Array.prototype.slice.call(a, 0)
    }

    function u(a, b, c) {
        for (var d = [], e = [], f = 0; f < a.length;) {
            var g = b ? a[f][b] : a[f];
            s(e, g) < 0 && d.push(a[f]), e[f] = g, f++
        }
        return c && (d = b ? d.sort(function (a, c) {
            return a[b] > c[b]
        }) : d.sort()), d
    }

    function v(a, b) {
        for (var c, e, f = b[0].toUpperCase() + b.slice(1), g = 0; g < ib.length;) {
            if (c = ib[g], e = c ? c + f : b, e in a)return e;
            g++
        }
        return d
    }

    function w() {
        return ob++
    }

    function x(a) {
        var b = a.ownerDocument;
        return b.defaultView || b.parentWindow
    }

    function y(a, b) {
        var c = this;
        this.manager = a, this.callback = b, this.element = a.element, this.target = a.options.inputTarget, this.domHandler = function (b) {
            l(a.options.enable, [a]) && c.handler(b)
        }, this.init()
    }

    function z(a) {
        var b, c = a.options.inputClass;
        return new (b = c ? c : rb ? N : sb ? Q : qb ? S : M)(a, A)
    }

    function A(a, b, c) {
        var d = c.pointers.length, e = c.changedPointers.length, f = b & yb && d - e === 0, g = b & (Ab | Bb) && d - e === 0;
        c.isFirst = !!f, c.isFinal = !!g, f && (a.session = {}), c.eventType = b, B(a, c), a.emit("hammer.input", c), a.recognize(c), a.session.prevInput = c
    }

    function B(a, b) {
        var c = a.session, d = b.pointers, e = d.length;
        c.firstInput || (c.firstInput = E(b)), e > 1 && !c.firstMultiple ? c.firstMultiple = E(b) : 1 === e && (c.firstMultiple = !1);
        var f = c.firstInput, g = c.firstMultiple, h = g ? g.center : f.center, i = b.center = F(d);
        b.timeStamp = nb(), b.deltaTime = b.timeStamp - f.timeStamp, b.angle = J(h, i), b.distance = I(h, i), C(c, b), b.offsetDirection = H(b.deltaX, b.deltaY), b.scale = g ? L(g.pointers, d) : 1, b.rotation = g ? K(g.pointers, d) : 0, D(c, b);
        var j = a.element;
        p(b.srcEvent.target, j) && (j = b.srcEvent.target), b.target = j
    }

    function C(a, b) {
        var c = b.center, d = a.offsetDelta || {}, e = a.prevDelta || {}, f = a.prevInput || {};
        (b.eventType === yb || f.eventType === Ab) && (e = a.prevDelta = {
            x: f.deltaX || 0,
            y: f.deltaY || 0
        }, d = a.offsetDelta = {x: c.x, y: c.y}), b.deltaX = e.x + (c.x - d.x), b.deltaY = e.y + (c.y - d.y)
    }

    function D(a, b) {
        var c, e, f, g, h = a.lastInterval || b, i = b.timeStamp - h.timeStamp;
        if (b.eventType != Bb && (i > xb || h.velocity === d)) {
            var j = h.deltaX - b.deltaX, k = h.deltaY - b.deltaY, l = G(i, j, k);
            e = l.x, f = l.y, c = mb(l.x) > mb(l.y) ? l.x : l.y, g = H(j, k), a.lastInterval = b
        } else c = h.velocity, e = h.velocityX, f = h.velocityY, g = h.direction;
        b.velocity = c, b.velocityX = e, b.velocityY = f, b.direction = g
    }

    function E(a) {
        for (var b = [], c = 0; c < a.pointers.length;)b[c] = {
            clientX: lb(a.pointers[c].clientX),
            clientY: lb(a.pointers[c].clientY)
        }, c++;
        return {timeStamp: nb(), pointers: b, center: F(b), deltaX: a.deltaX, deltaY: a.deltaY}
    }

    function F(a) {
        var b = a.length;
        if (1 === b)return {x: lb(a[0].clientX), y: lb(a[0].clientY)};
        for (var c = 0, d = 0, e = 0; b > e;)c += a[e].clientX, d += a[e].clientY, e++;
        return {x: lb(c / b), y: lb(d / b)}
    }

    function G(a, b, c) {
        return {x: b / a || 0, y: c / a || 0}
    }

    function H(a, b) {
        return a === b ? Cb : mb(a) >= mb(b) ? a > 0 ? Db : Eb : b > 0 ? Fb : Gb
    }

    function I(a, b, c) {
        c || (c = Kb);
        var d = b[c[0]] - a[c[0]], e = b[c[1]] - a[c[1]];
        return Math.sqrt(d * d + e * e)
    }

    function J(a, b, c) {
        c || (c = Kb);
        var d = b[c[0]] - a[c[0]], e = b[c[1]] - a[c[1]];
        return 180 * Math.atan2(e, d) / Math.PI
    }

    function K(a, b) {
        return J(b[1], b[0], Lb) - J(a[1], a[0], Lb)
    }

    function L(a, b) {
        return I(b[0], b[1], Lb) / I(a[0], a[1], Lb)
    }

    function M() {
        this.evEl = Nb, this.evWin = Ob, this.allow = !0, this.pressed = !1, y.apply(this, arguments)
    }

    function N() {
        this.evEl = Rb, this.evWin = Sb, y.apply(this, arguments), this.store = this.manager.session.pointerEvents = []
    }

    function O() {
        this.evTarget = Ub, this.evWin = Vb, this.started = !1, y.apply(this, arguments)
    }

    function P(a, b) {
        var c = t(a.touches), d = t(a.changedTouches);
        return b & (Ab | Bb) && (c = u(c.concat(d), "identifier", !0)), [c, d]
    }

    function Q() {
        this.evTarget = Xb, this.targetIds = {}, y.apply(this, arguments)
    }

    function R(a, b) {
        var c = t(a.touches), d = this.targetIds;
        if (b & (yb | zb) && 1 === c.length)return d[c[0].identifier] = !0, [c, c];
        var e, f, g = t(a.changedTouches), h = [], i = this.target;
        if (f = c.filter(function (a) {
                return p(a.target, i)
            }), b === yb)for (e = 0; e < f.length;)d[f[e].identifier] = !0, e++;
        for (e = 0; e < g.length;)d[g[e].identifier] && h.push(g[e]), b & (Ab | Bb) && delete d[g[e].identifier], e++;
        return h.length ? [u(f.concat(h), "identifier", !0), h] : void 0
    }

    function S() {
        y.apply(this, arguments);
        var a = k(this.handler, this);
        this.touch = new Q(this.manager, a), this.mouse = new M(this.manager, a)
    }

    function T(a, b) {
        this.manager = a, this.set(b)
    }

    function U(a) {
        if (q(a, bc))return bc;
        var b = q(a, cc), c = q(a, dc);
        return b && c ? cc + " " + dc : b || c ? b ? cc : dc : q(a, ac) ? ac : _b
    }

    function V(a) {
        this.id = w(), this.manager = null, this.options = i(a || {}, this.defaults), this.options.enable = m(this.options.enable, !0), this.state = ec, this.simultaneous = {}, this.requireFail = []
    }

    function W(a) {
        return a & jc ? "cancel" : a & hc ? "end" : a & gc ? "move" : a & fc ? "start" : ""
    }

    function X(a) {
        return a == Gb ? "down" : a == Fb ? "up" : a == Db ? "left" : a == Eb ? "right" : ""
    }

    function Y(a, b) {
        var c = b.manager;
        return c ? c.get(a) : a
    }

    function Z() {
        V.apply(this, arguments)
    }

    function $() {
        Z.apply(this, arguments), this.pX = null, this.pY = null
    }

    function _() {
        Z.apply(this, arguments)
    }

    function ab() {
        V.apply(this, arguments), this._timer = null, this._input = null
    }

    function bb() {
        Z.apply(this, arguments)
    }

    function cb() {
        Z.apply(this, arguments)
    }

    function db() {
        V.apply(this, arguments), this.pTime = !1, this.pCenter = !1, this._timer = null, this._input = null, this.count = 0
    }

    function eb(a, b) {
        return b = b || {}, b.recognizers = m(b.recognizers, eb.defaults.preset), new fb(a, b)
    }

    function fb(a, b) {
        b = b || {}, this.options = i(b, eb.defaults), this.options.inputTarget = this.options.inputTarget || a, this.handlers = {}, this.session = {}, this.recognizers = [], this.element = a, this.input = z(this), this.touchAction = new T(this, this.options.touchAction), gb(this, !0), g(b.recognizers, function (a) {
            var b = this.add(new a[0](a[1]));
            a[2] && b.recognizeWith(a[2]), a[3] && b.requireFailure(a[3])
        }, this)
    }

    function gb(a, b) {
        var c = a.element;
        g(a.options.cssProps, function (a, d) {
            c.style[v(c.style, d)] = b ? a : ""
        })
    }

    function hb(a, c) {
        var d = b.createEvent("Event");
        d.initEvent(a, !0, !0), d.gesture = c, c.target.dispatchEvent(d)
    }

    var ib = ["", "webkit", "moz", "MS", "ms", "o"], jb = b.createElement("div"), kb = "function", lb = Math.round, mb = Math.abs, nb = Date.now, ob = 1, pb = /mobile|tablet|ip(ad|hone|od)|android/i, qb = "ontouchstart" in a, rb = v(a, "PointerEvent") !== d, sb = qb && pb.test(navigator.userAgent), tb = "touch", ub = "pen", vb = "mouse", wb = "kinect", xb = 25, yb = 1, zb = 2, Ab = 4, Bb = 8, Cb = 1, Db = 2, Eb = 4, Fb = 8, Gb = 16, Hb = Db | Eb, Ib = Fb | Gb, Jb = Hb | Ib, Kb = ["x", "y"], Lb = ["clientX", "clientY"];
    y.prototype = {
        handler: function () {
        }, init: function () {
            this.evEl && n(this.element, this.evEl, this.domHandler), this.evTarget && n(this.target, this.evTarget, this.domHandler), this.evWin && n(x(this.element), this.evWin, this.domHandler)
        }, destroy: function () {
            this.evEl && o(this.element, this.evEl, this.domHandler), this.evTarget && o(this.target, this.evTarget, this.domHandler), this.evWin && o(x(this.element), this.evWin, this.domHandler)
        }
    };
    var Mb = {mousedown: yb, mousemove: zb, mouseup: Ab}, Nb = "mousedown", Ob = "mousemove mouseup";
    j(M, y, {
        handler: function (a) {
            var b = Mb[a.type];
            b & yb && 0 === a.button && (this.pressed = !0), b & zb && 1 !== a.which && (b = Ab), this.pressed && this.allow && (b & Ab && (this.pressed = !1), this.callback(this.manager, b, {
                pointers: [a],
                changedPointers: [a],
                pointerType: vb,
                srcEvent: a
            }))
        }
    });
    var Pb = {pointerdown: yb, pointermove: zb, pointerup: Ab, pointercancel: Bb, pointerout: Bb}, Qb = {
        2: tb,
        3: ub,
        4: vb,
        5: wb
    }, Rb = "pointerdown", Sb = "pointermove pointerup pointercancel";
    a.MSPointerEvent && (Rb = "MSPointerDown", Sb = "MSPointerMove MSPointerUp MSPointerCancel"), j(N, y, {
        handler: function (a) {
            var b = this.store, c = !1, d = a.type.toLowerCase().replace("ms", ""), e = Pb[d], f = Qb[a.pointerType] || a.pointerType, g = f == tb, h = s(b, a.pointerId, "pointerId");
            e & yb && (0 === a.button || g) ? 0 > h && (b.push(a), h = b.length - 1) : e & (Ab | Bb) && (c = !0), 0 > h || (b[h] = a, this.callback(this.manager, e, {
                pointers: b,
                changedPointers: [a],
                pointerType: f,
                srcEvent: a
            }), c && b.splice(h, 1))
        }
    });
    var Tb = {
        touchstart: yb,
        touchmove: zb,
        touchend: Ab,
        touchcancel: Bb
    }, Ub = "touchstart", Vb = "touchstart touchmove touchend touchcancel";
    j(O, y, {
        handler: function (a) {
            var b = Tb[a.type];
            if (b === yb && (this.started = !0), this.started) {
                var c = P.call(this, a, b);
                b & (Ab | Bb) && c[0].length - c[1].length === 0 && (this.started = !1), this.callback(this.manager, b, {
                    pointers: c[0],
                    changedPointers: c[1],
                    pointerType: tb,
                    srcEvent: a
                })
            }
        }
    });
    var Wb = {
        touchstart: yb,
        touchmove: zb,
        touchend: Ab,
        touchcancel: Bb
    }, Xb = "touchstart touchmove touchend touchcancel";
    j(Q, y, {
        handler: function (a) {
            var b = Wb[a.type], c = R.call(this, a, b);
            c && this.callback(this.manager, b, {pointers: c[0], changedPointers: c[1], pointerType: tb, srcEvent: a})
        }
    }), j(S, y, {
        handler: function (a, b, c) {
            var d = c.pointerType == tb, e = c.pointerType == vb;
            if (d)this.mouse.allow = !1; else if (e && !this.mouse.allow)return;
            b & (Ab | Bb) && (this.mouse.allow = !0), this.callback(a, b, c)
        }, destroy: function () {
            this.touch.destroy(), this.mouse.destroy()
        }
    });
    var Yb = v(jb.style, "touchAction"), Zb = Yb !== d, $b = "compute", _b = "auto", ac = "manipulation", bc = "none", cc = "pan-x", dc = "pan-y";
    T.prototype = {
        set: function (a) {
            a == $b && (a = this.compute()), Zb && (this.manager.element.style[Yb] = a), this.actions = a.toLowerCase().trim()
        }, update: function () {
            this.set(this.manager.options.touchAction)
        }, compute: function () {
            var a = [];
            return g(this.manager.recognizers, function (b) {
                l(b.options.enable, [b]) && (a = a.concat(b.getTouchAction()))
            }), U(a.join(" "))
        }, preventDefaults: function (a) {
            if (!Zb) {
                var b = a.srcEvent, c = a.offsetDirection;
                if (this.manager.session.prevented)return void b.preventDefault();
                var d = this.actions, e = q(d, bc), f = q(d, dc), g = q(d, cc);
                return e || f && c & Hb || g && c & Ib ? this.preventSrc(b) : void 0
            }
        }, preventSrc: function (a) {
            this.manager.session.prevented = !0, a.preventDefault()
        }
    };
    var ec = 1, fc = 2, gc = 4, hc = 8, ic = hc, jc = 16, kc = 32;
    V.prototype = {
        defaults: {}, set: function (a) {
            return h(this.options, a), this.manager && this.manager.touchAction.update(), this
        }, recognizeWith: function (a) {
            if (f(a, "recognizeWith", this))return this;
            var b = this.simultaneous;
            return a = Y(a, this), b[a.id] || (b[a.id] = a, a.recognizeWith(this)), this
        }, dropRecognizeWith: function (a) {
            return f(a, "dropRecognizeWith", this) ? this : (a = Y(a, this), delete this.simultaneous[a.id], this)
        }, requireFailure: function (a) {
            if (f(a, "requireFailure", this))return this;
            var b = this.requireFail;
            return a = Y(a, this), -1 === s(b, a) && (b.push(a), a.requireFailure(this)), this
        }, dropRequireFailure: function (a) {
            if (f(a, "dropRequireFailure", this))return this;
            a = Y(a, this);
            var b = s(this.requireFail, a);
            return b > -1 && this.requireFail.splice(b, 1), this
        }, hasRequireFailures: function () {
            return this.requireFail.length > 0
        }, canRecognizeWith: function (a) {
            return !!this.simultaneous[a.id]
        }, emit: function (a) {
            function b(b) {
                c.manager.emit(c.options.event + (b ? W(d) : ""), a)
            }

            var c = this, d = this.state;
            hc > d && b(!0), b(), d >= hc && b(!0)
        }, tryEmit: function (a) {
            return this.canEmit() ? this.emit(a) : void(this.state = kc)
        }, canEmit: function () {
            for (var a = 0; a < this.requireFail.length;) {
                if (!(this.requireFail[a].state & (kc | ec)))return !1;
                a++
            }
            return !0
        }, recognize: function (a) {
            var b = h({}, a);
            return l(this.options.enable, [this, b]) ? (this.state & (ic | jc | kc) && (this.state = ec), this.state = this.process(b), void(this.state & (fc | gc | hc | jc) && this.tryEmit(b))) : (this.reset(), void(this.state = kc))
        }, process: function () {
        }, getTouchAction: function () {
        }, reset: function () {
        }
    }, j(Z, V, {
        defaults: {pointers: 1}, attrTest: function (a) {
            var b = this.options.pointers;
            return 0 === b || a.pointers.length === b
        }, process: function (a) {
            var b = this.state, c = a.eventType, d = b & (fc | gc), e = this.attrTest(a);
            return d && (c & Bb || !e) ? b | jc : d || e ? c & Ab ? b | hc : b & fc ? b | gc : fc : kc
        }
    }), j($, Z, {
        defaults: {event: "pan", threshold: 10, pointers: 1, direction: Jb}, getTouchAction: function () {
            var a = this.options.direction, b = [];
            return a & Hb && b.push(dc), a & Ib && b.push(cc), b
        }, directionTest: function (a) {
            var b = this.options, c = !0, d = a.distance, e = a.direction, f = a.deltaX, g = a.deltaY;
            return e & b.direction || (b.direction & Hb ? (e = 0 === f ? Cb : 0 > f ? Db : Eb, c = f != this.pX, d = Math.abs(a.deltaX)) : (e = 0 === g ? Cb : 0 > g ? Fb : Gb, c = g != this.pY, d = Math.abs(a.deltaY))), a.direction = e, c && d > b.threshold && e & b.direction
        }, attrTest: function (a) {
            return Z.prototype.attrTest.call(this, a) && (this.state & fc || !(this.state & fc) && this.directionTest(a))
        }, emit: function (a) {
            this.pX = a.deltaX, this.pY = a.deltaY;
            var b = X(a.direction);
            b && this.manager.emit(this.options.event + b, a), this._super.emit.call(this, a)
        }
    }), j(_, Z, {
        defaults: {event: "pinch", threshold: 0, pointers: 2}, getTouchAction: function () {
            return [bc]
        }, attrTest: function (a) {
            return this._super.attrTest.call(this, a) && (Math.abs(a.scale - 1) > this.options.threshold || this.state & fc)
        }, emit: function (a) {
            if (this._super.emit.call(this, a), 1 !== a.scale) {
                var b = a.scale < 1 ? "in" : "out";
                this.manager.emit(this.options.event + b, a)
            }
        }
    }), j(ab, V, {
        defaults: {event: "press", pointers: 1, time: 500, threshold: 5}, getTouchAction: function () {
            return [_b]
        }, process: function (a) {
            var b = this.options, c = a.pointers.length === b.pointers, d = a.distance < b.threshold, f = a.deltaTime > b.time;
            if (this._input = a, !d || !c || a.eventType & (Ab | Bb) && !f)this.reset(); else if (a.eventType & yb)this.reset(), this._timer = e(function () {
                this.state = ic, this.tryEmit()
            }, b.time, this); else if (a.eventType & Ab)return ic;
            return kc
        }, reset: function () {
            clearTimeout(this._timer)
        }, emit: function (a) {
            this.state === ic && (a && a.eventType & Ab ? this.manager.emit(this.options.event + "up", a) : (this._input.timeStamp = nb(), this.manager.emit(this.options.event, this._input)))
        }
    }), j(bb, Z, {
        defaults: {event: "rotate", threshold: 0, pointers: 2}, getTouchAction: function () {
            return [bc]
        }, attrTest: function (a) {
            return this._super.attrTest.call(this, a) && (Math.abs(a.rotation) > this.options.threshold || this.state & fc)
        }
    }), j(cb, Z, {
        defaults: {event: "swipe", threshold: 10, velocity: .65, direction: Hb | Ib, pointers: 1},
        getTouchAction: function () {
            return $.prototype.getTouchAction.call(this)
        },
        attrTest: function (a) {
            var b, c = this.options.direction;
            return c & (Hb | Ib) ? b = a.velocity : c & Hb ? b = a.velocityX : c & Ib && (b = a.velocityY), this._super.attrTest.call(this, a) && c & a.direction && a.distance > this.options.threshold && mb(b) > this.options.velocity && a.eventType & Ab
        },
        emit: function (a) {
            var b = X(a.direction);
            b && this.manager.emit(this.options.event + b, a), this.manager.emit(this.options.event, a)
        }
    }), j(db, V, {
        defaults: {
            event: "tap",
            pointers: 1,
            taps: 1,
            interval: 300,
            time: 250,
            threshold: 2,
            posThreshold: 10
        }, getTouchAction: function () {
            return [ac]
        }, process: function (a) {
            var b = this.options, c = a.pointers.length === b.pointers, d = a.distance < b.threshold, f = a.deltaTime < b.time;
            if (this.reset(), a.eventType & yb && 0 === this.count)return this.failTimeout();
            if (d && f && c) {
                if (a.eventType != Ab)return this.failTimeout();
                var g = this.pTime ? a.timeStamp - this.pTime < b.interval : !0, h = !this.pCenter || I(this.pCenter, a.center) < b.posThreshold;
                this.pTime = a.timeStamp, this.pCenter = a.center, h && g ? this.count += 1 : this.count = 1, this._input = a;
                var i = this.count % b.taps;
                if (0 === i)return this.hasRequireFailures() ? (this._timer = e(function () {
                    this.state = ic, this.tryEmit()
                }, b.interval, this), fc) : ic
            }
            return kc
        }, failTimeout: function () {
            return this._timer = e(function () {
                this.state = kc
            }, this.options.interval, this), kc
        }, reset: function () {
            clearTimeout(this._timer)
        }, emit: function () {
            this.state == ic && (this._input.tapCount = this.count, this.manager.emit(this.options.event, this._input))
        }
    }), eb.VERSION = "2.0.4", eb.defaults = {
        domEvents: !1,
        touchAction: $b,
        enable: !0,
        inputTarget: null,
        inputClass: null,
        preset: [[bb, {enable: !1}], [_, {enable: !1}, ["rotate"]], [cb, {direction: Hb}], [$, {direction: Hb}, ["swipe"]], [db], [db, {
            event: "doubletap",
            taps: 2
        }, ["tap"]], [ab]],
        cssProps: {
            userSelect: "none",
            touchSelect: "none",
            touchCallout: "none",
            contentZooming: "none",
            userDrag: "none",
            tapHighlightColor: "rgba(0,0,0,0)"
        }
    };
    var lc = 1, mc = 2;
    fb.prototype = {
        set: function (a) {
            return h(this.options, a), a.touchAction && this.touchAction.update(), a.inputTarget && (this.input.destroy(), this.input.target = a.inputTarget, this.input.init()), this
        }, stop: function (a) {
            this.session.stopped = a ? mc : lc
        }, recognize: function (a) {
            var b = this.session;
            if (!b.stopped) {
                this.touchAction.preventDefaults(a);
                var c, d = this.recognizers, e = b.curRecognizer;
                (!e || e && e.state & ic) && (e = b.curRecognizer = null);
                for (var f = 0; f < d.length;)c = d[f], b.stopped === mc || e && c != e && !c.canRecognizeWith(e) ? c.reset() : c.recognize(a), !e && c.state & (fc | gc | hc) && (e = b.curRecognizer = c), f++
            }
        }, get: function (a) {
            if (a instanceof V)return a;
            for (var b = this.recognizers, c = 0; c < b.length; c++)if (b[c].options.event == a)return b[c];
            return null
        }, add: function (a) {
            if (f(a, "add", this))return this;
            var b = this.get(a.options.event);
            return b && this.remove(b), this.recognizers.push(a), a.manager = this, this.touchAction.update(), a
        }, remove: function (a) {
            if (f(a, "remove", this))return this;
            var b = this.recognizers;
            return a = this.get(a), b.splice(s(b, a), 1), this.touchAction.update(), this
        }, on: function (a, b) {
            var c = this.handlers;
            return g(r(a), function (a) {
                c[a] = c[a] || [], c[a].push(b)
            }), this
        }, off: function (a, b) {
            var c = this.handlers;
            return g(r(a), function (a) {
                b ? c[a].splice(s(c[a], b), 1) : delete c[a]
            }), this
        }, emit: function (a, b) {
            this.options.domEvents && hb(a, b);
            var c = this.handlers[a] && this.handlers[a].slice();
            if (c && c.length) {
                b.type = a, b.preventDefault = function () {
                    b.srcEvent.preventDefault()
                };
                for (var d = 0; d < c.length;)c[d](b), d++
            }
        }, destroy: function () {
            this.element && gb(this, !1), this.handlers = {}, this.session = {}, this.input.destroy(), this.element = null
        }
    }, h(eb, {
        INPUT_START: yb,
        INPUT_MOVE: zb,
        INPUT_END: Ab,
        INPUT_CANCEL: Bb,
        STATE_POSSIBLE: ec,
        STATE_BEGAN: fc,
        STATE_CHANGED: gc,
        STATE_ENDED: hc,
        STATE_RECOGNIZED: ic,
        STATE_CANCELLED: jc,
        STATE_FAILED: kc,
        DIRECTION_NONE: Cb,
        DIRECTION_LEFT: Db,
        DIRECTION_RIGHT: Eb,
        DIRECTION_UP: Fb,
        DIRECTION_DOWN: Gb,
        DIRECTION_HORIZONTAL: Hb,
        DIRECTION_VERTICAL: Ib,
        DIRECTION_ALL: Jb,
        Manager: fb,
        Input: y,
        TouchAction: T,
        TouchInput: Q,
        MouseInput: M,
        PointerEventInput: N,
        TouchMouseInput: S,
        SingleTouchInput: O,
        Recognizer: V,
        AttrRecognizer: Z,
        Tap: db,
        Pan: $,
        Swipe: cb,
        Pinch: _,
        Rotate: bb,
        Press: ab,
        on: n,
        off: o,
        each: g,
        merge: i,
        extend: h,
        inherit: j,
        bindFn: k,
        prefixed: v
    }), typeof define == kb && define.amd ? define(function () {
        return eb
    }) : "undefined" != typeof module && module.exports ? module.exports = eb : a[c] = eb
}(window, document, "Hammer");


/* FAST CLICK */
!function () {
    "use strict";
    function t(e, o) {
        function i(t, e) {
            return function () {
                return t.apply(e, arguments)
            }
        }

        var r;
        if (o = o || {}, this.trackingClick = !1, this.trackingClickStart = 0, this.targetElement = null, this.touchStartX = 0, this.touchStartY = 0, this.lastTouchIdentifier = 0, this.touchBoundary = o.touchBoundary || 10, this.layer = e, this.tapDelay = o.tapDelay || 200, this.tapTimeout = o.tapTimeout || 700, !t.notNeeded(e)) {
            for (var a = ["onMouse", "onClick", "onTouchStart", "onTouchMove", "onTouchEnd", "onTouchCancel"], c = this, s = 0, u = a.length; u > s; s++)c[a[s]] = i(c[a[s]], c);
            n && (e.addEventListener("mouseover", this.onMouse, !0), e.addEventListener("mousedown", this.onMouse, !0), e.addEventListener("mouseup", this.onMouse, !0)), e.addEventListener("click", this.onClick, !0), e.addEventListener("touchstart", this.onTouchStart, !1), e.addEventListener("touchmove", this.onTouchMove, !1), e.addEventListener("touchend", this.onTouchEnd, !1), e.addEventListener("touchcancel", this.onTouchCancel, !1), Event.prototype.stopImmediatePropagation || (e.removeEventListener = function (t, n, o) {
                var i = Node.prototype.removeEventListener;
                "click" === t ? i.call(e, t, n.hijacked || n, o) : i.call(e, t, n, o)
            }, e.addEventListener = function (t, n, o) {
                var i = Node.prototype.addEventListener;
                "click" === t ? i.call(e, t, n.hijacked || (n.hijacked = function (t) {
                        t.propagationStopped || n(t)
                    }), o) : i.call(e, t, n, o)
            }), "function" == typeof e.onclick && (r = e.onclick, e.addEventListener("click", function (t) {
                r(t)
            }, !1), e.onclick = null)
        }
    }

    var e = navigator.userAgent.indexOf("Windows Phone") >= 0, n = navigator.userAgent.indexOf("Android") > 0 && !e, o = /iP(ad|hone|od)/.test(navigator.userAgent) && !e, i = o && /OS 4_\d(_\d)?/.test(navigator.userAgent), r = o && /OS [6-7]_\d/.test(navigator.userAgent), a = navigator.userAgent.indexOf("BB10") > 0;
    t.prototype.needsClick = function (t) {
        switch (t.nodeName.toLowerCase()) {
            case"button":
            case"select":
            case"textarea":
                if (t.disabled)return !0;
                break;
            case"input":
                if (o && "file" === t.type || t.disabled)return !0;
                break;
            case"label":
            case"iframe":
            case"video":
                return !0
        }
        return /\bneedsclick\b/.test(t.className)
    }, t.prototype.needsFocus = function (t) {
        switch (t.nodeName.toLowerCase()) {
            case"textarea":
                return !0;
            case"select":
                return !n;
            case"input":
                switch (t.type) {
                    case"button":
                    case"checkbox":
                    case"file":
                    case"image":
                    case"radio":
                    case"submit":
                        return !1
                }
                return !t.disabled && !t.readOnly;
            default:
                return /\bneedsfocus\b/.test(t.className)
        }
    }, t.prototype.sendClick = function (t, e) {
        var n, o;
        document.activeElement && document.activeElement !== t && document.activeElement.blur(), o = e.changedTouches[0], n = document.createEvent("MouseEvents"), n.initMouseEvent(this.determineEventType(t), !0, !0, window, 1, o.screenX, o.screenY, o.clientX, o.clientY, !1, !1, !1, !1, 0, null), n.forwardedTouchEvent = !0, t.dispatchEvent(n)
    }, t.prototype.determineEventType = function (t) {
        return n && "select" === t.tagName.toLowerCase() ? "mousedown" : "click"
    }, t.prototype.focus = function (t) {
        var e;
        o && t.setSelectionRange && 0 !== t.type.indexOf("date") && "time" !== t.type && "month" !== t.type ? (e = t.value.length, t.setSelectionRange(e, e)) : t.focus()
    }, t.prototype.updateScrollParent = function (t) {
        var e, n;
        if (e = t.fastClickScrollParent, !e || !e.contains(t)) {
            n = t;
            do {
                if (n.scrollHeight > n.offsetHeight) {
                    e = n, t.fastClickScrollParent = n;
                    break
                }
                n = n.parentElement
            } while (n)
        }
        e && (e.fastClickLastScrollTop = e.scrollTop)
    }, t.prototype.getTargetElementFromEventTarget = function (t) {
        return t.nodeType === Node.TEXT_NODE ? t.parentNode : t
    }, t.prototype.onTouchStart = function (t) {
        var e, n, r;
        if (t.targetTouches.length > 1)return !0;
        if (e = this.getTargetElementFromEventTarget(t.target), n = t.targetTouches[0], o) {
            if (r = window.getSelection(), r.rangeCount && !r.isCollapsed)return !0;
            if (!i) {
                if (n.identifier && n.identifier === this.lastTouchIdentifier)return t.preventDefault(), !1;
                this.lastTouchIdentifier = n.identifier, this.updateScrollParent(e)
            }
        }
        return this.trackingClick = !0, this.trackingClickStart = t.timeStamp, this.targetElement = e, this.touchStartX = n.pageX, this.touchStartY = n.pageY, t.timeStamp - this.lastClickTime < this.tapDelay && t.preventDefault(), !0
    }, t.prototype.touchHasMoved = function (t) {
        var e = t.changedTouches[0], n = this.touchBoundary;
        return Math.abs(e.pageX - this.touchStartX) > n || Math.abs(e.pageY - this.touchStartY) > n ? !0 : !1
    }, t.prototype.onTouchMove = function (t) {
        return this.trackingClick ? ((this.targetElement !== this.getTargetElementFromEventTarget(t.target) || this.touchHasMoved(t)) && (this.trackingClick = !1, this.targetElement = null), !0) : !0
    }, t.prototype.findControl = function (t) {
        return void 0 !== t.control ? t.control : t.htmlFor ? document.getElementById(t.htmlFor) : t.querySelector("button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea")
    }, t.prototype.onTouchEnd = function (t) {
        var e, a, c, s, u, l = this.targetElement;
        if (!this.trackingClick)return !0;
        if (t.timeStamp - this.lastClickTime < this.tapDelay)return this.cancelNextClick = !0, !0;
        if (t.timeStamp - this.trackingClickStart > this.tapTimeout)return !0;
        if (this.cancelNextClick = !1, this.lastClickTime = t.timeStamp, a = this.trackingClickStart, this.trackingClick = !1, this.trackingClickStart = 0, r && (u = t.changedTouches[0], l = document.elementFromPoint(u.pageX - window.pageXOffset, u.pageY - window.pageYOffset) || l, l.fastClickScrollParent = this.targetElement.fastClickScrollParent), c = l.tagName.toLowerCase(), "label" === c) {
            if (e = this.findControl(l)) {
                if (this.focus(l), n)return !1;
                l = e
            }
        } else if (this.needsFocus(l))return t.timeStamp - a > 100 || o && window.top !== window && "input" === c ? (this.targetElement = null, !1) : (this.focus(l), this.sendClick(l, t), o && "select" === c || (this.targetElement = null, t.preventDefault()), !1);
        return o && !i && (s = l.fastClickScrollParent, s && s.fastClickLastScrollTop !== s.scrollTop) ? !0 : (this.needsClick(l) || (t.preventDefault(), this.sendClick(l, t)), !1)
    }, t.prototype.onTouchCancel = function () {
        this.trackingClick = !1, this.targetElement = null
    }, t.prototype.onMouse = function (t) {
        return this.targetElement ? t.forwardedTouchEvent ? !0 : t.cancelable && (!this.needsClick(this.targetElement) || this.cancelNextClick) ? (t.stopImmediatePropagation ? t.stopImmediatePropagation() : t.propagationStopped = !0, t.stopPropagation(), t.preventDefault(), !1) : !0 : !0
    }, t.prototype.onClick = function (t) {
        var e;
        return this.trackingClick ? (this.targetElement = null, this.trackingClick = !1, !0) : "submit" === t.target.type && 0 === t.detail ? !0 : (e = this.onMouse(t), e || (this.targetElement = null), e)
    }, t.prototype.destroy = function () {
        var t = this.layer;
        n && (t.removeEventListener("mouseover", this.onMouse, !0), t.removeEventListener("mousedown", this.onMouse, !0), t.removeEventListener("mouseup", this.onMouse, !0)), t.removeEventListener("click", this.onClick, !0), t.removeEventListener("touchstart", this.onTouchStart, !1), t.removeEventListener("touchmove", this.onTouchMove, !1), t.removeEventListener("touchend", this.onTouchEnd, !1), t.removeEventListener("touchcancel", this.onTouchCancel, !1)
    }, t.notNeeded = function (t) {
        var e, o, i, r;
        if ("undefined" == typeof window.ontouchstart)return !0;
        if (o = +(/Chrome\/([0-9]+)/.exec(navigator.userAgent) || [, 0])[1]) {
            if (!n)return !0;
            if (e = document.querySelector("meta[name=viewport]")) {
                if (-1 !== e.content.indexOf("user-scalable=no"))return !0;
                if (o > 31 && document.documentElement.scrollWidth <= window.outerWidth)return !0
            }
        }
        if (a && (i = navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/), i[1] >= 10 && i[2] >= 3 && (e = document.querySelector("meta[name=viewport]")))) {
            if (-1 !== e.content.indexOf("user-scalable=no"))return !0;
            if (document.documentElement.scrollWidth <= window.outerWidth)return !0
        }
        return "none" === t.style.msTouchAction || "manipulation" === t.style.touchAction ? !0 : (r = +(/Firefox\/([0-9]+)/.exec(navigator.userAgent) || [, 0])[1], r >= 27 && (e = document.querySelector("meta[name=viewport]"), e && (-1 !== e.content.indexOf("user-scalable=no") || document.documentElement.scrollWidth <= window.outerWidth)) ? !0 : "none" === t.style.touchAction || "manipulation" === t.style.touchAction ? !0 : !1)
    }, t.attach = function (e, n) {
        return new t(e, n)
    }, "function" == typeof define && "object" == typeof define.amd && define.amd ? define(function () {
        return t
    }) : "undefined" != typeof module && module.exports ? (module.exports = t.attach, module.exports.FastClick = t) : window.FastClick = t
}();


/*
 * arrive.js
 * v2.3.1
 * https://github.com/uzairfarooq/arrive
 * MIT licensed
 *
 * Copyright (c) 2014-2016 Uzair Farooq
 */

var Arrive = function (a, b, c) {
    "use strict";
    function l(a, b, c) {
        e.addMethod(b, c, a.unbindEvent), e.addMethod(b, c, a.unbindEventWithSelectorOrCallback), e.addMethod(b, c, a.unbindEventWithSelectorAndCallback)
    }

    function m(a) {
        a.arrive = j.bindEvent, l(j, a, "unbindArrive"), a.leave = k.bindEvent, l(k, a, "unbindLeave")
    }

    if (a.MutationObserver && "undefined" != typeof HTMLElement) {
        var d = 0, e = function () {
            var b = HTMLElement.prototype.matches || HTMLElement.prototype.webkitMatchesSelector || HTMLElement.prototype.mozMatchesSelector || HTMLElement.prototype.msMatchesSelector;
            return {
                matchesSelector: function (a, c) {
                    return a instanceof HTMLElement && b.call(a, c)
                }, addMethod: function (a, b, c) {
                    var d = a[b];
                    a[b] = function () {
                        return c.length == arguments.length ? c.apply(this, arguments) : "function" == typeof d ? d.apply(this, arguments) : void 0
                    }
                }, callCallbacks: function (a) {
                    for (var c, b = 0; c = a[b]; b++)c.callback.call(c.elem)
                }, checkChildNodesRecursively: function (a, b, c, d) {
                    for (var g, f = 0; g = a[f]; f++)c(g, b, d) && d.push({
                        callback: b.callback,
                        elem: g
                    }), g.childNodes.length > 0 && e.checkChildNodesRecursively(g.childNodes, b, c, d)
                }, mergeArrays: function (a, b) {
                    var d, c = {};
                    for (d in a)c[d] = a[d];
                    for (d in b)c[d] = b[d];
                    return c
                }, toElementsArray: function (b) {
                    return "undefined" == typeof b || "number" == typeof b.length && b !== a || (b = [b]), b
                }
            }
        }(), f = function () {
            var a = function () {
                this._eventsBucket = [], this._beforeAdding = null, this._beforeRemoving = null
            };
            return a.prototype.addEvent = function (a, b, c, d) {
                var e = {target: a, selector: b, options: c, callback: d, firedElems: []};
                return this._beforeAdding && this._beforeAdding(e), this._eventsBucket.push(e), e
            }, a.prototype.removeEvent = function (a) {
                for (var c, b = this._eventsBucket.length - 1; c = this._eventsBucket[b]; b--)a(c) && (this._beforeRemoving && this._beforeRemoving(c), this._eventsBucket.splice(b, 1))
            }, a.prototype.beforeAdding = function (a) {
                this._beforeAdding = a
            }, a.prototype.beforeRemoving = function (a) {
                this._beforeRemoving = a
            }, a
        }(), g = function (b, d) {
            var g = new f, h = this, i = {fireOnAttributesModification: !1};
            return g.beforeAdding(function (c) {
                var i, e = c.target;
                c.selector, c.callback;
                (e === a.document || e === a) && (e = document.getElementsByTagName("html")[0]), i = new MutationObserver(function (a) {
                    d.call(this, a, c)
                });
                var j = b(c.options);
                i.observe(e, j), c.observer = i, c.me = h
            }), g.beforeRemoving(function (a) {
                a.observer.disconnect()
            }), this.bindEvent = function (a, b, c) {
                b = e.mergeArrays(i, b);
                for (var d = e.toElementsArray(this), f = 0; f < d.length; f++)g.addEvent(d[f], a, b, c)
            }, this.unbindEvent = function () {
                var a = e.toElementsArray(this);
                g.removeEvent(function (b) {
                    for (var d = 0; d < a.length; d++)if (this === c || b.target === a[d])return !0;
                    return !1
                })
            }, this.unbindEventWithSelectorOrCallback = function (a) {
                var f, b = e.toElementsArray(this), d = a;
                f = "function" == typeof a ? function (a) {
                    for (var e = 0; e < b.length; e++)if ((this === c || a.target === b[e]) && a.callback === d)return !0;
                    return !1
                } : function (d) {
                    for (var e = 0; e < b.length; e++)if ((this === c || d.target === b[e]) && d.selector === a)return !0;
                    return !1
                }, g.removeEvent(f)
            }, this.unbindEventWithSelectorAndCallback = function (a, b) {
                var d = e.toElementsArray(this);
                g.removeEvent(function (e) {
                    for (var f = 0; f < d.length; f++)if ((this === c || e.target === d[f]) && e.selector === a && e.callback === b)return !0;
                    return !1
                })
            }, this
        }, h = function () {
            function h(a) {
                var b = {attributes: !1, childList: !0, subtree: !0};
                return a.fireOnAttributesModification && (b.attributes = !0), b
            }

            function i(a, b) {
                a.forEach(function (a) {
                    var c = a.addedNodes, d = a.target, f = [];
                    null !== c && c.length > 0 ? e.checkChildNodesRecursively(c, b, k, f) : "attributes" === a.type && k(d, b, f) && f.push({
                        callback: b.callback,
                        elem: node
                    }), e.callCallbacks(f)
                })
            }

            function k(a, b, f) {
                if (e.matchesSelector(a, b.selector) && (a._id === c && (a._id = d++), -1 == b.firedElems.indexOf(a._id))) {
                    if (b.options.onceOnly) {
                        if (0 !== b.firedElems.length)return;
                        b.me.unbindEventWithSelectorAndCallback.call(b.target, b.selector, b.callback)
                    }
                    b.firedElems.push(a._id), f.push({callback: b.callback, elem: a})
                }
            }

            var f = {fireOnAttributesModification: !1, onceOnly: !1, existing: !1};
            j = new g(h, i);
            var l = j.bindEvent;
            return j.bindEvent = function (a, b, c) {
                "undefined" == typeof c ? (c = b, b = f) : b = e.mergeArrays(f, b);
                var d = e.toElementsArray(this);
                if (b.existing) {
                    for (var g = [], h = 0; h < d.length; h++)for (var i = d[h].querySelectorAll(a), j = 0; j < i.length; j++)g.push({
                        callback: c,
                        elem: i[j]
                    });
                    if (b.onceOnly && g.length)return c.call(g[0].elem);
                    setTimeout(e.callCallbacks, 1, g)
                }
                l.call(this, a, b, c)
            }, j
        }, i = function () {
            function d(a) {
                var b = {childList: !0, subtree: !0};
                return b
            }

            function f(a, b) {
                a.forEach(function (a) {
                    var c = a.removedNodes, f = (a.target, []);
                    null !== c && c.length > 0 && e.checkChildNodesRecursively(c, b, h, f), e.callCallbacks(f)
                })
            }

            function h(a, b) {
                return e.matchesSelector(a, b.selector)
            }

            var c = {};
            k = new g(d, f);
            var i = k.bindEvent;
            return k.bindEvent = function (a, b, d) {
                "undefined" == typeof d ? (d = b, b = c) : b = e.mergeArrays(c, b), i.call(this, a, b, d)
            }, k
        }, j = new h, k = new i;
        b && m(b.fn), m(HTMLElement.prototype), m(NodeList.prototype), m(HTMLCollection.prototype), m(HTMLDocument.prototype), m(Window.prototype);
        var n = {};
        return l(j, n, "unbindAllArrive"), l(k, n, "unbindAllLeave"), n
    }
}(window, "undefined" == typeof jQuery ? null : jQuery, void 0);