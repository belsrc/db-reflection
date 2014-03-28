<?php namespace Belsrc\DbReflection;

    class Config {

        private static $selects = array(
            'db' => array(
                'schema_name as name',
                'default_character_set_name as charset',
                'default_collation_name as collation',
            ),

            'table' => array(
                'table_name as name',
                'table_type as type',
                'data_length as length',
                'max_data_length as maxLength',
                'auto_increment as increment',
                'create_time as createdAt',
                'update_time as updatedAt',
                'checksum',
                'create_options as options',
                'table_comment as comment',
                "table_schema as 'database'",
            ),

            'column' => array(
                'column_name as name',
                'ordinal_position as position',
                'column_default as defaultValue',
                'is_nullable as isNullable',
                'data_type as dataType',
                "numeric_precision as 'precision'",
                'character_maximum_length as maxLength',
                'column_type as columnType',
                'character_set_name as charSet',
                'extra',
                'privileges',
                'column_comment as comment',
                "table_name as 'table'",
                "table_schema as 'database'",
            ),

            'constraint' => array(
                "constraint_name as 'name'",
                "column_name as 'column'",
                "table_name as 'table'",
                "table_schema as 'database'",
                "referenced_table_schema as 'foreign_db'",
                "referenced_table_name as 'foreign_table'",
                "referenced_column_name as 'foreign_column'",
            )
        );

        /**
         * Gets configuration values. ex. Config::get('selects.column')
         *
         * @param  string $value The config property to get.
         * @return mixed
         */
        public static function get( $value ) {
            $tmp = explode( '.', $value );
            if( count( $tmp ) ) {
                $var = $tmp[0];
                $val = $tmp[1];

                return self::$selects[$val];
            }

            throw new InvalidArgumentException( "Property could not be found ($value)" );
        }
    }
