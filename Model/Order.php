<?php
namespace Mage2way\DeleteOrder\Model;

use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\App\ResourceConnection;

class Order extends AbstractModel
{
    /**
     * @var ManagerInterface
     */
    protected $messageManagerInterface;

    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * Order constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ManagerInterface $messageManagerInterface
     * @param ResourceConnection $resource
     * @param AbstractDb|null $resourceCollection
     * @param AbstractResource|null $abstractResource
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ManagerInterface $messageManagerInterface,
        ResourceConnection $resource,
        AbstractDb $resourceCollection = null,
        AbstractResource $abstractResource= null,
        array $data = []
    ) {
        $this->messageManagerInterface = $messageManagerInterface;
        $this->resource = $resource;
        parent::__construct($context, $registry, $abstractResource, $resourceCollection, $data);
    }

    /**
     * @param array $orderIds
     */
    public function deleteOrders($orderIds = [])
    {
        try {
            $deleteSqlQueries = $this->prepareSqlDeleteQuery($orderIds);
            foreach ($deleteSqlQueries as $deleteSqlQuery) {
                $this->resource->getConnection()->query($deleteSqlQuery);
            }
            $this->messageManagerInterface->addSuccess(
                __('%1 order(s) successfully deleted', sizeof($orderIds))
            );
        } catch (\Exception $e) {
            $this->messageManagerInterface->addError(__($e->getMessage()));
        }
    }

    /**
     * @param array $orderIds
     * @return array
     */
    public function prepareSqlDeleteQuery($orderIds = [])
    {
        $orderIds = is_array($orderIds) ? implode(",", $orderIds) : $orderIds;

        $quoteDeleteQuery = $this->prepareQuoteDeleteQuery($orderIds);
        $invocieDeleteQuery = $this->prepareInvoiceDeleteQuery($orderIds);
        $creditmemoDeleteQuery = $this->prepareCreditmemoDeleteQuery($orderIds);
        $shipmentDeleteQuery = $this->prepareShipmentDeleteQuery($orderIds);
        $salesTaxDeleteQuery = $this->prepareSalesTaxDeleteQuery($orderIds);
        $relatedDeleteQuery = $this->prepareRelatedDeleteQuery($orderIds);

        return array_merge(
            $quoteDeleteQuery,
            $invocieDeleteQuery,
            $creditmemoDeleteQuery,
            $shipmentDeleteQuery,
            $salesTaxDeleteQuery,
            $relatedDeleteQuery
        );
    }

    /**
     * @param null $orderIds
     * @return array
     */
    protected function prepareQuoteDeleteQuery($orderIds = null)
    {
        $sqlQry = [];
        $connection = $this->resource->getConnection();
        $quoteIds = $connection->fetchCol(
            $connection->select()
                ->from($this->resource->getTableName('sales_order'), 'quote_id'
                )->where('entity_id IN (?)', json_decode('[' . $orderIds . ']', true))
        );
        if (!empty($quoteIds)) {
            $quoteIds = implode(",", $quoteIds);
            // DELETE from quote_address_item
            $sqlQry[] = "DELETE FROM {$this->resource->getTableName('quote_address_item')}
            WHERE parent_item_id IN 
            (SELECT address_id FROM {$this->resource->getTableName('quote_address')}
            WHERE quote_id IN ({$quoteIds}));";

            // DELETE from quote_shipping_rate
            $sqlQry[] = "DELETE FROM {$this->resource->getTableName('quote_shipping_rate')}
            WHERE address_id IN 
            (SELECT address_id FROM {$this->resource->getTableName('quote_address')}
            WHERE quote_id IN ({$quoteIds}));";

            // DELETE from quote_id_mask
            $sqlQry[] = "DELETE FROM {$this->resource->getTableName('quote_id_mask')}
            WHERE quote_id IN ({$quoteIds});";

            // DELETE from quote_item_option
            $sqlQry[] = "DELETE FROM {$this->resource->getTableName('quote_item_option')}
            WHERE item_id IN 
            (SELECT item_id FROM {$this->resource->getTableName('quote_item')}
            WHERE quote_id IN ({$quoteIds}));";

            // DELETE from quote_address
            $sqlQry[] = "DELETE FROM {$this->resource->getTableName('quote_address')}
            WHERE quote_id IN ({$quoteIds});";

            // DELETE from quote_payment
            $sqlQry[] = "DELETE FROM {$this->resource->getTableName('quote_payment')}
            WHERE quote_id IN ({$quoteIds});";

            // DELETE from quote_item
            $sqlQry[] = "DELETE FROM {$this->resource->getTableName('quote_item')}
            WHERE quote_id IN ({$quoteIds});";

            // DELETE from quote
            $sqlQry[] = "DELETE FROM {$this->resource->getTableName('quote')}
            WHERE entity_id IN ({$quoteIds});";
        }

        return $sqlQry;
    }

    /**
     * @param null $orderIds
     * @return array
     */
    protected function prepareInvoiceDeleteQuery($orderIds = null)
    {
        $sqlQry = [];
        // DELETE from sales_invoice_item
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_invoice_item')}
            WHERE parent_id IN 
            (SELECT entity_id FROM {$this->resource->getTableName('sales_invoice')}
            WHERE order_id IN ({$orderIds}));";

        // DELETE from sales_invoice_comment
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_invoice_comment')}
            WHERE parent_id IN 
            (SELECT entity_id FROM {$this->resource->getTableName('sales_invoice')}
            WHERE order_id IN ({$orderIds}));";

        // DELETE from sales_invoice_grid
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_invoice_grid')}
            WHERE order_id IN ({$orderIds});";

        // DELETE from sales_invoice
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_invoice')}
            WHERE order_id IN ({$orderIds});";

        return $sqlQry;
    }

    /**
     * @param null $orderIds
     * @return array
     */
    protected function prepareCreditmemoDeleteQuery($orderIds = null)
    {
        $sqlQry = [];
        // DELETE from sales_creditmemo_item
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_creditmemo_item')}
            WHERE parent_id IN 
            (SELECT entity_id FROM {$this->resource->getTableName('sales_creditmemo')}
            WHERE order_id IN ({$orderIds}));";

        // DELETE from sales_creditmemo_comment
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_creditmemo_comment')}
            WHERE parent_id IN 
            (SELECT entity_id FROM {$this->resource->getTableName('sales_creditmemo')}
            WHERE order_id IN ({$orderIds}));";

        // DELETE from sales_creditmemo_grid
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_creditmemo_grid')}
            WHERE order_id IN ({$orderIds});";

        // DELETE from sales_creditmemo
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_creditmemo')}
            WHERE order_id IN ({$orderIds});";

        return $sqlQry;
    }

    /**
     * @param null $orderIds
     * @return array
     */
    protected function prepareShipmentDeleteQuery($orderIds = null)
    {
        $sqlQry = [];
        // DELETE from sales_shipment_item
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_shipment_item')}
            WHERE parent_id IN 
            (SELECT entity_id FROM {$this->resource->getTableName('sales_shipment')}
            WHERE order_id IN ({$orderIds}));";
        // DELETE from sales_shipment_comment
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_shipment_comment')}
            WHERE parent_id IN 
            (SELECT entity_id FROM {$this->resource->getTableName('sales_shipment')}
            WHERE order_id IN ({$orderIds}));";
        // DELETE from sales_shipment_track
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_shipment_track')}
            WHERE parent_id IN 
            (SELECT entity_id FROM {$this->resource->getTableName('sales_shipment')}
            WHERE order_id IN ({$orderIds}));";
        // DELETE from sales_shipment_grid
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_shipment_grid')}
            WHERE order_id IN ({$orderIds});";
        // DELETE from sales_shipment
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_shipment')}
            WHERE order_id IN ({$orderIds});";

        return $sqlQry;
    }

    /**
     * @param null $orderIds
     * @return array
     */
    protected function prepareSalesTaxDeleteQuery($orderIds = null)
    {
        $sqlQry = [];
        // DELETE from sales_order_tax_item
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_order_tax_item')}
            WHERE tax_id IN 
            (SELECT tax_id FROM {$this->resource->getTableName('sales_order_tax')}
            WHERE order_id IN ({$orderIds}));";
        // DELETE from sales_order_tax
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_order_tax')}
            WHERE order_id IN ({$orderIds});";

        return $sqlQry;
    }

    /**
     * @param null $orderIds
     * @return array
     */
    protected function prepareRelatedDeleteQuery($orderIds = null)
    {
        $sqlQry = [];
        // DELETE from sales_order_payment
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_order_payment')}
            WHERE parent_id IN ({$orderIds});";

        // DELETE from sales_order_status_history
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_order_status_history')}
            WHERE parent_id IN ({$orderIds});";

        // DELETE from sales_order_address
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_order_address')}
            WHERE parent_id IN ({$orderIds});";

        // DELETE from sales_order_item
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_order_item')}
            WHERE order_id IN ({$orderIds});";

        // DELETE from sales_order_grid
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_order_grid')}
            WHERE entity_id IN ({$orderIds});";

        // DELETE from sales_order
        $sqlQry[] = "DELETE FROM {$this->resource->getTableName('sales_order')}
            WHERE entity_id IN ({$orderIds});";

        return $sqlQry;
    }

}