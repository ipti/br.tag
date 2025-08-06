<?php
/** @var $this FoodInventoryController */
/** @var $dataProvider CActiveDataProvider */

$this->setPageTitle('TAG - ' . Yii::t('default', 'Estoque'));

$this->breadcrumbs = [
    'Food Inventories',
];

$this->menu = [
    ['label' => 'Create FoodInventory', 'url' => ['create']],
    ['label' => 'Manage FoodInventory', 'url' => ['admin']],
];
?>

<h1>Food Inventories</h1>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
]); ?>
