<?php
namespace Mage2way\DeleteOrder\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;

class MassDelete extends Action
{
    /**
     * MassDelete constructor.
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context)
    {
        parent::__construct($context);
    }

    /**
     * Mass Delete action
     */
    public function execute()
    {
        echo "Mass Delete Action";
        exit;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('deleteorder');
    }
}
