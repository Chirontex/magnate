<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Helpers;

use Magnate\Exceptions\RedirectorException;

/**
 * A class that helps with redirects.
 * @since 0.8.0
 */
class Redirector
{

    /**
     * @var string $url
     * Redirection URL.
     * @since 0.8.0
     */
    protected $url;

    /**
     * @var int $code
     * Redirection code.
     * @since 0.8.0
     */
    protected $code;

    /**
     * @since 0.8.0
     * 
     * @param string $url
     * Regirection URL.
     * 
     * @param int $code
     * Refirection code.
     */
    public function __construct(string $url, int $code)
    {
        
        if (empty($url)) throw new RedirectorException(
            sprintf(RedirectorException::pickMessage(
                RedirectorException::EMPTY
            ), 'URL'),
            RedirectorException::pickCode(RedirectorException::EMPTY)
        );

        if ($code < 300 ||
            $code > 308) throw new RedirectorException(
                sprintf(RedirectorException::pickMessage(
                    RedirectorException::NOT_TYPE
                ), 'HTTP answer code', 'a redirection one')
            );

        $this->url = $url;
        $this->code = $code;

        add_action('wp_headers', function($headers) {

            $headers = [];
            $headers['Location'] = $this->url;

            return $headers;

        });

        add_action('wp', function() {

            status_header($this->code);

        });

    }

}
