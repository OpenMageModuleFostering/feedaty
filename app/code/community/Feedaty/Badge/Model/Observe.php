<?php
class Feedaty_Badge_Model_Observe
{
        public function intercept_order(& $observer){
        	
        	$order = $observer->getEvent()->getOrder();
        	
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
					//$tmp['sku'] = $item->getSku();
					$tmp['Name'] = $item->getName();
					$tmp['Brand'] = $item->getBrand();
					$tmp['Id'] = $item->getProductId();
					$fd_oProduct = Mage::getModel('catalog/product')->load((int) $tmp['Id']);
					Mage::getModel('core/url_rewrite')->loadByRequestPath(
					    $tmp['Url'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).$fd_oProduct->getUrlPath()
					);
					if ($fd_oProduct->getImage() != "no_selection")
						$tmp['ImageUrl'] = Mage::getModel('catalog/product_media_config')->getMediaUrl( $fd_oProduct->getImage() );
					else
						$tmp['ImageUrl'] = "";
					//$tmp['Price'] = $item->getPrice();
					
					$fd_products[] = $tmp;
				}
				// ********************************
				
				// *******************************
				// Formatting the array to be sent
				$tmp_order['OrderId'] = $order->getId();
				$tmp_order['OrderDate'] = date("Y-m-d H:i:s");
				$tmp_order['CustomerEmail'] = $order->getBillingAddress()->getEmail();
				$tmp_order['CustomerId'] = $order->getBillingAddress()->getEmail();
				//$order['name'] = $order->getBillingAddress()->getName();
				$tmp_order['Platform'] = "Magento ".MAGE::getVersion();
				$tmp_order['Products'] = $fd_products;
				
				$fd_data['orders'][] = $tmp_order;
				$fd_data['merchantCode'] = Mage::getStoreConfig('feedaty_global/feedaty_preferences/feedaty_code');

				// *******************************
				
				// *********************************
				// Sending request to feedaty server
				Feedaty_Badge_Model_WebService::send_order($fd_data);
				// *********************************
				
        	}
        }
}