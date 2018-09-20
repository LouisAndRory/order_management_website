<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cookie\StoreRequest;
use App\Http\Requests\Cookie\UpdateRequest;
use App\Models\Cookie;
use App\Services\Mutators\CookieMutator;
use DB;
use Illuminate\Http\Request;

class CookieController extends Controller
{
    public function index()
    {
        $cookies = Cookie::select([
            'id', 'name', 'slug', 'enabled'
        ])->get();

        return view('management.cookies',[
            'cookies' => $cookies
        ]);
    }

    public function store(StoreRequest $request)
    {
        $cookieMutator = new CookieMutator();
        $cookieMutator->store($request->all());

        return response()->json_created();
    }

    public function update(UpdateRequest $request, $id)
    {
        $cookieMutator = new CookieMutator();
        $cookieMutator->update($id, $request->all());

        return response()->json_updated();
    }

    public function delete($id)
    {
        $cookieMutator = new CookieMutator();
        try {
            $cookieMutator->delete($id);
            return response()->json_deleted();
        } catch (\Exception $e) {
            return response()->json_delete_failed();
        }
    }
}
