<?php
namespace App\Services;


class AddressService
{

    public $zip_code;
    public $street;
    public $complement;
    public $neighborhood;
    public $city;
    public $state;
    public $ibge;
    public $ddd;
    public $gia;
    public $siafi;

    public function __construct(array $responseViaCep = [])
    {
        $this->fill($responseViaCep);
    }

    public function fill(array $responseViaCep)
    {
        if ($responseViaCep) {
            $this->zip_code      = $responseViaCep['cep'];
            $this->street       = $responseViaCep['logradouro'];
            $this->complement   = $responseViaCep['complemento'];
            $this->neighborhood = $responseViaCep['bairro'];
            $this->city         = $responseViaCep['localidade'];
            $this->state        = $responseViaCep['uf'];
            $this->ibge         = $responseViaCep['ibge'];
            $this->ddd          = $responseViaCep['ddd'];
            $this->gia          = $responseViaCep['gia'];
            $this->siafi          = $responseViaCep['siafi'];
        }

        return $this;
    }

    public function toJson()
    {
        return json_encode(get_object_vars($this));
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}
