<?php

namespace App\Admin\Repositories;

use App\Models\ProjectTag as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Project_Tag extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
