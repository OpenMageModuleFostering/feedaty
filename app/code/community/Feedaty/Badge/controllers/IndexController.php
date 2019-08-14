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
		    ->addFieldToFilter('status', Mage::getStoreConfig('feedaty_global/sendorder/sendorder'));

        foreach ($orders as $order) {

            $objproducts = $order->getAllItems();

            foreach ($objproducts as $itemId => $item) {
                unset($tmp);
                if (!$item->getParentItem()) {
                    $fd_oProduct = Mage::getModel('catalog/product')->load((int) $item->getProductId());

                    if ($fd_oProduct->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
                        $selectionCollection = $fd_oProduct->getTypeInstance(true)->getSelectionsCollection(
                            $fd_oProduct->getTypeInstance(true)->getOptionsIds($fd_oProduct), $fd_oProduct
                        );
                        foreach($selectionCollection as $option) {
                            $bundleproduct = Mage::getModel('catalog/product')->load($option->product_id);

                            $tmp['Id'] = $bundleproduct->getProductId();

                            Mage::getModel('core/url_rewrite')->loadByRequestPath(
                                $tmp['Url'] = Mage::app()->getStore($order->getStoreId())->getUrl($bundleproduct->getUrlPath())
                            );

                            if ($fd_oProduct->getImage() != "no_selection")
                                $tmp['ImageUrl'] = Mage::getModel('catalog/product_media_config')->getMediaUrl( $bundleproduct->getImage() );
                            else
                                $tmp['ImageUrl'] = "";
                            //$tmp['sku'] = $item->getSku();

                            $tmp['Name'] = $bundleproduct->getName();
                            $tmp['Brand'] = $bundleproduct->getBrand();
                            if (is_null($tmp['Brand'])) $bundleproduct['Brand']  = "";
                            $fd_products[] = $tmp;


                            $csv .= '"'.$order->getId().'","'.$order->getBillingAddress()->getEmail().'","'.$order->getBillingAddress()->getEmail().'",'
                                .'"'.$order->getCreatedAt().'","'.$item->getProductId().'","'.str_replace('"','""',$tmp['Name']).'","'.$tmp['Url'].'","'.$tmp['ImageUrl'].'","Magento '.MAGE::getVersion().' CSV"'
                                ."\n";


                        }
                    } else {
                        $tmp['Id'] = $item->getProductId();

                        Mage::getModel('core/url_rewrite')->loadByRequestPath(
                            $tmp['Url'] = Mage::app()->getStore($order->getStoreId())->getUrl($fd_oProduct->getUrlPath())
                        );



                        if ($fd_oProduct->getImage() != "no_selection")
                            $tmp['ImageUrl'] = Mage::getModel('catalog/product_media_config')->getMediaUrl( $fd_oProduct->getImage() );
                        else
                            $tmp['ImageUrl'] = "";
                        //$tmp['sku'] = $item->getSku();

                        $tmp['Name'] = $item->getName();
                        $tmp['Brand'] = $item->getBrand();
                        if (is_null($tmp['Brand'])) $tmp['Brand']  = "";


                        $csv .= '"'.$order->getId().'","'.$order->getBillingAddress()->getEmail().'","'.$order->getBillingAddress()->getEmail().'",'
                            .'"'.$order->getCreatedAt().'","'.$item->getProductId().'","'.str_replace('"','""',$tmp['Name']).'","'.$tmp['Url'].'","'.$tmp['ImageUrl'].'","Magento '.MAGE::getVersion().' CSV"'
                            ."\n";
                    }
                }
            }

		}
		
		echo $csv;
	}
}