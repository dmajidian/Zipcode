<?php
namespace Majidian\Zipcode\Controller\Adminhtml\Address;

use Magento\Framework\Controller\ResultFactory;

class AddZipcode extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;
    /**
     * @var \Majidian\Zipcode\Model\AddressFactory
     */
    private $addressFactory;

    /**
     * AddBanner constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Majidian\Zipcode\Model\AddressFactory $addressFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Majidian\Zipcode\Model\AddressFactory $addressFactory
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->addressFactory = $addressFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $rowId = (int) $this->getRequest()->getParam('id');
        $rowData = $this->addressFactory->create();

        if (!empty($rowId)) {
            $rowData = $rowData->load($rowId);
            $rowTitle = $rowData->getTitle();
            if (!$rowData->getZipcodeId()) {
                $this->messageManager->addError(__('Zipcode does not exist.'));
                $this->_redirect('*/*/index');
                return;
            }
        }

        $this->coreRegistry->register('row_data', $rowData);
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $title = $rowId ? __('Edit Zipcode ').$rowTitle : __('Add Zipcode');
        $resultPage->getConfig()->getTitle()->prepend($title);

        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Majidian_Zipcode::add_zipcode');
    }
}
