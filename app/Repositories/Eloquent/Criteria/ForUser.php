<?php

namespace App\Repositories\Eloquent\Criteria;

use App\Repositories\Criteria\ICriterion;

class ForUser implements ICriterion
{
    protected $user_id;

    /**
     * @param $user_id
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @param $model
     * @return mixed
     */
    public function apply($model)
    {
        return $model->where('user_id', $this->user_id);
    }
}
