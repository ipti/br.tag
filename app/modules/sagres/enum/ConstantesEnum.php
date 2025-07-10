<?php
// ConstantesEnum.php
enum ConstantesEnum: string
{
    case TURMA_STRONG = '<strong>TURMA<strong>';
    case SERIE_STRONG = '<strong>SÉRIE<strong>';
    case STUDENT_STRONG = '<strong>ESTUDANTE<strong>';
    case DATA_MATRICULA_INV = 'Data da matrícula no formato inválido: ';
    case DATE_FORMAT = 'd/m/Y';

    // Variáveis de inconsistência
    case INCONSISTENCY_BIRTH_AFTER_LIMIT = 'A data de nascimento não pode ser posterior a 30 de abril de 2024';
    case INCONSISTENCY_BIRTH_BEFORE_LIMIT = 'A data de nascimento não pode ser inferior a 01 de janeiro de 1930';
    case INCONSISTENCY_STUDENT_NAME_TOO_SHORT = 'Nome do estudante com menos de 5 caracteres';
    case INCONSISTENCY_ACTION_STUDENT_NAME_TOO_SHORT = 'Adicione um nome para o estudante com pelo menos 5 caracteres';
    case INCONSISTENCY_PCD_CODE_INVALID = 'Código pcd é inválido';
    case INCONSISTENCY_ACTION_PCD_CODE_INVALID = 'Adicione um valor válido para o pcd';
    case INCONSISTENCY_BIRTH_DATE_INVALID = 'Data de nascimento inválida';
    case INCONSISTENCY_ACTION_BIRTH_DATE_INVALID = 'Altere para uma data válida';
    case INCONSISTENCY_ACTION_BIRTH_DATE_INVALID_FORMAT = 'Altere o formato de data para dd/mm/aaaa';
    case INCONSISTENCY_SEX_INVALID = 'Sexo não é válido';
    case INCONSISTENCY_ACTION_SEX_INVALID = 'Adicione um sexo válido para o estudante';
    case INCONSISTENCY_STUDENT_NOT_FOUND_FOR_CLASS_REGISTRATION = 'Estudante não existe para a matrícula da turma: ';
    case INCONSISTENCY_ACTION_STUDENT_NOT_FOUND_FOR_CLASS_REGISTRATION = 'Adicione um estudante à turma da escola';
    case INCONSISTENCY_NO_REGISTRATION_FOR_CLASS = 'Não há matrícula para a turma';
    case INCONSISTENCY_ACTION_NO_REGISTRATION_FOR_CLASS = 'Adicione uma matrícula para a turma';
    case INCONSISTENCY_INVALID_ABSENCE_VALUE = 'O valor para o número de faltas é inválido';
    case INCONSISTENCY_ACTION_INVALID_ABSENCE_VALUE = 'Coloque um valor válido para o número de faltas';
    case INCONSISTENCY_INVALID_APPROVED_STATUS_VALUE = 'Valor inválido para o status aprovado';
    case INCONSISTENCY_ACTION_INVALID_APPROVED_STATUS_VALUE = 'Adicione um valor válido para o campo aprovado do aluno';
}
?>