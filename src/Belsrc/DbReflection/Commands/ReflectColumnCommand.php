<?php namespace Belsrc\DbReflection\Commands;

    use Illuminate\Console\Command;
    use Symfony\Component\Console\Input\InputOption;
    use Symfony\Component\Console\Input\InputArgument;
    use Belsrc\DbReflection\DbReflection;
    use Belsrc\DbReflection\Query\Query;
    use Belsrc\DbReflection\Display\Display;

    class ReflectColumnCommand extends Command {

        /**
         * The console command name.
         *
         * @var string
         */
        protected $name = 'reflect:column';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Get the information about a particular column.';

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
                $path = $this->argument( 'path' );
                $dbr  = new DbReflection( new Query() );
                $tmp  = $dbr->getColumn( $path );
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
                array( 'path', InputArgument::REQUIRED, "The 'path' of the column. (i.e. 'database.table.column')" ),
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
