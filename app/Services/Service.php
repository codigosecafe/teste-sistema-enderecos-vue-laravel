<?php

namespace App\Services;

use App\Traits\Validator;

abstract class Service
{
    use Validator;

    public $relations = [];
    public $relationsCount = [];

}
