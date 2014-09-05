<?php namespace Belsrc\DbReflection\Display;

class Display implements IDisplayable {

    /**
     * Gets the length of the longest property name and value.
     *
     * @param  Object $obj The object to scan through.
     * @return array
     */
    private function getLongestItems($obj) {
        $propCount = 0;
        $valCount = 0;

        foreach($obj as $key => $val) {
            if(strlen($key) > $propCount) {
                $propCount = strlen($key);
            }

            if(is_array($val)) {
                if(strlen(count($val)) > $valCount) {
                    $valCount = strlen(count($val));
                }
            }
            else {
                if(strlen($val) > $valCount) {
                    $valCount = strlen($val);
                }
            }
        }

        return array(
            'prop' => $propCount,
            'val' => $valCount
        );
    }

    /**
     * Builds a horizontal dashed border the width of the table.
     *
     * @param  int $propLen The length of the longest property name.
     * @param  int $valLen  The length of the longest property value.
     * @return string
     */
    private function buildHorizontalBorder($propLen, $valLen) {
        // Add 4 for the pipe and space on each side
        $propLen += 4;

        // Add 3 for the space on each side and trailing pipe
        $valLen += 3;

        $total = $propLen + $valLen;
        $str = '';
        for($i = 0; $i < $total; $i++) {
            $str .= '-';
        }

        return $str . "\n";
    }

    /**
     * Builds a row of the display table.
     *
     * @param  string $key    The property name.
     * @param  string $val    The property value.
     * @param  int    $keyMax The length of the longest property name.
     * @param  int    $valMax The length of the longest property value.
     * @return string
     */
    private function buildRow($key, $val, $keyMax, $valMax) {
        $keyPad = $keyMax - strlen($key);
        if(is_array($val)) {
            if(is_object($val[0]) && get_class($val[0]) == 'Belsrc\DbReflection\Reflection\ReflectionConstraint') {
                $val = $val[0]->type;
            }
            else {
                $val = count($val);
            }
        }

        $valPad = $valMax - strlen($val);

        $str = "| $key";
        for($i = 0; $i < $keyPad; $i++) {
            $str .= ' ';
        }

        $str .= " | $val";
        for($i = 0; $i < $valPad; $i++) {
            $str .= ' ';
        }

        $str .= " |\n";

        return $str;
    }

    /**
     * Displays an objects properties in a simple ASCII grid format.
     *
     * @param  Object $obj An object instance.
     * @return string
     */
    public function display($obj) {
        $maxes = $this->getLongestItems($obj);
        $horizBorder = $this->buildHorizontalBorder($maxes['prop'], $maxes['val']);
        $str = "\n" . $horizBorder;

        foreach($obj as $key => $val) {
            $str .= $this->buildRow($key, $val, $maxes['prop'], $maxes['val']);
        }

        $str .= $horizBorder . "\n";

        return $str;
    }
}
