<p align="center"><img width="150" src="https://q.qlogo.cn/headimg_dl?dst_uin=2771717608&spec=640&img_type=jpg" alt="Vue logo"></a></p>

# composer 助手

## 便捷调用部分composer ClassLoader和InstalledVersions开发的接口

## !!! `注意安装需要composer v2`

---

# 安装

## composer

---

```shell
#安装最新版
composer require death_satan/composer --dev
```

---

# 使用

---

```php

//获取当前应用程序内的ClassLoader
//如果当前应用程序没有进行存储则手动去获取

#手动获取 classLoader

//获取autoload.php目录并把它require进来

/**
* @var \Composer\Autoload\ClassLoader $classLoader
 */
$classLoader = require __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php'

$composer = new \SaTan\Composer($classLoader);
```

---

## 可用`function`列表

<table>
  <tbody>
    <tr align="center">
        <td>方法</td>
        <td>说明</td>
        <td>增改时间</td>
    </tr>
    <tr align="center">
        <td>packageReference</td>
        <td>检测包是否安装 如果包被替换或提供但没有真正安装，则将返回 null 作为参考</td>
        <td>2021-9-2</td>
    </tr>
    <tr align="center">
        <td>rootPackages</td>
        <td>获取当前包信息</td>
        <td>2021-9-2</td>
    </tr>
    <tr align="center">
        <td>packageVersion</td>
        <td>获取包名版本</td>
        <td>2021-9-2</td>
    </tr>
    <tr align="center">
        <td>getPackagePath</td>
        <td>获取包的目录</td>
        <td>2021-9-2</td>
    </tr>
    <tr align="center">
        <td>hasPackage</td>
        <td>检查是否存在某个包</td>
        <td>2021-9-2</td>
    </tr>
    <tr align="center">
        <td>packages</td>
        <td>返回当前所有已加载的包</td>
        <td>2021-9-2</td>
    </tr>
    <tr align="center">
        <td>packageVersion</td>
        <td>获取包名版本</td>
        <td>2021-9-2</td>
    </tr>
    <tr align="center">
        <td>getAllRawData</td>
        <td>返回当前加载的所有 installed.php 的原始数据</td>
        <td>2021-9-2</td>
    </tr>
    <tr align="center">
        <td>packageVersion</td>
        <td>获取包名版本</td>
        <td>2021-9-2</td>
    </tr>
    <tr align="center">
        <td>getClassMap</td>
        <td>获取类名映射列表</td>
        <td>2021-9-2</td>
    </tr>
    <tr align="center">
        <td>setPsr0</td>
        <td>设置一个psr0目录|如果之前有设置则会覆盖</td>
        <td>2021-9-2</td>
    </tr>
    <tr align="center">
        <td>setPsr4</td>
        <td>设置一个psr4目录|如果之前有设置则会覆盖</td>
        <td>2021-9-2</td>
    </tr>
    <tr align="center">
        <td>import</td>
        <td>加载给定的类或接口</td>
        <td>2021-9-2</td>
    </tr>
    <tr align="center">
        <td>addPsr4</td>
        <td>动态添加psr-4映射</td>
        <td>2021-9-2</td>
    </tr>
    <tr align="center">
        <td>addPsr0</td>
        <td>动态添加psr-0映射</td>
        <td>2021-9-2</td>
    </tr>
    <tr align="center">
        <td>findClassFile</td>
        <td>查找类的文件</td>
        <td>2021-9-2</td>
    </tr>
    <tr align="center">
        <td>getClassLoader</td>
        <td>获取ClassLoader实例</td>
        <td>2021-9-2</td>
    </tr>

  </tbody>
</table>