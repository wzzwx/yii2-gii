## 配置
    common/config/main-local.php 
    
    <?php
    return [
        // ...
        // 增加如下内容
        'bootstrap' => ['gii'],
        'modules' => [
            'gii' => [
                'class' => 'yii\gii\Module',
                'allowedIPs' => ['127.0.0.1', '::1', '192.168.*.*'],
                'generators' => \wzzwx\yii2gii\helpers\ConfigHelper::getConfig(),
            ],
        ],
        // ...
        
        
        'components' => [
            'db' => [
                'class' => 'yii\db\Connection',
        // ...

## 建表sql
    CREATE TABLE `test` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(45) NOT NULL DEFAULT '' COMMENT '姓名',
      `age` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '年龄',
      `attr` text DEFAULT NULL,
      `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '状态',
      `createBy` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '创建人',
      `updateBy` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '更新人',
      `creationTime` datetime NOT NULL DEFAULT current_timestamp() COMMENT '创建时间',
      `updateTime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '更新时间',
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='测试'

## 使用
### 生成model
    php yii gii/model --ns=common\\models --tableName=test --modelClass=Test
    
### 生成ajax crud
    php yii gii/ajaxcrud --modelClass=common\\models\\Test --controllerClass=frontend\\controllers\\TestController --viewPath=@frontend/views/test