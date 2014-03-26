<?php namespace Belsrc\DbReflection\Commands;

    use Symfony\Component\Console\Input\InputOption;
    use Symfony\Component\Console\Input\InputArgument;
    use Belsrc\DbReflection\DbReflection;
    use Belsrc\DbReflection\Query\Query;

    class ReflectColumnCommand extends BaseReflectCommand {

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
         * Execute the console command.
         *
         * @return mixed
         */
        public function fire() {
            try {
                $pdo  = $this->getPdoConnection();
                $path = $this->argument( 'path' );
                $dbr  = new DbReflection( new Query( $pdo ) );
                $tmp  = $dbr->getColumn( $path );

                echo $this->_display->display( $tmp );
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
