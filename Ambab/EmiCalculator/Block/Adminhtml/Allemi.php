<?php
namespace Ambab\EmiCalculator\Block\Adminhtml;

class Allemi extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_allemi';
        $this->_blockGroup = 'Ambab_EmiCalculator';
        $this->_headerText = __('Manage Emi');

        parent::_construct();

        if ($this->_isAllowedAction('Ambab_EmiCalculator::save')) {
            $this->buttonList->update('add', 'label', __('Add Emi'));
        } else {
            $this->buttonList->remove('add');
        }
    }

    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
