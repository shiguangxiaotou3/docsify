<?php

namespace Shiguangxiaotou3\Docsify\assets;

use Shiguangxiaotou3\Docsify\Docsify;
use  \Exception;
use Shiguangxiaotou3\Docsify\helpers\FileHelper;

class BaseDocsifyAsset
{

    public $sourcePath = "";
    public $css = [];
    public $js = [];
    public $jsOptions=[];
    public $depends =[];

    public static function register(){
        $asset = new static();
        $assets = Docsify::App()->assets;
        $asset_path = self::publish($asset->sourcePath);
        $asset_Name = get_class($asset);
        $depends_Name =[];
        foreach ($asset->depends as $depend){
            $depends_Name[]= $depend::register( $depends_Name);
        }
        foreach ($asset->js  as $item){
            $js_name = $asset_path[3]."_js_".str_replace("/",'_', $item);
            wp_register_script($asset_path[3]."_js_".str_replace("/",'_', $item), $asset_path[0] ."/". $item, $depends_Name);
            array_push($assets['js'], $js_name);
        }

        foreach ($asset->css  as $item){
            $css_name = $asset_path[3]."_css_".str_replace("/",'_', $item);
            wp_register_style($asset_Name, $asset_path[0] ."/". $item, $depends_Name);
            array_push($assets['css'], $css_name);
        }
        Docsify::App()->assets =$assets;
        return $js_name;
    }

    public static function publish($alias,$forceCopy=false){
        $path  = Docsify::getAlias($alias);
        if (is_dir($path) and is_readable($path)){
            $basedir =  Docsify::getAlias('@uploads');
            $baseurl =  Docsify::getAlias("@uploads_base_url");
            $web_relative_url = Docsify::getAlias("@uploads_relative_path");
            if(is_writable($basedir)){
                $publishDirName = substr(md5($path), 0, 8);
                $publish_url = $basedir."/".$publishDirName;
                if(is_dir($publish_url)){
                    if($forceCopy){
                        FileHelper::clearDir($publish_url);
                        FileHelper::copyDir($path,$publish_url);
                    }
                }else{
                    mkdir($publish_url, 0755, true);
                    FileHelper::copyDir($path,$publish_url);
                }
                return [
                    // 带域名绝对路径
                    $baseurl."/".$publishDirName,
                    // 目录绝对路径
                    $publish_url,
                    // web 相对路径
                    $web_relative_url."/".$publishDirName,
                    $publishDirName
                ];
            }else{
                throw new Exception($basedir.":目录缺少目录创建权限");
            }
        }else{
            throw new Exception($path.":目录不存在或者没有读权限");
        }
    }


}
