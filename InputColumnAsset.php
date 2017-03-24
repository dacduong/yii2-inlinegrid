<?php

namespace dacduong\inlinegrid;

use kartik\base\AssetBundle;

class InputColumnAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/it-grid-input']);
        parent::init();
    }
}
