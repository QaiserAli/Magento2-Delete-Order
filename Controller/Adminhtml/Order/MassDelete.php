<?php
namespace Mage2way\DeleteOrder\Controller\Adminhtml\Order;

class MassDelete extends AbstractDeleteAction
{
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
}
