<?php

namespace Magnate\Tests\Unit\Tables;

use PHPUnit\Framework\TestCase;
use Magnate\Tests\Mocks\WpdbMock;
use Magnate\Tests\Mocks\Models\TestModel;

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

    /**
     * @test
     * 
     * Simple test for testing test stand actually.
     */
    public function testNew()
    {

        $GLOBALS['wpdb'] = $this->getWpdb();

        $model = new TestModel;
        $model->test_key = 'this is a key';
        $model->test_value = 'this is a value';

        $this->assertSame($model, $model->save());

    }

}
