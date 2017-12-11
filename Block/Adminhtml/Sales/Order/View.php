<?php
namespace Mage2way\DeleteOrder\Block\Adminhtml\Sales\Order;

use Mage2way\DeleteOrder\Helper\Data;
use Magento\Sales\Block\Adminhtml\Order\View as OrderView;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Magento\Sales\Model\Config;
use Magento\Sales\Helper\Reorder;

class View extends OrderView
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * View constructor.
     * @param Context $context
     * @param Registry $registry
     * @param Config $salesConfig
     * @param Reorder $reorderHelper
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Config $salesConfig,
        Reorder $reorderHelper,
        Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $registry, $salesConfig, $reorderHelper, $data);
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        parent::_construct();

        if (!$this->helper->isEnabled()) {
            return;
        }

        $message = __('Are you sure you want to delete this order?');
        $this->addButton(
            'delete_btn',
            [
                'label' => __('Delete'),
                'class' => 'go',
                'onclick' => "deleteConfirm('{$message}', '{$this->getDeleteOrderUrl()}')"
            ]
        );
    }

    /**
     * @return string
     */
    public function getDeleteOrderUrl()
    {
        return $this->getUrl('deleteorder/order/index');
    }
}