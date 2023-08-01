<?php 
 class OutSerieAno
 {
     /**
      * @var string|null
      */
     public $outCodSerieAno;
 
     /**
      * @var string|null
      */
     public $outDescSerieAno;
 
     /**
      * @param string|null $outCodSerieAno
      * @param string|null $outDescSerieAno
      */
     public function __construct(?string $outCodSerieAno, ?string $outDescSerieAno)
     {
         $this->outCodSerieAno = $outCodSerieAno;
         $this->outDescSerieAno = $outDescSerieAno;
     }
 
     /**
      * @return string|null
      */
     public function getOutCodSerieAno(): ?string
     {
         return $this->outCodSerieAno;
     }
 
     /**
      * @return string|null
      */
     public function getOutDescSerieAno(): ?string
     {
         return $this->outDescSerieAno;
     }
 
     /**
      * @param string|null $outCodSerieAno
      * @return self
      */
     public function setOutCodSerieAno(?string $outCodSerieAno): self
     {
         $this->outCodSerieAno = $outCodSerieAno;
         return $this;
     }
 
     /**
      * @param string|null $outDescSerieAno
      * @return self
      */
     public function setOutDescSerieAno(?string $outDescSerieAno): self
     {
         $this->outDescSerieAno = $outDescSerieAno;
         return $this;
     }
 
     /**
      * @param array $data
      * @return self
      */
     public static function fromJson(array $data): self
     {
         return new self(
             $data['outCodSerieAno'] ?? null,
             $data['outDescSerieAno'] ?? null
         );
     }
 } 

?>