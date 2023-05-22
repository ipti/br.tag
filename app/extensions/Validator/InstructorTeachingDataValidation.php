<?php

$DS = DIRECTORY_SEPARATOR;

require_once(dirname(__FILE__) .  $DS . "register.php");

class instructorTeachingDataValidation extends Register{

	function checkRole($value, $pedagogical_mediation_type, $assistance_type, $status_instructor, $status_student){

		$result = $this->isAllowed($value, array('1', '2', '3', '4', '5', '6', '7', '8'));
		$translate = array('1' => 'Docente', '2' => 'Auxiliar/assistente educacional', '3' => 'Profissional/monitor de atividade complementar', '4' => 'Tradutor e Intérprete de Libras', '5' => 'EAD - Docente Titular', '6' => 'EAD - Docente Tutor', '7' => 'Guia-Intérprete', '8' => 'Profissional de apoio escolar para aluno(a)s com deficiência');
		if(!$result['status']){
			return array("status"=>false,"erro"=>$result['erro']);
		}

		if($pedagogical_mediation_type != '1'){
			if(!($value == '5' || $value == '6')){
				return array("status"=>false,"erro"=>"$translate[$value] indisponível devido ao tipo de mediação Didático-Pedagógica");
			}
		}

		if($assistance_type == '4' || $assistance_type == '5'){
			if ($value == '2'){
				return array("status"=>false,"erro"=>"$translate[$value] indisponível devido ao tipo de atendimento");
			}
		}

		if($pedagogical_mediation_type != '3'){
			if ($value == '5' || $value == '6'){
				return array("status"=>false,"erro"=>"$translate[$value] indisponível devido ao tipo de atendimento");
			}
		}

		if($value == '6' || $value == '4'){
			if($status_instructor != '1'){
				return array("status"=>false,"erro"=>"Não há instrutores além do tipo 4 e 6");
			}
		}

		if($value == '4'){
			if($status_student != '1'){
				return array("status"=>false,"erro"=>"Não há alunos ou instrutores com deficiência");
			}
		}


		return array("status"=>true,"erro"=>"");

	}

	function checkContactType($value, $role, $administrative_dependence){

		if(in_array($role, array('1', '5', '6')) && in_array($administrative_dependence, array('1', '2', '3'))) {
			$result = $this->isAllowed($value, array('1', '2', '3', '4'));
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

	function disciplineOne($discipline_code_one, $role, $assistance_type, $edcenso_svm){

		if( in_array($role, array('1', '5')) && !in_array($assistance_type, array('4', '5')) && !in_array($edcenso_svm, array('1', '2', '3', '65')) ){
			if($discipline_code_one == null){
				return array("status"=>false,"erro"=>"value não deveria ser nulo");
			}
		}

		return array("status"=>true,"erro"=>"");

	}

	function checkDisciplineCode($disciplines_codes, $role, $assistance_type, $edcenso_svm, $disciplines){

		if(!empty($disciplines_codes)){
			$result = $this->exclusive($disciplines_codes);

			if(!$result['status']){
					return array("status"=>false,"erro"=>$result['erro']);
			}
		}

		if(	!(in_array($role, array('1', '5')) ||
				in_array($assistance_type, array('4', '5')) ||
				in_array($edcenso_svm, array('1', '2', '3', '65'))) ) {

			foreach ($disciplines_codes as $key => $value) {
				if($value != null){
					return array("status"=>false,"erro"=>"value de $value de ordem $key deveria ser nulo");
				}	
			}
		}

		/*$result = $this->checkRangeOfArray($disciplines_codes, $disciplines);
		if(!$result['status']){
				return array("status"=>false,"erro"=>$result['erro']);
		}*/

		return array("status"=>true,"erro"=>"");

	}

}

?>