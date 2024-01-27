<?php

/**
 * 插件引导文件
 *
 * 这个插件为你的 WordPress 网站引入了 Docsify 强大的文档功能。
 *
 * @link              https://github.com/shiguangxiaotou3/docsify
 * @since             1.0.0
 * @package           Docsify
 *
 * @wordpress-plugin
 * Plugin Name:       Docsify
 * Plugin URI:        https://github.com/shiguangxiaotou3/docsify
 * Description:       Wordpress的Docsify插件 强大的文档功能
 * Version:           1.0.0
 * Author:            Docsify
 * Author URI:        https://github.com/shiguangxiaotou3/docsify
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-name
 * Domain Path:       /languages
 */




if (!function_exists('dump')) {
    /**
     * 打印日志
     * @param $res
     * @return void
     */
    function dump($res)
    {
        echo "<pre style='margin-left: 200px'>";
        print_r($res);
        echo "</pre>";
    }
}


require_once __DIR__ . '/vendor/autoload.php';

register_activation_hook(__FILE__, function(){
    $tmp =wp_upload_dir();
    $basedir = $tmp['basedir']."/docsify";
    if (!is_dir($basedir)) {
        mkdir($basedir, 0777, true);
    }
    setcookie('docsify', true, time() + 300, '/');
    flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, function(){
    $tmp =wp_upload_dir();
    $basedir = $tmp['basedir']."/docsify";
    if(is_dir($basedir)){
        \Shiguangxiaotou3\Docsify\helpers\FileHelper::deleteDir($basedir);
    }
    delete_option('docsify_config');
    flush_rewrite_rules();
});


(new Shiguangxiaotou3\Docsify\Docsify())->run();