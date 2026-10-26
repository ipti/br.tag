<?php

/*
 * ARQUIVO GERADO AUTOMATICAMENTE
 * ================================
 * Gerado por: scripts/generate-routes.php
 * Comando:    composer run routes:generate
 *
 * NÃO EDITE ESTE ARQUIVO MANUALMENTE.
 * Qualquer alteração será sobrescrita na próxima geração.
 *
 * Para adicionar ou renomear rotas, altere os controllers correspondentes
 * e re-execute: composer run routes:generate -- student
 */

class StudentRoutes
{
    // EnrollmentController
    public const ENROLLMENT_CHECKENROLLMENTDELETE = 'student/enrollment/checkEnrollmentDelete';
    public const ENROLLMENT_CREATE = 'student/enrollment/create';
    public const ENROLLMENT_DELETE = 'student/enrollment/delete';
    public const ENROLLMENT_GETMODALITIES = 'student/enrollment/getModalities';
    public const ENROLLMENT_INDEX = 'student/enrollment/index';
    public const ENROLLMENT_UPDATE = 'student/enrollment/update';
    public const ENROLLMENT_UPDATEDEPENDENCIES = 'student/enrollment/updateDependencies';
    public const ENROLLMENT_VIEW = 'student/enrollment/view';

    // StudentimcController
    public const STUDENTIMC_ADMIN = 'student/studentimc/admin';
    public const STUDENTIMC_CREATE = 'student/studentimc/create';
    public const STUDENTIMC_DELETE = 'student/studentimc/delete';
    public const STUDENTIMC_INDEX = 'student/studentimc/index';
    public const STUDENTIMC_RENDERSTUDENTTABLE = 'student/studentimc/renderStudentTable';
    public const STUDENTIMC_STUDENTIMCREPORT = 'student/studentimc/studentIMCReport';
    public const STUDENTIMC_STUDENTINDEX = 'student/studentimc/studentIndex';
    public const STUDENTIMC_UPDATE = 'student/studentimc/update';
    public const STUDENTIMC_VIEW = 'student/studentimc/view';

    // StudentController
    public const STUDENT_COMPARESTUDENTCERTIFICATE = 'student/student/compareStudentCertificate';
    public const STUDENT_COMPARESTUDENTCIVILREGISTERENROLLMENTNUMBER = 'student/student/compareStudentCivilRegisterEnrollmentNumber';
    public const STUDENT_COMPARESTUDENTCPF = 'student/student/compareStudentCpf';
    public const STUDENT_COMPARESTUDENTNAME = 'student/student/compareStudentName';
    public const STUDENT_CREATE = 'student/student/create';
    public const STUDENT_DELETE = 'student/student/delete';
    public const STUDENT_GETCITIES = 'student/student/getCities';
    public const STUDENT_GETGRADESANDFREQUENCY = 'student/student/getGradesAndFrequency';
    public const STUDENT_GETNATIONS = 'student/student/getNations';
    public const STUDENT_GETNOTARYOFFICE = 'student/student/getNotaryOffice';
    public const STUDENT_GETSTUDENTAJAX = 'student/student/getStudentAjax';
    public const STUDENT_GETTRANSFERCLASSROOMS = 'student/student/getTransferClassrooms';
    public const STUDENT_INDEX = 'student/student/index';
    public const STUDENT_SYNCTOSEDSP = 'student/student/syncToSedsp';
    public const STUDENT_TRANSFER = 'student/student/transfer';
    public const STUDENT_UPDATE = 'student/student/update';
    public const STUDENT_VIEW = 'student/student/view';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
