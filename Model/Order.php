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
    protected $messageManagerInterface;
    protected $resource;

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

    public function prepareSqlDeleteQuery($orderIds = [])
    {
        $orderIds = implode(",", $orderIds);
        $tableName = $this->resource->getTableName('sales_invoice_item');

        // DELETE from quote_address_item
        // DELETE from quote_shipping_rate
        // DELETE from quote_id_mask
        // DELETE from quote_item_option
        // DELETE from quote_address
        // DELETE from quote_payment
        // DELETE from quote_item
        // DELETE from quote

        $invocieDeleteQuery = $this->prepareInvoiceDeleteQuery($orderIds);
        $creditmemoDeleteQuery = $this->prepareCreditmemoDeleteQuery($orderIds);


        // DELETE from sales_shipment_item
        // DELETE from sales_shipment_comment
        // DELETE from sales_shipment_track
        // DELETE from sales_shipment_grid
        // DELETE from sales_shipment

        // DELETE from sales_order_tax_item
        // DELETE from sales_order_tax

        // DELETE from sales_order_payment
        // DELETE from sales_order_status_history
        // DELETE from sales_order_address

        // DELETE from sales_order_item
        // DELETE from sales_order_grid
        // DELETE from sales_order

        return array_merge($invocieDeleteQuery, $creditmemoDeleteQuery);
    }

    protected function prepareInvoiceDeleteQuery($orderIds = [])
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

    protected function prepareCreditmemoDeleteQuery($orderIds = [])
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

}