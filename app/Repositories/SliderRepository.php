<?php

namespace App\Repositories;

use App\Models\Slider;

class SliderRepository extends BaseRepository implements SliderRepositoryInterface
{
    public function __construct(Slider $model)
    {
        parent::__construct($model);
    }
}
