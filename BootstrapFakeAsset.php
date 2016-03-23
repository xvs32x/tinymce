<?php

/**
 * Фейковый бандл для подгрузки бутстрап стилей в TinyMCE
 * */
namespace xvs32x\tinymce;

use yii\web\AssetBundle;

class BootstrapFakeAsset extends AssetBundle {

    public $sourcePath = '@bower/bootstrap/dist';

}