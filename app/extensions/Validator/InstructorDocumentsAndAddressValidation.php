<?php

    $DS = DIRECTORY_SEPARATOR;

    require_once(dirname(__FILE__) .  $DS . "register.php");

    //registro 40
  class InstructorDocumentsAndAddressValidation extends Register
  {
        //campo 5
      public function isCPFValid($cpfStr)
      {
          if ($cpfStr !== "" && $cpfStr !== null) {
              $cpf = "$cpfStr";
              if (strpos($cpf, "-") !== false) {
                  $cpf = str_replace("-", "", $cpf);
              }
              if (strpos($cpf, ".") !== false) {
                  $cpf = str_replace(".", "", $cpf);
              }
              $sum = 0;
              $cpf = str_split($cpf);
              $cpftrueverifier = array();
              $cpfnumbers = array_splice($cpf, 0, 9);
              $cpfdefault = array(10, 9, 8, 7, 6, 5, 4, 3, 2);
              for ($i = 0; $i <= 8; $i++) {
                  $sum += $cpfnumbers[$i] * $cpfdefault[$i];
              }
              $sumresult = $sum % 11;
              if ($sumresult < 2) {
                  $cpftrueverifier[0] = 0;
              } else {
                  $cpftrueverifier[0] = 11 - $sumresult;
              }
              $sum = 0;
              $cpfdefault = array(11, 10, 9, 8, 7, 6, 5, 4, 3, 2);
              $cpfnumbers[9] = $cpftrueverifier[0];
              for ($i = 0; $i <= 9; $i++) {
                  $sum += $cpfnumbers[$i] * $cpfdefault[$i];
              }
              $sumresult = $sum % 11;
              if ($sumresult < 2) {
                  $cpftrueverifier[1] = 0;
              } else {
                  $cpftrueverifier[1] = 11 - $sumresult;
              }
              $returner = false;
              if ($cpf == $cpftrueverifier) {
                  $returner = true;
              }


              $cpfver = array_merge($cpfnumbers, $cpf);

              if (count(array_unique($cpfver)) == 1 || $cpfver == array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0)) {
                  $returner = false;
              }
              if (!$returner) {
                  return array("status" => false, "erro" => "'$cpfStr' inválido.");
              }
          } else {
              return array("status" => false, "erro" => "O campo CPF é obrigatório.");
          }
          return array("status" => true, "erro" => "");
      }

      //campo 7
      public function isCEPValid($cep)
      {
          // retira espacos em branco
          $cep = trim($cep);
          // expressao regular para avaliar o cep
          $avaliaCep = preg_match('/^[0-9]{5}[0-9]{3}$/', $cep);

          if ($cep == null) {
              return array("status" => false,"erro" => "O campo CEP é uma informação obrigatória.");
          }
          if (strlen($cep) != 8 || !$avaliaCep) {
              return array("status" => false,"erro" => "O campo CEP está com tamanho diferente do especificado.");
          }
          if (!is_numeric($cep)) {
              return array("status" => false,"erro" => "O campo CEP foi preenchido com valor inválido.");
          }

          return array("status" => true,"erro" =>"");
      }

      //campo 8, 9, 10, 11, 12, 13
      public function isAdressValid($field, $cep, $allowed_lenght)
      {
          $regex = "/^[0-9 a-z.,-ºª ]+$/";
          if ($cep == null) {
              if ($field == null) {
                  return array("status" => false,"erro" => "O campo não pode ser nulo.");
              }
          } elseif (strlen($field) > $allowed_lenght || strlen($field) <= 0) {
              return array("status" => false,"erro" => "O campo está com tamanho incorreto.");
          } elseif (!preg_match($regex, $field)) {
              return array("status" => false,"erro" => "O campo foi preenchido com valor inválido.");
          } elseif ($field == null) {
              return array("status" => false,"erro" => "O campo não pode ser nulo.");
          }
          return array("status" => true,"erro" =>"");
      }
  }
