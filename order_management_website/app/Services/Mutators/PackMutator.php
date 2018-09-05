<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 2018/9/5
 * Time: 下午12:09
 */

namespace App\Services\Mutators;


use App\Models\Pack;

class PackMutator implements MutatorContract
{

    public function store($data = [])
    {
        $pack = Pack::create($data);

        return $pack;
    }

    public function update($id, $data = [])
    {
        $pack = Pack::findOrFail($id);
        $pack->update($data);

        return $pack;
    }

    public function delete($id)
    {

    }
}