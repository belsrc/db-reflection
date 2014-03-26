<?php namespace Belsrc\DbReflection\Reflection;

    // IDE auto complete jazz
    /**
     * @property name         string The columns name.
     * @property position     int    The columns ordinal position.
     * @property defaultValue mixed  The default value for this column.
     * @property isNullable   string Whether or not this column is nullable.
     * @property dataType     string The data type of this column.
     * @property precision    int    The numeric precision of this column.
     * @property maxLength    int    The max character length of this column.
     * @property columnType   string The full column type for this column (varchar(12) or decimal(9,6))
     * @property charSet      string The character set for this column.
     * @property key          string The key type, if any, for this column.
     * @property extra        string Any extra information for this column.
     * @property privileges   string Privileges for this column.
     * @property comment      string Comments for this column.
     * @property parentItem   string The name of the parent table.
     */
    class ReflectionColumn {

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


        public function set( $name, $value ) {
            // write the value to db.table.column
            // maybe...if i can figure out a  good way to do it
        }
    }
