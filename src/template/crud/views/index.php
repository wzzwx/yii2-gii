<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? 'kartik\\grid\\GridView' : 'yii\\widgets\\ListView' ?>;
$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
<?php if (!empty($generator->searchModelClass)): ?>
<?= '    <?php '?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>

    <p class="text-right">
        <?= '<?= ' ?>Html::a(<?= $generator->generateString('新建') ?>, ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <div class="panel panel-default <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-list">
        <div class="panel-body">
<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= '<?= ' ?>GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
<?php //!empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n";?>
<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "            '" . $name . "',\n";
        } else {
            echo "            // '" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if (++$count < 6) {
            echo "            '" . $column->name . ($format === 'text' ? '' : ':' . $format) . "',\n";
        } else {
            echo "            // '" . $column->name . ($format === 'text' ? '' : ':' . $format) . "',\n";
        }
    }
}
?>
            [
                'class' => 'kartik\grid\ActionColumn', 
                'width' => '20%',
                'template' => '<div class="btn-group">
                  {update}
                  <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu">
                    <li>{delete}</li>
                    <li>{view}</li>
                    <li role="separator" class="divider"></li>
                    <li>{log}</li>
                  </ul>
                </div>',
                'header' => "操作",
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a("查看", ["view", "id" => $model->id]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('修改', $url, [
                            'class' => 'btn btn-warning',
                        ]);
                    },
                    'delete' => function($url, $model, $key) {
                        return Html::a('删除', ['delete', 'id' => $model->id], [
                            'data' => [
                                'confirm' => '你确定要删除吗？',
                                'method' => 'post',
                            ]
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>
<?php else: ?>
    <?= '<?= ' ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>
        </div>
    </div>
</div>
