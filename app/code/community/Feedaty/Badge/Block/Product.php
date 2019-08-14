<?php
class Feedaty_Badge_Block_Product extends Mage_Core_Block_Template
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('feedaty/base.phtml');
    }
}