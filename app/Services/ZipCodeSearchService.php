<?php
namespace App\Services;

use Cache;

use App\Services\Service;
use Illuminate\Support\Str;
use App\Services\ViaCepService;
use App\Entities\ZipCodeEntities;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use App\Exceptions\ZipCodeException;
use App\Validators\ZipCodeValidators;


class ZipCodeSearchService extends Service
{
    private $zipCodeEntities;
    /**
    * BuscaCepService constructor.
    */
    public function __construct(ZipCodeEntities $zipCodeEntities)
    {
        $this->zipCodeEntities = $zipCodeEntities;
    }
    /**
     * Metodo responsavel por criar ou atualizar um registro
     *
     * @param [type] $zipCode
     * @param boolean $isUpdate
     * @param [type] $entity
     * @return void
     */
    private function findAndCreateOrUpdate($zipCode, $isUpdate = false, $entity = null){
        $responseViaCEP= (new ViaCepService())->find($zipCode)->toArray();
        $dataToSave = $this->zipCodeEntities->fieldsForCreator(collect($responseViaCEP), $isUpdate);
        return ($isUpdate)? tap($entity)->update($dataToSave) : $this->zipCodeEntities->create($dataToSave);
    }

    /**
     * Metodo responsavel por listar os endereços cadastrados
     *
     * @param Collection|null $filters
     * @return void
     */
   public function index(Collection $filters = null){
        if (!$filters) {$filters = collect();}

        $query = $this->zipCodeEntities::with([]);
        if ($search = Str::upper($filters->get('searchTerm'))) {
            $query->where(function($query) use ($search){
                $query->where($this->zipCodeEntities::ZIP_CODE,         'like', "%$search%");
                $query->orWhere($this->zipCodeEntities::STREET,         'like', "%$search%");
                $query->orWhere($this->zipCodeEntities::COMPLEMENT,     'like', "%$search%");
                $query->orWhere($this->zipCodeEntities::NEIGHBORHOOD,   'like', "%$search%");
                $query->orWhere($this->zipCodeEntities::CITY,           'like', "%$search%");
                $query->orWhere($this->zipCodeEntities::STATE,          'like', "%$search%");
                $query->orWhere($this->zipCodeEntities::IBGE,           'like', "%".$this->zipCodeEntities->removeMask($search)."%");
                $query->orWhere($this->zipCodeEntities::DDD,            'like', "%".$this->zipCodeEntities->removeMask($search)."%");
            });
        }
        $order = $filters->get('order', 'asc');
        $sortBy = $filters->get('sort', $this->zipCodeEntities::CITY);
        $limit = $filters->get('limit', 0);
        $query->orderBy($sortBy, $order);
        return $limit > 0 ? $query->paginate($limit) : $query->get();
   }

   /**
    * Metodo responsavel por exibir um endereço já cadastrado ou buscar no ViaCep e cadastralo para exibir para o usuario
    *
    * @param [type] $zipCode
    * @return void
    */
   public function show($zipCode){
        $zipCode = $this->zipCodeEntities->removeMaskZipCode($zipCode);
        $nameCache = md5('zipCode_'.$zipCode);
        $dataCache = Cache::remember($nameCache, 7200, function () use ($zipCode) {
            $dataZipCode = $this->zipCodeEntities->where($this->zipCodeEntities::ZIP_CODE, $zipCode)->first();
            return ((!empty($dataZipCode))? $dataZipCode : $this->findAndCreateOrUpdate($zipCode));
        });

        if(empty($dataCache)){
            Cache::forget($nameCache);
            throw new ZipCodeException(__('zipcode.CEP_NAO_ENCONTRADO_OU_INVALIDO'), JsonResponse::HTTP_BAD_REQUEST);
        }
        return $dataCache;
   }

   /**
    * Metodo responsavel por cadastrar um novo endereço desde que ele não exista em nossa base
    *
    * @param Collection $request
    * @return void
    */
   public function store(Collection $request){
        $this->validateWithArray($request->toArray(), ZipCodeValidators::store(), ZipCodeValidators::messages() );
        return $this->show($request->get($this->zipCodeEntities::ZIP_CODE));
   }

   /**
    * Metodo responsavel por atualizar um novo endereço desde que ele exista em nossa base
    *
    * @param [type] $zipCode
    * @return void
    */
   public function update($zipCode){
        $zipCode = $this->zipCodeEntities->removeMaskZipCode($zipCode);
        $this->validateWithArray([$this->zipCodeEntities::ZIP_CODE => $zipCode], ZipCodeValidators::updateOrDestroy(), ZipCodeValidators::messages() );
        $zipCodeEntities = $this->zipCodeEntities->where($this->zipCodeEntities::ZIP_CODE, $zipCode)->first();
        Cache::forget(md5('zipCode_'.$zipCode));
        return $this->findAndCreateOrUpdate($zipCode, $this->zipCodeEntities::METHOD_UPDATE, $zipCodeEntities);
   }
   /**
    * Metodo responsavel por deletar um endereço de nossa base
    *
    * @param [type] $zipCode
    * @return void
    */
   public function destroy($zipCode){
        $zipCode = $this->zipCodeEntities->removeMaskZipCode($zipCode);
        $this->validateWithArray([$this->zipCodeEntities::ZIP_CODE => $zipCode], ZipCodeValidators::updateOrDestroy(), ZipCodeValidators::messages() );
        Cache::forget(md5('zipCode_'.$zipCode));
        return $this->zipCodeEntities->where($this->zipCodeEntities::ZIP_CODE, $zipCode)->forceDelete();
   }

}
