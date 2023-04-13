<?php
class OutErro {
  private $outErro;
  private $processoID;
  private $hasResponse;
  private $code;

  public function __construct($e) {
    $outdata = json_decode($e->getResponse()->getBody()->getContents());
    $this->hasResponse = $e->hasResponse();
    $this->code = $e->getCode();
    $this->outErro = trim($outdata->outErro);
    $this->processoID = $outdata->outProcessoID;
  }

  public function getoutErro() {
    return Yii::t('sedsp', $this->outErro);
  }
  public function getCode(){
      return $this->code;
  }
  public function getHasResponse(){
      return $this->hasResponse;
  }
  public function getProcessoID() {
    return $this->processoID;
  }
}

?>