<?php

namespace App\Repositories\Contracts\Design;

interface IDesign
{
    public function applyTags($id, array $data);

    public function addComment($designId, array $data);

    public function like($id);

    public function isLikedByUser($id);
}
