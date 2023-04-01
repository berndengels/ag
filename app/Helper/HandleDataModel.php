<?php

namespace App\Helper;

use Illuminate\Database\Eloquent\Model;

trait HandleDataModel {

    use HandlesDataBoundValues;

    private function setModelInstance($bind = null)
    {
        if ($bind) {
            $this->modelInstace = $bind ?: $this->getBoundTarget();
        }
    }
}
