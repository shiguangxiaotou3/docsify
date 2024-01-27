<?php

namespace Shiguangxiaotou3\Docsify\assets;


class DocsifyAsset extends BaseDocsifyAsset {
    public $sourcePath =  "@bower/docsify";
    public $css = [
        WP_DEBUG ? "css/vue.css":"css/vue.min.css"
    ];
    public $js = [
        WP_DEBUG ? "js/docsify.js":"js/docsify.min.js"
    ];
    public $jsOptions=[];
}
