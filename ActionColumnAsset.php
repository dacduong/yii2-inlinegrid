<?php

namespace dacduong\inlinegrid;

use kartik\base\AssetBundle;

class ActionColumnAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', ['js/it-grid-action']);
        parent::init();
    }
}
