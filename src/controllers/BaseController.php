<?php

namespace Shiguangxiaotou3\Docsify\controllers;

use imessage\assets\DocsifyAsset;
use Shiguangxiaotou3\Docsify\Docsify;

class BaseController
{

    public $view_path="";

    public function __construct(){

    }

    public function render($view,$params = []){

        // 将参数解包，将关联数组的键作为变量名，对应的值作为变量值
        extract($params);

        $path = Docsify::getAlias("@views/".$view);
        // 开始捕获输出
        ob_start();

        // 引入视图文件
        require  $path. ".php";

        // 获取捕获的输出并清除缓冲区
        return ob_get_clean();

    }

    /**
     * @param $data
     * @return false|string
     */
    public function json($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($data);
    }


    /**
     * @param $message
     * @param $data
     * @param $header
     * @return false|string
     */
    public function error($message='Error', $data = [], $header = [])
    {
        header('Content-Type: application/json; charset=utf-8');
        return json_encode([
            'code' => 0,
            'message' => $message,
            'time' => time(),
            'time_text' => date('Y-m-d H:i:s'),
            'data' => $data
        ]);
    }

    /**
     * @param $message
     * @param $data
     * @param $header
     * @return false|string
     */
    public function success($message="Success", $data = [], $header = [])
    {
        header('Content-Type: application/json; charset=utf-8');
        return json_encode([
            'code' => 1,
            'message' => $message,
            'time' => time(),
            'time_text' => date('Y-m-d H:i:s'),
            'data' => $data,
        ]);
    }


    public function getId() {
        $class = explode('\\',  get_class($this));
        return   lcfirst(str_replace("Controller","",end($class))) ;

    }

}