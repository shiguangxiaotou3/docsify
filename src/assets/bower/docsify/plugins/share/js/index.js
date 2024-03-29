const getCurrentScriptPath = () => {
  // 获取当前正在执行的 script 元素
  const script = document.currentScript || (() => {
    const scripts = document.getElementsByTagName('script');
    return scripts[scripts.length - 1];
  })();

  // 获取当前 script 元素的 src 属性
  const scriptSrc = script.src;

  // 使用 URL 对象获取路径
  const scriptUrl = new URL(scriptSrc);
  const scriptPath = scriptUrl.pathname;

  return scriptPath.replace(/\/[^/]+\/[^/]+$/, '');;
};

const currentScriptPath = getCurrentScriptPath();
console.log(currentScriptPath);
!function (e) {
    var t = {};

    function n(r) {
        if (t[r]) return t[r].exports;
        var a = t[r] = {i: r, l: !1, exports: {}};
        return e[r].call(a.exports, a, a.exports, n), a.l = !0, a.exports
    }

    n.m = e, n.c = t, n.d = function (e, t, r) {
        n.o(e, t) || Object.defineProperty(e, t, {enumerable: !0, get: r})
    }, n.r = function (e) {
        "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {value: "Module"}), Object.defineProperty(e, "__esModule", {value: !0})
    }, n.t = function (e, t) {
        if (1 & t && (e = n(e)), 8 & t) return e;
        if (4 & t && "object" == typeof e && e && e.__esModule) return e;
        var r = Object.create(null);
        if (n.r(r), Object.defineProperty(r, "default", {
            enumerable: !0,
            value: e
        }), 2 & t && "string" != typeof e) for (var a in e) n.d(r, a, function (t) {
            return e[t]
        }.bind(null, a));
        return r
    }, n.n = function (e) {
        var t = e && e.__esModule ? function () {
            return e.default
        } : function () {
            return e
        };
        return n.d(t, "a", t), t
    }, n.o = function (e, t) {
        return Object.prototype.hasOwnProperty.call(e, t)
    }, n.p = "", n(n.s = 1)
}([function (e, t, n) {
    "use strict";
    var r = this && this.__awaiter || function (e, t, n, r) {
        return new (n || (n = Promise))((function (a, s) {
            function o(e) {
                try {
                    l(r.next(e))
                } catch (e) {
                    s(e)
                }
            }

            function i(e) {
                try {
                    l(r.throw(e))
                } catch (e) {
                    s(e)
                }
            }

            function l(e) {
                var t;
                e.done ? a(e.value) : (t = e.value, t instanceof n ? t : new n((function (e) {
                    e(t)
                }))).then(o, i)
            }

            l((r = r.apply(e, t || [])).next())
        }))
    }, a = this && this.__generator || function (e, t) {
        var n, r, a, s, o = {
            label: 0, sent: function () {
                if (1 & a[0]) throw a[1];
                return a[1]
            }, trys: [], ops: []
        };
        return s = {
            next: i(0),
            throw: i(1),
            return: i(2)
        }, "function" == typeof Symbol && (s[Symbol.iterator] = function () {
            return this
        }), s;

        function i(s) {
            return function (i) {
                return function (s) {
                    if (n) throw new TypeError("Generator is already executing.");
                    for (; o;) try {
                        if (n = 1, r && (a = 2 & s[0] ? r.return : s[0] ? r.throw || ((a = r.return) && a.call(r), 0) : r.next) && !(a = a.call(r, s[1])).done) return a;
                        switch (r = 0, a && (s = [2 & s[0], a.value]), s[0]) {
                            case 0:
                            case 1:
                                a = s;
                                break;
                            case 4:
                                return o.label++, {value: s[1], done: !1};
                            case 5:
                                o.label++, r = s[1], s = [0];
                                continue;
                            case 7:
                                s = o.ops.pop(), o.trys.pop();
                                continue;
                            default:
                                if (!(a = o.trys, (a = a.length > 0 && a[a.length - 1]) || 6 !== s[0] && 2 !== s[0])) {
                                    o = 0;
                                    continue
                                }
                                if (3 === s[0] && (!a || s[1] > a[0] && s[1] < a[3])) {
                                    o.label = s[1];
                                    break
                                }
                                if (6 === s[0] && o.label < a[1]) {
                                    o.label = a[1], a = s;
                                    break
                                }
                                if (a && o.label < a[2]) {
                                    o.label = a[2], o.ops.push(s);
                                    break
                                }
                                a[2] && o.ops.pop(), o.trys.pop();
                                continue
                        }
                        s = t.call(e, o)
                    } catch (e) {
                        s = [6, e], r = 0
                    } finally {
                        n = a = 0
                    }
                    if (5 & s[0]) throw s[1];
                    return {value: s[0] ? s[1] : void 0, done: !0}
                }([s, i])
            }
        }
    };

    function s(e, t, n, r) {
        return ['<a href="', e, '" target="_blank" class="', t, '" tooltip="', n, '"><i class="', r, '"></i></a>'].join("")
    }

    Object.defineProperty(t, "__esModule", {value: !0}), t.install = void 0, t.install = function (e, t) {
        var n, o, i, l, c, u, f, h, d, p,
            b = null != t.config.share.facebook ? s("https://www.facebook.com/sharer.php?u=" + (null !== (n = t.config.share.facebook.url) && void 0 !== n ? n : window.location.href), "fab indigo", "Facebook", "fa fa-facebook animated") : "",
            g = null != t.config.share.reddit ? s("https://reddit.com/submit?url=" + (null !== (o = t.config.share.reddit.url) && void 0 !== o ? o : window.location.href) + "&title=" + (null !== (i = t.config.share.reddit.title) && void 0 !== i ? i : document.title), "fab red", "Reddit", "fa fa-reddit animated") : "",
            m = null != t.config.share.twitter ? s("https://twitter.com/intent/tweet?url=" + (null !== (l = t.config.share.twitter.url) && void 0 !== l ? l : window.location.href) + "&text=" + (null !== (c = t.config.share.twitter.title) && void 0 !== c ? c : document.title), "fab light-blue", "Twitter", "fa fa-twitter animated") : "",
            y = null != t.config.share.linkedin ? s("https://www.linkedin.com/sharing/share-offsite/?url=" + (null !== (u = t.config.share.linkedin.url) && void 0 !== u ? u : window.location.href), "fab blue-linkedin", "Linked In", "fa fa-linkedin animated") : "",
            w = null != t.config.share.whatsapp ? s("whatsapp://send?text=" + (null !== (f = t.config.share.whatsapp.title) && void 0 !== f ? f : document.title) + "%20" + (null !== (h = t.config.share.whatsapp.url) && void 0 !== h ? h : window.location.href), "fab green", "Whatsapp", "fa fa-whatsapp animated") : "",
            v = null != t.config.share.telegram ? s("https://telegram.me/share/url?url=&" + (null !== (d = t.config.share.telegram.url) && void 0 !== d ? d : window.location.href) + "text=" + (null !== (p = t.config.share.telegram.title) && void 0 !== p ? p : document.title), "fab black", "Telegram", "fa fa-telegram animated") : "",
            k = '<link rel="stylesheet" href="'+currentScriptPath+'/css/font-awesome.min.css">',
            _ = [k, '<link rel="stylesheet" href="'+currentScriptPath+'/css/docsify-share.min.css">', '<div class="fabs">', g, y, b, m, w, v, '<a target="_blank" class="fab light-green big-fab" tooltip="Share"><i class="fa fa-share-alt"></i></a>', "</div>"].join("");
        if (null != t.config.share.options) {
            if (null != t.config.share.options.theme) if ("open-window" == t.config.share.options.theme) _ = [k, '<link rel="stylesheet" href="'+currentScriptPath+'/css/docsify-share-open-window.min.css">', '<div class="share-button">', '<div class="share-button__back">', g, y, b, m, w, v, "</div>", '<div class="share-button__front">', '<p class="share-button__text"><span class="fa fa-share-alt"></span></p>', "</div>", "</div>"].join(""); else if ("slide-bar" == t.config.share.options.theme) _ = [k, '<link rel="stylesheet" href="'+currentScriptPath+'/css/docsify-share-slide-bar.min.css">', '<button class="btn-share">', g, y, b, m, w, v, '<span class="btn-fab"><span class="fa fa-share-alt"></span></span>', "</button>"].join(""); else if ("flip-it" == t.config.share.options.theme) _ = [k, '<link rel="stylesheet" href="'+currentScriptPath+'/css/docsify-share-flip-it.min.css">', '<div class="fab-container">', '<div class="btn-fab"><span class="fa fa-share-alt"></span></div>', '<div class="back">', g, y, b, m, w, v, "</div>", "</div>"].join("");
            null != t.config.share.options.color && function (e) {
                r(this, void 0, void 0, (function () {
                    var t, n, r, s, o, i, l, c, u, f, h, d, p, b, g, m, y, w, v, k, _, j, x, O;
                    return a(this, (function (a) {
                        switch (a.label) {
                            case 0:
                                return [4, (P = 300, new Promise((function (e) {
                                    return setTimeout(e, P)
                                })))];
                            case 1:
                                return a.sent(), [4, document.getElementsByClassName("big-fab")];
                            case 2:
                                return t = a.sent(), [4, document.getElementsByClassName("share-button__back")];
                            case 3:
                                return n = a.sent(), [4, document.getElementsByClassName("share-button__front")];
                            case 4:
                                return r = a.sent(), null == e.options.theme ? [3, 21] : "open-window" != e.options.theme ? [3, 10] : [4, document.getElementsByClassName("animated")];
                            case 5:
                                if (0 == (s = a.sent()).length) return [3, 9];
                                o = 0, i = Array.from(s), a.label = 6;
                            case 6:
                                return o < i.length ? (x = i[o], l = x.style, [4, e.options.color]) : [3, 9];
                            case 7:
                                l.color = a.sent(), a.label = 8;
                            case 8:
                                return o++, [3, 6];
                            case 9:
                                return [3, 21];
                            case 10:
                                return "slide-bar" != e.options.theme ? [3, 16] : [4, document.getElementsByClassName("btn-fab")];
                            case 11:
                                if (0 == (h = a.sent()).length) return [3, 15];
                                c = 0, u = Array.from(h), a.label = 12;
                            case 12:
                                return c < u.length ? (x = u[c], f = x.style, [4, e.options.color]) : [3, 15];
                            case 13:
                                f.background = a.sent(), a.label = 14;
                            case 14:
                                return c++, [3, 12];
                            case 15:
                                return [3, 21];
                            case 16:
                                return "flip-it" != e.options.theme ? [3, 21] : [4, document.getElementsByClassName("btn-fab")];
                            case 17:
                                if (0 == (h = a.sent()).length) return [3, 21];
                                d = 0, p = Array.from(h), a.label = 18;
                            case 18:
                                return d < p.length ? (x = p[d], b = x.style, [4, e.options.color]) : [3, 21];
                            case 19:
                                b.background = a.sent(), a.label = 20;
                            case 20:
                                return d++, [3, 18];
                            case 21:
                                if (0 == t.length) return [3, 25];
                                g = 0, m = Array.from(t), a.label = 22;
                            case 22:
                                return g < m.length ? (x = m[g], y = x.style, [4, e.options.color]) : [3, 25];
                            case 23:
                                y.background = a.sent(), a.label = 24;
                            case 24:
                                return g++, [3, 22];
                            case 25:
                                if (0 == n.length) return [3, 29];
                                w = 0, v = Array.from(n), a.label = 26;
                            case 26:
                                return w < v.length ? (x = v[w], k = x.style, [4, e.options.color]) : [3, 29];
                            case 27:
                                k.background = a.sent(), a.label = 28;
                            case 28:
                                return w++, [3, 26];
                            case 29:
                                if (0 == r.length) return [3, 33];
                                _ = 0, j = Array.from(r), a.label = 30;
                            case 30:
                                return _ < j.length ? (x = j[_], O = x.style, [4, e.options.color]) : [3, 33];
                            case 31:
                                O.background = a.sent(), a.label = 32;
                            case 32:
                                return _++, [3, 30];
                            case 33:
                                return [2]
                        }
                        var P
                    }))
                }))
            }(t.config.share)
        }
        Object.assign({}, t.config.share);
        e.afterEach((function (e) {
            return e + _
        }))
    }
}, function (e, t, n) {
    "use strict";
    var r = this && this.__createBinding || (Object.create ? function (e, t, n, r) {
        void 0 === r && (r = n), Object.defineProperty(e, r, {
            enumerable: !0, get: function () {
                return t[n]
            }
        })
    } : function (e, t, n, r) {
        void 0 === r && (r = n), e[r] = t[n]
    }), a = this && this.__exportStar || function (e, t) {
        for (var n in e) "default" === n || t.hasOwnProperty(n) || r(t, e, n)
    };
    Object.defineProperty(t, "__esModule", {value: !0});
    var s = n(0);
    window.$docsify || (window.$docsify = {}), window.$docsify.plugins = (window.$docsify.plugins || []).concat(s.install), a(n(0), t)
}]);
