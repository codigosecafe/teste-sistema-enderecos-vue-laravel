<?php
/**
 * Created by PhpStorm.
 * User: DevMaker BackEnd
 * Date: 17/04/2018
 * Time: 15:58
 */

namespace App\Traits;


use App\Entities\Entity;

trait CheckOriginalAttribute
{
    public function isEqual($field, Entity $model)
    {
        if(is_array($field)){
           return $this->handlerArrayIsEqual($field, $model);
        }

        return $model->getOriginal($field) == $model->$field;
    }

    public function handlerArrayIsEqual($field, $model)
    {
        foreach ($field as $item){
            if(!$this->isEqual($item, $model)){
                return false;
            }
        }
        return true;
    }
}