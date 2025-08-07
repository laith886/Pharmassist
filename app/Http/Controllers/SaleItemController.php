<?php

namespace App\Http\Controllers;

use App\Http\Requests\SellRequest;
use App\Models\SaleItem;
use App\Repositories\Interfaces\SaleItemRepositoryInterface;
use Illuminate\Http\Request;

class SaleItemController extends Controller
{

    protected $SaleItemsRepository;
    public function __construct(SaleItemRepositoryInterface $SaleItemsRepository)
    {
        $this->SaleItemsRepository = $SaleItemsRepository;
    }


    public function Sell(SellRequest $request)
    {

        return $this->SaleItemsRepository->Sell($request->validated());

    }


}
