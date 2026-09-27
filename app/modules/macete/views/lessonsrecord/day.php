<?php
/* @var $this LessonsrecordController */
/* @var $classroom Classroom */
/* @var $date string */
/* @var $records MaceteLessonRecord[] */

$dateParts = explode('-', $date);
$formattedDate = $dateParts[2] . '/' . $dateParts[1] . '/' . $dateParts[0];
$this->setPageTitle('TAG - Aulas registradas - ' . $formattedDate);

$createUrl = MaceteRoutes::url(MaceteRoutes::LESSONSRECORD_CREATE, [
    'classroomId' => $classroom->id,
    'date' => $date,
]);
?>

<div id="mainPage" class="main">
    <div class="row-fluid">
        <div class="span12">
            <h1>Aulas registradas</h1>
            <div class="t-buttons-container">
                <a class="t-button-secondary" href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONSRECORD_INDEX); ?>">Voltar</a>
                <a class="t-button-primary" href="<?php echo $createUrl; ?>">+ Registrar nova aula</a>
            </div>
        </div>
    </div>

    <p>
        <b>Turma:</b> <?php echo CHtml::encode($classroom->name); ?>
        &nbsp;&mdash;&nbsp;
        <b>Data:</b> <?php echo $formattedDate; ?>
    </p>

    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success"><?php echo Yii::app()->user->getFlash('success'); ?></div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-error"><?php echo Yii::app()->user->getFlash('error'); ?></div>
    <?php endif; ?>

    <div class="tag-inner">
        <div class="widget clearmargin">
            <div class="widget-body">
                <?php if (empty($records)): ?>
                    <p>Nenhuma aula registrada neste dia ainda.</p>
                <?php else: ?>
                    <table class="js-tag-table tag-table-primary tag-table table table-condensed table-striped table-hover table-primary table-vertical-center">
                        <thead>
                            <tr>
                                <th>Plano</th>
                                <th>Componente</th>
                                <th>Conteúdo executado</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($records as $record): ?>
                                <tr>
                                    <td>
                                        <a class="link-update-grid-view" href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONSRECORD_UPDATE, ['id' => $record->id]); ?>">
                                            <?php echo $record->lessonPlanFk !== null ? CHtml::encode($record->lessonPlanFk->name) : '—'; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $record->disciplineFk !== null ? CHtml::encode($record->disciplineFk->name) : '—'; ?></td>
                                    <td><?php echo CHtml::encode(mb_substr(strip_tags((string) $record->executed_content), 0, 150)); ?></td>
                                    <td style="text-align: center; width: 90px;">
                                        <a href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONSRECORD_UPDATE, ['id' => $record->id]); ?>" title="Editar" style="margin-right: 12px;">
                                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/editar.svg" alt="Editar">
                                        </a>
                                        <a href="#" class="js-macete-delete-record" title="Excluir"
                                            data-url="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONSRECORD_DELETE, ['id' => $record->id]); ?>">
                                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/deletar.svg" alt="Excluir">
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    (function ($) {
        $(document).on('click', '.js-macete-delete-record', function (event) {
            event.preventDefault();
            var link = $(this);

            if (!window.confirm('Excluir este registro de aula? Essa ação não pode ser desfeita.')) {
                return;
            }

            $.post(link.data('url')).done(function () {
                window.location.reload();
            }).fail(function () {
                window.alert('Não foi possível excluir o registro.');
            });
        });
    })(jQuery);
</script>
