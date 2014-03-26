<?php namespace Belsrc\DbReflection\Query;

    use Belsrc\DbReflection\Config;
    use Belsrc\DbReflection\Reflection;

    class Query implements IQuery {

        /**
         * Get the database from the database meta data.
         *
         * @param  string $database The name of the database to get.
         * @return Belsrc\DbReflection\Reflection\ReflectionDatabase
         */
        public function sqlDatabase( $database ) {
            $d = new Reflection\ReflectionDatabase();
            $result = \DB::table( 'information_schema.schemata' )
                         ->where( 'schema_name', $database )
                         ->get( Config::get('selects.db') );

            if( !empty( $result ) && !empty( $result[0] ) ) {
                foreach( get_object_vars( $result[0] ) as $key => $value ) {
                    $d->$key = $value;
                }
            }

            return $d;
        }

        /**
         * Get the table from the database meta data.
         *
         * @param  string    $table    The name of the table to get.
         * @param  string    $database The database the table is in.
         * @return Belsrc\DbReflection\Reflection\ReflectionTable
         */
        public function sqlTable( $table, $database ) {
            $t = new Reflection\ReflectionTable();
            $result = \DB::table( 'information_schema.tables' )
                         ->where( 'table_schema', $database )
                         ->where( 'table_name', $table )
                         ->get( Config::get('selects.table') );

            if( !empty( $result ) && !empty( $result[0] ) ) {
                foreach( get_object_vars( $result[0] ) as $key => $value ) {
                    $t->$key = $value;
                }

                $t->database = $database;
            }

            return $t;
        }

        /**
         * Get the column from the database meta data.
         *
         * @param  string    $column   The name of the column to get.
         * @param  string    $table    The name of the table the column is in.
         * @param  string    $database The database to the table is in.
         * @return Belsrc\DbReflection\Reflection\ReflectionColumn
         */
        public function sqlColumn( $column, $table, $database ) {
            $c = new Reflection\ReflectionColumn();
            $result = \DB::table( 'information_schema.columns' )
                         ->where( 'table_schema', $database )
                         ->where( 'table_name', $table )
                         ->where( 'column_name', $column )
                         ->get( Config::get('selects.column') );

            if( !empty( $result ) && !empty( $result[0] ) ) {
                foreach( get_object_vars( $result[0] ) as $key => $value ) {
                    $c->$key = $value;
                }

                $c->database = $database;
            }

            return $c;
        }
    }
