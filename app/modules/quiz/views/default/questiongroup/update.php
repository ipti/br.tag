<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Update Question Group'));
    $title = Yii::t('default', 'Update Question Group');
    ?>
    <?php
        echo $this->renderPartial('questiongroup/_form', array('questionGroup' => $questionGroup, 'title' => $title));
    ?>
</div>
