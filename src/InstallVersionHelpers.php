<?php


namespace SaTan;
use Composer\Autoload\ClassLoader;
use Composer\InstalledVersions;
use DeathSatan\ArrayHelpers\Arr;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

class InstallVersionHelpers
{
    public string $install_version_class = InstalledVersions::class;

    protected ?array $body = null;

    /**
     * 获取vendor目录绝对路径
     * @return string
     */
    public function getVendorPath(): string
    {
        $ref = new ReflectionClass(ClassLoader::class);
        $composer_path = pathinfo($ref->getFileName(),PATHINFO_DIRNAME);
        return dirname($composer_path);
    }

    /**
     * 获取installed.json的内容
     * @param string|null $installed_json_path
     * @return array
     */
    public function getInstallJson(string $installed_json_path = null): array
    {
        if ($this->body !== null)
        {
            return $this->body;
        }

        if ($installed_json_path === null) {
            try {
                $ref = new ReflectionClass($this->install_version_class);
            } catch (ReflectionException $e) {
                throw new RuntimeException($e->getMessage());
            }
            $installed_json_path = pathinfo($ref->getFileName(),PATHINFO_DIRNAME);
        }

        $installed_json_path = rtrim($installed_json_path,'\\|/').DIRECTORY_SEPARATOR;

        if (!is_dir($installed_json_path)) {
            throw new RuntimeException('path:['.$installed_json_path.'] Is not a valid directory');
        }

        $installed_json_path .= 'installed.json';

        if (!is_file($installed_json_path) || !is_readable($installed_json_path)) {
            throw new RuntimeException('installed.json NotFound');
        }

        $content = json_decode(file_get_contents($installed_json_path),true);

        if (json_last_error() !== JSON_ERROR_NONE)
        {
            throw new RuntimeException(json_last_error_msg());
        }
        $this->body = $content;
        return $this->body;
    }

    /**
     * 获取原始所有包内容
     * @return array
     */
    public function packagesRawData():array
    {
        return $this->getInstallJson()['packages'] ?? [];
    }

    /**
     * 判断某个包是否安装
     * @param string $packageName
     * @return bool
     */
    public function isInstallPackage(string $packageName): bool
    {
        return InstalledVersions::isInstalled($packageName);
    }

    /**
     * 获取指定包的详细信息
     * @param string $packageName
     * @return array
     */
    public function getPackage(string $packageName):array
    {
        $packages = Arr::where($this->packagesRawData(),function ($package)use ($packageName){
            return ($package['name'] ?? '') === $packageName;
        });
        if (count($packages) !== 1)
        {
            throw new RuntimeException($packageName . ' not installed'."\r\n please composer require ".$packageName);
        }
        return $packages[0];
    }

    /**
     * 获取指定包的版本
     * @param string $packageName
     * @return string|null
     */
    public function getPackageVersion(string $packageName,bool $normalized = false):?string
    {
        $version_field = $normalized ? 'version_normalized' : 'version';
        return $this->getPackage($packageName)[$version_field] ?? null;
    }

    /**
     * 获取某个包的类型
     * @param string $packageName
     * @return string|null
     */
    public function getPackageType(string $packageName):?string
    {
        return $this->getPackage($packageName)['type'] ?? null;
    }

    /**
     * 获取某个包的安装绝对目录
     * @param string $packageName
     * @return string|null
     */
    public function getPackagePath(string $packageName):?string
    {
        $package = $this->getPackage($packageName);
        $package_path = ltrim($package['install-path'],'\.\.');
        $root_path = rtrim($this->getVendorPath().'\\','/|\\');
        return  $root_path.$package_path;
    }
}