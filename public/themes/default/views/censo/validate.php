<div id="mainPage" class="main" style="margin-top:40px; padding: 10px">
    <?php

    $this->setPageTitle('TAG - ' . Yii::t('default', 'Censo'));
    $title = Yii::t('default', 'Create a new User');
    $contextDesc = Yii::t('default', 'Available actions that may be taken on User.');
    $this->menu = array(
        array(
            'label' => Yii::t('default', 'List User'),
            'url' => array('index'),
            'description' => Yii::t('default', 'This action list all User, you can search, delete and update')
        ),
    );
    $themeUrl = Yii::app()->theme->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl . '/js/censo/functions.js?v=' . TAG_VERSION, CClientScript::POS_END);

    ?>
    <style type="text/css">
        .widget-timeline .widget-body:before {
            background: none !important;
        }

        .widget-timeline ul.list-timeline li {
            margin-bottom: 2px;
        }

        .itens-censo {
            display: none;
            margin-bottom: 10px;
        }

        .ellipsis {
            max-width: calc(100% - 130px) !important;
            margin-right: 5px;
        }

        #loading-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 40vw;
            height: 20vh;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 8px;
            overflow: hidden;
        }

        @media (max-width: 600px) {
            #loading-popup {
                width: 32vh;
            }
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            backdrop-filter: blur(8px);
            z-index: 9999;

        }

        .loading-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .loading-spinner {
            margin-bottom: 10px;
            border: 4px solid #fff;
            border-top: 4px solid #506c93;
            border-radius: 50%;
            width: 10vw;
            height: 10vw;
            max-width: 50px;
            max-height: 50px;
            animation: spin 2s linear infinite;
        }

        .loading-text {
            color: #454e7e;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <div class="clearfix"></div>
    <div class="main form-content widget widget-4 widget-tabs-icons-only widget-timeline margin-bottom-none">

        <div class="widget-body">
        </div>

        <?php if (Yii::app()->user->hasFlash('success')) { ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php } else if (Yii::app()->user->hasFlash('error')) { ?>
            <div class="alert alert-error">
                <?php echo Yii::app()->user->getFlash('success') ?>
            </div>
        <?php } ?>
        <div class="row  t-buttons-container">
            <a href="<?= CHtml::normalizeUrl(array('censo/export', 'withoutCertificates' => 0)) ?>" class="t-button-primary js-export-link" style="margin:0;">
                <?= Yii::t('default', 'Exportar arquivo de migração') ?>
            </a>
            <!-- <button class="t-button-primary js-export-censo" style="margin:0;">
                <?= Yii::t('default', 'Exportar arquivo de migração') ?>
            </button> -->
            <?php if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)): ?>
                <a href="<?= CHtml::normalizeUrl(array('censo/exportidentification', 'withoutCertificates' => 0)) ?>" class="t-button-secondary js-export-link"
                    style="margin:0;">
                    <?= Yii::t('default', 'Exportar arquivo de identificação') ?>
                </a>
                <a href="<?= CHtml::normalizeUrl(array('censo/inepImport')) ?>" class="t-button-secondary"
                    style="margin:0;">
                    <?= Yii::t('default', 'Importar INEP ID') ?>
                </a>
            <?php endif; ?>
        </div>
        <div class="t-field-checkbox">
            <label class="t-field-checkbox__label">
                <input type="checkbox" class="t-field-checkbox__input js-withoutCertificates" name="withoutCertificates">
                Exportar sem certidões de nascimento
            </label>
        </div>
        <!-- Widget Heading END -->
        <?php
        $dataValidation = [];
        $timeExpiration = 60 * 60 * 12;
        ?>
        <div class="widget-body">
            <div class="tab-content">

                <!-- Filter Users Tab -->
                <div class="tab-pane active" id="filterUsersTab">
                    <div id="loading-popup">

                        <div class="loading-content">
                            <div><img height="50px" width="50px" src="/themes/default/img/loadingTag.gif"
                                    alt="TAG Loading">
                            </div>
                            <div class="loading-text">Aguarde enquanto validamos os dados...</div>
                        </div>

                    </div>


                </div>
            </div>
            <script type="text/javascript">
                $(function() {
                    $.ajax({
                        url: '<?= $this->createUrl('censo/validate') ?>',
                        success: function(data) {
                            $('#filterUsersTab').html(data);
                            $(".itens-censo li").parents('div').css("display", "block");
                            if ($(".list-timeline").find(".ellipsis").length) {
                                $(".list-timeline").find(".alert").addClass("alert-error").removeClass("alert-success").find("strong").html(
                                    "Foram encontradas pendências para exportação de dados para o EDUCACENSO. Corrija primeiro para exportar o arquivo."
                                );
                            } else {
                                $(".list-timeline").find(".alert").addClass("alert-success").removeClass("alert-error").find("strong").html(
                                    "Nenhuma pendência registrada.");

                            }
                        }
                    });

                });
            </script>
        </div>

    </div>
