<?php namespace Belsrc\DbReflection\Commands;

    use Illuminate\Console\Command;
    use Belsrc\DbReflection\Display\Display;

    class BaseReflectCommand extends Command {

        protected $_display;

        /**
         * Initializes a new instance of the BaseReflectCommand class.
         *
         * @return void
         */
        public function __construct() {
            parent::__construct();
            $this->_display = new Display();
        }

        /**
         * Gets a new connection to the information_schema database using the default connection.
         *
         * @return PDO
         */
        protected function getPdoConnection() {
            $default = \Config::get( 'database.default' );
            $conInfo = \Config::get( 'database.connections' )[$default];
            $conStr  = $conInfo['driver'] . ':host=' . $conInfo['host'] . ';dbname=information_schema';

            return new \PDO( $conStr, $conInfo['username'], $conInfo['password'] );
        }
    }
