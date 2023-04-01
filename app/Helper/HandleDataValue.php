<?php

namespace App\Helper;

trait HandleDataValue {

    use HandlesDataBoundValues;

    private function setData(string $name, ?Model $bind = null)
    {
        if ($bind) {
            $bind = $bind ?: $this->getBoundTarget();
        }

        $boundValue = $this->getBoundValue($bind, $name);
        $default = is_null($boundValue) ? '' : $boundValue;

        if($this->translate) {
            $default = __($default);
        }

        if($this->append) {
            if(is_array($this->append)) {
                $arr = [];
                foreach ($this->append as $field) {
                    $arr[] = $this->getBoundValue($bind, $field);
                }
                $seperator = $this->seperator ?? ' ';
                $default .= $seperator . implode($seperator, $arr);
            } elseif (is_string($this->append)) {
                $seperator = $this->seperator ?? ' ';
                $default .= $seperator . $this->append;
            }
        }
        if($this->fon) {
            $this->link = 'tel:' . $default;
        }
        if($this->email) {
            $emailValue = $this->getBoundValue($bind, $this->email === '1' ? $name : $this->email);
            $this->link = 'mailto:' . $emailValue;
        }

        $this->data =  old($name, $default);
    }
}
