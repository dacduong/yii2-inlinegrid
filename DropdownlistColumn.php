<?php

namespace dacduong\inlinegrid;

use kartik\grid\GridView;
use yii\helpers\Html;

class DropdownlistColumn extends TextInputColumn
{
    
    public $name = 'itdropdownlist';
    
    public $cssClass = 'it-row-dropdownlist';
    
    public $pageSummaryFunc = GridView::F_COUNT;
    
    /**
     *
     * @var type array key/value
     */
    public $data;

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {        
        if ($this->content === null) {
            $value = $this->getDataCellValue($model, $key, $index);
            return Html::dropDownList($this->name, $value, $this->data, $this->controlOptions).$this->help_block_str;
        } else {
            return parent::renderDataCellContent($model, $key, $index);
        }        
    }
}