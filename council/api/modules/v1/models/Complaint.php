<?php

namespace app\modules\v1\models;

use yii\mongodb\ActiveRecord;
use yii\web\UploadedFile;
use yii\base\Security;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use \DateTime;
use Yii;

class Complaint extends ActiveRecord
{
    public const SCENARIO_CITIZEN = 'CITIZEN';
    public const SCENARIO_CREATE = 'CREATE';
    public const SCENARIO_UPDATE = 'UPDATE';
    public const SCENARIO_FORMALIZE = 'FORMALIZE';
    public const SCENARIO_RESPONSE = 'RESPONSE';
    public const SCENARIO_FORWARD = 'FORWARD';

    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'complaint';
    }

    /**
     * @return array list of attribute names.
     * @var status integer | 1 - receive , 2 - analysis , 3 - forward , 9 - complete
     */
    public function attributes()
    {
        return ['_id', 'child', 'responsible', 'aggressor', 'address', 'status', 'place', 'was', 'type', 'type_complement', 'forwards', 'forward_date','receive_date','receive_user','denunciator','files'];
    }

    public function rules()
    {
        return [
            // Scenario Citizen
            [['child', 'aggressor', 'address', 'status','forward_date','place', 'forwards'], 'required', 'on' => self::SCENARIO_CITIZEN, 'message' => 'Campo obrigatório'],
            ['forward_date', 'date', 'on' => self::SCENARIO_CITIZEN, 'format' => 'php:Y-m-d H:i:s', 'message' => 'Data inválida'],

            // Scenario Create and Formalize
            [['child', 'responsible', 'address', 'status','forward_date', 'receive_date', 'type', 'place', 'forwards', 'receive_user'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_FORMALIZE], 'message' => 'Campo obrigatório'],
            [['forward_date','receive_date'], 'date', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_FORMALIZE], 'format' => 'php:Y-m-d H:i:s', 'message' => 'Data inválida'],
            [['files'], 'file', 'skipOnEmpty' => true, 'on' => [self::SCENARIO_CREATE, self::SCENARIO_FORMALIZE], 'extensions' => 'png, jpg, pdf, doc, docx, mp3, mp4, avi', 'maxFiles' => 4],
            
            // Scenario Update
            [['child', 'responsible', 'address', 'type'], 'required', 'on' => self::SCENARIO_UPDATE, 'message' => 'Campo obrigatório'],
            
            // Scenario Response and Forward
            [['forwards'], 'required', 'on' => [self::SCENARIO_RESPONSE, self::SCENARIO_FORWARD], 'message' => 'Campo obrigatório'],
            [['files'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf, doc, docx, mp3, mp4, avi', 'maxFiles' => 4, 'on' => [self::SCENARIO_RESPONSE, self::SCENARIO_FORWARD]],
            [['was','place'], 'required', 'on' => self::SCENARIO_FORWARD, 'message' => 'Campo obrigatório'],

            [['type_complement'], 'safe'],
        ];
    }

    public function beforeValidate(){
        switch ($this->scenario) {
            case self::SCENARIO_CREATE:
                $now = new DateTime (date('Y-m-d H:i:s'));
                $forwards = $this->forwards;
                $this->receive_date = $this->forward_date = $forwards[0]['date'] = $now->format('Y-m-d H:i:s');
                !empty($this->files) ? $forwards[0]['files'] = $this->files : null;
                $this->forwards = $forwards;
                $this->status = 2;
            break;
            case self::SCENARIO_FORMALIZE:
                $now = new DateTime (date('Y-m-d H:i:s'));
                $forwards = $this->forwards;
                $this->receive_date = $this->forward_date = $forwards[0]['date'] = $now->format('Y-m-d H:i:s');
                !empty($this->files) ? $forwards[0]['files'] = $this->files : null;
                $this->forwards = $forwards;
                $this->status = 2;
            break;
            case self::SCENARIO_FORWARD:
                $now = new DateTime (date('Y-m-d H:i:s'));
                $forwards = $this->forwards;
                $forwards[0]['date'] = $now->format('Y-m-d H:i:s');
                !empty($this->files) ? $forwards[0]['files'] = $this->files : null;
                $this->forwards = $forwards;
            break;
            case self::SCENARIO_RESPONSE:
                $now = new DateTime (date('Y-m-d H:i:s'));
                $forwards = $this->forwards;
                $forwards[0]['date'] = $now->format('Y-m-d H:i:s');
                !empty($this->files) ? $forwards[0]['files'] = $this->files : null;
                $this->forwards = $forwards;
            break;
            case self::SCENARIO_CITIZEN:
                $now = new DateTime (date('Y-m-d H:i:s'));
                $this->forward_date = $now->format('Y-m-d H:i:s');
                $this->place = 'Conselho';
                $this->status = 1;
            break;
        }
        return true;
    }

    public function beforeSave($insert){

        switch ($this->scenario) {
            case self::SCENARIO_CREATE:
                $forwardDate = new DateTime($this->forward_date);
                $this->forward_date = new UTCDateTime($forwardDate->getTimeStamp()*1000);
                $receiveDate = new DateTime($this->receive_date);
                $forwards = $this->forwards;
                end($forwards);
                $key = key($forwards);
                $this->receive_date = $forwards[$key]['date'] = new UTCDateTime($receiveDate->getTimeStamp()*1000);
                $this->forwards = $forwards;
            break;
            case self::SCENARIO_FORMALIZE:
                $forwardDate = new DateTime($this->forward_date);
                $this->forward_date = new UTCDateTime($forwardDate->getTimeStamp()*1000);
                $receiveDate = new DateTime($this->receive_date);
                $forwards = $this->forwards;
                end($forwards);
                $key = key($forwards);
                $this->receive_date = $forwards[$key]['date'] = new UTCDateTime($receiveDate->getTimeStamp()*1000);
                $this->forwards = $forwards;
            break;
            case self::SCENARIO_FORWARD:
                $forwards = $this->forwards;
                end($forwards);
                $key = key($forwards);
                $forwardDate = new DateTime($forwards[$key]['date']);
                $forwards[$key]['date'] = new UTCDateTime($forwardDate->getTimeStamp()*1000);
                $this->forwards = $forwards;
            break;
            case self::SCENARIO_RESPONSE:
                $forwards = $this->forwards;
                end($forwards);
                $key = key($forwards);
                $forwardDate = new DateTime($forwards[$key]['date']);
                $forwards[$key]['date'] = new UTCDateTime($forwardDate->getTimeStamp()*1000);
                $this->forwards = $forwards;
            break;
            case self::SCENARIO_CITIZEN:
                $forwardDate = new DateTime($this->forward_date);
                $this->forward_date = new UTCDateTime($forwardDate->getTimeStamp()*1000);
                
            break;
        }
        return true;
    }

    public function create($data){
        $this->load($data);

        if($this->validate()){
            $forwards = $this->forwards;
            if(isset($forwards[0]['files'])){
                if(!$this->upload()){
                    $this->addError('files', 'Erro ao realizar upload do(s) arquivo(s)');
                    return false;
                }
            }
            if($this->save(false)){
                return true;
            }

        }

        return false;
    }

    public function formalize($data){

        $cacheModel = $this->getAttributes();
        if(empty($cacheModel['forwards'])){
            $cacheModel['forwards'] = [];
        }
        $this->load($data);

        if($this->validate()){
            $forwards = $this->forwards;
            if(isset($forwards[0]['files'])){
                if(!$this->upload()){
                    $this->addError('files', 'Erro ao realizar upload do(s) arquivo(s)');
                    return false;
                }
                $forwards = $this->forwards;
            }
            array_push($cacheModel['forwards'], $forwards[0]);
            $this->forwards = $cacheModel['forwards'];
            if($this->save(false)){
                return true;
            }
        }
        return false;
    }


    public function forward($data){
        $cacheModel = $this->getAttributes();
        if(empty($cacheModel['was'])){
            $cacheModel['was'] = [];
        }

        $this->load($data);

        if($this->validate()){
            $forwards = $this->forwards;

            if(isset($forwards[0]['files'])){
                if(!$this->upload()){
                    $this->addError('files', 'Erro ao realizar upload do(s) arquivo(s)');
                    return false;
                }
                $forwards = $this->forwards;
            }

            array_push($cacheModel['forwards'], $forwards[0]);
            $this->forwards = $cacheModel['forwards'];
            array_push($cacheModel['was'], $cacheModel['place']);
            $this->was = $cacheModel['was'];

            if($this->save(false)){
                return true;
            }
        }
        return false;
    }

    public function response($data){
        $cacheModel = $this->getAttributes();
        $this->load($data);

        if($this->validate()){

            $forwards = $this->forwards;
            if(isset($forwards[0]['files'])){
                if(!$this->upload()){
                    $this->addError('files', 'Erro ao realizar upload do(s) arquivo(s)');
                    return false;
                }
                $forwards = $this->forwards;
            }

            array_push($cacheModel['forwards'], $forwards[0]);
            $this->forwards = $cacheModel['forwards'];

            if($this->save(false)){
                return true;
            }
        }
        return false;
    }

    public function upload(){
        $forwards = $this->forwards;
        $files = $forwards[0]['files'];
        if(isset($files) && count($files)){
            $path = Yii::getAlias('@web') .'uploads'. DIRECTORY_SEPARATOR;
            $security = new Security();
            $randomString = $security->generateRandomString(8);
            $path = $path . $randomString . DIRECTORY_SEPARATOR;
            mkdir($path, 0777, true);
            chmod($path, 0777);
            
            if(count($files) == count($files, COUNT_RECURSIVE)){
                $files = [$files];
            }
            
            foreach ($files as $key => $file) {
                $extension = substr($file['name'],strrpos($file['name'],'.'));
                $name = $security->generateRandomString(8);
                $files[$key] = $uploadfile = $path.$name.$extension;
                if(!move_uploaded_file($file['tmp_name'], $uploadfile)){
                    return false;
                }
            }

            $forwards[0]['files'] = $files;
            $this->forwards = $forwards;
            return true;
        }
        return false;
    }
}



?>