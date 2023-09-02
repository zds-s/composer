<?php

namespace SaTan\Support{

    use Composer\Autoload\ClassLoader;
    use SaTan\ComposerHelpers;

    /**
     * 获取Composer ClassLoader
     * @return ClassLoader|null
     */
    function get_class_loader(): ?ClassLoader
    {
        $loaders = spl_autoload_functions();
        foreach ($loaders as $loader)
        {
            if (is_array($loader) && $loader[0] instanceof ClassLoader)
            {
                return $loader[0];
            }
        }
        return null;
    }

    /**
     * 获取composer helpers助手实例
     * @param ClassLoader|null $classLoader
     * @return ComposerHelpers
     */
    function get_composer_helpers(?ClassLoader $classLoader = null): ComposerHelpers
    {
        return new ComposerHelpers($classLoader);
    }

    /**
     * 判断某个包是否存在
     * @param string $package_name 包名
     * @return bool
     */
    function has_package(string $package_name): bool
    {
        return get_composer_helpers()->hasPackage($package_name);
    }

    /**
     * 获取指定包的版本。如果包没有安装则返回null
     * @param string $package_name
     * @return string|null
     */
    function get_package_version(string $package_name): ?string
    {
        return !has_package($package_name) ? null : get_composer_helpers()->packageVersion($package_name);
    }

}