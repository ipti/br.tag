<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default','Create a new User'));
    $this->breadcrumbs=array(
            Yii::t('default', 'Administration')=>array('index'),
            Yii::t('default', 'Users')=>array('index'), //todo s2 - Trocar index pela página de lista de usuários quando ela tiver feita
            Yii::t('default', 'Create'),
    );
    $title=Yii::t('default', 'Create a new User');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on User.');
    $this->menu=array(
        array('label'=> Yii::t('default', 'List User'), 'url'=>array('index'),'description' => Yii::t('default', 'This action list all User, you can search, delete and update')),
    );
    ?>
    <?php echo $this->renderPartial('_form', array('model'=>$model, 
        'title'=>$title)); ?>

    <?php //echo $this->renderPartial('////common/defaultcontext', array('contextDesc'=>$contextDesc)); ?> 

</div>
