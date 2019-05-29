<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\core\st\stockLocation;

/**
 * Description of StockLocationEventHandler
 *
 * @author Shrishail
 */
class StockLocationEventHandler extends \app\cwf\vsla\xmlbo\EventHandlerBase {
    public function afterFetch($criteriaparam) {
        parent::afterFetch($criteriaparam);
        
        if($this->bo->stock_location_id == -1){
            $this->bo->branch_id = \app\cwf\vsla\security\SessionManager::getSessionVariable('branch_id');
        }
    }
}
