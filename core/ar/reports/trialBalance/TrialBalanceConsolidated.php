<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use  vsla\utils\FormatHelper;
namespace app\core\ar\reports\trialBalance;

/**
 * Description of TrialBalanceConsolidatedValidator
 *
 * @author Priyanka
 */
class TrialBalanceConsolidated extends \app\cwf\fwShell\base\ReportBase {
    //use  vsla\utils\FormatHelper;
    public function onRequestReport($rptOption) {
        parent::onRequestReport($rptOption);     
     
        if(strtotime($rptOption->rptParams["pfrom_date"]) > strtotime($rptOption->rptParams["pto_date"])){
            array_push($rptOption->brokenRules, 'From Date should be less than To Date.');
        }
        
        if($rptOption->rptParams['pbranch_id']=='' || $rptOption->rptParams['pbranch_id']=='-1'){
            array_push($rptOption->brokenRules, 'Please Select Branch.');
        }   
        
//        if($rptOption->rptParams['pbranch_id']== 0 AND $rptOption->rptParams['pbranch_id']<>'' AND $rptOption->rptParams['ptrial_balance_type']==0){
//            array_push($rptOption->brokenRules, 'Please select single branch for normal trial balance.');
//        } 
        
        if($rptOption->rptParams['ptrial_balance_type']==-1){
            array_push($rptOption->brokenRules, 'Please Select Trial Balance.');
        }
        
        //If 'All' selected in Branches combo
        if($rptOption->rptParams['pbranch_id']== 0 AND $rptOption->rptParams['pbranch_id']<>'' AND $rptOption->rptParams['ptrial_balance_type']==1){
            $rptOption->rptParams['pbranch_name']='Consolidated';
        }
        
        //Normal Columnar Reports
        
        if ($rptOption->rptParams['pwithout_groups']==1 AND $rptOption->rptParams['ptrial_balance_type']==1){ 
                $rptOption->rptParams['preport_period']='Between '. \app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pfrom_date'])
                    .' And ' . \app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pto_date'] );

                //for opening balance with out group
                if(($rptOption->rptParams['opening_balance']==1)AND ($rptOption->rptParams['transactions_during_period']==0)){
                    $rptOption->rptName='ArTBOpening_CTNoGroup';  
                    $rptOption->rptParams['preport_period']='Opening Balance for financial year - ' . $rptOption->rptParams['pyear'];
                }
                //for Transation During Period with out group
                else if(($rptOption->rptParams['opening_balance']==0)AND ($rptOption->rptParams['transactions_during_period']==1)){
                    $rptOption->rptName='ArTBClosing_CTNoGroup';
                }
                //for Transation During Period AND Opening balance with out group
                else if(($rptOption->rptParams['opening_balance']==1)AND ($rptOption->rptParams['transactions_during_period']==1)){
                    $rptOption->rptName='ArTBOpening_CTNoGroup';        
                    $rptOption->rptParams['preport_period']='Opening Balance for financial year - ' . $rptOption->rptParams['pyear'];
                }
                else{ //for Trial Balance with out group
                    $rptOption->rptName='ArTBClosing_CTNoGroup';
                    $rptOption->rptParams['preport_period']='As on ' .\app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pto_date'] );
                }   
        }  
        elseif ($rptOption->rptParams['pwithout_groups']==0 AND $rptOption->rptParams['ptrial_balance_type']==1){

                $rptOption->rptParams['preport_period']='Between '. \app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pfrom_date'])
                       .' And ' . \app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pto_date'] );

                //for opening balance with  group
                if(($rptOption->rptParams['opening_balance']==1)AND ($rptOption->rptParams['transactions_during_period']==0)){
                    $rptOption->rptName='ArTBOpening_CTGroup'; 
                    $rptOption->rptParams['preport_period']='Opening Balance for financial year - ' . $rptOption->rptParams['pyear'];
                }
                //for Transation During Period with  group
                else if(($rptOption->rptParams['opening_balance']==0)AND ($rptOption->rptParams['transactions_during_period']==1)){
                    $rptOption->rptName='ArTBClosing_CTGroup';
                }
                //for Transation During Period AND Opening balance with group
                else if(($rptOption->rptParams['opening_balance']==1)AND ($rptOption->rptParams['transactions_during_period']==1)){
                         $rptOption->rptName='ArTBOpening_CTGroup';  
                         $rptOption->rptParams['preport_period']='Opening Balance for financial year - ' . $rptOption->rptParams['pyear'];
                }
                else{ //for Trial Balance with  group
                   $rptOption->rptName='ArTBClosing_CTGroup';  
                   $rptOption->rptParams['preport_period']='As on ' .\app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pto_date'] );
                }   
        }
        
        
        //Normal Reports

        if ($rptOption->rptParams['pwithout_groups']==1 AND $rptOption->rptParams['ptrial_balance_type']==0){ 
                $rptOption->rptParams['preport_period']='Between '. \app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pfrom_date'])
                    .' And ' . \app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pto_date'] );

                //for opening balance with out groupTrialBalanceNoGroup
                if(($rptOption->rptParams['opening_balance']==1)AND ($rptOption->rptParams['transactions_during_period']==0)){
                    $rptOption->rptName='ArTrialBalanceNoGroup';  
                }
                //for Transation During Period with out group
                else if(($rptOption->rptParams['opening_balance']==0)AND ($rptOption->rptParams['transactions_during_period']==1)){
                    $rptOption->rptName='ArTrialBalanceTxnNoGroup';
                }
                //for Transation During Period AND Opening balance with out group
                else if(($rptOption->rptParams['opening_balance']==1)AND ($rptOption->rptParams['transactions_during_period']==1)){
                    $rptOption->rptName='ArTrialBalanceOpBalTxnNoGroup';        
                }
                else{ //for Trial Balance with out group
                    $rptOption->rptName='ArTrialBalanceNoGroup';
                    $rptOption->rptParams['preport_period']='As on ' .\app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pto_date'] );
                }   
        }  
        elseif ($rptOption->rptParams['pwithout_groups']==0 AND $rptOption->rptParams['ptrial_balance_type']==0){

                $rptOption->rptParams['preport_period']='Between '. \app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pfrom_date'])
                       .' And ' . \app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pto_date'] );

                //for opening balance with  group
                if(($rptOption->rptParams['opening_balance']==1)AND ($rptOption->rptParams['transactions_during_period']==0)){
                    $rptOption->rptName='ArTrialBalanceOpBal';  
                }
                //for Transation During Period with  group
                else if(($rptOption->rptParams['opening_balance']==0)AND ($rptOption->rptParams['transactions_during_period']==1)){
                    $rptOption->rptName='ArTrialBalanceTxn';
                }
                //for Transation During Period AND Opening balance with group
                else if(($rptOption->rptParams['opening_balance']==1)AND ($rptOption->rptParams['transactions_during_period']==1)){
                    $rptOption->rptName='ArTrialBalanceOpBalTxn';        
                }
                else{ //for Trial Balance with  group
                    $rptOption->rptName='ArTrialBalance';
                    $rptOption->rptParams['preport_period']='As on ' .\app\cwf\vsla\utils\FormatHelper::FormatDateForDisplay($rptOption->rptParams['pto_date'] );
                }   
        }
        return $rptOption;
    }  
}