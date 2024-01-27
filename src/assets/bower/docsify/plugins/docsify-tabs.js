/*!
 * docsify-tabs
 * v1.6.0
 * https://jhildenbiddle.github.io/docsify-tabs/
 * (c) 2018-2022 John Hildenbiddle
 * MIT license
 */
(function() {
    "use strict";
    var version = "1.6.0";
    function styleInject(css, ref) {
        if (ref === void 0) ref = {};
        var insertAt = ref.insertAt;
        if (!css || typeof document === "undefined") {
            return;
        }
        var head = document.head || document.getElementsByTagName("head")[0];
        var style = document.createElement("style");
        style.type = "text/css";
        if (insertAt === "top") {
            if (head.firstChild) {
                head.insertBefore(style, head.firstChild);
            } else {
                head.appendChild(style);
            }
        } else {
            head.appendChild(style);
        }
        if (style.styleSheet) {
            style.styleSheet.cssText = css;
        } else {
            style.appendChild(document.createTextNode(css));
        }
    }
    var css_248z = ':root{--docsifytabs-border-color:#ededed;--docsifytabs-border-px:1px;--docsifytabs-border-radius-px: ;--docsifytabs-margin:1.5em 0;--docsifytabs-tab-background:#f8f8f8;--docsifytabs-tab-background--active:var(--docsifytabs-content-background);--docsifytabs-tab-color:#999;--docsifytabs-tab-color--active:inherit;--docsifytabs-tab-highlight-px:3px;--docsifytabs-tab-highlight-color:var(--theme-color,currentColor);--docsifytabs-tab-padding:0.6em 1em;--docsifytabs-content-background:inherit;--docsifytabs-content-padding:1.5rem}.docsify-tabs:before,.docsify-tabs__tab{z-index:1}.docsify-tabs__tab--active,.docsify-tabs__tab:focus{z-index:2}.docsify-tabs{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;position:relative}.docsify-tabs:before{-ms-flex-order:0;content:"";-ms-flex:1 1;flex:1 1;order:0}.docsify-tabs__tab{-ms-flex-order:-1;appearance:none;font-size:inherit;margin:0;order:-1;position:relative}.docsify-tabs__content[class]{height:0;overflow:hidden;position:absolute;visibility:hidden;width:100%}.docsify-tabs__content[class]>:first-child{margin-top:0}.docsify-tabs__content[class]>:last-child{margin-bottom:0}.docsify-tabs__tab--active+.docsify-tabs__content[class]{height:auto;overflow:auto;position:relative;visibility:visible}[class*=docsify-tabs--]{margin:1.5em 0;margin:var(--docsifytabs-margin)}[class*=docsify-tabs--]>.docsify-tabs__tab{background:#f8f8f8;background:var(--docsifytabs-tab-background);color:#999;color:var(--docsifytabs-tab-color);padding:.6em 1em;padding:var(--docsifytabs-tab-padding)}[class*=docsify-tabs--]>.docsify-tabs__tab--active{background:inherit;background:var(--docsifytabs-tab-background--active);color:inherit;color:var(--docsifytabs-tab-color--active)}[class*=docsify-tabs--]>.docsify-tabs__content{background:inherit;background:var(--docsifytabs-content-background)}[class*=docsify-tabs--]>.docsify-tabs__tab--active+.docsify-tabs__content{padding:1.5rem;padding:var(--docsifytabs-content-padding)}.docsify-tabs--classic:before,.docsify-tabs--classic>.docsify-tabs__content,.docsify-tabs--classic>.docsify-tabs__tab{border-color:#ededed;border-color:var(--docsifytabs-border-color);border-style:solid;border-width:1px;border-width:var(--docsifytabs-border-px)}.docsify-tabs--classic:before{border-left-width:0;border-right-width:0;border-top-width:0;margin-right:1px;margin-right:var(--docsifytabs-border-px)}.docsify-tabs--classic>.docsify-tabs__tab:first-of-type{border-top-left-radius:var(--docsifytabs-border-radius-px)}.docsify-tabs--classic>.docsify-tabs__tab:last-of-type{border-top-right-radius:var(--docsifytabs-border-radius-px)}.docsify-tabs--classic>.docsify-tabs__tab~.docsify-tabs__tab{margin-left:-1px;margin-left:calc(0px - var(--docsifytabs-border-px))}.docsify-tabs--classic>.docsify-tabs__tab--active{border-bottom-width:0;box-shadow:inset 0 3px 0 0 currentColor;box-shadow:inset 0 var(--docsifytabs-tab-highlight-px) 0 0 var(--docsifytabs-tab-highlight-color)}.docsify-tabs--classic>.docsify-tabs__content{border-radius:0;border-radius:0 var(--docsifytabs-border-radius-px) var(--docsifytabs-border-radius-px) var(--docsifytabs-border-radius-px);border-top:0;margin-top:-1px;margin-top:calc(0px - var(--docsifytabs-border-px))}.docsify-tabs--material>.docsify-tabs__tab{background:transparent;border:0;margin-bottom:2px;margin-bottom:calc(var(--docsifytabs-tab-highlight-px) - var(--docsifytabs-border-px))}.docsify-tabs--material>.docsify-tabs__tab--active{background:transparent;box-shadow:0 3px 0 0 currentColor;box-shadow:0 var(--docsifytabs-tab-highlight-px) 0 0 var(--docsifytabs-tab-highlight-color)}.docsify-tabs--material>.docsify-tabs__content{border-color:#ededed;border-color:var(--docsifytabs-border-color);border-style:solid;border-width:1px 0;border-width:var(--docsifytabs-border-px) 0}';
    styleInject(css_248z, {
        insertAt: "top"
    });
    var commentReplaceMark = "tabs:replace";
    var classNames = {
        tabsContainer: "content",
        tabBlock: "docsify-tabs",
        tabButton: "docsify-tabs__tab",
        tabButtonActive: "docsify-tabs__tab--active",
        tabContent: "docsify-tabs__content"
    };
    var regex = {
        codeMarkup: /(```[\s\S]*?```)/gm,
        commentReplaceMarkup: new RegExp("\x3c!-- ".concat(commentReplaceMark, " (.*?) --\x3e")),
        tabBlockMarkup: /( *)(<!-+\s+tabs:\s*?start\s+-+>)(?:(?!(<!-+\s+tabs:\s*?(?:start|end)\s+-+>))[\s\S])*(<!-+\s+tabs:\s*?end\s+-+>)/,
        tabCommentMarkup: /[\r\n]*(\s*)<!-+\s+tab:\s*(.*)\s+-+>[\r\n]+([\s\S]*?)[\r\n]*\s*(?=<!-+\s+tabs?:(?!replace))/m,
        tabHeadingMarkup: /[\r\n]*(\s*)#{1,6}\s*[*_]{2}\s*(.*[^\s])\s*[*_]{2}[\r\n]+([\s\S]*?)(?=#{1,6}\s*[*_]{2}|<!-+\s+tabs:\s*?end\s+-+>)/m
    };
    var settings = {
        persist: true,
        sync: true,
        theme: "classic",
        tabComments: true,
        tabHeadings: true
    };
    var storageKeys = {
        get persist() {
            return "docsify-tabs.persist.".concat(window.location.pathname);
        },
        sync: "docsify-tabs.sync"
    };
    function getClosest(elm, closestSelectorString) {
        if (Element.prototype.closest) {
            return elm.closest(closestSelectorString);
        }
        while (elm) {
            var isMatch = matchSelector(elm, closestSelectorString);
            if (isMatch) {
                return elm;
            }
            elm = elm.parentNode || null;
        }
        return elm;
    }
    function matchSelector(elm, selectorString) {
        var matches = Element.prototype.matches || Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
        return matches.call(elm, selectorString);
    }
    function renderTabsStage1(content, vm) {
        var codeBlockMatch = content.match(regex.codeMarkup) || [];
        var codeBlockMarkers = codeBlockMatch.map((function(item, i) {
            var codeMarker = "\x3c!-- ".concat(commentReplaceMark, " CODEBLOCK").concat(i, " --\x3e");
            content = content.replace(item, (function() {
                return codeMarker;
            }));
            return codeMarker;
        }));
        var tabTheme = settings.theme ? "".concat(classNames.tabBlock, "--").concat(settings.theme) : "";
        var tempElm = document.createElement("div");
        var tabBlockMatch = content.match(regex.tabBlockMarkup);
        var tabIndex = 1;
        var _loop = function _loop() {
            var tabBlockOut = tabBlockMatch[0];
            var tabBlockIndent = tabBlockMatch[1];
            var tabBlockStart = tabBlockMatch[2];
            var tabBlockEnd = tabBlockMatch[4];
            var hasTabComments = settings.tabComments && regex.tabCommentMarkup.test(tabBlockOut);
            var hasTabHeadings = settings.tabHeadings && regex.tabHeadingMarkup.test(tabBlockOut);
            var tabMatch = void 0;
            var tabStartReplacement = "";
            var tabEndReplacement = "";
            if (hasTabComments || hasTabHeadings) {
                tabStartReplacement = "\x3c!-- ".concat(commentReplaceMark, ' <div class="').concat([ classNames.tabBlock, tabTheme ].join(" "), '"> --\x3e');
                tabEndReplacement = "\n".concat(tabBlockIndent, "\x3c!-- ").concat(commentReplaceMark, " </div> --\x3e");
                var _loop2 = function _loop2() {
                    tempElm.innerHTML = tabMatch[2].trim() ? vm.compiler.compile(tabMatch[2]).replace(/<\/?p>/g, "") : "Tab ".concat(tabIndex);
                    var tabTitle = tempElm.innerHTML;
                    var tabContent = (tabMatch[3] || "").trim();
                    var tabData = (tempElm.textContent || tempElm.firstChild.getAttribute("alt") || tempElm.firstChild.getAttribute("src")).trim().toLowerCase();
                    tabBlockOut = tabBlockOut.replace(tabMatch[0], (function() {
                        return [ "\n".concat(tabBlockIndent, "\x3c!-- ").concat(commentReplaceMark, ' <button class="').concat(classNames.tabButton, '" data-tab="').concat(tabData, '">').concat(tabTitle, "</button> --\x3e"), "\n".concat(tabBlockIndent, "\x3c!-- ").concat(commentReplaceMark, ' <div class="').concat(classNames.tabContent, '" data-tab-content="').concat(tabData, '"> --\x3e'), "\n\n".concat(tabBlockIndent).concat(tabContent), "\n\n".concat(tabBlockIndent, "\x3c!-- ").concat(commentReplaceMark, " </div> --\x3e") ].join("");
                    }));
                    tabIndex++;
                };
                while ((tabMatch = (settings.tabComments ? regex.tabCommentMarkup.exec(tabBlockOut) : null) || (settings.tabHeadings ? regex.tabHeadingMarkup.exec(tabBlockOut) : null)) !== null) {
                    _loop2();
                }
            }
            tabBlockOut = tabBlockOut.replace(tabBlockStart, (function() {
                return tabStartReplacement;
            }));
            tabBlockOut = tabBlockOut.replace(tabBlockEnd, (function() {
                return tabEndReplacement;
            }));
            content = content.replace(tabBlockMatch[0], (function() {
                return tabBlockOut;
            }));
            tabBlockMatch = content.match(regex.tabBlockMarkup);
        };
        while (tabBlockMatch) {
            _loop();
        }
        codeBlockMarkers.forEach((function(item, i) {
            content = content.replace(item, (function() {
                return codeBlockMatch[i];
            }));
        }));
        return content;
    }
    function renderTabsStage2(html) {
        var tabReplaceMatch;
        var _loop3 = function _loop3() {
            var tabComment = tabReplaceMatch[0];
            var tabReplacement = tabReplaceMatch[1] || "";
            html = html.replace(tabComment, (function() {
                return tabReplacement;
            }));
        };
        while ((tabReplaceMatch = regex.commentReplaceMarkup.exec(html)) !== null) {
            _loop3();
        }
        return html;
    }
    function setDefaultTabs() {
        var tabsContainer = document.querySelector(".".concat(classNames.tabsContainer));
        var tabBlocks = tabsContainer ? Array.apply(null, tabsContainer.querySelectorAll(".".concat(classNames.tabBlock))) : [];
        var tabStoragePersist = JSON.parse(sessionStorage.getItem(storageKeys.persist)) || {};
        var tabStorageSync = JSON.parse(sessionStorage.getItem(storageKeys.sync)) || [];
        setActiveTabFromAnchor();
        tabBlocks.forEach((function(tabBlock, index) {
            var activeButton = Array.apply(null, tabBlock.children).filter((function(elm) {
                return matchSelector(elm, ".".concat(classNames.tabButtonActive));
            }))[0];
            if (!activeButton) {
                if (settings.sync && tabStorageSync.length) {
                    activeButton = tabStorageSync.map((function(label) {
                        return Array.apply(null, tabBlock.children).filter((function(elm) {
                            return matchSelector(elm, ".".concat(classNames.tabButton, '[data-tab="').concat(label, '"]'));
                        }))[0];
                    })).filter((function(elm) {
                        return elm;
                    }))[0];
                }
                if (!activeButton && settings.persist) {
                    activeButton = tabBlock.querySelector(Array.apply(null, tabBlock.children).filter((function(elm) {
                        return matchSelector(elm, ".".concat(classNames.tabButton, '[data-tab="').concat(tabStoragePersist[index], '"]'));
                    }))[0]);
                }
                activeButton = activeButton || tabBlock.querySelector(".".concat(classNames.tabButton));
                activeButton && activeButton.classList.add(classNames.tabButtonActive);
            }
        }));
    }
    function setActiveTab(elm) {
        var _isMatchingTabSync = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
        var activeButton = getClosest(elm, ".".concat(classNames.tabButton));
        if (activeButton) {
            var activeButtonLabel = activeButton.getAttribute("data-tab");
            var tabsContainer = document.querySelector(".".concat(classNames.tabsContainer));
            var tabBlock = activeButton.parentNode;
            var tabButtons = Array.apply(null, tabBlock.children).filter((function(elm) {
                return matchSelector(elm, "button");
            }));
            var tabBlockOffset = tabBlock.offsetTop;
            tabButtons.forEach((function(buttonElm) {
                return buttonElm.classList.remove(classNames.tabButtonActive);
            }));
            activeButton.classList.add(classNames.tabButtonActive);
            if (!_isMatchingTabSync) {
                if (settings.persist) {
                    var tabBlocks = tabsContainer ? Array.apply(null, tabsContainer.querySelectorAll(".".concat(classNames.tabBlock))) : [];
                    var tabBlockIndex = tabBlocks.indexOf(tabBlock);
                    var tabStorage = JSON.parse(sessionStorage.getItem(storageKeys.persist)) || {};
                    tabStorage[tabBlockIndex] = activeButtonLabel;
                    sessionStorage.setItem(storageKeys.persist, JSON.stringify(tabStorage));
                }
                if (settings.sync) {
                    var tabButtonMatches = tabsContainer ? Array.apply(null, tabsContainer.querySelectorAll(".".concat(classNames.tabButton, '[data-tab="').concat(activeButtonLabel, '"]'))) : [];
                    var _tabStorage = JSON.parse(sessionStorage.getItem(storageKeys.sync)) || [];
                    tabButtonMatches.forEach((function(tabButtonMatch) {
                        setActiveTab(tabButtonMatch, true);
                    }));
                    window.scrollBy(0, 0 - (tabBlockOffset - tabBlock.offsetTop));
                    if (_tabStorage.indexOf(activeButtonLabel) > 0) {
                        _tabStorage.splice(_tabStorage.indexOf(activeButtonLabel), 1);
                    }
                    if (_tabStorage.indexOf(activeButtonLabel) !== 0) {
                        _tabStorage.unshift(activeButtonLabel);
                        sessionStorage.setItem(storageKeys.sync, JSON.stringify(_tabStorage));
                    }
                }
            }
        }
    }
    function setActiveTabFromAnchor() {
        var anchorID = decodeURIComponent((window.location.hash.match(/(?:id=)([^&]+)/) || [])[1]);
        var anchorSelector = anchorID && ".".concat(classNames.tabBlock, " #").concat(anchorID);
        var isAnchorElmInTabBlock = anchorID && document.querySelector(anchorSelector);
        if (isAnchorElmInTabBlock) {
            var anchorElm = document.querySelector("#".concat(anchorID));
            var tabContent;
            if (anchorElm.closest) {
                tabContent = anchorElm.closest(".".concat(classNames.tabContent));
            } else {
                tabContent = anchorElm.parentNode;
                while (tabContent !== document.body && !tabContent.classList.contains("".concat(classNames.tabContent))) {
                    tabContent = tabContent.parentNode;
                }
            }
            setActiveTab(tabContent.previousElementSibling);
        }
    }
    function docsifyTabs(hook, vm) {
        var hasTabs = false;
        hook.beforeEach((function(content) {
            hasTabs = regex.tabBlockMarkup.test(content);
            if (hasTabs) {
                content = renderTabsStage1(content, vm);
            }
            return content;
        }));
        hook.afterEach((function(html, next) {
            if (hasTabs) {
                html = renderTabsStage2(html);
            }
            next(html);
        }));
        hook.doneEach((function() {
            if (hasTabs) {
                setDefaultTabs();
            }
        }));
        hook.mounted((function() {
            var tabsContainer = document.querySelector(".".concat(classNames.tabsContainer));
            tabsContainer && tabsContainer.addEventListener("click", (function handleTabClick(evt) {
                setActiveTab(evt.target);
            }));
            window.addEventListener("hashchange", setActiveTabFromAnchor, false);
        }));
    }
    if (window) {
        window.$docsify = window.$docsify || {};
        window.$docsify.tabs = window.$docsify.tabs || {};
        Object.keys(window.$docsify.tabs).forEach((function(key) {
            if (Object.prototype.hasOwnProperty.call(settings, key)) {
                settings[key] = window.$docsify.tabs[key];
            }
        }));
        window.$docsify.tabs.version = version;
        if (settings.tabComments || settings.tabHeadings) {
            window.$docsify.plugins = [].concat(window.$docsify.plugins || [], docsifyTabs);
        }
    }
})();
//# sourceMappingURL=docsify-tabs.js.map
