<?php namespace Belsrc\DbReflection\Reflection;

    class ReflectionDatabase {

        public $name;
        public $charset;
        public $collation;

        public function __construct() {

        }

        public function tables() {

        }

        /**
         * Gets a class property.
         *
         * @param  mixed $property The name of the property.
         * @return mixed           The value of the property.
         */
        public function __get( $property ) {
            if( method_exists( $this, $property ) ) {
                return $this->$property();
            }

            if( property_exists( $this, $property ) ) {
                return $this->$property;
            }

            throw new ErrorException( 'Unknown property', 1 );
        }
    }
