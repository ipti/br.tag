<?php
/* @var $this LessonsplanController */
/* @var $dataProvider CActiveDataProvider */
/* @var $stages array */
/* @var $disciplines array */
/* @var $templateStages array */
/* @var $filters array */

$this->setPageTitle('TAG - Planos MACETE');
?>

<div id="mainPage" class="main">
    <div class="row-fluid">
        <div class="span12">
            <h1>Planos MACETE</h1>
            <div class="t-buttons-container">
                <a class="t-button-primary" href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONSPLAN_CREATE); ?>">
                    Novo plano
                </a>
                <a class="t-button-secondary" href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONSRECORD_INDEX); ?>">
                    Registrar aula
                </a>
                <a class="t-button-secondary" href="#" data-toggle="modal" data-target="#js-macete-use-template">
                    Usar modelo
                </a>
                <?php if (TagUtils::checkAccess(TRole::ADMIN)): ?>
                    <a class="t-button-secondary" href="<?php echo MaceteRoutes::url(MaceteRoutes::TEMPLATES_INDEX); ?>">
                        Gerenciar modelos
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success"><?php echo Yii::app()->user->getFlash('success'); ?></div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-error"><?php echo Yii::app()->user->getFlash('error'); ?></div>
    <?php endif; ?>

    <form method="get" class="mobile-row align-items--end">
        <input type="hidden" name="r" value="macete/lessonsplan/index">
        <div class="column t-field-select clearleft is-one-quarter">
            <?php echo CHtml::label('Etapa', 'stage', ['class' => 't-field-select__label']); ?>
            <?php echo CHtml::dropDownList(
    'stage',
    $filters['stage'],
    CHtml::listData($stages, 'id', 'name'),
    [
        'class' => 'select-search-on t-field-select__input',
        'prompt' => 'Todas as etapas',
    ]
); ?>
        </div>
        <div class="column t-field-select is-one-quarter">
            <?php echo CHtml::label('Componente', 'discipline', ['class' => 't-field-select__label']); ?>
            <?php echo CHtml::dropDownList(
                'discipline',
                $filters['discipline'],
                CHtml::listData($disciplines, 'id', 'name'),
                [
                    'class' => 'select-search-on t-field-select__input',
                    'prompt' => 'Todos os componentes',
                ]
            ); ?>
        </div>
        <div class="column t-buttons-container">
            <button type="submit" class="t-button-primary">Filtrar</button>
            <a class="t-button-secondary" href="<?php echo MaceteRoutes::url(MaceteRoutes::LESSONSPLAN_INDEX); ?>">Limpar</a>
        </div>
    </form>

    <div class="tag-inner">
        <div class="widget clearmargin">
            <div class="widget-body">
                <?php $this->widget('zii.widgets.grid.CGridView', [
                    'dataProvider' => $dataProvider,
                    'enablePagination' => false,
                    'enableSorting' => false,
                    'ajaxUpdate' => false,
                    'itemsCssClass' => 'js-tag-table tag-table-primary tag-table table table-condensed table-striped table-hover table-primary table-vertical-center',
                    'columns' => [
                        [
                            'header' => 'Plano',
                            'name' => 'name',
                            'type' => 'raw',
                            'value' => 'CHtml::link(CHtml::encode($data->name), MaceteRoutes::url(MaceteRoutes::LESSONSPLAN_UPDATE, ["id" => $data->id]))',
                            'htmlOptions' => ['width' => '18%', 'class' => 'link-update-grid-view'],
                        ],
                        [
                            'header' => 'Código',
                            'name' => 'code',
                            'value' => 'CHtml::encode($data->code)',
                            'htmlOptions' => ['width' => '8%'],
                        ],
                        [
                            'header' => 'Tema',
                            'name' => 'theme',
                            'value' => '$data->theme',
                            'htmlOptions' => ['width' => '18%'],
                        ],
                        [
                            'header' => 'Professor',
                            'name' => 'users_fk',
                            'value' => '$data->usersFk !== null ? $data->usersFk->name : ""',
                            'htmlOptions' => ['width' => '14%'],
                        ],
                        [
                            'header' => 'Etapas',
                            'name' => 'edcenso_stage_vs_modality_fk',
                            'value' => '$data->getStageNames()',
                            'htmlOptions' => ['width' => '14%'],
                        ],
                        [
                            'header' => 'Componente',
                            'name' => 'edcenso_discipline_fk',
                            'value' => '$data->getDisciplineNames()',
                            'htmlOptions' => ['width' => '12%'],
                        ],
                        [
                            'header' => 'Habilidades',
                            'type' => 'raw',
                            'value' => 'CHtml::encode($data->getAbilityCodes())',
                            'htmlOptions' => ['width' => '10%'],
                        ],
                        [
                            'header' => 'Acoes',
                            'class' => 'CButtonColumn',
                            'template' => '{update}{record}{delete}',
                            'buttons' => [
                                'update' => [
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/editar.svg',
                                    'url' => 'MaceteRoutes::url(MaceteRoutes::LESSONSPLAN_UPDATE, ["id" => $data->id])',
                                ],
                                'record' => [
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/buttonIcon/start.svg',
                                    'url' => 'MaceteRoutes::url(MaceteRoutes::LESSONSRECORD_INDEX)',
                                    'options' => ['title' => 'Registrar aula'],
                                ],
                                'delete' => [
                                    'imageUrl' => Yii::app()->theme->baseUrl . '/img/deletar.svg',
                                    'url' => 'MaceteRoutes::url(MaceteRoutes::LESSONSPLAN_DELETE, ["id" => $data->id])',
                                ],
                            ],
                            'updateButtonOptions' => ['style' => 'margin-right: 12px;'],
                            'deleteButtonOptions' => ['style' => 'cursor: pointer; margin-left: 12px;'],
                            'htmlOptions' => ['width' => '90px', 'style' => 'text-align: center'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>

    <div class="modal fade t-modal-container larger" id="js-macete-use-template" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="t-modal__header">
                <h4 class="t-title">Usar plano modelo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/Close.svg" alt="">
                </button>
            </div>
            <div class="t-modal__body">
                <div class="js-macete-template-loading">Carregando modelos...</div>
                <div class="js-macete-template-empty hide">Nenhum plano modelo cadastrado ainda.</div>
                <div class="js-macete-template-search hide">
                    <div class="mobile-row">
                        <div class="column clearleft is-one-quarter">
                            <div class="t-field-text">
                                <label class="t-field-text__label" for="js-macete-template-code">Código do plano</label>
                                <input type="text" id="js-macete-template-code" class="t-field-text__input" placeholder="Ex.: MAT-001" autocomplete="off">
                            </div>
                        </div>
                        <div class="column">
                            <div class="t-field-text">
                                <label class="t-field-text__label" for="js-macete-template-text">Pesquisar</label>
                                <input type="text" id="js-macete-template-text" class="t-field-text__input" placeholder="Digite palavras do título ou do tema" autocomplete="off">
                            </div>
                        </div>
                        <div class="column t-field-select is-one-quarter">
                            <label class="t-field-select__label" for="js-macete-template-stage">Etapa</label>
                            <?php echo CHtml::dropDownList(
                                'js-macete-template-stage',
                                '',
                                CHtml::listData($templateStages, 'id', 'name'),
                                [
                                    'id' => 'js-macete-template-stage',
                                    'class' => 't-field-select__input',
                                    'prompt' => 'Todas as etapas',
                                ]
                            ); ?>
                        </div>
                    </div>
                    <p class="js-macete-template-count"></p>
                    <table class="tag-table tag-table-primary">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Tema da aula</th>
                                <th>Etapa</th>
                                <th>Componente</th>
                            </tr>
                        </thead>
                        <tbody class="js-macete-template-results"></tbody>
                    </table>
                </div>
                <div class="t-modal__footer row reverse">
                    <div class="t-buttons-container justify-content--center">
                        <button type="button" class="t-button-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                    <div class="t-buttons-container justify-content--center">
                        <button type="button" class="t-button-primary js-macete-use-template-confirm hide">
                            Usar este modelo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function ($) {
        // Acima disso a lista pede para refinar a busca: o acervo de um ano
        // passa de mil modelos e montar todas as linhas deixa o modal lento
        var MAX_RESULTS = 100;
        var templatesLoaded = false;
        var templates = [];
        var selectedId = null;
        var filterTimer = null;

        // Busca sem diferenciar maiúsculas nem acentos ("matematica" acha "Matemática")
        function normalize(value) {
            return (value || '').toString().normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase().trim();
        }

        function goToTemplate(templateId) {
            window.location.href = '?r=macete/lessonsplan/create&templateId=' + encodeURIComponent(templateId);
        }

        function selectTemplate(templateId) {
            selectedId = templateId;
            $('.js-macete-template-results tr').removeClass('selected');
            if (templateId !== null) {
                $('.js-macete-template-results tr[data-id="' + templateId + '"]').addClass('selected');
            }
            $('.js-macete-use-template-confirm').toggleClass('hide', templateId === null);
        }

        function filterTemplates() {
            var code = normalize($('#js-macete-template-code').val());
            var words = normalize($('#js-macete-template-text').val()).split(/\s+/).filter(Boolean);
            var stageId = parseInt($('#js-macete-template-stage').val(), 10);

            var matches = templates.filter(function (template) {
                if (code && template.codeText.indexOf(code) === -1) {
                    return false;
                }
                if (stageId && template.stageIds.indexOf(stageId) === -1) {
                    return false;
                }
                return words.every(function (word) {
                    return template.searchText.indexOf(word) !== -1;
                });
            });

            var body = $('.js-macete-template-results').empty();
            $.each(matches.slice(0, MAX_RESULTS), function () {
                body.append(
                    $('<tr>').attr('data-id', this.id).css('cursor', 'pointer').append(
                        $('<td>').text(this.code || ''),
                        $('<td>').text(this.name),
                        $('<td>').text(this.stage || ''),
                        $('<td>').text(this.discipline || '')
                    )
                );
            });

            var count;
            if (!matches.length) {
                count = 'Nenhum modelo encontrado. Confira o código ou tente outras palavras.';
            } else if (matches.length > MAX_RESULTS) {
                count = matches.length + ' modelos encontrados. Mostrando os ' + MAX_RESULTS + ' primeiros: refine a busca para achar o que procura.';
            } else {
                count = matches.length + (matches.length === 1 ? ' modelo encontrado.' : ' modelos encontrados.');
            }
            $('.js-macete-template-count').text(count);

            // Mantém a seleção só se o modelo escolhido continua na lista
            var stillListed = matches.slice(0, MAX_RESULTS).some(function (template) {
                return template.id === selectedId;
            });
            selectTemplate(stillListed ? selectedId : null);
        }

        function loadTemplates() {
            if (templatesLoaded) {
                return;
            }
            templatesLoaded = true;

            $.ajax({
                type: 'POST',
                url: '?r=macete/lessonsplan/getTemplates',
                dataType: 'json',
            }).done(function (data) {
                $('.js-macete-template-loading').addClass('hide');

                if (!data || !data.length) {
                    $('.js-macete-template-empty').removeClass('hide');
                    return;
                }

                templates = $.map(data, function (template) {
                    template.codeText = normalize(template.code);
                    template.searchText = normalize([template.name, template.theme, template.code].join(' '));
                    return template;
                });

                $('.js-macete-template-search').removeClass('hide');
                filterTemplates();
                $('#js-macete-template-text').trigger('focus');
            });
        }

        $('#js-macete-use-template').on('show.bs.modal shown.bs.modal', loadTemplates);

        $(document).on('input', '#js-macete-template-code, #js-macete-template-text', function () {
            clearTimeout(filterTimer);
            filterTimer = setTimeout(filterTemplates, 200);
        });

        $(document).on('change', '#js-macete-template-stage', filterTemplates);

        $(document).on('click', '.js-macete-template-results tr', function () {
            selectTemplate($(this).data('id'));
        });

        $(document).on('dblclick', '.js-macete-template-results tr', function () {
            goToTemplate($(this).data('id'));
        });

        $(document).on('click', '.js-macete-use-template-confirm', function () {
            if (selectedId !== null) {
                goToTemplate(selectedId);
            }
        });
    })(jQuery);
</script>
