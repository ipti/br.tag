<?php

use Faker\Provider\Base;

class CustomProvider extends Base
{
    public function __construct(\Faker\Generator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * Método para gerar números do CNS.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function cnsNumber()
    {
        return $this->generator->numerify('###############');
    }

    /**
     * Método para gerar Número de Matrícula (Registro Civil - Certidão nova).
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function matriculaRegistroCivil()
    {
        // Primeiro Bloco (6 dígitos)
        $primeiroBloco = $this->generator->numerify('######');

        // Segundo Bloco (2 dígitos)
        $segundoBloco = $this->generator->numberBetween(1, 31);
        $segundoBloco = str_pad($segundoBloco, 2, '0', STR_PAD_LEFT);

        // Terceiro Bloco (2 dígitos)
        $terceiroBloco = $this->generator->numberBetween(1, 12);
        $terceiroBloco = str_pad($terceiroBloco, 2, '0', STR_PAD_LEFT);

        // Quarto Bloco (4 dígitos)
        $anoAtual = date('Y');
        $quartoBloco = $this->generator->numberBetween(2010, $anoAtual);

        // Quinto Bloco (1 dígito)
        $quintoBloco = '1';

        // Sexto Bloco (5 dígitos)
        $sextoBloco = $this->generator->numerify('#####');

        // Sétimo Bloco (3 dígitos)
        $setimoBloco = $this->generator->numerify('###');

        // Oitavo Bloco (7 dígitos)
        $oitavoBloco = $this->generator->numerify('#######');

        // Nono Bloco (2 dígitos)
        $nonoBloco = $this->generator->numerify('##');

        // Combina todos os blocos
        $matricula = "$primeiroBloco $segundoBloco $terceiroBloco $quartoBloco $quintoBloco $sextoBloco $setimoBloco $oitavoBloco $nonoBloco";

        return $matricula;
    }

    /**
     * Método que gera o livro de uma certidão.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function bookCivil()
    {
        return $this->generator->numerify('########');
    }

    /**
     * Método que gera a folha de uma certidão.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function sheetCivil()
    {
        return $this->generator->numerify('####');
    }

    /**
     * Método que gera o termo de uma certidão.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function termCivil()
    {
        return $this->generator->numerify('########');
    }

    /**
     * Método para gerar NIS (Número de Identificação Social).
     */
    public function nisNumber()
    {
        $nis = '1';
        $nis .= $this->generator->numerify('##########');

        return $nis;
    }

    /**
     * Método para gerar ID INEP.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function inepId()
    {
        $inep = '1';
        $inep .= $this->generator->numerify('#########');

        return $inep;
    }

    /**
     * Método que gera um complemento de uma localização.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function complementLocation()
    {
        $tipos = ['Bloco A', 'Casa 2', 'Sala 301', 'Fundos', 'Ap. 102', 'Lote 15'];

        return $this->generator->randomElement($tipos);
    }

    /**
     * Método que gera um nome para filiação.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function filiationName()
    {
        $firstName = $this->generator->firstName();
        $lastName = $this->generator->lastName();

        return "$firstName $lastName";
    }
/**
     * Método que gera um nome para turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function generateRandomClassName()
    {
        $adjectives = ['Red', 'Blue', 'Green', 'Yellow', 'Purple', 'Orange', 'Silver', 'Golden'];
        $nouns = ['Lions', 'Tigers', 'Bears', 'Eagles', 'Wolves', 'Dolphins', 'Sharks', 'Falcons'];

        $randomAdjective = $adjectives[array_rand($adjectives)];
        $randomNoun = $nouns[array_rand($nouns)];

        return $randomAdjective . ' ' . $randomNoun . ' Class';
    }

    /**
     * Método que gera o horário.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function generateRandomTime()
    {
        $hour = str_pad(random_int(0, 23), 2, '0', STR_PAD_LEFT);
        $minute = str_pad(random_int(0, 59), 2, '0', STR_PAD_LEFT);

        return "$hour:$minute";
    }

    public function generateRandomEndTime($startTime)
    {
        do {
            $endTime = $this->generateRandomTime();
        } while ($this->compareTimes($startTime, $endTime) >= 0);

        return $endTime;
    }

    private function compareTimes($time1, $time2)
    {
        $time1Parts = explode(':', $time1);
        $time2Parts = explode(':', $time2);

        $hour1 = intval($time1Parts[0]);
        $minute1 = intval($time1Parts[1]);

        $hour2 = intval($time2Parts[0]);
        $minute2 = intval($time2Parts[1]);

        if ($hour1 == $hour2) {
            return $minute1 - $minute2;
        }

        return $hour1 - $hour2;
    }

    /**
     * Gera a identificação da turma.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function identificationClass()
    {
        $letras = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
        $numeros = [1, 2, 3, 4, 5, 6, 7];

        $identificacao = $this->generator->randomElement($letras);

        if ($this->generator->boolean(50)) {
            $identificacao .= $this->generator->randomElement($numeros);
        }

        return $identificacao;
    }

    /**
     * Gera nome do plano de curso.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function classPlan()
    {
        $adjectives = ['Plano', 'Aula', 'Teste', 'Fundamental', 'Curso', 'Técnica'];
        $nouns = ['Português', 'Inglês', 'Geografia', 'História', 'Ciências', 'Matemática', 'História'];

        $randomAdjective = $adjectives[array_rand($adjectives)];
        $randomNoun = $nouns[array_rand($nouns)];

        return $randomAdjective . ' ' . $randomNoun;
    }

    /**
     * Gera um titulo de calendário.
     * @author Evellyn Jade de Cerqueira Reis- <ti.jade@ipti.org.br>
     */
    public function titleCalendar()
    {
        $numeroAleatorio = $this->generator->numberBetween(1, 100);

        return "Calendário $numeroAleatorio";
    }
}
