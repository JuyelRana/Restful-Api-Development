<?php

namespace App\Repositories\Eloquent\Criteria;

use App\Repositories\Criteria\ICriterion;

class IsLive implements ICriterion
{

    /**
     * @param $model
     * @return mixed
     */
    public function apply($model)
    {
        return $model->where('is_live', true);
    }
}
