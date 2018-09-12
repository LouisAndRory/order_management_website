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

        Response::macro('json_deleted', function ($value = []) {
            $returnArray = [
                'message' => 'Data deleted.'
            ];
            if (!empty($value)) {
                $returnArray = array_merge($returnArray, $value);
            }
            return Response::json($returnArray, 200);
        });

        Response::macro('json_create_failed', function($value = []) {
            $returnArray = [
                'message' => 'Create failed.'
            ];
            if (!empty($value)) {
                $returnArray = array_merge($returnArray, $value);
            }
            return Response::json($returnArray, 400);
        });

        Response::macro('json_update_failed', function($value = []) {
            $returnArray = [
                'message' => 'Update failed.'
            ];
            if (!empty($value)) {
                $returnArray = array_merge($returnArray, $value);
            }
            return Response::json($returnArray, 400);
        });

        Response::macro('json_deleted_failed', function($value = []) {
            $returnArray = [
                'message' => 'Delete failed.'
            ];
            if (!empty($value)) {
                $returnArray = array_merge($returnArray, $value);
            }
            return Response::json($returnArray, 400);
        });
    }
}