<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Login';
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'login-form',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
));
$year = date('Y');
if(date('m')>=11){
$yearnear = $year+1;
}else{
$yearnear = $year-1;
}
$rightbrowser = FALSE;
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE){
    $browser = 'Microsoft Internet Explorer';
}elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE) {
    $browser = 'Google Chrome';
    $rightbrowser = TRUE;
}elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE) {
    $browser = 'Mozilla Firefox';
}elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE) {
    $browser = 'Opera';
}elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE) {
    $browser = 'Apple Safari';
}else {
    $browser = 'error'; //<-- Browser not found.
}


?>
<div id="login">
            <!-- Box -->
            <div class="form-signin">
                <h3><?php echo INSTANCE ?> - LOGIN</h3>
                <!-- Row -->
                <div class="row innerLR">
                    <!-- Column -->
                        <div class="col-md-7 border-right innerT innerB">
                            <div class="innerAll center">
                                <?php $this->widget('WidgetLoginCode');?>
                                <div class="btn-group btn-group-lg" role="group" aria-label="...">
                                    <button type="button" class="btn btn-default"><?php echo $year?></button>
                                    <button type="button" class="btn btn-default">2019</button>
                                </div>
                            </div>
                        </div>
                        <!-- // Column END -->
                        
                        <!-- Column -->
                        <div class="col-md-5 innerT">
                            <div class="innerAll center">
                                <div class="col-md-12">
                                    <img class="innerAll" src="<?php echo Yii::app()->theme->baseUrl; ?>/common/img/tag_login.png"/>
                                    <div class="separator bottom"></div>
                                    <div class="separator bottom"></div>
                                    <a href="" class="innerAll widget-stats margin-bottom-none">
                                        <span class="glyphicons coins">
                                            <img class="col-md-7" src= "https://pbs.twimg.com/profile_images/902901234289360896/o93K8Tch_400x400.jpg">
                                            <img class="col-md-5" src= "https://upload.wikimedia.org/wikipedia/commons/thumb/5/52/Bras%C3%A3o_de_Sergipe.svg/200px-Bras%C3%A3o_de_Sergipe.svg.png">
                                        </span>
                                        <div class="clearfix"></div>
                                        <span class="count label label-success">Apoio</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- // Column END -->
                </div>
                <!-- // Row END -->
                <?php if(!$rightbrowser){?>
                    <div style="text-align:right;color:red">Este site é melhor visualizado no Google Chrome. Você está utilizando o <?php echo $browser; ?></div>
                <?php }?>
            </div>
 </div>
<?php $this->endWidget(); ?>
