<?php
require_once 'vendor/autoload.php';
require_once __DIR__ . '/../robots/LoginRobots.php';
require_once __DIR__ . '/../robots/MatrixRobots.php';
require_once __DIR__ . '/../builders/MatrixBuilder.php';
class MatrixCest
{
    public function _before(AcceptanceTester $tester)
    {
        $user = "admin";
        $secret = "p@s4ipti";

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($secret);
        $robots->submit();
        sleep(2);
    }

    // tests
    public function addMatrix(AcceptanceTester $teste)
    {
        sleep(5);
        $robots = new MatrixRobots($teste);
        $robots->pageMatrix();

        $builder = new MatrixBuilder();
        $dataMatrix = $builder->builderAddMatrix();

        $robots->stages($dataMatrix['stages']);
        $robots->disciplines($dataMatrix['disciplines']);
        $robots->workload($dataMatrix['workload']);
        $robots->credits($dataMatrix['credits']);
        $robots->btnAdd();

        return $dataMatrix;
    }
}
