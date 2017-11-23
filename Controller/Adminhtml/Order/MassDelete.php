<?php
namespace Mage2way\DeleteOrder\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Mage2way\DeleteOrder\Model\OrderFactory;

class MassDelete extends Action
{
    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * MassDelete constructor.
     * @param Action\Context $context
     * @param OrderFactory $orderFactory
     */
    public function __construct(Action\Context $context, OrderFactory $orderFactory)
    {
        $this->orderFactory = $orderFactory;
        parent::__construct($context);
    }

    /**
     * Mass Delete action
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $orderIds = $this->getRequest()->getParam('selected');
        if (empty($orderIds)) {
           $this->messageManager->addError(__("No order is selected to delete."));
            return $resultRedirect->setPath('sales/order/index');
        }

        try {
            $this->orderFactory->create()->deleteOrders($orderIds);
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }

        return $resultRedirect->setPath('sales/order/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('deleteorder');
    }
}
