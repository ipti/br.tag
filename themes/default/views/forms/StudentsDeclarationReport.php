<?php
/* @var $this ReportsController */
/* @var $report Mixed */
/* @var $school SchoolIdentification*/
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/js/reports/StudentsDeclarationReport/_initialization.js', CClientScript::POS_END);

$this->setPageTitle('TAG - ' . Yii::t('default', 'Reports'));
$school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);
?>

<div class="pageA4V">
    <?php $this->renderPartial('head'); ?>
        <p style="margin: 0 auto; text-align: justify; width:600px; font-size: 14px">Declaro para os devidos fins de direito, que o(a) aluno(a): <b><?php echo $report['name'] ?></b>,
            nascido(a) em:  <b><?php echo $report['birthday'] ?></b>, na cidade de: <b><?php echo $report['birth_city'] ?></b>, UF: <b><?php echo $report['birth_uf'] ?></b>,
            Registro Civil nº: 
            <?php if ($report['cc'] == 1): ?><b><?php echo $report['cc_number'] ?></b>, Livro: <b><?php echo $report['cc_book'] ?></b>, Folhas: <b><?php echo $report['cc_sheet'] ?></b>
            <?php else: ?><b><?php echo $report['cc_new'] ?></b><?php endif; ?>
            filho(a) do Sr. <b><?php echo $report['father'] ?></b> e da Srª. <b><?php echo $report['mother'] ?></b>, 
            matriculado(a) no <b><?php echo $report['stage'] ?></b>, turma "<b><?php echo $report['classroom'] ?></b>" turno 
            <b><?php echo(($report['turn'] == 'M') ? 'Matutino' : (($report['turn'] == 'T') ? 'Vespertino' : (($report['turn'] == 'N') ? 'Noturno' : '________'))); ?></b>
            no ano de <b><?php echo $report['year'] ?></b>.
            <br><br>
            Encontra-se na situação:
            <br>
            (_) Aprovado(a) para cursar o _____ Ano do Ensino Fundamental.
            <br>
            (_) Reprovado(a) no ano letivo de <?php echo $report['year'] ?> no <?php echo $report['stage'] ?>.
            <br>
            (_) Cancelou a matricula em ___/___/_____ no <?php echo $report['stage'] ?>.
            <br>
            (_) Esta cursando regularmente o <?php echo $report['stage'] ?>.
            <br>
            (_) Abandonou os estudos em ___/___/_____ no <?php echo $report['stage'] ?>.
            <br>
            (_) Cursou o <?php echo $report['stage'] ?> em  <?php echo $report['year'] ?>.
            <br>
            (_) Esta cursando regularmente a Pré-Escola.
            <br>
            (_) Solicitou transferência em ___/___/_____ para <br>
            __________________________________________________________________________________________________.
            <br>
            <br>
            Procedências anteriores:
            <br>___________________________________________________________________________________________________
            <br>___________________________________________________________________________________________________
            <br>___________________________________________________________________________________________________
            <br>___________________________________________________________________________________________________
        </p>
        <br>
        <br>
        <br>
        <br>
        <p style="margin: 0 auto; text-align: right; width:600px">
            <?=$school->edcensoCityFk->name?>(<?=$school->edcensoUfFk->acronym?>), <?php echo date('d') . ' de ' . yii::t('default', date('F')) . ' de ' . date('Y') . '.' ?>
        </p>
        <br>
        <br>
        <p style="margin: 0 auto; text-align: center; width:600px">
        _______________________________________________________<BR>
        <b>ASSINATURA DO DIRETOR(A)/SECRETÁRIO(A)</b>
        </p>
        <?php $this->renderPartial('footer'); ?>
</div>