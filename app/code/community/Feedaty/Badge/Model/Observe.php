<?php
class Feedaty_Badge_Model_Observe
{
        public function intercept_order(& $observer){
        	
        	$order = $observer->getEvent()->getOrder();

            $verify = array();

        	foreach ($order->getAllStatusHistory() as $orderComment){
        		$verify[$orderComment->getStatus()]++; 
        	}
			
        	if (($order->getStatus() == Mage::getStoreConfig('feedaty_global/sendorder/sendorder')) && ($verify[Mage::getStoreConfig('feedaty_global/sendorder/sendorder')] <= 1)) {
	        	// ********************************
				// Getting informations about order
				// and products
				$objproducts = $order->getAllItems();
				unset($fd_products);
				
				foreach ($objproducts as $itemId => $item) {
					unset($tmp);
                    if (!$item->getParentItem()) {
                        $fd_oProduct = Mage::getModel('catalog/product')->load((int) $item->getProductId());

                        $tmp['Id'] = $item->getProductId();


                        Mage::getModel('core/url_rewrite')->loadByRequestPath(
                            $tmp['Url'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).$fd_oProduct->getUrlPath()
                        );
                        if ($fd_oProduct->getImage() != "no_selection")
                            $tmp['ImageUrl'] = Mage::getModel('catalog/product_media_config')->getMediaUrl( $fd_oProduct->getImage() );
                        else
                            $tmp['ImageUrl'] = "";
                        //$tmp['sku'] = $item->getSku();

                        $tmp['Name'] = $item->getName();
                        $tmp['Brand'] = $item->getBrand();
                        if (is_null($tmp['Brand'])) $tmp['Brand']  = "";


                        //$tmp['Price'] = $item->getPrice();

                        $fd_products[] = $tmp;
                    }
				}
				// ********************************
				
				// *******************************
				// Formatting the array to be sent
				$tmp_order['OrderId'] = $order->getId();
				$tmp_order['OrderDate'] = date("Y-m-d H:i:s");
				$tmp_order['CustomerEmail'] = $order->getCustomerEmail();
				$tmp_order['CustomerId'] = $order->getCustomerEmail();
				$tmp_order['Platform'] = "Magento ".MAGE::getVersion();
				$tmp_order['Products'] = $fd_products;

				$fd_data['merchantCode'] = Mage::getStoreConfig('feedaty_global/feedaty_preferences/feedaty_code');
                $fd_data['orders'][] = $tmp_order;

				// *******************************
				
				// *********************************
				// Sending request to feedaty server
				Feedaty_Badge_Model_WebService::send_order($fd_data);
				// *********************************
				
        	}
        }
}