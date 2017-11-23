##### Module is under development ......

#### DeleteOrder Extension

The magento 2 default functionalities does not allow admin or site owner to delete test order(s) or any other type of order(s) that has been canceled, closed or shouldn't exist anymore.
So in order to provide such functionality, i developed this module. 
It's easy to use and works like default magento 2 way. 


The extension delete data from the following tables
 - **Order Quotes**
    1. {quote_address_item}
    2. {quote_shipping_rate}
    3. {quote_id_mask}
    4. {quote_item_option}
    5. {quote_address}
    6. {quote_payment}
    7. {quote_item}
    8. {quote}
 - **Order Invoices**
    9.  {sales_invoice_item}
    10. {sales_invoice_comment}
    11. {sales_invoice_grid}
    12. {sales_invoice}
 - **Order Credit Memos**
    13. {sales_creditmemo_item}
    14. {sales_creditmemo_comment}
    15. {sales_creditmemo_grid}
    16. {sales_creditmemo}
 - **Order Shipment**
    17. {sales_shipment_item}
    18. {sales_shipment_comment}
    19. {sales_shipment_track}
    20. {sales_shipment_grid}
    21. {sales_shipment}
 - **Order Tax**
    22. {sales_order_tax_item}
    23. {sales_order_tax}
 - **Order Payment**
    24. {sales_order_payment}
 - **Sales Order**
    25. {sales_order_status_history}
    26. {sales_order_address}
    27. {sales_order_item}
    28. {sales_order_grid}
    29. {sales_order}