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
use yongtiger\ueditor\traits\AttachableTrait;
use yongtiger\ueditor\Module;

/**
 * UEditor Input Widget
 *
 * @package yongtiger\ueditor\widgets
 */
class UEditor extends InputWidget
{
    ///[v0.0.10 (ADD# AttachableTrait)]@see http://stackoverflow.com/questions/12478124/how-to-overload-class-constructor-within-traits-in-php-5-4
    use AttachableTrait {
        AttachableTrait::init as private __initTrait;
    }

    //配置选项，会覆盖ueditor.config.js，参阅Ueditor官网文档(定制菜单等)
    ///@see http://fex.baidu.com/ueditor/#start-toolbar
    public $clientOptions = [];

    ///[UEditor:自定义请求参数]@see http://fex.baidu.com/ueditor/#dev-serverparam
    ///BUG:编辑器内容首行不能有`<pre>`！否则失效
    public $serverparam;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        ///[v0.1.2 (CHG# module widgetOptions, widgetClientOptions)]
        $this->options = ArrayHelper::merge(Module::instance()->widgetOptions, $this->options);
        $this->clientOptions = ArrayHelper::merge(Module::instance()->widgetClientOptions, $this->clientOptions);

        $this->clientOptions = ArrayHelper::merge([
            'serverUrl' => Url::to(['/ueditor/default/upload']),    ///?????是否可选择远程上传服务`http://domain/path/upload.php`
            'initialFrameWidth' => '100%',
            'initialFrameHeight' => '400',
            'lang' => (strtolower(Yii::$app->language) == 'en-us') ? 'en' : 'zh-cn',
        ] , $this->clientOptions);

        $this->registerClientScript();

        $this->__initTrait();   ///[v0.0.10 (ADD# AttachableTrait)]@see http://stackoverflow.com/questions/12478124/how-to-overload-class-constructor-within-traits-in-php-5-4
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            return Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            return Html::textarea($this->name, $this->value, $this->options);
        }
    }

    /**
     * Registers client script
     */
    protected function registerClientScript()
    {
        UEditorAsset::register($this->view);

        $clientOptions = Json::encode($this->clientOptions);

        ///[UEditor:自定义请求参数]@see http://fex.baidu.com/ueditor/#dev-serverparam
        ///BUG:编辑器内容首行不能有`<pre>`！否则失效
        $serverparam = Json::encode($this->serverparam);

        $this->view->beginBlock("client_script") ?>
        ///<script type="text/javascript"> ///欺骗sublime文本编辑器，使下面代码显示JS语法高亮
            ///[v0.1.0 (FIX# display error message in comment)]@see http://www.yiiframework.com/doc-2.0/yii-widgets-activefield.html#$selectors-detail
            var ue=UE.getEditor('<?= $this->options['id'] ?>', <?= $clientOptions ?>);

            ue.ready(function() {
                ue.execCommand('serverparam', <?= $serverparam ?>);
            });
            
        <?php $this->view->endBlock();

        $this->view->registerJs($this->view->blocks["client_script"], View::POS_END);    
    }
}