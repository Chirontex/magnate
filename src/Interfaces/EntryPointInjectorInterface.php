<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Interfaces;

use wpdb;

/**
 * EntryPoint injector interface.
 * @since 0.0.3
 */
interface EntryPointInjectorInterface
{

    /**
     * @since 0.0.1
     * 
     * @param string $path
     * Root path to your plugin.
     * 
     * @param string $url
     * Root URL to your plugin.
     */
    public function __construct(string $path, string $url);

    /**
     * Return global WPDB singleton.
     * @since 0.0.3
     * 
     * @return wpdb
     */
    public function getWpdb() : wpdb;

    /**
     * Return plugin root path.
     * @since 0.0.3
     * 
     * @return string
     */
    public function getPath() : string;

    /**
     * Return plugin root URL.
     * @since 0.0.3
     * 
     * @return string
     */
    public function getUrl() : string;

}
