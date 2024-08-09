<?php

class SchoolRobots
{
    public AcceptanceTester $tester;
    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    public function school()
    {
        $this->tester->click("#addSchool");
    }

    public function name($name)
    {
        $this->tester->fillField("#nameSchool", $name);
    }

    public function administrativeDependency()
    {
        $this->tester->selectFromDropdown('select', 2);
    }

    public function codInep($codInep)
    {
        $this->tester->fillField("#codInep", $codInep);
    }

    public function regulation()
    {
        $this->tester->selectFromDropdown('select', 2);
    }

    public function linkedSchool()
    {
        $this->tester->checkOption('#SchoolIdentification_linked_army');
    }

}

?>
