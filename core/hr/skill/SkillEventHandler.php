<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\core\hr\skill;

/**
 * Description of skillEventHandler
 *
 * @author Valli
 */

class SkillEventHandler extends \app\cwf\vsla\xmlbo\EventHandlerBase {
    
    public function afterFetch($criteriaparam) {
        parent::afterFetch($criteriaparam);
        
    }

    public function beforeSave($cn) {            
        parent::beforeSave($cn);
       
    }
    
    
}
