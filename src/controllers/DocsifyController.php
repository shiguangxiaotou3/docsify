<?php

namespace Shiguangxiaotou3\Docsify\controllers;

use Shiguangxiaotou3\Docsify\assets\DocsifyAsset;
use Shiguangxiaotou3\Docsify\assets\MarkdownAsset;
use Shiguangxiaotou3\Docsify\Docsify;
use Shiguangxiaotou3\Docsify\helpers\ArrayHelper;
use Shiguangxiaotou3\Docsify\helpers\FileHelper;


class DocsifyController extends BaseController {

    public function actionIndex(){

        $main =$this->getOptions();
        $main['docsify']["basePath"]= "/wp-admin/admin-ajax.php?action=docsify/markdown&file=";
        return $this->render('docsify/index',['main'=>$main]);
    }

    /**
     * 后台预览页面ajax 访问文件
     *
     * @return false|string|void
     */
    public function actionMarkdown()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $path = Docsify::getAlias("@bower/markdown");  //Yii::getAlias("@app") . "/assets/docsify";
        if ($requestMethod === 'GET') {

            $file = $_GET['file'];

            if (strpos($file, "_navbar.md") !== false) {
                $file = "/_navbar.md";
            }
            if (strpos($file, "_sidebar.md") !== false) {
                $file = "/_sidebar.md";
            }
            if (file_exists($path . $file)) {
                header('Content-Type: ' . mime_content_type($path . $file));
                header('Content-Disposition: inline; filename="' . basename($path . $file) . '"');
                header('Content-Length: ' . filesize($path . $file));
                // 读取文件并输出到响应
                readfile($path . $file);
            } else {
                header("HTTP/1.0 404 Not Found");
            }
        }

        die();

    }


    /**
     * 前台访问叶敏啊
     *
     * @return string
     */
    public function actionDocsify(){
        $config = $this->computed();
        $assets = DocsifyAsset::publish("@bower/docsify");
        $js = $assets[2].'/js/docsify.js';
        if(!WP_DEBUG){
            $js= str_replace(".js", ".min.js", $js);
        }
        array_unshift($config['js'],$js);
        return $this->render('docsify/docsify',$config);
    }

    public function actionFile()
    {

        $requestMethod = $_SERVER['REQUEST_METHOD'];


        $basePath = Docsify::getAlias('@bower/markdown');
        // get 请求获取文件信息
        if ($requestMethod === 'GET') {
            $path = $_GET['path'] ?: '';
            if (is_file($basePath . $path)) {
                return $this->success(
                    'Success', [
                        "type" => 'file',
                        'text' => file_get_contents($basePath . $path)
                    ]
                );
            } else {
                $dh = opendir($basePath . $path);
                $result = ['dir' => [], 'file' => [], 'details' => []];
                while (($assets = readdir($dh)) !== false) {
                    if ($assets != '.' && $assets != '..') {
                        if (is_dir($basePath . $path . "/" . $assets)) {
                            $result['dir'][] = $assets;
                        } else {
                            $result['file'][] = $assets;
                            $result['details'][] = $this->getFileDetails($basePath . $path . "/" . $assets);
                        }
                    }
                }
                closedir($dh);
                return $this->success(
                    'Success', [
                        "type" => 'dir',
                        'assets' => $result
                    ]
                );
            }
        }
        // post 创建文件或者目录
        if ($requestMethod === 'POST') {
            try {
                $path = $_POST['path'] ?: '';
                $text = $_POST['text'] ?: '';
                if (!empty($text)) {
                    $text = urldecode(base64_decode($text));
                }
               if ($this->CreateFile($basePath . $path, $text)) {
                    return $this->success('Success', [
                        'path' => $path,
                        'text' => $text,
                    ]);
               }
                return $this->error('创建文件或目录失败');
            } catch (\Exception $exception) {
                return $this->error("请检查文件或目录权限");
            }
        }
        // DELETE 删除文件
        if ($requestMethod === 'DELETE') {
            $path = $_GET['path'] ?: '';
            if (FileHelper::deleteDir($basePath . $path)) {
                return $this->success();
            }
            return $this->error('删除文件或目录失败');
        }

        // 用来保存设置
        if ($requestMethod === 'PUT') {
            try {

                $rawBody = file_get_contents('php://input');
                $config = json_decode($rawBody, true);
                $config["DEBUG"] = WP_DEBUG ?: false;
                $alias = $config['alias'] ?: [];
                $names = array_column($alias, 'name');
                $index = array_search('@DocsifyAsset', $names);
                if ($index !== false) {
                    $alias[$index] = ["name" => '@DocsifyAsset', "url" => (DocsifyAsset::publish("@bower/docsify"))[2]];
                } else {
                    $alias[] = ["name" => '@DocsifyAsset', "url" => (DocsifyAsset::publish("@bower/docsify"))[2]];
                }
                $config['alias'] = $alias;
                $this->setOptions($config);

                return $this->success("Success", $this->getOptions());
            } catch (\Exception $exception) {
                return $this->error($exception->getMessage());
            }
        }
        //PATCH 发布markdown资源包
        if ($requestMethod === 'PATCH') {
            $assets = MarkdownAsset::publish("@bower/markdown",true);

            $main = $this->getOptions();
            $main['docsify']['basePath'] = $assets[2]."/";
            $this->setOptions($main);
            return $this->success("Success",
                ["name" => '@MarkdownAsset', "url" => $assets[2]."/"]);
        }
        return $this->error('你没有访问权限');

    }


    private  function CreateFile( $path, $text = '')
    {

        if (file_exists($path )) {
            return  file_put_contents($path , $text)!== false;
        } else {
            if (substr($path, -3) === ".md") {
                $directoryPath = pathinfo($path , PATHINFO_DIRNAME);
                if (!is_dir($directoryPath)) {
                    mkdir($path, 0777, true);
                }
                return file_put_contents($path, $text) !== false;
            } elseif ($path ) {
                if (!is_dir($path )) {
                      mkdir($path , 0777, true);
                    return file_put_contents($path."/README.md", "### README") !== false;
                }

            }
        }
        return false;

    }


    private function getFileDetails($filename)
    {
        $fileInfo = stat($filename);

        if ($fileInfo !== false) {
            $permissions = decoct(fileperms($filename) & 0777);
            $owner = posix_getpwuid($fileInfo['uid']);
            $group = posix_getgrgid($fileInfo['gid']);

            return [
                'permissions' => $permissions,
                'name' => basename($filename),
                'owner' => $owner['name'],
                'group' => $group['name'],
                'size' => $fileInfo['size']. ' Bytes',
                'modified_time' => date('Y-m-d H:i:s', $fileInfo['mtime'])
            ];


        } else {
            return false;
        }
    }

    /**
     *
     * @return void
     */
    private function bashFile(){
        $array =['_coverpage.md','_footer.md','_navbar.md','_sidebar.md'];

    }

    private function computed(){
        $config = $this->getOptions();
        $result =[
            'main'=> $config['docsify']?:[],
            "css"=>[],
            'js'=>[]
        ];
        foreach ( $config['alias'] as $item1){
            if($item1['name']=='@MarkdownAsset'){
                $result['main']['docsify']['basePath'] =  $item1['url'];
            }
        }
        foreach ($config['pluginsEnable'] as   $item){
            if($config['plugins'][$item]){
                $result['main'] = ArrayHelper::merge($result['main'],$config['plugins'][$item]['usage']?:[]);
                $result['css'] = ArrayHelper::merge($result['css'],$config['plugins'][$item]['assets']['css']?:[]);
                $result['js'] = ArrayHelper::merge($result['js'],$config['plugins'][$item]['assets']['js']?:[]);
            }
        }
        if(isset( $config['themesEnable']) and !empty($config['themesEnable'])){
            if(isset( $config['themes'][$config['themesEnable']])){
                $result['css'] = ArrayHelper::merge($result['css'],$config['themes'][$config['themesEnable']]['css']?:[]);
                $result['js'] = ArrayHelper::merge($result['js'],$config['themes'][$config['themesEnable']]['js']?:[]);
            }
        }
        $js = $css =[];
        foreach ($result['css'] as $item){
            foreach ( $config['alias'] as $item1){
                $item =str_replace($item1['name'], $item1['url'], $item);
            }
            if(!WP_DEBUG){
                $item= str_replace(".css", ".min.css", $item);
            }
            $css[] = $item;

        }
        foreach ($result['js'] as $item){
            foreach ( $config['alias'] as $item1){
                $item =str_replace($item1['name'], $item1['url'], $item);
            }
            if(!WP_DEBUG){
                $item= str_replace(".js", ".min.js", $item);
            }
            $js[] = $item;
        }
        $result['css']=$css;
        $result['js']=$js;
        return $result;
    }


    public function getOptions()
    {
        $key = 'docsify_config';
        $optionValue = get_option($key);
        if ($optionValue === false) {
            add_option($key, [
                "docsify" => [
                    "name" => get_bloginfo('name'),
                    "loadNavbar" => "_navbar.md",
                    "loadSidebar" =>"_sidebar.md",
                    "coverpage" =>"_coverpage.md",
                    "loadFooter" =>"_footer.md",
                    "mergeNavbar"=> true,
                    "basePath" => "/docsify",
                    "formatUpdated" => "{MM}/{DD} {HH}:{mm}",
                    "maxLevel" => 3,
                    "subMaxLevel"=> 2,
                    "fallbackLanguages"=>['zh-CN'],
                    "repo"=>'shiguangxiaotou3/docsify',
                ],
                "themes" => [
                    "vue" => ["css" => ['@DocsifyAsset/css/vue.css']],
                    "pure" => ["css" => ['@DocsifyAsset/css/pure.css']],
                    "dark" => ["css" => ['@DocsifyAsset/css/dark.css']],
                    "buble" => ["css" => ['@DocsifyAsset/css/buble.css']]
                ],
                "pluginsEnable" => ['search', 'emoji', 'image', 'copy', 'tabs', 'count', 'footer', 'chat', 'hideCode', 'mermaid', 'prism', 'pagination'],
                "themesEnable" => "vue",
                "DEBUG" => false,
                /**
                 * 一旦插件被启用 插件配置项assets的css文件和js文件会被载入页面
                 * usage 会被合并到主配置文件main
                 */
                "plugins" => [
                    'search' => [
                        'usage' => [
                            'search' => [
                                'placeholder' => '开始搜索'
                            ]
                        ],
                        'describe' => '全文搜索插件.默认情况下,会识别当前页面上的超链接,并将内容保存在localStorage中。也可以指定文件的路径',
                        'assets' => [
                            'css' => [],
                            'js' => ['@DocsifyAsset/plugins/search.js']
                        ],
                        'home' => 'https://docsify.js.org/#/plugins?id=full-text-search',
                        'md' => 'https://raw.githubusercontent.com/docsifyjs/docsify/develop/docs/plugins.md'
                    ],
                    'google' => [
                        'usage' => [
                            'ga' => 'UA-XXXXX-Y'
                        ],
                        'describe' => '谷歌分析',
                        'assets' => [
                            'css' => [],
                            'js' => ['@DocsifyAsset/plugins/ga.js']
                        ],
                        'home' => 'https://docsify.js.org/#/plugins?id=google-analytics',
                        'md' => 'https://raw.githubusercontent.com/docsifyjs/docsify/develop/docs/plugins.md'
                    ],
                    'emoji' => [
                        'usage' => [

                        ],
                        'enable' => true,
                        'config' => [],
                        'describe' => '呈现更大的表情符号缩写代码集合',
                        'assets' => [
                            'css' => [],
                            'js' => ['@DocsifyAsset/plugins/emoji.js']
                        ],
                        'home' => 'https://docsify.js.org/#/plugins?id=emoji',
                        'md' => 'https://raw.githubusercontent.com/docsifyjs/docsify/develop/docs/plugins.md'
                    ],
                    'image' => [
                        'usage' => [

                        ],

                        'describe' => 'Medium的图像缩放,基于中等缩放',
                        'assets' => [
                            'css' => [],
                            'js' => ['@DocsifyAsset/plugins/zoom-image.js']
                        ],
                        'home' => 'https://docsify.js.org/#/plugins?id=zoom-image',
                        'md' => 'https://raw.githubusercontent.com/docsifyjs/docsify/develop/docs/plugins.md'
                    ],
                    'copy' => [
                        'usage' => [
                            'copyCode' => [
                                'buttonText' => 'Copy to clipboard',
                                'errorText' => 'Error',
                                'successText' => 'Copied'
                            ]
                        ],
                        'describe' => '一个docsify插件,可将Markdown代码块复制到剪贴板',
                        'assets' => [
                            'css' => [],
                            'js' => ['@DocsifyAsset/plugins/docsify-copy-code.js']],
                        'home' => 'https://github.com/jperasmus/docsify-copy-code',
                        'md' => 'https://raw.githubusercontent.com/docsifyjs/docsify/develop/docs/plugins.md'
                    ],
                    'pagination' => [
                        'usage' => [

                            'pagination' => [
                                'previousText' => '上一章节',
                                'nextText' => '下一章节',
                                'crossChapter' => true,
                                'crossChapterText' => true
                            ]

                        ],
                        'describe' => 'docsify分页插件',
                        'assets' => [
                            'css' => [],
                            'js' => ['@DocsifyAsset/plugins/docsify-pagination.js']
                        ],
                        'home' => 'https://github.com/imyelo/docsify-pagination',
                        'md' => 'https://raw.githubusercontent.com/docsifyjs/docsify/develop/docs/plugins.md'
                    ],
                    'tabs' => [
                        'usage' => [
                            'tabs' => ['persist' => true]
                        ],

                        'describe' => '一个docsify插件,用于显示Markdown中的选项卡式内容',
                        'assets' => [
                            'css' => [],
                            'js' => ['@DocsifyAsset/plugins/docsify-tabs.js']
                        ],
                        'home' => 'https://jhildenbiddle.github.io/docsify-tabs',
                        'md' => 'https://raw.githubusercontent.com/jhildenbiddle/docsify-tabs/master/README.md'
                    ],
                    'hideCode' => [
                        'usage' => [
                            'hideCode' => [
                                'scroll' => false, 'height' => 500
                            ]
                        ],
                        'describe' => '隐藏代码的docsify插件',
                        'assets' => [
                            'css' => [],
                            'js' => ['@DocsifyAsset/plugins/docsify-hide-code.js']
                        ],
                        'home' => 'https://github.com/jl15988/docsify-hide-code',
                        'md' => 'https://raw.githubusercontent.com/jl15988/docsify-hide-code/main/README.md'
                    ],
                    'footer' => [
                        'usage' => [
                            'loadFooter' => '_footer.md'
                        ],
                        'describe' => '用于启用 docsify 的站点的 markdown _footer.md 插件',
                        'assets' => [
                            'css' => [],
                            'js' => ['@DocsifyAsset/plugins/docsify-footer.js']
                        ],
                        'home' => 'https://alertbox.github.io/docsify-footer/',
                        'md' => 'https://raw.githubusercontent.com/alertbox/docsify-footer/main/README.md'
                    ],
                    'prism' => [
                        'usage' => [
                        ],
                        'describe' => 'Docsify使用Prism突出显示页面中的代码块',
                        'assets' => [
                            'css' => [],
                            'js' => [
                                '@DocsifyAsset/plugins/prism/prism-php.js',
                                '@DocsifyAsset/plugins/prism/prism-json.js',
                                '@DocsifyAsset/plugins/prism/prism-haml.js',
                                '@DocsifyAsset/plugins/prism/prism-css.js',
                                '@DocsifyAsset/plugins/prism/prism-bash.js',
                                '@DocsifyAsset/plugins/prism/prism-javascript.js',
                                '@DocsifyAsset/plugins/prism/prism-powershell.js',
                                '@DocsifyAsset/plugins/prism/prism-applescript.js',
                                '@DocsifyAsset/plugins/prism/prism-cil.js']],
                        'home' => 'https://raw.githubusercontent.com/docsifyjs/docsify/develop/docs/language-highlight.md',
                        'md' => 'https://raw.githubusercontent.com/docsifyjs/docsify/develop/docs//language-highlight.md'],
                    'mermaid' => [
                        'usage' => [
                            'mermaidConfig' => ['querySelector' => '.mermaid']
                        ],
                        'describe' => '在docsify中渲染Mermaid图的插件',
                        'assets' => [
                            'css' => [],
                            'js' => [
                                '@DocsifyAsset/plugins/mermaid/mermaid.js',
                                '@DocsifyAsset/plugins/mermaid/docsify-mermaid.js'
                            ]
                        ],
                        'home' => 'https://github.com/Leward/mermaid-docsify',
                        'md' => 'https://raw.githubusercontent.com/Leward/mermaid-docsify/master/README.md'
                    ],
                    'count' => [
                        'usage' => [

                            'count' => [
                                'countable' => true,
                                'position' => 'top',
                                'margin' => '10px',
                                'float' => 'right',
                                'fontsize' => '0.9em',
                                'color' => 'rgb(90,90,90)',
                                'language' => 'chinese',
                                'localization' => ['words' => '', 'minute' => ''],
                                'isExpected' => true
                            ]

                        ],
                        'describe' => '为docsify的markdown文件添加字数统计',
                        'assets' => [
                            'css' => [],
                            'js' => ['@DocsifyAsset/plugins/countable.js']
                        ],
                        'home' => 'https://github.com/827652549/docsify-count',
                        'md' => 'https://raw.githubusercontent.com/827652549/docsify-count/master/README.MD'],
                    'share' => [
                        'usage' => [

                            'share' => [
                                'reddit' => true,
                                'linkedin' => true,
                                'twitter' => true,
                                'whatsapp' => true,
                                'facebook' => true
                            ]

                        ],
                        'describe' => '在docsify中添加共享按钮的插件',
                        'assets' => [
                            'css' => [],
                            'js' => ['@DocsifyAsset/plugins/share/js/index.js']
                        ],
                        'home' => 'https://coroo.github.io/docsify-share',
                        'md' => 'https://raw.githubusercontent.com/coroo/docsify-share/master/README.md'
                    ],
                    'chat' => [
                        'usage' => [

                            'chat' => [
                                'title' => '聊天记录',
                                'users' => [
                                    ['nickname' => 'yuki', 'avatar' => ''],
                                    ['nickname' => 'kokkoro', 'avatar' => '']
                                ]
                            ]

                        ],
                        'describe' => '一个docsify插件,用于从Markdown生成聊天面板',
                        'assets' => [
                            'css' => [],
                            'js' => ['@DocsifyAsset/plugins/docsify-chat.js']
                        ],
                        'home' => 'https://github.com/xueelf/docsify-chat',
                        'md' => 'https://raw.githubusercontent.com/xueelf/docsify-chat/master/README.zh.md'],
                    'ads' => [
                        'usage' => [
                            'ads' => [
                                [
                                    'img' => 'https://dn-lego-static.qbox.me/cps/1638355965-480x300.jpg',
                                    'href' => 'https://s.qiniu.com/zqiMBz'
                                ]
                            ]
                        ],
                        'describe' => '用于展示广告的 docsify 插件',
                        'assets' => [
                            'css' => [],
                            'js' => ['@DocsifyAsset/plugins/docsify-ads.js']
                        ],
                        'home' => 'https://github.com/mg0324/docsify-ads',
                        'md' => 'https://raw.githubusercontent.com/mg0324/docsify-ads/main/README.md'
                    ],
                    'carbon_ads' => [
                        'usage' => [
                            'carbon_ads' => [
                                'carbonId' => '',
                                'carbonKey' => ''
                            ]
                        ],
                        'describe' => '一个插件,可让您轻松将Carbon广告添加到docsify',
                        'assets' => [
                            'css' => [],
                            'js' => ['@DocsifyAsset/plugins/share/js/index.js']
                        ],
                        'home' => 'https://github.com/waruqi/docsify-plugin-carbon',
                        'md' => 'https://raw.githubusercontent.com/waruqi/docsify-plugin-carbon/master/README.md'
                    ]
                ],
                "alias" => [
                    ["name" => '@DocsifyAsset', "url" => (DocsifyAsset::publish("@bower/docsify"))[2]]
                ]
            ]);
            $optionValue =get_option($key);
        }
        $assets = $optionValue['alias'];
        for ($i=0;$i<= count($assets)-1;$i++){
            if($assets[$i]['name']== "@DocsifyAsset"){
                $assets[$i]['url']=DocsifyAsset::publish("@bower/docsify")[2];
            }
        }
        $optionValue['alias'] =$assets ;
        return $optionValue;
    }

    public function setOptions($value =[]){
        $key = 'docsify_config';
        return update_option( $key, $value);
    }



}