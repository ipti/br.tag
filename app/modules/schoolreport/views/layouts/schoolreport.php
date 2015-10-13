<?php
/* @var $content String Conteúdo da página.
 * @var $enrollment StudentEnrollment
 */
$themeUrl = Yii::app()->theme->baseUrl;
$homeUrl = Yii::app()->controller->module->baseUrl;
$baseScriptUrl = Yii::app()->controller->module->baseScriptUrl;

$year = Yii::app()->user->year;
$eid = 6396;
$enrollment = StudentEnrollment::model()->findByPk($eid                                       );
$student = $enrollment->studentFk;
$classroom = $enrollment->classroomFk;
$school = $classroom->schoolInepFk;

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
                    <h1><?= yii::t('schoolreportModule.layout', "School Report") ?></h1>
                </div>
                <div class="ten wide column"></div>
                <div class="two wide column right aligned">
                    <button class="ui inverted basic button">Sair</button>
                </div>
            </div>
        </div>
    </header>

    <section id="info">
        <div class="ui container">
            <div class="ui grid">
                <div class="fourteen wide column">
                    <h2 class="ui header">
                        <img src="https://cdn0.iconfinder.com/data/icons/user-pictures/100/unknown2-128.png" class="ui circular image">
                        <div class="content">
                             <?=strtolower($student->name)?>
                            <div class="sub header"><?=strtolower($school->name)?></div>
                        </div>
                    </h2>
                </div>
                <div class="two wide column right aligned">
                    <button class="ui small button">
                        <i class="print icon"></i>
                        Imprimir
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section id="reports">
        <div class="ui container">
            <table class="ui striped very basic table">
                <thead>
                <tr>
                    <th class="left aligned">Disciplina</th>
                    <th>1ª</th>
                    <th>2ª</th>
                    <th>RS</th>
                    <th>3ª</th>
                    <th>4ª</th>
                    <th>RS</th>
                    <th>RF</th>
                    <th>Média Final</th>
                </tr></thead>
                <tbody>
                <tr>
                    <td class="left aligned">Português</td>
                    <td><a class="ui blue label">10</a></td>
                    <td><a class="ui red label">4.4</a></td>
                    <td><a class="ui blue label">6.8</a></td>
                    <td><a class="ui blue label">6.8</a></td>
                    <td><a class="ui blue label">6.8</a></td>
                    <td><a class="ui blue label">6.8</a></td>
                    <td><a class="ui blue label">6.8</a></td>
                    <td><a class="ui blue image label">6.8<div class="detail">Aprovado</div></td>
                </tr>
                <tr>
                    <td class="left aligned">Matemática</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td class="left aligned">Geografia</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td class="left aligned">História</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td class="left aligned">Biologia</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td class="left aligned">Física</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td class="left aligned">Química</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td class="left aligned">Inglês</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td class="left aligned">Redação</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td class="left aligned">Filosofia</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                </tbody>
            </table>
        </div>
    </section>

            <?= $content ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="<?=$baseScriptUrl?>/common/js/layout.js"></script>
</body>
</html>
