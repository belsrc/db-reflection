<?php namespace Belsrc\DbReflection;

use Belsrc\DbReflection\Query;

class DbReflection {

    private $_query;

    /**
     * Initializes a new instance of the DbReflection class.
     *
     * @param Belsrc\DbReflection\Query\IQuery $query An IQuery instance.
     */
    public function __construct(Query\IQuery $query) {
        $this->_query = $query;
    }

    /**
     * Gets the database information.
     *
     * @param  string $database The name of the database to get.
     * @return Belsrc\DbReflection\Reflection\ReflectionDatabase
     */
    public function getDatabase($database, array $with=array()) {
        return $this->_query->sqlDatabase($database, $with);
    }

    /**
     * Gets the table information.
     *
     * @param  string  $table    The name of the table to get.
     * @param  array   $with     An optional array of with values {database|table|column}
     * @param  string  $database The database to get the table from.
     * @return Belsrc\DbReflection\Reflection\ReflectionTable
     * @throws \InvalidArgumentException
     */
    public function getTable($table, array $with=array(), $database='') {
        if(empty($database)) {
            // if database empty, split table on '.' first will be database, second table
            if(empty($database)) {
                $tmp = explode('.', $table);
                if(count($tmp) != 2) {
                    throw new \InvalidArgumentException("Table was not a valid argument or not in a valid format ($table)");
                }

                $database = $tmp[0];
                $table = $tmp[1];
            }
        }

        return $this->_query->sqlTable($table, $database, $with);
    }

    /**
     * Gets the column information.
     *
     * @param  string $column   The name of the column to get.
     * @param  array  $with     An optional array of with values {database|table|column}
     * @param  string $table    The name of the table the column is in.
     * @param  string $database The database to the table is in.
     * @return Belsrc\DbReflection\Reflection\ReflectionColumn
     * @throws \InvalidArgumentException
     */
    public function getColumn($column, array $with=array(), $table='', $database='') {
        if(empty($table)) {
            // if table and database empty, split column on '.' first will be database, second table, third column
            if(empty($table) && empty($database)) {
                $tmp = explode('.', $column);
                if(count($tmp) != 3) {
                    throw new \InvalidArgumentException("Column was not a valid argument or not in a valid format ($column)");
                }

                $database = $tmp[0];
                $table = $tmp[1];
                $column = $tmp[2];
            }
        }

        return $this->_query->sqlColumn($column, $table, $database, $with);
    }
}
