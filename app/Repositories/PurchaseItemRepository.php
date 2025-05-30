<?php

namespace App\Repositories;

class PurchaseItemRepository
{
    private function CheckQuantities(array $items){

        foreach($items as $item){

            $quantity=$item['quantity'];

            if($quantity<=0){
                return false;
            }


        }
        return true;
    }


    public function MakeOrderSupply(array $data){
        if(!$this->CheckQuantities($data)){
            return null;
        }


    }




}
