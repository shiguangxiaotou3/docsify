<?php

namespace  Shiguangxiaotou3\Docsify;


use imessage\controllers\DocsifyController;
use yii\debug\panels\DumpPanel;

class Docsify
{
    private static $instance;

    public $alias=[];

    public $assets =[
        "js"=>[],
        'css'=>[],
        'code'=>''
    ];
    public function __construct(){
        $tmp =wp_upload_dir();
        $tmp_url = "/".substr(parse_url($tmp['baseurl'])['path'], 1);
        $this->alias= [
            '@app'=>__DIR__,
            '@bower'=>__DIR__."/assets/bower",
            "@views"=>__DIR__."/views",
            '@uploads'=>$tmp['basedir']."/docsify",
            "@uploads_base_url"=> $tmp['baseurl']."/docsify",
            "@uploads_relative_path"=> $tmp_url."/docsify",

        ];
    }
    public static function App() {
        if (!isset(self::$instance)) {
            // 如果实例不存在，创建一个新实例
            self::$instance = new self();
        }

        return self::$instance;
    }


    public static function getAlias($alias='', $throwException = true){
        if(empty($alias)){
            return '';
        }
        if (strncmp((string)$alias, '@', 1) !== 0) {
            // not an alias
            return $alias;
        }

        $pos = strpos($alias, '/');
        $root = $pos === false ? $alias : substr($alias, 0, $pos);
        $app = self::App();
        $Alias = $app->alias;
        if(isset( $Alias[$root])){
            return  str_replace($root,$Alias[$root],$alias);
        }else{
            throw new Exception("未找到别名:$root");
        }

    }

    public $controllerNamespace = 'Shiguangxiaotou3\Docsify\controllers';

    public function run(){

        // +----------------------------------------------------------------------
        // ｜后台页面、设置、菜单，挂载到wordpress钩子中
        // +----------------------------------------------------------------------
        add_action("admin_menu", [$this, "registerAdminPage"]);

        // +----------------------------------------------------------------------
        // ｜Ajax、RestfulApi、路由配置、解析规则，挂载到wordpress钩子中
        // +----------------------------------------------------------------------
        add_action("init", [$this, "registerAjax"]);

        //add_action("rest_api_init", [$this,"registerRestfulApi"]);

        add_action("admin_print_footer_scripts", [$this, "printAssets"]);
        $this->registerAdminFrontendPage();



    }


    public function registerAdminPage(){
        add_menu_page(
            "Docsify",
            "Docsify",
            'manage_options',
            "docsify",
            [$this, 'renderView'],
            'dashicons-align-full-width',110);
    }

    public function renderView(){
        try {
            $menu_id = isset($_GET['page']) ? $_GET['page'] : '';
            $controller = $this->createController($menu_id,$action);
            echo  $controller->$action();
        }catch (Exception $exception){
            dump($exception);
        }

    }

    public function registerAjax(){
        $actions =[
            [ 'menu_slug'=>"docsify/markdown","nopriv"=> false],
            [ 'menu_slug'=>"docsify/file","nopriv"=> false]
        ];
        wp_localize_script(
            'ajax-script',  // 这是你注册的脚本的标识符
            'docsify',         // 这是一个 JavaScript 对象的名称，将包含传递的数据
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),  // WordPress AJAX 处理的 URL
                'nonce'    => wp_create_nonce( 'title_example' ),  // 用于验证 AJAX 请求的安全性
            )
        );
        foreach ($actions as $action){
            if ($action['nopriv']){
                add_action("wp_ajax_nopriv_".$action['menu_slug'],[$this,"renderAjax"]);
            }else{
                add_action("wp_ajax_".$action['menu_slug'],[$this,"renderAjax"]);
            }
        }


    }

    public function renderAjax(){
        try {
            $menu_slug = isset($_GET['action']) ? $_GET['action'] : '';
            $controller = $this->createController($menu_slug,$action);

            $data = $controller->$action();
        }catch (\Exception $exception){
            header('Content-Type: application/json');
            $data = json_encode([
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'trace' => $exception->getTrace(),
                "file" => $exception->getFile()
            ]);
        }
        if(!empty($data)){
            exit($data);
        }

    }



    public function registerAdminFrontendPage(){
        add_action('init', function () {

            add_rewrite_rule('^docsify(.*)$',
                'index.php?docsify=docsify/docsify', "top");
        });

        add_filter('query_vars', function ($public_query_vars) {
            if(!in_array('docsify',$public_query_vars)){
                $public_query_vars[] = 'docsify';
            }
            return $public_query_vars;
        });
        add_action("template_redirect", [$this, "renderFrontendView"]);
    }

    public function renderFrontendView(){
        global $wp_query;
        $query_vars = $wp_query->query_vars;
        if (isset($query_vars['docsify']) and !empty($query_vars['docsify'])) {
            $route = "docsify/docsify";
            $controller = new \Shiguangxiaotou3\Docsify\controllers\DocsifyController();
            exit($controller->actionDocsify());
        }
    }

    public function createController($menu_slug,&$action="actionIndex"){
       $tmp =  explode("/",$menu_slug);
       $tmp = array_values(array_filter($tmp));
       $count = count($tmp);
       if( $count==0){
           throw new Exception("路由解析失败！");
       }elseif ($count ==1){
           $class =$this->controllerNamespace."\\".ucfirst(trim($tmp[0]))."Controller";
           $action ="actionIndex";

           return new $class();
       }elseif ($count ==2){
           $class =$this->controllerNamespace."\\".ucfirst(trim($tmp[0]))."Controller";
           $action ="action".ucfirst(trim($tmp[1]));

           return new $class();
       }else{
           $action = end($tmp);
           array_pop( $tmp);
           $controller = end($tmp);
           array_pop( $tmp);
           $class =$this->controllerNamespace."\\".implode("\\",  $tmp )."\\".ucfirst(trim($controller))."Controller";
           $action ="action".ucfirst(trim( $action));

           return new $class();

        }
    }

    public function printAssets(){

        $assets = Docsify::App()->assets;
        foreach ($assets['js'] as $js){
            wp_enqueue_script( $js);
        }
        foreach ($assets['css'] as $css){
            wp_enqueue_style($css);
        }

        $handle = end($assets['js'])?:"jquery";
        $js_code = (Docsify::App()->assets)['code'];
        $js =<<<JS
jQuery(function ($) {
    {$js_code}
});
JS;
        wp_add_inline_script(   $handle , $js);
    }
}
