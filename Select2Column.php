<?php

namespace dacduong\inlinegrid;

use kartik\grid\GridView;
use kartik\select2\Select2;
use Yii;
use yii\helpers\Html;

class Select2Column extends TextInputColumn
{
    
    public $name = 'itselect2';
    
    public $cssClass = 'it-row-select2';
    
    public $pageSummaryFunc = GridView::F_COUNT;
    
    public $modelFnc = 'getAvailableObject';
    
    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {        
        if ($this->content === null) {
            $value = $this->getDataCellValue($model, $key, $index);
            
            $this->controlOptions['data'] = call_user_func(array($model, $this->modelFnc));
            $this->controlOptions['model'] = $model;
            $this->controlOptions['attribute'] = $this->attribute;
            $this->controlOptions['options']['name'] = $this->name;
            $this->controlOptions['options']['class'] = 'value-'.$this->attribute;
            unset($this->controlOptions['class']);//class in OOP not css
            $this->controlOptions['options']['id'] = Yii::$app->security->generateRandomString(10);
            return Select2::widget($this->controlOptions).$this->help_block_str;      
        } else {
            return parent::renderDataCellContent($model, $key, $index);
        }        
    }
}