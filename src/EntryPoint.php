<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate;

use Magnate\Interfaces\EntryPointInjectorInterface;
use wpdb;

/**
 * @abstract
 * Class that implements a plugin entry point.
 * @since 0.0.1
 */
abstract class EntryPoint
{

    /**
     * @var wpdb $wpdb
     * WPDB singleton.
     * @since 0.0.3
     */
    protected $wpdb;

    /**
     * @var string $path
     * Plugin root path.
     * @since 0.0.3
     */
    protected $path;

    /**
     * @var string $url
     * Plugin root URL.
     * @since 0.0.3
     */
    protected $url;

    /**
     * @since 0.0.3
     * 
     * @param EntryPointInjectorInterface $injector
     */
    public function __construct(EntryPointInjectorInterface $injector)
    {
        
        $this->wpdb = $injector->getWpdb();
        $this->path = $injector->getPath();
        $this->url = $injector->getUrl();

        $this->init();

    }

    /**
     * Starts after object creation.
     * @since 0.0.3
     * 
     * @return $this
     */
    protected function init() : self
    {

        return $this;

    }

}
