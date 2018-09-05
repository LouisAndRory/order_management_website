<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cookie\StoreRequest;
use App\Http\Requests\Cookie\UpdateRequest;
use App\Models\Cookie;
use App\Services\Mutators\CookieMutator;
use Illuminate\Http\Request;

class CookieController extends Controller
{
    public function index()
    {
        $cookies = Cookie::all();

        return response()->json([
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
}
