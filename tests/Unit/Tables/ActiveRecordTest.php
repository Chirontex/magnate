<?php

namespace Magnate\Tests\Unit\Tables;

use PHPUnit\Framework\TestCase;
use Magnate\Tests\Mocks\WpdbMock;

/**
 * @since 0.9.8
 */
class ActiveRecordTest extends TestCase
{

    /**
     * Return a new WpdbMock object.
     * @since 0.9.8
     * 
     * @param string $var
     * get_var() return value.
     * 
     * @param array|object $results
     * get_results() return value.
     * 
     * @return \Magnate\Tests\Mocks\WpdbMock
     * 
     * @throws \Magnate\Tests\Exceptions\WpdbMockException
     */
    protected function getWpdb(string $var = '', $results = []) : WpdbMock
    {

        return (new WpdbMock)
            ->set_var($var)
            ->set_results($results);

    }

}
