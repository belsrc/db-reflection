<?php namespace Belsrc\DbReflection\Reflection;

class ReflectionColumn {

    public $name;
    public $position;
    public $defaultValue;
    public $isNullable;
    public $dataType;
    public $precision;
    public $maxLength;
    public $columnType;
    public $charSet;
    public $extra;
    public $privileges;
    public $comment;
    public $table;
    public $database;

    public $constraints;
}
