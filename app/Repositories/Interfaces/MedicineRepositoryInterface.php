<?php

namespace App\Repositories\Interfaces;

interface MedicineRepositoryInterface
{
public function all();

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function findByName(string $name);

    public function getMedicinesByCategoryName(string $categoryName);
}
