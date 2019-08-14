<?php
class Feedaty_Badge_Model_PositionStore
{
    public static function toOptionArray()
    {
    	$return = array(
    		array("value"=>"cms_page","label"=>Mage::helper('core')->__("Position Cms page")),
    		array("value"=>"page_content_heading","label"=>Mage::helper('core')->__("Position Page content heading")),
    		array("value"=>"cart_sidebar","label"=>Mage::helper('core')->__("Position Cart sidebar")),
    		array("value"=>"wishlist_sidebar","label"=>Mage::helper('core')->__("Position Wishlist sidebar")),
    		array("value"=>"right.reports.product.viewed","label"=>Mage::helper('core')->__("Position Right product viewed")),
    		array("value"=>"right.reports.product.compared","label"=>Mage::helper('core')->__("Position Right product compared")),
    		/*array("value"=>"right.permanent.callout","label"=>Mage::helper('core')->__("Position Right permanent callout")),*/
    		array("value"=>"right.poll","label"=>Mage::helper('core')->__("Position Right poll")),
    		array("value"=>"right","label"=>Mage::helper('core')->__("Position Right")),
			array("value"=>"left","label"=>Mage::helper('core')->__("Position Left")),
            array("value"=>"footer","label"=>Mage::helper('core')->__("Position Footer")),
    		array("value"=>"bottom.container","label"=>Mage::helper('core')->__("Position Bottom container")),
            array("value"=>"footer_links","label"=>Mage::helper('core')->__("Position Footer links"))
    	);
		
		return $return;
    }
}

