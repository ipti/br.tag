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
        $this->firestoreClient = new FirestoreClient('br-nham-agrigultor', 'AIzaSyAf7EefR1VXllpmE60kiQwl6xictSDO-Tc', [
            'database' => '(default)',
        ]);
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

        $this->firestoreClient->deleteDocument($documentPath);
        $this->deleteFarmerFoods($farmerId);
    }

    public function getFarmerRegister($cpf) {
        $farmerRegisters = $this->firestoreClient->listDocuments('farmer_register');
        $foundFarmer = [];

        foreach ($farmerRegisters['documents'] as $farmerRegister) {
            if ($farmerRegister->get('cpf') == $cpf) {
                $foundFarmer = array(
                    "name" => $farmerRegister->get('name'),
                    "groupType" => $farmerRegister->get('groupType'),
                    "cpf" => $farmerRegister->get('cpf'),
                    "phone" => $farmerRegister->get('phone')
                );
            }
        }

        return $foundFarmer;
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

}
