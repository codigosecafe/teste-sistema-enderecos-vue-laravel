<?php

namespace App\Entities;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Traits\AttributesMasks;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Entity extends Model
{
    use AttributesMasks;

    const ADD_FIELD_IF_NOT_EXIST    = true;
    const METHOD_UPDATE    = true;

    public function fieldsForCreator(Collection $dataInput, $isUpdate = false): array
    {
        $fillable = [];

        foreach($this->getFillable() as $key => $val) {
            $fillable[$val] = (!$isUpdate)? $dataInput->get($val) : (($dataInput->has($val))? ((!empty($dataInput->get($val)))? $dataInput->get($val) : '') : null);
            $fillable[$val]= $this->removeMascaraFields($val, $fillable[$val]);
        }

        foreach ($fillable as $key => $val) {
            if( $val === null){
                unset($fillable[$key]);
            }
        }
      return $fillable;
    }

    public function removeMascaraFields($key, $value)
    {
       $camposRemoveMask = ['zip_code', 'ibge', 'ddd'];
        if(in_array($key,$camposRemoveMask) && !empty($value)){
            $value = $this->removeMask($value);
        }
      return Str::upper($value);
    }

    public function setValueForField(&$fields, $fildCheck, $defaultValue, $createField = false)
    {
        if(array_key_exists($fildCheck, $fields)){
            $fields[$fildCheck] = (!empty($fields[$fildCheck]) || (is_numeric($fields[$fildCheck]) && $fields[$fildCheck] == 0) )? $fields[$fildCheck] : $defaultValue;
            return;
        }
        if($createField){
            $fields[$fildCheck] = $defaultValue;
        }
    }




}
