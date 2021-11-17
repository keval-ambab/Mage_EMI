<?php
namespace  Ambab\EmiCalculator\Block\Catalog\Product;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Js extends \Magento\Framework\View\Element\Template
{
      public function __construct(ScopeConfigInterface $scopeConfig)
      {
          $this->_scopeConfig = $scopeConfig;
      }

      public function getEmiOptionalYesNo()
      {
          return $this->_scopeConfig->getValue("ambab_emi/general/enable");
      }
}