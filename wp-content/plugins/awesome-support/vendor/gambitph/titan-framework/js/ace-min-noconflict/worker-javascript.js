"no use strict";
(function (e) {
    function t(e, t) {
        var n = e, r = "";
        while (n) {
            var i = t[n];
            if (typeof i == "string")return i + r;
            if (i)return i.location.replace(/\/*$/, "/") + (r || i.main || i.name);
            if (i === !1)return "";
            var s = n.lastIndexOf("/");
            if (s === -1)break;
            r = n.substr(s) + r, n = n.slice(0, s)
        }
        return e
    }

    if (typeof e.window != "undefined" && e.document)return;
    if (e.require && e.define)return;
    e.console = function () {
        var e = Array.prototype.slice.call(arguments, 0);
        postMessage({type: "log", data: e})
    }, e.console.error = e.console.warn = e.console.log = e.console.trace = e.console, e.window = e, e.ace = e, e.onerror = function (e, t, n, r, i) {
        postMessage({type: "error", data: {message: e, data: i.data, file: t, line: n, col: r, stack: i.stack}})
    }, e.normalizeModule = function (t, n) {
        if (n.indexOf("!") !== -1) {
            var r = n.split("!");
            return e.normalizeModule(t, r[0]) + "!" + e.normalizeModule(t, r[1])
        }
        if (n.charAt(0) == ".") {
            var i = t.split("/").slice(0, -1).join("/");
            n = (i ? i + "/" : "") + n;
            while (n.indexOf(".") !== -1 && s != n) {
                var s = n;
                n = n.replace(/^\.\//, "").replace(/\/\.\//, "/").replace(/[^\/]+\/\.\.\//, "")
            }
        }
        return n
    }, e.require = function (r, i) {
        i || (i = r, r = null);
        if (!i.charAt)throw new Error("worker.js require() accepts only (parentId, id) as arguments");
        i = e.normalizeModule(r, i);
        var s = e.require.modules[i];
        if (s)return s.initialized || (s.initialized = !0, s.exports = s.factory().exports), s.exports;
        if (!e.require.tlns)return console.log("unable to load " + i);
        var o = t(i, e.require.tlns);
        return o.slice(-3) != ".js" && (o += ".js"), e.require.id = i, e.require.modules[i] = {}, importScripts(o), e.require(r, i)
    }, e.require.modules = {}, e.require.tlns = {}, e.define = function (t, n, r) {
        arguments.length == 2 ? (r = n, typeof t != "string" && (n = t, t = e.require.id)) : arguments.length == 1 && (r = t, n = [], t = e.require.id);
        if (typeof r != "function") {
            e.require.modules[t] = {exports: r, initialized: !0};
            return
        }
        n.length || (n = ["require", "exports", "module"]);
        var i = function (n) {
            return e.require(t, n)
        };
        e.require.modules[t] = {
            exports: {}, factory: function () {
                var e = this, t = r.apply(this, n.map(function (t) {
                    switch (t) {
                        case"require":
                            return i;
                        case"exports":
                            return e.exports;
                        case"module":
                            return e;
                        default:
                            return i(t)
                    }
                }));
                return t && (e.exports = t), e
            }
        }
    }, e.define.amd = {}, require.tlns = {}, e.initBaseUrls = function (t) {
        for (var n in t)require.tlns[n] = t[n]
    }, e.initSender = function () {
        var n = e.require("ace/lib/event_emitter").EventEmitter, r = e.require("ace/lib/oop"), i = function () {
        };
        return function () {
            r.implement(this, n), this.callback = function (e, t) {
                postMessage({type: "call", id: t, data: e})
            }, this.emit = function (e, t) {
                postMessage({type: "event", name: e, data: t})
            }
        }.call(i.prototype), new i
    };
    var n = e.main = null, r = e.sender = null;
    e.onmessage = function (t) {
        var i = t.data;
        if (i.event && r)r._signal(i.event, i.data); else if (i.command)if (n[i.command])n[i.command].apply(n, i.args); else {
            if (!e[i.command])throw new Error("Unknown command:" + i.command);
            e[i.command].apply(e, i.args)
        } else if (i.init) {
            e.initBaseUrls(i.tlns), require("ace/lib/es5-shim"), r = e.sender = e.initSender();
            var s = require(i.module)[i.classname];
            n = e.main = new s(r)
        }
    }
})(this), ace.define("ace/lib/oop", ["require", "exports", "module"], function (e, t, n) {
    "use strict";
    t.inherits = function (e, t) {
        e.super_ = t, e.prototype = Object.create(t.prototype, {
            constructor: {
                value: e,
                enumerable: !1,
                writable: !0,
                configurable: !0
            }
        })
    }, t.mixin = function (e, t) {
        for (var n in t)e[n] = t[n];
        return e
    }, t.implement = function (e, n) {
        t.mixin(e, n)
    }
}), ace.define("ace/range", ["require", "exports", "module"], function (e, t, n) {
    "use strict";
    var r = function (e, t) {
        return e.row - t.row || e.column - t.column
    }, i = function (e, t, n, r) {
        this.start = {row: e, column: t}, this.end = {row: n, column: r}
    };
    (function () {
        this.isEqual = function (e) {
            return this.start.row === e.start.row && this.end.row === e.end.row && this.start.column === e.start.column && this.end.column === e.end.column
        }, this.toString = function () {
            return "Range: [" + this.start.row + "/" + this.start.column + "] -> [" + this.end.row + "/" + this.end.column + "]"
        }, this.contains = function (e, t) {
            return this.compare(e, t) == 0
        }, this.compareRange = function (e) {
            var t, n = e.end, r = e.start;
            return t = this.compare(n.row, n.column), t == 1 ? (t = this.compare(r.row, r.column), t == 1 ? 2 : t == 0 ? 1 : 0) : t == -1 ? -2 : (t = this.compare(r.row, r.column), t == -1 ? -1 : t == 1 ? 42 : 0)
        }, this.comparePoint = function (e) {
            return this.compare(e.row, e.column)
        }, this.containsRange = function (e) {
            return this.comparePoint(e.start) == 0 && this.comparePoint(e.end) == 0
        }, this.intersects = function (e) {
            var t = this.compareRange(e);
            return t == -1 || t == 0 || t == 1
        }, this.isEnd = function (e, t) {
            return this.end.row == e && this.end.column == t
        }, this.isStart = function (e, t) {
            return this.start.row == e && this.start.column == t
        }, this.setStart = function (e, t) {
            typeof e == "object" ? (this.start.column = e.column, this.start.row = e.row) : (this.start.row = e, this.start.column = t)
        }, this.setEnd = function (e, t) {
            typeof e == "object" ? (this.end.column = e.column, this.end.row = e.row) : (this.end.row = e, this.end.column = t)
        }, this.inside = function (e, t) {
            return this.compare(e, t) == 0 ? this.isEnd(e, t) || this.isStart(e, t) ? !1 : !0 : !1
        }, this.insideStart = function (e, t) {
            return this.compare(e, t) == 0 ? this.isEnd(e, t) ? !1 : !0 : !1
        }, this.insideEnd = function (e, t) {
            return this.compare(e, t) == 0 ? this.isStart(e, t) ? !1 : !0 : !1
        }, this.compare = function (e, t) {
            return !this.isMultiLine() && e === this.start.row ? t < this.start.column ? -1 : t > this.end.column ? 1 : 0 : e < this.start.row ? -1 : e > this.end.row ? 1 : this.start.row === e ? t >= this.start.column ? 0 : -1 : this.end.row === e ? t <= this.end.column ? 0 : 1 : 0
        }, this.compareStart = function (e, t) {
            return this.start.row == e && this.start.column == t ? -1 : this.compare(e, t)
        }, this.compareEnd = function (e, t) {
            return this.end.row == e && this.end.column == t ? 1 : this.compare(e, t)
        }, this.compareInside = function (e, t) {
            return this.end.row == e && this.end.column == t ? 1 : this.start.row == e && this.start.column == t ? -1 : this.compare(e, t)
        }, this.clipRows = function (e, t) {
            if (this.end.row > t)var n = {row: t + 1, column: 0}; else if (this.end.row < e)var n = {row: e, column: 0};
            if (this.start.row > t)var r = {row: t + 1, column: 0}; else if (this.start.row < e)var r = {
                row: e,
                column: 0
            };
            return i.fromPoints(r || this.start, n || this.end)
        }, this.extend = function (e, t) {
            var n = this.compare(e, t);
            if (n == 0)return this;
            if (n == -1)var r = {row: e, column: t}; else var s = {row: e, column: t};
            return i.fromPoints(r || this.start, s || this.end)
        }, this.isEmpty = function () {
            return this.start.row === this.end.row && this.start.column === this.end.column
        }, this.isMultiLine = function () {
            return this.start.row !== this.end.row
        }, this.clone = function () {
            return i.fromPoints(this.start, this.end)
        }, this.collapseRows = function () {
            return this.end.column == 0 ? new i(this.start.row, 0, Math.max(this.start.row, this.end.row - 1), 0) : new i(this.start.row, 0, this.end.row, 0)
        }, this.toScreenRange = function (e) {
            var t = e.documentToScreenPosition(this.start), n = e.documentToScreenPosition(this.end);
            return new i(t.row, t.column, n.row, n.column)
        }, this.moveBy = function (e, t) {
            this.start.row += e, this.start.column += t, this.end.row += e, this.end.column += t
        }
    }).call(i.prototype), i.fromPoints = function (e, t) {
        return new i(e.row, e.column, t.row, t.column)
    }, i.comparePoints = r, i.comparePoints = function (e, t) {
        return e.row - t.row || e.column - t.column
    }, t.Range = i
}), ace.define("ace/apply_delta", ["require", "exports", "module"], function (e, t, n) {
    "use strict";
    function r(e, t) {
        throw console.log("Invalid Delta:", e), "Invalid Delta: " + t
    }

    function i(e, t) {
        return t.row >= 0 && t.row < e.length && t.column >= 0 && t.column <= e[t.row].length
    }

    function s(e, t) {
        t.action != "insert" && t.action != "remove" && r(t, "delta.action must be 'insert' or 'remove'"), t.lines instanceof Array || r(t, "delta.lines must be an Array"), (!t.start || !t.end) && r(t, "delta.start/end must be an present");
        var n = t.start;
        i(e, t.start) || r(t, "delta.start must be contained in document");
        var s = t.end;
        t.action == "remove" && !i(e, s) && r(t, "delta.end must contained in document for 'remove' actions");
        var o = s.row - n.row, u = s.column - (o == 0 ? n.column : 0);
        (o != t.lines.length - 1 || t.lines[o].length != u) && r(t, "delta.range must match delta lines")
    }

    t.applyDelta = function (e, t, n) {
        var r = t.start.row, i = t.start.column, s = e[r] || "";
        switch (t.action) {
            case"insert":
                var o = t.lines;
                if (o.length === 1)e[r] = s.substring(0, i) + t.lines[0] + s.substring(i); else {
                    var u = [r, 1].concat(t.lines);
                    e.splice.apply(e, u), e[r] = s.substring(0, i) + e[r], e[r + t.lines.length - 1] += s.substring(i)
                }
                break;
            case"remove":
                var a = t.end.column, f = t.end.row;
                r === f ? e[r] = s.substring(0, i) + s.substring(a) : e.splice(r, f - r + 1, s.substring(0, i) + e[f].substring(a))
        }
    }
}), ace.define("ace/lib/event_emitter", ["require", "exports", "module"], function (e, t, n) {
    "use strict";
    var r = {}, i = function () {
        this.propagationStopped = !0
    }, s = function () {
        this.defaultPrevented = !0
    };
    r._emit = r._dispatchEvent = function (e, t) {
        this._eventRegistry || (this._eventRegistry = {}), this._defaultHandlers || (this._defaultHandlers = {});
        var n = this._eventRegistry[e] || [], r = this._defaultHandlers[e];
        if (!n.length && !r)return;
        if (typeof t != "object" || !t)t = {};
        t.type || (t.type = e), t.stopPropagation || (t.stopPropagation = i), t.preventDefault || (t.preventDefault = s), n = n.slice();
        for (var o = 0; o < n.length; o++) {
            n[o](t, this);
            if (t.propagationStopped)break
        }
        if (r && !t.defaultPrevented)return r(t, this)
    }, r._signal = function (e, t) {
        var n = (this._eventRegistry || {})[e];
        if (!n)return;
        n = n.slice();
        for (var r = 0; r < n.length; r++)n[r](t, this)
    }, r.once = function (e, t) {
        var n = this;
        t && this.addEventListener(e, function r() {
            n.removeEventListener(e, r), t.apply(null, arguments)
        })
    }, r.setDefaultHandler = function (e, t) {
        var n = this._defaultHandlers;
        n || (n = this._defaultHandlers = {_disabled_: {}});
        if (n[e]) {
            var r = n[e], i = n._disabled_[e];
            i || (n._disabled_[e] = i = []), i.push(r);
            var s = i.indexOf(t);
            s != -1 && i.splice(s, 1)
        }
        n[e] = t
    }, r.removeDefaultHandler = function (e, t) {
        var n = this._defaultHandlers;
        if (!n)return;
        var r = n._disabled_[e];
        if (n[e] == t) {
            var i = n[e];
            r && this.setDefaultHandler(e, r.pop())
        } else if (r) {
            var s = r.indexOf(t);
            s != -1 && r.splice(s, 1)
        }
    }, r.on = r.addEventListener = function (e, t, n) {
        this._eventRegistry = this._eventRegistry || {};
        var r = this._eventRegistry[e];
        return r || (r = this._eventRegistry[e] = []), r.indexOf(t) == -1 && r[n ? "unshift" : "push"](t), t
    }, r.off = r.removeListener = r.removeEventListener = function (e, t) {
        this._eventRegistry = this._eventRegistry || {};
        var n = this._eventRegistry[e];
        if (!n)return;
        var r = n.indexOf(t);
        r !== -1 && n.splice(r, 1)
    }, r.removeAllListeners = function (e) {
        this._eventRegistry && (this._eventRegistry[e] = [])
    }, t.EventEmitter = r
}), ace.define("ace/anchor", ["require", "exports", "module", "ace/lib/oop", "ace/lib/event_emitter"], function (e, t, n) {
    "use strict";
    var r = e("./lib/oop"), i = e("./lib/event_emitter").EventEmitter, s = t.Anchor = function (e, t, n) {
        this.$onChange = this.onChange.bind(this), this.attach(e), typeof n == "undefined" ? this.setPosition(t.row, t.column) : this.setPosition(t, n)
    };
    (function () {
        function e(e, t, n) {
            var r = n ? e.column <= t.column : e.column < t.column;
            return e.row < t.row || e.row == t.row && r
        }

        function t(t, n, r) {
            var i = t.action == "insert", s = (i ? 1 : -1) * (t.end.row - t.start.row), o = (i ? 1 : -1) * (t.end.column - t.start.column), u = t.start, a = i ? u : t.end;
            return e(n, u, r) ? {row: n.row, column: n.column} : e(a, n, !r) ? {
                row: n.row + s,
                column: n.column + (n.row == a.row ? o : 0)
            } : {row: u.row, column: u.column}
        }

        r.implement(this, i), this.getPosition = function () {
            return this.$clipPositionToDocument(this.row, this.column)
        }, this.getDocument = function () {
            return this.document
        }, this.$insertRight = !1, this.onChange = function (e) {
            if (e.start.row == e.end.row && e.start.row != this.row)return;
            if (e.start.row > this.row)return;
            var n = t(e, {row: this.row, column: this.column}, this.$insertRight);
            this.setPosition(n.row, n.column, !0)
        }, this.setPosition = function (e, t, n) {
            var r;
            n ? r = {row: e, column: t} : r = this.$clipPositionToDocument(e, t);
            if (this.row == r.row && this.column == r.column)return;
            var i = {row: this.row, column: this.column};
            this.row = r.row, this.column = r.column, this._signal("change", {old: i, value: r})
        }, this.detach = function () {
            this.document.removeEventListener("change", this.$onChange)
        }, this.attach = function (e) {
            this.document = e || this.document, this.document.on("change", this.$onChange)
        }, this.$clipPositionToDocument = function (e, t) {
            var n = {};
            return e >= this.document.getLength() ? (n.row = Math.max(0, this.document.getLength() - 1), n.column = this.document.getLine(n.row).length) : e < 0 ? (n.row = 0, n.column = 0) : (n.row = e, n.column = Math.min(this.document.getLine(n.row).length, Math.max(0, t))), t < 0 && (n.column = 0), n
        }
    }).call(s.prototype)
}), ace.define("ace/document", ["require", "exports", "module", "ace/lib/oop", "ace/apply_delta", "ace/lib/event_emitter", "ace/range", "ace/anchor"], function (e, t, n) {
    "use strict";
    var r = e("./lib/oop"), i = e("./apply_delta").applyDelta, s = e("./lib/event_emitter").EventEmitter, o = e("./range").Range, u = e("./anchor").Anchor, a = function (e) {
        this.$lines = [""], e.length === 0 ? this.$lines = [""] : Array.isArray(e) ? this.insertMergedLines({
            row: 0,
            column: 0
        }, e) : this.insert({row: 0, column: 0}, e)
    };
    (function () {
        r.implement(this, s), this.setValue = function (e) {
            var t = this.getLength() - 1;
            this.remove(new o(0, 0, t, this.getLine(t).length)), this.insert({row: 0, column: 0}, e)
        }, this.getValue = function () {
            return this.getAllLines().join(this.getNewLineCharacter())
        }, this.createAnchor = function (e, t) {
            return new u(this, e, t)
        }, "aaa".split(/a/).length === 0 ? this.$split = function (e) {
            return e.replace(/\r\n|\r/g, "\n").split("\n")
        } : this.$split = function (e) {
            return e.split(/\r\n|\r|\n/)
        }, this.$detectNewLine = function (e) {
            var t = e.match(/^.*?(\r\n|\r|\n)/m);
            this.$autoNewLine = t ? t[1] : "\n", this._signal("changeNewLineMode")
        }, this.getNewLineCharacter = function () {
            switch (this.$newLineMode) {
                case"windows":
                    return "\r\n";
                case"unix":
                    return "\n";
                default:
                    return this.$autoNewLine || "\n"
            }
        }, this.$autoNewLine = "", this.$newLineMode = "auto", this.setNewLineMode = function (e) {
            if (this.$newLineMode === e)return;
            this.$newLineMode = e, this._signal("changeNewLineMode")
        }, this.getNewLineMode = function () {
            return this.$newLineMode
        }, this.isNewLine = function (e) {
            return e == "\r\n" || e == "\r" || e == "\n"
        }, this.getLine = function (e) {
            return this.$lines[e] || ""
        }, this.getLines = function (e, t) {
            return this.$lines.slice(e, t + 1)
        }, this.getAllLines = function () {
            return this.getLines(0, this.getLength())
        }, this.getLength = function () {
            return this.$lines.length
        }, this.getTextRange = function (e) {
            return this.getLinesForRange(e).join(this.getNewLineCharacter())
        }, this.getLinesForRange = function (e) {
            var t;
            if (e.start.row === e.end.row)t = [this.getLine(e.start.row).substring(e.start.column, e.end.column)]; else {
                t = this.getLines(e.start.row, e.end.row), t[0] = (t[0] || "").substring(e.start.column);
                var n = t.length - 1;
                e.end.row - e.start.row == n && (t[n] = t[n].substring(0, e.end.column))
            }
            return t
        }, this.insertLines = function (e, t) {
            return console.warn("Use of document.insertLines is deprecated. Use the insertFullLines method instead."), this.insertFullLines(e, t)
        }, this.removeLines = function (e, t) {
            return console.warn("Use of document.removeLines is deprecated. Use the removeFullLines method instead."), this.removeFullLines(e, t)
        }, this.insertNewLine = function (e) {
            return console.warn("Use of document.insertNewLine is deprecated. Use insertMergedLines(position, ['', '']) instead."), this.insertMergedLines(e, ["", ""])
        }, this.insert = function (e, t) {
            return this.getLength() <= 1 && this.$detectNewLine(t), this.insertMergedLines(e, this.$split(t))
        }, this.insertInLine = function (e, t) {
            var n = this.clippedPos(e.row, e.column), r = this.pos(e.row, e.column + t.length);
            return this.applyDelta({start: n, end: r, action: "insert", lines: [t]}, !0), this.clonePos(r)
        }, this.clippedPos = function (e, t) {
            var n = this.getLength();
            e === undefined ? e = n : e < 0 ? e = 0 : e >= n && (e = n - 1, t = undefined);
            var r = this.getLine(e);
            return t == undefined && (t = r.length), t = Math.min(Math.max(t, 0), r.length), {row: e, column: t}
        }, this.clonePos = function (e) {
            return {row: e.row, column: e.column}
        }, this.pos = function (e, t) {
            return {row: e, column: t}
        }, this.$clipPosition = function (e) {
            var t = this.getLength();
            return e.row >= t ? (e.row = Math.max(0, t - 1), e.column = this.getLine(t - 1).length) : (e.row = Math.max(0, e.row), e.column = Math.min(Math.max(e.column, 0), this.getLine(e.row).length)), e
        }, this.insertFullLines = function (e, t) {
            e = Math.min(Math.max(e, 0), this.getLength());
            var n = 0;
            e < this.getLength() ? (t = t.concat([""]), n = 0) : (t = [""].concat(t), e--, n = this.$lines[e].length), this.insertMergedLines({
                row: e,
                column: n
            }, t)
        }, this.insertMergedLines = function (e, t) {
            var n = this.clippedPos(e.row, e.column), r = {
                row: n.row + t.length - 1,
                column: (t.length == 1 ? n.column : 0) + t[t.length - 1].length
            };
            return this.applyDelta({start: n, end: r, action: "insert", lines: t}), this.clonePos(r)
        }, this.remove = function (e) {
            var t = this.clippedPos(e.start.row, e.start.column), n = this.clippedPos(e.end.row, e.end.column);
            return this.applyDelta({
                start: t,
                end: n,
                action: "remove",
                lines: this.getLinesForRange({start: t, end: n})
            }), this.clonePos(t)
        }, this.removeInLine = function (e, t, n) {
            var r = this.clippedPos(e, t), i = this.clippedPos(e, n);
            return this.applyDelta({
                start: r,
                end: i,
                action: "remove",
                lines: this.getLinesForRange({start: r, end: i})
            }, !0), this.clonePos(r)
        }, this.removeFullLines = function (e, t) {
            e = Math.min(Math.max(0, e), this.getLength() - 1), t = Math.min(Math.max(0, t), this.getLength() - 1);
            var n = t == this.getLength() - 1 && e > 0, r = t < this.getLength() - 1, i = n ? e - 1 : e, s = n ? this.getLine(i).length : 0, u = r ? t + 1 : t, a = r ? 0 : this.getLine(u).length, f = new o(i, s, u, a), l = this.$lines.slice(e, t + 1);
            return this.applyDelta({start: f.start, end: f.end, action: "remove", lines: this.getLinesForRange(f)}), l
        }, this.removeNewLine = function (e) {
            e < this.getLength() - 1 && e >= 0 && this.applyDelta({
                start: this.pos(e, this.getLine(e).length),
                end: this.pos(e + 1, 0),
                action: "remove",
                lines: ["", ""]
            })
        }, this.replace = function (e, t) {
            !e instanceof o && (e = o.fromPoints(e.start, e.end));
            if (t.length === 0 && e.isEmpty())return e.start;
            if (t == this.getTextRange(e))return e.end;
            this.remove(e);
            var n;
            return t ? n = this.insert(e.start, t) : n = e.start, n
        }, this.applyDeltas = function (e) {
            for (var t = 0; t < e.length; t++)this.applyDelta(e[t])
        }, this.revertDeltas = function (e) {
            for (var t = e.length - 1; t >= 0; t--)this.revertDelta(e[t])
        }, this.applyDelta = function (e, t) {
            var n = e.action == "insert";
            if (n ? e.lines.length <= 1 && !e.lines[0] : !o.comparePoints(e.start, e.end))return;
            n && e.lines.length > 2e4 && this.$splitAndapplyLargeDelta(e, 2e4), i(this.$lines, e, t), this._signal("change", e)
        }, this.$splitAndapplyLargeDelta = function (e, t) {
            var n = e.lines, r = n.length, i = e.start.row, s = e.start.column, o = 0, u = 0;
            do {
                o = u, u += t - 1;
                var a = n.slice(o, u);
                if (u > r) {
                    e.lines = a, e.start.row = i + o, e.start.column = s;
                    break
                }
                a.push(""), this.applyDelta({
                    start: this.pos(i + o, s),
                    end: this.pos(i + u, s = 0),
                    action: e.action,
                    lines: a
                }, !0)
            } while (!0)
        }, this.revertDelta = function (e) {
            this.applyDelta({
                start: this.clonePos(e.start),
                end: this.clonePos(e.end),
                action: e.action == "insert" ? "remove" : "insert",
                lines: e.lines.slice()
            })
        }, this.indexToPosition = function (e, t) {
            var n = this.$lines || this.getAllLines(), r = this.getNewLineCharacter().length;
            for (var i = t || 0, s = n.length; i < s; i++) {
                e -= n[i].length + r;
                if (e < 0)return {row: i, column: e + n[i].length + r}
            }
            return {row: s - 1, column: n[s - 1].length}
        }, this.positionToIndex = function (e, t) {
            var n = this.$lines || this.getAllLines(), r = this.getNewLineCharacter().length, i = 0, s = Math.min(e.row, n.length);
            for (var o = t || 0; o < s; ++o)i += n[o].length + r;
            return i + e.column
        }
    }).call(a.prototype), t.Document = a
}), ace.define("ace/lib/lang", ["require", "exports", "module"], function (e, t, n) {
    "use strict";
    t.last = function (e) {
        return e[e.length - 1]
    }, t.stringReverse = function (e) {
        return e.split("").reverse().join("")
    }, t.stringRepeat = function (e, t) {
        var n = "";
        while (t > 0) {
            t & 1 && (n += e);
            if (t >>= 1)e += e
        }
        return n
    };
    var r = /^\s\s*/, i = /\s\s*$/;
    t.stringTrimLeft = function (e) {
        return e.replace(r, "")
    }, t.stringTrimRight = function (e) {
        return e.replace(i, "")
    }, t.copyObject = function (e) {
        var t = {};
        for (var n in e)t[n] = e[n];
        return t
    }, t.copyArray = function (e) {
        var t = [];
        for (var n = 0, r = e.length; n < r; n++)e[n] && typeof e[n] == "object" ? t[n] = this.copyObject(e[n]) : t[n] = e[n];
        return t
    }, t.deepCopy = function s(e) {
        if (typeof e != "object" || !e)return e;
        var t;
        if (Array.isArray(e)) {
            t = [];
            for (var n = 0; n < e.length; n++)t[n] = s(e[n]);
            return t
        }
        var r = e.constructor;
        if (r === RegExp)return e;
        t = r();
        for (var n in e)t[n] = s(e[n]);
        return t
    }, t.arrayToMap = function (e) {
        var t = {};
        for (var n = 0; n < e.length; n++)t[e[n]] = 1;
        return t
    }, t.createMap = function (e) {
        var t = Object.create(null);
        for (var n in e)t[n] = e[n];
        return t
    }, t.arrayRemove = function (e, t) {
        for (var n = 0; n <= e.length; n++)t === e[n] && e.splice(n, 1)
    }, t.escapeRegExp = function (e) {
        return e.replace(/([.*+?^${}()|[\]\/\\])/g, "\\$1")
    }, t.escapeHTML = function (e) {
        return e.replace(/&/g, "&#38;").replace(/"/g, "&#34;").replace(/'/g, "&#39;").replace(/</g, "&#60;")
    }, t.getMatchOffsets = function (e, t) {
        var n = [];
        return e.replace(t, function (e) {
            n.push({offset: arguments[arguments.length - 2], length: e.length})
        }), n
    }, t.deferredCall = function (e) {
        var t = null, n = function () {
            t = null, e()
        }, r = function (e) {
            return r.cancel(), t = setTimeout(n, e || 0), r
        };
        return r.schedule = r, r.call = function () {
            return this.cancel(), e(), r
        }, r.cancel = function () {
            return clearTimeout(t), t = null, r
        }, r.isPending = function () {
            return t
        }, r
    }, t.delayedCall = function (e, t) {
        var n = null, r = function () {
            n = null, e()
        }, i = function (e) {
            n == null && (n = setTimeout(r, e || t))
        };
        return i.delay = function (e) {
            n && clearTimeout(n), n = setTimeout(r, e || t)
        }, i.schedule = i, i.call = function () {
            this.cancel(), e()
        }, i.cancel = function () {
            n && clearTimeout(n), n = null
        }, i.isPending = function () {
            return n
        }, i
    }
}), ace.define("ace/worker/mirror", ["require", "exports", "module", "ace/range", "ace/document", "ace/lib/lang"], function (e, t, n) {
    "use strict";
    var r = e("../range").Range, i = e("../document").Document, s = e("../lib/lang"), o = t.Mirror = function (e) {
        this.sender = e;
        var t = this.doc = new i(""), n = this.deferredUpdate = s.delayedCall(this.onUpdate.bind(this)), r = this;
        e.on("change", function (e) {
            var i = e.data;
            if (i[0].start)t.applyDeltas(i); else for (var s = 0; s < i.length; s += 2) {
                if (Array.isArray(i[s + 1]))var o = {
                    action: "insert",
                    start: i[s],
                    lines: i[s + 1]
                }; else var o = {action: "remove", start: i[s], end: i[s + 1]};
                t.applyDelta(o, !0)
            }
            if (r.$timeout)return n.schedule(r.$timeout);
            r.onUpdate()
        })
    };
    (function () {
        this.$timeout = 500, this.setTimeout = function (e) {
            this.$timeout = e
        }, this.setValue = function (e) {
            this.doc.setValue(e), this.deferredUpdate.schedule(this.$timeout)
        }, this.getValue = function (e) {
            this.sender.callback(this.doc.getValue(), e)
        }, this.onUpdate = function () {
        }, this.isPending = function () {
            return this.deferredUpdate.isPending()
        }
    }).call(o.prototype)
}), ace.define("ace/mode/javascript/jshint", ["require", "exports", "module"], function (e, t, n) {
    n.exports = function r(t, n, i) {
        function o(u, a) {
            if (!n[u]) {
                if (!t[u]) {
                    var f = typeof e == "function" && e;
                    if (!a && f)return f(u, !0);
                    if (s)return s(u, !0);
                    var l = new Error("Cannot find module '" + u + "'");
                    throw l.code = "MODULE_NOT_FOUND", l
                }
                var c = n[u] = {exports: {}};
                t[u][0].call(c.exports, function (e) {
                    var n = t[u][1][e];
                    return o(n ? n : e)
                }, c, c.exports, r, t, n, i)
            }
            return n[u].exports
        }

        var s = typeof e == "function" && e;
        for (var u = 0; u < i.length; u++)o(i[u]);
        return o(i[0])
    }({
        "/node_modules/browserify/node_modules/events/events.js": [function (e, t, n) {
            function r() {
                this._events = this._events || {}, this._maxListeners = this._maxListeners || undefined
            }

            function i(e) {
                return typeof e == "function"
            }

            function s(e) {
                return typeof e == "number"
            }

            function o(e) {
                return typeof e == "object" && e !== null
            }

            function u(e) {
                return e === void 0
            }

            t.exports = r, r.EventEmitter = r, r.prototype._events = undefined, r.prototype._maxListeners = undefined, r.defaultMaxListeners = 10, r.prototype.setMaxListeners = function (e) {
                if (!s(e) || e < 0 || isNaN(e))throw TypeError("n must be a positive number");
                return this._maxListeners = e, this
            }, r.prototype.emit = function (e) {
                var t, n, r, s, a, f;
                this._events || (this._events = {});
                if (e === "error")if (!this._events.error || o(this._events.error) && !this._events.error.length)throw t = arguments[1], t instanceof Error ? t : TypeError('Uncaught, unspecified "error" event.');
                n = this._events[e];
                if (u(n))return !1;
                if (i(n))switch (arguments.length) {
                    case 1:
                        n.call(this);
                        break;
                    case 2:
                        n.call(this, arguments[1]);
                        break;
                    case 3:
                        n.call(this, arguments[1], arguments[2]);
                        break;
                    default:
                        r = arguments.length, s = new Array(r - 1);
                        for (a = 1; a < r; a++)s[a - 1] = arguments[a];
                        n.apply(this, s)
                } else if (o(n)) {
                    r = arguments.length, s = new Array(r - 1);
                    for (a = 1; a < r; a++)s[a - 1] = arguments[a];
                    f = n.slice(), r = f.length;
                    for (a = 0; a < r; a++)f[a].apply(this, s)
                }
                return !0
            }, r.prototype.addListener = function (e, t) {
                var n;
                if (!i(t))throw TypeError("listener must be a function");
                this._events || (this._events = {}), this._events.newListener && this.emit("newListener", e, i(t.listener) ? t.listener : t), this._events[e] ? o(this._events[e]) ? this._events[e].push(t) : this._events[e] = [this._events[e], t] : this._events[e] = t;
                if (o(this._events[e]) && !this._events[e].warned) {
                    var n;
                    u(this._maxListeners) ? n = r.defaultMaxListeners : n = this._maxListeners, n && n > 0 && this._events[e].length > n && (this._events[e].warned = !0, console.error("(node) warning: possible EventEmitter memory leak detected. %d listeners added. Use emitter.setMaxListeners() to increase limit.", this._events[e].length), typeof console.trace == "function" && console.trace())
                }
                return this
            }, r.prototype.on = r.prototype.addListener, r.prototype.once = function (e, t) {
                function r() {
                    this.removeListener(e, r), n || (n = !0, t.apply(this, arguments))
                }

                if (!i(t))throw TypeError("listener must be a function");
                var n = !1;
                return r.listener = t, this.on(e, r), this
            }, r.prototype.removeListener = function (e, t) {
                var n, r, s, u;
                if (!i(t))throw TypeError("listener must be a function");
                if (!this._events || !this._events[e])return this;
                n = this._events[e], s = n.length, r = -1;
                if (n === t || i(n.listener) && n.listener === t)delete this._events[e], this._events.removeListener && this.emit("removeListener", e, t); else if (o(n)) {
                    for (u = s; u-- > 0;)if (n[u] === t || n[u].listener && n[u].listener === t) {
                        r = u;
                        break
                    }
                    if (r < 0)return this;
                    n.length === 1 ? (n.length = 0, delete this._events[e]) : n.splice(r, 1), this._events.removeListener && this.emit("removeListener", e, t)
                }
                return this
            }, r.prototype.removeAllListeners = function (e) {
                var t, n;
                if (!this._events)return this;
                if (!this._events.removeListener)return arguments.length === 0 ? this._events = {} : this._events[e] && delete this._events[e], this;
                if (arguments.length === 0) {
                    for (t in this._events) {
                        if (t === "removeListener")continue;
                        this.removeAllListeners(t)
                    }
                    return this.removeAllListeners("removeListener"), this._events = {}, this
                }
                n = this._events[e];
                if (i(n))this.removeListener(e, n); else while (n.length)this.removeListener(e, n[n.length - 1]);
                return delete this._events[e], this
            }, r.prototype.listeners = function (e) {
                var t;
                return !this._events || !this._events[e] ? t = [] : i(this._events[e]) ? t = [this._events[e]] : t = this._events[e].slice(), t
            }, r.listenerCount = function (e, t) {
                var n;
                return !e._events || !e._events[t] ? n = 0 : i(e._events[t]) ? n = 1 : n = e._events[t].length, n
            }
        }, {}],
        "/node_modules/jshint/data/ascii-identifier-data.js": [function (e, t, n) {
            var r = [];
            for (var i = 0; i < 128; i++)r[i] = i === 36 || i >= 65 && i <= 90 || i === 95 || i >= 97 && i <= 122;
            var s = [];
            for (var i = 0; i < 128; i++)s[i] = r[i] || i >= 48 && i <= 57;
            t.exports = {asciiIdentifierStartTable: r, asciiIdentifierPartTable: s}
        }, {}],
        "/node_modules/jshint/node_modules/underscore/underscore.js": [function (e, t, n) {
            (function () {
                function N(e) {
                    function t(t, n, r, i, s, o) {
                        for (; s >= 0 && s < o; s += e) {
                            var u = i ? i[s] : s;
                            r = n(r, t[u], u, t)
                        }
                        return r
                    }

                    return function (n, r, i, s) {
                        r = g(r, s, 4);
                        var o = !T(n) && m.keys(n), u = (o || n).length, a = e > 0 ? 0 : u - 1;
                        return arguments.length < 3 && (i = n[o ? o[a] : a], a += e), t(n, r, i, o, a, u)
                    }
                }

                function L(e) {
                    return function (t, n, r) {
                        n = y(n, r);
                        var i = x(t), s = e > 0 ? 0 : i - 1;
                        for (; s >= 0 && s < i; s += e)if (n(t[s], s, t))return s;
                        return -1
                    }
                }

                function A(e, t, n) {
                    return function (r, i, s) {
                        var o = 0, u = x(r);
                        if (typeof s == "number")e > 0 ? o = s >= 0 ? s : Math.max(s + u, o) : u = s >= 0 ? Math.min(s + 1, u) : s + u + 1; else if (n && s && u)return s = n(r, i), r[s] === i ? s : -1;
                        if (i !== i)return s = t(a.call(r, o, u), m.isNaN), s >= 0 ? s + o : -1;
                        for (s = e > 0 ? o : u - 1; s >= 0 && s < u; s += e)if (r[s] === i)return s;
                        return -1
                    }
                }

                function D(e, t) {
                    var n = _.length, r = e.constructor, i = m.isFunction(r) && r.prototype || s, o = "constructor";
                    m.has(e, o) && !m.contains(t, o) && t.push(o);
                    while (n--)o = _[n], o in e && e[o] !== i[o] && !m.contains(t, o) && t.push(o)
                }

                var e = this, r = e._, i = Array.prototype, s = Object.prototype, o = Function.prototype, u = i.push, a = i.slice, f = s.toString, l = s.hasOwnProperty, c = Array.isArray, h = Object.keys, p = o.bind, d = Object.create, v = function () {
                }, m = function (e) {
                    if (e instanceof m)return e;
                    if (!(this instanceof m))return new m(e);
                    this._wrapped = e
                };
                typeof n != "undefined" ? (typeof t != "undefined" && t.exports && (n = t.exports = m), n._ = m) : e._ = m, m.VERSION = "1.8.3";
                var g = function (e, t, n) {
                    if (t === void 0)return e;
                    switch (n == null ? 3 : n) {
                        case 1:
                            return function (n) {
                                return e.call(t, n)
                            };
                        case 2:
                            return function (n, r) {
                                return e.call(t, n, r)
                            };
                        case 3:
                            return function (n, r, i) {
                                return e.call(t, n, r, i)
                            };
                        case 4:
                            return function (n, r, i, s) {
                                return e.call(t, n, r, i, s)
                            }
                    }
                    return function () {
                        return e.apply(t, arguments)
                    }
                }, y = function (e, t, n) {
                    return e == null ? m.identity : m.isFunction(e) ? g(e, t, n) : m.isObject(e) ? m.matcher(e) : m.property(e)
                };
                m.iteratee = function (e, t) {
                    return y(e, t, Infinity)
                };
                var b = function (e, t) {
                    return function (n) {
                        var r = arguments.length;
                        if (r < 2 || n == null)return n;
                        for (var i = 1; i < r; i++) {
                            var s = arguments[i], o = e(s), u = o.length;
                            for (var a = 0; a < u; a++) {
                                var f = o[a];
                                if (!t || n[f] === void 0)n[f] = s[f]
                            }
                        }
                        return n
                    }
                }, w = function (e) {
                    if (!m.isObject(e))return {};
                    if (d)return d(e);
                    v.prototype = e;
                    var t = new v;
                    return v.prototype = null, t
                }, E = function (e) {
                    return function (t) {
                        return t == null ? void 0 : t[e]
                    }
                }, S = Math.pow(2, 53) - 1, x = E("length"), T = function (e) {
                    var t = x(e);
                    return typeof t == "number" && t >= 0 && t <= S
                };
                m.each = m.forEach = function (e, t, n) {
                    t = g(t, n);
                    var r, i;
                    if (T(e))for (r = 0, i = e.length; r < i; r++)t(e[r], r, e); else {
                        var s = m.keys(e);
                        for (r = 0, i = s.length; r < i; r++)t(e[s[r]], s[r], e)
                    }
                    return e
                }, m.map = m.collect = function (e, t, n) {
                    t = y(t, n);
                    var r = !T(e) && m.keys(e), i = (r || e).length, s = Array(i);
                    for (var o = 0; o < i; o++) {
                        var u = r ? r[o] : o;
                        s[o] = t(e[u], u, e)
                    }
                    return s
                }, m.reduce = m.foldl = m.inject = N(1), m.reduceRight = m.foldr = N(-1), m.find = m.detect = function (e, t, n) {
                    var r;
                    T(e) ? r = m.findIndex(e, t, n) : r = m.findKey(e, t, n);
                    if (r !== void 0 && r !== -1)return e[r]
                }, m.filter = m.select = function (e, t, n) {
                    var r = [];
                    return t = y(t, n), m.each(e, function (e, n, i) {
                        t(e, n, i) && r.push(e)
                    }), r
                }, m.reject = function (e, t, n) {
                    return m.filter(e, m.negate(y(t)), n)
                }, m.every = m.all = function (e, t, n) {
                    t = y(t, n);
                    var r = !T(e) && m.keys(e), i = (r || e).length;
                    for (var s = 0; s < i; s++) {
                        var o = r ? r[s] : s;
                        if (!t(e[o], o, e))return !1
                    }
                    return !0
                }, m.some = m.any = function (e, t, n) {
                    t = y(t, n);
                    var r = !T(e) && m.keys(e), i = (r || e).length;
                    for (var s = 0; s < i; s++) {
                        var o = r ? r[s] : s;
                        if (t(e[o], o, e))return !0
                    }
                    return !1
                }, m.contains = m.includes = m.include = function (e, t, n, r) {
                    T(e) || (e = m.values(e));
                    if (typeof n != "number" || r)n = 0;
                    return m.indexOf(e, t, n) >= 0
                }, m.invoke = function (e, t) {
                    var n = a.call(arguments, 2), r = m.isFunction(t);
                    return m.map(e, function (e) {
                        var i = r ? t : e[t];
                        return i == null ? i : i.apply(e, n)
                    })
                }, m.pluck = function (e, t) {
                    return m.map(e, m.property(t))
                }, m.where = function (e, t) {
                    return m.filter(e, m.matcher(t))
                }, m.findWhere = function (e, t) {
                    return m.find(e, m.matcher(t))
                }, m.max = function (e, t, n) {
                    var r = -Infinity, i = -Infinity, s, o;
                    if (t == null && e != null) {
                        e = T(e) ? e : m.values(e);
                        for (var u = 0, a = e.length; u < a; u++)s = e[u], s > r && (r = s)
                    } else t = y(t, n), m.each(e, function (e, n, s) {
                        o = t(e, n, s);
                        if (o > i || o === -Infinity && r === -Infinity)r = e, i = o
                    });
                    return r
                }, m.min = function (e, t, n) {
                    var r = Infinity, i = Infinity, s, o;
                    if (t == null && e != null) {
                        e = T(e) ? e : m.values(e);
                        for (var u = 0, a = e.length; u < a; u++)s = e[u], s < r && (r = s)
                    } else t = y(t, n), m.each(e, function (e, n, s) {
                        o = t(e, n, s);
                        if (o < i || o === Infinity && r === Infinity)r = e, i = o
                    });
                    return r
                }, m.shuffle = function (e) {
                    var t = T(e) ? e : m.values(e), n = t.length, r = Array(n);
                    for (var i = 0, s; i < n; i++)s = m.random(0, i), s !== i && (r[i] = r[s]), r[s] = t[i];
                    return r
                }, m.sample = function (e, t, n) {
                    return t == null || n ? (T(e) || (e = m.values(e)), e[m.random(e.length - 1)]) : m.shuffle(e).slice(0, Math.max(0, t))
                }, m.sortBy = function (e, t, n) {
                    return t = y(t, n), m.pluck(m.map(e, function (e, n, r) {
                        return {value: e, index: n, criteria: t(e, n, r)}
                    }).sort(function (e, t) {
                        var n = e.criteria, r = t.criteria;
                        if (n !== r) {
                            if (n > r || n === void 0)return 1;
                            if (n < r || r === void 0)return -1
                        }
                        return e.index - t.index
                    }), "value")
                };
                var C = function (e) {
                    return function (t, n, r) {
                        var i = {};
                        return n = y(n, r), m.each(t, function (r, s) {
                            var o = n(r, s, t);
                            e(i, r, o)
                        }), i
                    }
                };
                m.groupBy = C(function (e, t, n) {
                    m.has(e, n) ? e[n].push(t) : e[n] = [t]
                }), m.indexBy = C(function (e, t, n) {
                    e[n] = t
                }), m.countBy = C(function (e, t, n) {
                    m.has(e, n) ? e[n]++ : e[n] = 1
                }), m.toArray = function (e) {
                    return e ? m.isArray(e) ? a.call(e) : T(e) ? m.map(e, m.identity) : m.values(e) : []
                }, m.size = function (e) {
                    return e == null ? 0 : T(e) ? e.length : m.keys(e).length
                }, m.partition = function (e, t, n) {
                    t = y(t, n);
                    var r = [], i = [];
                    return m.each(e, function (e, n, s) {
                        (t(e, n, s) ? r : i).push(e)
                    }), [r, i]
                }, m.first = m.head = m.take = function (e, t, n) {
                    return e == null ? void 0 : t == null || n ? e[0] : m.initial(e, e.length - t)
                }, m.initial = function (e, t, n) {
                    return a.call(e, 0, Math.max(0, e.length - (t == null || n ? 1 : t)))
                }, m.last = function (e, t, n) {
                    return e == null ? void 0 : t == null || n ? e[e.length - 1] : m.rest(e, Math.max(0, e.length - t))
                }, m.rest = m.tail = m.drop = function (e, t, n) {
                    return a.call(e, t == null || n ? 1 : t)
                }, m.compact = function (e) {
                    return m.filter(e, m.identity)
                };
                var k = function (e, t, n, r) {
                    var i = [], s = 0;
                    for (var o = r || 0, u = x(e); o < u; o++) {
                        var a = e[o];
                        if (T(a) && (m.isArray(a) || m.isArguments(a))) {
                            t || (a = k(a, t, n));
                            var f = 0, l = a.length;
                            i.length += l;
                            while (f < l)i[s++] = a[f++]
                        } else n || (i[s++] = a)
                    }
                    return i
                };
                m.flatten = function (e, t) {
                    return k(e, t, !1)
                }, m.without = function (e) {
                    return m.difference(e, a.call(arguments, 1))
                }, m.uniq = m.unique = function (e, t, n, r) {
                    m.isBoolean(t) || (r = n, n = t, t = !1), n != null && (n = y(n, r));
                    var i = [], s = [];
                    for (var o = 0, u = x(e); o < u; o++) {
                        var a = e[o], f = n ? n(a, o, e) : a;
                        t ? ((!o || s !== f) && i.push(a), s = f) : n ? m.contains(s, f) || (s.push(f), i.push(a)) : m.contains(i, a) || i.push(a)
                    }
                    return i
                }, m.union = function () {
                    return m.uniq(k(arguments, !0, !0))
                }, m.intersection = function (e) {
                    var t = [], n = arguments.length;
                    for (var r = 0, i = x(e); r < i; r++) {
                        var s = e[r];
                        if (m.contains(t, s))continue;
                        for (var o = 1; o < n; o++)if (!m.contains(arguments[o], s))break;
                        o === n && t.push(s)
                    }
                    return t
                }, m.difference = function (e) {
                    var t = k(arguments, !0, !0, 1);
                    return m.filter(e, function (e) {
                        return !m.contains(t, e)
                    })
                }, m.zip = function () {
                    return m.unzip(arguments)
                }, m.unzip = function (e) {
                    var t = e && m.max(e, x).length || 0, n = Array(t);
                    for (var r = 0; r < t; r++)n[r] = m.pluck(e, r);
                    return n
                }, m.object = function (e, t) {
                    var n = {};
                    for (var r = 0, i = x(e); r < i; r++)t ? n[e[r]] = t[r] : n[e[r][0]] = e[r][1];
                    return n
                }, m.findIndex = L(1), m.findLastIndex = L(-1), m.sortedIndex = function (e, t, n, r) {
                    n = y(n, r, 1);
                    var i = n(t), s = 0, o = x(e);
                    while (s < o) {
                        var u = Math.floor((s + o) / 2);
                        n(e[u]) < i ? s = u + 1 : o = u
                    }
                    return s
                }, m.indexOf = A(1, m.findIndex, m.sortedIndex), m.lastIndexOf = A(-1, m.findLastIndex), m.range = function (e, t, n) {
                    t == null && (t = e || 0, e = 0), n = n || 1;
                    var r = Math.max(Math.ceil((t - e) / n), 0), i = Array(r);
                    for (var s = 0; s < r; s++, e += n)i[s] = e;
                    return i
                };
                var O = function (e, t, n, r, i) {
                    if (r instanceof t) {
                        var s = w(e.prototype), o = e.apply(s, i);
                        return m.isObject(o) ? o : s
                    }
                    return e.apply(n, i)
                };
                m.bind = function (e, t) {
                    if (p && e.bind === p)return p.apply(e, a.call(arguments, 1));
                    if (!m.isFunction(e))throw new TypeError("Bind must be called on a function");
                    var n = a.call(arguments, 2), r = function () {
                        return O(e, r, t, this, n.concat(a.call(arguments)))
                    };
                    return r
                }, m.partial = function (e) {
                    var t = a.call(arguments, 1), n = function () {
                        var r = 0, i = t.length, s = Array(i);
                        for (var o = 0; o < i; o++)s[o] = t[o] === m ? arguments[r++] : t[o];
                        while (r < arguments.length)s.push(arguments[r++]);
                        return O(e, n, this, this, s)
                    };
                    return n
                }, m.bindAll = function (e) {
                    var t, n = arguments.length, r;
                    if (n <= 1)throw new Error("bindAll must be passed function names");
                    for (t = 1; t < n; t++)r = arguments[t], e[r] = m.bind(e[r], e);
                    return e
                }, m.memoize = function (e, t) {
                    var n = function (r) {
                        var i = n.cache, s = "" + (t ? t.apply(this, arguments) : r);
                        return m.has(i, s) || (i[s] = e.apply(this, arguments)), i[s]
                    };
                    return n.cache = {}, n
                }, m.delay = function (e, t) {
                    var n = a.call(arguments, 2);
                    return setTimeout(function () {
                        return e.apply(null, n)
                    }, t)
                }, m.defer = m.partial(m.delay, m, 1), m.throttle = function (e, t, n) {
                    var r, i, s, o = null, u = 0;
                    n || (n = {});
                    var a = function () {
                        u = n.leading === !1 ? 0 : m.now(), o = null, s = e.apply(r, i), o || (r = i = null)
                    };
                    return function () {
                        var f = m.now();
                        !u && n.leading === !1 && (u = f);
                        var l = t - (f - u);
                        return r = this, i = arguments, l <= 0 || l > t ? (o && (clearTimeout(o), o = null), u = f, s = e.apply(r, i), o || (r = i = null)) : !o && n.trailing !== !1 && (o = setTimeout(a, l)), s
                    }
                }, m.debounce = function (e, t, n) {
                    var r, i, s, o, u, a = function () {
                        var f = m.now() - o;
                        f < t && f >= 0 ? r = setTimeout(a, t - f) : (r = null, n || (u = e.apply(s, i), r || (s = i = null)))
                    };
                    return function () {
                        s = this, i = arguments, o = m.now();
                        var f = n && !r;
                        return r || (r = setTimeout(a, t)), f && (u = e.apply(s, i), s = i = null), u
                    }
                }, m.wrap = function (e, t) {
                    return m.partial(t, e)
                }, m.negate = function (e) {
                    return function () {
                        return !e.apply(this, arguments)
                    }
                }, m.compose = function () {
                    var e = arguments, t = e.length - 1;
                    return function () {
                        var n = t, r = e[t].apply(this, arguments);
                        while (n--)r = e[n].call(this, r);
                        return r
                    }
                }, m.after = function (e, t) {
                    return function () {
                        if (--e < 1)return t.apply(this, arguments)
                    }
                }, m.before = function (e, t) {
                    var n;
                    return function () {
                        return --e > 0 && (n = t.apply(this, arguments)), e <= 1 && (t = null), n
                    }
                }, m.once = m.partial(m.before, 2);
                var M = !{toString: null}.propertyIsEnumerable("toString"), _ = ["valueOf", "isPrototypeOf", "toString", "propertyIsEnumerable", "hasOwnProperty", "toLocaleString"];
                m.keys = function (e) {
                    if (!m.isObject(e))return [];
                    if (h)return h(e);
                    var t = [];
                    for (var n in e)m.has(e, n) && t.push(n);
                    return M && D(e, t), t
                }, m.allKeys = function (e) {
                    if (!m.isObject(e))return [];
                    var t = [];
                    for (var n in e)t.push(n);
                    return M && D(e, t), t
                }, m.values = function (e) {
                    var t = m.keys(e), n = t.length, r = Array(n);
                    for (var i = 0; i < n; i++)r[i] = e[t[i]];
                    return r
                }, m.mapObject = function (e, t, n) {
                    t = y(t, n);
                    var r = m.keys(e), i = r.length, s = {}, o;
                    for (var u = 0; u < i; u++)o = r[u], s[o] = t(e[o], o, e);
                    return s
                }, m.pairs = function (e) {
                    var t = m.keys(e), n = t.length, r = Array(n);
                    for (var i = 0; i < n; i++)r[i] = [t[i], e[t[i]]];
                    return r
                }, m.invert = function (e) {
                    var t = {}, n = m.keys(e);
                    for (var r = 0, i = n.length; r < i; r++)t[e[n[r]]] = n[r];
                    return t
                }, m.functions = m.methods = function (e) {
                    var t = [];
                    for (var n in e)m.isFunction(e[n]) && t.push(n);
                    return t.sort()
                }, m.extend = b(m.allKeys), m.extendOwn = m.assign = b(m.keys), m.findKey = function (e, t, n) {
                    t = y(t, n);
                    var r = m.keys(e), i;
                    for (var s = 0, o = r.length; s < o; s++) {
                        i = r[s];
                        if (t(e[i], i, e))return i
                    }
                }, m.pick = function (e, t, n) {
                    var r = {}, i = e, s, o;
                    if (i == null)return r;
                    m.isFunction(t) ? (o = m.allKeys(i), s = g(t, n)) : (o = k(arguments, !1, !1, 1), s = function (e, t, n) {
                        return t in n
                    }, i = Object(i));
                    for (var u = 0, a = o.length; u < a; u++) {
                        var f = o[u], l = i[f];
                        s(l, f, i) && (r[f] = l)
                    }
                    return r
                }, m.omit = function (e, t, n) {
                    if (m.isFunction(t))t = m.negate(t); else {
                        var r = m.map(k(arguments, !1, !1, 1), String);
                        t = function (e, t) {
                            return !m.contains(r, t)
                        }
                    }
                    return m.pick(e, t, n)
                }, m.defaults = b(m.allKeys, !0), m.create = function (e, t) {
                    var n = w(e);
                    return t && m.extendOwn(n, t), n
                }, m.clone = function (e) {
                    return m.isObject(e) ? m.isArray(e) ? e.slice() : m.extend({}, e) : e
                }, m.tap = function (e, t) {
                    return t(e), e
                }, m.isMatch = function (e, t) {
                    var n = m.keys(t), r = n.length;
                    if (e == null)return !r;
                    var i = Object(e);
                    for (var s = 0; s < r; s++) {
                        var o = n[s];
                        if (t[o] !== i[o] || !(o in i))return !1
                    }
                    return !0
                };
                var P = function (e, t, n, r) {
                    if (e === t)return e !== 0 || 1 / e === 1 / t;
                    if (e == null || t == null)return e === t;
                    e instanceof m && (e = e._wrapped), t instanceof m && (t = t._wrapped);
                    var i = f.call(e);
                    if (i !== f.call(t))return !1;
                    switch (i) {
                        case"[object RegExp]":
                        case"[object String]":
                            return "" + e == "" + t;
                        case"[object Number]":
                            if (+e !== +e)return +t !== +t;
                            return +e === 0 ? 1 / +e === 1 / t : +e === +t;
                        case"[object Date]":
                        case"[object Boolean]":
                            return +e === +t
                    }
                    var s = i === "[object Array]";
                    if (!s) {
                        if (typeof e != "object" || typeof t != "object")return !1;
                        var o = e.constructor, u = t.constructor;
                        if (o !== u && !(m.isFunction(o) && o instanceof o && m.isFunction(u) && u instanceof u) && "constructor" in e && "constructor" in t)return !1
                    }
                    n = n || [], r = r || [];
                    var a = n.length;
                    while (a--)if (n[a] === e)return r[a] === t;
                    n.push(e), r.push(t);
                    if (s) {
                        a = e.length;
                        if (a !== t.length)return !1;
                        while (a--)if (!P(e[a], t[a], n, r))return !1
                    } else {
                        var l = m.keys(e), c;
                        a = l.length;
                        if (m.keys(t).length !== a)return !1;
                        while (a--) {
                            c = l[a];
                            if (!m.has(t, c) || !P(e[c], t[c], n, r))return !1
                        }
                    }
                    return n.pop(), r.pop(), !0
                };
                m.isEqual = function (e, t) {
                    return P(e, t)
                }, m.isEmpty = function (e) {
                    return e == null ? !0 : T(e) && (m.isArray(e) || m.isString(e) || m.isArguments(e)) ? e.length === 0 : m.keys(e).length === 0
                }, m.isElement = function (e) {
                    return !!e && e.nodeType === 1
                }, m.isArray = c || function (e) {
                        return f.call(e) === "[object Array]"
                    }, m.isObject = function (e) {
                    var t = typeof e;
                    return t === "function" || t === "object" && !!e
                }, m.each(["Arguments", "Function", "String", "Number", "Date", "RegExp", "Error"], function (e) {
                    m["is" + e] = function (t) {
                        return f.call(t) === "[object " + e + "]"
                    }
                }), m.isArguments(arguments) || (m.isArguments = function (e) {
                    return m.has(e, "callee")
                }), typeof /./ != "function" && typeof Int8Array != "object" && (m.isFunction = function (e) {
                    return typeof e == "function" || !1
                }), m.isFinite = function (e) {
                    return isFinite(e) && !isNaN(parseFloat(e))
                }, m.isNaN = function (e) {
                    return m.isNumber(e) && e !== +e
                }, m.isBoolean = function (e) {
                    return e === !0 || e === !1 || f.call(e) === "[object Boolean]"
                }, m.isNull = function (e) {
                    return e === null
                }, m.isUndefined = function (e) {
                    return e === void 0
                }, m.has = function (e, t) {
                    return e != null && l.call(e, t)
                }, m.noConflict = function () {
                    return e._ = r, this
                }, m.identity = function (e) {
                    return e
                }, m.constant = function (e) {
                    return function () {
                        return e
                    }
                }, m.noop = function () {
                }, m.property = E, m.propertyOf = function (e) {
                    return e == null ? function () {
                    } : function (t) {
                        return e[t]
                    }
                }, m.matcher = m.matches = function (e) {
                    return e = m.extendOwn({}, e), function (t) {
                        return m.isMatch(t, e)
                    }
                }, m.times = function (e, t, n) {
                    var r = Array(Math.max(0, e));
                    t = g(t, n, 1);
                    for (var i = 0; i < e; i++)r[i] = t(i);
                    return r
                }, m.random = function (e, t) {
                    return t == null && (t = e, e = 0), e + Math.floor(Math.random() * (t - e + 1))
                }, m.now = Date.now || function () {
                        return (new Date).getTime()
                    };
                var H = {
                    "&": "&amp;",
                    "<": "&lt;",
                    ">": "&gt;",
                    '"': "&quot;",
                    "'": "&#x27;",
                    "`": "&#x60;"
                }, B = m.invert(H), j = function (e) {
                    var t = function (t) {
                        return e[t]
                    }, n = "(?:" + m.keys(e).join("|") + ")", r = RegExp(n), i = RegExp(n, "g");
                    return function (e) {
                        return e = e == null ? "" : "" + e, r.test(e) ? e.replace(i, t) : e
                    }
                };
                m.escape = j(H), m.unescape = j(B), m.result = function (e, t, n) {
                    var r = e == null ? void 0 : e[t];
                    return r === void 0 && (r = n), m.isFunction(r) ? r.call(e) : r
                };
                var F = 0;
                m.uniqueId = function (e) {
                    var t = ++F + "";
                    return e ? e + t : t
                }, m.templateSettings = {
                    evaluate: /<%([\s\S]+?)%>/g,
                    interpolate: /<%=([\s\S]+?)%>/g,
                    escape: /<%-([\s\S]+?)%>/g
                };
                var I = /(.)^/, q = {
                    "'": "'",
                    "\\": "\\",
                    "\r": "r",
                    "\n": "n",
                    "\u2028": "u2028",
                    "\u2029": "u2029"
                }, R = /\\|'|\r|\n|\u2028|\u2029/g, U = function (e) {
                    return "\\" + q[e]
                };
                m.template = function (e, t, n) {
                    !t && n && (t = n), t = m.defaults({}, t, m.templateSettings);
                    var r = RegExp([(t.escape || I).source, (t.interpolate || I).source, (t.evaluate || I).source].join("|") + "|$", "g"), i = 0, s = "__p+='";
                    e.replace(r, function (t, n, r, o, u) {
                        return s += e.slice(i, u).replace(R, U), i = u + t.length, n ? s += "'+\n((__t=(" + n + "))==null?'':_.escape(__t))+\n'" : r ? s += "'+\n((__t=(" + r + "))==null?'':__t)+\n'" : o && (s += "';\n" + o + "\n__p+='"), t
                    }), s += "';\n", t.variable || (s = "with(obj||{}){\n" + s + "}\n"), s = "var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};\n" + s + "return __p;\n";
                    try {
                        var o = new Function(t.variable || "obj", "_", s)
                    } catch (u) {
                        throw u.source = s, u
                    }
                    var a = function (e) {
                        return o.call(this, e, m)
                    }, f = t.variable || "obj";
                    return a.source = "function(" + f + "){\n" + s + "}", a
                }, m.chain = function (e) {
                    var t = m(e);
                    return t._chain = !0, t
                };
                var z = function (e, t) {
                    return e._chain ? m(t).chain() : t
                };
                m.mixin = function (e) {
                    m.each(m.functions(e), function (t) {
                        var n = m[t] = e[t];
                        m.prototype[t] = function () {
                            var e = [this._wrapped];
                            return u.apply(e, arguments), z(this, n.apply(m, e))
                        }
                    })
                }, m.mixin(m), m.each(["pop", "push", "reverse", "shift", "sort", "splice", "unshift"], function (e) {
                    var t = i[e];
                    m.prototype[e] = function () {
                        var n = this._wrapped;
                        return t.apply(n, arguments), (e === "shift" || e === "splice") && n.length === 0 && delete n[0], z(this, n)
                    }
                }), m.each(["concat", "join", "slice"], function (e) {
                    var t = i[e];
                    m.prototype[e] = function () {
                        return z(this, t.apply(this._wrapped, arguments))
                    }
                }), m.prototype.value = function () {
                    return this._wrapped
                }, m.prototype.valueOf = m.prototype.toJSON = m.prototype.value, m.prototype.toString = function () {
                    return "" + this._wrapped
                }, typeof define == "function" && define.amd && ace.define("underscore", [], function () {
                    return m
                })
            }).call(this)
        }, {}],
        "/node_modules/jshint/src/jshint.js": [function (e, t, n) {
            var r = e("underscore"), i = e("events"), s = e("./vars.js"), o = e("./messages.js"), u = e("./lex.js").Lexer, a = e("./reg.js"), f = e("./state.js").state, l = e("./style.js"), c = e("./options.js"), h = function () {
                "use strict";
                function M(e, t) {
                    return e = e.trim(), /^[+-]W\d{3}$/g.test(e) ? !0 : c.validNames.indexOf(e) === -1 && t.type !== "jslint" && !r.has(c.removed, e) ? (X("E001", t, e), !1) : !0
                }

                function D(e) {
                    return Object.prototype.toString.call(e) === "[object String]"
                }

                function P(e, t) {
                    return e ? !e.identifier || e.value !== t ? !1 : !0 : !1
                }

                function H(e) {
                    if (!e.reserved)return !1;
                    var t = e.meta;
                    if (t && t.isFutureReservedWord && f.inES5()) {
                        if (!t.es5)return !1;
                        if (t.strictOnly && !f.option.strict && !f.isStrict())return !1;
                        if (e.isProperty)return !1
                    }
                    return !0
                }

                function B(e, t) {
                    return e.replace(/\{([^{}]*)\}/g, function (e, n) {
                        var r = t[n];
                        return typeof r == "string" || typeof r == "number" ? r : e
                    })
                }

                function j(e, t) {
                    Object.keys(t).forEach(function (n) {
                        if (r.has(h.blacklist, n))return;
                        e[n] = t[n]
                    })
                }

                function F() {
                    if (f.option.enforceall) {
                        for (var e in c.bool.enforcing)f.option[e] === undefined && !c.noenforceall[e] && (f.option[e] = !0);
                        for (var t in c.bool.relaxing)f.option[t] === undefined && (f.option[t] = !1)
                    }
                }

                function I() {
                    F(), f.option.es3 || j(T, s.ecmaIdentifiers[5]), f.option.esnext && j(T, s.ecmaIdentifiers[6]), f.option.module && (Kt(f.funct) || X("E055", f.tokens.next, "module"), f.inESNext() || z("W134", f.tokens.next, "module", 6)), f.option.couch && j(T, s.couch), f.option.qunit && j(T, s.qunit), f.option.rhino && j(T, s.rhino), f.option.shelljs && (j(T, s.shelljs), j(T, s.node)), f.option.typed && j(T, s.typed), f.option.phantom && j(T, s.phantom), f.option.prototypejs && j(T, s.prototypejs), f.option.node && (j(T, s.node), j(T, s.typed)), f.option.devel && j(T, s.devel), f.option.dojo && j(T, s.dojo), f.option.browser && (j(T, s.browser), j(T, s.typed)), f.option.browserify && (j(T, s.browser), j(T, s.typed), j(T, s.browserify)), f.option.nonstandard && j(T, s.nonstandard), f.option.jasmine && j(T, s.jasmine), f.option.jquery && j(T, s.jquery), f.option.mootools && j(T, s.mootools), f.option.worker && j(T, s.worker), f.option.wsh && j(T, s.wsh), f.option.globalstrict && f.option.strict !== !1 && (f.option.strict = !0), f.option.yui && j(T, s.yui), f.option.mocha && j(T, s.mocha)
                }

                function q(e, t, n) {
                    var r = Math.floor(t / f.lines.length * 100), i = o.errors[e].desc;
                    throw{
                        name: "JSHintError",
                        line: t,
                        character: n,
                        message: i + " (" + r + "% scanned).",
                        raw: i,
                        code: e
                    }
                }

                function R(e, t, n, r) {
                    !f.ignored[t] && f.option.undef !== !1 && h.undefs.push([e, t, n, r])
                }

                function U() {
                    var e = f.ignoredLines;
                    if (r.isEmpty(e))return;
                    h.errors = r.reject(h.errors, function (t) {
                        return e[t.line]
                    })
                }

                function z(e, t, n, r, i, s) {
                    var u, a, l, c;
                    if (/^W\d{3}$/.test(e)) {
                        if (f.ignored[e])return;
                        c = o.warnings[e]
                    } else/E\d{3}/.test(e) ? c = o.errors[e] : /I\d{3}/.test(e) && (c = o.info[e]);
                    return t = t || f.tokens.next || {}, t.id === "(end)" && (t = f.tokens.curr), a = t.line || 0, u = t.from || 0, l = {
                        id: "(error)",
                        raw: c.desc,
                        code: c.code,
                        evidence: f.lines[a - 1] || "",
                        line: a,
                        character: u,
                        scope: h.scope,
                        a: n,
                        b: r,
                        c: i,
                        d: s
                    }, l.reason = B(c.desc, l), h.errors.push(l), U(), h.errors.length >= f.option.maxerr && q("E043", a, u), l
                }

                function W(e, t, n, r, i, s, o) {
                    return z(e, {line: t, from: n}, r, i, s, o)
                }

                function X(e, t, n, r, i, s) {
                    z(e, t, n, r, i, s)
                }

                function V(e, t, n, r, i, s, o) {
                    return X(e, {line: t, from: n}, r, i, s, o)
                }

                function $(e, t) {
                    var n;
                    return n = {id: "(internal)", elem: e, value: t}, h.internals.push(n), n
                }

                function J(e, t) {
                    var n = t.type, i = t.token, s = t.isblockscoped;
                    n === "exception" && r.has(f.funct["(context)"], e) && f.funct[e] !== !0 && !f.option.node && z("W002", f.tokens.next, e), r.has(f.funct, e) && !f.funct["(global)"] && (f.funct[e] === !0 ? f.option.latedef && (f.option.latedef === !0 && r.contains([f.funct[e], n], "unction") || !r.contains([f.funct[e], n], "unction")) && z("W003", f.tokens.next, e) : ((!f.option.shadow || r.contains(["inner", "outer"], f.option.shadow)) && n !== "exception" || f.funct["(blockscope)"].getlabel(e)) && z("W004", f.tokens.next, e)), f.funct["(context)"] && r.has(f.funct["(context)"], e) && n !== "function" && f.option.shadow === "outer" && z("W123", f.tokens.next, e), s ? (f.funct["(blockscope)"].current.add(e, n, f.tokens.curr), f.funct["(blockscope)"].atTop() && p[e] && (f.tokens.curr.exported = !0)) : (f.funct["(blockscope)"].shadow(e), f.funct[e] = n, i && (f.funct["(tokens)"][e] = i), f.funct["(global)"] ? (m[e] = f.funct, r.has(g, e) && (f.option.latedef && (f.option.latedef === !0 && r.contains([f.funct[e], n], "unction") || !r.contains([f.funct[e], n], "unction")) && z("W003", f.tokens.next, e), delete g[e])) : N[e] = f.funct)
                }

                function K() {
                    var e = f.tokens.next, t = e.body.match(/(-\s+)?[^\s,:]+(?:\s*:\s*(-\s+)?[^\s,]+)?/g) || [], i = {};
                    if (e.type === "globals") {
                        t.forEach(function (n, r) {
                            n = n.split(":");
                            var s = (n[0] || "").trim(), o = (n[1] || "").trim();
                            if (s === "-" || !s.length) {
                                if (r > 0 && r === t.length - 1)return;
                                X("E002", e);
                                return
                            }
                            s.charAt(0) === "-" ? (s = s.slice(1), o = !1, h.blacklist[s] = s, delete T[s]) : i[s] = o === "true"
                        }), j(T, i);
                        for (var s in i)r.has(i, s) && (n[s] = e)
                    }
                    e.type === "exported" && t.forEach(function (n, r) {
                        if (!n.length) {
                            if (r > 0 && r === t.length - 1)return;
                            X("E002", e);
                            return
                        }
                        p[n] = !0
                    }), e.type === "members" && (x = x || {}, t.forEach(function (e) {
                        var t = e.charAt(0), n = e.charAt(e.length - 1);
                        t === n && (t === '"' || t === "'") && (e = e.substr(1, e.length - 2).replace('\\"', '"')), x[e] = !1
                    }));
                    var o = ["maxstatements", "maxparams", "maxdepth", "maxcomplexity", "maxerr", "maxlen", "indent"];
                    if (e.type === "jshint" || e.type === "jslint")t.forEach(function (t) {
                        t = t.split(":");
                        var n = (t[0] || "").trim(), r = (t[1] || "").trim();
                        if (!M(n, e))return;
                        if (o.indexOf(n) >= 0) {
                            if (r !== "false") {
                                r = +r;
                                if (typeof r != "number" || !isFinite(r) || r <= 0 || Math.floor(r) !== r) {
                                    X("E032", e, t[1].trim());
                                    return
                                }
                                f.option[n] = r
                            } else f.option[n] = n === "indent" ? 4 : !1;
                            return
                        }
                        if (n === "validthis") {
                            if (f.funct["(global)"])return void X("E009");
                            if (r !== "true" && r !== "false")return void X("E002", e);
                            f.option.validthis = r === "true";
                            return
                        }
                        if (n === "quotmark") {
                            switch (r) {
                                case"true":
                                case"false":
                                    f.option.quotmark = r === "true";
                                    break;
                                case"double":
                                case"single":
                                    f.option.quotmark = r;
                                    break;
                                default:
                                    X("E002", e)
                            }
                            return
                        }
                        if (n === "shadow") {
                            switch (r) {
                                case"true":
                                    f.option.shadow = !0;
                                    break;
                                case"outer":
                                    f.option.shadow = "outer";
                                    break;
                                case"false":
                                case"inner":
                                    f.option.shadow = "inner";
                                    break;
                                default:
                                    X("E002", e)
                            }
                            return
                        }
                        if (n === "unused") {
                            switch (r) {
                                case"true":
                                    f.option.unused = !0;
                                    break;
                                case"false":
                                    f.option.unused = !1;
                                    break;
                                case"vars":
                                case"strict":
                                    f.option.unused = r;
                                    break;
                                default:
                                    X("E002", e)
                            }
                            return
                        }
                        if (n === "latedef") {
                            switch (r) {
                                case"true":
                                    f.option.latedef = !0;
                                    break;
                                case"false":
                                    f.option.latedef = !1;
                                    break;
                                case"nofunc":
                                    f.option.latedef = "nofunc";
                                    break;
                                default:
                                    X("E002", e)
                            }
                            return
                        }
                        if (n === "ignore") {
                            switch (r) {
                                case"start":
                                    f.ignoreLinterErrors = !0;
                                    break;
                                case"end":
                                    f.ignoreLinterErrors = !1;
                                    break;
                                case"line":
                                    f.ignoredLines[e.line] = !0, U();
                                    break;
                                default:
                                    X("E002", e)
                            }
                            return
                        }
                        var i = /^([+-])(W\d{3})$/g.exec(n);
                        if (i) {
                            f.ignored[i[2]] = i[1] === "-";
                            return
                        }
                        var s;
                        if (r === "true" || r === "false") {
                            e.type === "jslint" ? (s = c.renamed[n] || n, f.option[s] = r === "true", c.inverted[s] !== undefined && (f.option[s] = !f.option[s])) : f.option[n] = r === "true", n === "newcap" && (f.option["(explicitNewcap)"] = !0);
                            return
                        }
                        X("E002", e)
                    }), I()
                }

                function Q(e) {
                    var t = e || 0, n = 0, r;
                    while (n <= t)r = w[n], r || (r = w[n] = E.token()), n += 1;
                    return !r && f.tokens.next.id === "(end)" ? f.tokens.next : r
                }

                function G() {
                    var e = 0, t;
                    do t = Q(e++); while (t.id === "(endline)");
                    return t
                }

                function Y(e, t) {
                    switch (f.tokens.curr.id) {
                        case"(number)":
                            f.tokens.next.id === "." && z("W005", f.tokens.curr);
                            break;
                        case"-":
                            (f.tokens.next.id === "-" || f.tokens.next.id === "--") && z("W006");
                            break;
                        case"+":
                            (f.tokens.next.id === "+" || f.tokens.next.id === "++") && z("W007")
                    }
                    e && f.tokens.next.id !== e && (t ? f.tokens.next.id === "(end)" ? X("E019", t, t.id) : X("E020", f.tokens.next, e, t.id, t.line, f.tokens.next.value) : (f.tokens.next.type !== "(identifier)" || f.tokens.next.value !== e) && z("W116", f.tokens.next, e, f.tokens.next.value)), f.tokens.prev = f.tokens.curr, f.tokens.curr = f.tokens.next;
                    for (; ;) {
                        f.tokens.next = w.shift() || E.token(), f.tokens.next || q("E041", f.tokens.curr.line);
                        if (f.tokens.next.id === "(end)" || f.tokens.next.id === "(error)")return;
                        f.tokens.next.check && f.tokens.next.check();
                        if (f.tokens.next.isSpecial)K(); else if (f.tokens.next.id !== "(endline)")break
                    }
                }

                function Z(e) {
                    return e.infix || !e.identifier && !e.template && !!e.led
                }

                function et() {
                    var e = f.tokens.curr, t = f.tokens.next;
                    return t.id === ";" || t.id === "}" || t.id === ":" ? !0 : Z(t) === Z(e) || e.id === "yield" && f.inMoz() ? e.line !== rt(t) : !1
                }

                function tt(e) {
                    return !e.left && e.arity !== "unary"
                }

                function nt(e, t) {
                    var n, i = !1, s = !1, o = !1;
                    f.nameStack.push(), !t && f.tokens.next.value === "let" && Q(0).value === "(" && (f.inMoz() || z("W118", f.tokens.next, "let expressions"), o = !0, f.funct["(blockscope)"].stack(), Y("let"), Y("("), f.tokens.prev.fud(), Y(")")), f.tokens.next.id === "(end)" && X("E006", f.tokens.curr);
                    var u = f.option.asi && f.tokens.prev.line !== rt(f.tokens.curr) && r.contains(["]", ")"], f.tokens.prev.id) && r.contains(["[", "("], f.tokens.curr.id);
                    u && z("W014", f.tokens.curr, f.tokens.curr.id), Y(), t && (f.funct["(verb)"] = f.tokens.curr.value, f.tokens.curr.beginsStmt = !0);
                    if (t === !0 && f.tokens.curr.fud)n = f.tokens.curr.fud(); else {
                        f.tokens.curr.nud ? n = f.tokens.curr.nud() : X("E030", f.tokens.curr, f.tokens.curr.id);
                        while ((e < f.tokens.next.lbp || f.tokens.next.type === "(template)") && !et())i = f.tokens.curr.value === "Array", s = f.tokens.curr.value === "Object", n && (n.value || n.first && n.first.value) && (n.value !== "new" || n.first && n.first.value && n.first.value === ".") && (i = !1, n.value !== f.tokens.curr.value && (s = !1)), Y(), i && f.tokens.curr.id === "(" && f.tokens.next.id === ")" && z("W009", f.tokens.curr), s && f.tokens.curr.id === "(" && f.tokens.next.id === ")" && z("W010", f.tokens.curr), n && f.tokens.curr.led ? n = f.tokens.curr.led(n) : X("E033", f.tokens.curr, f.tokens.curr.id)
                    }
                    return o && f.funct["(blockscope)"].unstack(), f.nameStack.pop(), n
                }

                function rt(e) {
                    return e.startLine || e.line
                }

                function it(e, t) {
                    e = e || f.tokens.curr, t = t || f.tokens.next, !f.option.laxbreak && e.line !== rt(t) && z("W014", t, t.value)
                }

                function st(e) {
                    e = e || f.tokens.curr, e.line !== rt(f.tokens.next) && z("E022", e, e.value)
                }

                function ot(e, t) {
                    e.line !== rt(t) && (f.option.laxcomma || (ut.first && (z("I001"), ut.first = !1), z("W014", e, t.value)))
                }

                function ut(e) {
                    e = e || {}, e.peek ? ot(f.tokens.prev, f.tokens.curr) : (ot(f.tokens.curr, f.tokens.next), Y(","));
                    if (f.tokens.next.identifier && (!e.property || !f.inES5()))switch (f.tokens.next.value) {
                        case"break":
                        case"case":
                        case"catch":
                        case"continue":
                        case"default":
                        case"do":
                        case"else":
                        case"finally":
                        case"for":
                        case"if":
                        case"in":
                        case"instanceof":
                        case"return":
                        case"switch":
                        case"throw":
                        case"try":
                        case"var":
                        case"let":
                        case"while":
                        case"with":
                            return X("E024", f.tokens.next, f.tokens.next.value), !1
                    }
                    if (f.tokens.next.type === "(punctuator)")switch (f.tokens.next.value) {
                        case"}":
                        case"]":
                        case",":
                            if (e.allowTrailing)return !0;
                        case")":
                            return X("E024", f.tokens.next, f.tokens.next.value), !1
                    }
                    return !0
                }

                function at(e, t) {
                    var n = f.syntax[e];
                    if (!n || typeof n != "object")f.syntax[e] = n = {id: e, lbp: t, value: e};
                    return n
                }

                function ft(e) {
                    var t = at(e, 0);
                    return t.delim = !0, t
                }

                function lt(e, t) {
                    var n = ft(e);
                    return n.identifier = n.reserved = !0, n.fud = t, n
                }

                function ct(e, t) {
                    var n = lt(e, t);
                    return n.block = !0, n
                }

                function ht(e) {
                    var t = e.id.charAt(0);
                    if (t >= "a" && t <= "z" || t >= "A" && t <= "Z")e.identifier = e.reserved = !0;
                    return e
                }

                function pt(e, t) {
                    var n = at(e, 150);
                    return ht(n), n.nud = typeof t == "function" ? t : function () {
                        this.arity = "unary", this.right = nt(150);
                        if (this.id === "++" || this.id === "--")f.option.plusplus ? z("W016", this, this.id) : this.right && (!this.right.identifier || H(this.right)) && this.right.id !== "." && this.right.id !== "[" && z("W017", this), this.right && this.right.identifier && f.funct["(blockscope)"].labeltype(this.right.value) === "const" && X("E013", this, this.right.value);
                        return this
                    }, n
                }

                function dt(e, t) {
                    var n = ft(e);
                    return n.type = e, n.nud = t, n
                }

                function vt(e, t) {
                    var n = dt(e, t);
                    return n.identifier = !0, n.reserved = !0, n
                }

                function mt(e, t) {
                    var n = dt(e, t && t.nud || function () {
                            return this
                        });
                    return t = t || {}, t.isFutureReservedWord = !0, n.value = e, n.identifier = !0, n.reserved = !0, n.meta = t, n
                }

                function gt(e, t) {
                    return vt(e, function () {
                        return typeof t == "function" && t(this), this
                    })
                }

                function yt(e, t, n, r) {
                    var i = at(e, n);
                    return ht(i), i.infix = !0, i.led = function (i) {
                        return r || it(f.tokens.prev, f.tokens.curr), (e === "in" || e === "instanceof") && i.id === "!" && z("W018", i, "!"), typeof t == "function" ? t(i, this) : (this.left = i, this.right = nt(n), this)
                    }, i
                }

                function bt(e) {
                    var t = at(e, 42);
                    return t.led = function (e) {
                        return it(f.tokens.prev, f.tokens.curr), this.left = e, this.right = Gt({
                            type: "arrow",
                            loneArg: e
                        }), this
                    }, t
                }

                function wt(e, t) {
                    var n = at(e, 100);
                    return n.led = function (e) {
                        it(f.tokens.prev, f.tokens.curr), this.left = e;
                        var n = this.right = nt(100);
                        return P(e, "NaN") || P(n, "NaN") ? z("W019", this) : t && t.apply(this, [e, n]), (!e || !n) && q("E041", f.tokens.curr.line), e.id === "!" && z("W018", e, "!"), n.id === "!" && z("W018", n, "!"), this
                    }, n
                }

                function Et(e) {
                    return e && (e.type === "(number)" && +e.value === 0 || e.type === "(string)" && e.value === "" || e.type === "null" && !f.option.eqnull || e.type === "true" || e.type === "false" || e.type === "undefined")
                }

                function xt(e, t, n) {
                    var i;
                    return n.option.notypeof ? !1 : !e || !t ? !1 : (i = n.inESNext() ? St.es6 : St.es3, t.type === "(identifier)" && t.value === "typeof" && e.type === "(string)" ? !r.contains(i, e.value) : !1)
                }

                function Tt(e, t) {
                    var n = !1;
                    return e.type === "this" && t.funct["(context)"] === null ? n = !0 : e.type === "(identifier)" && (t.option.node && e.value === "global" ? n = !0 : t.option.browser && (e.value === "window" || e.value === "document") && (n = !0)), n
                }

                function Nt(e) {
                    function n(e) {
                        if (typeof e != "object")return;
                        return e.right === "prototype" ? e : n(e.left)
                    }

                    function r(e) {
                        while (!e.identifier && typeof e.left == "object")e = e.left;
                        if (e.identifier && t.indexOf(e.value) >= 0)return e.value
                    }

                    var t = ["Array", "ArrayBuffer", "Boolean", "Collator", "DataView", "Date", "DateTimeFormat", "Error", "EvalError", "Float32Array", "Float64Array", "Function", "Infinity", "Intl", "Int16Array", "Int32Array", "Int8Array", "Iterator", "Number", "NumberFormat", "Object", "RangeError", "ReferenceError", "RegExp", "StopIteration", "String", "SyntaxError", "TypeError", "Uint16Array", "Uint32Array", "Uint8Array", "Uint8ClampedArray", "URIError"], i = n(e);
                    if (i)return r(i)
                }

                function Ct(e, t, n) {
                    var r = yt(e, typeof t == "function" ? t : function (e, t) {
                        t.left = e;
                        if (e) {
                            if (f.option.freeze) {
                                var n = Nt(e);
                                n && z("W121", e, n)
                            }
                            T[e.value] === !1 && N[e.value]["(global)"] === !0 ? z("W020", e) : e["function"] && z("W021", e, e.value), f.funct["(blockscope)"].labeltype(e.value) === "const" && X("E013", e, e.value);
                            if (e.id === ".")return e.left ? e.left.value === "arguments" && !f.isStrict() && z("E031", t) : z("E031", t), f.nameStack.set(f.tokens.prev), t.right = nt(10), t;
                            if (e.id === "[")return f.tokens.curr.left.first ? f.tokens.curr.left.first.forEach(function (e) {
                                e && f.funct[e.value] === "const" && X("E013", e, e.value)
                            }) : e.left ? e.left.value === "arguments" && !f.isStrict() && z("E031", t) : z("E031", t), f.nameStack.set(e.right), t.right = nt(10), t;
                            if (e.identifier && !H(e))return f.funct[e.value] === "exception" && z("W022", e), f.nameStack.set(e), t.right = nt(10), t;
                            e === f.syntax["function"] && z("W023", f.tokens.curr)
                        }
                        X("E031", t)
                    }, n);
                    return r.exps = !0, r.assign = !0, r
                }

                function kt(e, t, n) {
                    var r = at(e, n);
                    return ht(r), r.led = typeof t == "function" ? t : function (e) {
                        return f.option.bitwise && z("W016", this, this.id), this.left = e, this.right = nt(n), this
                    }, r
                }

                function Lt(e) {
                    return Ct(e, function (e, t) {
                        f.option.bitwise && z("W016", t, t.id);
                        if (e)return e.id === "." || e.id === "[" || e.identifier && !H(e) ? (nt(10), t) : (e === f.syntax["function"] && z("W023", f.tokens.curr), t);
                        X("E031", t)
                    }, 20)
                }

                function At(e) {
                    var t = at(e, 150);
                    return t.led = function (e) {
                        return f.option.plusplus ? z("W016", this, this.id) : (!e.identifier || H(e)) && e.id !== "." && e.id !== "[" && z("W017", this), e && e.identifier && f.funct["(blockscope)"].labeltype(e.value) === "const" && X("E013", this, e.value), this.left = e, this
                    }, t
                }

                function Ot(e, t, n) {
                    if (!f.tokens.next.identifier)return;
                    n || Y();
                    var r = f.tokens.curr, i = f.tokens.curr.value;
                    return H(r) ? t && f.inES5() ? i : e && i === "undefined" ? i : (z("W024", f.tokens.curr, f.tokens.curr.id), i) : i
                }

                function Mt(e, t) {
                    var n = Ot(e, t, !1);
                    if (n)return n;
                    if (f.tokens.next.value === "...") {
                        f.option.esnext || z("W119", f.tokens.next, "spread/rest operator"), Y();
                        if (mn(f.tokens.next, ["..."])) {
                            z("E024", f.tokens.next, "...");
                            while (mn(f.tokens.next, ["..."]))Y()
                        }
                        if (!f.tokens.next.identifier) {
                            z("E024", f.tokens.curr, "...");
                            return
                        }
                        return Mt(e, t)
                    }
                    X("E030", f.tokens.next, f.tokens.next.value), f.tokens.next.id !== ";" && Y()
                }

                function _t(e) {
                    var t = 0, n;
                    if (f.tokens.next.id !== ";" || e.inBracelessBlock)return;
                    for (; ;) {
                        do n = Q(t), t += 1; while (n.id !== "(end)" && n.id === "(comment)");
                        if (n.reach)return;
                        if (n.id !== "(endline)") {
                            if (n.id === "function") {
                                f.option.latedef === !0 && z("W026", n);
                                break
                            }
                            z("W027", n, n.value, e.value);
                            break
                        }
                    }
                }

                function Dt() {
                    if (f.tokens.next.id !== ";") {
                        if (f.tokens.next.isUnclosed)return Y();
                        f.option.asi || (!f.option.lastsemic || f.tokens.next.id !== "}" || rt(f.tokens.next) !== f.tokens.curr.line) && W("W033", f.tokens.curr.line, f.tokens.curr.character)
                    } else Y(";")
                }

                function Pt() {
                    var e = b, t, n = N, r = f.tokens.next;
                    if (r.id === ";") {
                        Y(";");
                        return
                    }
                    var i = H(r);
                    i && r.meta && r.meta.isFutureReservedWord && Q().id === ":" && (z("W024", r, r.id), i = !1);
                    if (r.value === "module" && r.type === "(identifier)" && Q().type === "(identifier)") {
                        f.inESNext() || z("W119", f.tokens.curr, "module"), Y("module");
                        var s = Mt();
                        J(s, {type: "unused", token: f.tokens.curr}), Y("from"), Y("(string)"), Dt();
                        return
                    }
                    r.identifier && !i && Q().id === ":" && (Y(), Y(":"), N = Object.create(n), J(r.value, {type: "label"}), !f.tokens.next.labelled && f.tokens.next.value !== "{" && z("W028", f.tokens.next, r.value, f.tokens.next.value), f.tokens.next.label = r.value, r = f.tokens.next);
                    if (r.id === "{") {
                        var o = f.funct["(verb)"] === "case" && f.tokens.curr.value === ":";
                        jt(!0, !0, !1, !1, o);
                        return
                    }
                    return t = nt(0, !0), t && (!t.identifier || t.value !== "function") && t.type !== "(punctuator)" && !f.isStrict() && f.option.globalstrict && f.option.strict && z("E007"), r.block || (!f.option.expr && (!t || !t.exps) ? z("W030", f.tokens.curr) : f.option.nonew && t && t.left && t.id === "(" && t.left.id === "new" && z("W031", r), Dt()), b = e, N = n, t
                }

                function Ht() {
                    var e = [], t;
                    while (!f.tokens.next.reach && f.tokens.next.id !== "(end)")f.tokens.next.id === ";" ? (t = Q(), (!t || t.id !== "(" && t.id !== "[") && z("W032"), Y(";")) : e.push(Pt());
                    return e
                }

                function Bt() {
                    var e, t, n;
                    while (f.tokens.next.id === "(string)") {
                        t = Q(0);
                        if (t.id === "(endline)") {
                            e = 1;
                            do n = Q(e++); while (n.id === "(endline)");
                            if (n.id === ";")t = n; else {
                                if (n.value === "[" || n.value === ".")return;
                                (!f.option.asi || n.value === "(") && z("W033", f.tokens.next)
                            }
                        } else {
                            if (t.id === "." || t.id === "[")return;
                            t.id !== ";" && z("W033", t)
                        }
                        Y(), f.directive[f.tokens.curr.value] && z("W034", f.tokens.curr, f.tokens.curr.value), f.tokens.curr.value === "use strict" && (f.option["(explicitNewcap)"] || (f.option.newcap = !0), f.option.undef = !0), f.directive[f.tokens.curr.value] = !0, t.id === ";" && Y(";")
                    }
                }

                function jt(e, t, n, i, s) {
                    var o, u = y, a = b, l, c = N, h, p, d;
                    y = e;
                    if (!e || !f.option.funcscope)N = Object.create(N);
                    h = f.tokens.next;
                    var v = f.funct["(metrics)"];
                    v.nestedBlockDepth += 1, v.verifyMaxNestedBlockDepthPerFunction();
                    if (f.tokens.next.id === "{") {
                        Y("{"), f.funct["(blockscope)"].stack(), p = f.tokens.curr.line;
                        if (f.tokens.next.id !== "}") {
                            b += f.option.indent;
                            while (!e && f.tokens.next.from > b)b += f.option.indent;
                            if (n) {
                                l = {};
                                for (d in f.directive)r.has(f.directive, d) && (l[d] = f.directive[d]);
                                Bt(), f.option.strict && f.funct["(context)"]["(global)"] && !l["use strict"] && !f.isStrict() && z("E007")
                            }
                            o = Ht(), v.statementCount += o.length, n && (f.directive = l), b -= f.option.indent
                        }
                        Y("}", h), f.funct["(blockscope)"].unstack(), b = a
                    } else if (!e)if (n) {
                        l = {}, t && !i && !f.inMoz() && X("W118", f.tokens.curr, "function closure expressions");
                        if (!t)for (d in f.directive)r.has(f.directive, d) && (l[d] = f.directive[d]);
                        nt(10), f.option.strict && f.funct["(context)"]["(global)"] && !l["use strict"] && !f.isStrict() && z("E007")
                    } else X("E021", f.tokens.next, "{", f.tokens.next.value); else f.funct["(noblockscopedvar)"] = !0, (!t || f.option.curly) && z("W116", f.tokens.next, "{", f.tokens.next.value), f.tokens.next.inBracelessBlock = !0, b += f.option.indent, o = [Pt()], b -= f.option.indent, delete f.funct["(noblockscopedvar)"];
                    switch (f.funct["(verb)"]) {
                        case"break":
                        case"continue":
                        case"return":
                        case"throw":
                            if (s)break;
                        default:
                            f.funct["(verb)"] = null
                    }
                    if (!e || !f.option.funcscope)N = c;
                    return y = u, e && f.option.noempty && (!o || o.length === 0) && z("W035", f.tokens.prev), v.nestedBlockDepth -= 1, o
                }

                function Ft(e) {
                    x && typeof x[e] != "boolean" && z("W036", f.tokens.curr, e), typeof S[e] == "number" ? S[e] += 1 : S[e] = 1
                }

                function It(e) {
                    var t = e.value, n = Object.getOwnPropertyDescriptor(g, t);
                    n ? n.value.push(e.line) : g[t] = [e.line]
                }

                function Ut() {
                    var e = {};
                    e.exps = !0, f.funct["(comparray)"].stack();
                    var t = !1;
                    return f.tokens.next.value !== "for" && (t = !0, f.inMoz() || z("W116", f.tokens.next, "for", f.tokens.next.value), f.funct["(comparray)"].setState("use"), e.right = nt(10)), Y("for"), f.tokens.next.value === "each" && (Y("each"), f.inMoz() || z("W118", f.tokens.curr, "for each")), Y("("), f.funct["(comparray)"].setState("define"), e.left = nt(130), r.contains(["in", "of"], f.tokens.next.value) ? Y() : X("E045", f.tokens.curr), f.funct["(comparray)"].setState("generate"), nt(10), Y(")"), f.tokens.next.value === "if" && (Y("if"), Y("("), f.funct["(comparray)"].setState("filter"), e.filter = nt(10), Y(")")), t || (f.funct["(comparray)"].setState("use"), e.right = nt(10)), Y("]"), f.funct["(comparray)"].unstack(), e
                }

                function zt() {
                    return f.funct["(statement)"] && f.funct["(statement)"].type === "class" || f.funct["(context)"] && f.funct["(context)"]["(verb)"] === "class"
                }

                function Wt(e) {
                    return e.identifier || e.id === "(string)" || e.id === "(number)"
                }

                function Xt(e) {
                    var t, n = !0;
                    return typeof e == "object" ? t = e : (n = e, t = Ot(!1, !0, n)), t ? typeof t == "object" && (t.id === "(string)" || t.id === "(identifier)" ? t = t.value : t.id === "(number)" && (t = t.value.toString())) : f.tokens.next.id === "(string)" ? (t = f.tokens.next.value, n || Y()) : f.tokens.next.id === "(number)" && (t = f.tokens.next.value.toString(), n || Y()), t === "hasOwnProperty" && z("W001"), t
                }

                function Vt(e) {
                    var t, n = [], i, s = [], o, u = !1, a = !1, l = e && e.loneArg;
                    if (l && l.identifier === !0)return J(l.value, {type: "unused", token: l}), [l.value];
                    t = f.tokens.next, (!e || !e.parsedOpening) && Y("(");
                    if (f.tokens.next.id === ")") {
                        Y(")");
                        return
                    }
                    for (; ;) {
                        if (r.contains(["{", "["], f.tokens.next.id)) {
                            s = nn();
                            for (o in s)o = s[o], o.id && (n.push(o.id), J(o.id, {type: "unused", token: o.token}))
                        } else {
                            mn(f.tokens.next, ["..."]) && (a = !0), i = Mt(!0);
                            if (i)n.push(i), J(i, {
                                type: "unused",
                                token: f.tokens.curr
                            }); else while (!mn(f.tokens.next, [",", ")"]))Y()
                        }
                        u && f.tokens.next.id !== "=" && X("E051", f.tokens.current), f.tokens.next.id === "=" && (f.inESNext() || z("W119", f.tokens.next, "default parameters"), Y("="), u = !0, nt(10));
                        if (f.tokens.next.id !== ",")return Y(")", t), n;
                        a && z("W131", f.tokens.next), ut()
                    }
                }

                function $t(e, t, n, i) {
                    var s = {
                        "(name)": e,
                        "(breakage)": 0,
                        "(loopage)": 0,
                        "(scope)": n,
                        "(tokens)": {},
                        "(properties)": {},
                        "(catch)": !1,
                        "(global)": !1,
                        "(line)": null,
                        "(character)": null,
                        "(metrics)": null,
                        "(statement)": null,
                        "(context)": null,
                        "(blockscope)": null,
                        "(comparray)": null,
                        "(generator)": null,
                        "(params)": null
                    };
                    return t && r.extend(s, {
                        "(line)": t.line,
                        "(character)": t.character,
                        "(metrics)": Yt(t)
                    }), r.extend(s, i), s["(context)"] && (s["(blockscope)"] = s["(context)"]["(blockscope)"], s["(comparray)"] = s["(context)"]["(comparray)"]), s
                }

                function Jt(e) {
                    return "(scope)" in e
                }

                function Kt(e) {
                    return e["(global)"] && !e["(verb)"]
                }

                function Qt(e) {
                    function i() {
                        if (f.tokens.curr.template && f.tokens.curr.tail && f.tokens.curr.context === t)return !0;
                        var e = f.tokens.next.template && f.tokens.next.tail && f.tokens.next.context === t;
                        return e && Y(), e || f.tokens.next.isUnclosed
                    }

                    var t = this.context, n = this.noSubst, r = this.depth;
                    if (!n)while (!i())!f.tokens.next.template || f.tokens.next.depth > r ? nt(0) : Y();
                    return {id: "(template)", type: "(template)", tag: e}
                }

                function Gt(e) {
                    var t, n, i, s, o, u, a = f.option, l = f.ignored, c = N;
                    return e && (n = e.name, i = e.statement, s = e.classExprBinding, o = e.type === "generator", u = e.type === "arrow"), f.option = Object.create(f.option), f.ignored = Object.create(f.ignored), N = Object.create(N), f.funct = $t(n || f.nameStack.infer(), f.tokens.next, N, {
                        "(statement)": i,
                        "(context)": f.funct,
                        "(generator)": o
                    }), t = f.funct, f.tokens.curr.funct = f.funct, v.push(f.funct), n && J(n, {type: "function"}), s && J(s, {type: "function"}), f.funct["(params)"] = Vt(e), f.funct["(metrics)"].verifyMaxParametersPerFunction(f.funct["(params)"]), u && (f.option.esnext || z("W119", f.tokens.curr, "arrow function syntax (=>)"), e.loneArg || Y("=>")), jt(!1, !0, !0, u), !f.option.noyield && o && f.funct["(generator)"] !== "yielded" && z("W124", f.tokens.curr), f.funct["(metrics)"].verifyMaxStatementsPerFunction(), f.funct["(metrics)"].verifyMaxComplexityPerFunction(), f.funct["(unusedOption)"] = f.option.unused, N = c, f.option = a, f.ignored = l, f.funct["(last)"] = f.tokens.curr.line, f.funct["(lastcharacter)"] = f.tokens.curr.character, r.map(Object.keys(f.funct), function (e) {
                        if (e[0] === "(")return;
                        f.funct["(blockscope)"].unshadow(e)
                    }), f.funct = f.funct["(context)"], t
                }

                function Yt(e) {
                    return {
                        statementCount: 0,
                        nestedBlockDepth: -1,
                        ComplexityCount: 1,
                        verifyMaxStatementsPerFunction: function () {
                            f.option.maxstatements && this.statementCount > f.option.maxstatements && z("W071", e, this.statementCount)
                        },
                        verifyMaxParametersPerFunction: function (t) {
                            t = t || [], r.isNumber(f.option.maxparams) && t.length > f.option.maxparams && z("W072", e, t.length)
                        },
                        verifyMaxNestedBlockDepthPerFunction: function () {
                            f.option.maxdepth && this.nestedBlockDepth > 0 && this.nestedBlockDepth === f.option.maxdepth + 1 && z("W073", null, this.nestedBlockDepth)
                        },
                        verifyMaxComplexityPerFunction: function () {
                            var t = f.option.maxcomplexity, n = this.ComplexityCount;
                            t && n > t && z("W074", e, n)
                        }
                    }
                }

                function Zt() {
                    f.funct["(metrics)"].ComplexityCount += 1
                }

                function en(e) {
                    var t, n;
                    e && (t = e.id, n = e.paren, t === "," && (e = e.exprs[e.exprs.length - 1]) && (t = e.id, n = n || e.paren));
                    switch (t) {
                        case"=":
                        case"+=":
                        case"-=":
                        case"*=":
                        case"%=":
                        case"&=":
                        case"|=":
                        case"^=":
                        case"/=":
                            !n && !f.option.boss && z("W084")
                    }
                }

                function tn(e) {
                    if (f.inES5())for (var t in e)r.has(e, t) && e[t].setterToken && !e[t].getterToken && z("W078", e[t].setterToken)
                }

                function nn() {
                    var e, t, n = [];
                    f.inESNext() || z("W104", f.tokens.curr, "destructuring expression");
                    var r = function () {
                        var e;
                        if (mn(f.tokens.next, ["[", "{"])) {
                            t = nn();
                            for (var i in t)i = t[i], n.push({id: i.id, token: i.token})
                        } else if (mn(f.tokens.next, [","]))n.push({id: null, token: f.tokens.curr}); else {
                            if (!mn(f.tokens.next, ["("])) {
                                var s = mn(f.tokens.next, ["..."]);
                                return e = Mt(), e && n.push({id: e, token: f.tokens.curr}), s
                            }
                            Y("("), r(), Y(")")
                        }
                        return !1
                    };
                    if (mn(f.tokens.next, ["["])) {
                        Y("[");
                        var i = !1;
                        r() && mn(f.tokens.next, [","]) && !i && (z("W130", f.tokens.next), i = !0);
                        while (!mn(f.tokens.next, ["]"])) {
                            Y(",");
                            if (mn(f.tokens.next, ["]"]))break;
                            r() && mn(f.tokens.next, [","]) && !i && (z("W130", f.tokens.next), i = !0)
                        }
                        Y("]")
                    } else if (mn(f.tokens.next, ["{"])) {
                        Y("{"), e = Mt(), mn(f.tokens.next, [":"]) ? (Y(":"), r()) : n.push({
                            id: e,
                            token: f.tokens.curr
                        });
                        while (!mn(f.tokens.next, ["}"])) {
                            Y(",");
                            if (mn(f.tokens.next, ["}"]))break;
                            e = Mt(), mn(f.tokens.next, [":"]) ? (Y(":"), r()) : n.push({id: e, token: f.tokens.curr})
                        }
                        Y("}")
                    }
                    return n
                }

                function rn(e, t) {
                    var n = t.first;
                    if (!n)return;
                    r.zip(e, Array.isArray(n) ? n : [n]).forEach(function (e) {
                        var t = e[0], n = e[1];
                        t && n ? t.first = n : t && t.first && !n && z("W080", t.first, t.first.value)
                    })
                }

                function sn(e, t, n) {
                    var i = n && n.prefix, s = n && n.inexport, o = e === "let", u = e === "const", a, l, c, h;
                    f.inESNext() || z("W104", f.tokens.curr, e), o && f.tokens.next.value === "(" ? (f.inMoz() || z("W118", f.tokens.next, "let block"), Y("("), f.funct["(blockscope)"].stack(), h = !0) : f.funct["(noblockscopedvar)"] && X("E048", f.tokens.curr, u ? "Const" : "Let"), t.first = [];
                    for (; ;) {
                        var d = [];
                        r.contains(["{", "["], f.tokens.next.value) ? (a = nn(), l = !1) : (a = [{
                            id: Mt(),
                            token: f.tokens.curr
                        }], l = !0, s && (p[f.tokens.curr.value] = !0, f.tokens.curr.exported = !0));
                        for (var v in a)a.hasOwnProperty(v) && (v = a[v], f.inESNext() && f.funct["(blockscope)"].current.labeltype(v.id) === "const" && z("E011", null, v.id), f.funct["(global)"] && T[v.id] === !1 && z("W079", v.token, v.id), v.id && !f.funct["(noblockscopedvar)"] && (J(v.id, {
                            type: u ? "const" : "unused",
                            token: v.token,
                            isblockscoped: !0
                        }), d.push(v.token)));
                        t.first = t.first.concat(d), !i && u && f.tokens.next.id !== "=" && z("E012", f.tokens.curr, f.tokens.curr.value), f.tokens.next.id === "=" && (Y("="), !i && f.tokens.next.id === "undefined" && z("W080", f.tokens.prev, f.tokens.prev.value), !i && Q(0).id === "=" && f.tokens.next.identifier && z("W120", f.tokens.next, f.tokens.next.value), c = nt(i ? 120 : 10), l ? a[0].first = c : rn(d, c));
                        if (f.tokens.next.id !== ",")break;
                        ut()
                    }
                    return h && (Y(")"), jt(!0, !0), t.block = !0, f.funct["(blockscope)"].unstack()), t
                }

                function fn(e) {
                    return f.inESNext() || z("W104", f.tokens.curr, "class"), e ? (this.name = Mt(), J(this.name, {
                        type: "unused",
                        token: f.tokens.curr
                    })) : f.tokens.next.identifier && f.tokens.next.value !== "extends" ? (this.name = Mt(), this.namedExpr = !0) : this.name = f.nameStack.infer(), ln(this), this
                }

                function ln(e) {
                    var t = f.inClassBody;
                    f.tokens.next.value === "extends" && (Y("extends"), e.heritage = nt(10)), f.inClassBody = !0, Y("{"), e.body = cn(e), Y("}"), f.inClassBody = t
                }

                function cn(e) {
                    var t, n, r, i, s = {}, o = {}, u;
                    for (var a = 0; f.tokens.next.id !== "}"; ++a) {
                        t = f.tokens.next, n = !1, r = !1, i = null;
                        if (t.id === ";") {
                            z("W032"), Y(";");
                            continue
                        }
                        t.id === "*" && (r = !0, Y("*"), t = f.tokens.next);
                        if (t.id === "[")t = vn(), u = !0; else {
                            if (!Wt(t)) {
                                z("W052", f.tokens.next, f.tokens.next.value || f.tokens.next.type), Y();
                                continue
                            }
                            Y(), u = !1;
                            if (t.identifier && t.value === "static") {
                                mn(f.tokens.next, ["*"]) && (r = !0, Y("*"));
                                if (Wt(f.tokens.next) || f.tokens.next.id === "[")u = f.tokens.next.id === "[", n = !0, t = f.tokens.next, f.tokens.next.id === "[" ? t = vn() : Y()
                            }
                            t.identifier && (t.value === "get" || t.value === "set") && (Wt(f.tokens.next) || f.tokens.next.id === "[") && (u = f.tokens.next.id === "[", i = t, t = f.tokens.next, f.tokens.next.id === "[" ? t = vn() : Y())
                        }
                        if (!mn(f.tokens.next, ["("])) {
                            X("E054", f.tokens.next, f.tokens.next.value);
                            while (f.tokens.next.id !== "}" && !mn(f.tokens.next, ["("]))Y();
                            f.tokens.next.value !== "(" && Gt({statement: e})
                        }
                        u || (i ? dn(i.value, n ? o : s, t.value, t, !0, n) : (t.value === "constructor" ? f.nameStack.set(e) : f.nameStack.set(t), pn(n ? o : s, t.value, t, !0, n)));
                        if (i && t.value === "constructor") {
                            var l = i.value === "get" ? "class getter method" : "class setter method";
                            X("E049", t, l, "constructor")
                        } else t.value === "prototype" && X("E049", t, "class method", "prototype");
                        Xt(t), Gt({
                            statement: e,
                            type: r ? "generator" : null,
                            classExprBinding: e.namedExpr ? e.name : null
                        })
                    }
                    tn(s)
                }

                function pn(e, t, n, i, s) {
                    var o = ["key", "class method", "static class method"];
                    o = o[(i || !1) + (s || !1)], n.identifier && (t = n.value), e[t] && r.has(e, t) ? z("W075", f.tokens.next, o, t) : e[t] = {}, e[t].basic = !0, e[t].basictkn = n
                }

                function dn(e, t, n, i, s, o) {
                    var u = e === "get" ? "getterToken" : "setterToken", a = "";
                    s ? (o && (a += "static "), a += e + "ter method") : a = "key", f.tokens.curr.accessorType = e, f.nameStack.set(i), t[n] && r.has(t, n) ? (t[n].basic || t[n][u]) && z("W075", f.tokens.next, a, n) : t[n] = {}, t[n][u] = i
                }

                function vn() {
                    Y("["), f.option.esnext || z("W119", f.tokens.curr, "computed property names");
                    var e = nt(10);
                    return Y("]"), e
                }

                function mn(e, t) {
                    return e.type === "(punctuator)" && r.contains(t, e.value)
                }

                function gn() {
                    var e = hn();
                    e.notJson ? (!f.inESNext() && e.isDestAssign && z("W104", f.tokens.curr, "destructuring assignment"), Ht()) : (f.option.laxbreak = !0, f.jsonMode = !0, bn())
                }

                function bn() {
                    function e() {
                        var e = {}, t = f.tokens.next;
                        Y("{");
                        if (f.tokens.next.id !== "}")for (; ;) {
                            if (f.tokens.next.id === "(end)")X("E026", f.tokens.next, t.line); else {
                                if (f.tokens.next.id === "}") {
                                    z("W094", f.tokens.curr);
                                    break
                                }
                                f.tokens.next.id === "," ? X("E028", f.tokens.next) : f.tokens.next.id !== "(string)" && z("W095", f.tokens.next, f.tokens.next.value)
                            }
                            e[f.tokens.next.value] === !0 ? z("W075", f.tokens.next, "key", f.tokens.next.value) : f.tokens.next.value === "__proto__" && !f.option.proto || f.tokens.next.value === "__iterator__" && !f.option.iterator ? z("W096", f.tokens.next, f.tokens.next.value) : e[f.tokens.next.value] = !0, Y(), Y(":"), bn();
                            if (f.tokens.next.id !== ",")break;
                            Y(",")
                        }
                        Y("}")
                    }

                    function t() {
                        var e = f.tokens.next;
                        Y("[");
                        if (f.tokens.next.id !== "]")for (; ;) {
                            if (f.tokens.next.id === "(end)")X("E027", f.tokens.next, e.line); else {
                                if (f.tokens.next.id === "]") {
                                    z("W094", f.tokens.curr);
                                    break
                                }
                                f.tokens.next.id === "," && X("E028", f.tokens.next)
                            }
                            bn();
                            if (f.tokens.next.id !== ",")break;
                            Y(",")
                        }
                        Y("]")
                    }

                    switch (f.tokens.next.id) {
                        case"{":
                            e();
                            break;
                        case"[":
                            t();
                            break;
                        case"true":
                        case"false":
                        case"null":
                        case"(number)":
                        case"(string)":
                            Y();
                            break;
                        case"-":
                            Y("-"), Y("(number)");
                            break;
                        default:
                            X("E003", f.tokens.next)
                    }
                }

                var e, t = {
                    "<": !0,
                    "<=": !0,
                    "==": !0,
                    "===": !0,
                    "!==": !0,
                    "!=": !0,
                    ">": !0,
                    ">=": !0,
                    "+": !0,
                    "-": !0,
                    "*": !0,
                    "/": !0,
                    "%": !0
                }, n, p, d = ["closure", "exception", "global", "label", "outer", "unused", "var"], v, m, g, y, b, w, E, S, x, T, N, C, k, L, A = [], O = new i.EventEmitter, St = {};
                St.legacy = ["xml", "unknown"], St.es3 = ["undefined", "boolean", "number", "string", "function", "object"], St.es3 = St.es3.concat(St.legacy), St.es6 = St.es3.concat("symbol"), dt("(number)", function () {
                    return this
                }), dt("(string)", function () {
                    return this
                }), f.syntax["(identifier)"] = {
                    type: "(identifier)", lbp: 0, identifier: !0, nud: function () {
                        var e = this.value, t = N[e], n, r;
                        if (f.tokens.next.id === "=>")return this;
                        r = f.funct["(blockscope)"].getlabel(e), typeof t == "function" ? t = undefined : !r && typeof t == "boolean" && (n = f.funct, f.funct = v[0], J(e, {type: "var"}), t = f.funct, f.funct = n);
                        if (f.funct === t || r)switch (r ? r[e]["(type)"] : f.funct[e]) {
                            case"unused":
                                r ? r[e]["(type)"] = "var" : f.funct[e] = "var";
                                break;
                            case"unction":
                                r ? r[e]["(type)"] = "function" : f.funct[e] = "function", this["function"] = !0;
                                break;
                            case"const":
                                r[e]["(unused)"] = !1;
                                break;
                            case"function":
                                this["function"] = !0;
                                break;
                            case"label":
                                z("W037", f.tokens.curr, e)
                        } else switch (f.funct[e]) {
                            case"closure":
                            case"function":
                            case"var":
                            case"unused":
                                z("W038", f.tokens.curr, e);
                                break;
                            case"label":
                                z("W037", f.tokens.curr, e);
                                break;
                            case"outer":
                            case"global":
                                break;
                            default:
                                if (t === !0)f.funct[e] = !0; else if (t === null)z("W039", f.tokens.curr, e), It(f.tokens.curr); else if (typeof t != "object")f.funct["(comparray)"].check(e) || R(f.funct, "W117", f.tokens.curr, e), f.funct["(global)"] || (f.funct[e] = !0), It(f.tokens.curr); else switch (t[e]) {
                                    case"function":
                                    case"unction":
                                        this["function"] = !0, t[e] = "closure", f.funct[e] = t["(global)"] ? "global" : "outer";
                                        break;
                                    case"var":
                                    case"unused":
                                        t[e] = "closure", f.funct[e] = t["(global)"] ? "global" : "outer";
                                        break;
                                    case"closure":
                                        f.funct[e] = t["(global)"] ? "global" : "outer";
                                        break;
                                    case"label":
                                        z("W037", f.tokens.curr, e)
                                }
                        }
                        return this
                    }, led: function () {
                        X("E033", f.tokens.next, f.tokens.next.value)
                    }
                };
                var qt = {lbp: 0, identifier: !1, template: !0};
                f.syntax["(template)"] = r.extend({
                    type: "(template)",
                    nud: Qt,
                    led: Qt,
                    noSubst: !1
                }, qt), f.syntax["(template middle)"] = r.extend({
                    type: "(template middle)",
                    middle: !0,
                    noSubst: !1
                }, qt), f.syntax["(template tail)"] = r.extend({
                    type: "(template tail)",
                    tail: !0,
                    noSubst: !1
                }, qt), f.syntax["(no subst template)"] = r.extend({
                    type: "(template)",
                    nud: Qt,
                    led: Qt,
                    noSubst: !0,
                    tail: !0
                }, qt), dt("(regexp)", function () {
                    return this
                }), ft("(endline)"), ft("(begin)"), ft("(end)").reach = !0, ft("(error)").reach = !0, ft("}").reach = !0, ft(")"), ft("]"), ft('"').reach = !0, ft("'").reach = !0, ft(";"), ft(":").reach = !0, ft("#"), vt("else"), vt("case").reach = !0, vt("catch"), vt("default").reach = !0, vt("finally"), gt("arguments", function (e) {
                    f.isStrict() && f.funct["(global)"] && z("E008", e)
                }), gt("eval"), gt("false"), gt("Infinity"), gt("null"), gt("this", function (e) {
                    f.isStrict() && !zt() && !f.option.validthis && (f.funct["(statement)"] && f.funct["(name)"].charAt(0) > "Z" || f.funct["(global)"]) && z("W040", e)
                }), gt("true"), gt("undefined"), Ct("=", "assign", 20), Ct("+=", "assignadd", 20), Ct("-=", "assignsub", 20), Ct("*=", "assignmult", 20), Ct("/=", "assigndiv", 20).nud = function () {
                    X("E014")
                }, Ct("%=", "assignmod", 20), Lt("&="), Lt("|="), Lt("^="), Lt("<<="), Lt(">>="), Lt(">>>="), yt(",", function (e, t) {
                    var n;
                    t.exprs = [e], f.option.nocomma && z("W127");
                    if (!ut({peek: !0}))return t;
                    for (; ;) {
                        if (!(n = nt(10)))break;
                        t.exprs.push(n);
                        if (f.tokens.next.value !== "," || !ut())break
                    }
                    return t
                }, 10, !0), yt("?", function (e, t) {
                    return Zt(), t.left = e, t.right = nt(10), Y(":"), t["else"] = nt(10), t
                }, 30);
                var Rt = 40;
                yt("||", function (e, t) {
                    return Zt(), t.left = e, t.right = nt(Rt), t
                }, Rt), yt("&&", "and", 50), kt("|", "bitor", 70), kt("^", "bitxor", 80), kt("&", "bitand", 90), wt("==", function (e, t) {
                    var n = f.option.eqnull && (e.value === "null" || t.value === "null");
                    switch (!0) {
                        case!n && f.option.eqeqeq:
                            this.from = this.character, z("W116", this, "===", "==");
                            break;
                        case Et(e):
                            z("W041", this, "===", e.value);
                            break;
                        case Et(t):
                            z("W041", this, "===", t.value);
                            break;
                        case xt(t, e, f):
                            z("W122", this, t.value);
                            break;
                        case xt(e, t, f):
                            z("W122", this, e.value)
                    }
                    return this
                }), wt("===", function (e, t) {
                    return xt(t, e, f) ? z("W122", this, t.value) : xt(e, t, f) && z("W122", this, e.value), this
                }), wt("!=", function (e, t) {
                    var n = f.option.eqnull && (e.value === "null" || t.value === "null");
                    return !n && f.option.eqeqeq ? (this.from = this.character, z("W116", this, "!==", "!=")) : Et(e) ? z("W041", this, "!==", e.value) : Et(t) ? z("W041", this, "!==", t.value) : xt(t, e, f) ? z("W122", this, t.value) : xt(e, t, f) && z("W122", this, e.value), this
                }), wt("!==", function (e, t) {
                    return xt(t, e, f) ? z("W122", this, t.value) : xt(e, t, f) && z("W122", this, e.value), this
                }), wt("<"), wt(">"), wt("<="), wt(">="), kt("<<", "shiftleft", 120), kt(">>", "shiftright", 120), kt(">>>", "shiftrightunsigned", 120), yt("in", "in", 120), yt("instanceof", "instanceof", 120), yt("+", function (e, t) {
                    var n;
                    return t.left = e, t.right = n = nt(130), e && n && e.id === "(string)" && n.id === "(string)" ? (e.value += n.value, e.character = n.character, !f.option.scripturl && a.javascriptURL.test(e.value) && z("W050", e), e) : t
                }, 130), pt("+", "num"), pt("+++", function () {
                    return z("W007"), this.arity = "unary", this.right = nt(150), this
                }), yt("+++", function (e) {
                    return z("W007"), this.left = e, this.right = nt(130), this
                }, 130), yt("-", "sub", 130), pt("-", "neg"), pt("---", function () {
                    return z("W006"), this.arity = "unary", this.right = nt(150), this
                }), yt("---", function (e) {
                    return z("W006"), this.left = e, this.right = nt(130), this
                }, 130), yt("*", "mult", 140), yt("/", "div", 140), yt("%", "mod", 140), At("++"), pt("++", "preinc"), f.syntax["++"].exps = !0, At("--"), pt("--", "predec"), f.syntax["--"].exps = !0, pt("delete", function () {
                    var e = nt(10);
                    return e ? (e.id !== "." && e.id !== "[" && z("W051"), this.first = e, e.identifier && !f.isStrict() && (e.forgiveUndef = !0), this) : this
                }).exps = !0, pt("~", function () {
                    return f.option.bitwise && z("W016", this, "~"), this.arity = "unary", nt(150), this
                }), pt("...", function () {
                    return f.option.esnext || z("W119", this, "spread/rest operator"), !f.tokens.next.identifier && f.tokens.next.type !== "(string)" && !mn(f.tokens.next, ["[", "("]) && X("E030", f.tokens.next, f.tokens.next.value), nt(150), this
                }), pt("!", function () {
                    return this.arity = "unary", this.right = nt(150), this.right || q("E041", this.line || 0), t[this.right.id] === !0 && z("W018", this, "!"), this
                }), pt("typeof", function () {
                    var e = nt(150);
                    return this.first = e, e.identifier && (e.forgiveUndef = !0), this
                }), pt("new", function () {
                    var e = nt(155), t;
                    if (e && e.id !== "function")if (e.identifier) {
                        e["new"] = !0;
                        switch (e.value) {
                            case"Number":
                            case"String":
                            case"Boolean":
                            case"Math":
                            case"JSON":
                                z("W053", f.tokens.prev, e.value);
                                break;
                            case"Symbol":
                                f.option.esnext && z("W053", f.tokens.prev, e.value);
                                break;
                            case"Function":
                                f.option.evil || z("W054");
                                break;
                            case"Date":
                            case"RegExp":
                            case"this":
                                break;
                            default:
                                e.id !== "function" && (t = e.value.substr(0, 1), f.option.newcap && (t < "A" || t > "Z") && !r.has(m, e.value) && z("W055", f.tokens.curr))
                        }
                    } else e.id !== "." && e.id !== "[" && e.id !== "(" && z("W056", f.tokens.curr); else f.option.supernew || z("W057", this);
                    return f.tokens.next.id !== "(" && !f.option.supernew && z("W058", f.tokens.curr, f.tokens.curr.value), this.first = e, this
                }), f.syntax["new"].exps = !0, pt("void").exps = !0, yt(".", function (e, t) {
                    var n = Mt(!1, !0);
                    return typeof n == "string" && Ft(n), t.left = e, t.right = n, n && n === "hasOwnProperty" && f.tokens.next.value === "=" && z("W001"), !e || e.value !== "arguments" || n !== "callee" && n !== "caller" ? !f.option.evil && e && e.value === "document" && (n === "write" || n === "writeln") && z("W060", e) : f.option.noarg ? z("W059", e, n) : f.isStrict() && X("E008"), !f.option.evil && (n === "eval" || n === "execScript") && Tt(e, f) && z("W061"), t
                }, 160, !0), yt("(", function (e, t) {
                    f.option.immed && e && !e.immed && e.id === "function" && z("W062");
                    var n = 0, r = [];
                    e && e.type === "(identifier)" && e.value.match(/^[A-Z]([A-Z0-9_$]*[a-z][A-Za-z0-9_$]*)?$/) && "Number String Boolean Date Object Error Symbol".indexOf(e.value) === -1 && (e.value === "Math" ? z("W063", e) : f.option.newcap && z("W064", e));
                    if (f.tokens.next.id !== ")")for (; ;) {
                        r[r.length] = nt(10), n += 1;
                        if (f.tokens.next.id !== ",")break;
                        ut()
                    }
                    return Y(")"), typeof e == "object" && (f.inES3() && e.value === "parseInt" && n === 1 && z("W065", f.tokens.curr), f.option.evil || (e.value === "eval" || e.value === "Function" || e.value === "execScript" ? (z("W061", e), r[0] && [0].id === "(string)" && $(e, r[0].value)) : !r[0] || r[0].id !== "(string)" || e.value !== "setTimeout" && e.value !== "setInterval" ? r[0] && r[0].id === "(string)" && e.value === "." && e.left.value === "window" && (e.right === "setTimeout" || e.right === "setInterval") && (z("W066", e), $(e, r[0].value)) : (z("W066", e), $(e, r[0].value))), !e.identifier && e.id !== "." && e.id !== "[" && e.id !== "(" && e.id !== "&&" && e.id !== "||" && e.id !== "?" && (!f.option.esnext || !e["(name)"]) && z("W067", t)), t.left = e, t
                }, 155, !0).exps = !0, pt("(", function () {
                    var e = f.tokens.next, t, n = -1, r, i, s, o, u = 1, a = f.tokens.curr, l = f.tokens.prev, c = !f.option.singleGroups;
                    do e.value === "(" ? u += 1 : e.value === ")" && (u -= 1), n += 1, t = e, e = Q(n); while ((u !== 0 || t.value !== ")") && e.value !== ";" && e.type !== "(end)");
                    f.tokens.next.id === "function" && (i = f.tokens.next.immed = !0);
                    if (e.value === "=>")return Gt({type: "arrow", parsedOpening: !0});
                    var h = [];
                    if (f.tokens.next.id !== ")")for (; ;) {
                        h.push(nt(10));
                        if (f.tokens.next.id !== ",")break;
                        f.option.nocomma && z("W127"), ut()
                    }
                    Y(")", this), f.option.immed && h[0] && h[0].id === "function" && f.tokens.next.id !== "(" && f.tokens.next.id !== "." && f.tokens.next.id !== "[" && z("W068", this);
                    if (!h.length)return;
                    return h.length > 1 ? (r = Object.create(f.syntax[","]), r.exprs = h, s = h[0], o = h[h.length - 1], c || (c = l.assign || l.delim)) : (r = s = o = h[0], c || (c = a.beginsStmt && (r.id === "{" || i || Jt(r)) || i && (!et() || f.tokens.prev.id !== "}") || Jt(r) && !et() || r.id === "{" && l.id === "=>")), r && (!c && (s.left || r.exprs) && (c = !tt(l) && s.lbp <= l.lbp || !et() && o.lbp < f.tokens.next.lbp), c || z("W126", a), r.paren = !0), r
                }), bt("=>"), yt("[", function (e, t) {
                    var n = nt(10), r;
                    return n && n.type === "(string)" && (!f.option.evil && (n.value === "eval" || n.value === "execScript") && Tt(e, f) && z("W061"), Ft(n.value), !f.option.sub && a.identifier.test(n.value) && (r = f.syntax[n.value], (!r || !H(r)) && z("W069", f.tokens.prev, n.value))), Y("]", t), n && n.value === "hasOwnProperty" && f.tokens.next.value === "=" && z("W001"), t.left = e, t.right = n, t
                }, 160, !0), pt("[", function () {
                    var e = hn();
                    if (e.isCompArray)return f.inESNext() || z("W119", f.tokens.curr, "array comprehension"), Ut();
                    e.isDestAssign && !f.inESNext() && z("W104", f.tokens.curr, "destructuring assignment");
                    var t = f.tokens.curr.line !== rt(f.tokens.next);
                    this.first = [], t && (b += f.option.indent, f.tokens.next.from === b + f.option.indent && (b += f.option.indent));
                    while (f.tokens.next.id !== "(end)") {
                        while (f.tokens.next.id === ",") {
                            if (!f.option.elision) {
                                if (!!f.inES5()) {
                                    z("W128");
                                    do Y(","); while (f.tokens.next.id === ",");
                                    continue
                                }
                                z("W070")
                            }
                            Y(",")
                        }
                        if (f.tokens.next.id === "]")break;
                        this.first.push(nt(10));
                        if (f.tokens.next.id !== ",")break;
                        ut({allowTrailing: !0});
                        if (f.tokens.next.id === "]" && !f.inES5(!0)) {
                            z("W070", f.tokens.curr);
                            break
                        }
                    }
                    return t && (b -= f.option.indent), Y("]", this), this
                }), function (e) {
                    e.nud = function () {
                        var e, t, n, r, i, s = !1, o, u = {};
                        e = f.tokens.curr.line !== rt(f.tokens.next), e && (b += f.option.indent, f.tokens.next.from === b + f.option.indent && (b += f.option.indent));
                        for (; ;) {
                            if (f.tokens.next.id === "}")break;
                            o = f.tokens.next.value;
                            if (!f.tokens.next.identifier || G().id !== "," && G().id !== "}")if (Q().id === ":" || o !== "get" && o !== "set") {
                                f.tokens.next.value === "*" && f.tokens.next.type === "(punctuator)" ? (f.inESNext() || z("W104", f.tokens.next, "generator functions"), Y("*"), s = !0) : s = !1;
                                if (f.tokens.next.id === "[")n = vn(), f.nameStack.set(n); else {
                                    f.nameStack.set(f.tokens.next), n = Xt(), pn(u, n, f.tokens.next);
                                    if (typeof n != "string")break
                                }
                                f.tokens.next.value === "(" ? (f.inESNext() || z("W104", f.tokens.curr, "concise methods"), Gt({type: s ? "generator" : null})) : (Y(":"), nt(10))
                            } else Y(o), f.inES5() || X("E034"), n = Xt(), !n && !f.inESNext() && X("E035"), n && dn(o, u, n, f.tokens.curr), i = f.tokens.next, t = Gt(), r = t["(params)"], o === "get" && n && r ? z("W076", i, r[0], n) : o === "set" && n && (!r || r.length !== 1) && z("W077", i, n); else f.inESNext() || z("W104", f.tokens.next, "object short notation"), n = Xt(!0), pn(u, n, f.tokens.next), nt(10);
                            Ft(n);
                            if (f.tokens.next.id !== ",")break;
                            ut({
                                allowTrailing: !0,
                                property: !0
                            }), f.tokens.next.id === "," ? z("W070", f.tokens.curr) : f.tokens.next.id === "}" && !f.inES5(!0) && z("W070", f.tokens.curr)
                        }
                        return e && (b -= f.option.indent), Y("}", this), tn(u), this
                    }, e.fud = function () {
                        X("E036", f.tokens.curr)
                    }
                }(ft("{"));
                var on = lt("const", function (e) {
                    return sn("const", this, e)
                });
                on.exps = !0;
                var un = lt("let", function (e) {
                    return sn("let", this, e)
                });
                un.exps = !0;
                var an = lt("var", function (e) {
                    var t = e && e.prefix, n = e && e.inexport, i, o, u, a = e && e.implied, l = !e || !e.ignore;
                    this.first = [];
                    for (; ;) {
                        var c = [];
                        r.contains(["{", "["], f.tokens.next.value) ? (i = nn(), o = !1) : (i = [{
                            id: Mt(),
                            token: f.tokens.curr
                        }], o = !0, n && (p[f.tokens.curr.value] = !0, f.tokens.curr.exported = !0));
                        for (var h in i)if (i.hasOwnProperty(h)) {
                            h = i[h], f.inESNext() && f.funct["(blockscope)"].labeltype(h.id) === "const" && z("E011", null, h.id), !a && f.funct["(global)"] && (T[h.id] === !1 ? z("W079", h.token, h.id) : f.option.futurehostile === !1 && (!f.inES5() && s.ecmaIdentifiers[5][h.id] === !1 || !f.inESNext() && s.ecmaIdentifiers[6][h.id] === !1) && z("W129", h.token, h.id));
                            if (h.id) {
                                if (a === "for") {
                                    var d = h.token.value;
                                    switch (f.funct[d]) {
                                        case"unused":
                                            f.funct[d] = "var";
                                            break;
                                        case"var":
                                            break;
                                        default:
                                            !f.funct["(blockscope)"].getlabel(d) && !(N[d] || {})[d] && l && z("W088", h.token, d)
                                    }
                                } else J(h.id, {type: "unused", token: h.token});
                                c.push(h.token)
                            }
                        }
                        !t && l && f.option.varstmt && z("W132", this), this.first = this.first.concat(c), f.tokens.next.id === "=" && (f.nameStack.set(f.tokens.curr), Y("="), !t && l && f.tokens.next.id === "undefined" && z("W080", f.tokens.prev, f.tokens.prev.value), Q(0).id === "=" && f.tokens.next.identifier && (!t && l && !f.funct["(params)"] || f.funct["(params)"].indexOf(f.tokens.next.value) === -1) && z("W120", f.tokens.next, f.tokens.next.value), u = nt(t ? 120 : 10), o ? i[0].first = u : rn(c, u));
                        if (f.tokens.next.id !== ",")break;
                        ut()
                    }
                    return this
                });
                an.exps = !0, ct("class", function () {
                    return fn.call(this, !0)
                }), ct("function", function () {
                    var e = !1;
                    f.tokens.next.value === "*" && (Y("*"), f.inESNext({strict: !0}) ? e = !0 : z("W119", f.tokens.curr, "function*")), y && z("W082", f.tokens.curr);
                    var t = Ot();
                    return t === undefined && z("W025"), f.funct["(blockscope)"].labeltype(t) === "const" && z("E011", null, t), J(t, {
                        type: "unction",
                        token: f.tokens.curr
                    }), Gt({
                        name: t,
                        statement: this,
                        type: e ? "generator" : null
                    }), f.tokens.next.id === "(" && f.tokens.next.line === f.tokens.curr.line && X("E039"), this
                }), pt("function", function () {
                    function i(e) {
                        return e[0] !== "("
                    }

                    function s(e) {
                        return n[e] === "var"
                    }

                    var e = !1;
                    f.tokens.next.value === "*" && (f.inESNext() || z("W119", f.tokens.curr, "function*"), Y("*"), e = !0);
                    var t = Ot(), n = Gt({name: t, type: e ? "generator" : null});
                    return !f.option.loopfunc && f.funct["(loopage)"] && r.some(n, function (e, t) {
                        return i(t) && !s(t)
                    }) && z("W083"), this
                }), ct("if", function () {
                    var e = f.tokens.next;
                    Zt(), f.condition = !0, Y("(");
                    var t = nt(0);
                    en(t);
                    var n = null;
                    f.option.forin && f.forinifcheckneeded && (f.forinifcheckneeded = !1, n = f.forinifchecks[f.forinifchecks.length - 1], t.type === "(punctuator)" && t.value === "!" ? n.type = "(negative)" : n.type = "(positive)"), Y(")", e), f.condition = !1;
                    var r = jt(!0, !0);
                    return n && n.type === "(negative)" && r && r.length === 1 && r[0].type === "(identifier)" && r[0].value === "continue" && (n.type = "(negative-with-continue)"), f.tokens.next.id === "else" && (Y("else"), f.tokens.next.id === "if" || f.tokens.next.id === "switch" ? Pt() : jt(!0, !0)), this
                }), ct("try", function () {
                    function t() {
                        var e = N, t;
                        Y("catch"), Y("("), N = Object.create(e), t = f.tokens.next.value, f.tokens.next.type !== "(identifier)" && (t = null, z("E030", f.tokens.next, t)), Y(), f.funct = $t("(catch)", f.tokens.next, N, {
                            "(context)": f.funct,
                            "(breakage)": f.funct["(breakage)"],
                            "(loopage)": f.funct["(loopage)"],
                            "(statement)": !1,
                            "(catch)": !0
                        }), t && J(t, {type: "exception"}), f.tokens.next.value === "if" && (f.inMoz() || z("W118", f.tokens.curr, "catch filter"), Y("if"), nt(0)), Y(")"), f.tokens.curr.funct = f.funct, v.push(f.funct), jt(!1), N = e, f.funct["(last)"] = f.tokens.curr.line, f.funct["(lastcharacter)"] = f.tokens.curr.character, f.funct = f.funct["(context)"]
                    }

                    var e;
                    jt(!0);
                    while (f.tokens.next.id === "catch")Zt(), e && !f.inMoz() && z("W118", f.tokens.next, "multiple catch blocks"), t(), e = !0;
                    if (f.tokens.next.id === "finally") {
                        Y("finally"), jt(!0);
                        return
                    }
                    return e || X("E021", f.tokens.next, "catch", f.tokens.next.value), this
                }), ct("while", function () {
                    var e = f.tokens.next;
                    return f.funct["(breakage)"] += 1, f.funct["(loopage)"] += 1, Zt(), Y("("), en(nt(0)), Y(")", e), jt(!0, !0), f.funct["(breakage)"] -= 1, f.funct["(loopage)"] -= 1, this
                }).labelled = !0, ct("with", function () {
                    var e = f.tokens.next;
                    return f.isStrict() ? X("E010", f.tokens.curr) : f.option.withstmt || z("W085", f.tokens.curr), Y("("), nt(0), Y(")", e), jt(!0, !0), this
                }), ct("switch", function () {
                    var e = f.tokens.next, t = !1, n = !1;
                    f.funct["(breakage)"] += 1, Y("("), en(nt(0)), Y(")", e), e = f.tokens.next, Y("{"), f.tokens.next.from === b && (n = !0), n || (b += f.option.indent), this.cases = [];
                    for (; ;)switch (f.tokens.next.id) {
                        case"case":
                            switch (f.funct["(verb)"]) {
                                case"yield":
                                case"break":
                                case"case":
                                case"continue":
                                case"return":
                                case"switch":
                                case"throw":
                                    break;
                                default:
                                    a.fallsThrough.test(f.lines[f.tokens.next.line - 2]) || z("W086", f.tokens.curr, "case")
                            }
                            Y("case"), this.cases.push(nt(0)), Zt(), t = !0, Y(":"), f.funct["(verb)"] = "case";
                            break;
                        case"default":
                            switch (f.funct["(verb)"]) {
                                case"yield":
                                case"break":
                                case"continue":
                                case"return":
                                case"throw":
                                    break;
                                default:
                                    this.cases.length && (a.fallsThrough.test(f.lines[f.tokens.next.line - 2]) || z("W086", f.tokens.curr, "default"))
                            }
                            Y("default"), t = !0, Y(":");
                            break;
                        case"}":
                            n || (b -= f.option.indent), Y("}", e), f.funct["(breakage)"] -= 1, f.funct["(verb)"] = undefined;
                            return;
                        case"(end)":
                            X("E023", f.tokens.next, "}");
                            return;
                        default:
                            b += f.option.indent;
                            if (t)switch (f.tokens.curr.id) {
                                case",":
                                    X("E040");
                                    return;
                                case":":
                                    t = !1, Ht();
                                    break;
                                default:
                                    X("E025", f.tokens.curr);
                                    return
                            } else {
                                if (f.tokens.curr.id !== ":") {
                                    X("E021", f.tokens.next, "case", f.tokens.next.value);
                                    return
                                }
                                Y(":"), X("E024", f.tokens.curr, ":"), Ht()
                            }
                            b -= f.option.indent
                    }
                }).labelled = !0, lt("debugger", function () {
                    return f.option.debug || z("W087", this), this
                }).exps = !0, function () {
                    var e = lt("do", function () {
                        f.funct["(breakage)"] += 1, f.funct["(loopage)"] += 1, Zt(), this.first = jt(!0, !0), Y("while");
                        var e = f.tokens.next;
                        return Y("("), en(nt(0)), Y(")", e), f.funct["(breakage)"] -= 1, f.funct["(loopage)"] -= 1, this
                    });
                    e.labelled = !0, e.exps = !0
                }(), ct("for", function () {
                    var e, t = f.tokens.next, n = !1, i = null;
                    t.value === "each" && (i = t, Y("each"), f.inMoz() || z("W118", f.tokens.curr, "for each")), f.funct["(breakage)"] += 1, f.funct["(loopage)"] += 1, Zt(), Y("(");
                    var s, o = 0, u = ["in", "of"], a = 0, l, c;
                    mn(f.tokens.next, ["{", "["]) && ++a;
                    do {
                        s = Q(o), ++o, mn(s, ["{", "["]) ? ++a : mn(s, ["}", "]"]) && --a;
                        if (a < 0)break;
                        a === 0 && (!l && mn(s, [","]) ? l = s : !c && mn(s, ["="]) && (c = s))
                    } while (a > 0 || !r.contains(u, s.value) && s.value !== ";" && s.type !== "(end)");
                    if (r.contains(u, s.value)) {
                        !f.inESNext() && s.value === "of" && X("W104", s, "for of");
                        var h = !c && !l;
                        c && X("W133", l, s.value, "initializer is forbidden"), l && X("W133", l, s.value, "more than one ForBinding"), f.tokens.next.id === "var" ? (Y("var"), f.tokens.curr.fud({prefix: !0})) : f.tokens.next.id === "let" || f.tokens.next.id === "const" ? (Y(f.tokens.next.id), n = !0, f.funct["(blockscope)"].stack(), f.tokens.curr.fud({prefix: !0})) : Object.create(an).fud({
                            prefix: !0,
                            implied: "for",
                            ignore: !h
                        }), Y(s.value), nt(20), Y(")", t), s.value === "in" && f.option.forin && (f.forinifcheckneeded = !0, f.forinifchecks === undefined && (f.forinifchecks = []), f.forinifchecks.push({type: "(none)"})), e = jt(!0, !0);
                        if (s.value === "in" && f.option.forin) {
                            if (f.forinifchecks && f.forinifchecks.length > 0) {
                                var p = f.forinifchecks.pop();
                                (e && e.length > 0 && (typeof e[0] != "object" || e[0].value !== "if") || p.type === "(positive)" && e.length > 1 || p.type === "(negative)") && z("W089", this)
                            }
                            f.forinifcheckneeded = !1
                        }
                        f.funct["(breakage)"] -= 1, f.funct["(loopage)"] -= 1
                    } else {
                        i && X("E045", i);
                        if (f.tokens.next.id !== ";")if (f.tokens.next.id === "var")Y("var"), f.tokens.curr.fud(); else if (f.tokens.next.id === "let")Y("let"), n = !0, f.funct["(blockscope)"].stack(), f.tokens.curr.fud(); else for (; ;) {
                            nt(0, "for");
                            if (f.tokens.next.id !== ",")break;
                            l()
                        }
                        st(f.tokens.curr), Y(";"), f.tokens.next.id !== ";" && en(nt(0)), st(f.tokens.curr), Y(";"), f.tokens.next.id === ";" && X("E021", f.tokens.next, ")", ";");
                        if (f.tokens.next.id !== ")")for (; ;) {
                            nt(0, "for");
                            if (f.tokens.next.id !== ",")break;
                            l()
                        }
                        Y(")", t), jt(!0, !0), f.funct["(breakage)"] -= 1, f.funct["(loopage)"] -= 1
                    }
                    return n && f.funct["(blockscope)"].unstack(), this
                }).labelled = !0, lt("break", function () {
                    var e = f.tokens.next.value;
                    return f.funct["(breakage)"] === 0 && z("W052", f.tokens.next, this.value), f.option.asi || st(this), f.tokens.next.id !== ";" && !f.tokens.next.reach && f.tokens.curr.line === rt(f.tokens.next) && (f.funct[e] !== "label" ? z("W090", f.tokens.next, e) : N[e] !== f.funct && z("W091", f.tokens.next, e), this.first = f.tokens.next, Y()), _t(this), this
                }).exps = !0, lt("continue", function () {
                    var e = f.tokens.next.value;
                    return f.funct["(breakage)"] === 0 && z("W052", f.tokens.next, this.value), f.option.asi || st(this), f.tokens.next.id !== ";" && !f.tokens.next.reach ? f.tokens.curr.line === rt(f.tokens.next) && (f.funct[e] !== "label" ? z("W090", f.tokens.next, e) : N[e] !== f.funct && z("W091", f.tokens.next, e), this.first = f.tokens.next, Y()) : f.funct["(loopage)"] || z("W052", f.tokens.next, this.value), _t(this), this
                }).exps = !0, lt("return", function () {
                    return this.line === rt(f.tokens.next) ? f.tokens.next.id !== ";" && !f.tokens.next.reach && (this.first = nt(0), this.first && this.first.type === "(punctuator)" && this.first.value === "=" && !this.first.paren && !f.option.boss && W("W093", this.first.line, this.first.character)) : f.tokens.next.type === "(punctuator)" && ["[", "{", "+", "-"].indexOf(f.tokens.next.value) > -1 && st(this), _t(this), this
                }).exps = !0, function (e) {
                    e.exps = !0, e.lbp = 25
                }(pt("yield", function () {
                    var e = f.tokens.prev;
                    f.inESNext(!0) && !f.funct["(generator)"] ? ("(catch)" !== f.funct["(name)"] || !f.funct["(context)"]["(generator)"]) && X("E046", f.tokens.curr, "yield") : f.inESNext() || z("W104", f.tokens.curr, "yield"), f.funct["(generator)"] = "yielded";
                    var t = !1;
                    f.tokens.next.value === "*" && (t = !0, Y("*"));
                    if (this.line === rt(f.tokens.next) || !f.inMoz()) {
                        if (t || f.tokens.next.id !== ";" && !f.tokens.next.reach && f.tokens.next.nud)it(f.tokens.curr, f.tokens.next), this.first = nt(10), this.first.type === "(punctuator)" && this.first.value === "=" && !this.first.paren && !f.option.boss && W("W093", this.first.line, this.first.character);
                        f.inMoz() && f.tokens.next.id !== ")" && (e.lbp > 30 || !e.assign && !et() || e.id === "yield") && X("E050", this)
                    } else f.option.asi || st(this);
                    return this
                })), lt("throw", function () {
                    return st(this), this.first = nt(20), _t(this), this
                }).exps = !0, lt("import", function () {
                    f.inESNext() || z("W119", f.tokens.curr, "import");
                    if (f.tokens.next.type === "(string)")return Y("(string)"), this;
                    if (f.tokens.next.identifier) {
                        this.name = Mt(), J(this.name, {type: "unused", token: f.tokens.curr});
                        if (f.tokens.next.value !== ",")return Y("from"), Y("(string)"), this;
                        Y(",")
                    }
                    if (f.tokens.next.id === "*")Y("*"), Y("as"), f.tokens.next.identifier && (this.name = Mt(), J(this.name, {
                        type: "unused",
                        token: f.tokens.curr
                    })); else {
                        Y("{");
                        for (; ;) {
                            if (f.tokens.next.value === "}") {
                                Y("}");
                                break
                            }
                            var e;
                            f.tokens.next.type === "default" ? (e = "default", Y("default")) : e = Mt(), f.tokens.next.value === "as" && (Y("as"), e = Mt()), J(e, {
                                type: "unused",
                                token: f.tokens.curr
                            });
                            if (f.tokens.next.value !== ",") {
                                if (f.tokens.next.value === "}") {
                                    Y("}");
                                    break
                                }
                                X("E024", f.tokens.next, f.tokens.next.value);
                                break
                            }
                            Y(",")
                        }
                    }
                    return Y("from"), Y("(string)"), this
                }).exps = !0, lt("export", function () {
                    var e = !0, t, n;
                    f.inESNext() || (z("W119", f.tokens.curr, "export"), e = !1);
                    if (!f.funct["(global)"] || !f.funct["(blockscope)"].atTop())X("E053", f.tokens.curr), e = !1;
                    if (f.tokens.next.value === "*")return Y("*"), Y("from"), Y("(string)"), this;
                    if (f.tokens.next.type === "default") {
                        f.nameStack.set(f.tokens.next), Y("default");
                        if (f.tokens.next.id === "function" || f.tokens.next.id === "class")this.block = !0;
                        return t = Q(), nt(10), f.tokens.next.id === "class" ? n = t.name : n = t.value, J(n, {
                            type: "function",
                            token: t
                        }), this
                    }
                    if (f.tokens.next.value === "{") {
                        Y("{");
                        var r = [];
                        for (; ;) {
                            f.tokens.next.identifier || X("E030", f.tokens.next, f.tokens.next.value), Y(), f.tokens.curr.exported = e, r.push(f.tokens.curr), f.tokens.next.value === "as" && (Y("as"), f.tokens.next.identifier || X("E030", f.tokens.next, f.tokens.next.value), Y());
                            if (f.tokens.next.value !== ",") {
                                if (f.tokens.next.value === "}") {
                                    Y("}");
                                    break
                                }
                                X("E024", f.tokens.next, f.tokens.next.value);
                                break
                            }
                            Y(",")
                        }
                        return f.tokens.next.value === "from" ? (Y("from"), Y("(string)")) : e && r.forEach(function (e) {
                            f.funct[e.value] || R(f.funct, "W117", e, e.value), p[e.value] = !0, f.funct["(blockscope)"].setExported(e.value)
                        }), this
                    }
                    return f.tokens.next.id === "var" ? (Y("var"), f.tokens.curr.fud({inexport: !0})) : f.tokens.next.id === "let" ? (Y("let"), f.tokens.curr.fud({inexport: !0})) : f.tokens.next.id === "const" ? (Y("const"), f.tokens.curr.fud({inexport: !0})) : f.tokens.next.id === "function" ? (this.block = !0, Y("function"), p[f.tokens.next.value] = e, f.tokens.next.exported = !0, f.syntax["function"].fud()) : f.tokens.next.id === "class" ? (this.block = !0, Y("class"), p[f.tokens.next.value] = e, f.tokens.next.exported = !0, f.syntax["class"].fud()) : X("E024", f.tokens.next, f.tokens.next.value), this
                }).exps = !0, mt("abstract"), mt("boolean"), mt("byte"), mt("char"), mt("class", {
                    es5: !0,
                    nud: fn
                }), mt("double"), mt("enum", {es5: !0}), mt("export", {es5: !0}), mt("extends", {es5: !0}), mt("final"), mt("float"), mt("goto"), mt("implements", {
                    es5: !0,
                    strictOnly: !0
                }), mt("import", {es5: !0}), mt("int"), mt("interface", {
                    es5: !0,
                    strictOnly: !0
                }), mt("long"), mt("native"), mt("package", {es5: !0, strictOnly: !0}), mt("private", {
                    es5: !0,
                    strictOnly: !0
                }), mt("protected", {es5: !0, strictOnly: !0}), mt("public", {
                    es5: !0,
                    strictOnly: !0
                }), mt("short"), mt("static", {
                    es5: !0,
                    strictOnly: !0
                }), mt("super", {es5: !0}), mt("synchronized"), mt("transient"), mt("volatile");
                var hn = function () {
                    var e, t, n = -1, r = 0, i = {};
                    mn(f.tokens.curr, ["[", "{"]) && (r += 1);
                    do {
                        e = n === -1 ? f.tokens.next : Q(n), t = Q(n + 1), n += 1, mn(e, ["[", "{"]) ? r += 1 : mn(e, ["]", "}"]) && (r -= 1);
                        if (e.identifier && e.value === "for" && r === 1) {
                            i.isCompArray = !0, i.notJson = !0;
                            break
                        }
                        if (mn(e, ["}", "]"]) && r === 0) {
                            if (t.value === "=") {
                                i.isDestAssign = !0, i.notJson = !0;
                                break
                            }
                            if (t.value === ".") {
                                i.notJson = !0;
                                break
                            }
                        }
                        e.value === ";" && (i.isBlock = !0, i.notJson = !0)
                    } while (r > 0 && e.id !== "(end)");
                    return i
                }, yn = function () {
                    function i(e) {
                        var t = n.variables.filter(function (t) {
                            if (t.value === e)return t.undef = !1, e
                        }).length;
                        return t !== 0
                    }

                    function s(e) {
                        var t = n.variables.filter(function (t) {
                            if (t.value === e && !t.undef)return t.unused === !0 && (t.unused = !1), e
                        }).length;
                        return t === 0
                    }

                    var e = function () {
                        this.mode = "use", this.variables = []
                    }, t = [], n;
                    return {
                        stack: function () {
                            n = new e, t.push(n)
                        }, unstack: function () {
                            n.variables.filter(function (e) {
                                e.unused && z("W098", e.token, e.raw_text || e.value), e.undef && R(e.state.funct, "W117", e.token, e.value)
                            }), t.splice(-1, 1), n = t[t.length - 1]
                        }, setState: function (e) {
                            r.contains(["use", "define", "generate", "filter"], e) && (n.mode = e)
                        }, check: function (e) {
                            if (!n)return;
                            return n && n.mode === "use" ? (s(e) && n.variables.push({
                                funct: f.funct,
                                token: f.tokens.curr,
                                value: e,
                                undef: !0,
                                unused: !1
                            }), !0) : n && n.mode === "define" ? (i(e) || n.variables.push({
                                funct: f.funct,
                                token: f.tokens.curr,
                                value: e,
                                undef: !1,
                                unused: !0
                            }), !0) : n && n.mode === "generate" ? (R(f.funct, "W117", f.tokens.curr, e), !0) : n && n.mode === "filter" ? (s(e) && R(f.funct, "W117", f.tokens.curr, e), !0) : !1
                        }
                    }
                }, wn = function (e, t, n, r) {
                    var i = t.line, s = t.from, o = t.raw_text || e;
                    r === undefined && (r = f.option.unused), r === !0 && (r = "last-param");
                    var u = {vars: ["var"], "last-param": ["var", "param"], strict: ["var", "param", "last-param"]};
                    r && u[r] && u[r].indexOf(n) !== -1 && (t.exported || W("W098", i, s, o)), k.push({
                        name: e,
                        line: i,
                        character: s
                    })
                }, En = function () {
                    function n() {
                        for (var t in e) {
                            var n = e[t], r = n["(type)"];
                            if (r === "unused" || r === "const" && n["(unused)"])if (f.option.unused) {
                                var i = e[t]["(token)"];
                                if (i.exported)continue;
                                wn(t, i, "var")
                            }
                        }
                    }

                    function i(e) {
                        for (var n = t.length - 1; n >= 0; --n)if (r.has(t[n], e) && !t[n][e]["(shadowed)"])return t[n]
                    }

                    var e = {}, t = [e];
                    return {
                        stack: function () {
                            e = {}, t.push(e)
                        }, unstack: function () {
                            n(), t.splice(t.length - 1, 1), e = r.last(t)
                        }, getlabel: i, labeltype: function (e) {
                            var t = i(e);
                            return t ? t[e]["(type)"] : null
                        }, shadow: function (e) {
                            for (var n = t.length - 1; n >= 0; n--)r.has(t[n], e) && (t[n][e]["(shadowed)"] = !0)
                        }, unshadow: function (e) {
                            for (var n = t.length - 1; n >= 0; n--)r.has(t[n], e) && (t[n][e]["(shadowed)"] = !1)
                        }, atTop: function () {
                            return t.length === 1
                        }, setExported: function (t) {
                            if (f.funct["(blockscope)"].atTop()) {
                                var n = e[t];
                                n && n["(token)"] && (n["(token)"].exported = !0)
                            }
                        }, current: {
                            labeltype: function (t) {
                                return e[t] ? e[t]["(type)"] : null
                            }, has: function (t) {
                                return r.has(e, t)
                            }, add: function (t, n, r) {
                                e[t] = {"(type)": n, "(token)": r, "(shadowed)": !1, "(unused)": !0}
                            }
                        }
                    }
                }, Sn = function (e) {
                    return e.replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&")
                }, xn = function (t, i, o) {
                    function R(e, t) {
                        if (!e)return;
                        !Array.isArray(e) && typeof e == "object" && (e = Object.keys(e)), e.forEach(t)
                    }

                    var a, l, c, d, P, H, B = {}, F = {};
                    i = r.clone(i), f.reset(), i && i.scope ? h.scope = i.scope : (h.errors = [], h.undefs = [], h.internals = [], h.blacklist = {}, h.scope = "(main)"), T = Object.create(null), j(T, s.ecmaIdentifiers[3]), j(T, s.reservedVars), j(T, o || {}), n = Object.create(null), p = Object.create(null);
                    if (i) {
                        R(i.predef || null, function (e) {
                            var t, n;
                            e[0] === "-" ? (t = e.slice(1), h.blacklist[t] = t, delete T[t]) : (n = Object.getOwnPropertyDescriptor(i.predef, e), T[e] = n ? n.value : !1)
                        }), R(i.exported || null, function (e) {
                            p[e] = !0
                        }), delete i.predef, delete i.exported, H = Object.keys(i);
                        for (c = 0; c < H.length; c++)if (/^-W\d{3}$/g.test(H[c]))F[H[c].slice(1)] = !0; else {
                            var U = H[c];
                            B[U] = i[U], U === "es5" && i[U] && z("I003"), H[c] === "newcap" && i[U] === !1 && (B["(explicitNewcap)"] = !0)
                        }
                    }
                    f.option = B, f.ignored = F, f.option.indent = f.option.indent || 4, f.option.maxerr = f.option.maxerr || 50, b = 1, m = Object.create(T), N = m, f.funct = $t("(global)", null, N, {
                        "(global)": !0,
                        "(blockscope)": En(),
                        "(comparray)": yn(),
                        "(metrics)": Yt(f.tokens.next)
                    }), v = [f.funct], L = [], C = null, S = {}, x = null, g = {}, y = !1, w = [], k = [];
                    if (!D(t) && !Array.isArray(t))return V("E004", 0), !1;
                    e = {
                        get isJSON() {
                            return f.jsonMode
                        }, getOption: function (e) {
                            return f.option[e] || null
                        }, getCache: function (e) {
                            return f.cache[e]
                        }, setCache: function (e, t) {
                            f.cache[e] = t
                        }, warn: function (e, t) {
                            W.apply(null, [e, t.line, t.char].concat(t.data))
                        }, on: function (e, t) {
                            e.split(" ").forEach(function (e) {
                                O.on(e, t)
                            }.bind(this))
                        }
                    }, O.removeAllListeners(), (A || []).forEach(function (t) {
                        t(e)
                    }), f.tokens.prev = f.tokens.curr = f.tokens.next = f.syntax["(begin)"], i && i.ignoreDelimiters && (Array.isArray(i.ignoreDelimiters) || (i.ignoreDelimiters = [i.ignoreDelimiters]), i.ignoreDelimiters.forEach(function (e) {
                        if (!e.start || !e.end)return;
                        d = Sn(e.start) + "[\\s\\S]*?" + Sn(e.end), P = new RegExp(d, "ig"), t = t.replace(P, function (e) {
                            return e.replace(/./g, " ")
                        })
                    })), E = new u(t), E.on("warning", function (e) {
                        W.apply(null, [e.code, e.line, e.character].concat(e.data))
                    }), E.on("error", function (e) {
                        V.apply(null, [e.code, e.line, e.character].concat(e.data))
                    }), E.on("fatal", function (e) {
                        q("E041", e.line, e.from)
                    }), E.on("Identifier", function (e) {
                        O.emit("Identifier", e)
                    }), E.on("String", function (e) {
                        O.emit("String", e)
                    }), E.on("Number", function (e) {
                        O.emit("Number", e)
                    }), E.start();
                    for (var X in i)r.has(i, X) && M(X, f.tokens.curr);
                    I(), j(T, o || {}), ut.first = !0;
                    try {
                        Y();
                        switch (f.tokens.next.id) {
                            case"{":
                            case"[":
                                gn();
                                break;
                            default:
                                Bt(), f.isStrict() && (f.option.globalstrict || f.option.module || f.option.node || f.option.phantom || f.option.browserify || z("W097", f.tokens.prev)), Ht()
                        }
                        f.tokens.next.id !== "(end)" && q("E041", f.tokens.curr.line), f.funct["(blockscope)"].unstack();
                        var $ = function (e, t) {
                            do {
                                if (typeof t[e] == "string")return t[e] === "unused" ? t[e] = "var" : t[e] === "unction" && (t[e] = "closure"), !0;
                                t = t["(context)"]
                            } while (t);
                            return !1
                        }, J = function (e, t) {
                            if (!g[e])return;
                            var n = [];
                            for (var r = 0; r < g[e].length; r += 1)g[e][r] !== t && n.push(g[e][r]);
                            n.length === 0 ? delete g[e] : g[e] = n
                        }, K = function (e, t) {
                            var n = e[t], i = e["(tokens)"][t];
                            if (t.charAt(0) === "(")return;
                            if (n !== "unused" && n !== "unction")return;
                            if (e["(params)"] && e["(params)"].indexOf(t) !== -1)return;
                            if (e["(global)"] && r.has(p, t))return;
                            wn(t, i, "var")
                        };
                        for (a = 0; a < h.undefs.length; a += 1)l = h.undefs[a].slice(0), $(l[2].value, l[0]) || l[2].forgiveUndef ? J(l[2].value, l[2].line) : f.option.undef && z.apply(z, l.slice(1));
                        v.forEach(function (e) {
                            if (e["(unusedOption)"] === !1)return;
                            for (var t in e)r.has(e, t) && K(e, t);
                            if (!e["(params)"])return;
                            var n = e["(params)"].slice(), i = n.pop(), s, o;
                            while (i) {
                                s = e[i], o = e["(unusedOption)"] || f.option.unused, o = o === !0 ? "last-param" : o;
                                if (i === "undefined")return;
                                if (s === "unused" || s === "unction")wn(i, e["(tokens)"][i], "param", e["(unusedOption)"]); else if (o === "last-param")return;
                                i = n.pop()
                            }
                        });
                        for (var Q in n)r.has(n, Q) && !r.has(m, Q) && !r.has(p, Q) && wn(Q, n[Q], "var")
                    } catch (G) {
                        if (!G || G.name !== "JSHintError")throw G;
                        var Z = f.tokens.next || {};
                        h.errors.push({
                            scope: "(main)",
                            raw: G.raw,
                            code: G.code,
                            reason: G.message,
                            line: G.line || Z.line,
                            character: G.character || Z.from
                        }, null)
                    }
                    if (h.scope === "(main)") {
                        i = i || {};
                        for (a = 0; a < h.internals.length; a += 1)l = h.internals[a], i.scope = l.elem, xn(l.value, i, o)
                    }
                    return h.errors.length === 0
                };
                return xn.addModule = function (e) {
                    A.push(e)
                }, xn.addModule(l.register), xn.data = function () {
                    var e = {functions: [], options: f.option}, t = [], n = [], i, s, o, u, a, l;
                    xn.errors.length && (e.errors = xn.errors), f.jsonMode && (e.json = !0);
                    for (a in g)r.has(g, a) && t.push({name: a, line: g[a]});
                    t.length > 0 && (e.implieds = t), L.length > 0 && (e.urls = L), l = Object.keys(N), l.length > 0 && (e.globals = l);
                    for (o = 1; o < v.length; o += 1) {
                        s = v[o], i = {};
                        for (u = 0; u < d.length; u += 1)i[d[u]] = [];
                        for (u = 0; u < d.length; u += 1)i[d[u]].length === 0 && delete i[d[u]];
                        i.name = s["(name)"], i.param = s["(params)"], i.line = s["(line)"], i.character = s["(character)"], i.last = s["(last)"], i.lastcharacter = s["(lastcharacter)"], i.metrics = {
                            complexity: s["(metrics)"].ComplexityCount,
                            parameters: (s["(params)"] || []).length,
                            statements: s["(metrics)"].statementCount
                        }, e.functions.push(i)
                    }
                    k.length > 0 && (e.unused = k), n = [];
                    for (a in S)if (typeof S[a] == "number") {
                        e.member = S;
                        break
                    }
                    return e
                }, xn.jshint = xn, xn
            }();
            typeof n == "object" && n && (n.JSHINT = h)
        }, {
            "./lex.js": "/node_modules/jshint/src/lex.js",
            "./messages.js": "/node_modules/jshint/src/messages.js",
            "./options.js": "/node_modules/jshint/src/options.js",
            "./reg.js": "/node_modules/jshint/src/reg.js",
            "./state.js": "/node_modules/jshint/src/state.js",
            "./style.js": "/node_modules/jshint/src/style.js",
            "./vars.js": "/node_modules/jshint/src/vars.js",
            events: "/node_modules/browserify/node_modules/events/events.js",
            underscore: "/node_modules/jshint/node_modules/underscore/underscore.js"
        }],
        "/node_modules/jshint/src/lex.js": [function (e, t, n) {
            "use strict";
            function h() {
                var e = [];
                return {
                    push: function (t) {
                        e.push(t)
                    }, check: function () {
                        for (var t = 0; t < e.length; ++t)e[t]();
                        e.splice(0, e.length)
                    }
                }
            }

            function p(e) {
                var t = e;
                typeof t == "string" && (t = t.replace(/\r\n/g, "\n").replace(/\r/g, "\n").split("\n")), t[0] && t[0].substr(0, 2) === "#!" && (t[0].indexOf("node") !== -1 && (o.option.node = !0), t[0] = ""), this.emitter = new i.EventEmitter, this.source = e, this.setLines(t), this.prereg = !0, this.line = 0, this.char = 1, this.from = 1, this.input = "", this.inComment = !1, this.context = [], this.templateStarts = [];
                for (var n = 0; n < o.option.indent; n += 1)o.tab += " "
            }

            var r = e("underscore"), i = e("events"), s = e("./reg.js"), o = e("./state.js").state, u = e("../data/ascii-identifier-data.js"), a = u.asciiIdentifierStartTable, f = u.asciiIdentifierPartTable, l = {
                Identifier: 1,
                Punctuator: 2,
                NumericLiteral: 3,
                StringLiteral: 4,
                Comment: 5,
                Keyword: 6,
                NullLiteral: 7,
                BooleanLiteral: 8,
                RegExp: 9,
                TemplateHead: 10,
                TemplateMiddle: 11,
                TemplateTail: 12,
                NoSubstTemplate: 13
            }, c = {Block: 1, Template: 2};
            p.prototype = {
                _lines: [], inContext: function (e) {
                    return this.context.length > 0 && this.context[this.context.length - 1].type === e
                }, pushContext: function (e) {
                    this.context.push({type: e})
                }, popContext: function () {
                    return this.context.pop()
                }, isContext: function (e) {
                    return this.context.length > 0 && this.context[this.context.length - 1] === e
                }, currentContext: function () {
                    return this.context.length > 0 && this.context[this.context.length - 1]
                }, getLines: function () {
                    return this._lines = o.lines, this._lines
                }, setLines: function (e) {
                    this._lines = e, o.lines = this._lines
                }, peek: function (e) {
                    return this.input.charAt(e || 0)
                }, skip: function (e) {
                    e = e || 1, this.char += e, this.input = this.input.slice(e)
                }, on: function (e, t) {
                    e.split(" ").forEach(function (e) {
                        this.emitter.on(e, t)
                    }.bind(this))
                }, trigger: function () {
                    this.emitter.emit.apply(this.emitter, Array.prototype.slice.call(arguments))
                }, triggerAsync: function (e, t, n, r) {
                    n.push(function () {
                        r() && this.trigger(e, t)
                    }.bind(this))
                }, scanPunctuator: function () {
                    var e = this.peek(), t, n, r;
                    switch (e) {
                        case".":
                            if (/^[0-9]$/.test(this.peek(1)))return null;
                            if (this.peek(1) === "." && this.peek(2) === ".")return {type: l.Punctuator, value: "..."};
                        case"(":
                        case")":
                        case";":
                        case",":
                        case"[":
                        case"]":
                        case":":
                        case"~":
                        case"?":
                            return {type: l.Punctuator, value: e};
                        case"{":
                            return this.pushContext(c.Block), {type: l.Punctuator, value: e};
                        case"}":
                            return this.inContext(c.Block) && this.popContext(), {type: l.Punctuator, value: e};
                        case"#":
                            return {type: l.Punctuator, value: e};
                        case"":
                            return null
                    }
                    return t = this.peek(1), n = this.peek(2), r = this.peek(3), e === ">" && t === ">" && n === ">" && r === "=" ? {
                        type: l.Punctuator,
                        value: ">>>="
                    } : e === "=" && t === "=" && n === "=" ? {
                        type: l.Punctuator,
                        value: "==="
                    } : e === "!" && t === "=" && n === "=" ? {
                        type: l.Punctuator,
                        value: "!=="
                    } : e === ">" && t === ">" && n === ">" ? {
                        type: l.Punctuator,
                        value: ">>>"
                    } : e === "<" && t === "<" && n === "=" ? {
                        type: l.Punctuator,
                        value: "<<="
                    } : e === ">" && t === ">" && n === "=" ? {
                        type: l.Punctuator,
                        value: ">>="
                    } : e === "=" && t === ">" ? {
                        type: l.Punctuator,
                        value: e + t
                    } : e === t && "+-<>&|".indexOf(e) >= 0 ? {
                        type: l.Punctuator,
                        value: e + t
                    } : "<>=!+-*%&|^".indexOf(e) >= 0 ? t === "=" ? {type: l.Punctuator, value: e + t} : {
                        type: l.Punctuator,
                        value: e
                    } : e === "/" ? t === "=" ? {type: l.Punctuator, value: "/="} : {
                        type: l.Punctuator,
                        value: "/"
                    } : null
                }, scanComments: function () {
                    function s(e, t, n) {
                        var r = ["jshint", "jslint", "members", "member", "globals", "global", "exported"], i = !1, s = e + t, o = "plain";
                        return n = n || {}, n.isMultiline && (s += "*/"), t = t.replace(/\n/g, " "), r.forEach(function (n) {
                            if (i)return;
                            if (e === "//" && n !== "jshint")return;
                            t.charAt(n.length) === " " && t.substr(0, n.length) === n && (i = !0, e += n, t = t.substr(n.length)), !i && t.charAt(0) === " " && t.charAt(n.length + 1) === " " && t.substr(1, n.length) === n && (i = !0, e = e + " " + n, t = t.substr(n.length + 1));
                            if (!i)return;
                            switch (n) {
                                case"member":
                                    o = "members";
                                    break;
                                case"global":
                                    o = "globals";
                                    break;
                                default:
                                    o = n
                            }
                        }), {
                            type: l.Comment,
                            commentType: o,
                            value: s,
                            body: t,
                            isSpecial: i,
                            isMultiline: n.isMultiline || !1,
                            isMalformed: n.isMalformed || !1
                        }
                    }

                    var e = this.peek(), t = this.peek(1), n = this.input.substr(2), r = this.line, i = this.char;
                    if (e === "*" && t === "/")return this.trigger("error", {
                        code: "E018",
                        line: r,
                        character: i
                    }), this.skip(2), null;
                    if (e !== "/" || t !== "*" && t !== "/")return null;
                    if (t === "/")return this.skip(this.input.length), s("//", n);
                    var o = "";
                    if (t === "*") {
                        this.inComment = !0, this.skip(2);
                        while (this.peek() !== "*" || this.peek(1) !== "/")if (this.peek() === "") {
                            o += "\n";
                            if (!this.nextLine())return this.trigger("error", {
                                code: "E017",
                                line: r,
                                character: i
                            }), this.inComment = !1, s("/*", o, {isMultiline: !0, isMalformed: !0})
                        } else o += this.peek(), this.skip();
                        return this.skip(2), this.inComment = !1, s("/*", o, {isMultiline: !0})
                    }
                }, scanKeyword: function () {
                    var e = /^[a-zA-Z_$][a-zA-Z0-9_$]*/.exec(this.input), t = ["if", "in", "do", "var", "for", "new", "try", "let", "this", "else", "case", "void", "with", "enum", "while", "break", "catch", "throw", "const", "yield", "class", "super", "return", "typeof", "delete", "switch", "export", "import", "default", "finally", "extends", "function", "continue", "debugger", "instanceof"];
                    return e && t.indexOf(e[0]) >= 0 ? {type: l.Keyword, value: e[0]} : null
                }, scanIdentifier: function () {
                    function i(e) {
                        return e > 256
                    }

                    function s(e) {
                        return e > 256
                    }

                    function o(e) {
                        return /^[0-9a-fA-F]$/.test(e)
                    }

                    function p(e) {
                        return e.replace(/\\u([0-9a-fA-F]{4})/g, function (e, t) {
                            return String.fromCharCode(parseInt(t, 16))
                        })
                    }

                    var e = "", t = 0, n, r, u = function () {
                        t += 1;
                        if (this.peek(t) !== "u")return null;
                        var e = this.peek(t + 1), n = this.peek(t + 2), r = this.peek(t + 3), i = this.peek(t + 4), u;
                        return o(e) && o(n) && o(r) && o(i) ? (u = parseInt(e + n + r + i, 16), f[u] || s(u) ? (t += 5, "\\u" + e + n + r + i) : null) : null
                    }.bind(this), c = function () {
                        var e = this.peek(t), n = e.charCodeAt(0);
                        return n === 92 ? u() : n < 128 ? a[n] ? (t += 1, e) : null : i(n) ? (t += 1, e) : null
                    }.bind(this), h = function () {
                        var e = this.peek(t), n = e.charCodeAt(0);
                        return n === 92 ? u() : n < 128 ? f[n] ? (t += 1, e) : null : s(n) ? (t += 1, e) : null
                    }.bind(this);
                    r = c();
                    if (r === null)return null;
                    e = r;
                    for (; ;) {
                        r = h();
                        if (r === null)break;
                        e += r
                    }
                    switch (e) {
                        case"true":
                        case"false":
                            n = l.BooleanLiteral;
                            break;
                        case"null":
                            n = l.NullLiteral;
                            break;
                        default:
                            n = l.Identifier
                    }
                    return {type: n, value: p(e), text: e, tokenLength: e.length}
                }, scanNumericLiteral: function () {
                    function f(e) {
                        return /^[0-9]$/.test(e)
                    }

                    function c(e) {
                        return /^[0-7]$/.test(e)
                    }

                    function h(e) {
                        return /^[01]$/.test(e)
                    }

                    function p(e) {
                        return /^[0-9a-fA-F]$/.test(e)
                    }

                    function d(e) {
                        return e === "$" || e === "_" || e === "\\" || e >= "a" && e <= "z" || e >= "A" && e <= "Z"
                    }

                    var e = 0, t = "", n = this.input.length, r = this.peek(e), i, s = f, u = 10, a = !1;
                    if (r !== "." && !f(r))return null;
                    if (r !== ".") {
                        t = this.peek(e), e += 1, r = this.peek(e);
                        if (t === "0") {
                            if (r === "x" || r === "X")s = p, u = 16, e += 1, t += r;
                            if (r === "o" || r === "O")s = c, u = 8, o.option.esnext || this.trigger("warning", {
                                code: "W119",
                                line: this.line,
                                character: this.char,
                                data: ["Octal integer literal"]
                            }), e += 1, t += r;
                            if (r === "b" || r === "B")s = h, u = 2, o.option.esnext || this.trigger("warning", {
                                code: "W119",
                                line: this.line,
                                character: this.char,
                                data: ["Binary integer literal"]
                            }), e += 1, t += r;
                            c(r) && (s = c, u = 8, a = !0, i = !1, e += 1, t += r), !c(r) && f(r) && (e += 1, t += r)
                        }
                        while (e < n) {
                            r = this.peek(e);
                            if (a && f(r))i = !0; else if (!s(r))break;
                            t += r, e += 1
                        }
                        if (s !== f) {
                            if (!a && t.length <= 2)return {type: l.NumericLiteral, value: t, isMalformed: !0};
                            if (e < n) {
                                r = this.peek(e);
                                if (d(r))return null
                            }
                            return {type: l.NumericLiteral, value: t, base: u, isLegacy: a, isMalformed: !1}
                        }
                    }
                    if (r === ".") {
                        t += r, e += 1;
                        while (e < n) {
                            r = this.peek(e);
                            if (!f(r))break;
                            t += r, e += 1
                        }
                    }
                    if (r === "e" || r === "E") {
                        t += r, e += 1, r = this.peek(e);
                        if (r === "+" || r === "-")t += this.peek(e), e += 1;
                        r = this.peek(e);
                        if (!f(r))return null;
                        t += r, e += 1;
                        while (e < n) {
                            r = this.peek(e);
                            if (!f(r))break;
                            t += r, e += 1
                        }
                    }
                    if (e < n) {
                        r = this.peek(e);
                        if (d(r))return null
                    }
                    return {type: l.NumericLiteral, value: t, base: u, isMalformed: !isFinite(t)}
                }, scanEscapeSequence: function (e) {
                    var t = !1, n = 1;
                    this.skip();
                    var r = this.peek();
                    switch (r) {
                        case"'":
                            this.triggerAsync("warning", {
                                code: "W114",
                                line: this.line,
                                character: this.char,
                                data: ["\\'"]
                            }, e, function () {
                                return o.jsonMode
                            });
                            break;
                        case"b":
                            r = "\\b";
                            break;
                        case"f":
                            r = "\\f";
                            break;
                        case"n":
                            r = "\\n";
                            break;
                        case"r":
                            r = "\\r";
                            break;
                        case"t":
                            r = "\\t";
                            break;
                        case"0":
                            r = "\\0";
                            var i = parseInt(this.peek(1), 10);
                            this.triggerAsync("warning", {
                                code: "W115",
                                line: this.line,
                                character: this.char
                            }, e, function () {
                                return i >= 0 && i <= 7 && o.isStrict()
                            });
                            break;
                        case"u":
                            var s = this.input.substr(1, 4), u = parseInt(s, 16);
                            isNaN(u) && this.trigger("warning", {
                                code: "W052",
                                line: this.line,
                                character: this.char,
                                data: ["u" + s]
                            }), r = String.fromCharCode(u), n = 5;
                            break;
                        case"v":
                            this.triggerAsync("warning", {
                                code: "W114",
                                line: this.line,
                                character: this.char,
                                data: ["\\v"]
                            }, e, function () {
                                return o.jsonMode
                            }), r = "";
                            break;
                        case"x":
                            var a = parseInt(this.input.substr(1, 2), 16);
                            this.triggerAsync("warning", {
                                code: "W114",
                                line: this.line,
                                character: this.char,
                                data: ["\\x-"]
                            }, e, function () {
                                return o.jsonMode
                            }), r = String.fromCharCode(a), n = 3;
                            break;
                        case"\\":
                            r = "\\\\";
                            break;
                        case'"':
                            r = '\\"';
                            break;
                        case"/":
                            break;
                        case"":
                            t = !0, r = ""
                    }
                    return {"char": r, jump: n, allowNewLine: t}
                }, scanTemplateLiteral: function (e) {
                    var t, n = "", r, i = this.line, s = this.char, u = this.templateStarts.length;
                    if (!o.option.esnext)return null;
                    if (this.peek() === "`")t = l.TemplateHead, this.templateStarts.push({
                        line: this.line,
                        "char": this.char
                    }), u = this.templateStarts.length, this.skip(1), this.pushContext(c.Template); else {
                        if (!this.inContext(c.Template) || this.peek() !== "}")return null;
                        t = l.TemplateMiddle
                    }
                    while (this.peek() !== "`") {
                        while ((r = this.peek()) === "") {
                            n += "\n";
                            if (!this.nextLine()) {
                                var a = this.templateStarts.pop();
                                return this.trigger("error", {code: "E052", line: a.line, character: a.char}), {
                                    type: t,
                                    value: n,
                                    startLine: i,
                                    startChar: s,
                                    isUnclosed: !0,
                                    depth: u,
                                    context: this.popContext()
                                }
                            }
                        }
                        if (r === "$" && this.peek(1) === "{")return n += "${", this.skip(2), {
                            type: t,
                            value: n,
                            startLine: i,
                            startChar: s,
                            isUnclosed: !1,
                            depth: u,
                            context: this.currentContext()
                        };
                        if (r === "\\") {
                            var f = this.scanEscapeSequence(e);
                            n += f.char, this.skip(f.jump)
                        } else r !== "`" && (n += r, this.skip(1))
                    }
                    return t = t === l.TemplateHead ? l.NoSubstTemplate : l.TemplateTail, this.skip(1), this.templateStarts.pop(), {
                        type: t,
                        value: n,
                        startLine: i,
                        startChar: s,
                        isUnclosed: !1,
                        depth: u,
                        context: this.popContext()
                    }
                }, scanStringLiteral: function (e) {
                    var t = this.peek();
                    if (t !== '"' && t !== "'")return null;
                    this.triggerAsync("warning", {code: "W108", line: this.line, character: this.char}, e, function () {
                        return o.jsonMode && t !== '"'
                    });
                    var n = "", r = this.line, i = this.char, s = !1;
                    this.skip();
                    while (this.peek() !== t)if (this.peek() === "") {
                        s ? (s = !1, this.triggerAsync("warning", {
                            code: "W043",
                            line: this.line,
                            character: this.char
                        }, e, function () {
                            return !o.option.multistr
                        }), this.triggerAsync("warning", {
                            code: "W042",
                            line: this.line,
                            character: this.char
                        }, e, function () {
                            return o.jsonMode && o.option.multistr
                        })) : this.trigger("warning", {code: "W112", line: this.line, character: this.char});
                        if (!this.nextLine())return this.trigger("error", {
                            code: "E029",
                            line: r,
                            character: i
                        }), {type: l.StringLiteral, value: n, startLine: r, startChar: i, isUnclosed: !0, quote: t}
                    } else {
                        s = !1;
                        var u = this.peek(), a = 1;
                        u < " " && this.trigger("warning", {
                            code: "W113",
                            line: this.line,
                            character: this.char,
                            data: ["<non-printable>"]
                        });
                        if (u === "\\") {
                            var f = this.scanEscapeSequence(e);
                            u = f.char, a = f.jump, s = f.allowNewLine
                        }
                        n += u, this.skip(a)
                    }
                    return this.skip(), {
                        type: l.StringLiteral,
                        value: n,
                        startLine: r,
                        startChar: i,
                        isUnclosed: !1,
                        quote: t
                    }
                }, scanRegExp: function () {
                    var e = 0, t = this.input.length, n = this.peek(), r = n, i = "", s = [], o = !1, u = !1, a, f = function () {
                        n < " " && (o = !0, this.trigger("warning", {
                            code: "W048",
                            line: this.line,
                            character: this.char
                        })), n === "<" && (o = !0, this.trigger("warning", {
                            code: "W049",
                            line: this.line,
                            character: this.char,
                            data: [n]
                        }))
                    }.bind(this);
                    if (!this.prereg || n !== "/")return null;
                    e += 1, a = !1;
                    while (e < t) {
                        n = this.peek(e), r += n, i += n;
                        if (u) {
                            n === "]" && (this.peek(e - 1) !== "\\" || this.peek(e - 2) === "\\") && (u = !1), n === "\\" && (e += 1, n = this.peek(e), i += n, r += n, f()), e += 1;
                            continue
                        }
                        if (n === "\\") {
                            e += 1, n = this.peek(e), i += n, r += n, f();
                            if (n === "/") {
                                e += 1;
                                continue
                            }
                            if (n === "[") {
                                e += 1;
                                continue
                            }
                        }
                        if (n === "[") {
                            u = !0, e += 1;
                            continue
                        }
                        if (n === "/") {
                            i = i.substr(0, i.length - 1), a = !0, e += 1;
                            break
                        }
                        e += 1
                    }
                    if (!a)return this.trigger("error", {
                        code: "E015",
                        line: this.line,
                        character: this.from
                    }), void this.trigger("fatal", {line: this.line, from: this.from});
                    while (e < t) {
                        n = this.peek(e);
                        if (!/[gim]/.test(n))break;
                        s.push(n), r += n, e += 1
                    }
                    try {
                        new RegExp(i, s.join(""))
                    } catch (c) {
                        o = !0, this.trigger("error", {
                            code: "E016",
                            line: this.line,
                            character: this.char,
                            data: [c.message]
                        })
                    }
                    return {type: l.RegExp, value: r, flags: s, isMalformed: o}
                }, scanNonBreakingSpaces: function () {
                    return o.option.nonbsp ? this.input.search(/(\u00A0)/) : -1
                }, scanUnsafeChars: function () {
                    return this.input.search(s.unsafeChars)
                }, next: function (e) {
                    this.from = this.char;
                    var t;
                    if (/\s/.test(this.peek())) {
                        t = this.char;
                        while (/\s/.test(this.peek()))this.from += 1, this.skip()
                    }
                    var n = this.scanComments() || this.scanStringLiteral(e) || this.scanTemplateLiteral(e);
                    return n ? n : (n = this.scanRegExp() || this.scanPunctuator() || this.scanKeyword() || this.scanIdentifier() || this.scanNumericLiteral(), n ? (this.skip(n.tokenLength || n.value.length), n) : null)
                }, nextLine: function () {
                    var e;
                    if (this.line >= this.getLines().length)return !1;
                    this.input = this.getLines()[this.line], this.line += 1, this.char = 1, this.from = 1;
                    var t = this.input.trim(), n = function () {
                        return r.some(arguments, function (e) {
                            return t.indexOf(e) === 0
                        })
                    }, i = function () {
                        return r.some(arguments, function (e) {
                            return t.indexOf(e, t.length - e.length) !== -1
                        })
                    };
                    o.ignoreLinterErrors === !0 && !n("/*", "//") && (!this.inComment || !i("*/")) && (this.input = ""), e = this.scanNonBreakingSpaces(), e >= 0 && this.trigger("warning", {
                        code: "W125",
                        line: this.line,
                        character: e + 1
                    }), this.input = this.input.replace(/\t/g, o.tab), e = this.scanUnsafeChars(), e >= 0 && this.trigger("warning", {
                        code: "W100",
                        line: this.line,
                        character: e
                    });
                    if (o.option.maxlen && o.option.maxlen < this.input.length) {
                        var u = this.inComment || n.call(t, "//") || n.call(t, "/*"), a = !u || !s.maxlenException.test(t);
                        a && this.trigger("warning", {code: "W101", line: this.line, character: this.input.length})
                    }
                    return !0
                }, start: function () {
                    this.nextLine()
                }, token: function () {
                    function n(e, t) {
                        if (!e.reserved)return !1;
                        var n = e.meta;
                        if (n && n.isFutureReservedWord && o.inES5()) {
                            if (!n.es5)return !1;
                            if (n.strictOnly && !o.option.strict && !o.isStrict())return !1;
                            if (t)return !1
                        }
                        return !0
                    }

                    var e = h(), t, i = function (t, i, s, u) {
                        var a;
                        t !== "(endline)" && t !== "(end)" && (this.prereg = !1);
                        if (t === "(punctuator)") {
                            switch (i) {
                                case".":
                                case")":
                                case"~":
                                case"#":
                                case"]":
                                case"++":
                                case"--":
                                    this.prereg = !1;
                                    break;
                                default:
                                    this.prereg = !0
                            }
                            a = Object.create(o.syntax[i] || o.syntax["(error)"])
                        }
                        if (t === "(identifier)") {
                            if (i === "return" || i === "case" || i === "typeof")this.prereg = !0;
                            r.has(o.syntax, i) && (a = Object.create(o.syntax[i] || o.syntax["(error)"]), n(a, s && t === "(identifier)") || (a = null))
                        }
                        return a || (a = Object.create(o.syntax[t])), a.identifier = t === "(identifier)", a.type = a.type || t, a.value = i, a.line = this.line, a.character = this.char, a.from = this.from, a.identifier && u && (a.raw_text = u.text || u.value), u && u.startLine && u.startLine !== this.line && (a.startLine = u.startLine), u && u.context && (a.context = u.context), u && u.depth && (a.depth = u.depth), u && u.isUnclosed && (a.isUnclosed = u.isUnclosed), s && a.identifier && (a.isProperty = s), a.check = e.check, a
                    }.bind(this);
                    for (; ;) {
                        if (!this.input.length)return this.nextLine() ? i("(endline)", "") : this.exhausted ? null : (this.exhausted = !0, i("(end)", ""));
                        t = this.next(e);
                        if (!t) {
                            this.input.length && (this.trigger("error", {
                                code: "E024",
                                line: this.line,
                                character: this.char,
                                data: [this.peek()]
                            }), this.input = "");
                            continue
                        }
                        switch (t.type) {
                            case l.StringLiteral:
                                return this.triggerAsync("String", {
                                    line: this.line,
                                    "char": this.char,
                                    from: this.from,
                                    startLine: t.startLine,
                                    startChar: t.startChar,
                                    value: t.value,
                                    quote: t.quote
                                }, e, function () {
                                    return !0
                                }), i("(string)", t.value, null, t);
                            case l.TemplateHead:
                                return this.trigger("TemplateHead", {
                                    line: this.line,
                                    "char": this.char,
                                    from: this.from,
                                    startLine: t.startLine,
                                    startChar: t.startChar,
                                    value: t.value
                                }), i("(template)", t.value, null, t);
                            case l.TemplateMiddle:
                                return this.trigger("TemplateMiddle", {
                                    line: this.line,
                                    "char": this.char,
                                    from: this.from,
                                    startLine: t.startLine,
                                    startChar: t.startChar,
                                    value: t.value
                                }), i("(template middle)", t.value, null, t);
                            case l.TemplateTail:
                                return this.trigger("TemplateTail", {
                                    line: this.line,
                                    "char": this.char,
                                    from: this.from,
                                    startLine: t.startLine,
                                    startChar: t.startChar,
                                    value: t.value
                                }), i("(template tail)", t.value, null, t);
                            case l.NoSubstTemplate:
                                return this.trigger("NoSubstTemplate", {
                                    line: this.line,
                                    "char": this.char,
                                    from: this.from,
                                    startLine: t.startLine,
                                    startChar: t.startChar,
                                    value: t.value
                                }), i("(no subst template)", t.value, null, t);
                            case l.Identifier:
                                this.trigger("Identifier", {
                                    line: this.line,
                                    "char": this.char,
                                    from: this.form,
                                    name: t.value,
                                    raw_name: t.text,
                                    isProperty: o.tokens.curr.id === "."
                                });
                            case l.Keyword:
                            case l.NullLiteral:
                            case l.BooleanLiteral:
                                return i("(identifier)", t.value, o.tokens.curr.id === ".", t);
                            case l.NumericLiteral:
                                return t.isMalformed && this.trigger("warning", {
                                    code: "W045",
                                    line: this.line,
                                    character: this.char,
                                    data: [t.value]
                                }), this.triggerAsync("warning", {
                                    code: "W114",
                                    line: this.line,
                                    character: this.char,
                                    data: ["0x-"]
                                }, e, function () {
                                    return t.base === 16 && o.jsonMode
                                }), this.triggerAsync("warning", {
                                    code: "W115",
                                    line: this.line,
                                    character: this.char
                                }, e, function () {
                                    return o.isStrict() && t.base === 8 && t.isLegacy
                                }), this.trigger("Number", {
                                    line: this.line,
                                    "char": this.char,
                                    from: this.from,
                                    value: t.value,
                                    base: t.base,
                                    isMalformed: t.malformed
                                }), i("(number)", t.value);
                            case l.RegExp:
                                return i("(regexp)", t.value);
                            case l.Comment:
                                o.tokens.curr.comment = !0;
                                if (t.isSpecial)return {
                                    id: "(comment)",
                                    value: t.value,
                                    body: t.body,
                                    type: t.commentType,
                                    isSpecial: t.isSpecial,
                                    line: this.line,
                                    character: this.char,
                                    from: this.from
                                };
                                break;
                            case"":
                                break;
                            default:
                                return i("(punctuator)", t.value)
                        }
                    }
                }
            }, n.Lexer = p, n.Context = c
        }, {
            "../data/ascii-identifier-data.js": "/node_modules/jshint/data/ascii-identifier-data.js",
            "./reg.js": "/node_modules/jshint/src/reg.js",
            "./state.js": "/node_modules/jshint/src/state.js",
            events: "/node_modules/browserify/node_modules/events/events.js",
            underscore: "/node_modules/jshint/node_modules/underscore/underscore.js"
        }],
        "/node_modules/jshint/src/messages.js": [function (e, t, n) {
            "use strict";
            var r = e("underscore"), i = {
                E001: "Bad option: '{a}'.",
                E002: "Bad option value.",
                E003: "Expected a JSON value.",
                E004: "Input is neither a string nor an array of strings.",
                E005: "Input is empty.",
                E006: "Unexpected early end of program.",
                E007: 'Missing "use strict" statement.',
                E008: "Strict violation.",
                E009: "Option 'validthis' can't be used in a global scope.",
                E010: "'with' is not allowed in strict mode.",
                E011: "const '{a}' has already been declared.",
                E012: "const '{a}' is initialized to 'undefined'.",
                E013: "Attempting to override '{a}' which is a constant.",
                E014: "A regular expression literal can be confused with '/='.",
                E015: "Unclosed regular expression.",
                E016: "Invalid regular expression.",
                E017: "Unclosed comment.",
                E018: "Unbegun comment.",
                E019: "Unmatched '{a}'.",
                E020: "Expected '{a}' to match '{b}' from line {c} and instead saw '{d}'.",
                E021: "Expected '{a}' and instead saw '{b}'.",
                E022: "Line breaking error '{a}'.",
                E023: "Missing '{a}'.",
                E024: "Unexpected '{a}'.",
                E025: "Missing ':' on a case clause.",
                E026: "Missing '}' to match '{' from line {a}.",
                E027: "Missing ']' to match '[' from line {a}.",
                E028: "Illegal comma.",
                E029: "Unclosed string.",
                E030: "Expected an identifier and instead saw '{a}'.",
                E031: "Bad assignment.",
                E032: "Expected a small integer or 'false' and instead saw '{a}'.",
                E033: "Expected an operator and instead saw '{a}'.",
                E034: "get/set are ES5 features.",
                E035: "Missing property name.",
                E036: "Expected to see a statement and instead saw a block.",
                E037: null,
                E038: null,
                E039: "Function declarations are not invocable. Wrap the whole function invocation in parens.",
                E040: "Each value should have its own case label.",
                E041: "Unrecoverable syntax error.",
                E042: "Stopping.",
                E043: "Too many errors.",
                E044: null,
                E045: "Invalid for each loop.",
                E046: "A yield statement shall be within a generator function (with syntax: `function*`)",
                E047: null,
                E048: "{a} declaration not directly within block.",
                E049: "A {a} cannot be named '{b}'.",
                E050: "Mozilla requires the yield expression to be parenthesized here.",
                E051: "Regular parameters cannot come after default parameters.",
                E052: "Unclosed template literal.",
                E053: "Export declaration must be in global scope.",
                E054: "Class properties must be methods. Expected '(' but instead saw '{a}'.",
                E055: "The '{a}' option cannot be set after any executable code."
            }, s = {
                W001: "'hasOwnProperty' is a really bad name.",
                W002: "Value of '{a}' may be overwritten in IE 8 and earlier.",
                W003: "'{a}' was used before it was defined.",
                W004: "'{a}' is already defined.",
                W005: "A dot following a number can be confused with a decimal point.",
                W006: "Confusing minuses.",
                W007: "Confusing plusses.",
                W008: "A leading decimal point can be confused with a dot: '{a}'.",
                W009: "The array literal notation [] is preferable.",
                W010: "The object literal notation {} is preferable.",
                W011: null,
                W012: null,
                W013: null,
                W014: "Bad line breaking before '{a}'.",
                W015: null,
                W016: "Unexpected use of '{a}'.",
                W017: "Bad operand.",
                W018: "Confusing use of '{a}'.",
                W019: "Use the isNaN function to compare with NaN.",
                W020: "Read only.",
                W021: "'{a}' is a function.",
                W022: "Do not assign to the exception parameter.",
                W023: "Expected an identifier in an assignment and instead saw a function invocation.",
                W024: "Expected an identifier and instead saw '{a}' (a reserved word).",
                W025: "Missing name in function declaration.",
                W026: "Inner functions should be listed at the top of the outer function.",
                W027: "Unreachable '{a}' after '{b}'.",
                W028: "Label '{a}' on {b} statement.",
                W030: "Expected an assignment or function call and instead saw an expression.",
                W031: "Do not use 'new' for side effects.",
                W032: "Unnecessary semicolon.",
                W033: "Missing semicolon.",
                W034: 'Unnecessary directive "{a}".',
                W035: "Empty block.",
                W036: "Unexpected /*member '{a}'.",
                W037: "'{a}' is a statement label.",
                W038: "'{a}' used out of scope.",
                W039: "'{a}' is not allowed.",
                W040: "Possible strict violation.",
                W041: "Use '{a}' to compare with '{b}'.",
                W042: "Avoid EOL escaping.",
                W043: "Bad escaping of EOL. Use option multistr if needed.",
                W044: "Bad or unnecessary escaping.",
                W045: "Bad number '{a}'.",
                W046: "Don't use extra leading zeros '{a}'.",
                W047: "A trailing decimal point can be confused with a dot: '{a}'.",
                W048: "Unexpected control character in regular expression.",
                W049: "Unexpected escaped character '{a}' in regular expression.",
                W050: "JavaScript URL.",
                W051: "Variables should not be deleted.",
                W052: "Unexpected '{a}'.",
                W053: "Do not use {a} as a constructor.",
                W054: "The Function constructor is a form of eval.",
                W055: "A constructor name should start with an uppercase letter.",
                W056: "Bad constructor.",
                W057: "Weird construction. Is 'new' necessary?",
                W058: "Missing '()' invoking a constructor.",
                W059: "Avoid arguments.{a}.",
                W060: "document.write can be a form of eval.",
                W061: "eval can be harmful.",
                W062: "Wrap an immediate function invocation in parens to assist the reader in understanding that the expression is the result of a function, and not the function itself.",
                W063: "Math is not a function.",
                W064: "Missing 'new' prefix when invoking a constructor.",
                W065: "Missing radix parameter.",
                W066: "Implied eval. Consider passing a function instead of a string.",
                W067: "Bad invocation.",
                W068: "Wrapping non-IIFE function literals in parens is unnecessary.",
                W069: "['{a}'] is better written in dot notation.",
                W070: "Extra comma. (it breaks older versions of IE)",
                W071: "This function has too many statements. ({a})",
                W072: "This function has too many parameters. ({a})",
                W073: "Blocks are nested too deeply. ({a})",
                W074: "This function's cyclomatic complexity is too high. ({a})",
                W075: "Duplicate {a} '{b}'.",
                W076: "Unexpected parameter '{a}' in get {b} function.",
                W077: "Expected a single parameter in set {a} function.",
                W078: "Setter is defined without getter.",
                W079: "Redefinition of '{a}'.",
                W080: "It's not necessary to initialize '{a}' to 'undefined'.",
                W081: null,
                W082: "Function declarations should not be placed in blocks. Use a function expression or move the statement to the top of the outer function.",
                W083: "Don't make functions within a loop.",
                W084: "Assignment in conditional expression",
                W085: "Don't use 'with'.",
                W086: "Expected a 'break' statement before '{a}'.",
                W087: "Forgotten 'debugger' statement?",
                W088: "Creating global 'for' variable. Should be 'for (var {a} ...'.",
                W089: "The body of a for in should be wrapped in an if statement to filter unwanted properties from the prototype.",
                W090: "'{a}' is not a statement label.",
                W091: "'{a}' is out of scope.",
                W093: "Did you mean to return a conditional instead of an assignment?",
                W094: "Unexpected comma.",
                W095: "Expected a string and instead saw {a}.",
                W096: "The '{a}' key may produce unexpected results.",
                W097: 'Use the function form of "use strict".',
                W098: "'{a}' is defined but never used.",
                W099: null,
                W100: "This character may get silently deleted by one or more browsers.",
                W101: "Line is too long.",
                W102: null,
                W103: "The '{a}' property is deprecated.",
                W104: "'{a}' is available in ES6 (use esnext option) or Mozilla JS extensions (use moz).",
                W105: "Unexpected {a} in '{b}'.",
                W106: "Identifier '{a}' is not in camel case.",
                W107: "Script URL.",
                W108: "Strings must use doublequote.",
                W109: "Strings must use singlequote.",
                W110: "Mixed double and single quotes.",
                W112: "Unclosed string.",
                W113: "Control character in string: {a}.",
                W114: "Avoid {a}.",
                W115: "Octal literals are not allowed in strict mode.",
                W116: "Expected '{a}' and instead saw '{b}'.",
                W117: "'{a}' is not defined.",
                W118: "'{a}' is only available in Mozilla JavaScript extensions (use moz option).",
                W119: "'{a}' is only available in ES6 (use esnext option).",
                W120: "You might be leaking a variable ({a}) here.",
                W121: "Extending prototype of native object: '{a}'.",
                W122: "Invalid typeof value '{a}'",
                W123: "'{a}' is already defined in outer scope.",
                W124: "A generator function shall contain a yield statement.",
                W125: "This line contains non-breaking spaces: http://jshint.com/doc/options/#nonbsp",
                W126: "Unnecessary grouping operator.",
                W127: "Unexpected use of a comma operator.",
                W128: "Empty array elements require elision=true.",
                W129: "'{a}' is defined in a future version of JavaScript. Use a different variable name to avoid migration issues.",
                W130: "Invalid element after rest element.",
                W131: "Invalid parameter after rest parameter.",
                W132: "`var` declarations are forbidden. Use `let` or `const` instead.",
                W133: "Invalid for-{a} loop left-hand-side: {b}.",
                W134: "The '{a}' option is only available when linting ECMAScript {b} code."
            }, o = {
                I001: "Comma warnings can be turned off with 'laxcomma'.",
                I002: null,
                I003: "ES5 option is now set per default"
            };
            n.errors = {}, n.warnings = {}, n.info = {}, r.each(i, function (e, t) {
                n.errors[t] = {code: t, desc: e}
            }), r.each(s, function (e, t) {
                n.warnings[t] = {code: t, desc: e}
            }), r.each(o, function (e, t) {
                n.info[t] = {code: t, desc: e}
            })
        }, {underscore: "/node_modules/jshint/node_modules/underscore/underscore.js"}],
        "/node_modules/jshint/src/name-stack.js": [function (e, t, n) {
            "use strict";
            function r() {
                this._stack = []
            }

            Object.defineProperty(r.prototype, "length", {
                get: function () {
                    return this._stack.length
                }
            }), r.prototype.push = function () {
                this._stack.push(null)
            }, r.prototype.pop = function () {
                this._stack.pop()
            }, r.prototype.set = function (e) {
                this._stack[this.length - 1] = e
            }, r.prototype.infer = function () {
                var e = this._stack[this.length - 1], t = "", n;
                if (!e || e.type === "class")e = this._stack[this.length - 2];
                return e ? (n = e.type, n !== "(string)" && n !== "(number)" && n !== "(identifier)" && n !== "default" ? "(expression)" : (e.accessorType && (t = e.accessorType + " "), t + e.value)) : "(empty)"
            }, t.exports = r
        }, {}],
        "/node_modules/jshint/src/options.js": [function (e, t, n) {
            "use strict";
            n.bool = {
                enforcing: {
                    bitwise: !0,
                    freeze: !0,
                    camelcase: !0,
                    curly: !0,
                    eqeqeq: !0,
                    futurehostile: !0,
                    notypeof: !0,
                    es3: !0,
                    es5: !0,
                    forin: !0,
                    funcscope: !0,
                    immed: !0,
                    iterator: !0,
                    newcap: !0,
                    noarg: !0,
                    nocomma: !0,
                    noempty: !0,
                    nonbsp: !0,
                    nonew: !0,
                    undef: !0,
                    singleGroups: !1,
                    strict: !0,
                    varstmt: !1,
                    enforceall: !1
                },
                relaxing: {
                    asi: !0,
                    multistr: !0,
                    debug: !0,
                    boss: !0,
                    evil: !0,
                    globalstrict: !0,
                    plusplus: !0,
                    proto: !0,
                    scripturl: !0,
                    sub: !0,
                    supernew: !0,
                    laxbreak: !0,
                    laxcomma: !0,
                    validthis: !0,
                    withstmt: !0,
                    moz: !0,
                    noyield: !0,
                    eqnull: !0,
                    lastsemic: !0,
                    loopfunc: !0,
                    expr: !0,
                    esnext: !0,
                    elision: !0
                },
                environments: {
                    mootools: !0,
                    couch: !0,
                    jasmine: !0,
                    jquery: !0,
                    node: !0,
                    qunit: !0,
                    rhino: !0,
                    shelljs: !0,
                    prototypejs: !0,
                    yui: !0,
                    mocha: !0,
                    module: !0,
                    wsh: !0,
                    worker: !0,
                    nonstandard: !0,
                    browser: !0,
                    browserify: !0,
                    devel: !0,
                    dojo: !0,
                    typed: !0,
                    phantom: !0
                },
                obsolete: {onecase: !0, regexp: !0, regexdash: !0}
            }, n.val = {
                maxlen: !1,
                indent: !1,
                maxerr: !1,
                predef: !1,
                globals: !1,
                quotmark: !1,
                scope: !1,
                maxstatements: !1,
                maxdepth: !1,
                maxparams: !1,
                maxcomplexity: !1,
                shadow: !1,
                unused: !0,
                latedef: !1,
                ignore: !1,
                ignoreDelimiters: !1
            }, n.inverted = {
                bitwise: !0,
                forin: !0,
                newcap: !0,
                plusplus: !0,
                regexp: !0,
                undef: !0,
                eqeqeq: !0,
                strict: !0
            }, n.validNames = Object.keys(n.val).concat(Object.keys(n.bool.relaxing)).concat(Object.keys(n.bool.enforcing)).concat(Object.keys(n.bool.obsolete)).concat(Object.keys(n.bool.environments)), n.renamed = {
                eqeq: "eqeqeq",
                windows: "wsh",
                sloppy: "strict"
            }, n.removed = {
                nomen: !0,
                onevar: !0,
                passfail: !0,
                white: !0,
                gcl: !0,
                smarttabs: !0,
                trailing: !0
            }, n.noenforceall = {varstmt: !0, strict: !0}
        }, {}],
        "/node_modules/jshint/src/reg.js": [function (e, t, n) {
            "use strict";
            n.unsafeString = /@cc|<\/?|script|\]\s*\]|<\s*!|&lt/i, n.unsafeChars = /[\u0000-\u001f\u007f-\u009f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/, n.needEsc = /[\u0000-\u001f&<"\/\\\u007f-\u009f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/, n.needEscGlobal = /[\u0000-\u001f&<"\/\\\u007f-\u009f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g, n.starSlash = /\*\//, n.identifier = /^([a-zA-Z_$][a-zA-Z0-9_$]*)$/, n.javascriptURL = /^(?:javascript|jscript|ecmascript|vbscript|livescript)\s*:/i, n.fallsThrough = /^\s*\/\*\s*falls?\sthrough\s*\*\/\s*$/, n.maxlenException = /^(?:(?:\/\/|\/\*|\*) ?)?[^ ]+$/
        }, {}],
        "/node_modules/jshint/src/state.js": [function (e, t, n) {
            "use strict";
            var r = e("./name-stack.js"), i = {
                syntax: {}, isStrict: function () {
                    return this.directive["use strict"] || this.inClassBody || this.option.module
                }, inMoz: function () {
                    return this.option.moz
                }, inESNext: function () {
                    return this.option.moz || this.option.esnext
                }, inES5: function () {
                    return !this.option.es3
                }, inES3: function () {
                    return this.option.es3
                }, reset: function () {
                    this.tokens = {
                        prev: null,
                        next: null,
                        curr: null
                    }, this.option = {}, this.funct = null, this.ignored = {}, this.directive = {}, this.jsonMode = !1, this.jsonWarnings = [], this.lines = [], this.tab = "", this.cache = {}, this.ignoredLines = {}, this.forinifcheckneeded = !1, this.nameStack = new r, this.inClassBody = !1, this.ignoreLinterErrors = !1
                }
            };
            n.state = i
        }, {"./name-stack.js": "/node_modules/jshint/src/name-stack.js"}],
        "/node_modules/jshint/src/style.js": [function (e, t, n) {
            "use strict";
            n.register = function (e) {
                e.on("Identifier", function (n) {
                    if (e.getOption("proto"))return;
                    n.name === "__proto__" && e.warn("W103", {line: n.line, "char": n.char, data: [n.name]})
                }), e.on("Identifier", function (n) {
                    if (e.getOption("iterator"))return;
                    n.name === "__iterator__" && e.warn("W104", {line: n.line, "char": n.char, data: [n.name]})
                }), e.on("Identifier", function (n) {
                    if (!e.getOption("camelcase"))return;
                    n.name.replace(/^_+|_+$/g, "").indexOf("_") > -1 && !n.name.match(/^[A-Z0-9_]*$/) && e.warn("W106", {
                        line: n.line,
                        "char": n.from,
                        data: [n.name]
                    })
                }), e.on("String", function (n) {
                    var r = e.getOption("quotmark"), i;
                    if (!r)return;
                    r === "single" && n.quote !== "'" && (i = "W109"), r === "double" && n.quote !== '"' && (i = "W108"), r === !0 && (e.getCache("quotmark") || e.setCache("quotmark", n.quote), e.getCache("quotmark") !== n.quote && (i = "W110")), i && e.warn(i, {
                        line: n.line,
                        "char": n.char
                    })
                }), e.on("Number", function (n) {
                    n.value.charAt(0) === "." && e.warn("W008", {
                        line: n.line,
                        "char": n.char,
                        data: [n.value]
                    }), n.value.substr(n.value.length - 1) === "." && e.warn("W047", {
                        line: n.line,
                        "char": n.char,
                        data: [n.value]
                    }), /^00+/.test(n.value) && e.warn("W046", {line: n.line, "char": n.char, data: [n.value]})
                }), e.on("String", function (n) {
                    var r = /^(?:javascript|jscript|ecmascript|vbscript|livescript)\s*:/i;
                    if (e.getOption("scripturl"))return;
                    r.test(n.value) && e.warn("W107", {line: n.line, "char": n.char})
                })
            }
        }, {}],
        "/node_modules/jshint/src/vars.js": [function (e, t, n) {
            "use strict";
            n.reservedVars = {arguments: !1, NaN: !1}, n.ecmaIdentifiers = {
                3: {
                    Array: !1,
                    Boolean: !1,
                    Date: !1,
                    decodeURI: !1,
                    decodeURIComponent: !1,
                    encodeURI: !1,
                    encodeURIComponent: !1,
                    Error: !1,
                    eval: !1,
                    EvalError: !1,
                    Function: !1,
                    hasOwnProperty: !1,
                    isFinite: !1,
                    isNaN: !1,
                    Math: !1,
                    Number: !1,
                    Object: !1,
                    parseInt: !1,
                    parseFloat: !1,
                    RangeError: !1,
                    ReferenceError: !1,
                    RegExp: !1,
                    String: !1,
                    SyntaxError: !1,
                    TypeError: !1,
                    URIError: !1
                },
                5: {JSON: !1},
                6: {Map: !1, Promise: !1, Proxy: !1, Reflect: !1, Set: !1, Symbol: !1, WeakMap: !1, WeakSet: !1}
            }, n.browser = {
                Audio: !1,
                Blob: !1,
                addEventListener: !1,
                applicationCache: !1,
                atob: !1,
                blur: !1,
                btoa: !1,
                cancelAnimationFrame: !1,
                CanvasGradient: !1,
                CanvasPattern: !1,
                CanvasRenderingContext2D: !1,
                CSS: !1,
                clearInterval: !1,
                clearTimeout: !1,
                close: !1,
                closed: !1,
                Comment: !1,
                CustomEvent: !1,
                DOMParser: !1,
                defaultStatus: !1,
                Document: !1,
                document: !1,
                DocumentFragment: !1,
                Element: !1,
                ElementTimeControl: !1,
                Event: !1,
                event: !1,
                fetch: !1,
                FileReader: !1,
                FormData: !1,
                focus: !1,
                frames: !1,
                getComputedStyle: !1,
                HTMLElement: !1,
                HTMLAnchorElement: !1,
                HTMLBaseElement: !1,
                HTMLBlockquoteElement: !1,
                HTMLBodyElement: !1,
                HTMLBRElement: !1,
                HTMLButtonElement: !1,
                HTMLCanvasElement: !1,
                HTMLCollection: !1,
                HTMLDirectoryElement: !1,
                HTMLDivElement: !1,
                HTMLDListElement: !1,
                HTMLFieldSetElement: !1,
                HTMLFontElement: !1,
                HTMLFormElement: !1,
                HTMLFrameElement: !1,
                HTMLFrameSetElement: !1,
                HTMLHeadElement: !1,
                HTMLHeadingElement: !1,
                HTMLHRElement: !1,
                HTMLHtmlElement: !1,
                HTMLIFrameElement: !1,
                HTMLImageElement: !1,
                HTMLInputElement: !1,
                HTMLIsIndexElement: !1,
                HTMLLabelElement: !1,
                HTMLLayerElement: !1,
                HTMLLegendElement: !1,
                HTMLLIElement: !1,
                HTMLLinkElement: !1,
                HTMLMapElement: !1,
                HTMLMenuElement: !1,
                HTMLMetaElement: !1,
                HTMLModElement: !1,
                HTMLObjectElement: !1,
                HTMLOListElement: !1,
                HTMLOptGroupElement: !1,
                HTMLOptionElement: !1,
                HTMLParagraphElement: !1,
                HTMLParamElement: !1,
                HTMLPreElement: !1,
                HTMLQuoteElement: !1,
                HTMLScriptElement: !1,
                HTMLSelectElement: !1,
                HTMLStyleElement: !1,
                HTMLTableCaptionElement: !1,
                HTMLTableCellElement: !1,
                HTMLTableColElement: !1,
                HTMLTableElement: !1,
                HTMLTableRowElement: !1,
                HTMLTableSectionElement: !1,
                HTMLTemplateElement: !1,
                HTMLTextAreaElement: !1,
                HTMLTitleElement: !1,
                HTMLUListElement: !1,
                HTMLVideoElement: !1,
                history: !1,
                Image: !1,
                Intl: !1,
                length: !1,
                localStorage: !1,
                location: !1,
                matchMedia: !1,
                MessageChannel: !1,
                MessageEvent: !1,
                MessagePort: !1,
                MouseEvent: !1,
                moveBy: !1,
                moveTo: !1,
                MutationObserver: !1,
                name: !1,
                Node: !1,
                NodeFilter: !1,
                NodeList: !1,
                Notification: !1,
                navigator: !1,
                onbeforeunload: !0,
                onblur: !0,
                onerror: !0,
                onfocus: !0,
                onload: !0,
                onresize: !0,
                onunload: !0,
                open: !1,
                openDatabase: !1,
                opener: !1,
                Option: !1,
                parent: !1,
                performance: !1,
                print: !1,
                Range: !1,
                requestAnimationFrame: !1,
                removeEventListener: !1,
                resizeBy: !1,
                resizeTo: !1,
                screen: !1,
                scroll: !1,
                scrollBy: !1,
                scrollTo: !1,
                sessionStorage: !1,
                setInterval: !1,
                setTimeout: !1,
                SharedWorker: !1,
                status: !1,
                SVGAElement: !1,
                SVGAltGlyphDefElement: !1,
                SVGAltGlyphElement: !1,
                SVGAltGlyphItemElement: !1,
                SVGAngle: !1,
                SVGAnimateColorElement: !1,
                SVGAnimateElement: !1,
                SVGAnimateMotionElement: !1,
                SVGAnimateTransformElement: !1,
                SVGAnimatedAngle: !1,
                SVGAnimatedBoolean: !1,
                SVGAnimatedEnumeration: !1,
                SVGAnimatedInteger: !1,
                SVGAnimatedLength: !1,
                SVGAnimatedLengthList: !1,
                SVGAnimatedNumber: !1,
                SVGAnimatedNumberList: !1,
                SVGAnimatedPathData: !1,
                SVGAnimatedPoints: !1,
                SVGAnimatedPreserveAspectRatio: !1,
                SVGAnimatedRect: !1,
                SVGAnimatedString: !1,
                SVGAnimatedTransformList: !1,
                SVGAnimationElement: !1,
                SVGCSSRule: !1,
                SVGCircleElement: !1,
                SVGClipPathElement: !1,
                SVGColor: !1,
                SVGColorProfileElement: !1,
                SVGColorProfileRule: !1,
                SVGComponentTransferFunctionElement: !1,
                SVGCursorElement: !1,
                SVGDefsElement: !1,
                SVGDescElement: !1,
                SVGDocument: !1,
                SVGElement: !1,
                SVGElementInstance: !1,
                SVGElementInstanceList: !1,
                SVGEllipseElement: !1,
                SVGExternalResourcesRequired: !1,
                SVGFEBlendElement: !1,
                SVGFEColorMatrixElement: !1,
                SVGFEComponentTransferElement: !1,
                SVGFECompositeElement: !1,
                SVGFEConvolveMatrixElement: !1,
                SVGFEDiffuseLightingElement: !1,
                SVGFEDisplacementMapElement: !1,
                SVGFEDistantLightElement: !1,
                SVGFEFloodElement: !1,
                SVGFEFuncAElement: !1,
                SVGFEFuncBElement: !1,
                SVGFEFuncGElement: !1,
                SVGFEFuncRElement: !1,
                SVGFEGaussianBlurElement: !1,
                SVGFEImageElement: !1,
                SVGFEMergeElement: !1,
                SVGFEMergeNodeElement: !1,
                SVGFEMorphologyElement: !1,
                SVGFEOffsetElement: !1,
                SVGFEPointLightElement: !1,
                SVGFESpecularLightingElement: !1,
                SVGFESpotLightElement: !1,
                SVGFETileElement: !1,
                SVGFETurbulenceElement: !1,
                SVGFilterElement: !1,
                SVGFilterPrimitiveStandardAttributes: !1,
                SVGFitToViewBox: !1,
                SVGFontElement: !1,
                SVGFontFaceElement: !1,
                SVGFontFaceFormatElement: !1,
                SVGFontFaceNameElement: !1,
                SVGFontFaceSrcElement: !1,
                SVGFontFaceUriElement: !1,
                SVGForeignObjectElement: !1,
                SVGGElement: !1,
                SVGGlyphElement: !1,
                SVGGlyphRefElement: !1,
                SVGGradientElement: !1,
                SVGHKernElement: !1,
                SVGICCColor: !1,
                SVGImageElement: !1,
                SVGLangSpace: !1,
                SVGLength: !1,
                SVGLengthList: !1,
                SVGLineElement: !1,
                SVGLinearGradientElement: !1,
                SVGLocatable: !1,
                SVGMPathElement: !1,
                SVGMarkerElement: !1,
                SVGMaskElement: !1,
                SVGMatrix: !1,
                SVGMetadataElement: !1,
                SVGMissingGlyphElement: !1,
                SVGNumber: !1,
                SVGNumberList: !1,
                SVGPaint: !1,
                SVGPathElement: !1,
                SVGPathSeg: !1,
                SVGPathSegArcAbs: !1,
                SVGPathSegArcRel: !1,
                SVGPathSegClosePath: !1,
                SVGPathSegCurvetoCubicAbs: !1,
                SVGPathSegCurvetoCubicRel: !1,
                SVGPathSegCurvetoCubicSmoothAbs: !1,
                SVGPathSegCurvetoCubicSmoothRel: !1,
                SVGPathSegCurvetoQuadraticAbs: !1,
                SVGPathSegCurvetoQuadraticRel: !1,
                SVGPathSegCurvetoQuadraticSmoothAbs: !1,
                SVGPathSegCurvetoQuadraticSmoothRel: !1,
                SVGPathSegLinetoAbs: !1,
                SVGPathSegLinetoHorizontalAbs: !1,
                SVGPathSegLinetoHorizontalRel: !1,
                SVGPathSegLinetoRel: !1,
                SVGPathSegLinetoVerticalAbs: !1,
                SVGPathSegLinetoVerticalRel: !1,
                SVGPathSegList: !1,
                SVGPathSegMovetoAbs: !1,
                SVGPathSegMovetoRel: !1,
                SVGPatternElement: !1,
                SVGPoint: !1,
                SVGPointList: !1,
                SVGPolygonElement: !1,
                SVGPolylineElement: !1,
                SVGPreserveAspectRatio: !1,
                SVGRadialGradientElement: !1,
                SVGRect: !1,
                SVGRectElement: !1,
                SVGRenderingIntent: !1,
                SVGSVGElement: !1,
                SVGScriptElement: !1,
                SVGSetElement: !1,
                SVGStopElement: !1,
                SVGStringList: !1,
                SVGStylable: !1,
                SVGStyleElement: !1,
                SVGSwitchElement: !1,
                SVGSymbolElement: !1,
                SVGTRefElement: !1,
                SVGTSpanElement: !1,
                SVGTests: !1,
                SVGTextContentElement: !1,
                SVGTextElement: !1,
                SVGTextPathElement: !1,
                SVGTextPositioningElement: !1,
                SVGTitleElement: !1,
                SVGTransform: !1,
                SVGTransformList: !1,
                SVGTransformable: !1,
                SVGURIReference: !1,
                SVGUnitTypes: !1,
                SVGUseElement: !1,
                SVGVKernElement: !1,
                SVGViewElement: !1,
                SVGViewSpec: !1,
                SVGZoomAndPan: !1,
                Text: !1,
                TextDecoder: !1,
                TextEncoder: !1,
                TimeEvent: !1,
                top: !1,
                URL: !1,
                WebGLActiveInfo: !1,
                WebGLBuffer: !1,
                WebGLContextEvent: !1,
                WebGLFramebuffer: !1,
                WebGLProgram: !1,
                WebGLRenderbuffer: !1,
                WebGLRenderingContext: !1,
                WebGLShader: !1,
                WebGLShaderPrecisionFormat: !1,
                WebGLTexture: !1,
                WebGLUniformLocation: !1,
                WebSocket: !1,
                window: !1,
                Worker: !1,
                XDomainRequest: !1,
                XMLHttpRequest: !1,
                XMLSerializer: !1,
                XPathEvaluator: !1,
                XPathException: !1,
                XPathExpression: !1,
                XPathNamespace: !1,
                XPathNSResolver: !1,
                XPathResult: !1
            }, n.devel = {
                alert: !1,
                confirm: !1,
                console: !1,
                Debug: !1,
                opera: !1,
                prompt: !1
            }, n.worker = {
                importScripts: !0,
                postMessage: !0,
                self: !0,
                FileReaderSync: !0
            }, n.nonstandard = {escape: !1, unescape: !1}, n.couch = {
                require: !1,
                respond: !1,
                getRow: !1,
                emit: !1,
                send: !1,
                start: !1,
                sum: !1,
                log: !1,
                exports: !1,
                module: !1,
                provides: !1
            }, n.node = {
                __filename: !1,
                __dirname: !1,
                GLOBAL: !1,
                global: !1,
                module: !1,
                require: !1,
                Buffer: !0,
                console: !0,
                exports: !0,
                process: !0,
                setTimeout: !0,
                clearTimeout: !0,
                setInterval: !0,
                clearInterval: !0,
                setImmediate: !0,
                clearImmediate: !0
            }, n.browserify = {
                __filename: !1,
                __dirname: !1,
                global: !1,
                module: !1,
                require: !1,
                Buffer: !0,
                exports: !0,
                process: !0
            }, n.phantom = {phantom: !0, require: !0, WebPage: !0, console: !0, exports: !0}, n.qunit = {
                asyncTest: !1,
                deepEqual: !1,
                equal: !1,
                expect: !1,
                module: !1,
                notDeepEqual: !1,
                notEqual: !1,
                notPropEqual: !1,
                notStrictEqual: !1,
                ok: !1,
                propEqual: !1,
                QUnit: !1,
                raises: !1,
                start: !1,
                stop: !1,
                strictEqual: !1,
                test: !1,
                "throws": !1
            }, n.rhino = {
                defineClass: !1,
                deserialize: !1,
                gc: !1,
                help: !1,
                importClass: !1,
                importPackage: !1,
                java: !1,
                load: !1,
                loadClass: !1,
                Packages: !1,
                print: !1,
                quit: !1,
                readFile: !1,
                readUrl: !1,
                runCommand: !1,
                seal: !1,
                serialize: !1,
                spawn: !1,
                sync: !1,
                toint32: !1,
                version: !1
            }, n.shelljs = {
                target: !1,
                echo: !1,
                exit: !1,
                cd: !1,
                pwd: !1,
                ls: !1,
                find: !1,
                cp: !1,
                rm: !1,
                mv: !1,
                mkdir: !1,
                test: !1,
                cat: !1,
                sed: !1,
                grep: !1,
                which: !1,
                dirs: !1,
                pushd: !1,
                popd: !1,
                env: !1,
                exec: !1,
                chmod: !1,
                config: !1,
                error: !1,
                tempdir: !1
            }, n.typed = {
                ArrayBuffer: !1,
                ArrayBufferView: !1,
                DataView: !1,
                Float32Array: !1,
                Float64Array: !1,
                Int16Array: !1,
                Int32Array: !1,
                Int8Array: !1,
                Uint16Array: !1,
                Uint32Array: !1,
                Uint8Array: !1,
                Uint8ClampedArray: !1
            }, n.wsh = {
                ActiveXObject: !0,
                Enumerator: !0,
                GetObject: !0,
                ScriptEngine: !0,
                ScriptEngineBuildVersion: !0,
                ScriptEngineMajorVersion: !0,
                ScriptEngineMinorVersion: !0,
                VBArray: !0,
                WSH: !0,
                WScript: !0,
                XDomainRequest: !0
            }, n.dojo = {dojo: !1, dijit: !1, dojox: !1, define: !1, require: !1}, n.jquery = {
                $: !1,
                jQuery: !1
            }, n.mootools = {
                $: !1,
                $$: !1,
                Asset: !1,
                Browser: !1,
                Chain: !1,
                Class: !1,
                Color: !1,
                Cookie: !1,
                Core: !1,
                Document: !1,
                DomReady: !1,
                DOMEvent: !1,
                DOMReady: !1,
                Drag: !1,
                Element: !1,
                Elements: !1,
                Event: !1,
                Events: !1,
                Fx: !1,
                Group: !1,
                Hash: !1,
                HtmlTable: !1,
                IFrame: !1,
                IframeShim: !1,
                InputValidator: !1,
                instanceOf: !1,
                Keyboard: !1,
                Locale: !1,
                Mask: !1,
                MooTools: !1,
                Native: !1,
                Options: !1,
                OverText: !1,
                Request: !1,
                Scroller: !1,
                Slick: !1,
                Slider: !1,
                Sortables: !1,
                Spinner: !1,
                Swiff: !1,
                Tips: !1,
                Type: !1,
                typeOf: !1,
                URI: !1,
                Window: !1
            }, n.prototypejs = {
                $: !1,
                $$: !1,
                $A: !1,
                $F: !1,
                $H: !1,
                $R: !1,
                $break: !1,
                $continue: !1,
                $w: !1,
                Abstract: !1,
                Ajax: !1,
                Class: !1,
                Enumerable: !1,
                Element: !1,
                Event: !1,
                Field: !1,
                Form: !1,
                Hash: !1,
                Insertion: !1,
                ObjectRange: !1,
                PeriodicalExecuter: !1,
                Position: !1,
                Prototype: !1,
                Selector: !1,
                Template: !1,
                Toggle: !1,
                Try: !1,
                Autocompleter: !1,
                Builder: !1,
                Control: !1,
                Draggable: !1,
                Draggables: !1,
                Droppables: !1,
                Effect: !1,
                Sortable: !1,
                SortableObserver: !1,
                Sound: !1,
                Scriptaculous: !1
            }, n.yui = {YUI: !1, Y: !1, YUI_config: !1}, n.mocha = {
                mocha: !1,
                describe: !1,
                xdescribe: !1,
                it: !1,
                xit: !1,
                context: !1,
                xcontext: !1,
                before: !1,
                after: !1,
                beforeEach: !1,
                afterEach: !1,
                suite: !1,
                test: !1,
                setup: !1,
                teardown: !1,
                suiteSetup: !1,
                suiteTeardown: !1
            }, n.jasmine = {
                jasmine: !1,
                describe: !1,
                xdescribe: !1,
                it: !1,
                xit: !1,
                beforeEach: !1,
                afterEach: !1,
                setFixtures: !1,
                loadFixtures: !1,
                spyOn: !1,
                expect: !1,
                runs: !1,
                waitsFor: !1,
                waits: !1,
                beforeAll: !1,
                afterAll: !1,
                fail: !1,
                fdescribe: !1,
                fit: !1
            }
        }, {}]
    }, {}, ["/node_modules/jshint/src/jshint.js"])
}), ace.define("ace/mode/javascript_worker", ["require", "exports", "module", "ace/lib/oop", "ace/worker/mirror", "ace/mode/javascript/jshint"], function (require, exports, module) {
    "use strict";
    function startRegex(e) {
        return RegExp("^(" + e.join("|") + ")")
    }

    var oop = require("../lib/oop"), Mirror = require("../worker/mirror").Mirror, lint = require("./javascript/jshint").JSHINT, disabledWarningsRe = startRegex(["Bad for in variable '(.+)'.", 'Missing "use strict"']), errorsRe = startRegex(["Unexpected", "Expected ", "Confusing (plus|minus)", "\\{a\\} unterminated regular expression", "Unclosed ", "Unmatched ", "Unbegun comment", "Bad invocation", "Missing space after", "Missing operator at"]), infoRe = startRegex(["Expected an assignment", "Bad escapement of EOL", "Unexpected comma", "Unexpected space", "Missing radix parameter.", "A leading decimal point can", "\\['{a}'\\] is better written in dot notation.", "'{a}' used out of scope"]), JavaScriptWorker = exports.JavaScriptWorker = function (e) {
        Mirror.call(this, e), this.setTimeout(500), this.setOptions()
    };
    oop.inherits(JavaScriptWorker, Mirror), function () {
        this.setOptions = function (e) {
            this.options = e || {
                    esnext: !0,
                    moz: !0,
                    devel: !0,
                    browser: !0,
                    node: !0,
                    laxcomma: !0,
                    laxbreak: !0,
                    lastsemic: !0,
                    onevar: !1,
                    passfail: !1,
                    maxerr: 100,
                    expr: !0,
                    multistr: !0,
                    globalstrict: !0
                }, this.doc.getValue() && this.deferredUpdate.schedule(100)
        }, this.changeOptions = function (e) {
            oop.mixin(this.options, e), this.doc.getValue() && this.deferredUpdate.schedule(100)
        }, this.isValidJS = function (str) {
            try {
                eval("throw 0;" + str)
            } catch (e) {
                if (e === 0)return !0
            }
            return !1
        }, this.onUpdate = function () {
            var e = this.doc.getValue();
            e = e.replace(/^#!.*\n/, "\n");
            if (!e)return this.sender.emit("annotate", []);
            var t = [], n = this.isValidJS(e) ? "warning" : "error";
            lint(e, this.options);
            var r = lint.errors, i = !1;
            for (var s = 0; s < r.length; s++) {
                var o = r[s];
                if (!o)continue;
                var u = o.raw, a = "warning";
                if (u == "Missing semicolon.") {
                    var f = o.evidence.substr(o.character);
                    f = f.charAt(f.search(/\S/)), n == "error" && f && /[\w\d{(['"]/.test(f) ? (o.reason = 'Missing ";" before statement', a = "error") : a = "info"
                } else {
                    if (disabledWarningsRe.test(u))continue;
                    infoRe.test(u) ? a = "info" : errorsRe.test(u) ? (i = !0, a = n) : u == "'{a}' is not defined." ? a = "warning" : u == "'{a}' is defined but never used." && (a = "info")
                }
                t.push({row: o.line - 1, column: o.character - 1, text: o.reason, type: a, raw: u}), i
            }
            this.sender.emit("annotate", t)
        }
    }.call(JavaScriptWorker.prototype)
}), ace.define("ace/lib/es5-shim", ["require", "exports", "module"], function (e, t, n) {
    function r() {
    }

    function w(e) {
        try {
            return Object.defineProperty(e, "sentinel", {}), "sentinel" in e
        } catch (t) {
        }
    }

    function H(e) {
        return e = +e, e !== e ? e = 0 : e !== 0 && e !== 1 / 0 && e !== -1 / 0 && (e = (e > 0 || -1) * Math.floor(Math.abs(e))), e
    }

    function B(e) {
        var t = typeof e;
        return e === null || t === "undefined" || t === "boolean" || t === "number" || t === "string"
    }

    function j(e) {
        var t, n, r;
        if (B(e))return e;
        n = e.valueOf;
        if (typeof n == "function") {
            t = n.call(e);
            if (B(t))return t
        }
        r = e.toString;
        if (typeof r == "function") {
            t = r.call(e);
            if (B(t))return t
        }
        throw new TypeError
    }

    Function.prototype.bind || (Function.prototype.bind = function (t) {
        var n = this;
        if (typeof n != "function")throw new TypeError("Function.prototype.bind called on incompatible " + n);
        var i = u.call(arguments, 1), s = function () {
            if (this instanceof s) {
                var e = n.apply(this, i.concat(u.call(arguments)));
                return Object(e) === e ? e : this
            }
            return n.apply(t, i.concat(u.call(arguments)))
        };
        return n.prototype && (r.prototype = n.prototype, s.prototype = new r, r.prototype = null), s
    });
    var i = Function.prototype.call, s = Array.prototype, o = Object.prototype, u = s.slice, a = i.bind(o.toString), f = i.bind(o.hasOwnProperty), l, c, h, p, d;
    if (d = f(o, "__defineGetter__"))l = i.bind(o.__defineGetter__), c = i.bind(o.__defineSetter__), h = i.bind(o.__lookupGetter__), p = i.bind(o.__lookupSetter__);
    if ([1, 2].splice(0).length != 2)if (!function () {
            function e(e) {
                var t = new Array(e + 2);
                return t[0] = t[1] = 0, t
            }

            var t = [], n;
            t.splice.apply(t, e(20)), t.splice.apply(t, e(26)), n = t.length, t.splice(5, 0, "XXX"), n + 1 == t.length;
            if (n + 1 == t.length)return !0
        }())Array.prototype.splice = function (e, t) {
        var n = this.length;
        e > 0 ? e > n && (e = n) : e == void 0 ? e = 0 : e < 0 && (e = Math.max(n + e, 0)), e + t < n || (t = n - e);
        var r = this.slice(e, e + t), i = u.call(arguments, 2), s = i.length;
        if (e === n)s && this.push.apply(this, i); else {
            var o = Math.min(t, n - e), a = e + o, f = a + s - o, l = n - a, c = n - o;
            if (f < a)for (var h = 0; h < l; ++h)this[f + h] = this[a + h]; else if (f > a)for (h = l; h--;)this[f + h] = this[a + h];
            if (s && e === c)this.length = c, this.push.apply(this, i); else {
                this.length = c + s;
                for (h = 0; h < s; ++h)this[e + h] = i[h]
            }
        }
        return r
    }; else {
        var v = Array.prototype.splice;
        Array.prototype.splice = function (e, t) {
            return arguments.length ? v.apply(this, [e === void 0 ? 0 : e, t === void 0 ? this.length - e : t].concat(u.call(arguments, 2))) : []
        }
    }
    Array.isArray || (Array.isArray = function (t) {
        return a(t) == "[object Array]"
    });
    var m = Object("a"), g = m[0] != "a" || !(0 in m);
    Array.prototype.forEach || (Array.prototype.forEach = function (t) {
        var n = F(this), r = g && a(this) == "[object String]" ? this.split("") : n, i = arguments[1], s = -1, o = r.length >>> 0;
        if (a(t) != "[object Function]")throw new TypeError;
        while (++s < o)s in r && t.call(i, r[s], s, n)
    }), Array.prototype.map || (Array.prototype.map = function (t) {
        var n = F(this), r = g && a(this) == "[object String]" ? this.split("") : n, i = r.length >>> 0, s = Array(i), o = arguments[1];
        if (a(t) != "[object Function]")throw new TypeError(t + " is not a function");
        for (var u = 0; u < i; u++)u in r && (s[u] = t.call(o, r[u], u, n));
        return s
    }), Array.prototype.filter || (Array.prototype.filter = function (t) {
        var n = F(this), r = g && a(this) == "[object String]" ? this.split("") : n, i = r.length >>> 0, s = [], o, u = arguments[1];
        if (a(t) != "[object Function]")throw new TypeError(t + " is not a function");
        for (var f = 0; f < i; f++)f in r && (o = r[f], t.call(u, o, f, n) && s.push(o));
        return s
    }), Array.prototype.every || (Array.prototype.every = function (t) {
        var n = F(this), r = g && a(this) == "[object String]" ? this.split("") : n, i = r.length >>> 0, s = arguments[1];
        if (a(t) != "[object Function]")throw new TypeError(t + " is not a function");
        for (var o = 0; o < i; o++)if (o in r && !t.call(s, r[o], o, n))return !1;
        return !0
    }), Array.prototype.some || (Array.prototype.some = function (t) {
        var n = F(this), r = g && a(this) == "[object String]" ? this.split("") : n, i = r.length >>> 0, s = arguments[1];
        if (a(t) != "[object Function]")throw new TypeError(t + " is not a function");
        for (var o = 0; o < i; o++)if (o in r && t.call(s, r[o], o, n))return !0;
        return !1
    }), Array.prototype.reduce || (Array.prototype.reduce = function (t) {
        var n = F(this), r = g && a(this) == "[object String]" ? this.split("") : n, i = r.length >>> 0;
        if (a(t) != "[object Function]")throw new TypeError(t + " is not a function");
        if (!i && arguments.length == 1)throw new TypeError("reduce of empty array with no initial value");
        var s = 0, o;
        if (arguments.length >= 2)o = arguments[1]; else do {
            if (s in r) {
                o = r[s++];
                break
            }
            if (++s >= i)throw new TypeError("reduce of empty array with no initial value")
        } while (!0);
        for (; s < i; s++)s in r && (o = t.call(void 0, o, r[s], s, n));
        return o
    }), Array.prototype.reduceRight || (Array.prototype.reduceRight = function (t) {
        var n = F(this), r = g && a(this) == "[object String]" ? this.split("") : n, i = r.length >>> 0;
        if (a(t) != "[object Function]")throw new TypeError(t + " is not a function");
        if (!i && arguments.length == 1)throw new TypeError("reduceRight of empty array with no initial value");
        var s, o = i - 1;
        if (arguments.length >= 2)s = arguments[1]; else do {
            if (o in r) {
                s = r[o--];
                break
            }
            if (--o < 0)throw new TypeError("reduceRight of empty array with no initial value")
        } while (!0);
        do o in this && (s = t.call(void 0, s, r[o], o, n)); while (o--);
        return s
    });
    if (!Array.prototype.indexOf || [0, 1].indexOf(1, 2) != -1)Array.prototype.indexOf = function (t) {
        var n = g && a(this) == "[object String]" ? this.split("") : F(this), r = n.length >>> 0;
        if (!r)return -1;
        var i = 0;
        arguments.length > 1 && (i = H(arguments[1])), i = i >= 0 ? i : Math.max(0, r + i);
        for (; i < r; i++)if (i in n && n[i] === t)return i;
        return -1
    };
    if (!Array.prototype.lastIndexOf || [0, 1].lastIndexOf(0, -3) != -1)Array.prototype.lastIndexOf = function (t) {
        var n = g && a(this) == "[object String]" ? this.split("") : F(this), r = n.length >>> 0;
        if (!r)return -1;
        var i = r - 1;
        arguments.length > 1 && (i = Math.min(i, H(arguments[1]))), i = i >= 0 ? i : r - Math.abs(i);
        for (; i >= 0; i--)if (i in n && t === n[i])return i;
        return -1
    };
    Object.getPrototypeOf || (Object.getPrototypeOf = function (t) {
        return t.__proto__ || (t.constructor ? t.constructor.prototype : o)
    });
    if (!Object.getOwnPropertyDescriptor) {
        var y = "Object.getOwnPropertyDescriptor called on a non-object: ";
        Object.getOwnPropertyDescriptor = function (t, n) {
            if (typeof t != "object" && typeof t != "function" || t === null)throw new TypeError(y + t);
            if (!f(t, n))return;
            var r, i, s;
            r = {enumerable: !0, configurable: !0};
            if (d) {
                var u = t.__proto__;
                t.__proto__ = o;
                var i = h(t, n), s = p(t, n);
                t.__proto__ = u;
                if (i || s)return i && (r.get = i), s && (r.set = s), r
            }
            return r.value = t[n], r
        }
    }
    Object.getOwnPropertyNames || (Object.getOwnPropertyNames = function (t) {
        return Object.keys(t)
    });
    if (!Object.create) {
        var b;
        Object.prototype.__proto__ === null ? b = function () {
            return {__proto__: null}
        } : b = function () {
            var e = {};
            for (var t in e)e[t] = null;
            return e.constructor = e.hasOwnProperty = e.propertyIsEnumerable = e.isPrototypeOf = e.toLocaleString = e.toString = e.valueOf = e.__proto__ = null, e
        }, Object.create = function (t, n) {
            var r;
            if (t === null)r = b(); else {
                if (typeof t != "object")throw new TypeError("typeof prototype[" + typeof t + "] != 'object'");
                var i = function () {
                };
                i.prototype = t, r = new i, r.__proto__ = t
            }
            return n !== void 0 && Object.defineProperties(r, n), r
        }
    }
    if (Object.defineProperty) {
        var E = w({}), S = typeof document == "undefined" || w(document.createElement("div"));
        if (!E || !S)var x = Object.defineProperty
    }
    if (!Object.defineProperty || x) {
        var T = "Property description must be an object: ", N = "Object.defineProperty called on non-object: ", C = "getters & setters can not be defined on this javascript engine";
        Object.defineProperty = function (t, n, r) {
            if (typeof t != "object" && typeof t != "function" || t === null)throw new TypeError(N + t);
            if (typeof r != "object" && typeof r != "function" || r === null)throw new TypeError(T + r);
            if (x)try {
                return x.call(Object, t, n, r)
            } catch (i) {
            }
            if (f(r, "value"))if (d && (h(t, n) || p(t, n))) {
                var s = t.__proto__;
                t.__proto__ = o, delete t[n], t[n] = r.value, t.__proto__ = s
            } else t[n] = r.value; else {
                if (!d)throw new TypeError(C);
                f(r, "get") && l(t, n, r.get), f(r, "set") && c(t, n, r.set)
            }
            return t
        }
    }
    Object.defineProperties || (Object.defineProperties = function (t, n) {
        for (var r in n)f(n, r) && Object.defineProperty(t, r, n[r]);
        return t
    }), Object.seal || (Object.seal = function (t) {
        return t
    }), Object.freeze || (Object.freeze = function (t) {
        return t
    });
    try {
        Object.freeze(function () {
        })
    } catch (k) {
        Object.freeze = function (t) {
            return function (n) {
                return typeof n == "function" ? n : t(n)
            }
        }(Object.freeze)
    }
    Object.preventExtensions || (Object.preventExtensions = function (t) {
        return t
    }), Object.isSealed || (Object.isSealed = function (t) {
        return !1
    }), Object.isFrozen || (Object.isFrozen = function (t) {
        return !1
    }), Object.isExtensible || (Object.isExtensible = function (t) {
        if (Object(t) === t)throw new TypeError;
        var n = "";
        while (f(t, n))n += "?";
        t[n] = !0;
        var r = f(t, n);
        return delete t[n], r
    });
    if (!Object.keys) {
        var L = !0, A = ["toString", "toLocaleString", "valueOf", "hasOwnProperty", "isPrototypeOf", "propertyIsEnumerable", "constructor"], O = A.length;
        for (var M in{toString: null})L = !1;
        Object.keys = function I(e) {
            if (typeof e != "object" && typeof e != "function" || e === null)throw new TypeError("Object.keys called on a non-object");
            var I = [];
            for (var t in e)f(e, t) && I.push(t);
            if (L)for (var n = 0, r = O; n < r; n++) {
                var i = A[n];
                f(e, i) && I.push(i)
            }
            return I
        }
    }
    Date.now || (Date.now = function () {
        return (new Date).getTime()
    });
    var _ = "	\n\f\r \u00a0\u1680\u180e\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u202f\u205f\u3000\u2028\u2029\ufeff";
    if (!String.prototype.trim || _.trim()) {
        _ = "[" + _ + "]";
        var D = new RegExp("^" + _ + _ + "*"), P = new RegExp(_ + _ + "*$");
        String.prototype.trim = function () {
            return String(this).replace(D, "").replace(P, "")
        }
    }
    var F = function (e) {
        if (e == null)throw new TypeError("can't convert " + e + " to object");
        return Object(e)
    }
})