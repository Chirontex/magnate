<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Injectors;

use Magnate\Exceptions\EntryPointInjectorException;
use wpdb;

/**
 * Injector intends for EntryPoint class.
 * @since 0.0.1
 */
class EntryPointInjector
{

    /**
     * @var wpdb $wpdb
     * WPDB global object link.
     * @since 0.0.1
     */
    protected $wpdb;

    /**
     * @var string $path
     * Root path to your plugin.
     * @since 0.0.1
     */
    protected $path;

    /**
     * @var string $url
     * Root URL to your plugin.
     * @since 0.0.1
     */
    protected $uri;

    public function __construct(string $path, string $url)
    {
        
        global $wpdb;

        if (!($wpdb instanceof wpdb)) throw new EntryPointInjectorException(
            EntryPointInjectorException::pickMessage(
                EntryPointInjectorException::NOT_WPDB
            ),
            EntryPointInjectorException::pickCode(
                EntryPointInjectorException::NOT_WPDB
            )
        );

        $this->wpdb = $wpdb;

    }

}
