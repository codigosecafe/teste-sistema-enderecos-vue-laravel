<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Services\ZipCodeSearchService;
use App\Http\Resources\ZipCodeSearchResource;


class ZipCodeSearchController extends Controller
{

    private $zipCodeSearchService;
    public function __construct(ZipCodeSearchService $zipCodeSearchService)
    {
        $this->zipCodeSearchService = $zipCodeSearchService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = $this->zipCodeSearchService->index($request->toCollection());
        return new ZipCodeSearchResource($model);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): ZipCodeSearchResource
    {
        $model = $this->zipCodeSearchService->store($request->toCollection());
        return new ZipCodeSearchResource($model);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($zipCode)
    {
        $model = $this->zipCodeSearchService->show($zipCode);
        return new ZipCodeSearchResource($model);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($zipCode)
    {
        $model = $this->zipCodeSearchService->update($zipCode);
        return new ZipCodeSearchResource($model);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($zipCode)
    {
        $data = $this->zipCodeSearchService->destroy($zipCode);
        return response()->json(['status' => 'success', 'message' => __('zipcode.ENDERECO_DELETADO_COM_SUCESSO') ]);
    }
}
