<?php
namespace Ambab\EmiCalculator\Controller\Adminhtml\Allemi;

use Magento\Backend\App\Action\Context;
use Ambab\EmiCalculator\Api\AllemiRepositoryInterface as AllemiRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Ambab\EmiCalculator\Api\Data\AllemiInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    protected $allemiRepository;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    public function __construct(
        Context $context,
        AllemiRepository $allemiRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->allemiRepository = $allemiRepository;
        $this->jsonFactory = $jsonFactory;
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
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $id) {
            $emi = $this->allemiRepository->getById($id);
            try {
                $emiData = $postItems[$id];
                $extendedEmiData = $emi->getData();
                $this->setEmiData($emi, $extendedEmiData, $emiData);
                $this->allemiRepository->save($emi);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithId($emi, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithId($emi, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithId(
                    $emi,
                    __('Something went wrong while saving the emi.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    protected function getErrorWithId(AllemiInterface $emi, $errorText)
    {
        return '[ID: ' . $emi->getId() . '] ' . $errorText;
    }

    public function setEmiData(\Ambab\EmiCalculator\Model\Allemi $emi, array $extendedEmiData, array $emiData)
    {
        $emi->setData(array_merge($emi->getData(), $extendedEmiData, $emiData));
        return $this;
    }
}
