<?php
namespace App\Http\Resources;

use App\Http\Resources\Resource;

class ZipCodeSearchResource extends Resource
{

    public function toResource($resource)
    {
        return [

            'id'                 =>  $resource->getKey(),
            'cep'                =>  $this->makeMaskZipCode($resource->zip_code),
            'logradouro'         =>  $resource->street,
            'complemento'        =>  $resource->complement,
            'bairro'             =>  $resource->neighborhood,
            'cidade'             =>  $resource->city,
            'estado'             =>  $resource->state,
            'ibge'               =>  $resource->ibge,
            'ddd'                =>  $resource->ddd,
            'google_map'         =>  $resource->street_map,
            'cadastrado_em' => [
                'timestamp'      => $resource->created_at->format('Y-m-d H:i:s'),
                'dia_formatado'  => $resource->created_at->format('d/m/Y'),
                'hora_formatado' => $resource->created_at->format('H:i:s')
            ],
            'atualizado_em' => [
                'timestamp'      => $resource->updated_at->format('Y-m-d H:i:s'),
                'dia_formatado'  => $resource->updated_at->format('d/m/Y'),
                'hora_formatado' => $resource->updated_at->format('H:i:s')
            ]
        ];
    }
}
