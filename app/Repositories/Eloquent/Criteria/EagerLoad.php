<?php

namespace App\Repositories\Eloquent\Criteria;

use App\Repositories\Criteria\ICriterion;

class EagerLoad implements ICriterion
{
    protected $relationships;

    /**
     * @param $relationships
     */
    public function __construct($relationships)
    {
        $this->relationships = $relationships;
    }

    /**
     * @param $model
     * @return mixed
     */
    public function apply($model)
    {
        return $model->with($this->relationships);
    }
}
