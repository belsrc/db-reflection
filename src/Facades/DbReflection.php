<?php namespace Belsrc\DbReflection\Facades;

use Illuminate\Support\Facades\Facade;

class DbReflection extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'db-reflection';
    }
}
