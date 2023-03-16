<?php
require 'vendor/autoload.php'; 

class StudentSEDDataSource
{

    public function getStudentRA($name, $birthday, $mothersName)
    {
        $client = new \GuzzleHttp\Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://homologacaointegracaosed.educacao.sp.gov.br',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);

        $promise = $client->requestAsync('GET', '/ncaapi/api/Aluno/ConsultaRA', [
            'headers' => [
                'content-type' => 'application/json',
                'Authorization' => 'Bearer YYlW35bvTjLdVc+j6ozpvBFHy/t8PLTGb4i6oeMwqgOQVXjRm7y5tyQHVYDROC03nGm8zB+Oh295rqa+CG1jz/ZgbAcYI6hQFP6fBEBv3XgS9jeRHRTQ+UFTqCtiZjPCJ2HOU8GgkoRG9qRIRTQSVsE/ncDzJe/0PQbKWkDQvBSpryHF+u9LymeCtvUj+BDXlrUup3cW5LX4kthTuEaZ3sxv4f5weWCbZBXKfICmaf2K/XfaLDIHORv13oSzdVQ4Pwq+oIKf8+QdCd+CRTidHdC8rkiRO0+R+mtQyK200i23GqHgn/OgdSWOnpsOZwS+0lR+RCUmEFHCd2bXz309WogYOZU1R8I1/6RJ7/mbqb+fgrJ/abOrvYYOgp0sm40SuD0VmQ9lBIM3Or9UC3Qotg=='
            ],
            'body' => json_encode([
                "inNomeAluno" => $name,
                "inNomeMae" => $mothersName,
                "inDataNascimento" => $birthday
            ])
        ]);
        
        return $promise;
    }   

    public function getAllStudentsRA($students){
        $promises = [];
        foreach ($students as $key => $student) {
            $promises[] = $this->getStudentRA($student->name, $student->birthday, $student->filiation_1);
        }

        $data = []; 

        // GuzzleHttp\Promise\Utils::all(function() use ($promises)->then(function (array $responses) {
        //     foreach ($responses as $response) {
        //          $data[] = json_decode($response->getBody(), true);
        //          // Do something with the profile.
        //     }
        // }))->wait();

        return $data;
    }
}

?>