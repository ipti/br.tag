<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Inconsistency Sagres'));
    $this->menu = array(
        array(
            'label' => Yii::t('default', 'Inconsistency Sagres'),
            'description' => Yii::t('default', 'This action visualizes inconsistencies in Sagres export')
        ),
    );
    $themeUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    
    ?>

    <div class="widget clearmargin">
        <div class="widget-body">
            <div class="grid-view">
                <table id="student-identification-table"
                    class="display js-tag-table student-table 
                    tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs" style="width:100%"
                    aria-label="students table">
                    <thead>
                        <tr>
                            <th>Cadastro</th>
                            <th>ESCOLA</th>
                            <th>DESCRIÇÃO</th>
                            <th>AÇÃO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $models = ValidationSagresModel::model()->findAll();
                        foreach ($models as $model) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $model->enrollment ?>
                                </td>
                                <td>
                                    <?php echo $model->school ?>
                                </td>
                                <td>
                                    <?php echo $model->description ?>
                                </td>
                                <td>
                                    <?php echo $model->action ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="columntwo">
</div>
</div>

</div>