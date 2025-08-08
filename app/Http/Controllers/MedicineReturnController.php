<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReturnMedicineRequest;
use App\Repositories\Interfaces\MedicineReturnRepositoryInterface;
use App\Models\SaleItem;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;

class MedicineReturnController extends Controller
{
    protected $returnRepo;

    public function __construct(MedicineReturnRepositoryInterface $returnRepo)
    {
        $this->returnRepo = $returnRepo;
    }

    public function index()
    {
        return response()->json($this->returnRepo->getAll());
    }

    public function store(ReturnMedicineRequest $request)
    {
       $data = $request->validated();
        $return = $this->returnRepo->store($data);
        return response()->json(['message' => 'Medicine returned successfully', 'data' => $return], 201);
    }

    public function showBySale($saleId)
    {
        return response()->json($this->returnRepo->getBySaleId($saleId));
    }
}

