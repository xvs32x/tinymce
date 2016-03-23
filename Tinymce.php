<?php

namespace xvs32x\tinymce;

use Yii;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class Tinymce extends InputWidget
{

    public $pluginOptions = [];

    public function init()
    {
        parent::init();
        if (!$this->pluginOptions) {
            $this->pluginOptions = [
                'plugins' => [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                    "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
                ],
                'toolbar1' => "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                'toolbar2' => "link unlink anchor | image media | forecolor backcolor ",
                'language' => ArrayHelper::getValue(explode('-', Yii::$app->language), '0', Yii::$app->language),
            ];
        }
    }

    public function run()
    {

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
     * @param TinymceAsset $instance
     * @return string
     * */
    public function setOptions()
    {
        $id = $this->options['id'];
        return Json::encode(ArrayHelper::merge(['selector' => '#' . $id], $this->pluginOptions));
    }

    public function registerAssets()
    {
        $view = $this->getView();
        TinymceAsset::register($view);
        $options = $this->setOptions();
        $view->registerJs("tinymce.init($options)");
    }

}