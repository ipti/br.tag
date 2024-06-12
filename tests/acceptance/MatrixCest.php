<?php

class MatrixCest
{
    public function _before(AcceptanceTester $tester)
    {
        $builder = new LoginBuilder();
        $login = $builder->buildCompleted();

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($login['user']);
        $robots->fieldPassword($login['secret']);
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
