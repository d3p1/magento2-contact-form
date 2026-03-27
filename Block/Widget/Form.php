<?php
/**
 * @description Contact form widget block
 * @author      C. M. de Picciotto <d3p1@d3p1.dev> (https://d3p1.dev/)
 */
namespace D3p1\ContactForm\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class Form extends Template implements BlockInterface
{
    /**
     * @var string
     */
    protected $_template = 'D3p1_ContactForm::widget/form.phtml';

    /**
     * Get contact URL
     *
     * @return string
     */
    public function getContactUrl()
    {
        return $this->getUrl('contact/contact/send');
    }

    /**
     * Get form additional info block
     *
     * @return Template|null
     * @note   Allow configured an optional block to add
     *         additional information to this form
     */
    public function getFormAdditionalInfoBlock()
    {
        return $this->getData('form_additional_info_block');
    }

    /**
     * Set form additional info block
     *
     * @param  Template $block
     * @return $this
     * @note   Allow configured an optional block to add
     *         additional information to this form
     */
    public function setFormAdditionalInfoBlock(Template $block)
    {
        return $this->setData('form_additional_info_block', $block);
    }

    /**
     * Get form additional information HTML
     *
     * @return string
     * @note   Allow configured an optional block to add
     *         additional information to this form
     */
    public function getFormAdditionalInfoHtml()
    {
        if (!is_null($block = $this->getFormAdditionalInfoBlock())) {
            return $block->toHtml();
        }

        return $this->getChildHtml('form_additional_info');
    }
}
