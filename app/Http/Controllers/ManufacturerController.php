<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use App\Repositories\Interfaces\ManufactururRepositoryInterface;
use App\Repositories\Interfaces\MedicineRepositoryInterface;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    protected $ManufacturerRepository;

    public function __construct(ManufactururRepositoryInterface $ManufacturerRepository)
    {
        $this->ManufacturerRepository = $ManufacturerRepository;
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Manufacturer $manufacturer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Manufacturer $manufacturer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Manufacturer $manufacturer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Manufacturer $manufacturer)
    {
        //
    }

    public function GetMedicines(string $ManuName){

        return $this->ManufacturerRepository->GetManufacturerMedicines($ManuName);


    }
}
