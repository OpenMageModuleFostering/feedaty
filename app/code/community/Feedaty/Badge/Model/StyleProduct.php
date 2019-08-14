<?php
class Feedaty_Badge_Model_StyleProduct
{
    public function toOptionArray()
    {
		if (strlen(Mage::getStoreConfig('feedaty_global/feedaty_preferences/feedaty_code')) == 0) return array();
        $data = Feedaty_Badge_Model_WebService::_get_FeedatyData();
		
		foreach ($data as $k=>$v) {
            if ($v['type'] == "product")
			    $return[] = array('value'=>$k,'label'=>' <img src="'.$v['thumb'].'"><br />');
		}
		
		
		return $return;
    }
}

