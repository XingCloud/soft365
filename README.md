###phpstrom debug 环境搭建

#### 第一步 安装php及相关扩展

利用[phpbrew](https://github.com/c9s/phpbrew) 可以很方便的管理php及扩展的安装。快速入门：

```shell

# 安装phpbrew
curl -O https://raw.github.com/c9s/phpbrew/master/phpbrew
chmod +x phpbrew
sudo cp phpbrew /usr/bin/phpbrew
phpbrew init
source ~/.phpbrew/bashrc

# 安装php
phpbrew known #列出可用的php版本
phpbrew install php-5.4.0 +default #安装一个版本的php
phpbrew list #查看已经安装的php
phpbrew switch php-5.4.0 #切换系统环境到这个php

#安装扩展，这里我们用到的的扩展是xdebug

phpbrew ext install xdebug stable

```

### 第二步 在php.ini中配置xdebug

```shell
phpbrew config #可以方便的打开当前的php版本对应的php.ini
```

追加配置如下:

```ini
[XDebug]
zend_extension="/home/wcharlie/.phpbrew/php/php-5.4.0/lib/php/extensions/no-debug-non-zts-20100525/xdebug.so"
xdebug.remote_enable=1
xdebug.remote_port=9000
xdebug.profiler_enable=1
xdebug.profiler_output_dir="/tmp/xdebug/"
```

### 第三步 配置php interpreter

进入phpstorm，Project -> Settings -> PHP -> Interpreter, "PHP home"设为 `/home/wcharlie/.phpbrew/php/php-5.4.0/bin`.

### 第四步 配置application的运行环境

有几种方式可以启动php应用(apache/nginx/php-fpm.../存php-cgi)，php5.4.0自带了一个web server，
可以很方便的在phpstorm里面启起来．

Run ->  edit configuration -> + ->　选择php build-in web server, host填写`localhost`,port填`8080`
(默认80没有权限监听)，Document root填`/home/wcharlie/workspace/soft365/webroot`,接着点界面上的三角
run按钮就可以在phpstorm里面启动8080,另外会启动debug端口9000,phpstorm就是通过这个端口获取调试信息，
见上面php.ini的配置．

### 第四步 增加php server

Project -> Settings -> PHP ->　Servers -> + ,根据上一步配置添加一个server叫php builit-in server，下
一步配置debug的时候会用到．

### 第五步 设置断点，启动debug

Run ->  edit configuration -> + -> 选择php web application,server选择刚刚添加的php built-in server, start url /, browser随便选个．

代码里面设个断点，选择刚刚创建的configuration然后点击工具栏上的debug按钮．

测试:webroot底下添加test.php，localhost:8080/test.php:

```php
<html>
<head>
    <title>Testing PHP</title>
</head>
<body>
<h1>Testing PHP</h1>
<h2>Demo Code</h2>
<?PHP
$a = 1;
echo "This is a test";
phpinfo( );
?>
</body>
</html>
```





