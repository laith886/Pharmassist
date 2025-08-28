<?php

namespace App\Http\Controllers;

use App\Http\Requests\PharmacistRequests\RegisterPharmacistRequest;
use App\Http\Requests\PharmacistRequests\UpdatePharmacistRequest;
use App\Http\Resources\GetPharmacistProfile;
use App\Http\Resources\GetPharmacistPurchase;
use App\Http\Resources\GetPharmacistSales;
use App\Models\Pharmacist;
use App\Models\SaleItem;
use App\Repositories\Interfaces\PharmacistRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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


   public function update(UpdatePharmacistRequest $request, int $id){
    $data = $request->validated();

    // معالجة كلمة المرور إن وُجدت
    if (array_key_exists('password', $data)) {
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
    }

    // استدعاء الريبو الصحيح
    $pharmacist = $this->pharmacistRepository->update($id, $data);

    // عدم إرجاع كلمة المرور
    unset($pharmacist->password);

    return response()->json([
        'message'     => 'Pharmacist updated successfully',
        'pharmacist'  => $pharmacist,
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

    public function GetPharmacistSales(){
       $saleItems= $this->pharmacistRepository->GetPharmacistSales();

        if (!$saleItems) {
        return response()->json(['message' => 'Not Found'], 404);
    }

    return  GetPharmacistSales::collection($saleItems);
    }

     public function GetPharmacistPurchase(){
       $PurchaseItems= $this->pharmacistRepository->GetPharmacistPurchases();

        if (!$PurchaseItems) {
        return response()->json(['message' => 'Not Found'], 404);
    }

        //return  GetPharmacistSales::collection($PurchaseItems);
        return GetPharmacistPurchase::collection($PurchaseItems);
    }

    public function GetPharmacistProfile(){
        $PharmacistProfile=$this->pharmacistRepository->GetPharmacistProfile();

       return response()->json(['data'=>new GetPharmacistProfile($PharmacistProfile)]);


    }
    public function GetAllPharmacists(){
        $pharmacists=$this->pharmacistRepository->GetAllPharmacists();

        return GetPharmacistProfile::collection($pharmacists);

    }



}
