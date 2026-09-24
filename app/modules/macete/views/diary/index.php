<?php
/* @var $this DiaryController */
/* @var $classrooms Classroom[] */

$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseScriptUrl . '/macete-diary.js?v=' . TAG_VERSION, CClientScript::POS_END);

$this->setPageTitle('TAG - Diário do MACETE');

$months = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
];
$currentMonth = (int) date('n');
$currentYear = (int) Yii::app()->user->year;
?>

<div id="mainPage" class="main">
    <div class="row-fluid">
        <div class="span12">
            <h1>Diário do MACETE</h1>
        </div>
    </div>

    <div class="mobile-row align-items--end">
        <div class="column t-field-select clearleft is-one-quarter">
            <label class="t-field-select__label--required">Turma</label>
            <select id="macete-diary-classroom" class="select-search-on t-field-select__input">
                <option value="">Selecione a turma</option>
                <?php foreach ($classrooms as $classroom): ?>
                    <option value="<?php echo (int) $classroom->id; ?>"><?php echo CHtml::encode($classroom->name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="column t-field-select is-one-quarter">
            <label class="t-field-select__label">Componente curricular</label>
            <select id="macete-diary-discipline" class="select-search-on t-field-select__input" disabled>
                <option value="">Todos os componentes</option>
            </select>
        </div>
        <div class="column t-field-select is-one-eighth">
            <label class="t-field-select__label--required">Mês</label>
            <select id="macete-diary-month" class="select-search-on t-field-select__input">
                <?php foreach ($months as $number => $name): ?>
                    <option value="<?php echo $number; ?>" <?php echo $number === $currentMonth ? 'selected' : ''; ?>>
                        <?php echo $name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="column t-field-text is-one-eighth">
            <label class="t-field-text__label--required">Ano</label>
            <input type="number" id="macete-diary-year" class="t-field-text__input" value="<?php echo $currentYear; ?>">
        </div>
        <div class="column t-buttons-container">
            <button type="button" id="macete-diary-search" class="t-button-primary">Buscar</button>
            <a id="macete-diary-print" class="t-button-secondary hide" target="_blank" rel="noopener">
                <span class="t-icon-printer"></span> Imprimir
            </a>
        </div>
    </div>

    <div id="macete-diary-error" class="alert alert-error hide"></div>

    <div id="macete-diary-summary" class="row hide">
        <div class="column">
            <div class="t-badge-info">
                <b>Total de dias letivos (Calendário Escolar): </b>
                <span id="macete-diary-total-scheduled"></span>
            </div>
        </div>
        <div class="column">
            <div class="t-badge-info">
                <b>Total de aulas registradas no MACETE: </b>
                <span id="macete-diary-total-registered"></span>
            </div>
        </div>
    </div>

    <div class="tag-inner">
        <div class="widget clearmargin">
            <div class="widget-body">
                <table id="macete-diary-table" class="js-tag-table tag-table-primary tag-table table table-condensed table-striped table-hover table-primary table-vertical-center hide">
                    <thead>
                        <tr>
                            <th>Dia</th>
                            <th>Situação</th>
                            <th>Plano usado</th>
                            <th>Status do registro</th>
                            <th>Conteúdo executado</th>
                        </tr>
                    </thead>
                    <tbody id="macete-diary-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
