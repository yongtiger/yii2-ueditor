# Development roadmap

## v0.1.3 (CHG# remove AttachableTrait)


## v0.1.2 (CHG# module widgetOptions, widgetClientOptions)

* `\Module.php` (33)
* `\widgets\UEditor.php` (53)


## v0.1.1 (CHG# module actionConfig)

* `\Module.php` (32-45)
* `\controllers\DefaultController.php` (455)


## v0.1.0 (reconstruct and FIX# display error message in comment)

* `\widgets\UEditor.php` (91): `var ue=UE.getEditor('<?= $this->options['id'] ?>', <?= $clientOptions ?>);`

* Usage:

```php
'editorCallback' => function($field, $model, $attribute, $name = null, $params = []) {

    $field->selectors = ['input' => 'textarea'];	///@see http://www.yiiframework.com/doc-2.0/yii-widgets-activefield.html#$selectors-detail

    return $field->widget('yongtiger\ueditor\widgets\UEditor', [

        'clientOptions' => [
        // ...
```


## v0.0.999 (attachable using js)


## v0.0.10 (ADD# AttachableTrait)

* `\traits\AttachabeTrait.php`
* `\widgets\UEditor.php` (31)


## v0.0.8 (TYPO# UeditorParseAsset)

* `\UeditorParseAsset.php`


## v0.0.7 (FIX# collectionToArray)

* `\assets\ueditor.all.js` (29685)
* `\widgets\UEditor.php` (156)


## v0.0.6 (CHG# detachValues)

* `\assets\ueditor.all.js` (29650)
* `\widgets\UEditor.php`


## v0.0.5 (Reconstruction and FIX# original)

* `\assets\dialogs\attachment\attachment.js` (559)
* `\assets\ueditor.all.js` (23817, 23838)
* `\widgets\UEditor.php` (119, 133)


## v0.0.4 (FIX# video js data-setup)

* `\assets\ueditor.all.js`


## v0.0.3 (ADD# UEditor_insertvideo)


## v0.0.2 (FIX# UEditor_snapscreen)

* `\assets\ueditor.all.js`


## v0.0.0 (initial commit)

Features of this version:

* Sample of extensions directory structure. `src`, `docs`, etc.
* `README.md`
* `composer.json`
* `development-roadmap.md`
