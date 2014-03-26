<?php namespace Belsrc\DbReflection\Reflection;

    class ReflectionTable {

        public function __construct() {

        }

        /**
         * Gets a class property.
         *
         * @param  mixed $property The name of the property.
         * @return mixed           The value of the property.
         */
        public function __get( $property ) {
            // If there is a getter method for the property
            // call that method. Lets you drop the parenthesis and
            // acts a little more like other language getters.
            $m = 'get' . ucwords( $property );
            if( method_exists( $this, $m ) ) {
                return $this->$m();
            }

            if( property_exists( $this, $property ) ) {
                return $this->$property;
            }

            throw new ErrorException( 'Unknown property', 1 );
        }
    }
