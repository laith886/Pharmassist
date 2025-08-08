<?php
namespace App\Repositories\Interfaces;

interface MedicineReturnRepositoryInterface
{
    public function getAll();
    public function store(array $data);
    public function getBySaleId($saleId);
}
