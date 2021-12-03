<?php
namespace App\Services;

use GuzzleHttp\Client;
use App\Services\Service;
use GuzzleHttp\ClientInterface;
use App\Services\AddressService;
use Illuminate\Http\JsonResponse;
use App\Exceptions\ZipCodeException;

class ViaCepService extends Service
{
    protected $http;
    /**
    * ViaCepService constructor.
    */

    public function __construct(ClientInterface $http = null)
    {
        $this->http = $http ?: new Client;
    }

    public function find($zipCode)
    {
        try {
            $url = sprintf('https://viacep.com.br/ws/%s/json', $zipCode);

            $response = $this->http->request('GET', $url);

            $attributes = json_decode($response->getBody(), true);
            if (array_key_exists('erro', $attributes) && $attributes['erro'] === true) {
                throw new ZipCodeException(__('zipcode.CEP_NAO_ENCONTRADO_OU_INVALIDO'), JsonResponse::HTTP_BAD_REQUEST);
            }
            return new AddressService($attributes);
        } catch (\Throwable $th) {
            throw new ZipCodeException(__('zipcode.CEP_NAO_ENCONTRADO_OU_INVALIDO'), JsonResponse::HTTP_BAD_REQUEST);
        }

    }

}
