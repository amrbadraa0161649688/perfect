<?php

namespace App\Repositories\Eloquent;

use App\Models\Locations;
use App\Repositories\Interfaces\LocationRepositoryInterface;

class LocationRepository extends BaseRepository implements LocationRepositoryInterface
{
    public function __construct(Locations $model)
    {
        parent::__construct($model);
    }
}
