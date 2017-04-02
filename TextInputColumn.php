<?php

namespace dacduong\inlinegrid;

use kartik\grid\GridView;
use yii\helpers\Html;

class TextInputColumn extends DataColumn
{
    
    const CONTROL_DEFAULT_VALUE = 'defaultValue';
    
    const HELP_BLOCK = '<div class="help-block error-%s"></div>';
    
    protected $help_block_str;

    public $name = 'ddtextinput';
    
    public $defaultValue = '';

    public $cssClass = 'dd-row-textinput';
    
    public $rowHighlight = true;
    
    public $rowSelectedClass = GridView::TYPE_WARNING;

    protected $_clientVars = '';
    
    public $controlOptions = [];

    public $refreshGrid = false;

    /**
     * @inheritdoc
     */
    public function init()
    {      
        parent::init();
        InputColumnAsset::register($this->_view);
        $this->help_block_str = sprintf(self::HELP_BLOCK, $this->attribute);
        
        //mark model field as: value-$attribute
        if (!array_key_exists('class', $this->controlOptions)) {
            $this->controlOptions['class'] = 'value-'.$this->attribute;
        } else {
            $this->controlOptions['class'] .= ' value-'.$this->attribute;
        }
        //mark table footer pageSummaryFunc
        if ($this->pageSummary) {
            $this->pageSummaryOptions['data-func'] = $this->pageSummaryFunc;
            $this->pageSummaryOptions['data-field'] = 'summary-'.$this->attribute;
            $this->pageSummaryOptions['data-format'] = $this->format;
        }
    }

    /**
     * @inheritdoc
     */
    public function renderDataCell($model, $key, $index)
    {
        $options = $this->fetchContentOptions($model, $key, $index);
        $this->parseGrouping($options, $model, $key, $index);
        $this->parseExcelFormats($options, $model, $key, $index);
        
        //hightlight + name
        if ($this->rowHighlight) {
            Html::addCssClass($options, $this->cssClass);
        }
        if ($this->attribute !== null) {
            $this->name = Html::getInputName($model, "[{$index}]{$this->attribute}");
        }
        
        //highlight row changed
        $css = $this->rowHighlight ? $this->rowSelectedClass : '';
        $grid = $this->grid->options['id'];
        $this->_clientVars = "'{$grid}', '{$this->name}', '{$this->cssClass}', '{$css}'";
        $this->_clientScript = "itSelectInput({$this->_clientVars});";
        $this->_view->registerJs($this->_clientScript);        
        $this->initPjax($this->_clientScript);
        return Html::tag('td', $this->renderDataCellContent($model, $key, $index), $options);
    }
    
    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {        
        if ($this->content === null) {
            $value = $this->getDataCellValue($model, $key, $index);
            $formatedValue = '';
            if ($value) {
                $formatedValue = $this->grid->formatter->format($this->getDataCellValue($model, $key, $index), $this->format);
            } else {
                if (in_array(self::CONTROL_DEFAULT_VALUE, $this->controlOptions)) {
                    $formatedValue = $this->controlOptions[self::CONTROL_DEFAULT_VALUE];
                }
            }            
            return Html::textInput($this->name, $formatedValue, $this->controlOptions).$this->help_block_str;
        } else {
            return parent::renderDataCellContent($model, $key, $index);
        }    
    }
}

