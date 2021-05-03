<?php
/**
 * @package Magnate
 * @author Dmitry Shumilin (chirontex@yandex.ru)
 */
namespace Magnate\Tables;

use Magnate\Exceptions\MigrationException;
use wpdb;

/**
 * @abstract
 * Migration abstract class.
 * @since 0.0.4
 */
abstract class Migration
{

    /**
     * @var wpdb $wpdb
     * WPDB global singleton.
     * @since 0.0.4
     */
    protected $wpdb;

    /**
     * @var string $table_name
     * Table name.
     * @since 0.0.4
     */
    protected $table_name = '';

    /**
     * @var array $fields
     * Table fields.
     * @since 0.0.4
     */
    protected $fields = [];

    /**
     * @var array $indexes
     * Table indexes.
     * @since 0.0.4
     */
    protected $indexes = [];

    /**
     * @var array $entries
     * Entries that need to add to empty table.
     * @since 0.0.5
     */
    protected $entries = [];

    /**
     * Class constructor.
     * @since 0.0.4
     * 
     * @throws Magnate\Exceptions\MigrationException
     */
    public function __construct()
    {
        
        global $wpdb;

        if (!($wpdb instanceof wpdb)) throw new MigrationException(
            MigrationException::pickMessage(
                MigrationException::NOT_WPDB
            ),
            MigrationException::pickCode(
                MigrationException::NOT_WPDB
            )
        );

        $this->wpdb = $wpdb;

        $this->up()->create();

    }

    /**
     * Add table parameters.
     * @since 0.0.4
     * 
     * @return $this
     */
    protected function up() : self
    {

        return $this;

    }

    /**
     * Add table name.
     * @since 0.0.4
     * 
     * @param string $table_name
     * Table name, cannot be empty.
     * 
     * @return $this
     * 
     * @throws Magnate\Exceptions\MigrationException
     */
    protected function table(string $table_name) : self
    {

        if (empty($table_name)) throw new MigrationException(
            sprintf(MigrationException::pickMessage(
                MigrationException::EMPTY
            ), 'Table name'),
            MigrationException::pickCode(MigrationException::EMPTY)
        );

        $this->table_name = $table_name;

        return $this;

    }

    /**
     * Add table field.
     * @since 0.0.4
     * 
     * @param string $field_name
     * Field name.
     * 
     * @param string $field_props
     * Field properties.
     * 
     * @return $this
     * 
     * @throws Magnate\Exceptions\MigrationException
     */
    protected function field(string $field_name, string $field_props) : self
    {

        if (empty($field_name)) throw new MigrationException(
            sprintf(MigrationException::pickMessage(
                MigrationException::EMPTY
            ), 'Field name'),
            MigrationException::pickCode(MigrationException::EMPTY)
        );

        if (empty($field_props)) throw new MigrationException(
            sprintf(MigrationException::pickMessage(
                MigrationException::EMPTY
            ), 'Field properties'),
            MigrationException::pickCode(MigrationException::EMPTY)
        );

        $this->fields[$field_name] = $field_props;

        return $this;

    }

    /**
     * Add index.
     * @since 0.0.4
     * 
     * @param string $field_name
     * Index field name.
     * 
     * @param string $index_props
     * Index properties.
     * 
     * @return $this
     * 
     * @throws Magnate\Exceptions\MigrationException
     */
    protected function index(string $field_name, string $index_props) : self
    {

        if (empty($field_name)) throw new MigrationException(
            sprintf(MigrationException::pickMessage(
                MigrationException::EMPTY
            ), 'Field name'),
            MigrationException::pickCode(MigrationException::EMPTY)
        );

        if (empty($index_props)) throw new MigrationException(
            sprintf(MigrationException::pickMessage(
                MigrationException::EMPTY
            ), 'Index properties'),
            MigrationException::pickCode(MigrationException::EMPTY)
        );

        $this->indexes[$field_name] = $index_props;

        return $this;

    }

    /**
     * Add entry to empty table.
     * @since 0.0.5
     * 
     * @param array $fields
     * Entry fields and values.
     * 
     * @return $this
     */
    protected function entry(array $fields) : self
    {

        $this->entries[] = $fields;

        return $this;

    }

    /**
     * Create table if not exists.
     * @since 0.0.4
     * 
     * @return $this
     * 
     * @throws Magnate\Exceptions\MigrationException
     */
    protected function create() : self
    {

        $fn = function(array $entities, string $format) : string {

            $result = "";

            foreach ($entities as $key => $props) {

                $result .= ", ".sprintf($format, $key, $props);

            }

            return $result;

        };

        $fields = call_user_func(
            $fn,
            $this->fields,
            "`%1\$s` %2\$s"
        );

        $indexes = call_user_func(
            $fn,
            $this->indexes,
            "%2\$s (`%1\$s`)"
        );

        if ($this->wpdb->query(
            "CREATE TABLE IF NOT EXISTS `".$this->wpdb->prefix.
                    $this->table_name."` (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT".$fields.",
                PRIMARY KEY (`id`)".$indexes."
            )
            COLLATE='utf8mb4_unicode_ci'
            AUTO_INCREMENT=0"
        ) === false) throw new MigrationException(
            sprintf(MigrationException::pickMessage(
                MigrationException::CREATE_TABLE
            ), $this->table_name),
            MigrationException::pickCode(MigrationException::CREATE_TABLE)
        );

        foreach ($this->entries as $i => $entry) {

            $types = [];

            foreach ($entry as $key => $value) {

                if (is_int($value)) $types[] = '%d';
                elseif (is_float($value)) $types[] = '%f';
                else {

                    $this->entries[$i][$key] = (string)$value;

                    $types[] = '%s';

                }

            }

            if ($this->wpdb->insert(
                    $this->wpdb->prefix.$this->table_name,
                    $entry,
                    $types
                ) === false) throw new MigrationException(
                MigrationException::pickMessage(
                    MigrationException::INSERT_ENTRY
                ),
                MigrationException::pickCode(
                    MigrationException::INSERT_ENTRY
                )
            );

        }

        return $this;

    }

}
