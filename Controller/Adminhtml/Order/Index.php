<?php
namespace Mage2way\DeleteOrder\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Index extends Action
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
     * Delete action
     */
    public function execute()
    {
        echo "Hello World";
        exit;
    }

    /**
     * @inheritdoc
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('deleteorder');
    }
}

