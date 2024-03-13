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
        $collectionFoods = 'farmer_foods';
        $uuid = Uuid::uuid4();

        $document = new FirestoreDocument;
        $document->fillValues([
            'name' => $name,
            'cpf' => $cpf,
            'phone' => $phone,
            'groupType'=> $groupType,
            'id'=> $uuid->toString(),
        ]);

        $this->firestoreClient->addDocument($collection, $document);

        foreach ($foodsRelation as $foodData) {
            $document = new FirestoreDocument;
            $document->fillValues([
                'name' => $foodData['foodDescription'],
                'amount' => $foodData['amount'],
                'measurementUnit' => $foodData['measurementUnit'],
                'id'=> $uuid->toString(),
            ]);

            $this->firestoreClient->addDocument($collectionFoods, $document);
        }
    }

    public function getFarmerRegister() {
        $collections = $firestoreClient->listDocuments('farmer_register');

        return $collections;
    }

}
