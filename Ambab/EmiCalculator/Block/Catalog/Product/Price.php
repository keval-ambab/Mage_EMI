<?php
namespace Ambab\EmiCalculator\Block\Catalog\Product;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Checkout\Model\Cart;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\Product;
use Magento\Framework\Registry;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku; 

class Price extends AbstractProduct
{
    protected $registry;
    protected $grandTotal;
    protected $scopeConfigInterface;
    protected $_Product;
    protected $session;
    protected $stockRegistry;
    protected $stockItemRepository;
    protected $getSalableQuantityDataBySku;

    /**
 * @var 
 */


    public function __construct(Context $context, array $data = [],
    // ManagerInterface $messageManager,
    \Magento\Framework\Registry $registry, 
    \Magento\Checkout\Model\Cart $grandTotal,    
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface,
    Product $product,
    SessionManagerInterface $session,
    StockRegistryInterface $stockRegistry,
    GetSalableQuantityDataBySku $getSalableQuantityDataBySku,
    StockItemRepository $stockItemRepository

    )
    {
        $this->registry = $registry;
        $this->grandTotal = $grandTotal;
        $this->scopeConfigInterface = $scopeConfigInterface;
        $this->_Product = $product;
        $this->session = $session;
        $this->stockRegistry = $stockRegistry;
        $this->stockItemRepository = $stockItemRepository;
        $this->getSalableQuantityDataBySku = $getSalableQuantityDataBySku;
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

    public function getRemainingQty($productId) {
        $stock = $this->stockRegistry->getStockItem($productId);
        return $stock->getQty();
    }

    protected function getCurrentProduct() {
        return $this->_registry->registry('current_product')->getId();
    }

       // get stock value
    public function getStockItem($productId)
    {
        return $this->stockItemRepository->get($productId);
    }

    public function getProductSalableQty($sku)
    {
         $salable = $this->getSalableQuantityDataBySku->execute($sku);
         return $salable[0]['qty'];
        // return $salable;
    }
   }

