<?php
class Feedaty_Badge_IndexController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction() {
		
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=feedatyexport.csv");
		header("Content-Transfer-Encoding: binary");
		
		$csv = '"Order ID","UserID","E-mail","Date","Product ID","Extra","Product Url","Product Image","Platform"'."\n";
		
		$orders = Mage::getModel('sales/order')->getCollection()
		    ->addFieldToFilter('status', Mage::getStoreConfig('feedaty_global/sendorder/sendorder'))
		    ;
		
		foreach ($orders as $order) {
			
			$objproducts = $order->getAllItems();
			
			foreach ($objproducts as $itemId => $item) {
				$fd_oProduct = Mage::getModel('catalog/product')->load((int) $item->getProductId());
					Mage::getModel('core/url_rewrite')->loadByRequestPath(
					    $tmp['Url'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).$fd_oProduct->getUrlPath()
					);
					if ($fd_oProduct->getImage() != "no_selection")
						$tmp['ImageUrl'] = Mage::getModel('catalog/product_media_config')->getMediaUrl( $fd_oProduct->getImage() );
					else
						$tmp['ImageUrl'] = "";
				
				$csv .= '"'.$order->getId().'","'.$order->getBillingAddress()->getEmail().'","'.$order->getBillingAddress()->getEmail().'",'
						.'"'.$order->getCreatedAt().'","'.$item->getProductId().'","'.str_replace('"','""',$item->getName()).'","'.$tmp['Url'].'","'.$tmp['ImageUrl'].'","Magento '.MAGE::getVersion().'"'
						."\n";
			}
		}
		
		echo $csv;
	}
}