<?php
    require_once 'vendor/autoload.php'; 

class ClassroomSEDDataSource extends SedDataSource
{
    
    public function getClassroomRA($school_id, $year)
    {
        $promise = $this->client->requestAsync('GET', '/ncaapi/api/Aluno/ConsultaRA', [
            'headers' => [
                'content-type' => 'application/json',
                'Authorization' => 'Bearer YYlW35bvTjLdVc+j6ozpvBFHy/t8PLTGb4i6oeMwqgOOlO0XqSSbNKBGlVXzJbCMhkjugFNWg7KFwPGlUY0TZNgmi51SrVlPgj4vrKmiF6g0vvx5NYfLR0uVM5RpzKtZ2RpsAzdLpSHks7AdAv/bofUMpGDWHHe9MxjpDSWcLg39G1uVEUXtIaGFVEdQzvbJ+UWJ4fW4ZEkwxouk/NTYj48pCqI+ckRZTPP2uNmgpSW64ZniTC1TYKTGuMKQIThw0tJxsbWrfXK8AiUT8EHpff62udurJxfvdComB8hfVhDLbFbvm3WCZQLCNN86AKnc9+gM6wedWNIFu4EKkBV0ZQLF2DIHXkpFo7LSn8FGBhnZmVhwhI4TVlxyFSkrdg76eIZoaXEhBB5CG0tTOgeUxA=='
            ],
            'body' => json_encode([
                "inAnoLetivo" => $year,
	            "inCodEscola" => $school_id
            ])
        ]); 
        
        return $promise;
    }   

    public function getAllClassroomsRA($classrooms){
        
        $promises = [];
        foreach (array_slice($classrooms, 0, 5) as $key => $classroom) {
            $promises[] = $this->getClassroomRA($classroom->name, $classroom->birthday, $classroom->filiation_1);
        }
        
        $data = GuzzleHttp\Promise\Utils::all($promises)->then(function (array $responses){
            $data = [];
            foreach ($responses as $response) {
                 $data[] = json_decode($response->getBody()->getContents(), true);
            }
            return $data;
        })->wait(true);

        return $data;
    }
}