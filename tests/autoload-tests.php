<?php
/**
 * @package Magnate
 * 
 * @since 0.9.8
 */
spl_autoload_register(function($classname) {

    if (strpos($classname, 'Magnate\\Tests') !== false) {

        $file = explode('\\', $classname);
        
        if ($file[0] !== 'Magnate' ||
        $file[1] !== 'Tests') return;
        
        $path = __DIR__.'/';

        for ($i = 2; $i < count($file) - 1; $i++) {

            $path .= $file[$i].'/';

        }

        $path .= $file[count($file) - 1].'.php';

        if (file_exists($path)) require_once $path;

    }

});
