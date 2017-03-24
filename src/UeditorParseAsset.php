<?php ///[yii2-ueditor]

/**
 * Yii2 ueditor
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-ueditor
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2017 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\ueditor;

use yii\web\AssetBundle;

class UeditorParseAsset extends AssetBundle
{
    public $js = [
        'ueditor.parse.min.js',
    ];
   
    public function init()
    {
        $this->sourcePath = '@vendor/yongtiger/yii2-ueditor/src/assets';
    }
}
///[http://www.brainbook.cc]