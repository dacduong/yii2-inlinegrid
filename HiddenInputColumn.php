<?php

namespace dacduong\inlinegrid;

use kartik\grid\DataColumn;
use yii\helpers\Html;

class HiddenInputColumn extends DataColumn
{
    
    public $name = 'ithiddeninput';
    
    public $controlOptions = [];
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!array_key_exists('class', $this->controlOptions)) {
            $this->controlOptions['class'] = 'value-'.$this->attribute;
        } else {
            $this->controlOptions['class'] .= ' value-'.$this->attribute;
        }
    }
    
    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if ($this->attribute !== null) {
            $this->name = Html::getInputName($model, "[{$index}]{$this->attribute}");
        }
        if ($this->content === null) {
            $value = $this->getDataCellValue($model, $key, $index);
            return Html::hiddenInput($this->name, $value, $this->controlOptions);
        } else {
            return parent::renderDataCellContent($model, $key, $index);
        }        
    }
}

