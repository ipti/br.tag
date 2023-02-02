<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Create a new User'));
    $title = Yii::t('default', 'Create a new User');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on User.');
    $this->menu = [
        ['label' => Yii::t('default', 'List User'),
            'url' => ['index'],
            'description' => Yii::t('default', 'This action list all User, you can search, delete and update')],
    ];
    ?>
    <?php
    echo $this->renderPartial('_form', [
        'model' => $model,
        'title' => $title
    ]);
    ?>
</div>
