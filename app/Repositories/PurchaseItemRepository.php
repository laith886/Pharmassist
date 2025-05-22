<?php

namespace App\Repositories;

class PurchaseItemRepository
{
    private function CheckQuantities(array $items){

        foreach($items as $item){

            if($item['quantity']<=0){
                return false;
            }
        }
        return true;
    }


    public function MakeOrderSupply(array $data){
        if(!$this->CheckQuantities($data)){
            return ['staus'=>'fail','message:invalid quantities'];
        }
        

    }




}
