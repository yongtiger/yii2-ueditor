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
    public $widgetOptions = [];   ///[v0.1.2 (CHG# module widgetOptions, widgetClientOptions)]
    public $widgetClientOptions = []; ///[v0.1.2 (CHG# module widgetOptions, widgetClientOptions)]

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
