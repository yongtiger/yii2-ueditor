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
    public $uploadInputNames = [
        'image' => 'insertimage[]',
        'file' => 'insertfile[]',
        'video' => 'insertvideo[]',///[v0.0.3 (ADD# UEditor_insertvideo)]
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
                            input.name = '<?= $this->uploadInputNames['image'] ?>';
                            input.type = 'hidden';
                            input.value = '{"src":"'+arguments[i].src+'", "alt":"'+arguments[i].alt+'", "title":"'+arguments[i].title+'", "type":"'+arguments[i].type+'", "size":'+arguments[i].size+'}';
                            ue.container.appendChild(input);
                        }

                    }
                });
                ue.addListener('afterinsertfile',function(type, arguments){
                    if(type == 'afterinsertfile'){
                        ///arguments: 上传文件的对象数组（如果上传多个文件，可遍历该值）
                        for(var i=0;i<arguments.length;i++){
                            var input = document.createElement('input');
                            input.name = '<?= $this->uploadInputNames['file'] ?>';
                            input.type = 'hidden';
                            input.value = '{"url":"'+arguments[i].url+'", "original":"'+arguments[i].original+'", "title":"'+arguments[i].title+'", "type":"'+arguments[i].type+'", "size":'+arguments[i].size+'}'; ///[v0.0.5 (FIX# original)]
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
                            input.name = '<?= $this->uploadInputNames['video'] ?>';
                            input.type = 'hidden';
                            input.value = '{"url":"'+arguments[i].url+'", "original":"'+arguments[i].original+'", "title":"'+arguments[i].title+'", "type":"'+arguments[i].type+'", "size":'+arguments[i].size+'}'; ///[v0.0.5 (FIX# original)]
                            ue.container.appendChild(input);
                        }

                    }
                });
                ue.addListener('aftersimpleupload afterautouploadimage afterwordimage aftercatchremote',function(type, argument){
                    if(type == 'aftersimpleupload' || type == 'afterautouploadimage' || type == 'afterwordimage' || type == 'aftercatchremote'){
                        ///argument: 上传图片的img对象，另外size为上传图片的文件大小
                        var input = document.createElement('input');
                        input.name = '<?= $this->uploadInputNames['image'] ?>';
                        input.type = 'hidden';
                        input.value = '{"src":"'+argument.src+'", "alt":"'+argument.alt+'", "title":"'+argument.title+'", "type":"'+argument.type+'", "size":'+argument.size+'}';
                        ue.container.appendChild(input);
                    }
                });
                ///[http://www.brainbook.cc]

                ///检测body中是否含有附件项，如果没有则删除
                ue.addListener('contentchange',function(){
                    var uploadimages = document.getElementsByName('<?= $this->uploadInputNames['image'] ?>');
                    if(uploadimages.length>0){
                        var imgs = ue.document.getElementsByTagName('img');
                        ///遍历uploadimages
                        for(var i=0;i<uploadimages.length;i++){
                            var is_exist = false;
                            ///遍历ue.document中所有img标签，如果所有img标签的src都不包含uploadimages[i].value的src，则删除该uploadimages[i]
                            for(var j=0;j<imgs.length;j++){
                                ///[decodeURI is needed] because:
                                ///imgs[j].src = `http://localhost/%5Bgit%5D/1_article/frontend/web/index.php?r=article%2Fpost%2Fupdate&id=67'
                                ///JSON.parse(uploadimages[i].value).src = `/[git]/1_article/frontend/web/upload/scrawl/20170325/1490402665412466.png`
                                $src1 = decodeURI(imgs[j].src); 
                                $src2 = decodeURI(JSON.parse(uploadimages[i].value).src);   
                                if($src1.indexOf($src2)>=0){
                                    is_exist = true;
                                    break;
                                }
                            }
                            if(!is_exist){
                                ue.container.removeChild(uploadimages[i]);
                            }
                        }
                    }
                    var uploadfiles = document.getElementsByName('<?= $this->uploadInputNames['file'] ?>');
                    if(uploadfiles.length>0){
                        var files = ue.document.getElementsByTagName('a');
                        ///遍历uploadfiles
                        for(var i=0;i<uploadfiles.length;i++){
                            var is_exist = false;
                            ///遍历ue.document中所有a标签，如果所有a标签的href都不包含uploadfiles[i].value的url，则删除该uploadfiles[i]
                            for(var j=0;j<files.length;j++){
                                ///[decodeURI is needed]
                                $url1 = decodeURI(files[j].href);
                                $url2 = decodeURI(JSON.parse(uploadfiles[i].value).url);
                                if($url1.indexOf($url2)>=0){
                                    is_exist = true;
                                    break;
                                }
                            }
                            if(!is_exist){
                                ue.container.removeChild(uploadfiles[i]);
                            }
                        }
                    }
                    ///[v0.0.3 (ADD# UEditor_insertvideo)]
                    var uploadvideos = document.getElementsByName('<?= $this->uploadInputNames['video'] ?>');
                    if(uploadvideos.length>0){
                        var videos = ue.document.getElementsByTagName('img');
                        ///遍历uploadvideos
                        for(var i=0;i<uploadvideos.length;i++){
                            var is_exist = false;
                            ///遍历ue.document中所有img标签，如果所有img标签的_url都不包含uploadvideos[i].value的url，则删除该uploadvideos[i]
                            for(var j=0;j<videos.length;j++){
                                ///[decodeURI is needed]
                                $url1 = decodeURI(videos[j].getAttribute('_url'));
                                $url2 = decodeURI(JSON.parse(uploadvideos[i].value).url);
                                if($url1.indexOf($url2)>=0){
                                    is_exist = true;
                                    break;
                                }
                            }
                            if(!is_exist){
                                ue.container.removeChild(uploadvideos[i]);
                            }
                        }
                    }
                });
                ///[http://www.brainbook.cc]

                ///[yii2-brainblog_v0.9.1_f0.9.0_post_attachment_AttachableBehavior]
                <?php foreach ($this->model->attachValues as $key => $value) : ?>
                    <?php foreach ($value as $uploadJson) : ?>
                        var input = document.createElement('input');
                        input.name = 'Content[attachValues][<?= $key ?>][]';
                        input.type = 'hidden';
                        input.value = '<?= $uploadJson ?>';
                        ue.container.appendChild(input);
                    <?php endforeach; ?>
                <?php endforeach; ?>
                ///[http://www.brainbook.cc]

            });

        <?php $this->view->endBlock();

        $this->view->registerJs($this->view->blocks["client_script"], View::POS_END);
    }
}