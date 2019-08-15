<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model-><?= $generator->getNameAttribute() ?>;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">

    <h1><?= '<?= ' ?>Html::encode($this->title) ?></h1>
    <p>
        <?= '<?= ' ?>Html::a(<?= $generator->generateString('首页') ?>, ['index', <?= $urlParams ?>], ['class' => 'btn btn-success']) ?>
        <?= '<?= ' ?>Html::a(<?= $generator->generateString('修改') ?>, ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary']) ?>
        <?= '<?= ' ?>Html::a(<?= $generator->generateString('删除') ?>, ['delete', <?= $urlParams ?>], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => <?= $generator->generateString('你确认要删除？') ?>,
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= '<?= ' ?>DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "            '" . $name . "',\n";
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        echo "            '" . $column->name . ($format === 'text' ? '' : ':' . $format) . "',\n";
    }
}
?>
        ],
    ]) ?>

</div>
