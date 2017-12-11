<?php
namespace Mage2way\DeleteOrder\Controller\Adminhtml\Order;

class Index extends AbstractDeleteAction
{
    /**
     * Delete action
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $orderId = $this->getRequest()->getParam('entity_id');
        if (empty($orderId)) {
            $this->messageManager->addError(__("Order doesn't exist or cannot be deleted."));
            return $resultRedirect->setPath('sales/order/index');
        }

        try {
            $this->orderFactory->create()->deleteOrders($orderId);
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }

        return $resultRedirect->setPath('sales/order/index');
    }
}

