# Magnate 0.8.8

## TODO

2. Write the docs.

3. Add JOIN to Active Record. (?)

## How to use

### Entry Point

You can create an entry point class by inheriting **\Magnate\EntryPoint**. An entry point class intended for create a singleton that contains all or significant part of the plugin logic.

The init() method can be overrided if you want to add some logic to object construction.

You must create **\Magnate\Injectors\EntryPointInjector** instance before create an entry point object. It is pretty simple: all you need is a root directory and URL of your plugin.

**NOTICE:** The init() method must be protected and return *$this*.

### Migrations

You can add migrations class by inheriting **\Magnate\Tables\Migration** with overrided up() method that contains all table creating logic. Table name can be determined by calling the table() method. Call the field() method to add field, the index() method to add index and the entry() method to add an entry to empty table.

**NOTICE:** The up() method must be protected and return *$this*.

### Active Record

An AR model can be created by inheriting **\Magnate\Tables\ActiveRecord**. You can get a single model instance by find() method or took a collection of instances by where(), order() or limit() methods.

Example:

```php
<?php

$document = Document::where(
    // This level lists will be united with OR.
    [
        [
            // This level lists will be united with AND
            'key' => [
                'condition' => 'LIKE %s',
                'value' => '%test%'
            ],
            'text' => [
                'condition' => '= %s',
                'value' => 'this is testing text'
            ]
        ],
        [
            'value' => [
                'condition' => '= %s',
                'value' => 'This is test.'
            ]
        ]
    ]
)->order(['key' => 'DESC'])->limit(1)->get()->all();

```

The get() method returns us a **\Magnate\Tables\ActiveRecordCollection** object, and all() method retrieves a list of model instances.

Of course, you can be save the model state in database by calling the save() method.
