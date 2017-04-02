<?php

namespace dacduong\inlinegrid;

use kartik\grid\SerialColumn as KartikSerialColumn;
use yii\helpers\Html;

class SerialColumn extends KartikSerialColumn {
    
    public $cssClass = 'dd-serial';
    
    /**
     * @inheritdoc
     */
    public function renderDataCell($model, $key, $index)
    {
        $options = $this->fetchContentOptions($model, $key, $index);
        $this->parseExcelFormats($options, $model, $key, $index);
        $out = $this->grid->formatter->format($this->renderDataCellContent($model, $key, $index), $this->format);
        
        //add class identity class
        if (!array_key_exists('class', $options)) {
            $options['class'] = $this->cssClass;
        } else {
            $options['class'] .= " $this->cssClass";
        }
        
        return Html::tag('td', $out, $options);
    }
}
