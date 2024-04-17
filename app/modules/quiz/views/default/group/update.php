<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Update Group'));
    $title = Yii::t('default', 'Update Group');
    ?>
    <?php
        echo $this->renderPartial('group/_form', array('group' => $group, 'title' => $title));
    ?>
</div>
