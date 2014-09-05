<?php namespace Belsrc\DbReflection\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Belsrc\DbReflection\DbReflection;
use Belsrc\DbReflection\Query\Query;

class ReflectDatabaseCommand extends BaseReflectCommand {

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        try {
            $pdo  = $this->getPdoConnection();
            $name = $this->argument('name');
            $with = $this->option('with');
            $dbr  = new DbReflection(new Query($pdo));
            $tmp  = $dbr->getDatabase($name, $with);

            echo $this->_display->display($tmp);
        }
        catch(Exception $e) {
            $this->error($e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return array(
            array('name', InputArgument::REQUIRED, "The name of the database."),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return array(
            array('with', null, InputOption::VALUE_IS_ARRAY, "With Table or Column."),
        );
    }
}
