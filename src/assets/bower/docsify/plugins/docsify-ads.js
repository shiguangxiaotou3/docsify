(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define([], factory);
	else if(typeof exports === 'object')
		exports["DocsifyAds"] = factory();
	else
		root["DocsifyAds"] = factory();
})(this, () => {
return /******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};

;// CONCATENATED MODULE: ./src/main.js
function install (hook, vm) {
    hook.doneEach(function () {
        if(!window.$docsify.ads){
            console.warn("[docsify-ads] ads not config")
            return ;
        }
        var ads = window.$docsify.ads;
        var theOne = ads[0];
        if(ads.length > 1){
            var randIndex =  Math.floor(Math.random() * ads.length);
            console.log("[docsify-ads] ads random index="+randIndex)
            theOne = ads[randIndex]
        }
        const sidebarEl = document.querySelector(".sidebar-nav");
        var divEl = document.createElement("div");
        divEl.innerHTML = `<a target='_blank' href='${theOne.href}'><img src='${theOne.img}'/></a>`;
        sidebarEl.insertBefore(divEl,sidebarEl.firstChild);
        console.info("docsify-ads render success!")
    })
}

// function injectStyle() {
//     const styleEl = document.createElement("style");
//     styleEl.textContent = `
      
//     `;
//     document.head.insertBefore(styleEl, document.querySelector("head style, head link[rel*='stylesheet']"));
// }
;// CONCATENATED MODULE: ./src/index.js


if (!window.$docsify) {
  window.$docsify = {}
}

window.$docsify.plugins = (window.$docsify.plugins || []).concat(install)
__webpack_exports__ = __webpack_exports__["default"];
/******/ 	return __webpack_exports__;
/******/ })()
;
});