<?php

namespace Ambab\EmiCalculator\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    protected $date;
     
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $emidata = [ 
            [
                'bank_name' => 'TEST',
                'interest_rate' => 6,
                'duration' => 3,
                'status' => 1,
            ],
            [
                'bank_name' => 'SBI',
                'interest_rate' => 6,
                'duration' => 3,
                'status' => 1,
            ],  [
                'bank_name' => 'SBI',
                'interest_rate' => 6,
                'duration' => 6,
                'status' => 1,
            ],  [
                'bank_name' => 'SBI',
                'interest_rate' => 6,
                'duration' => 9,
                'status' => 1,
            ],  [
                'bank_name' => 'SBI',
                'interest_rate' => 6,
                'duration' => 12,
                'status' => 1,
            ],

            [
                'bank_name' => 'BOB',
                'interest_rate' => 6,
                'duration' => 3,
                'status' => 1,
            ],  [
                'bank_name' => 'BOB',
                'interest_rate' => 6,
                'duration' => 6,
                'status' => 1,
            ],  [
                'bank_name' => 'BOB',
                'interest_rate' => 6,
                'duration' => 9,
                'status' => 1,
            ],  [
                'bank_name' => 'BOB',
                'interest_rate' => 6,
                'duration' => 12,
                'status' => 1,
            ],

            [
                'bank_name' => 'HDFC',
                'interest_rate' => 6,
                'duration' => 3,
                'status' => 1,
            ],  [
                'bank_name' => 'HDFC',
                'interest_rate' => 6,
                'duration' => 6,
                'status' => 1,
            ],  [
                'bank_name' => 'HDFC',
                'interest_rate' => 6,
                'duration' => 9,
                'status' => 1,
            ],  [
                'bank_name' => 'HDFC',
                'interest_rate' => 6,
                'duration' => 12,
                'status' => 1,
            ],

            [
                'bank_name' => 'ICICI',
                'interest_rate' => 6,
                'duration' => 3,
                'status' => 1,
            ],  [
                'bank_name' => 'ICICI',
                'interest_rate' => 6,
                'duration' => 6,
                'status' => 1,
            ],  [
                'bank_name' => 'ICICI',
                'interest_rate' => 6,
                'duration' => 9,
                'status' => 1,
            ],  [
                'bank_name' => 'ICICI',
                'interest_rate' => 6,
                'duration' => 12,
                'status' => 1,
            ],
            
            [
                'bank_name' => 'AXIS',
                'interest_rate' => 6,
                'duration' => 3,
                'status' => 1,
            ],  [
                'bank_name' => 'AXIS',
                'interest_rate' => 6,
                'duration' => 6,
                'status' => 1,
            ],  [
                'bank_name' => 'AXIS',
                'interest_rate' => 6,
                'duration' => 9,
                'status' => 1,
            ],  [
                'bank_name' => 'AXIS',
                'interest_rate' => 6,
                'duration' => 12,
                'status' => 1,
            ],

            [
                'bank_name' => 'DENA',
                'interest_rate' => 6,
                'duration' => 3,
                'status' => 1,
            ],  [
                'bank_name' => 'DENA',
                'interest_rate' => 6,
                'duration' => 6,
                'status' => 1,
            ],  [
                'bank_name' => 'DENA',
                'interest_rate' => 6,
                'duration' => 9,
                'status' => 1,
            ],  [
                'bank_name' => 'DENA',
                'interest_rate' => 6,
                'duration' => 12,
                'status' => 1,
            ],

            [
                'bank_name' => 'RBL',
                'interest_rate' => 6,
                'duration' => 3,
                'status' => 1,
            ],  [
                'bank_name' => 'RBL',
                'interest_rate' => 6,
                'duration' => 6,
                'status' => 1,
            ],  [
                'bank_name' => 'RBL',
                'interest_rate' => 6,
                'duration' => 9,
                'status' => 1,
            ],  [
                'bank_name' => 'RBL',
                'interest_rate' => 6,
                'duration' => 12,
                'status' => 1,
            ],
        ];
        
        foreach($emidata as $data) {
            $setup->getConnection()->insert($setup->getTable('ambab_emi'), $data);
        }
    }
}
?>
