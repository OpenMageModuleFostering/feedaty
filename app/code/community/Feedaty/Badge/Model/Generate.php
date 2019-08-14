<?php

define("FEEDATY_DEBUG",false);

class Feedaty_Badge_Model_Generate {
	public function product_badge($observer) {
        $block = $observer->getBlock();
        $transport = $observer->getTransport();

        if ($block->getNameInLayout()==Mage::getStoreConfig('feedaty_badge_options/widget_products/product_position')) {
            // Verify that plugin is enable
            $plugin_enabled = Mage::getStoreConfig('feedaty_badge_options/widget_products/product_enabled');
            if($plugin_enabled!=0){
                $product = Mage::registry('current_product');
                $product = $product->getId();
                if (!is_null($product)) {
                    $data = Feedaty_Badge_Model_WebService::_get_FeedatyData();
                    $ver = json_decode(json_encode(Mage::getConfig()->getNode()->modules->Feedaty_Badge->version),true);
                    $html = '<!-- PlPMa '.$ver[0].' -->'.str_replace("__insert_ID__",$product,$data[Mage::getStoreConfig('feedaty_badge_options/widget_products/badge_style')]['html_embed']).$transport->getHtml();
                    $transport->setHtml($html);
                }
            }
        }
	}
	public function product_reviews($observer) {
		$block = $observer->getBlock();
		$transport = $observer->getTransport();
			  
		$html = $transport->getHtml();
		//$transport->setHtml("<p>".$block->getNameInLayout()."</p>".$html);return;
		
		if ($block->getNameInLayout()==Mage::getStoreConfig('feedaty_badge_options/review_products/product_position')) {
			// Verify that plugin is enable
			$plugin_enabled = Mage::getStoreConfig('feedaty_badge_options/review_products/product_enabled');
			$product = Mage::registry('current_product');
			if($plugin_enabled!=0 && !is_null($product)){
                $product = $product->getId();
				$toview['data_review'] = Feedaty_Badge_Model_WebService::retrive_informations_product($product);

				if (Mage::getStoreConfig('feedaty_badge_options/review_products/order_review') == 1)
					$toview['data_review']['Feedbacks'] = array_reverse($toview['data_review']['Feedbacks']);
				
				$toview['count_review'] = Mage::getStoreConfig('feedaty_badge_options/review_products/count_review');
				$toview['link'] = '<a href="'.$toview['data_review']['Product']['Url'].'">'.Mage::helper('adminhtml')->__('Leggi tutte le recensioni').'</a>';

				if (count($toview['data_review']['Feedbacks']) > 0) {
					$html = $transport->getHtml();
					$buttons = Mage::app()->getLayout()->createBlock('badge/product', 'addthis', array('template'=>'feedaty/product_reviews.phtml'))->setData('view', $toview)->setTemplate('feedaty/product_reviews.phtml'); //->setData('view', $toview);
					$html .= $buttons->toHtml();
					$transport->setHtml($html);
				}
			}
		}
	}
	public function store_badge($observer) {
		$block = $observer->getBlock();
		$transport = $observer->getTransport();
		
		if ($block->getNameInLayout()==Mage::getStoreConfig('feedaty_badge_options/widget_store/store_position')) {
            Feedaty_Badge_Model_WebService::send_notification();
			// Verify that plugin is enable
			$plugin_enabled = Mage::getStoreConfig('feedaty_badge_options/widget_store/enabled');
			if($plugin_enabled!=0){
                $data = Feedaty_Badge_Model_WebService::_get_FeedatyData();

                $ver = json_decode(json_encode(Mage::getConfig()->getNode()->modules->Feedaty_Badge->version),true);
                $html = '<!-- PlSMa '.$ver[0].' -->'.$data[Mage::getStoreConfig('feedaty_badge_options/widget_store/badge_style')]['html_embed'].$transport->getHtml();
                $transport->setHtml($html);
			}
		}
	}
}