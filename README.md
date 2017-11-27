##### Module is under development ......

#### DeleteOrder Extension

The magento 2 default functionalities does not allow admin or site owner to delete test order(s) or any other type of order(s) that has been canceled, closed or shouldn't exist anymore.
So in order to provide such functionality, i developed this module. 
It's easy to use and works like default magento 2 way. 

The extension comes with the following features
- Configuration option to enable or disable the module
- Delete single or multiple order(s) from order grid page
- Delete single order from the order view page
- Delete order(s) in bulk

#### Configuration Option
The configuration option can be found in Stores -> Configuration -> Other Settings -> Delete Orders
![screenshot](https://user-images.githubusercontent.com/2330736/33277078-c477dbb6-d397-11e7-9355-b3c278a452fb.png)

#### Delete Order(s) from Order Grid 

##### Delete order from order view page



The extension delete data from the following tables
 - **Order Quotes** 
    - {quote_address_item}
    - {quote_shipping_rate}
    - {quote_id_mask}
    - {quote_item_option}
    - {quote_address}
    - {quote_payment}
    - {quote_item}
    - {quote}
 - **Order Invoices**
    - {sales_invoice_item}
    - {sales_invoice_comment}
    - {sales_invoice_grid}
    - {sales_invoice}
 - **Order Credit Memos**
    - {sales_creditmemo_item}
    - {sales_creditmemo_comment}
    - {sales_creditmemo_grid}
    - {sales_creditmemo}
 - **Order Shipment**
    - {sales_shipment_item}
    - {sales_shipment_comment}
    - {sales_shipment_track}
    - {sales_shipment_grid}
    - {sales_shipment}
 - **Order Tax**
    - {sales_order_tax_item}
    - {sales_order_tax}
 - **Sales Order**
    - {sales_order_payment}
    - {sales_order_status_history}
    - {sales_order_address}
    - {sales_order_item}
    - {sales_order_grid}
    - {sales_order}