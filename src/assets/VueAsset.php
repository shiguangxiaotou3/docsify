<?php

namespace Shiguangxiaotou3\Docsify\assets;

class VueAsset extends BaseDocsifyAsset
{
    public $sourcePath =  "@bower/vue/dist";
    public $css = [];
    public $js = [
        (WP_DEBUG)? "vue.js" :'vue.min.js'
    ];
    public $jsOptions=[];
    public $depends = ['Shiguangxiaotou3\Docsify\assets\AceAsset'];

}