<?php
class Feedaty_Badge_Model_OrderStatuses
{
    public function toOptionArray()
    {
    	$statuses = Mage::getSingleton('sales/order_config')->getStatuses();
    	
    	foreach ($statuses as $k=>$v) {
    		$return[] = array('value'=>$k,'label'=>$v);
    	}
		
		return $return;
    }
}

