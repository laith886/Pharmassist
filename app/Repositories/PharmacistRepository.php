<?php

namespace App\Repositories;

use App\Models\Pharmacist;
use App\Repositories\Interfaces\PharmacistRepositoryInterface;

class PharmacistRepository implements PharmacistRepositoryInterface
{

    public function all(){
        return Pharmacist::all();
    }
    public function find($id){
        return Pharmacist::find($id);
    }

    public function update(int $id, array $data)
    {
        $pharmacist = Pharmacist::findOrFail($id);
        $pharmacist->update($data);
        $pharmacist->refresh();
        return $pharmacist;
    }
    public function register(array $data){

        $pharmacist = Pharmacist::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'username'   => $data['username'],
            'password'   => bcrypt($data['password']),
            'phone'      => $data['phone'],
            'employment_date' => now(),
            'salary' =>$data['salary'],
        ]);

        return $pharmacist;
    }
    public function delete(int $id): bool
    {
        $pharmacist = Pharmacist::findOrFail($id);

        return $pharmacist->delete(); // works for soft or hard delete
    }


}
