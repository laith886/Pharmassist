<?php

namespace App\Http\Controllers;

use App\Http\Requests\PharmacistRequests\RegisterPharmacistRequest;
use App\Http\Requests\PharmacistRequests\UpdatePharmacistRequest;
use App\Models\Pharmacist;
use App\Repositories\Interfaces\PharmacistRepositoryInterface;
use Illuminate\Http\Request;

class PharmacistController extends Controller
{


    protected $pharmacistRepository;

    public function __construct(PharmacistRepositoryInterface $PharmacistRepository)
    {
        $this->pharmacistRepository = $PharmacistRepository;
    }
    public function index()
    {

    }

    public function store(RegisterPharmacistRequest $request)
    {
        return $this->pharmacistRepository->register($request->validated());

    }

    public function show(int $id)
    {
       return $this->pharmacistRepository->find($id);
    }


    public function update(UpdatePharmacistRequest $request, int $id)
    {
        $pharmacist = $this->pharmacistRepository->update($id, $request->validated());

        return response()->json([
            'message' => 'Pharmacist updated successfully.',
            'data' => $pharmacist
        ]);
    }


    public function destroy(int $id)
    {
        $pharmacist=Pharmacist::find($id);

        if($pharmacist){

        }
    }


    public function create(RegisterPharmacistRequest $request){
        return $this->pharmacistRepository->register($request->validated());

    }
    public function login(Request $request){

        return $this->pharmacistRepository->login( $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:8',
        ]));
    }



}
