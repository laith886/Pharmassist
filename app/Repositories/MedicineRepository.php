<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Medicine;
use App\Repositories\Interfaces\MedicineRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MedicineRepository implements MedicineRepositoryInterface
{
    public function all()
    {
        return  Medicine::all();
    }
    public function find($id)
    {
        return Medicine::findOrFail($id);
    }

    public function create(array $data)
    {
        return Medicine::create($data);
    }

    public function update($id, array $data)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->update($data);
        $medicine->refresh();
        return $medicine;
    }
    public function delete($id)
    {
        $medicine = Medicine::findOrFail($id);
        return $medicine->delete();
    }
    public function findByName(string $name)
    {
        $medicine = Medicine::whereRaw('BINARY LOWER(Name) = {name}', [strtolower($name)])->firstOrFail();

        if (!$medicine) {
            return null;
        }

        return $medicine;
    }
    public function getMedicinesByCategoryName(string $categoryName)
    {
        $category = Category::where('name', $categoryName)->first();

        if (!$category) {
            return null;
        }

        $medicines = $category->medicines;

        return $category->medicines;
    }

}
