<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicinesRequests\StoreMedicineRequest;
use App\Http\Requests\MedicinesRequests\UpdateMedicineRequest;
use App\Http\Resources\GetAllMedicines;
use App\Repositories\Interfaces\MedicineRepositoryInterface;

class MedicineController extends Controller
{
    protected $medicineRepository;

    public function __construct(MedicineRepositoryInterface $medicineRepository)
    {
        $this->medicineRepository = $medicineRepository;
    }

//-------------------------CRUD--------------------------
    public function index()
    {
        $medicines= $this->medicineRepository->all();
        return  GetAllMedicines::collection($medicines);
    }

    public function store(StoreMedicineRequest $request)
    {
        $this->medicineRepository->create($request->validated());
    }

    public function show(int $id)
    {
        return $medicine = $this->medicineRepository->find($id);
    }

    public function update(UpdateMedicineRequest $request, $id)
    {
        $updatedMedicine = $this->medicineRepository->update($id, $request->validated());

        return response()->json([
            'message' => 'Medicine updated successfully.',
            'data' => $updatedMedicine
        ], 200);
    }

    public function destroy(int $id)
    {
        $this->medicineRepository->delete($id);
        return response()->json(['message'=>'Medicine deleted successfully'],200);
    }
//-------------------------END CRUD--------------------------

    public function showByName(string $name)
    {
        $medicine = $this->medicineRepository->findByName($name);

       if ($medicine->isEmpty()) {
        return response()->json(['message' => 'Medicine Not found'], 404);
    }


        return  GetAllMedicines::collection($medicine);
    }


    public function getMedicinesByCategoryName($categoryName)
    {
        if($categoryName=='All'){
            return $this->index();
        }
        $medicines = $this->medicineRepository->getMedicinesByCategoryName($categoryName);

        if ($medicines->isEmpty()) {
            return response()->json(['message' => 'No medicines found or category does not exist.'], 404);
        }

        return GetAllMedicines::collection($medicines);
    }





}
