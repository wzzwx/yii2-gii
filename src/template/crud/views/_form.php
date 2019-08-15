<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div class=" panel panel-info <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">
    <div class="panel-heading">
        <h3 class="panel-title text-center"><?= '<?=Html::encode($this->title)?>' ?></h3>
    </div>
    <div class="panel-body">
        <?= '<?php ' ?>$form = ActiveForm::begin([
            'layout' => 'horizontal',
        ]); ?>

<?php foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
        echo '        <?= ' . $generator->generateActiveField($attribute) . " ?>\n";
    }
} ?>
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-4">
            <?= '<?= ' ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('创建') ?> : <?= $generator->generateString('修改') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
        <?= '<?php ' ?>ActiveForm::end(); ?>
    </div>
</div>
