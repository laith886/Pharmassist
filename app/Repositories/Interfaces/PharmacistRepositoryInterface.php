<?php

namespace App\Repositories\Interfaces;

interface PharmacistRepositoryInterface
{
  public function all();
    public function find($id);
    public function register(array $data);
    public function delete(int $id): bool;
    public function login(array $credentials);
    public function GetPharmacistSales();

     public function GetPharmacistPurchases();

     public function  GetPharmacistProfile();

     public function GetAllContacts();

     public function GetAllPharmacists();
     public function update(int $id, array $data);

}
