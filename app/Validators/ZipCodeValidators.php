<?php

namespace App\Validators;

use App\Entities\ZipCodeEntities;
/**
 * Class ModeloRules
 * @package App\Validators
 */
class ZipCodeValidators
{
    static public function store(): array
    {
        return [
            ZipCodeEntities::ZIP_CODE => ['required','string', 'unique:zip_code'],
        ];
    }
    static public function updateOrDestroy(): array
    {
        return [
            ZipCodeEntities::ZIP_CODE => ['required','string', 'exists:zip_code,zip_code'],
        ];
    }

    static public function messages()
    {
        return [
            ZipCodeEntities::ZIP_CODE.'.unique' =>  __('zipcode.CEP_JAH_CADASTRADO'),
            ZipCodeEntities::ZIP_CODE.'.exists' =>  __('zipcode.CEP_NAO_CADASTRADO')
        ];
    }
}
