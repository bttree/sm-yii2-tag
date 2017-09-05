<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model bttree\smytag\models\Tag */

$this->title                   = Yii::t('smy.tag',
                                        'Update {modelClass}: ',
                                        [
                                            'modelClass' => 'Tag',
                                        ]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('smy.tag', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id];
$this->params['breadcrumbs'][] = Yii::t('smy.tag', 'Update');
?>
<div class="tag-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form',
                      [
                          'model' => $model,
                      ]) ?>

</div>
