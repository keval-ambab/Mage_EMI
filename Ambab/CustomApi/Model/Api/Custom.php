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
            $i = 0;
            $orderInfo = [];
            $order_info = $this->OrderRepositoryInterface->get($id);
            $orderInfo['Order_Entity_ID'] = $order_info->getEntityId();
            
            foreach($order_info->getAllItems() as $item){
                $productID = $orderInfo['Order_ID'] = $order_info->getId();
                $orderInfo['items'][$i]['Order_Name'] = $item->getName();
                $orderInfo['items'][$i]['Order_Qty'] = $item->getQtyOrdered();
                $orderInfo['items'][$i]['Order_Special_Price'] = $item->getPrice(); 
                $orderInfo['items'][$i]['Order_Original_Price'] = $item->getOriginalPrice(); 
                $orderInfo['items'][$i]['Order_Discount_Price'] = $item->getDiscountAmount(); 
                // Get Product Image url
                $product = $this->productRepository->getById($productID);
                $store = $this->_storeManager->getStore();
                $orderInfo['items'][$i]['Order_image_url'] = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product'.$product->getImage();
                $i++;
            }
            $shipping_details['Shipping_Amount']=$order_info->getShippingAmount();
            $shipping_details['Order_Payment_Method'] = $order_info->getPayment()->getMethod();
            $shipping_details['Shipping_City']=$order_info->getShippingAddress()->getCity();
            $shipping_details['Shipping_RegionId']=$order_info->getShippingAddress()->getRegionId();
            $shipping_details['Shipping_CountryId']=$order_info->getShippingAddress()->getCountryId();
          $response = ['success' => true, 'message' => $orderInfo,$shipping_details];
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info($e->getMessage());
        }
        $returnArray = json_encode($response);
        return $response; 
   }
}

