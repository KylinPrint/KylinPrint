<?php

namespace App\Admin\Repositories;

use App\Models\Project_Tag_Bind as Project_Tag_BindModel;
use Dcat\Admin\Repositories\EloquentRepository;

class Project_Tag_Bind extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Project_Tag_BindModel::class;
}
