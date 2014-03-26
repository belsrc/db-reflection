<?php namespace Belsrc\DbReflection\Display;

    interface IDisplayable {

        /**
         * Displays an objects properties.
         *
         * @param  Object $obj An object instance.
         */
        public function display( $obj );
    }
