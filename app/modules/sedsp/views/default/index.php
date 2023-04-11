<?php
/* @var $this DefaultController */

$this->setPageTitle('TAG - ' . Yii::t('default', 'SEDSP'));

$this->breadcrumbs = array(
    $this->module->id,
);
$themeUrl = Yii::app()->theme->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCssFile($themeUrl . '/css/template2.css');
$cs->registerCssFile(Yii::app()->request->baseUrl . '/sass/css/main.css');
?>

<div class="main">
    <div class="row-fluid">
        <div class="span12">
            <?php $this->widget(
                'zii.widgets.grid.CGridView',
                array(
                    'dataProvider' => $dataProvider,
                    'enablePagination' => false,
                    'enableSorting' => false,
                    'itemsCssClass' => 'js-tag-table tag-table table table-condensed
            table-striped table-hover table-primary table-vertical-center checkboxs',
                    'columns' => array(
                        array(
                            'name' => 'name'
                        ),
                        array
                        (
                            'header' => 'Ações',
                            'class' => 'CButtonColumn',
                            'template' => '{generate}',
                            'buttons' => array
                            (
                                'generate' => array
                                (
                                    'label' => 'Gerar RA',
                                    'options' => array("id" => 'data->primaryKey'),
                                    'click' => "function(e){
                                     e.preventDefault();
                                    var th=this;
                                   $.ajax({
                                        type:'POST',
                                        url:$(this).attr('href'),
                                        success:function(data) {
                                            $(th).html(data);
                                        },
                                        error: function(error) {
                                            const approved =  confirm('Aluno nao encontrado na SED, deseja envia-lo?');
                                            if(approved){
                                                console.log(error);
                                            }
                                        }
                                    });
                                    return false;
                              }
                     ",
                                    'url' => 'Yii::app()->controller->createUrl("GenRA",array("id"=>$data->primaryKey))',
                                ),
                            )


                        )
                    ),
                )
            ); ?>
        </div>
    </div>
</div>

<script>
    function create(element, id){
        $.ajax({
                type: 'POST',
                url: `/?r=sedsp/default/CreateRA&id=${id}`,
                success: function (data) {
                    $(element).html(data);
                }
            });
    }
    function generate(element, url){
        $.ajax({
                type: 'POST',
                url: url,
                success: function (data) {
                    $(element).html(data);
                },
                error: function (error) {
                    const approved = confirm('Aluno nao encontrado na SED, deseja envia-lo?');
                    if (approved) {
                        create(element, error.responseJSON.id);
                    }
                }
            });
    }
    $(document).ready(function () {
        $('.generate').click(function (event) {
            event.preventDefault();

            const element = this;
            const url = $(this).attr('href');

            generate(element, url);

            return false;
        });
    });
</script>