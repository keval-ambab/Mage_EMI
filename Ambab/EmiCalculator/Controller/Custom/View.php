<?php
namespace Ambab\EmiCalculator\Controller\Custom;
 
 
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
 
 
class View extends Action
{
 
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
 
    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;
 
 
    /**
     * View constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(Context $context, PageFactory $resultPageFactory, JsonFactory $resultJsonFactory)
    {
 
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
 
        parent::__construct($context);
    }
 
 
    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = $this->_resultJsonFactory->create();
        $resultPage = $this->_resultPageFactory->create();

        $emiamount = $this->getRequest()->getParam('emiamount');
        $duration = $this->getRequest()->getParam('duration');
        $rateOfInterest = $this->getRequest()->getParam('rateOfInterest');
        $interestPM = $this->getRequest()->getParam('interestPM');
        $totalAmount = $this->getRequest()->getParam('currentproduct');

        $data = array(
            ['emiamount'=>$emiamount],
            ['duration'=>$duration],
            ['rateOfInterest'=>$rateOfInterest],
            ['interestPM'=>$interestPM],
            ['totalAmount'=>$totalAmount]
    );
 
        $block = $resultPage->getLayout()
                ->createBlock('Ambab\EmiCalculator\Block\Catalog\Product\View')
                ->setTemplate('Ambab_EmiCalculator::popup.phtml')
                ->setData('data',$data)
                ->toHtml();
 
        $result->setData(['output' => $block]);
        Print_r($result); exit;
        return $result;
    }
 
}