<?php
class Feedaty_Badge_Model_Order
{
    public function toOptionArray()
    {
    	$return = array(
    		array("value"=>"0","label"=>Mage::helper('adminhtml')->__("Newest reviews first")),
    		array("value"=>"1","label"=>Mage::helper('adminhtml')->__("Old reviews first")),
    	);
		
		return $return;
    }
}

