<?php
class Autenticacao {
  private $autenticacao;
  private $usuario;
  private $processoID;

  public function __construct($json) {
    $data = json_decode($json);
    $this->autenticacao = $data->outAutenticacao;
    $this->usuario = $data->outUsuario;
    $this->processoID = $data->outProcessoID;
  }

  public function getAutenticacao() {
    return $this->autenticacao;
  }

  public function getUsuario() {
    return $this->usuario;
  }

  public function getProcessoID() {
    return $this->processoID;
  }
}

?>