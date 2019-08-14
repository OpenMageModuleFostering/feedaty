<?php
class Feedaty_Badge_Block_LinkCsv extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        $url = $this->getUrl('feedatyexport.csv'); //

        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('scalable')
                    ->setLabel($this->__('Salva ora'))
                    ->setOnClick("document.location.href = '".$url."'")
                    ->toHtml();

        return $html;
    }
}