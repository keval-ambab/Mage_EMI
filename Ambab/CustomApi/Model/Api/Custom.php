<?php
namespace Ambab\CustomApi\Model\Api;

use Psr\Log\LoggerInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;


class Custom
{
    protected $logger;
    protected $OrderRepositoryInterface;
    protected $productRepository;
    protected $_storeManager;
  
    public function __construct(
        LoggerInterface $logger,
        OrderRepositoryInterface $OrderRepositoryInterface,     
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storemanager
        )
        {
            $this->logger = $logger;
            $this->OrderRepositoryInterface = $OrderRepositoryInterface;
            $this->productRepository = $productRepository;
            $this->_storeManager =  $storemanager;
        }
    /**
     * @inheritdoc
     */
    public function getPost($id)
    {
        try {

            $orderInfo = [];
            $order_info = $this->OrderRepositoryInterface->get($id);
            

            
            foreach($order_info->getAllItems() as $item){

                
                $orderInfo['Order_ID'] = $item->getId();
                $orderInfo['Order_Name'] = $item->getName();
                $orderInfo['Order_Qty'] = $item->getQtyOrdered();
                $orderInfo['Order_Price'] = $item->getPrice(); 
                $orderInfo['Order_Discount_Price'] = $item->getDiscountAmount(); 
                
                // Get Product Image url
                $product = $this->productRepository->getById($orderInfo['Order_ID']);
                $store = $this->_storeManager->getStore();
                $orderInfo['Order_image_url'] = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product'.$product->getImage();
            }
            
            $shipping_amount['Shipping_Amount']=$order_info->getShippingAmount();
            $shipping_amount['Order_Payment_Method'] = $order_info->getPayment()->getMethod();
            
            $shipping_address['Shipping_City']=$order_info->getShippingAddress()->getCity();
            $shipping_address['Shipping_RegionId']=$order_info->getShippingAddress()->getRegionId();
            $shipping_address['Shipping_CountryId']=$order_info->getShippingAddress()->getCountryId();
            // $shipping_address['Shipping_Amount']=$order_info->getShippingAddress()->getData();

 
            // array_push($orderInfo,);

          $response = ['success' => true, 'message' => $orderInfo,$shipping_amount,$shipping_address];
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info($e->getMessage());
        }
        $returnArray = json_encode($response);
        return $response; 
   }
}
