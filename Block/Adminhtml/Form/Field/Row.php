<?php
namespace Elsnertech\Noindex\Block\Adminhtml\Form\Field;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Elsnertech\Noindex\Block\Adminhtml\Form\Field\DynamicColumn;
class Row extends AbstractFieldArray
{
    /**
     * @var Templete
     */
    private $templeteRenderer;
    /**
     * Prepare rendering the new field by adding all the needed columns
     */
    protected function _prepareToRender()
    {
        $this->addColumn('text_1', ['label' => __('Enter Your Url'), 'class' => 'required-entry']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Row');
    }
    /**
     * Prepare existing row data object
     *
     * @param DataObject $row
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];
        $templete = $row->getTemplete();
        if ($templete !== null) {
            $options['option_' . $this->getTempleteRenderer()->calcOptionHash($templete)] = 'selected="selected"';
        }
        $row->setData('option_extra_attrs', $options);
    }
    /**
     *
     * @return Templete
     * @throws LocalizedException
     */
    private function getTempleteRenderer()
    {
        if (!$this->templeteRenderer) {
            $this->templeteRenderer = $this->getLayout()->createBlock(
                DynamicColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->templeteRenderer;
    }
}