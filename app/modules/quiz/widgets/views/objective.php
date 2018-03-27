<div class="row-fluid">
    <div class="span12">
        <div class="widget widget-scroll margin-bottom-none" data-toggle="collapse-widget" data-scroll-height="223px" data-collapse-closed="false">
            <div class="widget-head white-background">
                <h4 class="heading glyphicons nameplate">
                    <i></i> <?= $model->question->description ?>
                </h4>
            </div> <!-- .widget-head -->
            <div class="widget-body in" style="height: auto;">
                <div class="control-group">                
                    <?php 
                        
                        $options = $model->question->questionOptions;
                        $bigPortion = ceil(count($options) / 2);
                        $smallPortion = floor(count($options) / 2);

                        $column1 = array_slice($options, 0, $bigPortion);
                        $column2 = array_slice($options, $bigPortion, $smallPortion);
                        $letterIndex = range('a','z');
                    
                    ?>
                    <div class="controls margin-multiple">
                        <div class="span6">
                            <?php 
                                foreach ($column1 as $option) {
                                    $parsedId = str_replace(['[',']'],['_',''],$model->getIdentifier());
                                    echo CHtml::label(
                                        current($letterIndex) . ') ' . $option->description . CHtml::radioButton($model->getIdentifier(). '['.$option->id.'][response]', false, array('uid' => $parsedId . current($letterIndex), 'value' => $option->answer)), $model->getIdentifier() . current($letterIndex), 
                                        array('class' => 'radio')
                                    );

                                    if($option->complement == '1'){
                                        echo '<div class="option-complement" id="'. $parsedId . current($letterIndex).'">' . CHtml::textField($model->getIdentifier() . '['.$option->id.'][complement]', $model->answer->value, array('size' => 60, 'maxlength' => 150, 'disabled' => true)) .'</div>';
                                    }
                                    next($letterIndex);
                                }
                                ?>
                        </div> <!-- .span6 -->

                        <div class="span6">
                            <?php 
                                foreach ($column2 as $option) {
                                    $parsedId = str_replace(['[',']'],['_',''],$model->getIdentifier());
                                    echo CHtml::label(
                                        current($letterIndex) . ') ' . $option->description . CHtml::radioButton($model->getIdentifier() . '['.$option->id.'][response]', false, array('uid' => $parsedId . current($letterIndex), 'value' => $option->answer)), $model->getIdentifier() . current($letterIndex), 
                                        array('class' => 'radio')
                                    );

                                    if($option->complement == '1'){
                                        echo '<div class="option-complement" id="'. $parsedId . current($letterIndex).'">' . CHtml::textField($model->getIdentifier() . '['.$option->id.'][complement]', $model->answer->value, array('size' => 60, 'maxlength' => 150, 'disabled' => true)) .'</div>';
                                    }
                                    next($letterIndex);
                                }
                            ?>
                        </div> <!-- .span6 -->
                    </div> <!-- .controls -->
                </div> <!-- .control-group -->
            </div> <!-- .widget-body --> 
        </div> <!-- .widget -->
    </div> <!-- .span12 -->
</div> <!-- .row-fluid -->
