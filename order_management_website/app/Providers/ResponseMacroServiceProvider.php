<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseMacroServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Response::macro('json_created', function ($value = []) {
            $returnArray = [
                'message' => 'Data created.'
            ];
            if (!empty($value)) {
                $returnArray = array_merge($returnArray, $value);
            }
            return Response::json($returnArray, 201);
        });

        Response::macro('json_updated', function ($value = []) {
            $returnArray = [
                'message' => 'Data updated.'
            ];
            if (!empty($value)) {
                $returnArray = array_merge($returnArray, $value);
            }
            return Response::json($returnArray, 200);
        });

        Response::macro('json_delete', function ($value = []) {
            $returnArray = [
                'message' => 'Data deleted..'
            ];
            if (!empty($value)) {
                $returnArray = array_merge($returnArray, $value);
            }
            return Response::json($returnArray, 200);
        });
    }
}