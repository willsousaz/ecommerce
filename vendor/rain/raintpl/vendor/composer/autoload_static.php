<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit960d831f474191997b247c4546e69532
{
    public static $prefixesPsr0 = array (
        'R' => 
        array (
            'Rain' => 
            array (
                0 => __DIR__ . '/../..' . '/library',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit960d831f474191997b247c4546e69532::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit960d831f474191997b247c4546e69532::$classMap;

        }, null, ClassLoader::class);
    }
}
