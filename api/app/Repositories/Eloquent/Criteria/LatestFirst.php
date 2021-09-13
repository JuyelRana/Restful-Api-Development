<?php

namespace App\Repositories\Eloquent\Criteria;

use App\Repositories\Criteria\ICriterion;

class LatestFirst implements ICriterion
{

    /**
     * @param $model
     * @return mixed
     */
    public function apply($model)
    {
        return $model->latest();
    }
}
