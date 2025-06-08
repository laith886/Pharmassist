<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeSupplyOrderRequest;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use App\Repositories\PurchaseItemRepository;
use App\Repositories\Interfaces\PurchaseItemsRepositoryInterface;
class PurchaseItemController extends Controller
{
    protected $purchaseItemRepository;

    public function __construct(PurchaseItemsRepositoryInterface $PurchaseRepository)
    {
        $this->purchaseItemRepository = $PurchaseRepository;
    }
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }


    public function show(PurchaseItem $purchaseItem)
    {
        //
    }

    public function edit(PurchaseItem $purchaseItem)
    {
        //
    }


    public function update(Request $request, PurchaseItem $purchaseItem)
    {
        //
    }

    public function destroy(PurchaseItem $purchaseItem)
    {
        //
    }
   public function MakeSupplyOrder(MakeSupplyOrderRequest $request)
    {
        $validated = $request->validated();
        $items = $validated['items'];
        $saleRepresentativeId = $validated['sale_representative_id'];


        $response = $this->purchaseItemRepository->MakeSupplyOrder([
            'items' => $items,
            'sale_representative_id' => $saleRepresentativeId,
        ]);


        return $response;
    }
}
