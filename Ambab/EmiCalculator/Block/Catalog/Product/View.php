<?php
namespace Ambab\EmiCalculator\Block\Catalog\Product;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\AbstractProduct;
use Ambab\EmiCalculator\Model\AllemiFactory;
use Ambab\EmiCalculator\Api\AllemiRepositoryInterface;
use Magento\Checkout\Model\Cart;
use Magento\Framework\Message\ManagerInterface;

class View extends AbstractProduct
{
    protected $_product;
    protected $registry;
    protected $_allemiFactory;
    protected $_allemiRepositoryInterface;
    protected $grandTotal;
    protected $messageManager;

    public function __construct(Context $context, array $data = [],
    ManagerInterface $messageManager,
    \Magento\Framework\Registry $registry, AllemiFactory $allemiFactory, AllemiRepositoryInterface $allemiRepositoryInterface, \Magento\Checkout\Model\Cart $grandTotal
    )
    {
        $this->_allemiFactory = $allemiFactory;
        $this->_allemiRepositoryInterface = $allemiRepositoryInterface;
        $this->registry = $registry;
        $this->grandTotal = $grandTotal;
        $this->messageManager = $messageManager;
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
            ->addFieldToFilter('status', array('eq' => '1'))
            // addFieldToFilter('status', array('eq' => '1'))
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
            ->addFieldToFilter('status', array('eq' => '1'))
            ->setOrder('bank_name','ASC')
            ->load();

        return $collection;
    }

    // public function getEmiPlans($currentProductPrice, $rateOfInterest, $duration){
    //     $emiamount = getEmiAmount($currentProductPrice, $rateOfInterest, $duration);
    //     $totalAmount = $emiamount * $duration;
    //     // $interestPM = ($totalAmount - $currentProductPrice)/$duration;
    // }

     public function getEmiAmount($currentProductPrice, $rateOfInterest, $duration)
    {
        $rateOfInterest = $rateOfInterest / (12 * 100);
        $EMI = $currentProductPrice * $rateOfInterest * (pow(1 + $rateOfInterest, $duration) / (pow(1 + $rateOfInterest, $duration) - 1));
        return $EMI;

    }
    
    public function getTotalAmount($currentProductPrice,$rateOfInterest,$emiamount,$duration){
        $emiamount = $this->getEmiAmount($currentProductPrice, $rateOfInterest, $duration);
        $totalAmount = $emiamount * $duration;
        return $totalAmount;
    }
    
    public function getMonthlyAmount($currentProductPrice,$rateOfInterest,$totalAmount,$duration,$emiamount){
        $totalAmount = $this->getTotalAmount($currentProductPrice,$rateOfInterest,$emiamount,$duration);
        $monthlyAmount = $totalAmount/ $duration;
        return $monthlyAmount;
        
    }
    public function getMonthlyInterst($currentProductPrice,$duration,$emiamount){
        $totalAmount = $this->getTotalAmount($currentProductPrice,$rateOfInterest,$emiamount,$duration);
        $interestPM = ($totalAmount - $currentProductPrice);
        return $interestPM;
    }


    public function getGrandTotal(){
        // return $this->grandTotal->getQuote()->getBaseSubtotal();
        return $this->grandTotal->getQuote()->getGrandTotal();
    }

}