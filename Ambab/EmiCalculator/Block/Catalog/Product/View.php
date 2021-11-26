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
    // protected $messageManager;
    protected $json;
    protected $scopeConfigInterface;

    public function __construct(Context $context, array $data = [],
    // ManagerInterface $messageManager,
    \Magento\Framework\Registry $registry, AllemiFactory $allemiFactory, AllemiRepositoryInterface $allemiRepositoryInterface, 
    \Magento\Checkout\Model\Cart $grandTotal,
    \Magento\Framework\Serialize\Serializer\Json $json,
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
    )
    {
        $this->_allemiFactory = $allemiFactory;
        $this->_allemiRepositoryInterface = $allemiRepositoryInterface;
        $this->registry = $registry;
        $this->grandTotal = $grandTotal;
        $this->scopeConfigInterface = $scopeConfigInterface;
        // $this->messageManager = $messageManager;
        $this->json = $json;
        parent::__construct($context, $data);
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
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

    public function getEmiDetails($bankName)
    {
        $irdata = $this
            ->_allemiFactory
            ->create();
        $collection = $irdata->getCollection()
            ->addFieldToFilter('bank_name', ['like' => $bankName])
            ->addFieldToFilter('status', array('eq' => '1'))
            ->setOrder('duration','ASC')
            ->load();
        // $jsonCollection = json_encode($collection->getData());
        // print_r($jsonCollection);
        return $collection;
    }

    public function getBankNameData()
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
            // $jsonBNCollection = json_encode($collection->getData());
            // print_r($jsonBNCollection); exit;
            return $collection;
    }

     public function getEmiAmount($currentProductPrice, $rateOfInterest, $duration){
        $emiCalulationData = [];
        $rateOfInterest = $rateOfInterest / (12 * 100);
        $emiamount = $currentProductPrice * $rateOfInterest * (pow(1 + $rateOfInterest, $duration) / (pow(1 + $rateOfInterest, $duration) - 1));
        $totalAmount = $emiamount * $duration;
        $monthlyAmount = $totalAmount/ $duration;
        $interestPM = ($totalAmount - $currentProductPrice);
        //channge made here
        array_push($emiCalulationData, $emiamount, $totalAmount, $monthlyAmount,$interestPM);    
        // print_r($EMI); exit;
        return $emiCalulationData;
    }
    
    public function getGrandTotal(){
        // return $this->grandTotal->getQuote()->getBaseSubtotal();
        return $this->grandTotal->getQuote()->getGrandTotal();
    }

    public function getSubTotal(){
        return $this->grandTotal->getQuote()->getBaseSubtotal();
    }


    public function JSONData(){
        $jsondata = [];
        foreach ($this->getBankNameData() as $bankNm){
            $jsondata['BANKNAME'][] = $bankNm['bank_name']; //print array of the bank
            $bank = $bankNm['bank_name'];  //printing first data in the array
                foreach($this->getEmiDetails($bank) as $emiPlans){
                 $jsondata['DURATION'][$emiPlans['bank_name']]['duration'][] = $emiPlans['duration']; 
                 $jsondata['RATEOFINTEREST'][$emiPlans['bank_name']]['interest_rate'][] = $emiPlans['interest_rate']; //print array of the bank          
                }
        }
        echo json_encode($jsondata,JSON_PRETTY_PRINT); 
        // exit;
        
    }
}