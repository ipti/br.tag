<?php
/* @var $this FoodmenuController */
/* @var $students array */

$this->setPageTitle('TAG - ' . Yii::t('default', 'Alergias Alimentares'));
?>

<div id="mainPage" class="main">
    <div class="row-fluid">
        <div class="span12">
            <h1>Alunos com Alergia Alimentar</h1>
            <div class="t-buttons-container">
                <a class="t-button-secondary" href="<?php echo Yii::app()->createUrl('foods/foodmenu/viewlunch'); ?>">
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="tag-inner">
        <div class="widget clearmargin">
            <div class="widget-body">
                <?php if (empty($students)): ?>
                    <p>Nenhum aluno com alergia alimentar registrada.</p>
                <?php else: ?>
                    <div class="grid-view">
                        <table class="js-tag-table tag-table-primary tag-table table table-condensed table-striped table-hover table-primary table-vertical-center" aria-describedby="Alunos com alergia alimentar">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Turma</th>
                                    <th>Turno</th>
                                    <th>Alergias</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?php echo CHtml::encode($student['name']); ?></td>
                                        <td><?php echo CHtml::encode($student['classroom']); ?></td>
                                        <td><?php echo CHtml::encode($student['turn']); ?></td>
                                        <td><?php echo CHtml::encode(implode(', ', $student['allergies'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
