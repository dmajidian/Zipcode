<?php
namespace Majidian\Zipcode\Controller\Adminhtml\Address;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Majidian\Zipcode\Model\AddressFactory
     */
    protected $addressFactory;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Majidian\Zipcode\Model\AddressFactory $addressFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Majidian\Zipcode\Model\AddressFactory $addressFactory
    ) {
        parent::__construct($context);
        $this->addressFactory = $addressFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        if (!$data) {
            $this->_redirect('address/address/addzipcode');
            return;
        }

        try {
            $addressData = $this->addressFactory->create();
            $addressData->setData($data);
            if (isset($data['id'])) {
                $addressData->setEntityId($data['id']);
            }
            if (isset($data['country_id'])) {
                $addressData->setCountryId($data['country_id']);
            }
            if (isset($data['region_id'])) {
                $addressData->setRegionId($data['region_id']);
            }
            $addressData->save();
            $this->messageManager->addSuccess(__('Zipcode successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('address/address/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Majidian_Zipcode::save');
    }
}
