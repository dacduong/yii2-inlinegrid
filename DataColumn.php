<?php

namespace dacduong\inlinegrid;

use kartik\grid\DataColumn as KartikDataColumn;
use yii\helpers\Html;

class DataColumn extends KartikDataColumn {

    public $cssClass = 'dd-data';

    /**
     * @inheritdoc
     */
    public function renderDataCell($model, $key, $index)
    {
        $options = $this->fetchContentOptions($model, $key, $index);
        $this->parseGrouping($options, $model, $key, $index);
        $this->parseExcelFormats($options, $model, $key, $index);
        $this->initPjax($this->_clientScript);
        
        //add class identity class
        if (!array_key_exists('class', $options)) {
            $options['class'] = $this->cssClass;
        } else {
            $options['class'] .= " $this->cssClass";
        }
        
        if ($this->attribute) {
            $options['class'] .= " value-$this->attribute";
        }
        
        return Html::tag('td', $this->renderDataCellContent($model, $key, $index), $options);
    }

}
