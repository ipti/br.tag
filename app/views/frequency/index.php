<?php
/* @var $this ClassesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Frequency',
);

$this->menu = array(
    array('label' => 'Create Classes', 'url' => array('create')),
    array('label' => 'Manage Classes', 'url' => array('admin')),
);

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'classes-form',
    'enableAjaxValidation' => false,
    'action' => CHtml::normalizeUrl(array('frequency/save')),
        ));
?>

<?php echo $form->errorSummary($model); ?>

<div class="row-fluid">
    <div class="span12">
        <h3 class="heading-mosaic"><?php echo Yii::t('default', 'Frequency'); ?><span> | Marcar apenas faltas.</h3>  
        <div class="buttons">
            <a id="print" class='btn btn-icon glyphicons print hidden-print'><?php echo Yii::t('default', 'Print') ?><i></i></a>
            <a id="save" class='btn btn-icon btn-primary glyphicons circle_ok hidden-print'><?php echo Yii::t('default', 'Save') ?><i></i></a>
        </div>
    </div>
</div>


<div class="innerLR">

    <div class="filter-bar margin-bottom-none">
        <div>
            <?php echo CHtml::label(yii::t('default', 'Classroom'), 'classroom', array('class' => 'control-label')); ?>
            <?php
            echo CHtml::dropDownList('classroom', '', CHtml::listData(Classroom::model()->findAll('school_inep_fk=' . Yii::app()->user->school, array('order' => 'name')), 'id', 'name'), array(
                'key' => 'id',
                'class' => 'select-search-on',
                'prompt' => 'Selecione a turma',
                'ajax' => array(
                    'type' => 'POST',
                    'url' => CController::createUrl('frequency/getDisciplines'),
                    'update' => '#disciplines',
            )));
            ?>
        </div>

        <div>    
            <?php echo CHtml::label(yii::t('default', 'Month'), 'month', array('class' => 'control-label')); ?>
            <?php
            echo CHtml::dropDownList('month', '', array(1 => 'Janeiro',
                2 => 'Fevereiro',
                3 => 'Março',
                4 => 'Abril',
                5 => 'Maio',
                6 => 'Junho',
                7 => 'Julho',
                8 => 'Agosto',
                9 => 'Setembro',
                10 => 'Outubro',
                11 => 'Novembro',
                12 => 'Dezembro'), array(
                'key' => 'id',
                'class' => 'select-search-on',
                'prompt' => 'Selecione o mês',
            ));
            ?>
        </div>
        <div>
            <?php echo CHtml::label(yii::t('default', 'Disciplines'), 'disciplines', array('class' => 'control-label')); ?>
            <?php
            echo CHtml::dropDownList('disciplines', '', array(), array(
                'key' => 'id',
                'class' => 'select-search-on',
                'prompt' => 'Todas as disciplinas',
            ));
            ?>
        </div>
        <div>
            <a id="classesSearch" class='btn btn-icon btn-small btn-primary glyphicons search'><?php echo Yii::t('default', 'Search') ?><i></i></a>
        </div>



    </div>
    <div class="widget" id="widget-frequency" style="display:none; margin-top: 8px;">
        <div class="widget-head">
            <h4 class="heading"><span id="month_text"></span> - <span id="discipline_text"></span></h4>
        </div>
            <table id="frequency" class="table table-bordered table-striped">
                <thead>
                </thead>
                <tbody>
                    <tr>
                        <td class="center">1</td>

                    </tr>
                </tbody>
            </table>
    </div>



    <?php $this->endWidget(); ?>
</div>



<script>
    $('#month, #disciplines, #classroom').on('change', function(){
        $('#frequency').hide();
    })
    $('#classroom').on('change', function(){
        $('#disciplines').val('').trigger('change');
    });
    $('#classesSearch').on('click', function(){
        jQuery.ajax({
            'type':'POST',
            'url':'<?php echo Yii::app()->baseUrl; ?>/index.php?r=frequency/getClasses',
            'cache':false,
            'data':jQuery('#classroom').parents("form").serialize(),
            'success':function(data){
                var data = jQuery.parseJSON(data);
<?php //@done s2 - não mostrar "Selecione a disciplina" como disciplina ?>
                console.log(data['days'] == undefined);
                if(data['days'] == undefined) {
                    $('#frequency > thead').html('<tr><th class="center">Não há aulas desta matéria.</th></tr>');
                    $('#frequency > tbody').html('');
                    $('#widget-frequency').show();
                    $('#frequency').show();
                    return true;
                }
                console.log(data['days']);
                $('#frequency > thead').html('<tr><th class="center">Alunos</th></tr>');
                $('#frequency > tbody').html('');
                
                var month = $('#month').val();
                var year = new Date().getFullYear();
                
                var maxDays = new Date(year, month, 0).getDate();
                
                for(var day=1; day <= maxDays; day++){
                    //MM DD YYYY
                    var date = new Date(month+" "+day+" "+year);
                    var weekDay = date.getDay();
                    if(data['days'][weekDay][0] != "0" ){
                        var thead = '<th class="center">'+day+'<br>';
                        $(data['days'][weekDay]).each(function(i, e){
                             var given = data['instructorFaults'] == undefined || data['instructorFaults'][day] == undefined || data['instructorFaults'][day][e-1] == undefined;

                             if(data['days'][weekDay][i] != "" ){
                                thead += '<span>';
                                thead += '<input id="day['+day+']['+e+']" name="day['+day+']['+e+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;"'+(given ? ' ' : ' checked ')+'>';
                                thead += '</span>';
                            }
                        });
                        thead += '</th>';
                        $('#frequency > thead > tr').append(thead);
                    }
                    
                }
                
                $(data['students']['name']).each(function(j, name){
                    var tbody = "<tr>";
                    tbody += '<td class="frequency-list">'+name+'</td>';
                    for(var day=1; day <= maxDays; day++){
                        
                        var date = new Date(month+" "+day+" "+year);
                        var weekDay = date.getDay();
                        
                        if(data['days'][weekDay][0] != "0" ){
                            tbody += '<td class="center">';
                            $(data['days'][weekDay]).each(function(i, e){
                                var fault = data['faults'] && data['faults'][day] != undefined && data['faults'][day][e] != undefined;
                                if (fault){
                                    fault = false;
                                    $(data['faults'][day][e]).each(function(shc, stId){
                                        fault = fault || (data['students']['id'][j] == stId);
                                    });
                                }
<?php //@done s2 - inabilitar checkbox quando vier checado  ?>
                                if(data['days'][weekDay][i] != "" ){
                                   tbody += '<span>';
                                    tbody += '<input id="day[' + day + '][' + e + ']" name="student[' + data['students']['id'][j] + '][' + day + '][' + e + ']" class="student-fault checkbox" type="checkbox" value="1" style="opacity: 100;"' + (fault ? ' checked disabled' : ' ') + '>';
                                    tbody += '</span>';
                                }
                            });
                            tbody += '</td>';
                        }
                    }
                    tbody += "</tr>";
                    $('#frequency > tbody').append(tbody);
                });

                $('input.instructor-fault:checked').each(function(i, e) {
                    var id = $(this).attr('id');
                    var students = $("input.student-fault[id='" + id + "']");
                    students.attr('disabled', 'disabled');

                })

                $('#widget-frequency').show();
                $('#frequency').show();
                $('#month_text').html($('#month').find('option:selected').text());
                $('#discipline_text').html($('#disciplines').find('option:selected').text());
            }});
    });

<?php //@done s2 - desabilitar a coluna ao clicar em falta do professor ?>
    $(document).on('click', '.instructor-fault', function() {
        var id = $(this).attr('id');
        var students = $("input.student-fault[id='" + id + "']");
<?php //@done s2 - reabilitar apenas os que não estão checados  ?>
        $(students).each(function(i, e) {
            var student = $(e);
            if (student.attr('disabled') == 'disabled' && !student.attr('checked')) {
                student.removeAttr('disabled');
            } else {
                student.attr('disabled', 'disabled');
            }
        });
    });


    $(document).ready(function() {
        $('#frequency').hide();
    });

    $("#print").on('click', function() {
        window.print();
    });

    $("#save").on('click', function() {
        $("#classes-form").submit();
    });
    
    $('.heading-buttons').css('width', $('#content').width());
</script>