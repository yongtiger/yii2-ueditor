<?php ///[v0.0.10 (ADD# AttachableTrait)]
/**
 * AttachableTrait for ueditor
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-ueditor
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2017 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\ueditor\traits;

use Yii;
use yii\helpers\Json;
use yii\web\View;

/**
 * Trait ClientTrait
 *
 * Used of client classes which implement the interface IAuth.
 *
 * @package yongtiger\ueditor\traits
 */
trait AttachableTrait
{
    /**
     * Extra user info.
     */
    public $attachableBehaviorName = 'attachable';
    public $attachableBehavior;
    public $isAttachabe;
    
    ///[UEditor_Event_insertimage_insertfile_simpleuploadge]
    ///[v0.0.6 (CHG# detachValues)]
    public $uploadInputNames = [
        'attachValues' => 'attachValues[]',
        'detachValues' => 'detachValues[]',
    ];

    /**
     * Init
     */
    public function init()
    {
        $this->attachableBehavior = $this->model->getBehavior($this->attachableBehaviorName);
        $this->isAttachabe = $this->attachableBehavior ? true : false;

        if ($this->isAttachabe) {
            $this->registerAttachableClientScript();
        }
    }

    /**
     * Register client script
     */
    protected function registerAttachableClientScript()
    {
        $this->view->beginBlock("attachable_client_script") ?>
        ///<script type="text/javascript"> ///欺骗sublime文本编辑器，使下面代码显示JS语法高亮
            ue.ready(function() {

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

        $this->view->registerJs($this->view->blocks["attachable_client_script"], View::POS_END);
    }
}
