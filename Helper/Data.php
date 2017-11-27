<?php
namespace Mage2way\DeleteOrder\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * path to delete order enable option
     */
    const XML_PATH_DELETE_ORDER_ENABLED = 'delete_order/general/enable';

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_DELETE_ORDER_ENABLED, ScopeInterface::SCOPE_STORE);
    }
}