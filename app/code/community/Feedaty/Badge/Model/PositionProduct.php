<?php
class Feedaty_Badge_Model_PositionProduct
{
    public static function toOptionArray()
    {
    	$return = array(
    		array("value"=>"content","label"=>Mage::helper('core')->__("After Content")),
    		array("value"=>"catalog.product.related","label"=>Mage::helper('core')->__("Product Related")),
    		array("value"=>"productalert.price","label"=>Mage::helper('core')->__("After Price")),
    		array("value"=>"productalert.stock","label"=>Mage::helper('core')->__("After Stock Informations")),
    		array("value"=>"product.info.simple","label"=>Mage::helper('core')->__("Simple")),
    		array("value"=>"product.info.simple.extra.child0","label"=>Mage::helper('core')->__("Extra child")),
    		array("value"=>"product.info.addtocart","label"=>Mage::helper('core')->__("Add to cart")),
    		array("value"=>"product.description","label"=>Mage::helper('core')->__("Product description")),
    		array("value"=>"product.attributes","label"=>Mage::helper('core')->__("Product attributes")),
    		array("value"=>"product.info.upsell","label"=>Mage::helper('core')->__("Product upsell")),
    		array("value"=>"product.info.additional","label"=>Mage::helper('core')->__("Product additional")),
    		array("value"=>"product_tag_list","label"=>Mage::helper('core')->__("Product tag list"))
    	);
		
		return $return;
    }
}

