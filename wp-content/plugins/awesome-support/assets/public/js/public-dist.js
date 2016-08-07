function stringToBool(a) {
    return "true" === (a + "").toLowerCase()
}
function clearFileInput(a) {
    if (a.value) {
        try {
            a.value = ""
        } catch (b) {
        }
        if (a.value) {
            var c = document.createElement("form"), d = a.parentNode, e = a.nextSibling;
            c.appendChild(a), c.reset(), d.insertBefore(a, e)
        }
    }
}
!function (a, b) {
    "function" == typeof define && define.amd ? define([], b) : "object" == typeof exports ? module.exports = b() : a.Autolinker = b()
}(this, function () {
    var a = function (a) {
        a = a || {}, this.urls = this.normalizeUrlsCfg(a.urls), this.email = "boolean" == typeof a.email ? a.email : !0, this.twitter = "boolean" == typeof a.twitter ? a.twitter : !0, this.phone = "boolean" == typeof a.phone ? a.phone : !0, this.hashtag = a.hashtag || !1, this.newWindow = "boolean" == typeof a.newWindow ? a.newWindow : !0, this.stripPrefix = "boolean" == typeof a.stripPrefix ? a.stripPrefix : !0;
        var b = this.hashtag;
        if (b !== !1 && "twitter" !== b && "facebook" !== b && "instagram" !== b)throw new Error("invalid `hashtag` cfg - see docs");
        this.truncate = this.normalizeTruncateCfg(a.truncate), this.className = a.className || "", this.replaceFn = a.replaceFn || null, this.htmlParser = null, this.matchers = null, this.tagBuilder = null
    };
    return a.prototype = {
        constructor: a, normalizeUrlsCfg: function (a) {
            return null == a && (a = !0), "boolean" == typeof a ? {
                schemeMatches: a,
                wwwMatches: a,
                tldMatches: a
            } : {
                schemeMatches: "boolean" == typeof a.schemeMatches ? a.schemeMatches : !0,
                wwwMatches: "boolean" == typeof a.wwwMatches ? a.wwwMatches : !0,
                tldMatches: "boolean" == typeof a.tldMatches ? a.tldMatches : !0
            }
        }, normalizeTruncateCfg: function (b) {
            return "number" == typeof b ? {
                length: b,
                location: "end"
            } : a.Util.defaults(b || {}, {length: Number.POSITIVE_INFINITY, location: "end"})
        }, parse: function (a) {
            for (var b = this.getHtmlParser(), c = b.parse(a), d = 0, e = [], f = 0, g = c.length; g > f; f++) {
                var h = c[f], i = h.getType();
                if ("element" === i && "a" === h.getTagName())h.isClosing() ? d = Math.max(d - 1, 0) : d++; else if ("text" === i && 0 === d) {
                    var j = this.parseText(h.getText(), h.getOffset());
                    e.push.apply(e, j)
                }
            }
            return e = this.compactMatches(e), e = this.removeUnwantedMatches(e)
        }, compactMatches: function (a) {
            a.sort(function (a, b) {
                return a.getOffset() - b.getOffset()
            });
            for (var b = 0; b < a.length - 1; b++)for (var c = a[b], d = c.getOffset() + c.getMatchedText().length; b + 1 < a.length && a[b + 1].getOffset() <= d;)a.splice(b + 1, 1);
            return a
        }, removeUnwantedMatches: function (b) {
            var c = a.Util.remove;
            return this.hashtag || c(b, function (a) {
                return "hashtag" === a.getType()
            }), this.email || c(b, function (a) {
                return "email" === a.getType()
            }), this.phone || c(b, function (a) {
                return "phone" === a.getType()
            }), this.twitter || c(b, function (a) {
                return "twitter" === a.getType()
            }), this.urls.schemeMatches || c(b, function (a) {
                return "url" === a.getType() && "scheme" === a.getUrlMatchType()
            }), this.urls.wwwMatches || c(b, function (a) {
                return "url" === a.getType() && "www" === a.getUrlMatchType()
            }), this.urls.tldMatches || c(b, function (a) {
                return "url" === a.getType() && "tld" === a.getUrlMatchType()
            }), b
        }, parseText: function (a, b) {
            b = b || 0;
            for (var c = this.getMatchers(), d = [], e = 0, f = c.length; f > e; e++) {
                for (var g = c[e].parseMatches(a), h = 0, i = g.length; i > h; h++)g[h].setOffset(b + g[h].getOffset());
                d.push.apply(d, g)
            }
            return d
        }, link: function (a) {
            if (!a)return "";
            for (var b = this.parse(a), c = [], d = 0, e = 0, f = b.length; f > e; e++) {
                var g = b[e];
                c.push(a.substring(d, g.getOffset())), c.push(this.createMatchReturnVal(g)), d = g.getOffset() + g.getMatchedText().length
            }
            return c.push(a.substring(d)), c.join("")
        }, createMatchReturnVal: function (b) {
            var c;
            if (this.replaceFn && (c = this.replaceFn.call(this, this, b)), "string" == typeof c)return c;
            if (c === !1)return b.getMatchedText();
            if (c instanceof a.HtmlTag)return c.toAnchorString();
            var d = b.buildTag();
            return d.toAnchorString()
        }, getHtmlParser: function () {
            var b = this.htmlParser;
            return b || (b = this.htmlParser = new a.htmlParser.HtmlParser), b
        }, getMatchers: function () {
            if (this.matchers)return this.matchers;
            var b = a.matcher, c = this.getTagBuilder(), d = [new b.Hashtag({
                tagBuilder: c,
                serviceName: this.hashtag
            }), new b.Email({tagBuilder: c}), new b.Phone({tagBuilder: c}), new b.Twitter({tagBuilder: c}), new b.Url({
                tagBuilder: c,
                stripPrefix: this.stripPrefix
            })];
            return this.matchers = d
        }, getTagBuilder: function () {
            var b = this.tagBuilder;
            return b || (b = this.tagBuilder = new a.AnchorTagBuilder({
                newWindow: this.newWindow,
                truncate: this.truncate,
                className: this.className
            })), b
        }
    }, a.link = function (b, c) {
        var d = new a(c);
        return d.link(b)
    }, a.match = {}, a.matcher = {}, a.htmlParser = {}, a.truncate = {}, a.Util = {
        abstractMethod: function () {
            throw"abstract"
        }, trimRegex: /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, assign: function (a, b) {
            for (var c in b)b.hasOwnProperty(c) && (a[c] = b[c]);
            return a
        }, defaults: function (a, b) {
            for (var c in b)b.hasOwnProperty(c) && void 0 === a[c] && (a[c] = b[c]);
            return a
        }, extend: function (b, c) {
            var d = b.prototype, e = function () {
            };
            e.prototype = d;
            var f;
            f = c.hasOwnProperty("constructor") ? c.constructor : function () {
                d.constructor.apply(this, arguments)
            };
            var g = f.prototype = new e;
            return g.constructor = f, g.superclass = d, delete c.constructor, a.Util.assign(g, c), f
        }, ellipsis: function (a, b, c) {
            return a.length > b && (c = null == c ? ".." : c, a = a.substring(0, b - c.length) + c), a
        }, indexOf: function (a, b) {
            if (Array.prototype.indexOf)return a.indexOf(b);
            for (var c = 0, d = a.length; d > c; c++)if (a[c] === b)return c;
            return -1
        }, remove: function (a, b) {
            for (var c = a.length - 1; c >= 0; c--)b(a[c]) === !0 && a.splice(c, 1)
        }, splitAndCapture: function (a, b) {
            for (var c, d = [], e = 0; c = b.exec(a);)d.push(a.substring(e, c.index)), d.push(c[0]), e = c.index + c[0].length;
            return d.push(a.substring(e)), d
        }, trim: function (a) {
            return a.replace(this.trimRegex, "")
        }
    }, a.HtmlTag = a.Util.extend(Object, {
        whitespaceRegex: /\s+/, constructor: function (b) {
            a.Util.assign(this, b), this.innerHtml = this.innerHtml || this.innerHTML
        }, setTagName: function (a) {
            return this.tagName = a, this
        }, getTagName: function () {
            return this.tagName || ""
        }, setAttr: function (a, b) {
            var c = this.getAttrs();
            return c[a] = b, this
        }, getAttr: function (a) {
            return this.getAttrs()[a]
        }, setAttrs: function (b) {
            var c = this.getAttrs();
            return a.Util.assign(c, b), this
        }, getAttrs: function () {
            return this.attrs || (this.attrs = {})
        }, setClass: function (a) {
            return this.setAttr("class", a)
        }, addClass: function (b) {
            for (var c, d = this.getClass(), e = this.whitespaceRegex, f = a.Util.indexOf, g = d ? d.split(e) : [], h = b.split(e); c = h.shift();)-1 === f(g, c) && g.push(c);
            return this.getAttrs()["class"] = g.join(" "), this
        }, removeClass: function (b) {
            for (var c, d = this.getClass(), e = this.whitespaceRegex, f = a.Util.indexOf, g = d ? d.split(e) : [], h = b.split(e); g.length && (c = h.shift());) {
                var i = f(g, c);
                -1 !== i && g.splice(i, 1)
            }
            return this.getAttrs()["class"] = g.join(" "), this
        }, getClass: function () {
            return this.getAttrs()["class"] || ""
        }, hasClass: function (a) {
            return -1 !== (" " + this.getClass() + " ").indexOf(" " + a + " ")
        }, setInnerHtml: function (a) {
            return this.innerHtml = a, this
        }, getInnerHtml: function () {
            return this.innerHtml || ""
        }, toAnchorString: function () {
            var a = this.getTagName(), b = this.buildAttrsStr();
            return b = b ? " " + b : "", ["<", a, b, ">", this.getInnerHtml(), "</", a, ">"].join("")
        }, buildAttrsStr: function () {
            if (!this.attrs)return "";
            var a = this.getAttrs(), b = [];
            for (var c in a)a.hasOwnProperty(c) && b.push(c + '="' + a[c] + '"');
            return b.join(" ")
        }
    }), a.RegexLib = function () {
        var a = "A-Za-zªµºÀ-ÖØ-öø-ˁˆ-ˑˠ-ˤˬˮͰ-ʹͶͷͺ-ͽͿΆΈ-ΊΌΎ-ΡΣ-ϵϷ-ҁҊ-ԯԱ-Ֆՙա-ևא-תװ-ײؠ-يٮٯٱ-ۓەۥۦۮۯۺ-ۼۿܐܒ-ܯݍ-ޥޱߊ-ߪߴߵߺࠀ-ࠕࠚࠤࠨࡀ-ࡘࢠ-ࢴऄ-हऽॐक़-ॡॱ-ঀঅ-ঌএঐও-নপ-রলশ-হঽৎড়ঢ়য়-ৡৰৱਅ-ਊਏਐਓ-ਨਪ-ਰਲਲ਼ਵਸ਼ਸਹਖ਼-ੜਫ਼ੲ-ੴઅ-ઍએ-ઑઓ-નપ-રલળવ-હઽૐૠૡૹଅ-ଌଏଐଓ-ନପ-ରଲଳଵ-ହଽଡ଼ଢ଼ୟ-ୡୱஃஅ-ஊஎ-ஐஒ-கஙசஜஞடணதந-பம-ஹௐఅ-ఌఎ-ఐఒ-నప-హఽౘ-ౚౠౡಅ-ಌಎ-ಐಒ-ನಪ-ಳವ-ಹಽೞೠೡೱೲഅ-ഌഎ-ഐഒ-ഺഽൎൟ-ൡൺ-ൿඅ-ඖක-නඳ-රලව-ෆก-ะาำเ-ๆກຂຄງຈຊຍດ-ທນ-ຟມ-ຣລວສຫອ-ະາຳຽເ-ໄໆໜ-ໟༀཀ-ཇཉ-ཬྈ-ྌက-ဪဿၐ-ၕၚ-ၝၡၥၦၮ-ၰၵ-ႁႎႠ-ჅჇჍა-ჺჼ-ቈቊ-ቍቐ-ቖቘቚ-ቝበ-ኈኊ-ኍነ-ኰኲ-ኵኸ-ኾዀዂ-ዅወ-ዖዘ-ጐጒ-ጕጘ-ፚᎀ-ᎏᎠ-Ᏽᏸ-ᏽᐁ-ᙬᙯ-ᙿᚁ-ᚚᚠ-ᛪᛱ-ᛸᜀ-ᜌᜎ-ᜑᜠ-ᜱᝀ-ᝑᝠ-ᝬᝮ-ᝰក-ឳៗៜᠠ-ᡷᢀ-ᢨᢪᢰ-ᣵᤀ-ᤞᥐ-ᥭᥰ-ᥴᦀ-ᦫᦰ-ᧉᨀ-ᨖᨠ-ᩔᪧᬅ-ᬳᭅ-ᭋᮃ-ᮠᮮᮯᮺ-ᯥᰀ-ᰣᱍ-ᱏᱚ-ᱽᳩ-ᳬᳮ-ᳱᳵᳶᴀ-ᶿḀ-ἕἘ-Ἕἠ-ὅὈ-Ὅὐ-ὗὙὛὝὟ-ώᾀ-ᾴᾶ-ᾼιῂ-ῄῆ-ῌῐ-ΐῖ-Ίῠ-Ῥῲ-ῴῶ-ῼⁱⁿₐ-ₜℂℇℊ-ℓℕℙ-ℝℤΩℨK-ℭℯ-ℹℼ-ℿⅅ-ⅉⅎↃↄⰀ-Ⱞⰰ-ⱞⱠ-ⳤⳫ-ⳮⳲⳳⴀ-ⴥⴧⴭⴰ-ⵧⵯⶀ-ⶖⶠ-ⶦⶨ-ⶮⶰ-ⶶⶸ-ⶾⷀ-ⷆⷈ-ⷎⷐ-ⷖⷘ-ⷞⸯ々〆〱-〵〻〼ぁ-ゖゝ-ゟァ-ヺー-ヿㄅ-ㄭㄱ-ㆎㆠ-ㆺㇰ-ㇿ㐀-䶵一-鿕ꀀ-ꒌꓐ-ꓽꔀ-ꘌꘐ-ꘟꘪꘫꙀ-ꙮꙿ-ꚝꚠ-ꛥꜗ-ꜟꜢ-ꞈꞋ-ꞭꞰ-ꞷꟷ-ꠁꠃ-ꠅꠇ-ꠊꠌ-ꠢꡀ-ꡳꢂ-ꢳꣲ-ꣷꣻꣽꤊ-ꤥꤰ-ꥆꥠ-ꥼꦄ-ꦲꧏꧠ-ꧤꧦ-ꧯꧺ-ꧾꨀ-ꨨꩀ-ꩂꩄ-ꩋꩠ-ꩶꩺꩾ-ꪯꪱꪵꪶꪹ-ꪽꫀꫂꫛ-ꫝꫠ-ꫪꫲ-ꫴꬁ-ꬆꬉ-ꬎꬑ-ꬖꬠ-ꬦꬨ-ꬮꬰ-ꭚꭜ-ꭥꭰ-ꯢ가-힣ힰ-ퟆퟋ-ퟻ豈-舘並-龎ﬀ-ﬆﬓ-ﬗיִײַ-ﬨשׁ-זּטּ-לּמּנּסּףּפּצּ-ﮱﯓ-ﴽﵐ-ﶏﶒ-ﷇﷰ-ﷻﹰ-ﹴﹶ-ﻼＡ-Ｚａ-ｚｦ-ﾾￂ-ￇￊ-ￏￒ-ￗￚ-ￜ", b = "0-9٠-٩۰-۹߀-߉०-९০-৯੦-੯૦-૯୦-୯௦-௯౦-౯೦-೯൦-൯෦-෯๐-๙໐-໙༠-༩၀-၉႐-႙០-៩᠐-᠙᥆-᥏᧐-᧙᪀-᪉᪐-᪙᭐-᭙᮰-᮹᱀-᱉᱐-᱙꘠-꘩꣐-꣙꤀-꤉꧐-꧙꧰-꧹꩐-꩙꯰-꯹０-９", c = a + b, d = new RegExp("[" + c + ".\\-]*[" + c + "\\-]"), e = /(?:international|construction|contractors|enterprises|photography|productions|foundation|immobilien|industries|management|properties|technology|christmas|community|directory|education|equipment|institute|marketing|solutions|vacations|bargains|boutique|builders|catering|cleaning|clothing|computer|democrat|diamonds|graphics|holdings|lighting|partners|plumbing|supplies|training|ventures|academy|careers|company|cruises|domains|exposed|flights|florist|gallery|guitars|holiday|kitchen|neustar|okinawa|recipes|rentals|reviews|shiksha|singles|support|systems|agency|berlin|camera|center|coffee|condos|dating|estate|events|expert|futbol|kaufen|luxury|maison|monash|museum|nagoya|photos|repair|report|social|supply|tattoo|tienda|travel|viajes|villas|vision|voting|voyage|actor|build|cards|cheap|codes|dance|email|glass|house|mango|ninja|parts|photo|press|shoes|solar|today|tokyo|tools|watch|works|aero|arpa|asia|best|bike|blue|buzz|camp|club|cool|coop|farm|fish|gift|guru|info|jobs|kiwi|kred|land|limo|link|menu|mobi|moda|name|pics|pink|post|qpon|rich|ruhr|sexy|tips|vote|voto|wang|wien|wiki|zone|bar|bid|biz|cab|cat|ceo|com|edu|gov|int|kim|mil|net|onl|org|pro|pub|red|tel|uno|wed|xxx|xyz|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cu|cv|cw|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sx|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|za|zm|zw)\b/;
        return {alphaNumericCharsStr: c, domainNameRegex: d, tldRegex: e}
    }(), a.AnchorTagBuilder = a.Util.extend(Object, {
        constructor: function (b) {
            a.Util.assign(this, b)
        }, build: function (b) {
            return new a.HtmlTag({
                tagName: "a",
                attrs: this.createAttrs(b.getType(), b.getAnchorHref()),
                innerHtml: this.processAnchorText(b.getAnchorText())
            })
        }, createAttrs: function (a, b) {
            var c = {href: b}, d = this.createCssClass(a);
            return d && (c["class"] = d), this.newWindow && (c.target = "_blank"), c
        }, createCssClass: function (a) {
            var b = this.className;
            return b ? b + " " + b + "-" + a : ""
        }, processAnchorText: function (a) {
            return a = this.doTruncate(a)
        }, doTruncate: function (b) {
            var c = this.truncate;
            if (!c)return b;
            var d = c.length, e = c.location;
            return "smart" === e ? a.truncate.TruncateSmart(b, d, "..") : "middle" === e ? a.truncate.TruncateMiddle(b, d, "..") : a.truncate.TruncateEnd(b, d, "..")
        }
    }), a.htmlParser.HtmlParser = a.Util.extend(Object, {
        htmlRegex: function () {
            var a = /!--([\s\S]+?)--/, b = /[0-9a-zA-Z][0-9a-zA-Z:]*/, c = /[^\s\0"'>\/=\x01-\x1F\x7F]+/, d = /(?:"[^"]*?"|'[^']*?'|[^'"=<>`\s]+)/, e = c.source + "(?:\\s*=\\s*" + d.source + ")?";
            return new RegExp(["(?:", "<(!DOCTYPE)", "(?:", "\\s+", "(?:", e, "|", d.source + ")", ")*", ">", ")", "|", "(?:", "<(/)?", "(?:", a.source, "|", "(?:", "(" + b.source + ")", "(?:", "\\s*", e, ")*", "\\s*/?", ")", ")", ">", ")"].join(""), "gi")
        }(),
        htmlCharacterEntitiesRegex: /(&nbsp;|&#160;|&lt;|&#60;|&gt;|&#62;|&quot;|&#34;|&#39;)/gi,
        parse: function (a) {
            for (var b, c, d = this.htmlRegex, e = 0, f = []; null !== (b = d.exec(a));) {
                var g = b[0], h = b[3], i = b[1] || b[4], j = !!b[2], k = b.index, l = a.substring(e, k);
                l && (c = this.parseTextAndEntityNodes(e, l), f.push.apply(f, c)), h ? f.push(this.createCommentNode(k, g, h)) : f.push(this.createElementNode(k, g, i, j)), e = k + g.length
            }
            if (e < a.length) {
                var m = a.substring(e);
                m && (c = this.parseTextAndEntityNodes(e, m), f.push.apply(f, c))
            }
            return f
        },
        parseTextAndEntityNodes: function (b, c) {
            for (var d = [], e = a.Util.splitAndCapture(c, this.htmlCharacterEntitiesRegex), f = 0, g = e.length; g > f; f += 2) {
                var h = e[f], i = e[f + 1];
                h && (d.push(this.createTextNode(b, h)), b += h.length), i && (d.push(this.createEntityNode(b, i)), b += i.length)
            }
            return d
        },
        createCommentNode: function (b, c, d) {
            return new a.htmlParser.CommentNode({offset: b, text: c, comment: a.Util.trim(d)})
        },
        createElementNode: function (b, c, d, e) {
            return new a.htmlParser.ElementNode({offset: b, text: c, tagName: d.toLowerCase(), closing: e})
        },
        createEntityNode: function (b, c) {
            return new a.htmlParser.EntityNode({offset: b, text: c})
        },
        createTextNode: function (b, c) {
            return new a.htmlParser.TextNode({offset: b, text: c})
        }
    }), a.htmlParser.HtmlNode = a.Util.extend(Object, {
        offset: void 0, text: void 0, constructor: function (b) {
            a.Util.assign(this, b)
        }, getType: a.Util.abstractMethod, getOffset: function () {
            return this.offset
        }, getText: function () {
            return this.text
        }
    }), a.htmlParser.CommentNode = a.Util.extend(a.htmlParser.HtmlNode, {
        comment: "", getType: function () {
            return "comment"
        }, getComment: function () {
            return this.comment
        }
    }), a.htmlParser.ElementNode = a.Util.extend(a.htmlParser.HtmlNode, {
        tagName: "",
        closing: !1,
        getType: function () {
            return "element"
        },
        getTagName: function () {
            return this.tagName
        },
        isClosing: function () {
            return this.closing
        }
    }), a.htmlParser.EntityNode = a.Util.extend(a.htmlParser.HtmlNode, {
        getType: function () {
            return "entity"
        }
    }), a.htmlParser.TextNode = a.Util.extend(a.htmlParser.HtmlNode, {
        getType: function () {
            return "text"
        }
    }), a.match.Match = a.Util.extend(Object, {
        constructor: function (a) {
            this.tagBuilder = a.tagBuilder, this.matchedText = a.matchedText, this.offset = a.offset
        }, getType: a.Util.abstractMethod, getMatchedText: function () {
            return this.matchedText
        }, setOffset: function (a) {
            this.offset = a
        }, getOffset: function () {
            return this.offset
        }, getAnchorHref: a.Util.abstractMethod, getAnchorText: a.Util.abstractMethod, buildTag: function () {
            return this.tagBuilder.build(this)
        }
    }), a.match.Email = a.Util.extend(a.match.Match, {
        constructor: function (b) {
            a.match.Match.prototype.constructor.call(this, b), this.email = b.email
        }, getType: function () {
            return "email"
        }, getEmail: function () {
            return this.email
        }, getAnchorHref: function () {
            return "mailto:" + this.email
        }, getAnchorText: function () {
            return this.email
        }
    }), a.match.Hashtag = a.Util.extend(a.match.Match, {
        constructor: function (b) {
            a.match.Match.prototype.constructor.call(this, b), this.serviceName = b.serviceName, this.hashtag = b.hashtag
        }, getType: function () {
            return "hashtag"
        }, getServiceName: function () {
            return this.serviceName
        }, getHashtag: function () {
            return this.hashtag
        }, getAnchorHref: function () {
            var a = this.serviceName, b = this.hashtag;
            switch (a) {
                case"twitter":
                    return "https://twitter.com/hashtag/" + b;
                case"facebook":
                    return "https://www.facebook.com/hashtag/" + b;
                case"instagram":
                    return "https://instagram.com/explore/tags/" + b;
                default:
                    throw new Error("Unknown service name to point hashtag to: ", a)
            }
        }, getAnchorText: function () {
            return "#" + this.hashtag
        }
    }), a.match.Phone = a.Util.extend(a.match.Match, {
        constructor: function (b) {
            a.match.Match.prototype.constructor.call(this, b), this.number = b.number, this.plusSign = b.plusSign
        }, getType: function () {
            return "phone"
        }, getNumber: function () {
            return this.number
        }, getAnchorHref: function () {
            return "tel:" + (this.plusSign ? "+" : "") + this.number
        }, getAnchorText: function () {
            return this.matchedText
        }
    }), a.match.Twitter = a.Util.extend(a.match.Match, {
        constructor: function (b) {
            a.match.Match.prototype.constructor.call(this, b), this.twitterHandle = b.twitterHandle
        }, getType: function () {
            return "twitter"
        }, getTwitterHandle: function () {
            return this.twitterHandle
        }, getAnchorHref: function () {
            return "https://twitter.com/" + this.twitterHandle
        }, getAnchorText: function () {
            return "@" + this.twitterHandle
        }
    }), a.match.Url = a.Util.extend(a.match.Match, {
        constructor: function (b) {
            a.match.Match.prototype.constructor.call(this, b), this.urlMatchType = b.urlMatchType, this.url = b.url, this.protocolUrlMatch = b.protocolUrlMatch, this.protocolRelativeMatch = b.protocolRelativeMatch, this.stripPrefix = b.stripPrefix
        },
        urlPrefixRegex: /^(https?:\/\/)?(www\.)?/i,
        protocolRelativeRegex: /^\/\//,
        protocolPrepended: !1,
        getType: function () {
            return "url"
        },
        getUrlMatchType: function () {
            return this.urlMatchType
        },
        getUrl: function () {
            var a = this.url;
            return this.protocolRelativeMatch || this.protocolUrlMatch || this.protocolPrepended || (a = this.url = "http://" + a, this.protocolPrepended = !0), a
        },
        getAnchorHref: function () {
            var a = this.getUrl();
            return a.replace(/&amp;/g, "&")
        },
        getAnchorText: function () {
            var a = this.getMatchedText();
            return this.protocolRelativeMatch && (a = this.stripProtocolRelativePrefix(a)), this.stripPrefix && (a = this.stripUrlPrefix(a)), a = this.removeTrailingSlash(a)
        },
        stripUrlPrefix: function (a) {
            return a.replace(this.urlPrefixRegex, "")
        },
        stripProtocolRelativePrefix: function (a) {
            return a.replace(this.protocolRelativeRegex, "")
        },
        removeTrailingSlash: function (a) {
            return "/" === a.charAt(a.length - 1) && (a = a.slice(0, -1)), a
        }
    }), a.matcher.Matcher = a.Util.extend(Object, {
        constructor: function (a) {
            this.tagBuilder = a.tagBuilder
        }, parseMatches: a.Util.abstractMethod
    }), a.matcher.Email = a.Util.extend(a.matcher.Matcher, {
        matcherRegex: function () {
            var b = a.RegexLib.alphaNumericCharsStr, c = new RegExp("[" + b + "\\-;:&=+$.,]+@"), d = a.RegexLib.domainNameRegex, e = a.RegexLib.tldRegex;
            return new RegExp([c.source, d.source, "\\.", e.source].join(""), "gi")
        }(), parseMatches: function (b) {
            for (var c, d = this.matcherRegex, e = this.tagBuilder, f = []; null !== (c = d.exec(b));) {
                var g = c[0];
                f.push(new a.match.Email({tagBuilder: e, matchedText: g, offset: c.index, email: g}))
            }
            return f
        }
    }), a.matcher.Hashtag = a.Util.extend(a.matcher.Matcher, {
        matcherRegex: new RegExp("#[_" + a.RegexLib.alphaNumericCharsStr + "]{1,139}", "g"),
        nonWordCharRegex: new RegExp("[^" + a.RegexLib.alphaNumericCharsStr + "]"),
        constructor: function (b) {
            a.matcher.Matcher.prototype.constructor.call(this, b), this.serviceName = b.serviceName
        },
        parseMatches: function (b) {
            for (var c, d = this.matcherRegex, e = this.nonWordCharRegex, f = this.serviceName, g = this.tagBuilder, h = []; null !== (c = d.exec(b));) {
                var i = c.index, j = b.charAt(i - 1);
                if (0 === i || e.test(j)) {
                    var k = c[0], l = c[0].slice(1);
                    h.push(new a.match.Hashtag({tagBuilder: g, matchedText: k, offset: i, serviceName: f, hashtag: l}))
                }
            }
            return h
        }
    }), a.matcher.Phone = a.Util.extend(a.matcher.Matcher, {
        matcherRegex: /(?:(\+)?\d{1,3}[-\040.])?\(?\d{3}\)?[-\040.]?\d{3}[-\040.]\d{4}/g,
        parseMatches: function (b) {
            for (var c, d = this.matcherRegex, e = this.tagBuilder, f = []; null !== (c = d.exec(b));) {
                var g = c[0], h = g.replace(/\D/g, ""), i = !!c[1];
                f.push(new a.match.Phone({tagBuilder: e, matchedText: g, offset: c.index, number: h, plusSign: i}))
            }
            return f
        }
    }), a.matcher.Twitter = a.Util.extend(a.matcher.Matcher, {
        matcherRegex: new RegExp("@[_" + a.RegexLib.alphaNumericCharsStr + "]{1,20}", "g"),
        nonWordCharRegex: new RegExp("[^" + a.RegexLib.alphaNumericCharsStr + "]"),
        parseMatches: function (b) {
            for (var c, d = this.matcherRegex, e = this.nonWordCharRegex, f = this.tagBuilder, g = []; null !== (c = d.exec(b));) {
                var h = c.index, i = b.charAt(h - 1);
                if (0 === h || e.test(i)) {
                    var j = c[0], k = c[0].slice(1);
                    g.push(new a.match.Twitter({tagBuilder: f, matchedText: j, offset: h, twitterHandle: k}))
                }
            }
            return g
        }
    }), a.matcher.Url = a.Util.extend(a.matcher.Matcher, {
        matcherRegex: function () {
            var b = /(?:[A-Za-z][-.+A-Za-z0-9]*:(?![A-Za-z][-.+A-Za-z0-9]*:\/\/)(?!\d+\/?)(?:\/\/)?)/, c = /(?:www\.)/, d = a.RegexLib.domainNameRegex, e = a.RegexLib.tldRegex, f = a.RegexLib.alphaNumericCharsStr, g = new RegExp("[" + f + "\\-+&@#/%=~_()|'$*\\[\\]?!:,.;]*[" + f + "\\-+&@#/%=~_()|'$*\\[\\]]");
            return new RegExp(["(?:", "(", b.source, d.source, ")", "|", "(", "(//)?", c.source, d.source, ")", "|", "(", "(//)?", d.source + "\\.", e.source, ")", ")", "(?:" + g.source + ")?"].join(""), "gi")
        }(), wordCharRegExp: /\w/, openParensRe: /\(/g, closeParensRe: /\)/g, constructor: function (b) {
            a.matcher.Matcher.prototype.constructor.call(this, b), this.stripPrefix = b.stripPrefix
        }, parseMatches: function (b) {
            for (var c, d = this.matcherRegex, e = this.stripPrefix, f = this.tagBuilder, g = []; null !== (c = d.exec(b));) {
                var h = c[0], i = c[1], j = c[2], k = c[3], l = c[5], m = c.index, n = k || l, o = b.charAt(m - 1);
                if (a.matcher.UrlMatchValidator.isValid(h, i) && !(m > 0 && "@" === o || m > 0 && n && this.wordCharRegExp.test(o))) {
                    if (this.matchHasUnbalancedClosingParen(h))h = h.substr(0, h.length - 1); else {
                        var p = this.matchHasInvalidCharAfterTld(h, i);
                        p > -1 && (h = h.substr(0, p))
                    }
                    var q = i ? "scheme" : j ? "www" : "tld", r = !!i;
                    g.push(new a.match.Url({
                        tagBuilder: f,
                        matchedText: h,
                        offset: m,
                        urlMatchType: q,
                        url: h,
                        protocolUrlMatch: r,
                        protocolRelativeMatch: !!n,
                        stripPrefix: e
                    }))
                }
            }
            return g
        }, matchHasUnbalancedClosingParen: function (a) {
            var b = a.charAt(a.length - 1);
            if (")" === b) {
                var c = a.match(this.openParensRe), d = a.match(this.closeParensRe), e = c && c.length || 0, f = d && d.length || 0;
                if (f > e)return !0
            }
            return !1
        }, matchHasInvalidCharAfterTld: function (a, b) {
            if (!a)return -1;
            var c = 0;
            b && (c = a.indexOf(":"), a = a.slice(c));
            var d = /^((.?\/\/)?[A-Za-z0-9\u00C0-\u017F\.\-]*[A-Za-z0-9\u00C0-\u017F\-]\.[A-Za-z]+)/, e = d.exec(a);
            return null === e ? -1 : (c += e[1].length, a = a.slice(e[1].length), /^[^.A-Za-z:\/?#]/.test(a) ? c : -1)
        }
    }), a.matcher.UrlMatchValidator = {
        hasFullProtocolRegex: /^[A-Za-z][-.+A-Za-z0-9]*:\/\//,
        uriSchemeRegex: /^[A-Za-z][-.+A-Za-z0-9]*:/,
        hasWordCharAfterProtocolRegex: /:[^\s]*?[A-Za-z\u00C0-\u017F]/,
        isValid: function (a, b) {
            return !(b && !this.isValidUriScheme(b) || this.urlMatchDoesNotHaveProtocolOrDot(a, b) || this.urlMatchDoesNotHaveAtLeastOneWordChar(a, b))
        },
        isValidUriScheme: function (a) {
            var b = a.match(this.uriSchemeRegex)[0].toLowerCase();
            return "javascript:" !== b && "vbscript:" !== b
        },
        urlMatchDoesNotHaveProtocolOrDot: function (a, b) {
            return !(!a || b && this.hasFullProtocolRegex.test(b) || -1 !== a.indexOf("."))
        },
        urlMatchDoesNotHaveAtLeastOneWordChar: function (a, b) {
            return a && b ? !this.hasWordCharAfterProtocolRegex.test(a) : !1
        }
    }, a.truncate.TruncateEnd = function (b, c, d) {
        return a.Util.ellipsis(b, c, d)
    }, a.truncate.TruncateMiddle = function (a, b, c) {
        if (a.length <= b)return a;
        var d = b - c.length, e = "";
        return d > 0 && (e = a.substr(-1 * Math.floor(d / 2))), (a.substr(0, Math.ceil(d / 2)) + c + e).substr(0, b)
    }, a.truncate.TruncateSmart = function (a, b, c) {
        var d = function (a) {
            var b = {}, c = a, d = c.match(/^([a-z]+):\/\//i);
            return d && (b.scheme = d[1], c = c.substr(d[0].length)), d = c.match(/^(.*?)(?=(\?|#|\/|$))/i), d && (b.host = d[1], c = c.substr(d[0].length)), d = c.match(/^\/(.*?)(?=(\?|#|$))/i), d && (b.path = d[1], c = c.substr(d[0].length)), d = c.match(/^\?(.*?)(?=(#|$))/i), d && (b.query = d[1], c = c.substr(d[0].length)), d = c.match(/^#(.*?)$/i), d && (b.fragment = d[1]), b
        }, e = function (a) {
            var b = "";
            return a.scheme && a.host && (b += a.scheme + "://"), a.host && (b += a.host), a.path && (b += "/" + a.path), a.query && (b += "?" + a.query), a.fragment && (b += "#" + a.fragment), b
        }, f = function (a, b) {
            var d = b / 2, e = Math.ceil(d), f = -1 * Math.floor(d), g = "";
            return 0 > f && (g = a.substr(f)), a.substr(0, e) + c + g
        };
        if (a.length <= b)return a;
        var g = b - c.length, h = d(a);
        if (h.query) {
            var i = h.query.match(/^(.*?)(?=(\?|\#))(.*?)$/i);
            i && (h.query = h.query.substr(0, i[1].length), a = e(h))
        }
        if (a.length <= b)return a;
        if (h.host && (h.host = h.host.replace(/^www\./, ""), a = e(h)), a.length <= b)return a;
        var j = "";
        if (h.host && (j += h.host), j.length >= g)return h.host.length == b ? (h.host.substr(0, b - c.length) + c).substr(0, b) : f(j, g).substr(0, b);
        var k = "";
        if (h.path && (k += "/" + h.path), h.query && (k += "?" + h.query), k) {
            if ((j + k).length >= g) {
                if ((j + k).length == b)return (j + k).substr(0, b);
                var l = g - j.length;
                return (j + f(k, l)).substr(0, b)
            }
            j += k
        }
        if (h.fragment) {
            var m = "#" + h.fragment;
            if ((j + m).length >= g) {
                if ((j + m).length == b)return (j + m).substr(0, b);
                var n = g - j.length;
                return (j + f(m, n)).substr(0, b)
            }
            j += m
        }
        if (h.scheme && h.host) {
            var o = h.scheme + "://";
            if ((j + o).length < g)return (o + j).substr(0, b)
        }
        if (j.length <= b)return j;
        var p = "";
        return g > 0 && (p = j.substr(-1 * Math.floor(g / 2))), (j.substr(0, Math.ceil(g / 2)) + c + p).substr(0, b)
    }, a
}), !function (a) {
    "use strict";
    function b(a, c) {
        if (!(this instanceof b)) {
            var d = new b(a, c);
            return d.open(), d
        }
        this.id = b.id++, this.setup(a, c), this.chainCallbacks(b._callbackChain)
    }

    if ("undefined" == typeof a)return void("console" in window && window.console.info("Too much lightness, Featherlight needs jQuery."));
    var c = [], d = function (b) {
        return c = a.grep(c, function (a) {
            return a !== b && a.$instance.closest("body").length > 0
        })
    }, e = function (a, b) {
        var c = {}, d = new RegExp("^" + b + "([A-Z])(.*)");
        for (var e in a) {
            var f = e.match(d);
            if (f) {
                var g = (f[1] + f[2].replace(/([A-Z])/g, "-$1")).toLowerCase();
                c[g] = a[e]
            }
        }
        return c
    }, f = {keyup: "onKeyUp", resize: "onResize"}, g = function (c) {
        a.each(b.opened().reverse(), function () {
            return c.isDefaultPrevented() || !1 !== this[f[c.type]](c) ? void 0 : (c.preventDefault(), c.stopPropagation(), !1)
        })
    }, h = function (c) {
        if (c !== b._globalHandlerInstalled) {
            b._globalHandlerInstalled = c;
            var d = a.map(f, function (a, c) {
                return c + "." + b.prototype.namespace
            }).join(" ");
            a(window)[c ? "on" : "off"](d, g)
        }
    };
    b.prototype = {
        constructor: b,
        namespace: "featherlight",
        targetAttr: "data-featherlight",
        variant: null,
        resetCss: !1,
        background: null,
        openTrigger: "click",
        closeTrigger: "click",
        filter: null,
        root: "body",
        openSpeed: 250,
        closeSpeed: 250,
        closeOnClick: "background",
        closeOnEsc: !0,
        closeIcon: "&#10005;",
        loading: "",
        persist: !1,
        otherClose: null,
        beforeOpen: a.noop,
        beforeContent: a.noop,
        beforeClose: a.noop,
        afterOpen: a.noop,
        afterContent: a.noop,
        afterClose: a.noop,
        onKeyUp: a.noop,
        onResize: a.noop,
        type: null,
        contentFilters: ["jquery", "image", "html", "ajax", "iframe", "text"],
        setup: function (b, c) {
            "object" != typeof b || b instanceof a != 0 || c || (c = b, b = void 0);
            var d = a.extend(this, c, {target: b}), e = d.resetCss ? d.namespace + "-reset" : d.namespace, f = a(d.background || ['<div class="' + e + "-loading " + e + '">', '<div class="' + e + '-content">', '<span class="' + e + "-close-icon " + d.namespace + '-close">', d.closeIcon, "</span>", '<div class="' + d.namespace + '-inner">' + d.loading + "</div>", "</div>", "</div>"].join("")), g = "." + d.namespace + "-close" + (d.otherClose ? "," + d.otherClose : "");
            return d.$instance = f.clone().addClass(d.variant), d.$instance.on(d.closeTrigger + "." + d.namespace, function (b) {
                var c = a(b.target);
                ("background" === d.closeOnClick && c.is("." + d.namespace) || "anywhere" === d.closeOnClick || c.closest(g).length) && (d.close(b), b.preventDefault())
            }), this
        },
        getContent: function () {
            if (this.persist !== !1 && this.$content)return this.$content;
            var b = this, c = this.constructor.contentFilters, d = function (a) {
                return b.$currentTarget && b.$currentTarget.attr(a)
            }, e = d(b.targetAttr), f = b.target || e || "", g = c[b.type];
            if (!g && f in c && (g = c[f], f = b.target && e), f = f || d("href") || "", !g)for (var h in c)b[h] && (g = c[h], f = b[h]);
            if (!g) {
                var i = f;
                if (f = null, a.each(b.contentFilters, function () {
                        return g = c[this], g.test && (f = g.test(i)), !f && g.regex && i.match && i.match(g.regex) && (f = i), !f
                    }), !f)return "console" in window && window.console.error("Featherlight: no content filter found " + (i ? ' for "' + i + '"' : " (no target specified)")), !1
            }
            return g.process.call(b, f)
        },
        setContent: function (b) {
            var c = this;
            return (b.is("iframe") || a("iframe", b).length > 0) && c.$instance.addClass(c.namespace + "-iframe"), c.$instance.removeClass(c.namespace + "-loading"), c.$instance.find("." + c.namespace + "-inner").not(b).slice(1).remove().end().replaceWith(a.contains(c.$instance[0], b[0]) ? "" : b), c.$content = b.addClass(c.namespace + "-inner"), c
        },
        open: function (b) {
            var d = this;
            if (d.$instance.hide().appendTo(d.root), !(b && b.isDefaultPrevented() || d.beforeOpen(b) === !1)) {
                b && b.preventDefault();
                var e = d.getContent();
                if (e)return c.push(d), h(!0), d.$instance.fadeIn(d.openSpeed), d.beforeContent(b), a.when(e).always(function (a) {
                    d.setContent(a), d.afterContent(b)
                }).then(d.$instance.promise()).done(function () {
                    d.afterOpen(b)
                })
            }
            return d.$instance.detach(), a.Deferred().reject().promise()
        },
        close: function (b) {
            var c = this, e = a.Deferred();
            return c.beforeClose(b) === !1 ? e.reject() : (0 === d(c).length && h(!1), c.$instance.fadeOut(c.closeSpeed, function () {
                c.$instance.detach(), c.afterClose(b), e.resolve()
            })), e.promise()
        },
        resize: function (a, b) {
            if (a && b) {
                this.$content.css("width", "").css("height", "");
                var c = Math.max(a / parseInt(this.$content.parent().css("width"), 10), b / parseInt(this.$content.parent().css("height"), 10));
                c > 1 && this.$content.css("width", "" + a / c + "px").css("height", "" + b / c + "px")
            }
        },
        chainCallbacks: function (b) {
            for (var c in b)this[c] = a.proxy(b[c], this, a.proxy(this[c], this))
        }
    }, a.extend(b, {
        id: 0,
        autoBind: "[data-featherlight]",
        defaults: b.prototype,
        contentFilters: {
            jquery: {
                regex: /^[#.]\w/, test: function (b) {
                    return b instanceof a && b
                }, process: function (b) {
                    return this.persist !== !1 ? a(b) : a(b).clone(!0)
                }
            }, image: {
                regex: /\.(png|jpg|jpeg|gif|tiff|bmp|svg)(\?\S*)?$/i, process: function (b) {
                    var c = this, d = a.Deferred(), e = new Image, f = a('<img src="' + b + '" alt="" class="' + c.namespace + '-image" />');
                    return e.onload = function () {
                        f.naturalWidth = e.width, f.naturalHeight = e.height, d.resolve(f)
                    }, e.onerror = function () {
                        d.reject(f)
                    }, e.src = b, d.promise()
                }
            }, html: {
                regex: /^\s*<[\w!][^<]*>/, process: function (b) {
                    return a(b)
                }
            }, ajax: {
                regex: /./, process: function (b) {
                    var c = a.Deferred(), d = a("<div></div>").load(b, function (a, b) {
                        "error" !== b && c.resolve(d.contents()), c.fail()
                    });
                    return c.promise()
                }
            }, iframe: {
                process: function (b) {
                    var c = new a.Deferred, d = a("<iframe/>").hide().attr("src", b).css(e(this, "iframe")).on("load", function () {
                        c.resolve(d.show())
                    }).appendTo(this.$instance.find("." + this.namespace + "-content"));
                    return c.promise()
                }
            }, text: {
                process: function (b) {
                    return a("<div>", {text: b})
                }
            }
        },
        functionAttributes: ["beforeOpen", "afterOpen", "beforeContent", "afterContent", "beforeClose", "afterClose"],
        readElementConfig: function (b, c) {
            var d = this, e = new RegExp("^data-" + c + "-(.*)"), f = {};
            return b && b.attributes && a.each(b.attributes, function () {
                var b = this.name.match(e);
                if (b) {
                    var c = this.value, g = a.camelCase(b[1]);
                    if (a.inArray(g, d.functionAttributes) >= 0)c = new Function(c); else try {
                        c = a.parseJSON(c)
                    } catch (h) {
                    }
                    f[g] = c
                }
            }), f
        },
        extend: function (b, c) {
            var d = function () {
                this.constructor = b
            };
            return d.prototype = this.prototype, b.prototype = new d, b.__super__ = this.prototype, a.extend(b, this, c), b.defaults = b.prototype, b
        },
        attach: function (b, c, d) {
            var e = this;
            "object" != typeof c || c instanceof a != 0 || d || (d = c, c = void 0), d = a.extend({}, d);
            var f, g = d.namespace || e.defaults.namespace, h = a.extend({}, e.defaults, e.readElementConfig(b[0], g), d);
            return b.on(h.openTrigger + "." + h.namespace, h.filter, function (g) {
                var i = a.extend({
                    $source: b,
                    $currentTarget: a(this)
                }, e.readElementConfig(b[0], h.namespace), e.readElementConfig(this, h.namespace), d), j = f || a(this).data("featherlight-persisted") || new e(c, i);
                "shared" === j.persist ? f = j : j.persist !== !1 && a(this).data("featherlight-persisted", j), i.$currentTarget.blur(), j.open(g)
            }), b
        },
        current: function () {
            var a = this.opened();
            return a[a.length - 1] || null
        },
        opened: function () {
            var b = this;
            return d(), a.grep(c, function (a) {
                return a instanceof b
            })
        },
        close: function (a) {
            var b = this.current();
            return b ? b.close(a) : void 0
        },
        _onReady: function () {
            var b = this;
            b.autoBind && (a(b.autoBind).each(function () {
                b.attach(a(this))
            }), a(document).on("click", b.autoBind, function (c) {
                c.isDefaultPrevented() || "featherlight" === c.namespace || (c.preventDefault(), b.attach(a(c.currentTarget)), a(c.target).trigger("click.featherlight"))
            }))
        },
        _callbackChain: {
            onKeyUp: function (b, c) {
                return 27 === c.keyCode ? (this.closeOnEsc && a.featherlight.close(c), !1) : b(c)
            }, onResize: function (a, b) {
                return this.resize(this.$content.naturalWidth, this.$content.naturalHeight), a(b)
            }, afterContent: function (a, b) {
                var c = a(b);
                return this.onResize(b), c
            }
        }
    }), a.featherlight = b, a.fn.featherlight = function (a, c) {
        return b.attach(this, a, c)
    }, a(document).ready(function () {
        b._onReady()
    })
}(jQuery), function (a, b) {
    function c() {
        var a = this;
        a.id = null, a.busy = !1, a.start = function (b, c) {
            a.busy || (a.stop(), a.id = setTimeout(function () {
                b(), a.id = null, a.busy = !1
            }, c), a.busy = !0)
        }, a.stop = function () {
            null !== a.id && (clearTimeout(a.id), a.id = null, a.busy = !1)
        }
    }

    function d(d, e, f) {
        var g = this;
        g.id = f, g.table = d, g.options = e, g.breakpoints = [], g.breakpointNames = "", g.columns = {}, g.plugins = b.footable.plugins.load(g);
        var h = g.options, i = h.classes, j = h.events, k = h.triggers, l = 0;
        return g.timers = {
            resize: new c, register: function (a) {
                return g.timers[a] = new c, g.timers[a]
            }
        }, g.init = function () {
            var c = a(b), d = a(g.table);
            if (b.footable.plugins.init(g), d.hasClass(i.loaded))return void g.raise(j.alreadyInitialized);
            g.raise(j.initializing), d.addClass(i.loading), d.find(h.columnDataSelector).each(function () {
                var a = g.getColumnData(this);
                g.columns[a.index] = a
            });
            for (var e in h.breakpoints)g.breakpoints.push({
                name: e,
                width: h.breakpoints[e]
            }), g.breakpointNames += e + " ";
            g.breakpoints.sort(function (a, b) {
                return a.width - b.width
            }), d.unbind(k.initialize).bind(k.initialize, function () {
                d.removeData("footable_info"), d.data("breakpoint", ""), d.trigger(k.resize), d.removeClass(i.loading), d.addClass(i.loaded).addClass(i.main), g.raise(j.initialized)
            }).unbind(k.redraw).bind(k.redraw, function () {
                g.redraw()
            }).unbind(k.resize).bind(k.resize, function () {
                g.resize()
            }).unbind(k.expandFirstRow).bind(k.expandFirstRow, function () {
                d.find(h.toggleSelector).first().not("." + i.detailShow).trigger(k.toggleRow)
            }).unbind(k.expandAll).bind(k.expandAll, function () {
                d.find(h.toggleSelector).not("." + i.detailShow).trigger(k.toggleRow)
            }).unbind(k.collapseAll).bind(k.collapseAll, function () {
                d.find("." + i.detailShow).trigger(k.toggleRow)
            }), d.trigger(k.initialize), c.bind("resize.footable", function () {
                g.timers.resize.stop(), g.timers.resize.start(function () {
                    g.raise(k.resize)
                }, h.delay)
            })
        }, g.addRowToggle = function () {
            if (h.addRowToggle) {
                var b = a(g.table), c = !1;
                b.find("span." + i.toggle).remove();
                for (var d in g.columns) {
                    var e = g.columns[d];
                    if (e.toggle) {
                        c = !0;
                        var f = "> tbody > tr:not(." + i.detail + ",." + i.disabled + ") > td:nth-child(" + (parseInt(e.index, 10) + 1) + "),> tbody > tr:not(." + i.detail + ",." + i.disabled + ") > th:nth-child(" + (parseInt(e.index, 10) + 1) + ")";
                        return void b.find(f).not("." + i.detailCell).prepend(a(h.toggleHTMLElement).addClass(i.toggle))
                    }
                }
                c || b.find("> tbody > tr:not(." + i.detail + ",." + i.disabled + ") > td:first-child").add("> tbody > tr:not(." + i.detail + ",." + i.disabled + ") > th:first-child").not("." + i.detailCell).prepend(a(h.toggleHTMLElement).addClass(i.toggle))
            }
        }, g.setColumnClasses = function () {
            var b = a(g.table);
            for (var c in g.columns) {
                var d = g.columns[c];
                if (null !== d.className) {
                    var e = "", f = !0;
                    a.each(d.matches, function (a, b) {
                        f || (e += ", "), e += "> tbody > tr:not(." + i.detail + ") > td:nth-child(" + (parseInt(b, 10) + 1) + ")", f = !1
                    }), b.find(e).not("." + i.detailCell).addClass(d.className)
                }
            }
        }, g.bindToggleSelectors = function () {
            var b = a(g.table);
            g.hasAnyBreakpointColumn() && (b.find(h.toggleSelector).unbind(k.toggleRow).bind(k.toggleRow, function () {
                var b = a(this).is("tr") ? a(this) : a(this).parents("tr:first");
                g.toggleDetail(b)
            }), b.find(h.toggleSelector).unbind("click.footable").bind("click.footable", function (c) {
                b.is(".breakpoint") && a(c.target).is("td,th,." + i.toggle) && a(this).trigger(k.toggleRow)
            }))
        }, g.parse = function (a, b) {
            var c = h.parsers[b.type] || h.parsers.alpha;
            return c(a)
        }, g.getColumnData = function (b) {
            var c = a(b), d = c.data("hide"), e = c.index();
            d = d || "", d = jQuery.map(d.split(","), function (a) {
                return jQuery.trim(a)
            });
            var f = {
                index: e,
                hide: {},
                type: c.data("type") || "alpha",
                name: c.data("name") || a.trim(c.text()),
                ignore: c.data("ignore") || !1,
                toggle: c.data("toggle") || !1,
                className: c.data("class") || null,
                matches: [],
                names: {},
                group: c.data("group") || null,
                groupName: null,
                isEditable: c.data("editable")
            };
            if (null !== f.group) {
                var i = a(g.table).find('> thead > tr.footable-group-row > th[data-group="' + f.group + '"], > thead > tr.footable-group-row > td[data-group="' + f.group + '"]').first();
                f.groupName = g.parse(i, {type: "alpha"})
            }
            var k = parseInt(c.prev().attr("colspan") || 0, 10);
            l += k > 1 ? k - 1 : 0;
            var m = parseInt(c.attr("colspan") || 0, 10), n = f.index + l;
            if (m > 1) {
                var o = c.data("names");
                o = o || "", o = o.split(",");
                for (var p = 0; m > p; p++)f.matches.push(p + n), o.length > p && (f.names[p + n] = o[p])
            } else f.matches.push(n);
            f.hide["default"] = "all" === c.data("hide") || a.inArray("default", d) >= 0;
            var q = !1;
            for (var r in h.breakpoints)f.hide[r] = "all" === c.data("hide") || a.inArray(r, d) >= 0, q = q || f.hide[r];
            f.hasBreakpoint = q;
            var s = g.raise(j.columnData, {column: {data: f, th: b}});
            return s.column.data
        }, g.getViewportWidth = function () {
            return window.innerWidth || (document.body ? document.body.offsetWidth : 0)
        }, g.calculateWidth = function (a, b) {
            return jQuery.isFunction(h.calculateWidthOverride) ? h.calculateWidthOverride(a, b) : (b.viewportWidth < b.width && (b.width = b.viewportWidth), b.parentWidth < b.width && (b.width = b.parentWidth), b)
        }, g.hasBreakpointColumn = function (a) {
            for (var b in g.columns)if (g.columns[b].hide[a]) {
                if (g.columns[b].ignore)continue;
                return !0
            }
            return !1
        }, g.hasAnyBreakpointColumn = function () {
            for (var a in g.columns)if (g.columns[a].hasBreakpoint)return !0;
            return !1
        }, g.resize = function () {
            var b = a(g.table);
            if (b.is(":visible")) {
                if (!g.hasAnyBreakpointColumn())return void b.trigger(k.redraw);
                var c = {width: b.width(), viewportWidth: g.getViewportWidth(), parentWidth: b.parent().width()};
                c = g.calculateWidth(b, c);
                var d = b.data("footable_info");
                if (b.data("footable_info", c), g.raise(j.resizing, {
                        old: d,
                        info: c
                    }), !d || d && d.width && d.width !== c.width) {
                    for (var e, f = null, h = 0; g.breakpoints.length > h; h++)if (e = g.breakpoints[h], e && e.width && c.width <= e.width) {
                        f = e;
                        break
                    }
                    var i = null === f ? "default" : f.name, l = g.hasBreakpointColumn(i), m = b.data("breakpoint");
                    b.data("breakpoint", i).removeClass("default breakpoint").removeClass(g.breakpointNames).addClass(i + (l ? " breakpoint" : "")), i !== m && (b.trigger(k.redraw), g.raise(j.breakpoint, {
                        breakpoint: i,
                        info: c
                    }))
                }
                g.raise(j.resized, {old: d, info: c})
            }
        }, g.redraw = function () {
            g.addRowToggle(), g.bindToggleSelectors(), g.setColumnClasses();
            var b = a(g.table), c = b.data("breakpoint"), d = g.hasBreakpointColumn(c);
            b.find("> tbody > tr:not(." + i.detail + ")").data("detail_created", !1).end().find("> thead > tr:last-child > th").each(function () {
                var d = g.columns[a(this).index()], e = "", f = !0;
                a.each(d.matches, function (a, b) {
                    f || (e += ", ");
                    var c = b + 1;
                    e += "> tbody > tr:not(." + i.detail + ") > td:nth-child(" + c + ")", e += ", > tfoot > tr:not(." + i.detail + ") > td:nth-child(" + c + ")", e += ", > colgroup > col:nth-child(" + c + ")", f = !1
                }), e += ', > thead > tr[data-group-row="true"] > th[data-group="' + d.group + '"]';
                var h = b.find(e).add(this);
                if ("" !== c && (d.hide[c] === !1 ? h.addClass("footable-visible").show() : h.removeClass("footable-visible").hide()), 1 === b.find("> thead > tr.footable-group-row").length) {
                    var j = b.find('> thead > tr:last-child > th[data-group="' + d.group + '"]:visible, > thead > tr:last-child > th[data-group="' + d.group + '"]:visible'), k = b.find('> thead > tr.footable-group-row > th[data-group="' + d.group + '"], > thead > tr.footable-group-row > td[data-group="' + d.group + '"]'), l = 0;
                    a.each(j, function () {
                        l += parseInt(a(this).attr("colspan") || 1, 10)
                    }), l > 0 ? k.attr("colspan", l).show() : k.hide()
                }
            }).end().find("> tbody > tr." + i.detailShow).each(function () {
                g.createOrUpdateDetailRow(this)
            }), b.find("[data-bind-name]").each(function () {
                g.toggleInput(this)
            }), b.find("> tbody > tr." + i.detailShow + ":visible").each(function () {
                var b = a(this).next();
                b.hasClass(i.detail) && (d ? b.show() : b.hide())
            }), b.find("> thead > tr > th.footable-last-column, > tbody > tr > td.footable-last-column").removeClass("footable-last-column"), b.find("> thead > tr > th.footable-first-column, > tbody > tr > td.footable-first-column").removeClass("footable-first-column"), b.find("> thead > tr, > tbody > tr").find("> th.footable-visible:last, > td.footable-visible:last").addClass("footable-last-column").end().find("> th.footable-visible:first, > td.footable-visible:first").addClass("footable-first-column"), g.raise(j.redrawn)
        }, g.toggleDetail = function (b) {
            var c = b.jquery ? b : a(b), d = c.next();
            c.hasClass(i.detailShow) ? (c.removeClass(i.detailShow), d.hasClass(i.detail) && d.hide(), g.raise(j.rowCollapsed, {row: c[0]})) : (g.createOrUpdateDetailRow(c[0]), c.addClass(i.detailShow).next().show(), g.raise(j.rowExpanded, {row: c[0]}))
        }, g.removeRow = function (b) {
            var c = b.jquery ? b : a(b);
            c.hasClass(i.detail) && (c = c.prev());
            var d = c.next();
            c.data("detail_created") === !0 && d.remove(), c.remove(), g.raise(j.rowRemoved)
        }, g.appendRow = function (b) {
            var c = b.jquery ? b : a(b);
            a(g.table).find("tbody").append(c), g.redraw()
        }, g.getColumnFromTdIndex = function (b) {
            var c = null;
            for (var d in g.columns)if (a.inArray(b, g.columns[d].matches) >= 0) {
                c = g.columns[d];
                break
            }
            return c
        }, g.createOrUpdateDetailRow = function (b) {
            var c, d = a(b), e = d.next(), f = [];
            if (d.data("detail_created") === !0)return !0;
            if (d.is(":hidden"))return !1;
            if (g.raise(j.rowDetailUpdating, {row: d, detail: e}), d.find("> td:hidden").each(function () {
                    var b = a(this).index(), c = g.getColumnFromTdIndex(b), d = c.name;
                    if (c.ignore === !0)return !0;
                    b in c.names && (d = c.names[b]);
                    var e = a(this).attr("data-bind-name");
                    if (null != e && a(this).is(":empty")) {
                        var h = a("." + i.detailInnerValue + '[data-bind-value="' + e + '"]');
                        a(this).html(a(h).contents().detach())
                    }
                    var j;
                    return c.isEditable !== !1 && (c.isEditable || a(this).find(":input").length > 0) && (null == e && (e = "bind-" + a.now() + "-" + b, a(this).attr("data-bind-name", e)), j = a(this).contents().detach()), j || (j = a(this).contents().clone(!0, !0)), f.push({
                        name: d,
                        value: g.parse(this, c),
                        display: j,
                        group: c.group,
                        groupName: c.groupName,
                        bindName: e
                    }), !0
                }), 0 === f.length)return !1;
            var k = d.find("> td:visible").length, l = e.hasClass(i.detail);
            return l || (e = a('<tr class="' + i.detail + '"><td class="' + i.detailCell + '"><div class="' + i.detailInner + '"></div></td></tr>'), d.after(e)), e.find("> td:first").attr("colspan", k), c = e.find("." + i.detailInner).empty(), h.createDetail(c, f, h.createGroupedDetail, h.detailSeparator, i), d.data("detail_created", !0), g.raise(j.rowDetailUpdated, {
                row: d,
                detail: e
            }), !l
        }, g.raise = function (b, c) {
            g.options.debug === !0 && a.isFunction(g.options.log) && g.options.log(b, "event"), c = c || {};
            var d = {ft: g};
            a.extend(!0, d, c);
            var e = a.Event(b, d);
            return e.ft || a.extend(!0, e, d), a(g.table).trigger(e), e
        }, g.reset = function () {
            var b = a(g.table);
            b.removeData("footable_info").data("breakpoint", "").removeClass(i.loading).removeClass(i.loaded), b.find(h.toggleSelector).unbind(k.toggleRow).unbind("click.footable"), b.find("> tbody > tr").removeClass(i.detailShow), b.find("> tbody > tr." + i.detail).remove(), g.raise(j.reset)
        }, g.toggleInput = function (b) {
            var c = a(b).attr("data-bind-name");
            if (null != c) {
                var d = a("." + i.detailInnerValue + '[data-bind-value="' + c + '"]');
                null != d && (a(b).is(":visible") ? a(d).is(":empty") || a(b).html(a(d).contents().detach()) : a(b).is(":empty") || a(d).html(a(b).contents().detach()))
            }
        }, g.init(), g
    }

    b.footable = {
        options: {
            delay: 100,
            breakpoints: {phone: 480, tablet: 1024},
            parsers: {
                alpha: function (b) {
                    return a(b).data("value") || a.trim(a(b).text())
                }, numeric: function (b) {
                    var c = a(b).data("value") || a(b).text().replace(/[^0-9.\-]/g, "");
                    return c = parseFloat(c), isNaN(c) && (c = 0), c
                }
            },
            addRowToggle: !0,
            calculateWidthOverride: null,
            toggleSelector: " > tbody > tr:not(.footable-row-detail)",
            columnDataSelector: "> thead > tr:last-child > th, > thead > tr:last-child > td",
            detailSeparator: ":",
            toggleHTMLElement: "<span />",
            createGroupedDetail: function (a) {
                for (var b = {_none: {name: null, data: []}}, c = 0; a.length > c; c++) {
                    var d = a[c].group;
                    null !== d ? (d in b || (b[d] = {
                        name: a[c].groupName || a[c].group,
                        data: []
                    }), b[d].data.push(a[c])) : b._none.data.push(a[c])
                }
                return b
            },
            createDetail: function (b, c, d, e, f) {
                var g = d(c);
                for (var h in g)if (0 !== g[h].data.length) {
                    "_none" !== h && b.append('<div class="' + f.detailInnerGroup + '">' + g[h].name + "</div>");
                    for (var i = 0; g[h].data.length > i; i++) {
                        var j = g[h].data[i].name ? e : "";
                        b.append(a("<div></div>").addClass(f.detailInnerRow).append(a("<div></div>").addClass(f.detailInnerName).append(g[h].data[i].name + j)).append(a("<div></div>").addClass(f.detailInnerValue).attr("data-bind-value", g[h].data[i].bindName).append(g[h].data[i].display)))
                    }
                }
            },
            classes: {
                main: "footable",
                loading: "footable-loading",
                loaded: "footable-loaded",
                toggle: "footable-toggle",
                disabled: "footable-disabled",
                detail: "footable-row-detail",
                detailCell: "footable-row-detail-cell",
                detailInner: "footable-row-detail-inner",
                detailInnerRow: "footable-row-detail-row",
                detailInnerGroup: "footable-row-detail-group",
                detailInnerName: "footable-row-detail-name",
                detailInnerValue: "footable-row-detail-value",
                detailShow: "footable-detail-show"
            },
            triggers: {
                initialize: "footable_initialize",
                resize: "footable_resize",
                redraw: "footable_redraw",
                toggleRow: "footable_toggle_row",
                expandFirstRow: "footable_expand_first_row",
                expandAll: "footable_expand_all",
                collapseAll: "footable_collapse_all"
            },
            events: {
                alreadyInitialized: "footable_already_initialized",
                initializing: "footable_initializing",
                initialized: "footable_initialized",
                resizing: "footable_resizing",
                resized: "footable_resized",
                redrawn: "footable_redrawn",
                breakpoint: "footable_breakpoint",
                columnData: "footable_column_data",
                rowDetailUpdating: "footable_row_detail_updating",
                rowDetailUpdated: "footable_row_detail_updated",
                rowCollapsed: "footable_row_collapsed",
                rowExpanded: "footable_row_expanded",
                rowRemoved: "footable_row_removed",
                reset: "footable_reset"
            },
            debug: !1,
            log: null
        }, version: {
            major: 0, minor: 5, toString: function () {
                return b.footable.version.major + "." + b.footable.version.minor
            }, parse: function (a) {
                var b = /(\d+)\.?(\d+)?\.?(\d+)?/.exec(a);
                return {major: parseInt(b[1], 10) || 0, minor: parseInt(b[2], 10) || 0, patch: parseInt(b[3], 10) || 0}
            }
        }, plugins: {
            _validate: function (c) {
                if (!a.isFunction(c))return b.footable.options.debug === !0 && console.error('Validation failed, expected type "function", received type "{0}".', typeof c), !1;
                var d = new c;
                return "string" != typeof d.name ? (b.footable.options.debug === !0 && console.error('Validation failed, plugin does not implement a string property called "name".', d), !1) : a.isFunction(d.init) ? (b.footable.options.debug === !0 && console.log('Validation succeeded for plugin "' + d.name + '".', d), !0) : (b.footable.options.debug === !0 && console.error('Validation failed, plugin "' + d.name + '" does not implement a function called "init".', d), !1)
            }, registered: [], register: function (c, d) {
                b.footable.plugins._validate(c) && (b.footable.plugins.registered.push(c), "object" == typeof d && a.extend(!0, b.footable.options, d))
            }, load: function (a) {
                var c, d, e = [];
                for (d = 0; b.footable.plugins.registered.length > d; d++)try {
                    c = b.footable.plugins.registered[d], e.push(new c(a))
                } catch (f) {
                    b.footable.options.debug === !0 && console.error(f)
                }
                return e
            }, init: function (a) {
                for (var c = 0; a.plugins.length > c; c++)try {
                    a.plugins[c].init(a)
                } catch (d) {
                    b.footable.options.debug === !0 && console.error(d)
                }
            }
        }
    };
    var e = 0;
    a.fn.footable = function (c) {
        c = c || {};
        var f = a.extend(!0, {}, b.footable.options, c);
        return this.each(function () {
            e++;
            var b = new d(this, f, e);
            a(this).data("footable", b)
        })
    }
}(jQuery, window), function (a, b, c) {
    function d() {
        var b = this;
        b.name = "Footable Filter", b.init = function (c) {
            if (b.footable = c, c.options.filter.enabled === !0) {
                if (a(c.table).data("filter") === !1)return;
                c.timers.register("filter"), a(c.table).unbind(".filtering").bind({
                    "footable_initialized.filtering": function () {
                        var d = a(c.table), e = {
                            input: d.data("filter") || c.options.filter.input,
                            timeout: d.data("filter-timeout") || c.options.filter.timeout,
                            minimum: d.data("filter-minimum") || c.options.filter.minimum,
                            disableEnter: d.data("filter-disable-enter") || c.options.filter.disableEnter
                        };
                        e.disableEnter && a(e.input).keypress(function (a) {
                            return window.event ? 13 !== window.event.keyCode : 13 !== a.which
                        }), d.bind("footable_clear_filter", function () {
                            a(e.input).val(""), b.clearFilter()
                        }), d.bind("footable_filter", function (a, c) {
                            b.filter(c.filter)
                        }), a(e.input).keyup(function (d) {
                            c.timers.filter.stop(), 27 === d.which && a(e.input).val(""), c.timers.filter.start(function () {
                                var c = a(e.input).val() || "";
                                b.filter(c)
                            }, e.timeout)
                        })
                    }, "footable_redrawn.filtering": function () {
                        var d = a(c.table), e = d.data("filter-string");
                        e && b.filter(e)
                    }
                }).data("footable-filter", b)
            }
        }, b.filter = function (c) {
            var d = b.footable, e = a(d.table), f = e.data("filter-minimum") || d.options.filter.minimum, g = !c, h = d.raise("footable_filtering", {
                filter: c,
                clear: g
            });
            if (!(h && h.result === !1 || h.filter && f > h.filter.length))if (h.clear)b.clearFilter(); else {
                var i = h.filter.split(" ");
                e.find("> tbody > tr").hide().addClass("footable-filtered");
                var j = e.find("> tbody > tr:not(.footable-row-detail)");
                a.each(i, function (a, b) {
                    b && b.length > 0 && (e.data("current-filter", b), j = j.filter(d.options.filter.filterFunction))
                }), j.each(function () {
                    b.showRow(this, d), a(this).removeClass("footable-filtered")
                }), e.data("filter-string", h.filter), d.raise("footable_filtered", {filter: h.filter, clear: !1})
            }
        }, b.clearFilter = function () {
            var c = b.footable, d = a(c.table);
            d.find("> tbody > tr:not(.footable-row-detail)").removeClass("footable-filtered").each(function () {
                b.showRow(this, c)
            }), d.removeData("filter-string"), c.raise("footable_filtered", {clear: !0})
        }, b.showRow = function (b, c) {
            var d = a(b), e = d.next(), f = a(c.table);
            d.is(":visible") || (f.hasClass("breakpoint") && d.hasClass("footable-detail-show") && e.hasClass("footable-row-detail") ? (d.add(e).show(), c.createOrUpdateDetailRow(b)) : d.show())
        }
    }

    if (b.footable === c || null === b.footable)throw Error("Please check and make sure footable.js is included in the page and is loaded prior to this script.");
    var e = {
        filter: {
            enabled: !0,
            input: ".footable-filter",
            timeout: 300,
            minimum: 2,
            disableEnter: !1,
            filterFunction: function () {
                var b = a(this), c = b.parents("table:first"), d = c.data("current-filter").toUpperCase(), e = b.find("td").text();
                return c.data("filter-text-only") || b.find("td[data-value]").each(function () {
                    e += a(this).data("value")
                }), e.toUpperCase().indexOf(d) >= 0
            }
        }
    };
    b.footable.plugins.register(d, e)
}(jQuery, window), function (a, b, c) {
    function d(b) {
        var c = a(b.table), d = c.data();
        this.pageNavigation = d.pageNavigation || b.options.pageNavigation, this.pageSize = d.pageSize || b.options.pageSize, this.firstText = d.firstText || b.options.firstText, this.previousText = d.previousText || b.options.previousText, this.nextText = d.nextText || b.options.nextText, this.lastText = d.lastText || b.options.lastText, this.limitNavigation = parseInt(d.limitNavigation || b.options.limitNavigation || f.limitNavigation, 10), this.limitPreviousText = d.limitPreviousText || b.options.limitPreviousText, this.limitNextText = d.limitNextText || b.options.limitNextText, this.limit = this.limitNavigation > 0, this.currentPage = d.currentPage || 0, this.pages = [], this.control = !1
    }

    function e() {
        var b = this;
        b.name = "Footable Paginate", b.init = function (c) {
            if (c.options.paginate === !0) {
                if (a(c.table).data("page") === !1)return;
                b.footable = c, a(c.table).unbind(".paging").bind({
                    "footable_initialized.paging footable_row_removed.paging footable_redrawn.paging footable_sorted.paging footable_filtered.paging": function () {
                        b.setupPaging()
                    }
                }).data("footable-paging", b)
            }
        }, b.setupPaging = function () {
            var c = b.footable, e = a(c.table).find("> tbody");
            c.pageInfo = new d(c), b.createPages(c, e), b.createNavigation(c, e), b.fillPage(c, e, c.pageInfo.currentPage)
        }, b.createPages = function (b, c) {
            var d = 1, e = b.pageInfo, f = d * e.pageSize, g = [], h = [];
            e.pages = [];
            var i = c.find("> tr:not(.footable-filtered,.footable-row-detail)");
            i.each(function (a, b) {
                g.push(b), a === f - 1 ? (e.pages.push(g), d++, f = d * e.pageSize, g = []) : a >= i.length - i.length % e.pageSize && h.push(b)
            }), h.length > 0 && e.pages.push(h), e.currentPage >= e.pages.length && (e.currentPage = e.pages.length - 1), 0 > e.currentPage && (e.currentPage = 0), 1 === e.pages.length ? a(b.table).addClass("no-paging") : a(b.table).removeClass("no-paging")
        }, b.createNavigation = function (c) {
            var d = a(c.table).find(c.pageInfo.pageNavigation);
            if (0 === d.length) {
                if (d = a(c.pageInfo.pageNavigation), d.parents("table:first").length > 0 && d.parents("table:first") !== a(c.table))return;
                d.length > 1 && c.options.debug === !0 && console.error("More than one pagination control was found!")
            }
            if (0 !== d.length) {
                d.is("ul") || (0 === d.find("ul:first").length && d.append("<ul />"), d = d.find("ul")), d.find("li").remove();
                var e = c.pageInfo;
                e.control = d, e.pages.length > 0 && (d.append('<li class="footable-page-arrow"><a data-page="first" href="#first">' + c.pageInfo.firstText + "</a>"), d.append('<li class="footable-page-arrow"><a data-page="prev" href="#prev">' + c.pageInfo.previousText + "</a></li>"), e.limit && d.append('<li class="footable-page-arrow"><a data-page="limit-prev" href="#limit-prev">' + c.pageInfo.limitPreviousText + "</a></li>"), e.limit || a.each(e.pages, function (a, b) {
                    b.length > 0 && d.append('<li class="footable-page"><a data-page="' + a + '" href="#">' + (a + 1) + "</a></li>")
                }), e.limit && (d.append('<li class="footable-page-arrow"><a data-page="limit-next" href="#limit-next">' + c.pageInfo.limitNextText + "</a></li>"), b.createLimited(d, e, 0)), d.append('<li class="footable-page-arrow"><a data-page="next" href="#next">' + c.pageInfo.nextText + "</a></li>"), d.append('<li class="footable-page-arrow"><a data-page="last" href="#last">' + c.pageInfo.lastText + "</a></li>")), d.off("click", "a[data-page]").on("click", "a[data-page]", function (f) {
                    f.preventDefault();
                    var g = a(this).data("page"), h = e.currentPage;
                    if ("first" === g)h = 0; else if ("prev" === g)h > 0 && h--; else if ("next" === g)e.pages.length - 1 > h && h++; else if ("last" === g)h = e.pages.length - 1; else if ("limit-prev" === g) {
                        h = -1;
                        var i = d.find(".footable-page:first a").data("page");
                        b.createLimited(d, e, i - e.limitNavigation), b.setPagingClasses(d, e.currentPage, e.pages.length)
                    } else if ("limit-next" === g) {
                        h = -1;
                        var j = d.find(".footable-page:last a").data("page");
                        b.createLimited(d, e, j + 1), b.setPagingClasses(d, e.currentPage, e.pages.length)
                    } else h = g;
                    if (h >= 0) {
                        if (e.limit && e.currentPage != h) {
                            for (var k = h; 0 !== k % e.limitNavigation;)k -= 1;
                            b.createLimited(d, e, k)
                        }
                        b.paginate(c, h)
                    }
                }), b.setPagingClasses(d, e.currentPage, e.pages.length)
            }
        }, b.createLimited = function (a, b, c) {
            c = c || 0, a.find("li.footable-page").remove();
            var d, e, f = a.find('li.footable-page-arrow > a[data-page="limit-prev"]').parent(), g = a.find('li.footable-page-arrow > a[data-page="limit-next"]').parent();
            for (d = b.pages.length - 1; d >= 0; d--)e = b.pages[d], d >= c && c + b.limitNavigation > d && e.length > 0 && f.after('<li class="footable-page"><a data-page="' + d + '" href="#">' + (d + 1) + "</a></li>");
            0 === c ? f.hide() : f.show(), c + b.limitNavigation >= b.pages.length ? g.hide() : g.show()
        }, b.paginate = function (c, d) {
            var e = c.pageInfo;
            if (e.currentPage !== d) {
                var f = a(c.table).find("> tbody"), g = c.raise("footable_paging", {page: d, size: e.pageSize});
                if (g && g.result === !1)return;
                b.fillPage(c, f, d), e.control.find("li").removeClass("active disabled"), b.setPagingClasses(e.control, e.currentPage, e.pages.length)
            }
        }, b.setPagingClasses = function (a, b, c) {
            a.find("li.footable-page > a[data-page=" + b + "]").parent().addClass("active"), b >= c - 1 && (a.find('li.footable-page-arrow > a[data-page="next"]').parent().addClass("disabled"), a.find('li.footable-page-arrow > a[data-page="last"]').parent().addClass("disabled")), 1 > b && (a.find('li.footable-page-arrow > a[data-page="first"]').parent().addClass("disabled"), a.find('li.footable-page-arrow > a[data-page="prev"]').parent().addClass("disabled"))
        }, b.fillPage = function (c, d, e) {
            c.pageInfo.currentPage = e, a(c.table).data("currentPage", e), d.find("> tr").hide(), a(c.pageInfo.pages[e]).each(function () {
                b.showRow(this, c)
            }), c.raise("footable_page_filled")
        }, b.showRow = function (b, c) {
            var d = a(b), e = d.next(), f = a(c.table);
            f.hasClass("breakpoint") && d.hasClass("footable-detail-show") && e.hasClass("footable-row-detail") ? (d.add(e).show(), c.createOrUpdateDetailRow(b)) : d.show()
        }
    }

    if (b.footable === c || null === b.footable)throw Error("Please check and make sure footable.js is included in the page and is loaded prior to this script.");
    var f = {
        paginate: !0,
        pageSize: 10,
        pageNavigation: ".pagination",
        firstText: "&laquo;",
        previousText: "&lsaquo;",
        nextText: "&rsaquo;",
        lastText: "&raquo;",
        limitNavigation: 0,
        limitPreviousText: "...",
        limitNextText: "..."
    };
    b.footable.plugins.register(e, f)
}(jQuery, window), function (a, b, c) {
    function d() {
        var b = this;
        b.name = "Footable Sortable", b.init = function (d) {
            b.footable = d, d.options.sort === !0 && a(d.table).unbind(".sorting").bind({
                "footable_initialized.sorting": function () {
                    var c, e, f = a(d.table), g = (f.find("> tbody"), d.options.classes.sort);
                    if (f.data("sort") !== !1) {
                        f.find("> thead > tr:last-child > th, > thead > tr:last-child > td").each(function () {
                            var b = a(this), c = d.columns[b.index()];
                            c.sort.ignore === !0 || b.hasClass(g.sortable) || (b.addClass(g.sortable), a("<span />").addClass(g.indicator).appendTo(b))
                        }), f.find("> thead > tr:last-child > th." + g.sortable + ", > thead > tr:last-child > td." + g.sortable).unbind("click.footable").bind("click.footable", function (c) {
                            c.preventDefault(), e = a(this);
                            var d = !e.hasClass(g.sorted);
                            return b.doSort(e.index(), d), !1
                        });
                        var h = !1;
                        for (var i in d.columns)if (c = d.columns[i], c.sort.initial) {
                            var j = "descending" !== c.sort.initial;
                            b.doSort(c.index, j);
                            break
                        }
                        h && d.bindToggleSelectors()
                    }
                }, "footable_redrawn.sorting": function () {
                    var e = a(d.table), f = d.options.classes.sort;
                    e.data("sorted") >= 0 && e.find("> thead > tr:last-child > th").each(function (d) {
                        var e = a(this);
                        return e.hasClass(f.sorted) || e.hasClass(f.descending) ? (b.doSort(d), c) : c
                    })
                }, "footable_column_data.sorting": function (b) {
                    var c = a(b.column.th);
                    b.column.data.sort = b.column.data.sort || {}, b.column.data.sort.initial = c.data("sort-initial") || !1, b.column.data.sort.ignore = c.data("sort-ignore") || !1, b.column.data.sort.selector = c.data("sort-selector") || null;
                    var d = c.data("sort-match") || 0;
                    d >= b.column.data.matches.length && (d = 0), b.column.data.sort.match = b.column.data.matches[d]
                }
            }).data("footable-sort", b)
        }, b.doSort = function (d, e) {
            var f = b.footable;
            if (a(f.table).data("sort") !== !1) {
                var g = a(f.table), h = g.find("> tbody"), i = f.columns[d], j = g.find("> thead > tr:last-child > th:eq(" + d + ")"), k = f.options.classes.sort, l = f.options.events.sort;
                if (e = e === c ? j.hasClass(k.sorted) : "toggle" === e ? !j.hasClass(k.sorted) : e, i.sort.ignore === !0)return !0;
                var m = f.raise(l.sorting, {column: i, direction: e ? "ASC" : "DESC"});
                m && m.result === !1 || (g.data("sorted", i.index), g.find("> thead > tr:last-child > th, > thead > tr:last-child > td").not(j).removeClass(k.sorted + " " + k.descending), e === c && (e = j.hasClass(k.sorted)), e ? j.removeClass(k.descending).addClass(k.sorted) : j.removeClass(k.sorted).addClass(k.descending), b.sort(f, h, i, e), f.bindToggleSelectors(), f.raise(l.sorted, {
                    column: i,
                    direction: e ? "ASC" : "DESC"
                }))
            }
        }, b.rows = function (b, d, e) {
            var f = [];
            return d.find("> tr").each(function () {
                var d = a(this), g = null;
                if (d.hasClass(b.options.classes.detail))return !0;
                d.next().hasClass(b.options.classes.detail) && (g = d.next().get(0));
                var h = {row: d, detail: g};
                return e !== c && (h.value = b.parse(this.cells[e.sort.match], e)), f.push(h), !0
            }).detach(), f
        }, b.sort = function (a, c, d, e) {
            var f = b.rows(a, c, d), g = a.options.sorters[d.type] || a.options.sorters.alpha;
            f.sort(function (a, b) {
                return e ? g(a.value, b.value) : g(b.value, a.value)
            });
            for (var h = 0; f.length > h; h++)c.append(f[h].row), null !== f[h].detail && c.append(f[h].detail)
        }
    }

    if (b.footable === c || null === b.footable)throw Error("Please check and make sure footable.js is included in the page and is loaded prior to this script.");
    var e = {
        sort: !0,
        sorters: {
            alpha: function (a, b) {
                return "string" == typeof a && (a = a.toLowerCase()), "string" == typeof b && (b = b.toLowerCase()), a === b ? 0 : b > a ? -1 : 1
            }, numeric: function (a, b) {
                return a - b
            }
        },
        classes: {
            sort: {
                sortable: "footable-sortable",
                sorted: "footable-sorted",
                descending: "footable-sorted-desc",
                indicator: "footable-sort-indicator"
            }
        },
        events: {sort: {sorting: "footable_sorting", sorted: "footable_sorted"}}
    };
    b.footable.plugins.register(d, e)
}(jQuery, window), function (a) {
    "function" == typeof define && define.amd ? define(["jquery"], a) : a("object" == typeof exports ? require("jquery") : jQuery)
}(function (a, b) {
    function c(b, c) {
        this.element = a(b), this.wrapperElement = a(), this.toggleElement = a(), this.init(c)
    }

    var d = "plugin_hideShowPassword", e = ["show", "innerToggle"], f = 32, g = 13, h = function () {
        var a = document.body, b = document.createElement("input"), c = !0;
        a || (a = document.createElement("body")), b = a.appendChild(b);
        try {
            b.setAttribute("type", "text")
        } catch (d) {
            c = !1
        }
        return a.removeChild(b), c
    }(), i = {
        show: "infer",
        innerToggle: !1,
        enable: h,
        className: "hideShowPassword-field",
        initEvent: "hideShowPasswordInit",
        changeEvent: "passwordVisibilityChange",
        props: {autocapitalize: "off", autocomplete: "off", autocorrect: "off", spellcheck: "false"},
        toggle: {
            element: '<button type="button">',
            className: "hideShowPassword-toggle",
            touchSupport: "undefined" == typeof Modernizr ? !1 : Modernizr.touchevents,
            attachToEvent: "click.hideShowPassword",
            attachToTouchEvent: "touchstart.hideShowPassword mousedown.hideShowPassword",
            attachToKeyEvent: "keyup",
            attachToKeyCodes: !0,
            styles: {position: "absolute"},
            touchStyles: {pointerEvents: "none"},
            position: "infer",
            verticalAlign: "middle",
            offset: 0,
            attr: {role: "button", "aria-label": "Show Password", title: "Show Password", tabIndex: 0}
        },
        wrapper: {
            element: "<div>",
            className: "hideShowPassword-wrapper",
            enforceWidth: !0,
            styles: {position: "relative"},
            inheritStyles: ["display", "verticalAlign", "marginTop", "marginRight", "marginBottom", "marginLeft"],
            innerElementStyles: {marginTop: 0, marginRight: 0, marginBottom: 0, marginLeft: 0}
        },
        states: {
            shown: {
                className: "hideShowPassword-shown",
                changeEvent: "passwordShown",
                props: {type: "text"},
                toggle: {
                    className: "hideShowPassword-toggle-hide",
                    content: "Hide",
                    attr: {"aria-pressed": "true", title: "Hide Password"}
                }
            },
            hidden: {
                className: "hideShowPassword-hidden",
                changeEvent: "passwordHidden",
                props: {type: "password"},
                toggle: {
                    className: "hideShowPassword-toggle-show",
                    content: "Show",
                    attr: {"aria-pressed": "false", title: "Show Password"}
                }
            }
        }
    };
    c.prototype = {
        init: function (b) {
            this.update(b, i) && (this.element.addClass(this.options.className), this.options.innerToggle && (this.wrapElement(this.options.wrapper), this.initToggle(this.options.toggle), "string" == typeof this.options.innerToggle && (this.toggleElement.hide(), this.element.one(this.options.innerToggle, a.proxy(function () {
                this.toggleElement.show()
            }, this)))), this.element.trigger(this.options.initEvent, [this]))
        }, update: function (a, b) {
            return this.options = this.prepareOptions(a, b), this.updateElement() && this.element.trigger(this.options.changeEvent, [this]).trigger(this.state().changeEvent, [this]), this.options.enable
        }, toggle: function (a) {
            return a = a || "toggle", this.update({show: a})
        }, prepareOptions: function (b, c) {
            var d, e = b || {}, h = [];
            if (c = c || this.options, b = a.extend(!0, {}, c, b), e.hasOwnProperty("wrapper") && e.wrapper.hasOwnProperty("inheritStyles") && (b.wrapper.inheritStyles = e.wrapper.inheritStyles), b.enable && ("toggle" === b.show ? b.show = this.isType("hidden", b.states) : "infer" === b.show && (b.show = this.isType("shown", b.states)), "infer" === b.toggle.position && (b.toggle.position = "rtl" === this.element.css("text-direction") ? "left" : "right"), !a.isArray(b.toggle.attachToKeyCodes))) {
                if (b.toggle.attachToKeyCodes === !0)switch (d = a(b.toggle.element), d.prop("tagName").toLowerCase()) {
                    case"button":
                    case"input":
                        break;
                    case"a":
                        if (d.filter("[href]").length) {
                            h.push(f);
                            break
                        }
                    default:
                        h.push(f, g)
                }
                b.toggle.attachToKeyCodes = h
            }
            return b
        }, updateElement: function () {
            return !this.options.enable || this.isType() ? !1 : (this.element.prop(a.extend({}, this.options.props, this.state().props)).addClass(this.state().className).removeClass(this.otherState().className), this.updateToggle(), !0)
        }, isType: function (a, c) {
            return c = c || this.options.states, a = a || this.state(b, b, c).props.type, c[a] && (a = c[a].props.type), this.element.prop("type") === a
        }, state: function (a, c, d) {
            return d = d || this.options.states, a === b && (a = this.options.show), "boolean" == typeof a && (a = a ? "shown" : "hidden"), c && (a = "shown" === a ? "hidden" : "shown"), d[a]
        }, otherState: function (a) {
            return this.state(a, !0)
        }, wrapElement: function (b) {
            var c, d = b.enforceWidth;
            return this.wrapperElement.length || (c = this.element.outerWidth(), a.each(b.inheritStyles, a.proxy(function (a, c) {
                b.styles[c] = this.element.css(c)
            }, this)), this.element.css(b.innerElementStyles).wrap(a(b.element).addClass(b.className).css(b.styles)), this.wrapperElement = this.element.parent(), d === !0 && (d = this.wrapperElement.outerWidth() === c ? !1 : c), d !== !1 && this.wrapperElement.css("width", d)), this.wrapperElement
        }, initToggle: function (b) {
            return this.toggleElement.length || (this.toggleElement = a(b.element).attr(b.attr).addClass(b.className).css(b.styles).appendTo(this.wrapperElement), this.updateToggle(), this.positionToggle(b.position, b.verticalAlign, b.offset), b.touchSupport ? (this.toggleElement.css(b.touchStyles), this.element.on(b.attachToTouchEvent, a.proxy(this.toggleTouchEvent, this))) : this.toggleElement.on(b.attachToEvent, a.proxy(this.toggleEvent, this)), b.attachToKeyCodes.length && this.toggleElement.on(b.attachToKeyEvent, a.proxy(this.toggleKeyEvent, this))), this.toggleElement
        }, positionToggle: function (a, b, c) {
            var d = {};
            switch (d[a] = c, b) {
                case"top":
                case"bottom":
                    d[b] = c;
                    break;
                case"middle":
                    d.top = "50%", d.marginTop = this.toggleElement.outerHeight() / -2
            }
            return this.toggleElement.css(d)
        }, updateToggle: function (a, b) {
            var c, d;
            return this.toggleElement.length && (c = "padding-" + this.options.toggle.position, a = a || this.state().toggle, b = b || this.otherState().toggle, this.toggleElement.attr(a.attr).addClass(a.className).removeClass(b.className).html(a.content), d = this.toggleElement.outerWidth() + 2 * this.options.toggle.offset, this.element.css(c) !== d && this.element.css(c, d)), this.toggleElement
        }, toggleEvent: function (a) {
            a.preventDefault(), this.toggle()
        }, toggleKeyEvent: function (b) {
            a.each(this.options.toggle.attachToKeyCodes, a.proxy(function (a, c) {
                return b.which === c ? (this.toggleEvent(b), !1) : void 0
            }, this))
        }, toggleTouchEvent: function (a) {
            var b, c, d, e = this.toggleElement.offset().left;
            e && (b = a.pageX || a.originalEvent.pageX, "left" === this.options.toggle.position ? (e += this.toggleElement.outerWidth(), c = b, d = e) : (c = e, d = b), d >= c && this.toggleEvent(a))
        }
    }, a.fn.hideShowPassword = function () {
        var b = {};
        return a.each(arguments, function (c, d) {
            var f = {};
            if ("object" == typeof d)f = d; else {
                if (!e[c])return !1;
                f[e[c]] = d
            }
            a.extend(!0, b, f)
        }), this.each(function () {
            var e = a(this), f = e.data(d);
            f ? f.update(b) : e.data(d, new c(this, b))
        })
    }, a.each({show: !0, hide: !1, toggle: "toggle"}, function (b, c) {
        a.fn[b + "Password"] = function (a, b) {
            return this.hideShowPassword(c, a, b)
        }
    })
}), function (a) {
    "use strict";
    a(function () {
        if (a(".wpas-modal-trigger").featherlight(), a("#wpas_form_registration").on("change", 'input[name="wpas_pwdshow[]"]', function (b) {
                b.preventDefault(), a("#wpas_password").hideShowPassword(a(this).prop("checked"))
            }), "undefined" != typeof wpas && stringToBool(wpas.emailCheck) && a("#wpas_form_registration").length) {
            var b, c = a("#wpas_form_registration #wpas_email"), d = a('<div class="wpas-help-block" id="wpas_emailvalidation"></div>');
            d.appendTo(a("#wpas_email_wrapper")).hide(), c.on("change", function () {
                c.addClass("wpas-form-control-loading"), b = {
                    action: "email_validation", email: c.val()
                }, a.post(wpas.ajaxurl, b, function (a) {
                    d.html(a).show(), c.removeClass("wpas-form-control-loading")
                })
            }), d.on("click", "strong", function () {
                c.val(a(this).html()), d.hide()
            })
        }
    })
}(jQuery), function (a) {
    "use strict";
    a(function () {
        if ("undefined" != typeof wpas && a(".wpas-ticket-replies").length && a(".wpas-pagi").length) {
            var b = a(".wpas-ticket-replies tbody"), c = a(".wpas-pagi"), d = a(".wpas-pagi-loadmore"), e = a(".wpas-replies-current"), f = a(".wpas-replies-total"), g = {
                action: "wpas_load_replies",
                ticket_id: wpas.ticket_id,
                ticket_replies_total: 0
            }, h = a(".wpas-pagi-text").outerHeight();
            a(".wpas-pagi-loader").css({width: h, height: h});
            d.on("click", function (h) {
                h.preventDefault(), g.ticket_replies_total = b.find("tr.wpas-reply-single").length - 1, c.addClass("wpas-pagi-loading"), a.post(wpas.ajaxurl, g, function (g) {
                    g = a.parseJSON(g), c.removeClass("wpas-pagi-loading"), e.text(g.current), f.text(g.total), g.current == g.total && d.hide(), a(g.html).appendTo(b).addClass("wpas-reply-single-added").delay(900).queue(function () {
                        a(this).removeClass("wpas-reply-single-added").dequeue()
                    })
                })
            })
        }
    })
}(jQuery), jQuery(document).ready(function (a) {
    jQuery().select2 && a("select.wpas-select2").length && a("select.wpas-select2").select2()
}), function (a) {
    "use strict";
    a(function () {
        a(".wpas-reply-content").length && a(".wpas-reply-content").each(function (a, b) {
            b.innerHTML = Autolinker.link(b.innerHTML)
        });
        var b = a("#wpas-new-reply"), c = a('textarea[name="wpas_user_reply"]'), d = a('input[name="wpas_close_ticket"]');
        b.on("change", d, function () {
            c.is(":visible") && c.prop("required", d.is(":checked"))
        }), "undefined" != typeof tinyMCE ? a(".wpas-form").submit(function (b) {
            var c = a('[type="submit"]', a(this)), e = tinyMCE.activeEditor.getContent();
            return d.is(":checked") || "" !== e && null !== e ? void c.prop("disabled", !0).text(wpas.translations.onSubmit) : (a(tinyMCE.activeEditor.getBody()).css("background-color", "#ffeeee"), alert(wpas.translations.emptyEditor), a(tinyMCE.activeEditor.getBody()).css("background-color", ""), tinyMCE.activeEditor.focus(), !1)
        }) : a(".wpas-form").submit(function (b) {
            var c = a('[type="submit"]', a(this)), d = c.attr("data-onsubmit") ? c.attr("data-onsubmit") : wpas.translations.onSubmit;
            c.prop("disabled", !0).text(d)
        })
    })
}(jQuery), function (a) {
    "use strict";
    a(function () {
        function b() {
            var b = a(".wpas-filter-status");
            c.footable(), c.footable().bind("footable_filtering", function (a) {
                var c = b.find(":selected").val();
                c && c.length > 0 && (a.filter += a.filter && a.filter.length > 0 ? " " + c : c, a.clear = !a.filter)
            });
            var e = [], f = "";
            d.each(function (b, c) {
                var d = a(c).find(".wpas-label").text();
                -1 == e.indexOf(d) && (e.push(d), f += '<option value="' + d + '">' + d + "</option>")
            }), e.length > 1 ? b.append(f) : b.hide(), b.change(function (b) {
                b.preventDefault(), c.trigger("footable_filter", {filter: a("#wpas_filter").val()})
            }), a(".wpas-clear-filter").click(function (a) {
                a.preventDefault(), b.val(""), c.trigger("footable_clear_filter")
            })
        }

        var c = a("#wpas_ticketlist"), d = a("#wpas_ticketlist > tbody > tr"), e = d.length, f = a("#wpas_ticketlist_filters"), g = c.length && e >= 5 && a.fn.footable && "undefined" != typeof wpas;
        g ? b() : f.hide()
    })
}(jQuery), function (a) {
    "use strict";
    a(function () {
        if ("undefined" != typeof wpas && wpas.fileUploadMax) {
            var b = a("#wpas_files");
            b.on("change", function (c) {
                c.preventDefault();
                var d = [];
                a.each(b.get(0).files, function (a, b) {
                    b.size > wpas.fileUploadSize && d.push(b.name)
                }), 0 !== d.length && (alert(wpas.fileUploadMaxSizeError[0] + "\n\n" + d.join("\n") + ".\n\n" + wpas.fileUploadMaxSizeError[1]), clearFileInput(b[0])), parseInt(b.get(0).files.length) > parseInt(wpas.fileUploadMax, 10) && (alert(wpas.fileUploadMaxError), clearFileInput(b[0]))
            })
        }
    })
}(jQuery);
//# sourceMappingURL=public-dist.js.map