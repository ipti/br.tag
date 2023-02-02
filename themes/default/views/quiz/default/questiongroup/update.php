<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Update Question Group'));
    $title = Yii::t('default', 'Update Question Group');
    ?>
    <?php
        echo $this->renderPartial('//quiz/default/questiongroup/_form', ['questionGroup' => $questionGroup, 'title' => $title]);
    ?>
</div>
