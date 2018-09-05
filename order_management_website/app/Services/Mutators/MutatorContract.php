<?php
/**
 * Created by PhpStorm.
 * User: louis
 * Date: 2018/9/5
 * Time: 上午10:50
 */

namespace App\Services\Mutators;


interface MutatorContract
{
    public function store($data = []);

    public function update($id, $data = []);

    public function delete($id);
}