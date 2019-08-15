<?php
/**
 * This is the template for generating a controller class file.
 */
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\controller\Generator */

echo "<?php\n";
?>

namespace <?= $generator->getControllerNamespace() ?>;
use \Yii;
use yii\filters\AccessControl;
use \wzzwx\yii2common\helpers\SysMsg;

class <?= StringHelper::basename($generator->controllerClass) ?> extends <?= '\\' . trim($generator->baseClass, '\\') . "\n" ?>
{
    <?php
        $actionStr = '';
        foreach ($generator->getActionIDs() as $action) {
            $actionStr .= '"'.$action.'",';
        }
        $actionStr = trim($actionStr, ',');
    ?>
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [<?=$actionStr?>],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }


<?php foreach ($generator->getActionIDs() as $action): ?>
    public function action<?= Inflector::id2camel($action) ?>()
    {
        return $this->render('<?= $action ?>');
    }

<?php endforeach; ?>
}
