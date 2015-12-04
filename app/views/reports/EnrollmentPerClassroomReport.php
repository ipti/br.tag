<?php
/* @var $this ReportsController */
/* @var $report mixed */
/* @var $classroom Classroom*/
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentPerClassroomReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$classroom = Classroom::model()->findByPk($cid);
$school = SchoolIdentification::model()->findByPk($classroom->school_inep_fk);

$criteria = new CDbCriteria();
$criteria->select = '*';
$criteria->alias = 'i';
$criteria->join = 'JOIN student_enrollment e ON i.id = e.student_fk';
$criteria->condition = 'e.classroom_fk = ' . $classroom->id;
$criteria->order = 'i.name';

$identifications = StudentIdentification::model()->findAll($criteria);
?>

<div class="row-fluid hidden-print">
    <div class="span12">
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
        </div>
    </div>
</div>


<div class="innerLR">
    <div>

        <script type="text/javascript">
            /*<![CDATA[*/
            jQuery(function ($) {
                    jQuery.ajax({'type': 'GET',
                        'data': {'cid':<?php echo $cid; ?>},
                        'url': '<?php echo Yii::app()->createUrl('reports/getEnrollmentPerClassroomInformation') ?>',
                        'success': function (data) {
                            gerarRelatorio(data);
                        }, 'error': function () {
                            limpar();
                        }, 'cache': false});
                    return false;
                }
            );
            /*]]>*/
        </script>

        <div id="report">
            <div id="container-header" style="width: 100%; margin: -25px auto auto auto; text-align: center">
                <?php
                    if(isset($school->act_of_acknowledgement)){
                ?>
                <span style="display:block;clear:both;width: 40px;margin:0 auto;">
                <?= CHtml::image(Yii::app()->controller->createUrl('school/displayLogo', array('id'=>$school->inep_id)), 'logo', array('width'=>40, 'display:block;width:40px; margin:0 auto')) ?>
                </span>
                <p style="font-weight:bold;font-size:15px"><?php echo $school->name ?></p>
                <p style="font-size:10px;font-weight: bold"><?php echo $school->act_of_acknowledgement ?></p>
                <p style="font-size:8px;font-weight: bold">
                    <?= $school->address ?>,
                    <?= $school->edcensoCityFk->name ?>/
                    <?= $school->edcensoUfFk->name ?> - CEP: <?= $school->cep ?>
                </p>
                <span style="display: block; clear: both"></span>
                <?php
                    }else{
                ?>
                <img src="<?php echo yii::app()->baseUrl; ?>/images/boquim.png" width="40px" style="float: left; margin-right: 5px;">
                <span style="text-align: center; float: left; margin-top: 5px;">PREFEITURA MUNICIPAL DE BOQUIM<br>
                SECRETARIA MUNICIPAL DE EDUCAÇÃO, CULTURA, ESPORTE, LAZER E TURISMO</span>
                <span style="clear:both;display:block"></span>
                <?php }?>
            </div>

            <span style="clear:both;display:block"></span>
            <br><br>

            <div style="width: 100%; margin: 0 auto; text-align:center;margin-top: -15px;">
                <div style="float: left; text-align:left; margin: 5px 0 5px -20px;line-height: 14px;">
                    <div class="span2"><b>INEP: </b>
                        <?= isset($classroom->inep_id) ? $classroom->inep_id : 'Não informado' ?>
                    </div>
                    <div class="span2"><b>TURNO: </b>
                        <?php
                            switch ($classroom->turn){
                                case 'M':
                                    echo 'Manhã';
                                    break;
                                case 'T':
                                    echo 'Tarde';
                                    break;
                                case 'N':
                                    echo 'Noite';
                                    break;
                                case 'I':
                                    echo 'Integral';
                                    break;
                                default:
                                    echo 'Não informado';
                                    break;
                            }
                        ?>
                    </div>
                    <div class="span4"><b>SÉRIE/ANO: </b><?php echo $classroom->edcenso_stage_vs_modality_fk ?></div>
                    <div class="span2"><b>TURMA: </b><?php echo $classroom->name ?></div>
                    <br>
                    <div class="span2"><b>PROFESSOR(A): </b><?php echo $classroom->instructor_situation ?></div>
                </div>
            </div>

            <span style="clear:both;display:block"></span>

            <table>
                <tr>
                    <th>Nº</th>
                    <th>Nome do aluno</th>
                    <th>Gênero</th>
                    <th>Data de nascimento</th>
                    <th>Naturalidade</th>
                    <th>Tipo de matrícula</th>
                    <th>Situação na série</th>
                    <th>Endereço</th>
                </tr>
                <?php
                    foreach($identifications as $key=>$i){
                ?>
                <tr>
                    <td><?= $key+1 ?></td>
                    <td><?= $i['name'] ?></td>
                    <td><?= $i['sex'] == 1 ? 'M' : 'F' ?></td>
                    <td><?= $i['birthday'] ?></td>
                    <td><?= $i['nationality'] == 1 ? ($i['sex'] == 1 ? 'Brasileiro' : 'Brasileira') : 'Estrangeiro' ?></td>
                    <td><?= $i['register_type'] ?></td> <!-- MC, MI, etc -->
                    <td><?= $i['sex'] ?></td> <!-- situação na série =>  N / P / R => ???????? -->
                    <td><?= $i['sex'] ?></td> <!-- endereço => dar join com docs and address -->
                </tr>
                <?php
                    }
                ?>
            </table>
        </div>
    </div>
</div>

<style>

    table, td, tr, th {
        border: 1px solid black;
        border-collapse: collapse;
    }

    @media print {

        #report{
            margin-top: 50px;
            margin-left; 100px;
            transform: rotate(90deg);
            width: 100%;
        }

        #container-header {
            width: 425px !important;
        }
        table, td, tr, th {
            border-color: black !important;
        }
        .report-table-empty td {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
        .vertical-text {
            height: 110px;
            vertical-align: bottom !IMPORTANT;
        }
        .vertical-text div {
            transform: translate(5px, 0px) rotate(180deg);
            width: 5px;
            line-height: 13px;
            margin: 0px 10px 0px 0px;
        }
        #canvas-td {
            background: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' preserveAspectRatio='none' viewBox='0 0 10 10'> <path d='M0 0 L0 10 L10 10' fill='black' /></svg>");
            background-repeat:no-repeat;
            background-position:center center;
            background-size: 100% 100%, auto;
        }
    }
</style>