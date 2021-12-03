<?php


namespace App\Http\Requests;


use Illuminate\Http\Request as RequestBase;

class Request extends RequestBase
{
    public function __construct(\Illuminate\Http\Request $request)
    {
        $query = $request->query->all();

        foreach ($query as $key => $item){
            if(is_array($item) && count($item) == 1 && $request->method() == 'GET'){
                if(strlen(preg_replace('/[0-9,]+/', '', $item[0])) <= 0){
                    $item = explode(',', $item[0]);
                };
            }
            $query[$key] = $item;
        }

        parent::__construct(
            $query,
            $request->toArray(),
            $request->attributes->all(),
            $request->cookies->all(),
            $request->files->all(),
            $request->server->all(),
            $request->content
        );
    }

    public function toCollection($attributes = [])
    {
        if(count($attributes) > 0){
            return collect($this->only($attributes));
        }

        return collect($this->all());
    }
}
