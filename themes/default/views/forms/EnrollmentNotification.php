<?php
/* @var $this ReportsController */
/* @var $report mixed */
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/EnrollmentNotification/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>

<div class="pageA4V">
    <?php $this->renderPartial('head'); ?>
        
        <script type="text/javascript">
            /*<![CDATA[*/
            jQuery(function ($) {
                jQuery.ajax({'type': 'GET',
                    'data': {'enrollment_id':<?php echo $enrollment_id;?>},
                    'url': '<?php echo Yii::app()->createUrl('forms/getEnrollmentNotificationInformation') ?>',
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
    <br><br>
                <span style="text-decoration:underline;font-size: 16px;text-align: center;display: block">COMUNICADO</span>
                <br><br>
                <div style="font-size:14px;">
                Comunicamos que
                <?php 
                    if ($gender == '1'){
                        echo "o aluno";
                    } else {
                        echo "a aluna";
                    }
                ?>

                <span class="name" style="font-weight: bold"></span> 
                matriculou-se no(a) 
                <?php echo $school->name?>, 
                no ano de 
                <span class="enrollment_date"></span>, 
                no turno
                <?php
                    if ($shift == 'M'){
                        echo "matutino, ";
                    } else if ($shift == 'T'){
                        echo "vespertino, ";
                    } else {
                        echo "[não informado], ";
                    }
                ?>
                     com o professor(a) ___________________________________________________________________.
                </div>
                <br><br>
                <span class="pull-right">
                    <?=$school->edcensoCityFk->name?>(<?=$school->edcensoUfFk->acronym?>), <?php echo date('d') . " de " . yii::t('default', date('F')) . " de " . date('Y') . "." ?>
                </span>

            <br><br>
        <?php $this->renderPartial('footer'); ?>
</div>

<style>
    @media print{
        #report {
            margin: 0 50px 0 100px;
        }
    }
</style>