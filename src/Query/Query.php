<?php namespace Belsrc\DbReflection\Query;

use Belsrc\DbReflection\Config;
use Belsrc\DbReflection\Reflection\ReflectionDatabase;
use Belsrc\DbReflection\Reflection\ReflectionTable;
use Belsrc\DbReflection\Reflection\ReflectionColumn;

class Query implements IQuery {

    private $_pdo;

    /**
     * Initializes a new instance of the Query class.
     *
     * @param PDO $pdo A PDO connection instance.
     */
    public function __construct(\PDO $pdo) {
        $this->_pdo = $pdo;
        $this->_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Builds and fetches a query from the database.
     *
     * @param  string $string     The query string to use.
     * @param  array  $params     An array of query string parameters.
     * @param  int    $fetchType  The PDO fetch type.
     * @param  string $fetchClass The class to use if fetch type is FETCH_CLASS.
     * @return array
     */
    private function query($string, array $params=array(), $fetchType=\PDO::FETCH_ASSOC, $fetchClass='') {
        $statement = $this->_pdo->prepare($string);

        if($fetchType == \PDO::FETCH_CLASS) {
            $statement->setFetchMode($fetchType, $fetchClass);
        }
        else {
            $statement->setFetchMode($fetchType);
        }

        $statement->execute($params);

        return $statement->fetchAll();
    }

    /**
     * Gets a list of tables from the specified database.
     *
     * @param  string $database The name of the database.
     * @return array
     */
    public function getDatabaseTables($database) {
        $tmp = array();
        $result = $this->query(
            "SELECT table_name as 'name' FROM `tables` WHERE `table_schema` = :db",
            array('db' => $database)
        );

        foreach( $result as $row ) {
            $tmp[] = $row['name'];
        }

        return $tmp;
    }

    /**
     * Gets a list of columns from the specified table.
     *
     * @param  string $database The name of the database.
     * @param  string $table    The name of the table.
     * @return array
     */
    public function getTableColumns($database, $table) {
        $tmp = array();
        $result = $this->query(
            "SELECT column_name as 'name' FROM `columns` WHERE `table_schema` = :db AND `table_name` = :table",
            array('db' => $database, 'table' => $table)
        );

        foreach($result as $row) {
            $tmp[] = $row['name'];
        }

        return $tmp;
    }

    /**
     * Gets a list of constraints from the specified column.
     *
     * @param  string $database The name of the database.
     * @param  string $table    The name of the table.
     * @param  string $column   The name of the column.
     * @return array
     */
    public function getColumnConstraints($database, $table, $column) {
        $selects = implode(',', Config::get('selects.constraint'));
        $result = $this->query(
            "SELECT $selects FROM `key_column_usage` WHERE `table_schema` = :db AND `table_name` = :table AND `column_name` = :column",
            array('db' => $database, 'table' => $table, 'column' => $column),
            \PDO::FETCH_CLASS,
            'Belsrc\DbReflection\Reflection\ReflectionConstraint'
        );

        foreach($result as $row) {
            if(strtolower($row->name) == 'primary') {
                $row->type = 'Primary Key';
            }
            else {
                $row->type = 'Foreign Key';
            }
        }

        return $result;
    }

    /**
     * Get the database from the database meta data.
     *
     * @param  string $database The name of the database to get.
     * @return Belsrc\DbReflection\Reflection\ReflectionDatabase
     */
    public function sqlDatabase($database, array $with) {
        $selects = implode(',', Config::get('selects.db'));
        $result = $this->query(
            "SELECT $selects FROM `schemata` WHERE `schema_name` = :value",
            array('value' => $database),
            \PDO::FETCH_CLASS,
            'Belsrc\DbReflection\Reflection\ReflectionDatabase'
        );

        if(count($result)) {
            $dbObj = $result[0];
            $tables = $this->getDatabaseTables($database);

            if(in_array('table', $with) || in_array('column', $with)) {
                $tmp = array();

                foreach($tables as $table) {
                    $tmp[] = $this->sqlTable($table, $database, $with);
                }

                $dbObj->tables = $tmp;
            }
            else {
                $dbObj->tables = $tables;
            }

            return $dbObj;
        }
        else {
            throw new PDOException("Unknown database in table (path: $database, table: information_schema.schemata)");
        }
    }

    /**
     * Get the table from the database meta data.
     *
     * @param  string    $table    The name of the table to get.
     * @param  string    $database The database the table is in.
     * @return Belsrc\DbReflection\Reflection\ReflectionTable
     */
    public function sqlTable($table, $database, array $with) {
        $selects = implode(',', Config::get('selects.table'));
        $result = $this->query(
            "SELECT $selects FROM `tables` WHERE `table_schema` = :db AND `table_name` = :table",
            array('db' => $database, 'table' => $table),
            \PDO::FETCH_CLASS,
            'Belsrc\DbReflection\Reflection\ReflectionTable'
        );

        if(count($result)) {
            $dbObj = $result[0];
            $columns = $this->getTableColumns($database, $table);

            if(in_array('database', $with)) {
                $parent = $this->sqlDatabase($database, array());
            }

            // If it has a 'column' with we need to get the information for the columns
            if(in_array('column', $with)) {
                $tmp = array();
                unset($with['columns']);
                foreach($columns as $column) {
                    $tmp[] = $this->sqlColumn($column, $table, $database, $with);
                }

                $dbObj->columns = $tmp;
            }
            else {
                $dbObj->columns = $columns;
            }

            // If they decided they wanted the database from this method we need to add the table information
            // to the 'tables' field so it all has the proper structure.
            if(isset($parent)) {
                $parent->tables = $dbObj;
                return $parent;
            }

            return $dbObj;
        }
        else {
            throw new PDOException("Unknown database in table (path: $database, table: information_schema.schemata)");
        }
    }

    /**
     * Get the column from the database meta data.
     *
     * @param  string    $column   The name of the column to get.
     * @param  string    $table    The name of the table the column is in.
     * @param  string    $database The database to the table is in.
     * @return Belsrc\DbReflection\Reflection\ReflectionColumn
     */
    public function sqlColumn($column, $table, $database, array $with) {
        $selects = implode(',', Config::get('selects.column'));
        $result = $this->query(
            "SELECT $selects FROM `columns` WHERE `table_schema` = :db AND `table_name` = :table AND `column_name` = :column",
            array('db' => $database, 'table' => $table, 'column' => $column),
            \PDO::FETCH_CLASS,
            'Belsrc\DbReflection\Reflection\ReflectionColumn'
        );

        if(count($result)) {
            $dbObj = $result[0];
            $dbObj->constraints = $this->getColumnConstraints($database, $table, $column);

            if(in_array('database', $with)) {
                $grandparent = $this->sqlDatabase($database, array());
            }

            if(in_array('table', $with) || in_array('database', $with)) {
                $parent = $this->sqlTable($table, $database, array());
            }

            if(isset($parent)) {
                $parent->columns = $dbObj;
                if(isset($grandparent)) {
                    $grandparent->tables = $parent;
                    return $grandparent;
                }

                return $parent;
            }

            return $dbObj;
        }
        else {
            throw new PDOException("Unknown database in table (path: $database, table: information_schema.schemata)");
        }
    }
}
