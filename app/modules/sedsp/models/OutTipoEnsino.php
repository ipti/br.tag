<?php
 class OutTipoEnsino
 {
     /**
      * @var string|null
      */
     public $outCodTipoEnsino;
 
     /**
      * @var string|null
      */
     public $outDescTipoEnsino;
 
     /**
      * @var OutSerieAno[]|null
      */
     public $outSerieAno;
 
     /**
      * @param string|null $outCodTipoEnsino
      * @param string|null $outDescTipoEnsino
      * @param OutSerieAno[]|null $outSerieAno
      */
     public function __construct(
         ?string $outCodTipoEnsino,
         ?string $outDescTipoEnsino,
         ?array $outSerieAno
     ) {
         $this->outCodTipoEnsino = $outCodTipoEnsino;
         $this->outDescTipoEnsino = $outDescTipoEnsino;
         $this->outSerieAno = $outSerieAno;
     }
 
     /**
      * @return string|null
      */
     public function getOutCodTipoEnsino(): ?string
     {
         return $this->outCodTipoEnsino;
     }
 
     /**
      * @return string|null
      */
     public function getOutDescTipoEnsino(): ?string
     {
         return $this->outDescTipoEnsino;
     }
 
     /**
      * @return OutSerieAno[]|null
      */
     public function getOutSerieAno(): ?array
     {
         return $this->outSerieAno;
     }
 
     /**
      * @param string|null $outCodTipoEnsino
      * @return self
      */
     public function setOutCodTipoEnsino(?string $outCodTipoEnsino): self
     {
         $this->outCodTipoEnsino = $outCodTipoEnsino;
         return $this;
     }
 
     /**
      * @param string|null $outDescTipoEnsino
      * @return self
      */
     public function setOutDescTipoEnsino(?string $outDescTipoEnsino): self
     {
         $this->outDescTipoEnsino = $outDescTipoEnsino;
         return $this;
     }
 
     /**
      * @param OutSerieAno[]|null $outSerieAno
      * @return self
      */
     public function setOutSerieAno(?array $outSerieAno): self
     {
         $this->outSerieAno = $outSerieAno;
         return $this;
     }
 
     /**
      * @param array $data
      * @return self
      */
     public static function fromJson(array $data): self
     {
         return new self(
             $data['outCodTipoEnsino'] ?? null,
             $data['outDescTipoEnsino'] ?? null,
             ($data['outSerieAno'] ?? null) !== null ? array_map(static function($data) {
                 return OutSerieAno::fromJson($data);
             }, $data['outSerieAno']) : null
         );
     }
 }
 
?>