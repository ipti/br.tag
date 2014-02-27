<?php
/* @var $this ClassesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Classes',
);

$this->menu = array(
    array('label' => 'Create Classes', 'url' => array('create')),
    array('label' => 'Manage Classes', 'url' => array('admin')),
);

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'classes-form',
    'enableAjaxValidation' => false,
        ));
?>

<?php echo $form->errorSummary($model); ?>
<div class="row-fluid">
    <div class="span12">
        <div class="heading-buttons" data-spy="affix" data-offset-top="95" data-offset-bottom="0" class="affix">
            <div class="row-fluid">
                <div class="span8">
                    <h3><?php echo Yii::t('default', 'Classes'); ?><span> | <?php echo Yii::t('help', 'Classes subtitle') ?></span></h3>        
                </div>
            </div>
        </div>        
    </div>
</div>

<div class="innerLR">


    <div class="widget widget-tabs border-bottom-none">

        <div class="widget-head">
            <ul class="tab-classboard">
                <li id="tab-classboard" class="active" ><a class="glyphicons user" href="#classboard" data-toggle="tab"><i></i><?php echo Yii::t('default', 'Class Boards') ?></a></li>
            </ul>
        </div>

        <div class="widget-body form-horizontal">
            <div class="tab-content">
                <!-- Tab content -->
                <div class="tab-pane active" id="classboard">
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="control-group">
                                <?php echo CHtml::label(yii::t('default', 'Classroom'), 'classroom', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo CHtml::dropDownList('classroom', '', CHtml::listData(Classroom::model()->findAll('school_inep_fk=' . Yii::app()->user->school, array('order' => 'name')), 'id', 'name'), array(
                                        'key' => 'id',
                                        'class' => 'select-search-on',
                                        'prompt' => 'Selecione a turma',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('classes/getDisciplines'),
                                            'update' => '#disciplines',
                                    )));
                                    ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <?php echo CHtml::label(yii::t('default', 'Month'), 'month', array('class' => 'control-label')); ?>
                                <div class="controls">
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
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <a id="classesSearch" class='btn btn-icon btn-primary glyphicons search'><?php echo Yii::t('default', 'Search') ?><i></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <?php echo CHtml::label(yii::t('default', 'Disciplines'), 'disciplines', array('class' => 'control-label')); ?>
                                <div class="controls">
                                    <?php
                                    echo CHtml::dropDownList('disciplines', '', array(), array(
                                        'key' => 'id',
                                        'class' => 'select-search-on',
                                        'prompt' => 'Selecione a disciplina',
                                    ));
                                    ?>
                                </div>
                            </div> 
                        </div>
                    </div> <hr>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="widget">
                                <div class="widget-head">
                                    <h4 class="heading">Frequência: <span id="month_text">X</span> - <span id="discipline_text">Y</span></h4>
                                </div>
                                <div class="widget-body">
                                    <table id="frequency" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="center">Alunos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="center">1</td>
                                                
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
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
            'url':'/tag/index.php?r=classes/getClasses',
            'cache':false,
            'data':jQuery('#classroom').parents("form").serialize(),
            'success':function(data){
                //$('#frequency > thead > tr').append('<th>1</th><th>3</th><th>5</th><th>8</th><th>10</th><th>12</th><th>15</th>')
                var data = jQuery.parseJSON(data);
                
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
                             if(data['days'][weekDay][i] != "" ){
                                thead += '<span>';
                                thead += '<div id="uniform-undefined" class="checker">';
                                thead += '<input id="day['+day+']['+e+']" class="instructor-fault checkbox" type="checkbox" value="1" style="opacity: 100;">';
                                thead += '</div>';
                                thead += '</span>';
                            }
                        });
                        thead += '</th>';
                        $('#frequency > thead > tr').append(thead);
                    }
                    
                }
                
                $(data['students']['name']).each(function(j, name){
                    var tbody = "<tr>";
                    tbody += '<td class="center">'+name+'</td>';
                    for(var day=1; day <= maxDays; day++){
                        
                        var date = new Date(month+" "+day+" "+year);
                        var weekDay = date.getDay();
                        
                        if(data['days'][weekDay][0] != "0" ){
                            tbody += '<td class="center">';
                            $(data['days'][weekDay]).each(function(i, e){
                                if(data['days'][weekDay][i] != "" ){
                                   tbody += '<span>';
                                   tbody += '<div id="uniform-undefined" class="checker">';
                                   tbody += '<input id="day['+day+']['+e+']" class="student-fault checkbox" type="checkbox" value="1" style="opacity: 100;">';
                                   tbody += '</div>';
                                   tbody += '</span>';
                               }
                            });
                            tbody += '</td>';
                        }
                    }
                    tbody += "</tr>";
                    $('#frequency > tbody').append(tbody);
                });
                
                
                
                
                
                
                
                $('#frequency').show();
                $('#month_text').html($('#month').find('option:selected').text());
                $('#discipline_text').html($('#disciplines').find('option:selected').text());
            }});
    });
    
    $(document).ready(function(){
        $('#frequency').hide();
    })
    
    
</script>