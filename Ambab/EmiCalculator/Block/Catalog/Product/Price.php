<?php
namespace Ambab\EmiCalculator\Block\Catalog\Product;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Checkout\Model\Cart;

class Price extends AbstractProduct
{
    protected $registry;
    protected $grandTotal;
    protected $scopeConfigInterface;
    protected $stockState;
    private $stockRegistry;

    /**
 * @var 
 */


    public function __construct(Context $context, array $data = [],
    // ManagerInterface $messageManager,
    \Magento\Framework\Registry $registry, 
    \Magento\Checkout\Model\Cart $grandTotal,
    \Magento\CatalogInventory\Api\StockStateInterface $stockState,
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface,
    \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
    )
    {
        $this->registry = $registry;
        $this->grandTotal = $grandTotal;
        $this->stockState = $stockState;
        $this->stockRegistry = $stockRegistry;
        $this->scopeConfigInterface = $scopeConfigInterface;
        parent::__construct($context, $data);
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

  
    public function getGrandTotal(){
        // return $this->grandTotal->getQuote()->getBaseSubtotal();
        return $this->grandTotal->getQuote()->getGrandTotal();
    }

    public function getSubTotal(){
        return $this->grandTotal->getQuote()->getBaseSubtotal();
    }

    public function getMinimumValue(){
        return $this->scopeConfigInterface->getValue('sales/minimum_order/amount');
    }

    public function getStockQty($productId)
    {
        return $this->stockState->getStockQty($productId);
    }

    // public function getStockRegistry()
    // {
    //     return $this->stockRegistry;
    // }
}



