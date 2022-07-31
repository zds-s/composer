<?php

use Composer\Autoload\ClassLoader;
/**
 * @var ClassLoader $class_loader
 */
$class_loader = require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$helpers = new \SaTan\ComposerHelpers();
$install_helpers = $helpers->getInstallVersionHelpers();
var_dump($install_helpers->getPackagePath('death_satan/array-helpers'));