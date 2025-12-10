<div class="tab-pane" id="professional-enrollment">
    <div>
        <h3>Atendimentos</h3>
    </div>
    <div class="column clearleft">
        <div class="control-group column clearleft">
            <div class="controls">
                <a href="#" class="t-button-primary" id="new-enrollment-button">Adicionar Atendimento</a>
            </div>
        </div>
        <div class="enrollment-container">

        </div>
    </div>
</div>

<div class="modal fade modal-content" id="js-add-professional-enrollment" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position:static;">
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="" style="vertical-align: -webkit-baseline-middle">
            </button>
            <h4 class="modal-title"
                id="myModalLabel">Adicionar vinculo</h4>
        </div>
        <form method="post">
            <input type="hidden" class="course-class-index">
            <div class="modal-body">
                <!-- <div class="alert js-alert-ability-structure">Para adicionar habilidades, é necessário selecionar a etapa na aba criar plano, e informar o componente curricular/eixo.</div> -->
                <div id="minorEducationContainer" class="column clearfix">
                    <div class="t-field-select">
                        <?php echo CHtml::label(yii::t('default', 'Discipline'), 'discipline_fk', array('class' => 'control-label t-field-select__label--required')); ?>
                        <select class="select-search-on t-field-select__input select2-container" id="minorEducationDisciplines" name="minorEducationDisciplines">
                            <option value="">Selecione a especialidade</option>
                            <?php
                            foreach ($specialities as $speciality) :
                                echo "<option value=" . $speciality->id . ">" . $speciality->name . "</option>";
                            endforeach;
                            ?>
                        </select>
                        <img class="js-course-plan-loading-abilities" style="margin: 10px 20px;" height="30px" width="30px" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading">
                    </div>
                </div>
                <img class="loading-abilities-select" style="display:none;margin: 0px 5px;" height="30px" width="30px" src="<?php echo Yii::app()->theme->baseUrl; ?>/img/loadingTag.gif" alt="TAG Loading"></img>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                        data-dismiss="modal">Cancelar
                    </button>
                    <button type="button" class="btn btn-primary js-add-selected-abilities"
                        data-dismiss="modal">Adicionar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>