<?php

namespace Magnate\Tests\Mocks;

use Magnate\Tests\Exceptions\WpdbMockException;
use wpdb;

/**
 * WPDB mock class.
 * @since 0.9.8
 */
class WpdbMock extends wpdb
{

    /**
     * @var string $prefix
     * @since 0.9.8
     */
    public $prefix = '';

    /**
     * @var string $var
     * get_var() return value
     * @since 0.9.8
     */
    protected $var = '';

    /**
     * @var array|object $result
     * get_results() return value
     * @since 0.9.8
     */
    protected $results;

    /**
     * @since 0.9.8
     */
    public function __construct()
    {
        // disable parent constructor
    }

    /**
     * Set get_var() return value.
     * @since 0.9.8
     * 
     * @param string $var
     * 
     * @return $this
     */
    public function set_var(string $var) : self
    {

        $this->var = $var;

        return $this;

    }

    /**
     * @since 0.9.8
     * 
     * @return string
     * value of $query
     */
    public function get_var($query = null, $x = 0, $y = 0) : string
    {
        
        return $this->var;

    }

    /**
     * Set get_results() return value.
     * @since 0.9.8
     * 
     * @param array|object $results
     * 
     * @return $this
     * 
     * @throws \Magnate\Tests\Exceptions\WpdbMockException
     */
    public function set_results($results) : self
    {

        if (!is_array($results) &&
            is_object($results)) throw new WpdbMockException(
                sprintf(WpdbMockException::pickMessage(
                    WpdbMockException::NOT_TYPE
                ), '$results', 'array or object'),
                WpdbMockException::pickCode(
                    WpdbMockException::NOT_TYPE
                )
            );

        $this->results = $results;

        return $this;

    }

    /**
     * @since 0.9.8
     * 
     * @param array|object $query
     * Answer value.
     * 
     * @param string $output
     * Not works.
     */
    public function get_results($query = null, $output = OBJECT)
    {
        
        return $query;

    }

}
