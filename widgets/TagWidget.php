<?php

namespace bttree\smytag\widgets;

use bttree\smytag\behaviors\TagBehavior;
use kartik\select2\Select2;
use kartik\select2\Select2Asset;
use yii\base\Model;
use yii\base\Widget;

/**
 * Class TagWidget
 * @package \bttree\smytag\widgets
 *
 */
class TagWidget extends Widget
{

    /**
     * @var Model the data model that this widget is associated with.
     */
    public $model;

    /**
     * @var string the model attribute that this widget is associated with.
     */
    public $attribute;

    /**
     * @var string the input name. This must be set if [[model]] and [[attribute]] are not set.
     */
    public $name;

    /**
     * @var string the input value.
     */
    public $value;

    /**
     * @var array the HTML attributes for the input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    /**
     * @var string
     */
    public $behaviorName = 'smytag';

    /**
     * @inheritdoc
     */
    public function init()
    {
        Select2Asset::register($this->getView());

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->hasModel()) {
            return false;
        }
        $behavior = $this->model->getBehavior($this->behaviorName);
        if (null === $behavior || !$behavior instanceof TagBehavior) {
            return false;
        }
        $attributeName = $behavior->attribute;
        $modelName     = $this->model->formName();

        return Select2::widget([
                                   'name'          => "{$modelName}[{$attributeName}]",
                                   'value'         => array_keys($this->model->getTagsArray()),
                                   'data'          => $this->model->getTagsArray(),
                                   'options'       => ['multiple' => true, 'placeholder' => 'Select tags ...'],
                                   'pluginOptions' => [
                                       'allowClear'      => true,
                                       'tags'            => true,
                                       'tokenSeparators' => [' '],
                                   ],
                               ]);
    }

    /**
     * @return boolean whether this widget is associated with a data model.
     */
    protected function hasModel()
    {
        return $this->model instanceof Model && $this->attribute !== null;
    }
}