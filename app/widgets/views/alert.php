<div class="container-censo">
    <div class="modal-censo">
        <div class="modal-censo-container">
            <div class="row-fluid">
                <?php
                    foreach ($data as $key => $value) { ?>
                        <div class="span12">
                            <div class="alert alert-info">
                                <?= $value ?>
                            </div>
                        </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="side-censo" onclick="iconState(this)">
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div>
    </div>
</div>

<?php
Yii::app()->clientScript->registerScript('menucenso', "
    $(document).ready(function(){
        $('.side-censo').toggle(function(){
            $('.container-censo').stop(true).animate({width:400});
            $('.modal-censo').stop(true).animate({right:0});
            $('.side-censo').stop(true).animate({right:410});
        },function(){
            $('.container-censo').stop(true).animate({width:0});
            $('.modal-censo').stop(true).animate({right:-420});
            $('.side-censo').stop(true).animate({right:5});
        });
    });

    function iconState(x) {
        x.classList.toggle('change');
    }
", CClientScript::POS_END);
?>


