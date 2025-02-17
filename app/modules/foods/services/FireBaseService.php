<?php

use Ramsey\Uuid\Uuid;
use MrShan0\PHPFirestore\FirestoreClient;
use MrShan0\PHPFirestore\FirestoreDocument;
// Optional, depending on your usage
use MrShan0\PHPFirestore\Fields\FirestoreTimestamp;
use MrShan0\PHPFirestore\Fields\FirestoreArray;
use MrShan0\PHPFirestore\Fields\FirestoreBytes;
use MrShan0\PHPFirestore\Fields\FirestoreGeoPoint;
use MrShan0\PHPFirestore\Fields\FirestoreObject;
use MrShan0\PHPFirestore\Fields\FirestoreReference;
use MrShan0\PHPFirestore\Attributes\FirestoreDeleteAttribute;


class FireBaseService
{

    public $firestoreClient;

    public function __construct()
    {
        $this->firestoreClient = new FirestoreClient('br-nham-agrigultor', getenv("PWD_FIREBASE"), [
            'database' => '(default)',
        ]);
        $this->firestoreClient->authenticator()->signInEmailPassword('usertag@thp.org.br', '123456');
    }

    public function createNotice()
    {
        $collection = 'Edital';

        $document = new FirestoreDocument;
        $document->fillValues([
            'NomedoEdital' => 'Edital teste chagas 2',
            'Alimento' => 'Batata chagas 2',
        ]);

        $this->firestoreClient->addDocument($collection, $document);
    }

    public function createFarmerRegister($name, $cpf, $phone, $groupType, $foodsRelation) {
        $collection = 'farmer_register';
        $uuid = Uuid::uuid4();
        $cpf = mask($cpf, '###.###.###-##');

        $document = new FirestoreDocument;
        $document->fillValues([
            'name' => $name,
            'cpf' => $cpf,
            'phone' => $phone,
            'groupType'=> $groupType,
            'id'=> $uuid->toString(),
        ]);

        $this->firestoreClient->addDocument($collection, $document, $uuid->toString());

        $this->createFarmerFoods($foodsRelation, $uuid->toString());

        return $uuid->toString();
    }

    public function updateFarmerRegister($farmerId, $name, $cpf, $phone, $groupType, $foodsRelation) {
        $collection = 'farmer_register';
        $documentPath =  $collection . '/' . $farmerId;
        $cpf = mask($cpf, '###.###.###-##');

        $this->firestoreClient->updateDocument($documentPath, [
            'cpf' => $cpf,
            'name' => $name,
            'phone' => $phone,
            'groupType'=> $groupType,
        ]);

        $this->deleteFarmerFoods($farmerId);
        $this->createFarmerFoods($foodsRelation, $farmerId);
    }

    public function deleteFarmerRegister($farmerId) {
        $collection = 'farmer_register';
        $documentPath =  $collection . '/' . $farmerId;

        $farmerRegister = $this->firestoreClient->getDocument($documentPath);

        try {
            $userField = $farmerRegister->get('user');
        } catch (\MrShan0\PHPFirestore\Exceptions\Client\FieldNotFound $e) {
            $userField = "";
        }

        if($userField == "") {
            $this->firestoreClient->deleteDocument($documentPath);
            $this->deleteFarmerFoods($farmerId);
        }
    }

    public function hasUserField($documentPath) {
        $farmerRegister = $this->firestoreClient->getDocument($documentPath);

        try {
            $userField = $farmerRegister->get('user');
        } catch (\MrShan0\PHPFirestore\Exceptions\Client\FieldNotFound $e) {
            $userField = "";
        }

        if($userField == "") {
            return false;
        }
        return true;
    }

    public function getFarmerRegister($cpf) {
        $farmerRegisters = $this->firestoreClient->listDocuments('farmer_register');
        $foundFarmer = [];
        $cpf = mask($cpf, '###.###.###-##');

        foreach ($farmerRegisters['documents'] as $farmerRegister) {
            if ($farmerRegister->get('cpf') == $cpf) {
                $newcpf = preg_replace("/\D/", "", $farmerRegister->get('cpf'));
                $foundFarmer = array(
                    "id" => $farmerRegister->get("id"),
                    "name" => $farmerRegister->get('name'),
                    "groupType" => $farmerRegister->get('groupType'),
                    "cpf" => $newcpf,
                    "phone" => $this->getFieldOrDefault($farmerRegister, 'phone'),
                    "user" => $this->getFieldOrDefault($farmerRegister, 'user')
                );

            }
        }

        return $foundFarmer;
    }

    private function getFieldOrDefault($document, $field, $default = "") {
        try {
            return $document->get($field);
        } catch (\MrShan0\PHPFirestore\Exceptions\Client\FieldNotFound $e) {
            return $default;
        }
    }

    public function createFarmerFoods($foods, $farmerId) {
        $collectionFoods = 'farmer_foods';

        foreach ($foods as $foodData) {
            $uuid = Uuid::uuid4();
            $document = new FirestoreDocument;
            $document->fillValues([
                'name' => $foodData['foodDescription'],
                'amount' => $foodData['amount'],
                'measurementUnit' => $foodData['measurementUnit'],
                'notice' => $foodData['noticeId'],
                'farmer_id'=> $farmerId,
                'id' => $uuid->toString(),
            ]);

            $this->firestoreClient->addDocument($collectionFoods, $document, $uuid->toString());
        }
    }

    public function deleteFarmerFoods($farmerId) {
        $collectionFoods = 'farmer_foods';

        $farmerFoods = $this->firestoreClient->listDocuments('farmer_foods');

        foreach ($farmerFoods['documents'] as $farmerFood) {
            if ($farmerFood->get('farmer_id') == $farmerId) {
                $documentPath =  $collectionFoods . '/' . $farmerFood->get('id');
                $this->firestoreClient->deleteDocument($documentPath);
            }
        }
    }

    private function addCategoryUrl(&$requestItems) {

        $obj = [
            'Cereais e derivados' => "cereais_e_derivados",
            'Verduras' => "verduras_hortalicas_e_derivados",
            'Frutas e derivados' => "frutas_e_derivados",
            'Gorduras e óleos' => "gorduras_e_oleos",
            'Pescados e frutos do mar' => "pescados_e_frutos_do_mar",
            'Carnes e derivados' => "carnes_e_derivados",
            'Leite e derivados' => "leites_e_derivados",
            'Bebidas (alcoólicas e não alcoólicas)' => "bebidas_alcoolicas_e_nao_alcoolicas",
            'Ovos e derivados' => "ovos_e_derivados",
            'Produtos açucarados' => "produtos_acucarados",
            'Miscelâneas' => "miscelaneas",
            'Outros alimentos industrializados' => "outros_alimentos_industrializados",
            'Alimentos preparados' => "alimentos_preparados",
            'Leguminosas e derivados' => "leguminosas_e_derivados",
            'Nozes e sementes' => "nozes_e_sementes"
        ];

        foreach($requestItems as &$item) {
            $item["accepted_amount"] = 0;
            $item["delivered_amount"] = 0;
            $item["send_amount"] = 0;
            $item["amount"] = intval($item["amount"]);
            $item["imageUrl"] = "https://firebasestorage.googleapis.com/v0/b/br-nham-agrigultor.appspot.com/o/food_image%2F" . $obj[$item['category']] . ".png?alt=media&token=" . getenv("TOKEN_FIREBASE");
        }
        unset($item);
    }

    public function createFoodRequest($requestTitle, $requestSchoolNames, $farmersCpfs, $requestItems) {
        $collection = 'food_request';
        $uuid = Uuid::uuid4();
        $this->addCategoryUrl($requestItems);

        $maskedCpfs = array_map(function($cpf) {
            return mask($cpf, '###.###.###-##');
        }, $farmersCpfs);

        $document = new FirestoreDocument;
        $document->setString('title', $requestTitle);
        $document->setString('id', $uuid->toString());
        $document->setArray('schools', $requestSchoolNames);
        $document->setArray('farmers', $maskedCpfs);

        $map = array();

        foreach ($requestItems as $index => $value) {
            $map[$index + 1] = $value;
        }

        $foodsRequestedArray = array();
        foreach ($map as $key => $value) {
            $foodsRequestedArray[] = new FirestoreObject($value);
        }

        $document->setArray('foods_requested', new FirestoreArray($foodsRequestedArray));

        $this->firestoreClient->addDocument($collection, $document, $uuid->toString());

        return $uuid->toString();
    }

}
function mask($val, $mask) {
    $maskared = '';
    $k = 0;
    for($i = 0; $i<=strlen($mask)-1; $i++) {
        if($mask[$i] == '#') {
            if(isset($val[$k])) {
                $maskared .= $val[$k++];
            }
        } else {
            if(isset($mask[$i])) {
                $maskared .= $mask[$i];
            }
        }
    }
    return $maskared;
}
