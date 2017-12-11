<?php
namespace Mage2way\DeleteOrder\Ui;

use Mage2way\DeleteOrder\Helper\Data;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\MassAction;

class DeleteOrderMassAction extends MassAction
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * DeleteOrderMassAction constructor.
     * @param ContextInterface $context
     * @param Data $helper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        Data $helper,
        $components = [],
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $components, $data);
    }

    /**
     * @inheritdoc
     */
    public function prepare()
    {
        parent::prepare();
        if ($this->helper->isEnabled()) {
            $config = $this->getConfiguration();
            $config['actions'][] = [
                'component' => 'uiComponent',
                'type' => 'delete_order',
                'label' => __('Delete'),
                'url' => $this->getContext()->getUrl('deleteorder/order/massDelete'),
                'confirm' => [
                    'title' => __('Delete Order(s)'),
                    'message' => __('Are you sure you want to delete selected order(s)?')
                ]
            ];
            $this->setData('config', $config);
        }
    }
}