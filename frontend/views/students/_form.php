<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Students $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="students-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'gender')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'race_ethnicity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parental_level_of_education')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lunch')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'test_preparation_course')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'math_score')->textInput() ?>

    <?= $form->field($model, 'reading_score')->textInput() ?>

    <?= $form->field($model, 'writing_score')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
