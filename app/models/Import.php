<?php


class Import extends CModel{
	
	const INSTRUCTOR_TEACHING_DATA = 'INSTRUCTOR_TEACHING_DATA';
	const INSTRUCTOR_IDENTIFICATION = 'INSTRUCTOR_IDENTIFICATION';
	const STUDENT_IDENTIFICATION = 'STUDENT_IDENTIFICATION';
	const STUDENT_DOCUMENT_AND_ADDRESS = 'STUDENT_DOCUMENT_AND_ADDRESS';

    public $registers; 
	public $year;
	public $file;
	public $importWithError;
	
	public function  __constructor($year){
		parent::__constructor();
		$this->registers = [];
	}

	public function attributeNames(){
		return ['registers', 'year', 'file','importWithError'];
	}

	public function attributeLabels() {
		return [
			'registers' => "Registros", 
			'year' => "Ano do arquivo", 
			'file' => "Arquivo",
			'importWithError' => "Importar com erro?"
		];
	}

    public function run(){
		set_time_limit(0);
		ignore_user_abort();
		
        $file = fopen($this->file, 'r');
        
		if ($file == false) {
			$this->setError('file','O arquivo não existe.');
			return;
		}

		$registers = [];

		while (true) {
			
			$line = fgets($file);
			if ($line == null) {
				break;
			}

			
			$registerType= $line[0] . $line[1];
			
			$fields = explode("|", $line);
			$lineFields = [];

			foreach ($fields as $key => $field) {
				$value = !(isset($field)) ? '' : trim($field);
				$lineFields[$key] = $value;
			}

			if(in_array($registerType, [40,50,60])){
				$inepId = $fields[3];

				if(!is_null($inepId)){
					$registers[$registerType][$inepId] = $lineFields;
				}
				else{
					$this->setFailure($registerType, $line);
				}
            }
            else{
                $registers[$registerType][] = $lineFields;
            }
        }

		$this->registers = $registers;
		$this->initImport();
	}
	
	private function initImport(){
		$transaction = Yii::app()->db->beginTransaction();
		try{
			$this->importRegister00($this->registers['00']);
			$this->importRegister10($this->registers['10']);
			$this->importRegister20($this->registers['20']);
			$this->importRegister30($this->registers['30']);
			$this->importRegister40($this->registers['40']);
			$this->importRegister50($this->registers['50']);
			$this->importRegister60($this->registers['60']);
			
			if(!$this->hasErrors() || $this->importWithError){
				$transaction->commit();
				Yii::app()->user->setFlash("success", "Importação realizada com sucesso!");
			}

			$transaction->rollBack();
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
			$this->addError('file',$e->getMessage());
		}
	}
    
    public function importRegister00($lines){

		$fields = EdcensoAlias::model()->findAllByAttributes(["register" => 0]);
		$schoolIdentification = new SchoolIdentification();
		$attributes = $schoolIdentification->attributeNames();

		foreach ($lines as $line) {

			$hasModified = false;
			$schoolIdentificationModel = new SchoolIdentification();
			
			foreach ($fields as $field) {
				$columnName = $field->attr;
				$collumnOrder = $field->corder -1;

				if(isset($line[$collumnOrder]) && $line[$collumnOrder] != "" && in_array($columnName, $attributes)){
					$schoolIdentificationModel->{$columnName} = utf8_encode($line[$collumnOrder]);
					$hasModified = true;
				}
			}

			if($hasModified){
				$edcensoCityId = $line[7];
				$edcensoCity = EdcensoCity::model()->findByPk($edcensoCityId);
				$edcensoDistrict = EdcensoDistrict::model()->findByAttributes(['edcenso_city_fk' => $edcensoCityId]);
				$schoolIdentificationModel->edcenso_uf_fk = $edcensoCity->edcenso_uf_fk;
				$schoolIdentificationModel->edcenso_district_fk = $edcensoDistrict->id;
				if(!$schoolIdentificationModel->save()){
					$this->setFailure('00', $line);
				}
			}
			
		}
	}

    public function importRegister10($lines){

		$fields = EdcensoAlias::model()->findAllByAttributes(["register" => 10]);
		$schoolStructure = new SchoolStructure();
		$attributes = $schoolStructure->attributeNames();

		foreach ($lines as $line) {

			$hasModified = false;
			$schoolStructureModel = new SchoolStructure();
			
			foreach ($fields as $field) {
				$columnName = $field->attr;
				$collumnOrder = $field->corder -1;

				if(isset($line[$collumnOrder]) && $line[$collumnOrder] != "" && in_array($columnName, $attributes)){
					$schoolStructureModel->{$columnName} = utf8_encode($line[$collumnOrder]);
					$hasModified = true;
				}
			}

			if($hasModified){
				if(!$schoolStructureModel->save()){
					$this->setFailure('10', $line);
				}
			}
			
		}
	}

    public function importRegister20($lines){

		$fields = EdcensoAlias::model()->findAllByAttributes(["register" => 20]);
		$classroom = new Classroom();
		$attributes = $classroom->attributeNames();

		foreach ($lines as $line) {

			$hasModified = false;
			$classroomModel = new Classroom();
			
			foreach ($fields as $field) {
				$columnName = $field->attr;
				$collumnOrder = $field->corder -1;

				if(isset($line[$collumnOrder]) && $line[$collumnOrder] != "" && in_array($columnName, $attributes)){
					$classroomModel->{$columnName} = utf8_encode($line[$collumnOrder]);
					$hasModified = true;
				}
			}

			if($hasModified){
				$classroomModel->assistance_type = 0;
				$classroomModel->school_year = $this->year;
				if(!$classroomModel->save()){
					$this->setFailure('20', $line);
				}
			}
			
		}
	}

	
	public function importRegister30($lines){

		foreach ($lines as $line) {
			$isStudent = $this->isStudent($line[3]);
			
			if($isStudent){
				$this->importRegister301($line);
			}
			else{
				$this->importRegister302($line);
			}
		}
	}


    public function importRegister301($line){

		$fields = EdcensoAlias::model()->findAllByAttributes(["register" => 301]);
		$studentIdentificationModel = new StudentIdentification();
		$studentDocumentModel = new StudentDocumentsAndAddress();
		
		foreach ($fields as $field) {
			$columnName = $field->attr;
			$collumnOrder = $field->corder -1;
			$modelType = $field->stable;

			if(is_null($modelType)){
				continue;
			}

			$model = $modelType == self::STUDENT_IDENTIFICATION ? $studentIdentificationModel : $studentDocumentModel;

			if(isset($line[$collumnOrder]) && $line[$collumnOrder] != "" && in_array($columnName, $model->attributeNames())){
				$model->{$columnName} = utf8_encode($line[$collumnOrder]);
			}
		}

		$studentIdentificationModel->send_year = $this->year;
		$studentDocumentModel->school_inep_id_fk = $studentIdentificationModel->school_inep_id_fk;
		if($studentIdentificationModel->validate() && $studentDocumentModel->validate()){
			if($studentIdentificationModel->save(false)){
				$studentDocumentModel->student_fk = $studentIdentificationModel->id;
				$studentDocumentModel->save();
				return;
			}
		}

		$this->setFailure('30', $line);
			
	}

    public function importRegister302($line){

		$fields = EdcensoAlias::model()->findAllByAttributes(["register" => 302]);
		$instructorIdentificationModel = new InstructorIdentification(InstructorIdentification::SCENARIO_IMPORT);
		$instructorDocumentModel = new InstructorDocumentsAndAddress(InstructorDocumentsAndAddress::SCENARIO_IMPORT);
		
		foreach ($fields as $field) {
			$columnName = $field->attr;
			$collumnOrder = $field->corder -1;
			$modelType = $field->stable;

			if(is_null($modelType)){
				continue;
			}

			$model = $modelType == self::INSTRUCTOR_IDENTIFICATION ? $instructorIdentificationModel : $instructorDocumentModel;

			if(isset($line[$collumnOrder]) && $line[$collumnOrder] != "" && in_array($columnName, $model->attributeNames())){
				$model->{$columnName} = utf8_encode($line[$collumnOrder]);
			}
		}

		$instructorDocumentModel->school_inep_id_fk = $instructorIdentificationModel->school_inep_id_fk;
		if($instructorIdentificationModel->validate() && $instructorDocumentModel->validate()){
			if($instructorIdentificationModel->save(false)){
				$instructorDocumentModel->save();
				return;
			}
		}

		$this->setFailure('30', $line);
			
	}

	public function importRegister40($lines){

		$fields = EdcensoAlias::model()->findAllByAttributes(["register" => 40]);
		$school = new SchoolIdentification();
		$attributes = $school->attributeNames();
		$attributes = array_diff($attributes, ['register_type', 'inep_id']);

		foreach ($lines as $line){

			$hasModified = false;
			$schoolInepId = $line[1];

			if(is_null($schoolInepId)){
				continue;
			}

			$schoolModel = SchoolIdentification::model()->findByAttributes(['inep_id' => $schoolInepId]);
			
			if(!is_null($schoolModel)){
				foreach ($fields as $field){
					$columnName = $field->attr;
					$collumnOrder = $field->corder -1;
	
					if(isset($line[$collumnOrder]) && $line[$collumnOrder] != "" && in_array($columnName, $attributes)){
						$schoolModel->{$columnName} = utf8_encode($line[$collumnOrder]);
						$hasModified = true;
					}
				}
			}

			if($hasModified){
				$inepId = $line[3];
				if(isset($inepId)){

					$manager = Yii::app()->db->createCommand(array(
						'select' => array('name', 'email'),
						'from' => 'instructor_identification',
						'where' => 'inep_id=:inep_id',
						'params' => array(':inep_id' => $inepId),
					))->queryRow();

					if(is_array($manager)){
						$schoolModel->manager_name = $manager['name'];
						$schoolModel->manager_email = $manager['email'];
					}
				}

				if(!$schoolModel->save()){
					$this->setFailure('40', $line);
				}
			}
			
		}
	}

	public function importRegister50($lines){

		$fields = EdcensoAlias::model()->findAllByAttributes(["register" => 50]);
		$instructorTeaching = new InstructorTeachingData(InstructorTeachingData::SCENARIO_IMPORT);
		$attributes = $instructorTeaching->attributeNames();

		foreach ($lines as $line) {

			$hasModified = false;
			$instructorTeachingModel = new InstructorTeachingData(InstructorTeachingData::SCENARIO_IMPORT);
			
			foreach ($fields as $field) {
				$columnName = $field->attr;
				$collumnOrder = $field->corder -1;

				if(isset($line[$collumnOrder]) && $line[$collumnOrder] != "" && in_array($columnName, $attributes)){
					$instructorTeachingModel->{$columnName} = utf8_encode($line[$collumnOrder]);
					$hasModified = true;
				}
			}

			if($hasModified){

				$classroomInepId = $line[5];
				if(isset($classroomInepId)){
					$classroom = Classroom::model()->findByAttributes(['inep_id' => $classroomInepId]);
					if(!is_null($classroom)){
						$instructorTeachingModel->classroom_id_fk = $classroom->id;
					}
				}

				$instructorInepId = $line[3];
				if(isset($instructorInepId)){

					$instructor = Yii::app()->db->createCommand(array(
						'select' => array('id'),
						'from' => 'instructor_identification',
						'where' => 'inep_id=:inep_id',
						'params' => array(':inep_id' => $instructorInepId),
					))->queryRow();

					if(is_array($instructor)){
						$instructorTeachingModel->instructor_fk = $instructor['id'];
					}
				}

				if(!$instructorTeachingModel->save()){
					$this->setFailure('50', $line);
				}
			}
			
		}
	}

	public function importRegister60($lines){

		$fields = EdcensoAlias::model()->findAllByAttributes(["register" => 60]);
		$studentEnrollment = new StudentEnrollment();
		$attributes = $studentEnrollment->attributeNames();

		foreach ($lines as $line) {

			$hasModified = false;
			$studentEnrollmentModel = new StudentEnrollment();
			
			foreach ($fields as $field) {
				$columnName = $field->attr;
				$collumnOrder = $field->corder -1;

				if(isset($line[$collumnOrder]) && $line[$collumnOrder] != "" && in_array($columnName, $attributes)){
					$studentEnrollmentModel->{$columnName} = utf8_encode($line[$collumnOrder]);
					$hasModified = true;
				}
			}

			if($hasModified){

				$inepId = $line[3];
				if(isset($inepId)){
					$student = StudentIdentification::model()->findByAttributes(['inep_id' => $inepId]);
					if(!is_null($student)){
						$studentEnrollmentModel->student_fk = $student->id;
					}
				}

				$classroomInepId = $line[5];
				if(isset($classroomInepId)){
					$classroom = Classroom::model()->findByAttributes(['inep_id' => $classroomInepId]);
					if(!is_null($classroom)){
						$studentEnrollmentModel->classroom_fk = $classroom->id;
					}
				}

				if(!$studentEnrollmentModel->save()){
					$this->setFailure('60', $line);
				}
			}
			
		}
	}
	
	public function setFailure($registerType, $data){
		$this->addError('file', implode("|", $data));
	}

	public function isStudent($inepId){
		return !(array_key_exists($inepId, $this->registers['50']) || array_key_exists($inepId, $this->registers['40']));
	}
}


?>