<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;
<?php
    //如果存在creationTime, updateTime,则自动更新时间戳,如存在ip，则自动更新ip
    $hasCreationTime = false;
    $hasUpdateTime = false;
    $hasIp = false;
    $hasAttr = false;
    $hasStatus = false;
    $hasUpdateBy = false;
    $hasCreateBy = false;
    foreach ($tableSchema->columns as $column) {
        if ($column->name == 'creationTime') {
            $hasCreationTime = true;
        } elseif ($column->name == 'updateTime') {
            $hasUpdateTime = true;
        } elseif ($column->name == 'ip') {
            $hasIp = true;
        } elseif ($column->name == 'attr') {
            $hasAttr = true;
        } elseif ($column->name == 'status') {
            $hasStatus = true;
        } elseif ($column->name == 'createBy') {
            $hasCreateBy = true;
        } elseif ($column->name == 'updateBy') {
            $hasUpdateBy = true;
        }
    }
?>

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;
<?php
echo $hasIp ? 'use yii\behaviors\AttributeBehavior;'."\n" : '';
echo ($hasCreateBy || $hasUpdateBy) ? 'use wzzwx\yii2common\behaviors\BlameableBehavior;'."\n" : '';
echo ($hasCreationTime || $hasUpdateTime) ? 'use wzzwx\yii2common\behaviors\DateTimeBehavior;'."\n" : '';
echo $hasAttr ? 'use wzzwx\yii2common\behaviors\AttrBehavior;'."\n" : ''; ?>

/**
 * 该model对应数据库表 "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}"?><?php if ($column->comment) {
    echo " {$column->comment}";
} ?><?php echo "\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
<?php
    if ($hasStatus) {
        echo "\n";
        echo "    /** 状态：正常*/\n";
        echo "    CONST STATUS_NEW = 0;\n";
        echo "    /** 状态：删除*/\n";
        echo "    CONST STATUS_DELETED = 255;\n\n";
        echo "    public static \$statusArr = [
        self::STATUS_NEW => '正常',
        self::STATUS_DELETED => '已删除',
    ];\n";
    }
?>

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . "\n        " ?>];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }

<?php if ($hasStatus) {
    ?>
    //获取status的描述
    public function getStatusDesc($default = "未知")
    {
        return ArrayHelper::getValue(self::$statusArr, $this->status, $default);
    }

    //软删除
    public function setDelete($autoSave = true)
    {
        $this->status=self::STATUS_DELETED;
        $autoSave && $this->save(false);
    }
<?php
} ?>

    public function behaviors()
    {
        return [
<?php if ($hasCreationTime || $hasUpdateTime) {
        ?>
            [
                'class' => DateTimeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => [<?php
                        echo $hasCreationTime ? "'creationTime'," : '';
        echo $hasUpdateTime ? "'updateTime'" : ''; ?>],
                    ActiveRecord::EVENT_BEFORE_UPDATE => [<?=$hasUpdateTime ? "'updateTime'" : ''?>],
                ],
            ],
<?php
    }?>
<?php if ($hasIp) {
        ?>
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                   ActiveRecord::EVENT_BEFORE_INSERT => 'ip',
                 ],
                'value' => function($event){
                    if(Yii::$app->request instanceof \yii\console\Request) {
                        return 0;
                    }
                    return ip2long(Yii::$app->request->userIP);
                },
            ],
<?php
    }?>
<?php if ($hasUpdateBy || $hasCreateBy) {
        ?>
            [
                'class' => BlameableBehavior::class,
<?php if (!$hasCreateBy) { ?>
                'logCreate' => false,
<?php } ?>
<?php if (!$hasUpdateBy) { ?>
                'logUpdate' => false,
<?php } ?>
            ],
<?php
    }?>
<?php if ($hasAttr): ?>
            [
                'class' => AttrBehavior::class,
                'attrKey' => 'attr',
                'properties' => [],
            ],
<?php endif; ?>
        ];
    }

<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * @inheritdoc
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php endif; ?>
}
