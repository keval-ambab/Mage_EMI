<?php
namespace Ambab\EmiCalculator\Block\Catalog\Product;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\AbstractProduct;
// Use Magento\Catalog\Model\ProductFactory;
use Ambab\EmiCalculator\Model\AllemiFactory;
use Ambab\EmiCalculator\Api\AllemiRepositoryInterface;
use Magento\Checkout\Model\Cart;

class View extends AbstractProduct
{
    protected $_product;
    // protected $productFactory;
    protected $registry;
    protected $_allemiFactory;
    protected $_allemiRepositoryInterface;
    protected $subTotal;

    public function __construct(Context $context, array $data = [],
    // ProductFactory $productFactory,
    \Magento\Framework\Registry $registry, AllemiFactory $allemiFactory, AllemiRepositoryInterface $allemiRepositoryInterface, \Magento\Checkout\Model\Cart $subTotal)
    {
        $this->_allemiFactory = $allemiFactory;
        $this->_allemiRepositoryInterface = $allemiRepositoryInterface;
        $this->registry = $registry;
        $this->subTotal = $subTotal;
        // $this->productFactory = $productFactory;
        parent::__construct($context, $data);
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getCurrentProduct()
    {
        return $this
            ->registry
            ->registry('current_product');
    }

    // public function getPriceById($id)
    // {
    //     $id =  700;//Product ID
    //     $product = $this->productFactory->create();
    //     $productPriceById = $product->load($id)->getPrice();
    //     return $productPriceById;
    // }
    public function getCollection()
    {
        return $this
            ->_allemiFactory
            ->create()
            ->getCollection();

    }

    public function getProductPrize()
    {
        $_product = $this->getProduct();
        $productprice = $_product->getFinalPrice();
        return $productprice;
    }

    public function getEmiAmount($currentProductPrice, $rateOfInterest, $duration)
    {
        $rateOfInterest = $rateOfInterest / (12 * 100);
        $EMI = $currentProductPrice * $rateOfInterest * (pow(1 + $rateOfInterest, $duration) / (pow(1 + $rateOfInterest, $duration) - 1));
        return $EMI;

    }

    public function hello(){
        return "hello";
    }


    // public function getRateOfInterest($id)
    // {
    //     $irdata = $this
    //         ->_allemiFactory
    //         ->create();
    //     $collection = $irdata->getCollection()
    //         ->addFieldToFilter('id', ['like' => $id])->load();
    //     return $collection;
    // }

    public function getEmiDetails($bankName)
    {
        $irdata = $this
            ->_allemiFactory
            ->create();
        $collection = $irdata->getCollection()
            ->addFieldToFilter('bank_name', ['like' => $bankName])
            ->addFieldToFilter('status', ['eq' => '1'])
            ->setOrder('duration','ASC')
            ->load();
        return $collection;
    }

    public function getBankName()
    {
        $bank = $this
            ->_allemiFactory
            ->create();
        $collection = $bank->getCollection()
            ->distinct(true)
            ->addFieldToSelect('bank_name')
            ->setOrder('bank_name','ASC')
            ->load();

        return $collection;
    }

    public function getSubTotal(){
        return $this->subTotal->getQuote()->getBaseSubtotal();
    }



}

