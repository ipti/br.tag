<?php
use PHPUnit\Framework\TestCase;
require_once 'C:\br.tag\app\modules\sedsp\models\School\InEscola.php';
require_once 'C:\br.tag\app\modules\sedsp\usecases\School\GetEscolasFromSEDUseCase.php';


class GetEscolasFromSEDUseCaseTest extends TestCase
{
    public function testExec()
    {

        $getEscolasUseCaseMock = $this->createMock(SchoolSEDDataSource::class);

        $getEscolasUseCaseMock->expects($this->any())
            ->method('getSchool')
            ->willReturn(new OutEscola());

        $usecase = new GetEscolasFromSEDUseCase($getEscolasUseCaseMock);

        $result = $usecase->exec(new InEscola("CEMPRI PROFESSORA MARTA HELENA DA SILVA ARAUJO", null, null, null));

        $this->assertEquals(false, $result);
        
    }
}
