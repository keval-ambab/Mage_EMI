<?php

namespace Ambab\EmiCalculator\Controller\Adminhtml\AllEmi;

use Magento\Backend\App\Action;
use Ambab\EmiCalculator\Model\Allemi;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Ambab\EmiCalculator\Model\AllemiFactory
     */
    private $allemiFactory;

    /**
     * @var \Ambab\EmiCalculator\Api\AllemiRepositoryInterface
     */
    private $allemiRepository;

    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param \Ambab\EmiCalculator\Model\AllemiFactory $allemiFactory
     * @param \Ambab\EmiCalculator\Api\AllemiRepositoryInterface $allemiRepository
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        \Ambab\EmiCalculator\Model\AllemiFactory $allemiFactory = null,
        \Ambab\EmiCalculator\Api\AllemiRepositoryInterface $allemiRepository = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->allemiFactory = $allemiFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\Ambab\EmiCalculator\Model\AllemiFactory::class);
        $this->allemiRepository = $allemiRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\Ambab\EmiCalculator\Api\AllemiRepositoryInterface::class);
        parent::__construct($context);
    }
	
	/**
     * Authorization level
     *
     * @see _isAllowed()
     */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Ambab_EmiCalculator::save');
	}

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['status']) && $data['status'] === 'true') {
                $data['status'] = Allemi::STATUS_ENABLED;
            }
            if (empty($data['id'])) {
                $data['id'] = null;
            }

            /** @var \Ambab\EmiCalculator\Model\Allemi $model */
            $model = $this->allemiFactory->create();
            // $result = [
            //         'bank_name' => $data['Bank_Name'],
            //         'interest_rate' => $data['Interest_Rate'],
            //         'duration' => $data['Duration'],
            //         'status' => $data['status'],
            //         'form_key' => $data['form_key'],
            //         ];

            $id = $this->getRequest()->getParam('id');

            if ($id) {
                try {
                    $model = $this->allemiRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This emi no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);
            

            $this->_eventManager->dispatch(
                'emical_allemi_prepare_save',
                ['allemi' => $model, 'request' => $this->getRequest()]
            );

            try {
                $this->allemiRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the emi.'));
                $this->dataPersistor->clear('emical_allemi');
            
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                    // return $resultRedirect->setPath('emical/AllEmi/index', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?:$e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the emi.'));
            }

            $this->dataPersistor->set('emical_allemi', $data);
            


            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
