<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\MakeSupplyOrderRequest;

interface PurchaseItemsRepositoryInterface
{

    public function MakeSupplyOrder(array $items);
    public function ImportPricedSupplyOrder($filePath);
}
