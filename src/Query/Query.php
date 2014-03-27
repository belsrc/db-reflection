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
        public function __construct( \PDO $pdo ) {
            $this->_pdo = $pdo;
            $this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        }

        /**
         * Gets a list of tables from the specified database.
         *
         * @param  string $database The name of the database.
         * @return array
         */
        public function getDatabaseTables( $database ) {
            $tmp = array();
            $statement = $this->_pdo->prepare(
                "SELECT table_name as 'name' FROM `tables` WHERE `table_schema` = :db"
            );
            $statement->setFetchMode( \PDO::FETCH_ASSOC );
            $statement->execute( array( 'db' => $database ) );
            $result = $statement->fetchAll();

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
        public function getTableColumns( $database, $table ) {
            $tmp = array();
            $statement = $this->_pdo->prepare(
                "SELECT column_name as 'name' FROM `columns` WHERE `table_schema` = :db AND `table_name` = :table"
            );
            $statement->setFetchMode( \PDO::FETCH_ASSOC );
            $statement->execute( array( 'db' => $database, 'table' => $table ) );
            $result = $statement->fetchAll();

            foreach( $result as $row ) {
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
        public function getColumnConstraints( $database, $table, $column ) {
            $selects = implode( ',', Config::get( 'selects.constraint' ) );
            $statement = $this->_pdo->prepare(
                "SELECT $selects FROM `key_column_usage` WHERE `table_schema` = :database AND `table_name` = :table AND `column_name` = :column"
            );
            $statement->setFetchMode( \PDO::FETCH_CLASS, 'Belsrc\DbReflection\Reflection\ReflectionConstraint' );
            $statement->execute( array( 'db' => $database, 'table' => $table ) );
            $result = $statement->fetchAll();

            foreach( $result as $row ) {
                if( strtolower( $row->name ) == 'primary' ) {
                    $row->type == $row->name;
                }
                else {
                    $row->type == 'FOREIGN';
                }
            }

            return $tmp;
        }

        /**
         * Get the database from the database meta data.
         *
         * @param  string $database The name of the database to get.
         * @return Belsrc\DbReflection\Reflection\ReflectionDatabase
         */
        public function sqlDatabase( $database ) {
            $selects = implode( ',', Config::get( 'selects.db' ) );
            $statement = $this->_pdo->prepare(
                "SELECT $selects FROM `schemata` WHERE `schema_name` = :value"
            );
            $statement->setFetchMode( \PDO::FETCH_CLASS, 'Belsrc\DbReflection\Reflection\ReflectionDatabase' );
            $result = $statement->execute( array( 'value' => $database ) );

            if( count( $result ) ) {
                $dbObj = $statement->fetch();
                $dbObj->tables = $this->getDatabaseTables( $database );

                return $dbObj;
            }
            else {
                throw new PDOException( "Unknown database in table (path: $database, table: information_schema.schemata)" );
            }
        }

        /**
         * Get the table from the database meta data.
         *
         * @param  string    $table    The name of the table to get.
         * @param  string    $database The database the table is in.
         * @return Belsrc\DbReflection\Reflection\ReflectionTable
         */
        public function sqlTable( $table, $database ) {
            $selects = implode( ',', Config::get( 'selects.table' ) );
            $statement = $this->_pdo->prepare(
                "SELECT $selects FROM `tables` WHERE `table_schema` = :db AND `table_name` = :table"
            );
            $statement->setFetchMode( \PDO::FETCH_CLASS, 'Belsrc\DbReflection\Reflection\ReflectionTable' );
            $result = $statement->execute( array( 'db' => $database, 'table' => $table ) );

            if( count( $result ) ) {
                $dbObj = $statement->fetch();
                $dbObj->columns = $this->getTableColumns( $database, $table );

                return $dbObj;
            }
            else {
                throw new PDOException( "Unknown database in table (path: $database, table: information_schema.schemata)" );
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
        public function sqlColumn( $column, $table, $database ) {
            $selects = implode( ',', Config::get( 'selects.column' ) );
            $statement = $this->_pdo->prepare(
                "SELECT $selects FROM `columns` WHERE `table_schema` = :db AND `table_name` = :table AND `column_name` = :column"
            );
            $statement->setFetchMode( \PDO::FETCH_CLASS, 'Belsrc\DbReflection\Reflection\ReflectionColumn' );
            $result = $statement->execute( array( 'db' => $database, 'table' => $table, 'column' => $column ) );

            if( count( $result ) ) {
                $dbObj = $statement->fetch();
                $dbObj->constraints = $this->getColumnConstraints( $database, $table, $column );

                return $dbObj;
            }
            else {
                throw new PDOException( "Unknown database in table (path: $database, table: information_schema.schemata)" );
            }
        }
    }
