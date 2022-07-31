<?php
/**
 * @author    : Death-Satan
 * @date      : 2021/9/2
 * @createTime: 22:58
 * @company   : Death撒旦
 * @link      https://www.cnblogs.com/death-satan
 */

namespace SaTan;

use Composer\Autoload\ClassLoader;
use Composer\InstalledVersions;
use OutOfBoundsException;

class ComposerHelpers
{
    protected ?ClassLoader $classLoader = null;

    public function __construct(?ClassLoader $classLoader = null)
    {
        $this->classLoader = $classLoader;
        // 如果不指定传入的class loader 就从当前系统内去获取
        if ($classLoader === null) {
            $this->initClassLoader();
        }
        $this->initInstallHelpers();
    }

    protected InstallVersionHelpers $install_version_helpers;

    protected function initInstallHelpers()
    {
        $this->install_version_helpers = new InstallVersionHelpers();
    }

    /**
     * @return InstallVersionHelpers
     */
    public function getInstallVersionHelpers(): InstallVersionHelpers
    {
        return $this->install_version_helpers;
    }

    /**
     * @param ClassLoader $classLoader
     * @return ComposerHelpers
     */
    public function setClassLoader(ClassLoader $classLoader): ComposerHelpers
    {
        $this->classLoader = $classLoader;
        return $this;
    }

    /**
     * 通过系统内置函数获取到class Loader的实例
     */
    protected function initClassLoader()
    {
        $loaders = spl_autoload_functions();
        foreach ($loaders as $loader)
        {
           if (is_array($loader) && $loader[0] instanceof ClassLoader)
           {
               $this->classLoader = $loader[0];
               return;
           }
        }
        throw new \RuntimeException('Class Loader NotFound');
    }

    /**
     * 检测包是否安装 如果包被替换或提供但没有真正安装，则将返回 null 作为参考
     * @param string $packageName 包名
     * @return string|null
     * @throws OutOfBoundsException
     */
    public function packageReference(string $packageName): ?string
    {
        return InstalledVersions::getReference($packageName);
    }

    /**
     * 获取当前包信息
     * @return array
     */
    public function rootPackages(): array
    {
        return InstalledVersions::getRootPackage();
    }

    /**
     * 获取包的版本信息
     * @param string $packageName
     * @return string|null
     * @throws OutOfBoundsException
     */
    public function packageVersion(string $packageName): ?string
    {
        return InstalledVersions::getVersion($packageName);
    }

    /**
     * 获取包的目录
     * @param string $packageName 包名
     * @return string
     * @throws OutOfBoundsException
     */
    public function getPackagePath(string $packageName): string
    {
        return $this->getInstallVersionHelpers()->getPackagePath($packageName);
    }

    /**
     * 检查是否存在某个包
     * @param string $packageName 包名
     * @return bool
     */
    public function hasPackage(string $packageName): bool
    {
        return $this->getInstallVersionHelpers()->isInstallPackage($packageName);
    }

    /**
     * 返回当前所有已加载的包
     * @return array
     */
    public function packages(): array
    {
        return InstalledVersions::getInstalledPackages();
    }

    /**
     * 返回当前加载的所有 installed.php 的原始数据
     * @param bool $is_merge 是否合并
     * @return array
     */
    public function getAllRawData(bool $is_merge = true): array
    {
        $data = InstalledVersions::getRawData();
        if (!$is_merge) return $data;

        $install_data = [];
        foreach ($data as $install) {
            $install_data = array_merge($install_data, $install);
        }
        return $install_data;
    }

    /**
     * 获取类名映射列表
     * @return array
     */
    public function getClassMap(): array
    {
        return $this->classLoader->getClassMap();
    }

    /**
     * 检测命名空间是否正确,如果不正确自动补齐
     * @param string $namespace 命名空间
     * @return string
     */
    protected function detectNamespace(string $namespace): string
    {
        return (substr($namespace, -2) === '\\') ? $namespace : $namespace . '\\';
    }

    /**
     * 设置一个psr0目录|如果之前有设置则会覆盖
     * @param string|array $namespace 命名空间
     * @param bool|string|array $directory 目录|可以是二维数组的目录
     */
    public function setPsr0(array|string $namespace, $directory = false)
    {
        if (is_array($namespace)) {
            foreach ($namespace as &$name) {
                $name = $this->detectNamespace($name);
            }
            unset($name);
        }
        $this->classLoader->set($namespace, $directory);
    }

    /**
     * 设置一个psr4目录|如果之前有设置则会覆盖
     * @param string|array $namespace 命名空间
     * @param bool|string|array $directory 目录|可以是二维数组的目录
     */
    public function setPsr4(array|string $namespace, $directory = false)
    {
        if (is_array($namespace)) {
            foreach ($namespace as &$name) {
                $name = $this->detectNamespace($name);
            }
            unset($name);
        }
        $this->classLoader->setPsr4($namespace, $directory);
    }

    /**
     * 加载给定的类或接口
     * @param string $class
     * @return bool
     */
    public function import(string $class): bool
    {
        return $this->classLoader->loadClass($class);
    }

    /**
     * 动态添加psr-4映射
     * @param string|array $namespace 命名空间
     * @param null $directory 目录|可以是二维数组的目录
     * @param bool $prepend 是否覆盖在最栈最上方
     * @return void
     */
    public function addPsr4(array|string $namespace, $directory = null, bool $prepend = false): void
    {
        if (is_array($namespace) && is_null($directory)) {
            foreach ($namespace as $name => $dir) {
                $this->classLoader->addPsr4($this->detectNamespace($name), $dir, $prepend);
            }
            return;
        }
        $this->classLoader->addPsr4($this->detectNamespace($namespace), $directory, $prepend);
    }

    /**
     * 动态添加psr-0映射
     * @param string|array $namespace 命名空间
     * @param null $directory 目录|可以是二维数组的目录
     * @param bool $prepend 是否覆盖在最栈最上方
     * @return void
     */
    public function addPsr0(array|string $namespace, $directory = null, bool $prepend = false): void
    {
        if (is_array($namespace) && is_null($directory)) {
            foreach ($namespace as $name => $dir) {
                $this->classLoader->add($this->detectNamespace($name), $dir, $prepend);
            }
            return;
        }
        $this->classLoader->add($this->detectNamespace($namespace), $directory, $prepend);
        return;
    }

    /**
     * 获取ClassLoader实例
     * @return ClassLoader
     */
    public function getClassLoader(): ClassLoader
    {
        return $this->classLoader;
    }

    /**
     * 查找类的文件
     * @param string $class
     * @return false|string
     */
    public function findClassFile(string $class): bool|string
    {
        return $this->classLoader->findFile($class);
    }
}