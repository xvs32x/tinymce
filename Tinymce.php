<?php

namespace xvs32x\tinymce;

use Yii;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

class Tinymce extends InputWidget
{

    public $pluginOptions = [];
    public $options;

    public function init()
    {
        parent::init();
        if (!$this->pluginOptions) {
            $this->pluginOptions = [
                'setup' => new JsExpression("
                    function(editor){
                        editor.on('change', function () {
                        editor.save();
                    });
                 }"),
                'skin' => "flat",
                'plugins' => [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                    "table contextmenu directionality emoticons paste textcolor code"
                ],
                'toolbar1' => "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect | link unlink anchor | image media | forecolor backcolor",
                'language' => ArrayHelper::getValue(explode('-', Yii::$app->language), '0', Yii::$app->language),
            ];
        }
    }

    public function run()
    {
        Html::addCssStyle($this->options, ['opacity' => 0]);
        if ($this->hasModel()) {

            if (!ArrayHelper::getValue($this->options, 'id')) {
                $this->options['id'] = Html::getInputId($this->model, $this->attribute);
            }

            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {

            if (!ArrayHelper::getValue($this->options, 'id')) {
                $this->options['id'] = Html::getAttributeName($this->name . rand(1, 9999));
            }

            echo Html::textarea($this->name, $this->value, $this->options);
        }
        $this->registerAssets();
    }


    /**
     * @param BootstrapFakeAsset $bootstrapAssetInstance
     * @return string
     * */
    public function setOptions($bootstrapAssetInstance)
    {
        $id = $this->options['id'];
        if(!ArrayHelper::getValue($this->pluginOptions, 'content_css')){
            $this->pluginOptions['content_css'] = $bootstrapAssetInstance->baseUrl . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'bootstrap.min.css';
            $this->pluginOptions['content_style'] = "html {padding: 10px}";
        }
        return Json::encode(ArrayHelper::merge(['selector' => '#' . $id], $this->pluginOptions));
    }

    public function registerAssets()
    {
        $view = $this->getView();
        TinymceAsset::register($view);
        $bootstrapAssetInstance = BootstrapFakeAsset::register($view);
        $options = $this->setOptions($bootstrapAssetInstance);
        $view->registerJs("tinymce.init($options)");
    }

}