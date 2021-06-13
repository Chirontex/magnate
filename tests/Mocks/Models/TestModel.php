<?php

namespace Magnate\Tests\Mocks\Models;

use Magnate\Tables\ActiveRecord;

/**
 * Test model for ActiveRecordTest.
 * @since 0.9.8
 */
class TestModel extends ActiveRecord
{

    public static function tableName() : string
    {
        
        return 'test';

    }

}
