<?php

/**
*
*/
class Register

{

	function __construct(){
	}

	function isEmpty($value){
		if (trim($value) === '' || !isset($value)){
			return array('status' => true, 'erro' => '');
		}
		return array('status' => false, 'erro' => 'O valor nao eh vazio');
	}

	function isNull($x){
		if($x == null){
			return array("status"=>true,"erro"=>"");
		}
		return array("status"=>false,"erro"=>"Valor não é nulo");

	}


	function ifNull($value){
		if($value == null)
			$value = "nulo";
		return $value;
	}

	//campo 1002
	function isEqual($x, $y, $msg){

		$result = $this->isNUll($x);

		if($result['status']){
			return array("status"=>false,"erro"=>"valor é nulo");
		}
		if($x != $y){
			return array("status"=>false,"erro"=>$msg);
		}
		return array("status"=>true,"erro"=>"");
	}

	//campo 1003 à 1011, 1033 à 1038
	function atLeastOne($items){
		$number_of_ones = 0;
		for($i = 0; $i < sizeof($items); $i++){
			if(@$items[$i]=="1")
				$number_of_ones++;
		}
		if($number_of_ones==0){
			return array("status"=>false,"erro"=>"Não há nenhum valor marcado");
		}
		return array("status"=>true,"erro"=>"");
	}

	function atLeastOneNotEmpty($items){
		$number_of_not_empty = 0;
		for($i = 0; $i < sizeof($items); $i++){
			if($items[$i] != "")
				$number_of_not_empty++;
		}
		if($number_of_not_empty==0){
			return array("status"=>false,"erro"=>"Não há nenhum valor preenchido");
		}
		return array("status"=>true,"erro"=>"");
	}

	function moreThanOne($items){

		$number_of_ones = 0;
		for($i = 0; $i < sizeof($items); $i++){
			if($items[$i]=="1")
				$number_of_ones++;
		}
		if($number_of_ones<1){
			return array("status"=>false,"erro"=>"Não há mais de um valor marcado");
		}
		return array("status"=>true,"erro"=>"");

	}


	//campo 1001, 3001, 4001, 6001
	function isRegister($number, $value){
		$result = $this->isEqual($value, $number, "O tipo de registro não deveria ser $value e sim $number");
		if(!$result["status"]){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		return array("status"=>true,"erro"=>"");
	}

	//campo 1002, 3002, 4002, 6002
	function isAllowedInepId($inep_id, $allowed_inep_ids){
		if(!in_array($inep_id, $allowed_inep_ids)){
			return array("status"=>false,"erro"=>"inep_id $inep_id não está entre os permitidos");

		}

		return array("status"=>true,"erro"=>"");
	}

	//campo 3003, 6003
	function isNumericOfSize($allowed_length, $value){
		if(is_numeric($value)){
			$len = strlen($value);
			if($len != $allowed_length){
				return array("status"=>false,"erro"=>"valor deveria ter $allowed_length caracteres ao invés de $len");
			}
		}else{
			$value = $this->ifNull($value);
			return array("status"=>false,"erro"=>"valor $value não é numérico");
		}

		return array("status"=>true,"erro"=>"");
	}

	//1070, 1088
	function isGreaterThan($value, $target){

		if($value <= $target){
			$value = $this->ifNull($value);
			return array("status"=>false,"erro"=>"Valor $value não é maior que o alvo.");
		}
		return array("status"=>true,"erro"=>"");
	}

	//3004, 6004
	function isNotGreaterThan($value, $target){

		$result = $this->isGreaterThan(strlen($value), $target);
		if($result['status']){
			return array("status"=>false,"erro"=>"Valor $value é maior que o alvo.");
		}

		return array("status"=>true,"erro"=>"");
	}

	function onlyAlphabet($value){

		$regex="/^[a-zA-Z ]+$/";
		if (!preg_match($regex, $value)){
			return array("status"=>false,"erro"=>"'$value' contém caracteres inválidos");
		}

		return array("status"=>true,"erro"=>"");

	}

	//3005, 6005
	function isNameValid($value, $target, $cpf){

		$result = $this->isGreaterThan(strlen($value), $target);
		if($result['status']){
			return array("status"=>false,"erro"=>"Número de caracteres maior que o permitido.");
		}

		$result = $this->onlyAlphabet($value);
		if (!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		$result = $this->ifCPFNull($cpf, $value);
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		return array("status"=>true,"erro"=>"");
	}


	function validateEmailFormat($email){

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			return array("status"=>false,"erro"=>"'$email' contém caracteres inválidos");
		}

		return array("status"=>true,"erro"=>"");

	}

	function validateDateformart($date){

		if($date == '' || $date == null){
            return array("status" => false,"erro" =>"Data no formato incorreto");
        }

		//separa data em dia, mês e ano
		$mdy = explode('/', $date);

		// verifica se a data é valida. Mês-dia-ano
		if(!checkdate( $mdy[1] , $mdy[0] , $mdy[2] )){
			return array("status"=>false,"erro"=>"'$date' está inválida");
		}

		return array("status"=>true,"erro"=>"");

	}

	function getAge($birthyear, $currentyear){
		$age = $currentyear - $birthyear;
		return $age;
	}

	function isOlderThan($target_age, $birthyear, $currentyear){

		$age = $this->getAge($birthyear, $currentyear);
		$result = $this->isGreaterThan($age, $target_age);
		if(!$result['status']){
			return array("status"=>false,"erro"=>"idade $age é menor que o permitido ($target_age)");
		}

		return array("status"=>true,"erro"=>"");

	}

	function isYoungerThan($target_age, $birthyear, $currentyear){

		$age = $this->getAge($birthyear, $currentyear);
		$result = $this->isNotGreaterThan($age, $target_age);
		if(!$result['status']){
			return array("status"=>false,"erro"=>"idade $age é maior que o permitido ($target_age)");
		}

		return array("status"=>true,"erro"=>"");

	}

	//campo 1020, 3009, 6007
	function oneOfTheValues($value){
		if($value == 1 || $value == 2){
			return array("status"=>true,"erro"=>"");
		}
		$value = $this->ifNull($value);
		return array("status"=>false,"erro"=>"Valor $value não está entre as opções");

	}


	//10101, 10105, 10106, 3010, 6008
	function isAllowed($value, $allowed_values){
		if(!in_array($value, $allowed_values)){
				$value = $this->ifNull($value);
				return array("status"=>false,
								"erro"=>"Valor $value não está entre as opções");
		}
		return array("status"=>true,"erro"=>"");
	}

	function ifCPFNull($cpf, $value){

		if($cpf == null){

			if(str_word_count($value) < 2){
				return array("status"=>false,"erro"=>"'$value' possui cpf nulo e não contém mais que 2 palavras");
			}

			if (preg_match('/(\w)\1{5,}/', $value)) {
				return array("status"=>false,"erro"=>"'$value' possui cpf nulo e contém mais de 4 caracteres repetidos");
			}

		}

		return array("status"=>true,"erro"=>"");

	}

	function validateParent($parent, $high_limit, $cpf){

		$result = $this->isGreaterThan(strlen($parent), $high_limit);
		if($result['status']){
			return array("status"=>false,"erro"=>"'$filiation_father' $contém número de caracteres maior que o permitido.");
		}


		$result = $this->onlyAlphabet($parent);
		if (!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}


		$result = $this->ifCPFNull($cpf, $parent);
		if (!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		return array("status"=>true,"erro"=>"");

	}
	//3011, 3012, 3013, 6009.6010, 6011
	function validateFiliation($filiation, $filiation_mother, $filiation_father, $cpf, $high_limit){

		$result = $this->isAllowed($filiation, array("0", "1"));
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		if($filiation == "1"){

			if(!($filiation_mother != "" || $filiation_father != "")){
				return array("status"=>false,"erro"=>"Uma das filiações deve ser preenchida");
			}

			if($filiation_mother == $filiation_father){
				return array("status"=>false,"erro"=>"As filiações não podem ser idênticas");
			}

			if($filiation_mother != ""){

				$result = $this->validateParent($filiation_mother, $high_limit, $cpf);
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}

			}

			if($filiation_father != ""){

				$result = $this->validateParent($filiation_father, $high_limit, $cpf);
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}

			}

		}else{

			if(!($filiation_mother == null && $filiation_father == null)){
				return array("status"=>false,"erro"=>"Ambas filiãções deveriam ser nulas campo 11 é 0");
			}

		}

		return array("status"=>true,"erro"=>"");

	}

	//3014, 3015, 6012, 6013
	function checkNation($nationality, $nation, $allowedvalues){

		$result = $this->isAllowed($nationality, $allowedvalues);
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		if($nationality == 1 || $nationality == 2){
			if($nation != "76"){
				return array("status"=>false,"erro"=>"País de origem deveria ser Brasil");
			}
		}else{
			if($nation == "76"){
				return array("status"=>false,"erro"=>"País de origem não deveria ser Brasil");
			}
		}

		return array("status"=>true,"erro"=>"");

	}

	function ufcity($nationality,$nation, $city){

		if($nationality == 1){
			if($nation == "" || $city == null){
				return array("status"=>false,"erro"=>"Cidade deveria ser preenchida");
			}
		}else{
			if($nation != ""){
				return array("status"=>false,"erro"=>"Cidade não deveria ser preenchida");
			}
		}

		return array("status"=>true,"erro"=>"");
	}

	function exclusiveDeficiency( $deficiency, $excludingdeficiencies){

		$result = $this->atLeastOne($excludingdeficiency);
		if(!$result['status']){
			if($deficiency != "0"){
				return array("status"=>false,"erro"=>"Valor $deficiency deveria ser 0");
			}
		}

		return array("status"=>true,"erro"=>"");

	}

	function checkDeficiencies($hasdeficiency, $deficiencies, $excludingdeficiencies){

		if($hasdeficiency == "1"){

			$result = $this->atLeastOne($deficiencies);
			if(!$result['status']){
				return array("status"=>false,"erro"=>$result['erro']);
			}

			foreach ($excludingdeficiencies as $deficiency => $excluded) {
				$result = $this->exclusiveDeficiency($deficiency, $excluded);
				if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
				}
			}

		}elseif ($hasdeficiency == "0"){
			foreach ($deficiencies as $key => $value) {
				if($value != null){
					return array("status"=>false,"erro"=>"Valor deveria ser nulo");
				}
			}

		}

		return array("status"=>true,"erro"=>"");

	}

	function checkMultiple($hasdeficiency, $multipleDeficiencies, $deficiencies){

		if($hasdeficiency == "1"){
			$result = $this->moreThanOne($deficiencies);
			if($result['status']){
				if($multipleDeficiencies != "1"){
					return array("status"=>false,"erro"=>"Valor $multipleDeficiencies deveria ser 1 pois há multiplas deficiências");
				}
			}else{
				if($multipleDeficiencies != "0"){
					return array("status"=>false,"erro"=>"Valor $multipleDeficiencies deveria ser 0 pois não há multiplas deficiências");
				}
			}
		}elseif ($hasdeficiency == "0"){

			if($multipleDeficiencies != null){
					return array("status"=>false,"erro"=>"multiplas dependências $multipleDeficiencies deveria ser nulo");

			}
		}

		return array("status"=>true,"erro"=>"");

	}

	function ifDemandsCheckValues($demand, $value, $allowed_values){

		if($demand == '1'){
			$result = $this->isAllowed($value, $allowed_values);
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

	function allowedNumberOfTypes($items, $value, $limit){

		$number_of_ones = 0;
		for($i = 0; $i < sizeof($items); $i++){
			if($items[$i]==$value)
				$number_of_ones++;
		}
		if($number_of_ones>$limit){
			return array("status"=>false,"erro"=>"Há valores marcados além do permitido");
		}
		return array("status"=>true,"erro"=>"");
	}


	//Registro 10 ( 21 à 25, 26 à 29, 30 à 32, 39 à 68 )
	function checkRangeOfArray($array, $allowed_values){
		foreach ($array as $key => $value) {
			$result = $this->isAllowed($value, $allowed_values);
			if(!$result["status"]){
				return array("status"=>false,"erro"=>"Valor $value de ordem $key não está entre as opções");
			}
		}
		return array("status"=>true,"erro"=>"");
	}

	function adaptedArrayCount($items){
		$result = array();

		foreach ($items as $key => $value) {
			if($value != null){
				if (array_key_exists($value, $result)){
					$result[$value] += 1;
				}else{
					$result[$value] = 1;
				}
			}
		}

		return $result;
	}

	//1098 à 10100
	function exclusive($items){

		$count = $this->adaptedArrayCount($items);
		if(!empty($count)){
			if (max($count) > 1)
				return array("status"=>false,"erro"=>"Há mais de um valor marcado");
		}

		return array("status"=>true,"erro"=>"");

	}


}

?>
