<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Create a new Group'));
    $title = Yii::t('default', 'Create a new Group');
    $this->menu = [
        ['label' => Yii::t('default', 'List Group'), 'url' => ['index'], 'description' => Yii::t('default', 'This action list all Groups, you can search, delete and update')],
    ];
    ?>
    <?php
        echo $this->renderPartial('//quiz/default/group/_form', ['group' => $group, 'title' => $title]);
    ?>
</div>
