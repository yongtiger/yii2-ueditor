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

use Yii;

/**
 * Class Module
 *
 * @package yongtiger\ueditor
 */
class Module extends \yii\base\Module
{
    /**
     * @var string module name
     */
    public static $moduleName = 'ueditor';

    /**
     * @var array
     */
    public $actionConfig = [];  ///[v0.1.1 (CHG# module actionConfig)]

    ///[Yii2-ueditor v0.1.2 (CHG# module widgetOptions, widgetClientOptions)]
    public $widgetOptions = [];
    public $widgetClientOptions = [
        //编辑区域大小
        'initialFrameHeight' => '320',
        //定制按钮
        'toolbars' => [
            [
                'fullscreen', //全屏
                'source', //源代码
                '|',
                'undo', //撤销
                'redo', //重做
                '|',
                'bold', //加粗
                'italic', //斜体
                'underline', //下划线
                'fontborder', //字符边框
                'strikethrough', //删除线
                '|',
                'forecolor', //字体颜色
                'backcolor', //背景色
                'fontfamily', //字体
                'fontsize', //字号
                '|',
                'paragraph', //段落格式
                'rowspacingtop', //段前距
                'rowspacingbottom', //段后距
                'lineheight', //行间距
                '|',
                'insertorderedlist', //有序列表
                'insertunorderedlist', //无序列表
                'indent', //首行缩进
                'justifyleft', //居左对齐
                'justifyright', //居右对齐
                'justifycenter', //居中对齐
                'justifyjustify', //两端对齐
                '|',
                'removeformat', //清除格式
                'formatmatch', //格式刷
                'pasteplain', //纯文本粘贴模式
                'autotypeset', //自动排版
            ],
            [
                'searchreplace', //查询替换
                'selectall', //全选
                'cleardoc', //清空文档
                '|',
                'wordimage', //图片转存
                'snapscreen', //截图
                'scrawl', //涂鸦
                'charts', // 图表
                'simpleupload', //单图上传
                'insertimage', //多图上传
                'imagenone', //默认
                'imagecenter', //居中
                'imageleft', //左浮动
                'imageright', //右浮动
                '|',
                'attachment', //附件

                'insertvideo', //视频
                'insertframe', //插入Iframe
                'insertcode', //代码语言
                '|',
                'template', //模板
                '|',
                'background', //背景
                'blockquote', //引用
                '|',
                'spechars', //特殊字符
                'emotion', //表情
                'time', //时间
                'date', //日期
                'anchor', //锚点
                'link', //超链接
                'unlink', //取消链接
                'horizontal', //分隔线
                '|',
                'preview', //预览
                'help', //帮助
            ],
        ],
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        ///[v0.1.1 (CHG# module actionConfig)]
        ///Enable use `@webroot` e.g. `'imageRoot' => '@webroot'`
        if (!empty($this->actionConfig['imageRoot'])) {
            $this->actionConfig['imageRoot'] = Yii::getAlias($this->actionConfig['imageRoot']);
        }
    }

    /**
     * @return static
     */
    public static function instance()
    {
        return Yii::$app->getModule(static::$moduleName);
    }

    /**
     * Registers the translation files.
     */
    public static function registerTranslations()
    {
        ///[i18n]
        ///if no setup the component i18n, use setup in this module.
        if (!isset(Yii::$app->i18n->translations['extensions/yongtiger/yii2-ueditor/*']) && !isset(Yii::$app->i18n->translations['extensions/yongtiger/yii2-ueditor'])) {
            Yii::$app->i18n->translations['extensions/yongtiger/yii2-ueditor/*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@vendor/yongtiger/yii2-ueditor/src/messages',
                'fileMap' => [
                    'extensions/yongtiger/yii2-ueditor/message' => 'message.php',  ///category in Module::t() is message
                ],
            ];
        }
    }

    /**
     * Translates a message. This is just a wrapper of Yii::t().
     *
     * @see http://www.yiiframework.com/doc-2.0/yii-baseyii.html#t()-detail
     *
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        static::registerTranslations();
        return Yii::t('extensions/yongtiger/yii2-ueditor/' . $category, $message, $params, $language);
    }
}
