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
        public $key;
        public $extra;
        public $privileges;
        public $comment;
        public $parentItem;

        /**
         * Gets a class property.
         *
         * @param  mixed $property The name of the property.
         * @return mixed           The value of the property.
         */
        public function __get( $property ) {
            if( method_exists( $this, $property ) ) {
                return $this->$m();
            }

            if( property_exists( $this, $property ) ) {
                return $this->$property;
            }

            throw new ErrorException( 'Unknown property', 1 );
        }


        public function set( $name, $value ) {
            // write the value to db.table.column
            // maybe...if i can figure out a  good way to do it
        }
    }
