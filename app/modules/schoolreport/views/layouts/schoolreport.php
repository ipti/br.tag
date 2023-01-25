<?php
/* @var $content String Conteúdo da página.
 * @var $enrollment StudentEnrollment
 */
$themeUrl = Yii::app()->theme->baseUrl;
$homeUrl = Yii::app()->controller->module->baseUrl;
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

//$eid = $this->eid;
//$enrollment = StudentEnrollment::model()->findByPk($eid);
//$student = $enrollment->studentFk;
//$classroom = $enrollment->classroomFk;
//$school = $classroom->schoolInepFk;

?>
<!DOCTYPE html>
<!--[if lt IE 7]><html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]><html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]><html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]><html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
<head>
    <meta charset="UTF-8"/>
    <title><?= CHtml::encode($this->pageTitle); ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
    <link href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.4/semantic.min.css" rel="stylesheet"/>
    <link href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.4/components/accordion.min.css" rel="stylesheet"/>
    <link href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.4/components/menu.min.css" rel="stylesheet"/>
    <link href="<?=$baseScriptUrl?>/common/css/layout.css" rel="stylesheet"/>
    <script type="text/javascript">var $baseScriptUrl = "<?=$baseScriptUrl?>"</script>
</head>

    <header>
        <div class="ui container">
            <div class="ui grid">
                <div class="four wide column">
                    <a href="<?=$homeUrl?>">
                        <img src="<?=$baseScriptUrl?>/common/img/logo.png" height="30">
                    </a>
                    <h1><?= yii::t('schoolreportModule.layout', 'School Report') ?></h1>
                </div>
                <div class="eight wide column"></div>
                <div class="four wide column right aligned">
                    <a class="ui inverted basic button float" href="#" onclick="window.history.back()">Voltar</a>
                    <a class="ui inverted basic button float right" href="<?= Yii::app()->createUrl('schoolreport/default/logout')?>">Sair</a>
                </div>
            </div>
        </div>
    </header>

    <section id="info">
        <div class="ui container">
            <div class="ui grid">
                <div class="fourteen wide column">
                    <h2 class="ui breadcrumb" >
                        <a class="section" href="<?= Yii::app()->createUrl('schoolreport/default/index')?>"><?=strtolower(Yii::app()->user->info['name'])?></a>
                        <?php if ($this->studentName) {?>
                            <i class="right chevron icon divider"></i>
                            <div class="section">
                                <?=strtolower($this->studentName)?>
                            </div>
                            <i class="right arrow icon divider"></i>
                            <div class="active section">
                                <div class="ui dropdown item">
                                <?=strtolower($this->whichSectionIs)?>
                                    <i class="dropdown icon"></i>
                                    <div class="menu">
                                        <a href="<?= Yii::app()->createUrl('schoolreport/default/frequency', ['eid' => $this->eid])?>" class="item">
                                            Frequência
                                        </a>
                                        <a href="<?= Yii::app()->createUrl('schoolreport/default/grades', ['eid' => $this->eid])?>" class="item">
                                            Notas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    </h2>
                </div>
                <div class="two wide column right aligned">
                    <?php if ($this->showPrintButton) {?>
                    <button class="ui small button" onclick="window.print();">
                        <i class="print icon"></i>
                        Imprimir
                    </button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    <section id="reports">
        <div class="ui container">
            <?= $content ?>
        </div>
    </section>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.4/semantic.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.4/components/accordion.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.4/components/accordion.min.js"></script>

    <script src="<?=$baseScriptUrl?>/common/js/layout.js"></script>
</body>
</html>
