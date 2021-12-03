<?php

namespace App\Rules;

use App\Traits\AttributesMasks;
use App\Entities\ZipCodeEntities;
use Illuminate\Contracts\Validation\Rule;

class ZipCodeRules implements Rule
{
    use AttributesMasks;
    protected $model;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $idServico = 0)
    {
        $this->model = new ZipCodeEntities();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->validateCodigoIfExists($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O Código preenchido já existe';
    }

    public function validateCodigoIfExists($value)
    {
        return $this->model::where($this->model::COD_SERVICO, $value)
                ->where($this->model::ID, '!=', $this->idServico)
                    ->where($this->model::EMPRESA_ID, $this->idEmpresa)->count() ? false : true;

    }
}
