<?php
use PHPUnit\Framework\TestCase;
require_once 'C:\br.tag\app\modules\sedsp\models\School\InEscola.php';
require_once 'C:\br.tag\app\modules\sedsp\usecases\School\GetEscolasFromSEDUseCase.php';


class GetEscolasFromSEDUseCaseTest extends TestCase
{
    public function testExec()
    {

        $getEscolasUseCaseMock = $this->createMock(GetEscolasFromSEDUseCase::class);

        $getEscolasUseCaseMock->expects($this->any())
            ->method('exec')
            ->willReturn(false);

        $result = $getEscolasUseCaseMock->exec(new InEscola("CEMPRI PROFESSORA MARTA HELENA DA SILVA ARAUJO", null, null, null)); 
        $this->assertEquals(false, $result);
        
    }
}
