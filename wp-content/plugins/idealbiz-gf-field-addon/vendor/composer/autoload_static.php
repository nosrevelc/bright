<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6dd98bfa35c62158b7dd91fa5e18272d
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WidgiLabs\\WP\\Plugin\\iDealBiz\\GFFieldAddon\\' => 42,
            'WidgiLabs\\WP\\Plugin\\Tests\\iDealBiz\\GFFieldAddon\\' => 48,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WidgiLabs\\WP\\Plugin\\iDealBiz\\GFFieldAddon\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib',
        ),
        'WidgiLabs\\WP\\Plugin\\Tests\\iDealBiz\\GFFieldAddon\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6dd98bfa35c62158b7dd91fa5e18272d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6dd98bfa35c62158b7dd91fa5e18272d::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
