<?php
namespace Mage2way\DeleteOrder\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    /**
     * OrderDelete constructor.
     * @param Action\Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    /**
     * Mass Delete Action
     */
    public function execute()
    {
        echo "Order Delete Action";
        exit;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('deleteorder');
    }
}