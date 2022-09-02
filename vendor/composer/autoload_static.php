<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8b871bcf2b6c6a5993da088fa3e45005
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Minicli\\' => 8,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Minicli\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8b871bcf2b6c6a5993da088fa3e45005::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8b871bcf2b6c6a5993da088fa3e45005::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8b871bcf2b6c6a5993da088fa3e45005::$classMap;

        }, null, ClassLoader::class);
    }
}