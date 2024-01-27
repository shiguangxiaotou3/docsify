<p align="center">
  <a href="https://www.shiguangxiaotou.com/docsify">
    <img alt="docsify" src="https://www.shiguangxiaotou.com/favicon.ico" height="100px">
  </a>
</p>

<p align="center">
  这是一个wordpress插件
</p>
<p align="center">
  为你的 WordPress 网站带来 Docsify 强大的文档功能。
</p>

## Features

- 插件为你的网站添加Docsify丰富的功能
- 在线编辑 Markdown 文本
- 丰富的Docsify插件和主题

## Usage

```bash
# 将插件上传至:
  wp-content/plugins
# markdown文件位于:(请求确保具有读写权限0755)
  wp-content/plugins/docsify/src/assets/bower/markdown 
# markdown发布目录位于:
  wp-content/plugin/uploads/docsify
  
# 编辑.htaccess文件禁止插件目录外部访问
<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteBase /
	RewriteRule ^wp-content/plugins/docsify - [R=404,L]
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule . /index.php [L]
</IfModule>    
```


## Requirements

PHP 5 or higher

## Screenshots

![Docsify](https://www.shiguangxiaotou.com/wp-content/uploads/2024/01/截屏2024-01-14-22.07.04.png)
![Plugins](https://www.shiguangxiaotou.com/wp-content/uploads/2024/01/截屏2024-01-14-22.09.39.png)

## License

[MIT](LICENSE)
