<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd4ca930b5f969464312c475d0686b211
{
    public static $files = array (
        'a2c48002d05f7782d8b603bd2bcb5252' => __DIR__ . '/..' . '/johnbillion/extended-cpts/extended-cpts.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WidgiLabs\\WP\\Plugin\\Tests\\IdealBiz\\Service\\Request\\' => 51,
            'WidgiLabs\\WP\\Plugin\\IdealBiz\\Service\\Request\\' => 45,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WidgiLabs\\WP\\Plugin\\Tests\\IdealBiz\\Service\\Request\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests',
        ),
        'WidgiLabs\\WP\\Plugin\\IdealBiz\\Service\\Request\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd4ca930b5f969464312c475d0686b211::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd4ca930b5f969464312c475d0686b211::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}