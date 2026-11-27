<?php
$this->pageTitle = 'TAG - Controle de lançamentos';
$labels = ['grade' => 'Notas', 'frequency' => 'Frequência', 'lesson' => 'Aulas'];
$policies = [];
foreach ($rules as $rule) {
    $policies[$rule['school_inep_fk']][$rule['classroom_fk']][$rule['entry_type']] = (int) $rule['locked'];
}
$bySchool = [];
foreach ($classrooms as $classroom) {
    $bySchool[$classroom['school_inep_fk']][] = $classroom;
}
?>
<div class="main entry-lock">
    <header class="entry-lock__heading">
        <div>
            <span class="entry-lock__eyebrow">DIÁRIO ELETRÔNICO</span>
            <h1>Controle de lançamentos</h1>
            <p>Gerencie a liberação de notas, frequência e aulas por escola ou turma.</p>
        </div>
        <div class="entry-lock__year">Ano letivo <strong><?= CHtml::encode(Yii::app()->user->year) ?></strong></div>
    </header>

    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success" role="status"><?= CHtml::encode(Yii::app()->user->getFlash('success')) ?></div>
    <?php endif; ?>

    <div class="entry-lock__notice">
        <span class="entry-lock__notice-icon" aria-hidden="true">i</span>
        <p><strong>Como funciona</strong><br>Os indicadores mostram a situação atual. As caixas servem apenas para selecionar o que você deseja alterar. A regra da escola vale para o ano letivo selecionado, inclusive para novas turmas desse ano, e substitui as exceções das turmas nos tipos escolhidos.</p>
    </div>

    <form method="post" id="entry-lock-form">
        <input type="hidden" name="token" value="<?= CHtml::encode(Yii::app()->session['diary_lock_token']) ?>">
        <input type="hidden" name="school_year" value="<?= CHtml::encode(Yii::app()->user->year) ?>">
        <?php if (Yii::app()->request->enableCsrfValidation): ?>
            <input type="hidden" name="<?= CHtml::encode(Yii::app()->request->csrfTokenName) ?>" value="<?= CHtml::encode(Yii::app()->request->csrfToken) ?>">
        <?php endif; ?>

        <section class="entry-lock__panel" aria-labelledby="entry-lock-types-heading">
            <div class="entry-lock__section-heading">
                <div><span class="entry-lock__step">1</span><h2 id="entry-lock-types-heading">Escolha os lançamentos</h2><p>Selecione os tipos que deseja alterar.</p></div>
            </div>
            <div class="entry-lock__types">
                <?php foreach ($labels as $type => $label): ?>
                    <label class="entry-lock__choice"><input type="checkbox" name="types[]" value="<?= CHtml::encode($type) ?>"><span><?= CHtml::encode($label) ?></span></label>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="entry-lock__panel" aria-labelledby="entry-lock-schools-heading">
            <div class="entry-lock__section-heading entry-lock__section-heading--split">
                <div><span class="entry-lock__step">2</span><h2 id="entry-lock-schools-heading">Escolha onde aplicar</h2><p>Selecione escolas inteiras ou abra uma escola para escolher turmas.</p></div>
                <div class="entry-lock__bulk"><button type="button" id="select-schools" class="t-button-secondary">Selecionar todas as escolas</button><button type="button" id="clear-schools" class="entry-lock__text-button">Limpar seleção</button></div>
            </div>
            <div class="entry-lock__toolbar">
                <label class="entry-lock__search"><span>Buscar escola</span><input type="search" id="entry-lock-search" placeholder="Digite o nome da escola"></label>
                <span id="entry-lock-selection" class="entry-lock__selection" aria-live="polite">Nenhuma escola ou turma selecionada</span>
            </div>
            <p class="entry-lock__legend"><span class="entry-lock__dot entry-lock__dot--open"></span> Liberado <span class="entry-lock__dot entry-lock__dot--locked"></span> Bloqueado <span class="entry-lock__legend-note">Situação atual</span></p>
            <div class="entry-lock__schools">
                <?php foreach ($schools as $school):
                    $schoolId = $school['inep_id'];
                    $schoolRules = $policies[$schoolId][0] ?? [];
                    $schoolClassrooms = $bySchool[$schoolId] ?? [];
                ?>
                    <article class="entry-lock-school" data-school-name="<?= CHtml::encode($school['name']) ?>">
                        <div class="entry-lock-school__top">
                            <label class="entry-lock-school__select"><input class="school-select" type="checkbox" name="schools[]" value="<?= CHtml::encode($schoolId) ?>"><span><strong><?= CHtml::encode($school['name']) ?></strong><small>Selecionar escola inteira · <?= count($schoolClassrooms) ?> <?= count($schoolClassrooms) === 1 ? 'turma' : 'turmas' ?> neste ano</small></span></label>
                            <div class="entry-lock__statuses" aria-label="Situação da escola">
                                <?php foreach ($labels as $type => $label): $locked = !empty($schoolRules[$type]); ?>
                                    <span class="entry-lock__status <?= $locked ? 'entry-lock__status--locked' : 'entry-lock__status--open' ?>"><span aria-hidden="true"><?= $locked ? '●' : '✓' ?></span> <?= CHtml::encode($label) ?>: <?= $locked ? 'Bloqueado' : 'Liberado' ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <details class="entry-lock-school__details">
                            <summary>Ver turmas e exceções <span aria-hidden="true">⌄</span></summary>
                            <div class="entry-lock-school__classes">
                                <?php if (!$schoolClassrooms): ?><p>Nenhuma turma neste ano letivo.</p><?php endif; ?>
                                <?php foreach ($schoolClassrooms as $classroom): ?>
                                    <div class="entry-lock-classroom">
                                        <label class="entry-lock-classroom__select"><input class="classroom-select" type="checkbox" name="classrooms[]" value="<?= CHtml::encode($classroom['id']) ?>"><span><?= CHtml::encode($classroom['name']) ?></span></label>
                                        <div class="entry-lock__statuses" aria-label="Situação da turma">
                                            <?php foreach ($labels as $type => $label):
                                                $own = $policies[$schoolId][$classroom['id']][$type] ?? null;
                                                $locked = $own === null ? ($schoolRules[$type] ?? 0) : $own;
                                            ?>
                                                <span class="entry-lock__status <?= $locked ? 'entry-lock__status--locked' : 'entry-lock__status--open' ?>"><span aria-hidden="true"><?= $locked ? '●' : '✓' ?></span> <?= CHtml::encode($label) ?>: <?= $locked ? 'Bloqueado' : 'Liberado' ?> <small><?= $own === null ? '· escola' : '· exceção' ?></small></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </details>
                    </article>
                <?php endforeach; ?>
                <p id="entry-lock-empty" class="entry-lock__empty" hidden>Nenhuma escola encontrada para essa busca.</p>
            </div>
            <p class="entry-lock__footnote">Escolas e turmas são configuradas para o ano letivo selecionado. Outros anos não são alterados.</p>
        </section>

        <section class="entry-lock__panel entry-lock__apply" aria-labelledby="entry-lock-action-heading">
            <div class="entry-lock__section-heading"><div><span class="entry-lock__step">3</span><h2 id="entry-lock-action-heading">Defina a ação</h2><p>A ação será aplicada aos tipos e locais selecionados acima.</p></div></div>
            <div class="entry-lock__actions">
                <label class="entry-lock__choice"><input type="radio" name="operation" value="unlock" required><span>Liberar lançamentos</span></label>
                <label class="entry-lock__choice"><input type="radio" name="operation" value="lock"><span>Bloquear lançamentos</span></label>
                <label class="entry-lock__choice"><input type="radio" name="operation" value="inherit"><span>Turmas: herdar da escola</span></label>
            </div>
            <p class="entry-lock__footnote">Para voltar a herdar a regra da escola, selecione somente turmas.</p>
            <p id="entry-lock-error" class="entry-lock__error" role="alert" hidden></p>
            <button class="t-button-primary" type="submit">Aplicar alterações</button>
        </section>
    </form>
</div>
<script>
(function () {
    var form = document.getElementById('entry-lock-form');
    var schools = Array.prototype.slice.call(document.querySelectorAll('.school-select'));
    var selection = document.getElementById('entry-lock-selection');
    function updateSelection() {
        var selectedSchools = schools.filter(function (school) { return school.checked; }).length;
        var selectedClasses = document.querySelectorAll('.entry-lock-school:not(.is-selected) .classroom-select:checked').length;
        selection.textContent = selectedSchools || selectedClasses ? selectedSchools + ' escola(s) e ' + selectedClasses + ' turma(s) selecionada(s)' : 'Nenhuma escola ou turma selecionada';
    }
    schools.forEach(function (school) {
        var card = school.closest('.entry-lock-school');
        var children = card.querySelectorAll('.classroom-select');
        school.addEventListener('change', function () {
            card.classList.toggle('is-selected', school.checked);
            children.forEach(function (child) { child.checked = school.checked; });
            updateSelection();
        });
        children.forEach(function (child) {
            child.addEventListener('change', function () {
                if (!child.checked) { school.checked = false; card.classList.remove('is-selected'); }
                updateSelection();
            });
        });
    });
    document.getElementById('select-schools').addEventListener('click', function () { schools.forEach(function (school) { school.checked = true; school.dispatchEvent(new Event('change')); }); });
    document.getElementById('clear-schools').addEventListener('click', function () { schools.forEach(function (school) { school.checked = false; school.dispatchEvent(new Event('change')); }); });
    document.getElementById('entry-lock-search').addEventListener('input', function () {
        var query = this.value.trim().toLocaleLowerCase();
        var visible = 0;
        document.querySelectorAll('.entry-lock-school').forEach(function (card) {
            var match = card.getAttribute('data-school-name').toLocaleLowerCase().indexOf(query) !== -1;
            card.hidden = !match;
            if (match) visible++;
        });
        document.getElementById('entry-lock-empty').hidden = visible !== 0;
    });
    form.addEventListener('submit', function (event) {
        var error = document.getElementById('entry-lock-error');
        var operation = form.querySelector('input[name="operation"]:checked');
        var message = '';
        if (!form.querySelector('input[name="types[]"]:checked')) message = 'Selecione pelo menos um tipo de lançamento.';
        else if (!form.querySelector('.school-select:checked, .classroom-select:checked')) message = 'Selecione pelo menos uma escola ou turma.';
        else if (operation && operation.value === 'inherit' && form.querySelector('.school-select:checked')) message = 'Para herdar a regra da escola, selecione somente turmas.';
        error.textContent = message;
        error.hidden = !message;
        if (message) { event.preventDefault(); error.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
    });
}());
</script>
