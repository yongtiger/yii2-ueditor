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
 * @package yongtiger\ueditor\traits
 */
trait AttachableTrait
{
    public $attachableBehaviorName = 'attachable';
    public $attachableBehavior;
    public $isAttachabe;

    /**
     * Inits Trait
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
     * Registers client script
     */
    protected function registerAttachableClientScript()
    {

    }
}
