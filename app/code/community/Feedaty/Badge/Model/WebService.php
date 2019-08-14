<?php

class Feedaty_Badge_Model_WebService {
	// Fetch all reviews and info about badge
	
	
	public function retrive_informations_product($id) {
		$cache = Mage::app()->getCache();
		
		$content = $cache->load("feedaty_product_".$id.(int) FEEDATY_DEBUG);
		
		if (!$content || strlen($content) == 0) {
			$feedaty_code = Mage::getStoreConfig('feedaty_global/feedaty_preferences/feedaty_code');
			
			$ch = curl_init();
            $url = 'http://widget.zoorate.com/go.php?function=feed&action=ws&task=product&merchant_code='.$feedaty_code.'&ProductID='.$id.'&language='.Mage::app()->getLocale()->getLocaleCode();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, '3');
			$content = trim(curl_exec($ch));
			curl_close($ch);
			
			if (strlen($content) > 0)
			$cache->save($content, "feedaty_product_".$id, array("feedaty_cache"), 3*60*60); // 3 hours of cache
		}
		
		$data = json_decode($content,true);
		
		return $data;
	}
	
	public function retrive_informations_store() {
		$cache = Mage::app()->getCache();
		
		$content = $cache->load("feedaty_store");
		
		if (!$content || strlen($content) < 5) {
			$feedaty_code = Mage::getStoreConfig('feedaty_global/feedaty_preferences/feedaty_code');
			$ch = curl_init();
			// Recensioni in ordine dalla più recente alla meno
            $url = 'http://widget.zoorate.com/go.php?function=feed&action=ws&task=merchant&merchant_code='.$feedaty_code;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, '3');
			$content = trim(curl_exec($ch));
			curl_close($ch);
			if (strlen($content) > 0)
			$cache->save($content, "feedaty_store", array("feedaty_cache"), 3*60*60); // 3 hours of cache
		}
		
		$data = json_decode($content,true);
    
    //print_r($data);
		
		return $data;
	}
	
	public function send_order($data) {
		$ch = curl_init();
        $url = 'http://www.zoorate.com/ws/feedatyapi.svc/SubmitOrders';

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, '60');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		$content = trim(curl_exec($ch));
		curl_close($ch);
	}

    public function _get_FeedatyData() {
        $cache = Mage::app()->getCache();

        $content = $cache->load("feedaty_store");

        Feedaty_Badge_Model_WebService::send_notification();

        $feedaty_code = Mage::getStoreConfig('feedaty_global/feedaty_preferences/feedaty_code');

        $string = "FeedatyData".$feedaty_code.Mage::app()->getLocale()->getLocaleCode().(int) FEEDATY_DEBUG;
        $content =$cache->load($string);

		if (!$content || strlen($content) == 0) {
            $ch = curl_init();
            $url = 'http://widget.zoorate.com/go.php?function=feed_be&action=widget_list&merchant_code='.$feedaty_code.'&language='.Mage::app()->getLocale()->getLocaleCode();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, '60');
            $content = trim(curl_exec($ch));
            curl_close($ch);

            $cache->save($content, "FeedatyData".$feedaty_code.Mage::app()->getLocale()->getLocaleCode().(int) FEEDATY_DEBUG, array("feedaty_cache"), 24*60*60); // 24 hours of cache
        }

        $data = json_decode($content,true);

        return $data;
    }

    public function send_notification() {
        $cache = Mage::app()->getCache();

        $content = $cache->load("feedaty_notification");

        $cnt = Mage::getStoreConfig('feedaty_global/feedaty_preferences/feedaty_code')."-".Mage::getStoreConfig('feedaty_badge_options/widget_store/enabled')."-".Mage::getStoreConfig('feedaty_badge_options/widget_products/product_enabled');

        if ($content != $cnt) {
            $store = Mage::app()->getStore();

            $ver = json_decode(json_encode(Mage::getConfig()->getNode()->modules->Feedaty_Badge->version),true);

            $fdata['keyValuePairs'][] = array("Key" => "Platform", "Value" => "Magento ".MAGE::getVersion());
            $fdata['keyValuePairs'][] = array("Key" => "Version", "Value" => (string) Mage::getConfig()->getModuleConfig("Feedaty_Badge")->version);
            $fdata['keyValuePairs'][] = array("Key" => "Url", "Value" => Mage::getBaseUrl());
            $fdata['keyValuePairs'][] = array("Key" => "Os", "Value" => PHP_OS);
            $fdata['keyValuePairs'][] = array("Key" => "Php Version", "Value" => phpversion());
            $fdata['keyValuePairs'][] = array("Key" => "Name", "Value" => $store->getName());
            $fdata['keyValuePairs'][] = array("Key" => "Action", "Value" => "Enabled");
            $fdata['keyValuePairs'][] = array("Key" => "Position_Merchant", "Value" => Mage::getStoreConfig('feedaty_badge_options/widget_store/store_position'));
            $fdata['keyValuePairs'][] = array("Key" => "Position_Product", "Value" => Mage::getStoreConfig('feedaty_badge_options/widget_products/product_position'));
            $fdata['keyValuePairs'][] = array("Key" => "Status", "Value" => Mage::getStoreConfig('feedaty_global/sendorder/sendorder'));
            $fdata['merchantCode'] = Mage::getStoreConfig('feedaty_global/feedaty_preferences/feedaty_code');

            $ch = curl_init();

            $url = 'http://www.zoorate.com/ws/feedatyapi.svc/SetPluginKeyValue';

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, '60');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($fdata));
            curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json','Expect:'));
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            $content = trim(curl_exec($ch));
            curl_close($ch);

            $cache->save($cnt, "feedaty_notification", array("feedaty_cache"), 10*24*60*60);
        }
    }
}	