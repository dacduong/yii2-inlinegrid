<?php

namespace dacduong\inlinegrid;

use kartik\grid\GridView;
use yii\helpers\Html;

class TextareaColumn extends TextInputColumn
{
    
    public $name = 'ddtextarea';
    
    public $cssClass = 'dd-row-textarea';
    
    public $pageSummaryFunc = GridView::F_COUNT;

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {        
        if ($this->content === null) {
            $value = $this->getDataCellValue($model, $key, $index);
            return Html::textarea($this->name, $value, $this->controlOptions).$this->help_block_str;
        } else {
            return parent::renderDataCellContent($model, $key, $index);
        }        
    }
}