<?php

class DatePickerWidget
{
    public static function renderDatePicker($model, $attribute)
    {
        return [
            'model' => $model,
            'attribute' => $attribute,
            'options' => [
                'dateFormat' => 'dd/mm/yy',
                'changeYear' => true,
                'changeMonth' => true,
                'yearRange' => '1930:' . date('Y'),
                'showOn' => 'focus',
                'maxDate' => 0,
                'monthNamesShort' => [
                    'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                ],
                'dayNames' => [
                    'Domingo',
                    'Segunda-feira',
                    'Terça-feira',
                    'Quarta-feira',
                    'Quinta-feira',
                    'Sexta-feira',
                    'Sábado'
                ],
                'dayNamesShort' => ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                'dayNamesMin' => ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
            ],
            'htmlOptions' => [
                'id' => 'initial_date_picker',
                'readonly' => 'readonly',
                'style' => 'cursor: pointer;',
                'placeholder' => 'Clique aqui para escolher a data'
            ],
        ];
    }
}