<?php
namespace Mage2way\DeleteOrder\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Mage2way\DeleteOrder\Model\OrderFactory;

abstract class AbstractDeleteAction extends Action
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
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('deleteorder');
    }

}