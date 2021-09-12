<?php

namespace App\Repositories\Eloquent\Criteria;

use App\Repositories\Criteria\ICriterion;

class WithTrashed implements ICriterion
{

    /**
     * @param $model
     * @return mixed
     */
    public function apply($model)
    {
        return $model->withTrashed();
    }
}
