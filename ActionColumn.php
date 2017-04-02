<?php

namespace dacduong\inlinegrid;

use kartik\grid\ActionColumn as KartikActionColumn;
use yii\helpers\Html;

class ActionColumn extends KartikActionColumn {

    public $cssClass = 'dd-action';
    public $actionSaveRow = './save-row';
    public $actionReloadRow = './reload-row';
    public $actionDeleteRow = './delete-row';
    public $alwaysEdit = true;
    public $primaryKey = 'id';
    public $template = '{edit} {save} {cancel} {copy} {delete}';

    /**
     * @inheritdoc
     */
    public function init() {
        $this->initMyButtons();
        parent::init();
        ActionColumnAsset::register($this->grid->getView());
    }

    private $_urlFormat = "<a href='javascript://' title='%s' onclick='%s' %s>%s</a>";
    protected function initMyButtons() {
        $this->buttons = [
            'save' => function($url, $model, $key) {
                $class = $this->alwaysEdit ? 'class="btnsave"' : 'class="hidden btnsave"';
                $alwaysEditFlag = $this->alwaysEdit ? 'true' : 'false';
                $icon = '<span class="glyphicon glyphicon-floppy-disk"></span>';
                return sprintf($this->_urlFormat, 'Save', "saveRow(this, \"$this->primaryKey\", \"$this->actionSaveRow\", $alwaysEditFlag);", $class, $icon);
            },
            'cancel' => function($url, $model, $key) {
                $class = $this->alwaysEdit ? 'class="btncancel"' : 'class="hidden btncancel"';
                $alwaysEditFlag = $this->alwaysEdit ? 'true' : 'false';
                $icon = '<span class="glyphicon glyphicon-repeat"></span>';
                return sprintf($this->_urlFormat, 'Reset', "cancelRow(this, \"$this->primaryKey\", \"$this->actionReloadRow\", $alwaysEditFlag);", $class, $icon);                    
            },
            'copy' => function($url, $model, $key) {
                $class = 'class="btncopy"';
                $icon = '<span class="glyphicon glyphicon-duplicate"></span>';
                return sprintf($this->_urlFormat, 'Copy', "copyRow(this, \"$this->primaryKey\");", $class, $icon);                    
            },
            'delete' => function($url, $model, $key) {
                $class = 'class="btndelete"';
                $icon = '<span class="glyphicon glyphicon-trash"></span>';
                return sprintf($this->_urlFormat, 'Delete', "deleteRow(this, \"$this->primaryKey\", \"$this->actionDeleteRow\");", $class, $icon);                    
            },
        ];
        if (!$this->alwaysEdit) {
            $this->buttons = \yii\helpers\ArrayHelper::merge($this->buttons, [
                'edit' => function($url, $model, $key) {
                    $class = 'class="btnedit"';
                    $icon = '<span class="glyphicon glyphicon-edit"></span>';
                    return sprintf($this->_urlFormat, 'Edit', "editRow(this);", $class, $icon);
                },
            ]);
            $view = $this->grid->getView();
            $js = "$(function() {disableInputs($('.kv-grid-table'));});";
            $view->registerJs($js);
        }
    }
    
    /**
     * @inheritdoc
     */
    public function renderDataCell($model, $key, $index)
    {
        $options = $this->fetchContentOptions($model, $key, $index);
        
        //add class identity class
        if (!array_key_exists('class', $options)) {
            $options['class'] = $this->cssClass;
        } else {
            $options['class'] .= " $this->cssClass";
        }
        
        return Html::tag('td', $this->renderDataCellContent($model, $key, $index), $options);
    }

}
