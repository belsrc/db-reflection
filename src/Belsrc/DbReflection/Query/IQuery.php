<?php namespace Belsrc\DbReflection\Query;

    interface IQuery {

        /**
         * Get the database from the database meta data.
         *
         * @param  string $database The name of the database to get.
         */
        public function sqlDatabase( $database );

        /**
         * Get the table from the database meta data.
         *
         * @param  string    $table    The name of the table to get.
         * @param  string    $database The database the table is in.
         */
        public function sqlTable( $table, $database );

        /**
         * Get the column from the database meta data.
         *
         * @param  string    $column   The name of the column to get.
         * @param  string    $table    The name of the table the column is in.
         * @param  string    $database The database to the table is in.
         */
        public function sqlColumn( $column, $table, $database );
    }
