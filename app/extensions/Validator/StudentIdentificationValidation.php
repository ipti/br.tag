<?php

$DS = DIRECTORY_SEPARATOR;

require_once(dirname(__FILE__) .  $DS . "register.php");

class studentIdentificationValidation extends Register{
	
	function __construct() {
	}

	//campo 08
	function validateBirthday($date, $lowyear_limit, $currentyear){

		$result = $this->validateDateformart($date);
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		$mdy = explode('/', $date);

		$result = $this->isGreaterThan($mdy[2], 1910);
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		$result = $this->isNotGreaterThan($mdy[2], $currentyear);
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		return array("status"=>true,"erro"=>"");

	}

	function specialNeeds($value, $allowedvalues, $requirement){

		$result = $this->isAllowed($value, $allowedvalues);
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		if($requirement == '1'){
			if($value != '1'){
				return array("status"=>false,"erro"=>"Valor deveria ser 1 pois estudante possui deficiência");
			}
		}

		return array("status"=>true,"erro"=>"");

	}

	function inNeedOfResources($deficiencies, $demandresources, $resources, $deficiency_type_blindness, $deficiency_type_deafblindness){

		$first_result = $this->atLeastOne($deficiencies);
		if($first_result['status'] && $demandresources > '0'){
			$second_result = $this->atLeastOne($resources);
			if(!$second_result['status']){
				return array("status"=>false,"erro"=>$second_result['erro']);
			}
		}
		if($deficiency_type_deafblindness == '1' || $deficiency_type_blindness == '1'){
			$resource_aid_transcription = array_pop($resources);
			$result = $this->atLeastOne($resources);
			if(!($resource_aid_transcription == '1' && $result['status'])){
				return array("status"=>false,"erro"=>"Campo 31 vale 0 ou ".$result['erro']);
			}
		}

		return array("status"=>true,"erro"=>"");
	}
}

?>