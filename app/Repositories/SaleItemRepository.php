<?php

namespace App\Repositories;

use App\Models\Medicine;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Repositories\Interfaces\SaleItemRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class SaleItemRepository implements SaleItemRepositoryInterface
{
    private function checkQuantities(array $items): array
    {
        $saleItems = [];

        foreach ($items as $item) {
            $medicine = Medicine::find($item['medicine_id']);
            if (!$medicine || $medicine->quantity_in_stock < $item['quantity']) {
                return [
                    'status' => false,
                    'message' => "ID : the quantity is over the stock {$item['medicine_id']}"
                ];
            }

            $saleItems[] = [
                'medicine' => $medicine,
                'quantity' => $item['quantity'],
                'price' => $medicine->price,
            ];
        }

        return ['status' => true, 'data' => $saleItems];
    }
    private function calculateTotal(array $saleItems): float
    {
        $total = 0;

        foreach ($saleItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }
    private function createSale(float $totalPrice): Sale
    {
        return Sale::create([
            'pharmacist_id' => Auth::id(),
            'sale_date' => now(),
            'total_price' => $totalPrice,
        ]);
    }
    private function UpdateStock($medicine, int $quantity): void{

        $medicine->quantity_in_stock = $medicine->quantity_in_stock - $quantity;
        $medicine->save();
    }
    private function createSaleItems(Sale $sale, array $saleItems): void
    {
        foreach ($saleItems as $item) {
            SaleItem::create([
                'sale_id' => $sale->id,
                'medicine_id' => $item['medicine']->id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            $this->UpdateStock($item['medicine'], $item['quantity']);
        }
    }

    public function sell(array $data): array
    {
        $saleItems = $this->checkQuantities($data['items']);

        if (!$saleItems['status']) {
            return $saleItems;
        }

        $totalPrice = $this->calculateTotal($saleItems['data']);
        $sale = $this->createSale($totalPrice);
        $this->createSaleItems($sale, $saleItems['data']);

        return [
            'status' => true,
            'message' => 'Sale operation completed successfully',
            'sale_id' => $sale->id,
        ];
    }
}
