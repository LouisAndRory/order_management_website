<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 2018/9/5
 * Time: 上午10:43
 */

namespace App\Services\Mutators;


use App\Models\Cookie;

class CookieMutator implements MutatorContract
{
    public function store($data = [])
    {
        $cookie = Cookie::create($data);

        return $cookie;
    }

    public function update($id, $data = [])
    {
        $cookie = Cookie::findOrFail($id);
        $cookie->update($data);

        return $cookie;
    }

    public function delete($id)
    {
        
    }
}