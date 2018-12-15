<?php
$DS = DIRECTORY_SEPARATOR;

require_once(dirname(__FILE__) .  $DS . "register.php");

class studentEnrollmentValidation extends Register{
	
	function __construct() {
	}

	function multiLevel($value, $demand){
		$result = $this->isAllowed($demand, array('12', '13', '22', '23', '24', '72', '56', '64'));
		if($result['status']){

			if($demand == '12' || $demand == '13'){
				$result = $this->isAllowed($value, array('4', '5', '6', '7', '8', '9', '10', '11'));
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}
			}

			if($demand == '22' || $demand == '23'){
				$result = $this->isAllowed($value, array('14', '15', '16', '17', '18', '19', '20', '21', '41'));
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}
			}

			if($demand == '24'){
				$result = $this->isAllowed($value, array('4', '5', '6', '7', '8', '9', '10', '11', 
															'14', '15', '16', '17', '18', '19', '20', '21', '41'));
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}
			}

			if($demand == '72'){
				$result = $this->isAllowed($value, array('69', '70'));
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}
			}

			if($demand == '56'){
				$result = $this->isAllowed($value, array('1', '2', '4', '5', '6', '7', '8', '9', '10', '11',
															'14', '15', '16', '17', '18', '19', '20', '21', '41'));
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}
			}

			if($demand == '64'){
				$result = $this->isAllowed($value, array('39', '40'));
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}
			}

		}else{
			if($value != null){
				return array("status"=>false,"erro"=>"value $value deveria ser nulo");
			}
		}

		return array("status"=>true,"erro"=>"");

	}

	function anotherScholarizationPlace($value, $assistance_type, $pedagogical_mediation_type){
		
		if($assistance_type != '4' && $assistance_type != '5' && $pedagogical_mediation_type == '1'){
			$result = $this->isAllowed($value, array("1", "2", "3"));
			if(!$result['status']){
				return array("status"=>false,"erro"=>$result['erro']);
			}
		}else{
			if($value != null){
				return array("status"=>false,"erro"=>"value $value deveria ser nulo");
			}
		}

		if($assistance_type == '1'){
			if($value != '1'){
				return array("status"=>false,"erro"=>"value $value deveria ser 1");
			}	
		}

		return array("status"=>true,"erro"=>"");

	}

	function publicTransportation($value, $pedagogical_mediation_type){

		if($value == '1' || $value == '2'){
			if(!($value == '1' || $value == '0')){
				return array("status"=>false,"erro"=>"value $value não é disponível");
			}
		}else{
			if($value != null){
				return array("status"=>false,"erro"=>"value $value deveria ser nulo");
			}	
		}

		return array("status"=>true,"erro"=>"");

	}

	function vehiculesTypes($public_transport, $types){

		if($public_transport == '1'){

			$result = $this->checkRangeOfArray($types, array("0", "1"));
			if(!$result['status']){
				return array("status"=>false,"erro"=>$result['erro']);
			}

			$result = $this->allowedNumberOfTypes($types, '1', 3);
			if(!$result['status']){
				return array("status"=>false,"erro"=>$result['erro']);
			}
		}else{
			foreach ($types as $key => $value) {
				if($value != null){
					return array("status"=>false,"erro"=>"value $value deveria ser nulo");
				}	
			}
		}

		return array("status"=>true,"erro"=>"");

	}

	function studentEntryForm($value, $administrative_dependence, $edcenso_svm){

		$edcenso_svm_allowed_values = array('39', '40', '64', '30', '31', '32', '33', '34', '74');
		$first_result = $this->isAllowed($edcenso_svm, $edcenso_svm_allowed_values);

		if($administrative_dependence == '1' && $first_result['status']){

			$allowed_values = array('1', '2', '3', '4', '5', '6', '7', '8','9');
			$second_result = $this->isAllowed($value, $allowed_values);
			if(!$result['status']){
				return array("status"=>false,"erro"=>$result['erro']);
			}

		}else{
			if($value != null){
					return array("status"=>false,"erro"=>"value $value deveria ser nulo");
			}	
		}

		return array("status"=>true,"erro"=>"");
	}



}

?>