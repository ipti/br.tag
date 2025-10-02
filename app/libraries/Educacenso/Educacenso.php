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
        include __DIR__ . '/RegisterIdentification.php';
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

    private function register30($year, $withoutCertificates)
    {
        $this->registers['30'] = Register30::export($year, $withoutCertificates);
    }

    private function register40()
    {
        $this->registers['40'] = Register40::export();
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

    private function registerIdentification($withoutCertificates)
    {
        return  RegisterIdentification::export($withoutCertificates);
    }

    public function exportar($year, $withoutCertificates)
    {
        $this->register00($year);
        $this->register10($year);
        $this->register20($year);
        $this->register30($year, $withoutCertificates);
        $this->register40();
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

    public function exportarIdentification($withoutCertificates)
    {
        $registerForIdentification = $this->registerIdentification($withoutCertificates);

        $lines = [];
        foreach ($registerForIdentification as $register) {
            array_push($lines, $register);
        }

        return implode("\n", $lines);
    }
}
