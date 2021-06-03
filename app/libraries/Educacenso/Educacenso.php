<?php
class Educacenso
{
    private $registers = [];

    public function __construct()
    {
        include __DIR__ . '/Register00.php';
        include __DIR__ . '/Register10.php';
        include __DIR__ . '/Register20.php';
        include __DIR__ . '/Register30.php';
        include __DIR__ . '/Register40.php';
        include __DIR__ . '/Register50.php';
        include __DIR__ . '/Register60.php';
        include __DIR__ . '/Register99.php';
    }

    private function register00()
    {
        $this->registers['00'] = Register00::export();
    }

    private function register10()
    {
        $this->registers['10'] = Register10::export();
    }

    private function register20()
    {
        $this->registers['20'] = Register20::export();
    }

    private function register30()
    {
        $this->registers['30'] = Register30::export();
    }

    private function register40()
    {
        $this->registers['40'] = Register40::export();
    }

    private function register50()
    {
        $this->registers['50'] = Register50::export();
    }

    private function register60()
    {
        $this->registers['60'] = Register60::export();
    }

    private function register99()
    {
        $this->registers['99'] = Register99::export();
    }

    public function exportar()
    {
        $this->register00();
        $this->register10();
        $this->register20();
        $this->register30();
        $this->register40();
        $this->register50();
        $this->register60();
        $this->register99();

        $lines = [];

        foreach ($this->registers as $registerType) {
            foreach ($registerType as $register) {
                array_push($lines, $register);
            }
        }

        return implode("\n", $lines);
    }
}