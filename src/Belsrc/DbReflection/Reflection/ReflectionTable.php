<?php namespace Belsrc\DbReflection\Reflection;

    class ReflectionTable {

        public $name;
        public $type;
        public $length;
        public $maxLength;
        public $increment;
        public $createdAt;
        public $updatedAt;
        public $checksum;
        public $options;
        public $comment;
        public $database;

        public function __construct() {

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
