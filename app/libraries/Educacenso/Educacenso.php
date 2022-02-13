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

    private function register00($year)
    {
        $this->registers['00'] = Register00::export($year);
    }

    private function register10($year)
    {
        $this->registers['10'] = Register10::export($year);
    }

    private function register20($year)
    {
        $this->registers['20'] = Register20::export($year);
    }

    private function register30($year)
    {
        $this->registers['30'] = Register30::export($year);
    }

    private function register40($year)
    {
        $this->registers['40'] = Register40::export($year);
    }

    private function register50($year)
    {
        $this->registers['50'] = Register50::export($year);
    }

    private function register60($year)
    {
        $this->registers['60'] = Register60::export($year);
    }

    private function register99()
    {
        $this->registers['99'] = Register99::export();
    }

    public function exportar($year)
    {
        $this->register00($year);
        $this->register10($year);
        $this->register20($year);
        $this->register30($year);
        $this->register40($year);
        $this->register50($year);
        $this->register60($year);
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