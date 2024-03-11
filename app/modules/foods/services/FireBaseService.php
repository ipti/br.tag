<?php

// require 'vendor/autoload.php';

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
      /*   $this->firestoreClient = new FirestoreClient('31058192374', 'AIzaSyAf7EefR1VXllpmE60kiQwl6xictSDO-Tc ', [
            'database' => '(default)',
        ]); */
    }

    public function createNotice()
    {
        $this->firestoreClient = new FirestoreClient('br-nham-agrigultor', 'AIzaSyAf7EefR1VXllpmE60kiQwl6xictSDO-Tc ', [
            'database' => '(default)',
        ]);
        $collection = 'Edital';

        $document = new FirestoreDocument;
        $document->fillValues([
            'NomedoEdital' => 'Edital teste',
            'Alimento' => 'Batata',
        ]);

        $this->firestoreClient->addDocument($collection, $document,);
    }

}
