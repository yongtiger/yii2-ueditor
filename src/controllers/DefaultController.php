<?php

namespace yongtiger\ueditor\controllers;

use Yii;
use yii\web\Controller;
use yongtiger\ueditor\Module;

/**
 * Class DefaultController
 *
 * @package yongtiger\ueditor\controllers
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'yongtiger\ueditor\actions\UEditorAction',
                'config' => Module::instance()->actionConfig, ///[v0.1.1 (CHG# module actionConfig)]
            ]
        ];
    }
}
