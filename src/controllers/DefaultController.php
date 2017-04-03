<?php

namespace yongtiger\ueditor\controllers;

use Yii;
use yii\web\Controller;

/**
 * Class DefaultController
 *
 * @package yongtiger\comment\controllers
 */
class DefaultController extends Controller
{
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'yongtiger\ueditor\actions\UEditorAction',
                'config' => [
                    //"imageUrlPrefix"  => "http://www.baidu.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径
                    "imageRoot" => Yii::getAlias("@webroot"),
                ],
            ]
        ];
    }
}
