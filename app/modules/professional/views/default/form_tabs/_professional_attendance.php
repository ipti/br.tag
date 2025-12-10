<div class="tab-pane" id="professional-attendance">
    <div>
        <h3>Atendimentos</h3>
    </div>
    <div class="column clearleft">
        <div class="control-group column clearleft">
            <div class="controls">
                <a href="#" class="t-button-primary new-attendance-button" id="new-attendance-button">Adicionar Atendimento</a>
            </div>
        </div>
        <div class="attendance-container">
            <div class="form-attendance" style="display: none;">
                <div>
                    <h3>Atendimento</h3>
                </div>
                <div class="control-group">
                    <div><?php echo "<strong>* Se a data não for escolhida, mas o local do atendimento for informado, a data registrada será a atual.</strong>" ?></div>

                    <div class="controls">
                        <?php echo $form->label($modelAttendance, 'date', array('class' => 'control-label')); ?>
                    </div>
                    <div class="controls">
                        <?php
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'model' => $modelAttendance,
                            'attribute' => 'date',
                            'options' => array(
                                'dateFormat' => 'dd/mm/yy',
                                'changeYear' => true,
                                'changeMonth' => true,
                                'yearRange' => '2000:' . date('Y'),
                                'showOn' => 'focus',
                                'maxDate' => 0
                            ),
                            'htmlOptions' => array(
                                'readonly' => 'readonly',
                                'style' => 'cursor: pointer;',
                                'placeholder' => 'Clique aqui para escolher a data'
                            ),
                        ));

                        echo CHtml::link('	Limpar', '#', array(
                            'onclick' => '$("#' . CHtml::activeId($modelAttendance, 'date') . '").datepicker("setDate", null); return false;',
                        ));

                        echo $form->error($modelAttendance, 'date');
                        ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <?php echo $form->label($modelAttendance, 'local', array('class' => 'control-label')); ?>
                    </div>
                    <div class="controls">
                        <?php echo $form->textField($modelAttendance, 'local', array('size' => 60, 'maxlength' => 100, 'placeholder' => 'Informe o local do atendimento')); ?>
                        <?php echo $form->error($modelAttendance, 'local'); ?>
                    </div>
                </div>
            </div>
            <div id="attendances" class="widget widget-scroll margin-bottom-none table-responsive">
                <h3>
                    Atendimentos
                </h3>
                <div style="" class="full">
                    <table class="tag-table-secondary table-bordered table-striped align-start"
                        aria-describedby="tabela de atendimentos">
                        <thead>
                            <tr>
                                <th style="min-width: 100px;border: none">Data</th>
                                <th style="min-width: 200px;border: none">Local</th>
                                <th style="text-align: right; min-width: 50px;border: none"></th>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($modelAttendances as $attendance) {
                            ?>
                                <tr>
                                    <td style="border: none"><?php echo date("d/m/Y", strtotime($attendance->date)) ?></td>
                                    <td style="border: none"><?php echo $attendance->local ?></td>
                                    <td style="border: none">
                                        <button
                                            type="button"
                                            class="delete-attendance-bt t-button-content"
                                            style="float:right; margin-right: 14px"
                                            value="<?php echo $attendance->id_attendance ?>"
                                            onclick="deleteAttendance(this)">
                                            <!-- <div class="t-icon-trash"></div> -->
                                            <img src="<?php echo Yii::app()->theme->baseUrl . '/img/deletar.svg'; ?>" alt='Excluir'></img>
                                        </button>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <div>
                        <p>Atendimentos encontrados: <?php echo count($modelAttendances); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>