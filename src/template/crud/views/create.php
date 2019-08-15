<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

echo "<?php\n";
?>
use yii\helpers\Html;

$this->title = <?= $generator->generateString('新建 ' . Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create">
    <?= '<?= ' ?>$this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
