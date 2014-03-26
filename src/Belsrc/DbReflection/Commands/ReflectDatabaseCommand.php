<?php namespace Belsrc\DbReflection\Commands;

    use Illuminate\Console\Command;
    use Symfony\Component\Console\Input\InputOption;
    use Symfony\Component\Console\Input\InputArgument;
    use Belsrc\DbReflection\DbReflection;
    use Belsrc\DbReflection\Query\Query;
    use Belsrc\DbReflection\Display\Display;

    class ReflectDatabaseCommand extends Command {

        /**
         * The console command name.
         *
         * @var string
         */
        protected $name = 'reflect:database';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Get the information about a particular database.';

        /**
         * Create a new command instance.
         *
         * @return void
         */
        public function __construct() {
            parent::__construct();
        }

        /**
         * Execute the console command.
         *
         * @return mixed
         */
        public function fire() {
            try {
                $name = $this->argument( 'name' );
                $dbr = new DbReflection( new Query() );
                $tmp = $dbr->getDatabase( $name );
                $display = new Display();
                echo $display->display( $tmp );
            }
            catch( Exception $e ) {
                $this->error( $e->getMessage() );
                $this->error( $e->getTraceAsString() );
            }
        }

        /**
         * Get the console command arguments.
         *
         * @return array
         */
        protected function getArguments() {
            return array(
                array( 'name', InputArgument::REQUIRED, "The name of the database." )
            );
        }

        /**
         * Get the console command options.
         *
         * @return array
         */
        protected function getOptions() {
            return array();
        }
    }
