<?php

namespace dacduong\inlinegrid;

use yii\data\BaseDataProvider;

class EmptyDataProvider extends BaseDataProvider
{
    
    private $_emptyModel;

    public function __construct($emptyModel)
    {
        $this->_emptyModel = $emptyModel;
    }

    protected function prepareKeys($models): array {
        return array_keys($models);
    }

    protected function prepareModels(): array {
        $models = [];
        $models[] = $this->_emptyModel;
        return $models;
    }

    protected function prepareTotalCount(): int {
        return 1;
    }

}
