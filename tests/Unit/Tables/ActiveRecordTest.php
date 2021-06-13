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

        $entity = [
            'id' => 1,
            'test_value' => 'this is value'
        ];

        $GLOBALS['wpdb'] = $this->getWpdb('id', [$entity]);

        $model = new TestModel;
        $model->test_value = 'this is a value';

        $this->assertSame($model, $model->save());

        return $entity;

    }

    /**
     * @test
     * 
     * @depends testNew
     * 
     * Model finding test.
     */
    public function testFind(array $entity)
    {

        $model = TestModel::find(1);

        $this->assertSame($entity['id'], $model->id);
        $this->assertSame($entity['test_value'], $model->test_value);

        return $entity;

    }

    /**
     * @test
     * 
     * @depends testFind
     * 
     * Model refreshing test.
     */
    public function testRefresh(array $entity)
    {

        $model = TestModel::find(1);
        $model->test_value = 'new value';
        $model->refresh();

        $this->assertSame($entity['test_value'], $model->test_value);

        return $entity;

    }

    /**
     * @test
     * 
     * @depends testRefresh
     * 
     * Model deleting test.
     */
    public function testDelete(array $entity)
    {

        $model = TestModel::find(1);
        $model->delete();

        $this->assertNotSame($entity['id'], $model->id);

    }

}
