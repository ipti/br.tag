<?php

class boxWidget extends CWidget
{
    public $color = 'box-blue-1';
    public $percent = 0;
    public $label;
    public $sideLabel;

    public function run()
    {
        $this->render('box', [
            'color' => $this->color,
            'percent' => $this->percent,
            'label' => $this->label,
            'sideLabel' => $this->sideLabel,
        ]);
    }
}
