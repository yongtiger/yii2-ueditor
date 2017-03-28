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

namespace yongtiger\ueditor\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\InputWidget;
use yongtiger\ueditor\UEditorAsset;

/**
 * UEditor Input Widget
 *
 * @package yongtiger\ueditor\widgets
 */
class UEditor extends InputWidget
{
    //配置选项，参阅Ueditor官网文档(定制菜单等)
    public $clientOptions = [];

    ///[yii2-brainblog_v0.9.0_f0.8.0_UEditor_SyntaxHighlighter]
    ///[UEditor:自定义请求参数]（注意：编辑器内容首行不能有pre！否则失效）@see http://fex.baidu.com/ueditor/#dev-serverparam
    public $serverparam;
    
    ///[UEditor_Event_insertimage_insertfile_simpleuploadge]
    ///[v0.0.6 (CHG# detachValues)]
    public $uploadInputNames = [
        'attachValues' => 'attachValues[]',
        'detachValues' =>'detachValues[]',
    ];

    //默认配置
    protected $_options;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if (isset($this->options['id'])) {
            $this->id = $this->options['id'];
        } else {
            $this->id = $this->hasModel() ? Html::getInputId($this->model,
                $this->attribute) : $this->id;
        }
        $this->_options = [
            'serverUrl' => Url::to(['upload']),
            'initialFrameWidth' => '100%',
            'initialFrameHeight' => '400',
            'lang' => (strtolower(Yii::$app->language) == 'en-us') ? 'en' : 'zh-cn',
        ];
        $this->clientOptions = ArrayHelper::merge($this->_options, $this->clientOptions);
        parent::init();
    }

    public function run()
    {
        $this->registerClientScript();
        if ($this->hasModel()) {
            return Html::activeTextarea($this->model, $this->attribute, ['id' => $this->id]);
        } else {
            return Html::textarea($this->id, $this->value, ['id' => $this->id]);
        }
    }

    /**
     * 注册客户端脚本
     */
    protected function registerClientScript()
    {
        UEditorAsset::register($this->view);

        $clientOptions = Json::encode($this->clientOptions);

        ///[yii2-brainblog_v0.9.0_f0.8.0_UEditor_SyntaxHighlighter]
        ///[UEditor:自定义请求参数]（注意：编辑器内容首行不能有pre！否则失效）@see http://fex.baidu.com/ueditor/#dev-serverparam
        $serverparam = Json::encode($this->serverparam);

        $this->view->beginBlock("client_script") ?>
        ///<script type="text/javascript"> ///欺骗sublime文本编辑器，使下面代码显示JS语法高亮
            var ue=UE.getEditor('<?= $this->id ?>', <?= $clientOptions ?>);
            ue.ready(function() {
                ue.execCommand('serverparam', <?= $serverparam ?>);

                ///[UEditor_Event_insertimage_insertfile_simpleuploadge]
                ue.addListener('afterinsertimage',function(type, arguments){
                    if(type == 'afterinsertimage'){
                        ///arguments: 上传图片的对象数组（如果上传多张图片，可遍历该值）
                        for(var i=0;i<arguments.length;i++){
                            var input = document.createElement('input');
                            input.name = '<?= $this->uploadInputNames['attachValues'] ?>';
                            input.type = 'hidden';
                            input.value = '{"url":"'+arguments[i].src+'", "original":"'+arguments[i].alt+'", "title":"'+arguments[i].title+'", "suffix":"'+arguments[i].type+'", "type":"image", "size":'+arguments[i].size+'}';
                            ue.container.appendChild(input);
                        }

                    }
                });
                ue.addListener('afterinsertfile',function(type, arguments){
                    if(type == 'afterinsertfile'){
                        ///arguments: 上传文件的对象数组（如果上传多个文件，可遍历该值）
                        for(var i=0;i<arguments.length;i++){
                            var input = document.createElement('input');
                            input.name = '<?= $this->uploadInputNames['attachValues'] ?>';
                            input.type = 'hidden';
                            input.value = '{"url":"'+arguments[i].url+'", "original":"'+arguments[i].original+'", "title":"'+arguments[i].title+'", "suffix":"'+arguments[i].type+'", "type":"file", "size":'+arguments[i].size+'}'; ///[v0.0.5 (FIX# original)]
                            ue.container.appendChild(input);
                        }

                    }
                });
                ///[v0.0.3 (ADD# UEditor_insertvideo)]
                ue.addListener('afterinsertvideo',function(type, arguments){
                    if(type == 'afterinsertvideo'){
                        ///arguments: 上传视频的对象数组（如果上传多个视频，可遍历该值）
                        for(var i=0;i<arguments.length;i++){
                            var input = document.createElement('input');
                            input.name = '<?= $this->uploadInputNames['attachValues'] ?>';
                            input.type = 'hidden';
                            input.value = '{"url":"'+arguments[i].url+'", "original":"'+arguments[i].original+'", "title":"'+arguments[i].title+'", "suffix":"'+arguments[i].type+'", "type":"video", "size":'+arguments[i].size+'}'; ///[v0.0.5 (FIX# original)]
                            ue.container.appendChild(input);
                        }

                    }
                });
                ue.addListener('aftersimpleupload afterautouploadimage afterwordimage aftercatchremote',function(type, argument){
                    if(type == 'aftersimpleupload' || type == 'afterautouploadimage' || type == 'afterwordimage' || type == 'aftercatchremote'){
                        ///argument: 上传图片的img对象，另外size为上传图片的文件大小
                        var input = document.createElement('input');
                        input.name = '<?= $this->uploadInputNames['attachValues'] ?>';
                        input.type = 'hidden';
                        input.value = '{"url":"'+argument.src+'", "original":"'+argument.alt+'", "title":"'+argument.title+'", "suffix":"'+argument.type+'", "type":"image", "size":'+argument.size+'}';
                        ue.container.appendChild(input);
                    }
                });
                ///[http://www.brainbook.cc]

                ///[v0.0.6 (CHG# detachValues)]
                ///检测body中是否含有附件项，如果没有则删除
                ue.addListener('contentchange',function(){
                    ///[v0.0.7 (FIX# collectionToArray)]
                    var attachValues = collectionToArray(document.getElementsByName('<?= $this->uploadInputNames['attachValues'] ?>'));
                    var detachValues = collectionToArray(document.getElementsByName('<?= $this->uploadInputNames['detachValues'] ?>'));

                    ///遍历attachValues
                    for(var i=0;i<attachValues.length;i++){
                        var uploadvalue = JSON.parse(attachValues[i].value);
                        if(!isExist(uploadvalue)){
                            attachValues[i].name = '<?= $this->uploadInputNames['detachValues'] ?>';
                        }
                    }

                    ///遍历detachValues
                    for(var i=0;i<detachValues.length;i++){
                        var uploadvalue = JSON.parse(detachValues[i].value);
                        if(isExist(uploadvalue)){
                            detachValues[i].name = '<?= $this->uploadInputNames['attachValues'] ?>';
                        }
                    }
                });

                ///[yii2-brainblog_v0.9.1_f0.9.0_post_attachment_AttachableBehavior]
                <?php foreach ($this->model->attachValues as $key => $value) : ?>
                    <?php foreach ($value as $uploadJson) : ?>
                        var input = document.createElement('input');
                        input.name = '<?= $this->uploadInputNames['attachValues'] ?>';
                        input.type = 'hidden';
                        input.value = '<?= $uploadJson ?>';
                        ue.container.appendChild(input);
                    <?php endforeach; ?>
                <?php endforeach; ?>

            });

        <?php $this->view->endBlock();

        $this->view->registerJs($this->view->blocks["client_script"], View::POS_END);
    }
}