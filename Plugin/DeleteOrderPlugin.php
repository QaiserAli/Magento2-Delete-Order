<?php
namespace Mage2way\DeleteOrder\Plugin;

use Mage2way\DeleteOrder\Helper\Data;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Sales\Ui\Component\Listing\Column\ViewAction;

class DeleteOrderPlugin extends ViewAction
{
    /**
     * @var Data
     */
    protected $helper;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        Data $helper,
        array $components = [],
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $uiComponentFactory, $urlBuilder, $components, $data);
    }

    /**
     * @param ViewAction $subject
     * @param $dataSource
     * @return array|void
     */
    public function afterPrepareDataSource(ViewAction $subject, $dataSource)
    {
        if (!$this->helper->isEnabled()) {
            return $dataSource;
        }
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['entity_id'])) {
                    $deleteUrlPath = 'deleteorder/order/index';
                    $item['actions']['delete'] = [
                         'href' => $this->urlBuilder->getUrl(
                             $deleteUrlPath,
                                [
                                    'entity_id' => $item['entity_id']
                                ]
                            ),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete Order'),
                            'message' => __('Are you sure you want to delete this order?')
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}