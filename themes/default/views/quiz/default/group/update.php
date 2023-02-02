<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Update Group'));
    $title = Yii::t('default', 'Update Group');
    ?>
    <?php
        echo $this->renderPartial('//quiz/default/group/_form', ['group' => $group, 'title' => $title]);
    ?>
</div>
