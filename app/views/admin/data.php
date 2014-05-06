<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Data Statistics'));
    $this->breadcrumbs = array(
        Yii::t('default', 'Administration') => array('index'),
        Yii::t('default', 'Data'),
    );
    $title = Yii::t('default', 'Data Statistics');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on User.');
    $this->menu = array(
        array('label' => Yii::t('default', 'List User'), 'url' => array('index'), 'description' => Yii::t('default', 'This action list all User, you can search, delete and update')),
    );
    ?>
    ?>
    <div class="row-fluid">
        <div class="span12">
            <h3 class="heading-mosaic"><?php echo $title; ?></h3>  
            <div class="buttons">
                <div class="buttons">
                </div>
            </div>
        </div>
    </div>
    <div class="innerLR home">
        <div class="row-fluid">
            <div class="span12">
                <div class="widget" id="widget-frequency" style="margin-top: 8px;">
                    <div class="widget-head">
                        <h4 class="heading"><span><?php echo $title?></span></h4>
                    </div>
                    <table id="frequency" class="table table-bordered table-striped">
                        <thead>
                        <th class="center"><?php echo Yii::t('Default', 'Classroom') ?></th>
                        <th class="center"><?php echo Yii::t('Default', 'Instructor') ?></th>
                        <th class="center"><?php echo Yii::t('Default', 'Student') ?></th>
                        <th class="center"><?php echo Yii::t('Default', 'Enrollment') ?></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="center"><?php echo $data['classroom']; ?></td>
                                <td class="center"><?php echo $data['instructors']; ?></td>
                                <td class="center"><?php echo $data['students']; ?></td>
                                <td class="center"><?php echo $data['enrollments']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



</div>
