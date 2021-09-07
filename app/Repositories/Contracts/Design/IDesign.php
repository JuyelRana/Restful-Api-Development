<?php

namespace App\Repositories\Contracts\Design;

interface IDesign
{
    public function applyTags($id, array $data);

    public function allLive();
}
