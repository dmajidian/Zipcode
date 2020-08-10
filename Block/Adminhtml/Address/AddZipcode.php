<?php
namespace Majidian\Zipcode\Block\Adminhtml\Address;

class AddZipcode extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * @var \Magento\Framework\Registry|null
     */
    protected $_coreRegistry = null;

    /**
     * AddBanner constructor.
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     *
     */
    protected function _construct()
    {
        $this->_objectId = 'row_id';
        $this->_blockGroup = 'Majidian_Zipcode';
        $this->_controller = 'adminhtml_address';
        parent::_construct();
        if ($this->_isAllowedAction('Majidian_Zipcode::add_zipcode'))
        {
            $this->buttonList->update('save', 'label', __('Save'));
        }
        else {
            $this->buttonList->remove('save');
        }
        $this->buttonList->remove('reset');
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    public function getHeaderText()
    {
        return __('Add New Address');
    }

    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * @return mixed|string
     */
    public function getFormActionUrl()
    {
        if ($this->hasFormActionUrl())
        {
            return $this->getData('form_action_url');
        }

        return $this->getUrl('*/*/save');
    }
}
