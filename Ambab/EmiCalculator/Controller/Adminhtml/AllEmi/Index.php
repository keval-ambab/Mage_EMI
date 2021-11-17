<?php
namespace Ambab\EmiCalculator\Controller\Adminhtml\AllEmi;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;
    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
        ){
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Authorization level
     *
     * @see _isAllowed()
     */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Ambab_EmiCalculator::emical_allemi');
	}
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('All Emi'));
        return $resultPage;
    }
}
?>


