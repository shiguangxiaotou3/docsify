<?php

namespace Shiguangxiaotou3\Docsify\assets;

class AceAsset extends BaseDocsifyAsset
{
    public $sourcePath = WP_DEBUG ? "@bower/ace/src":"@bower/ace/src-min";
    public $css = [];
    public $js = [
        'ace.js',"ext-settings_menu.js"
    ];
    public $jsOptions=[
    ];

}
