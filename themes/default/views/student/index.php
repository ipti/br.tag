<div id="mainPage" class="main">
    <?php
    $this->setPageTitle('TAG - ' . Yii::t('default', 'Student Identifications'));
    $contextDesc = Yii::t('default', 'Available actions that may be taken on StudentIdentification.');
    $this->menu = array(
        array('label' => Yii::t('default', 'Create a new StudentIdentification'), 'url' => array('create'), 'description' => Yii::t('default', 'This action create a new StudentIdentification')),
    );
    $themeUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();

    ?>

    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo Yii::t('default', 'Student Identifications') ?></h1>
            <div class="t-buttons-container">
                <!--<a href="<?= CHtml::normalizeUrl(array('student/create')) ?>" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Alunos PNE</a>-->
                <a class="t-button-primary"
                   href="<?= CHtml::normalizeUrl(array('student/create')) ?>"><?= Yii::t('default', 'Add') ?></a>
                <div class="mobile-row">
                    <a class="t-button-secondary"
                       href="<?= CHtml::normalizeUrl(array('student/create', 'simple' => 1)) ?>"> <?= Yii::t('default', 'Add (Fast)') ?></a>
                    <a class="t-button-secondary"
                       href="<?= CHtml::normalizeUrl(array('wizard/configuration/student')) ?>">Matrícula em Grupo</a>
                </div>
            </div>
        </div>
    </div>

    <div class="tag-inner">
        <?php if (Yii::app()->user->hasFlash('error')) : ?>
            <div class="alert alert-error">
                <?php echo Yii::app()->user->getFlash('error') ?>
            </div>
            <?php
            if (isset($buttons))
                echo $buttons;
            ?>
            <br/>
        <?php endif ?>
        <?php if (Yii::app()->user->hasFlash('success')) : ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
            <?php
            if (isset($buttons))
                echo $buttons;
            ?>
        <?php endif ?>
        <div class="widget clearmargin">
            <div class="widget-body">
                <div class="grid-view">
                    <table id="student-identification-table" class="display js-tag-table student-table
                        tag-table-primary table table-condensed table-striped table-hover table-primary table-vertical-center checkboxs"
                           style="width:100%" aria-label="students table">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Filiação Principal</th>
                            <th>Data de Nascimento</th>
                            <th>CPF</th>
                            <th>ID INEP</th>
                            <th style="width: 104px;text-align: center;">Ações</th>
                            <?php if (Yii::app()->features->isEnable("FEAT_SEDSP")): ?>
                                <th style="width: 1px;text-align: center;">Sincronizado</th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="columntwo">
    </div>
</div>

<div class="modal fade modal-content" id="syncStudentToSEDSP" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt=""
                     style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title"
                id="myModalLabel">Sincronizar Aluno para o SEDSP</h4>
        </div>
        <form method="post" action="">
            <div class="centered-loading-gif">
                <i class="fa fa-spin fa-spinner"></i>
            </div>
            <div class="modal-body">
                <div class="alert alert-error no-show"></div>
                <div class="row-fluid">
                    Você tem certeza?
                    <input type="hidden" name="sync-student-url" id="sync-student-url" value="">
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">Cancelar
                    </button>
                    <button type="button"
                            class="btn btn-primary sync-student-button">Confirmar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    #student-identification-table tbody tr td:nth-child(5), #student-identification-table tbody tr td:nth-child(6) {
        text-align: center;
    }
</style>

<script>
    $(document).on("click", "#student-delete", function (event) {
        event.preventDefault();
        var confirmation = confirm("Tem certeza que deseja excluir? Essa ação não pode ser desfeita!");
        if (confirmation) {
            window.location.href = event.currentTarget.href;
        }
    });

    $(document).on("click", ".unsync", function(e) {
        e.preventDefault();
        $("#syncStudentToSEDSP").find("form").attr("action", $(this).attr("href"));
        $('#syncStudentToSEDSP').modal("show");
    });

    $(document).on("click", ".sync", function(e) {
        e.preventDefault();
    });

    $(document).on("click", ".sync-student-button", function() {
        $(this).closest("form").submit();
    });
</script>
