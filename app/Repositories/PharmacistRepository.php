<?php

namespace App\Repositories;

use App\Models\Pharmacist;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Repositories\Interfaces\PharmacistRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'is_admin'=>$data['is_admin']
        ]);

        return $pharmacist;
    }
    public function delete(int $id): bool
    {
        $pharmacist = Pharmacist::findOrFail($id);

        return $pharmacist->delete(); // works for soft or hard delete
    }

    public function login(array $credentials)
    {
        $pharmacist = Pharmacist::where('username', $credentials['username'])->first();

        if (! $pharmacist || ! Hash::check($credentials['password'], $pharmacist->password)) {
            return response()->json(['message' => 'invalid username or password'], 401);
        }


        $token = $pharmacist->createToken('pharmacist_token')->plainTextToken;

        return response()->json([
            'message' => 'Login Success',
            'pharmacist' => $pharmacist,
            'token' => $token,
            'Is_admin'=>$pharmacist->is_admin
        ]);
    }

    public function GetPharmacistSales(){

     $sales = Sale::with(['pharmacist', 'salesItems.medicine'])->get();
    return $sales;

}

    public function GetPharmacistPurchases(){


    $purchases = Purchase::with(['pharmacist', 'PurchaseItems.medicine'])->get();
    return $purchases;

}


    public function GetPharmacistProfile(){
        $pharmacist_id=Auth::id();

        $pharmacist=Pharmacist::find($pharmacist_id);

        return $pharmacist;
    }
    public function GetAllPharmacists(){

           return Pharmacist::all();

    }
}
