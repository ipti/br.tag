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
 * e re-execute: composer run routes:generate -- schoolreport
 */

class SchoolreportRoutes
{
    // DefaultController
    public const DEFAULT_ERROR = 'schoolreport/default/error';
    public const DEFAULT_FREQUENCY = 'schoolreport/default/frequency';
    public const DEFAULT_GETGRADES = 'schoolreport/default/getGrades';
    public const DEFAULT_GRADES = 'schoolreport/default/grades';
    public const DEFAULT_INDEX = 'schoolreport/default/index';
    public const DEFAULT_LOGIN = 'schoolreport/default/login';
    public const DEFAULT_LOGOUT = 'schoolreport/default/logout';
    public const DEFAULT_SELECT = 'schoolreport/default/select';

    // FormsController
    public const FORMS_ATASCHOOLPERFORMANCE = 'schoolreport/forms/ataSchoolPerformance';
    public const FORMS_CONCLUSIONCERTIFICATION = 'schoolreport/forms/conclusionCertification';
    public const FORMS_ENROLLMENTDECLARATIONREPORT = 'schoolreport/forms/enrollmentDeclarationReport';
    public const FORMS_ENROLLMENTGRADESREPORT = 'schoolreport/forms/enrollmentGradesReport';
    public const FORMS_ENROLLMENTGRADESREPORTBOQUIM = 'schoolreport/forms/enrollmentGradesReportBoquim';
    public const FORMS_ENROLLMENTGRADESREPORTBOQUIMCICLO = 'schoolreport/forms/enrollmentGradesReportBoquimCiclo';
    public const FORMS_ENROLLMENTNOTIFICATION = 'schoolreport/forms/enrollmentNotification';
    public const FORMS_GETENROLLMENTDECLARATIONINFORMATION = 'schoolreport/forms/getEnrollmentDeclarationInformation';
    public const FORMS_GETENROLLMENTNOTIFICATIONINFORMATION = 'schoolreport/forms/getEnrollmentNotificationInformation';
    public const FORMS_GETSTUDENTSFILEINFORMATION = 'schoolreport/forms/getStudentsFileInformation';
    public const FORMS_GETTRANSFERFORMINFORMATION = 'schoolreport/forms/getTransferFormInformation';
    public const FORMS_GETTRANSFERREQUIREMENTINFORMATION = 'schoolreport/forms/getTransferRequirementInformation';
    public const FORMS_INDEX = 'schoolreport/forms/index';
    public const FORMS_INDIVIDUALRECORD = 'schoolreport/forms/individualRecord';
    public const FORMS_STATEMENTATTENDED = 'schoolreport/forms/statementAttended';
    public const FORMS_STUDENTFILEFORM = 'schoolreport/forms/studentFileForm';
    public const FORMS_STUDENTIMCHISTORYREPORT = 'schoolreport/forms/studentIMCHistoryReport';
    public const FORMS_STUDENTIMCREPORT = 'schoolreport/forms/studentIMCReport';
    public const FORMS_STUDENTSDECLARATIONREPORT = 'schoolreport/forms/studentsDeclarationReport';
    public const FORMS_STUDENTSFILEFORM = 'schoolreport/forms/studentsFileForm';
    public const FORMS_SUSPENSIONTERM = 'schoolreport/forms/suspensionTerm';
    public const FORMS_TRANSFERFORM = 'schoolreport/forms/transferForm';
    public const FORMS_TRANSFERREQUIREMENT = 'schoolreport/forms/transferRequirement';
    public const FORMS_WARNINGTERM = 'schoolreport/forms/warningTerm';

    // ReportsController
    public const REPORTS_ALLCLASSROOMSREPORTOFSTUDENTSBENEFITINGFROMTHEBF = 'schoolreport/reports/allClassroomsReportOfStudentsBenefitingFromTheBF';
    public const REPORTS_ALLSCHOOLSREPORTOFSTUDENTSBENEFITINGFROMTHEBF = 'schoolreport/reports/allSchoolsReportOfStudentsBenefitingFromTheBF';
    public const REPORTS_ALLSCHOOLSTRANSFERREPORT = 'schoolreport/reports/allSchoolsTransferReport';
    public const REPORTS_BFREPORT = 'schoolreport/reports/bFReport';
    public const REPORTS_BFRSTUDENTREPORT = 'schoolreport/reports/bFRStudentReport';
    public const REPORTS_CLASSCONTENTSREPORT = 'schoolreport/reports/classContentsReport';
    public const REPORTS_CLASSCOUNCILREPORT = 'schoolreport/reports/classCouncilReport';
    public const REPORTS_CLASSROOMTRANSFERREPORT = 'schoolreport/reports/classroomTransferReport';
    public const REPORTS_CLASSROOMWITHOUTINSTRUCTORRELATIONREPORT = 'schoolreport/reports/classroomWithoutInstructorRelationReport';
    public const REPORTS_CLOCPERCLASSROOM = 'schoolreport/reports/clocPerClassroom';
    public const REPORTS_CLOCREPORT = 'schoolreport/reports/clocReport';
    public const REPORTS_CNSPERCLASSROOMREPORT = 'schoolreport/reports/cnsPerClassroomReport';
    public const REPORTS_CNSPERSCHOOL = 'schoolreport/reports/cnsPerSchool';
    public const REPORTS_CNSSCHOOLS = 'schoolreport/reports/cnsSchools';
    public const REPORTS_COMPLEMENTARACTIVITYASSISTANTBYCLASSROOMREPORT = 'schoolreport/reports/complementarActivityAssistantByClassroomReport';
    public const REPORTS_DISCIPLINEANDINSTRUCTORRELATIONREPORT = 'schoolreport/reports/disciplineAndInstructorRelationReport';
    public const REPORTS_EDUCATIONALASSISTANTPERCLASSROOMREPORT = 'schoolreport/reports/educationalAssistantPerClassroomReport';
    public const REPORTS_ELECTRONICDIARY = 'schoolreport/reports/electronicDiary';
    public const REPORTS_ENROLLMENTCOMPARATIVEANALYSISREPORT = 'schoolreport/reports/enrollmentComparativeAnalysisReport';
    public const REPORTS_ENROLLMENTPERCLASSROOMREPORT = 'schoolreport/reports/enrollmentPerClassroomReport';
    public const REPORTS_ENROLLMENTSTATISTICSBYYEARREPORT = 'schoolreport/reports/enrollmentStatisticsByYearReport';
    public const REPORTS_EVALUATIONFOLLOWUPSTUDENTSREPORT = 'schoolreport/reports/evaluationFollowUpStudentsReport';
    public const REPORTS_GENERATEELECTRONICDIARYREPORT = 'schoolreport/reports/generateElectronicDiaryReport';
    public const REPORTS_GETDISCIPLINES = 'schoolreport/reports/getDisciplines';
    public const REPORTS_GETENROLLMENTS = 'schoolreport/reports/getEnrollments';
    public const REPORTS_GETSTAGESMULTI = 'schoolreport/reports/getStagesMulti';
    public const REPORTS_GETSTUDENTCLASSROOMS = 'schoolreport/reports/getStudentClassrooms';
    public const REPORTS_INCOMPATIBLESTUDENTAGEBYCLASSROOMREPORT = 'schoolreport/reports/incompatibleStudentAgeByClassroomReport';
    public const REPORTS_INDEX = 'schoolreport/reports/index';
    public const REPORTS_INSTRUCTORSPERCLASSROOMREPORT = 'schoolreport/reports/instructorsPerClassroomReport';
    public const REPORTS_NUMBEROFCLASSESPERSCHOOL = 'schoolreport/reports/numberOfClassesPerSchool';
    public const REPORTS_NUMBEROFSTUDENTSENROLLEDPERPERIODALLSCHOOLS = 'schoolreport/reports/numberOfStudentsEnrolledPerPeriodAllSchools';
    public const REPORTS_NUMBEROFSTUDENTSENROLLEDPERPERIODPERCLASSROOM = 'schoolreport/reports/numberOfStudentsEnrolledPerPeriodPerClassroom';
    public const REPORTS_NUMBEROFSTUDENTSENROLLEDPERPERIODPERSCHOOL = 'schoolreport/reports/numberOfStudentsEnrolledPerPeriodPerSchool';
    public const REPORTS_NUMBERSTUDENTSPERCLASSROOMREPORT = 'schoolreport/reports/numberStudentsPerClassroomReport';
    public const REPORTS_OUTOFTOWNSTUDENTSREPORT = 'schoolreport/reports/outOfTownStudentsReport';
    public const REPORTS_QUARTERLYFOLLOWUPREPORT = 'schoolreport/reports/quarterlyFollowUpReport';
    public const REPORTS_QUARTERLYREPORT = 'schoolreport/reports/quarterlyReport';
    public const REPORTS_REPORTOFSTUDENTSBENEFITINGFROMTHEBFPERCLASSROOM = 'schoolreport/reports/reportOfStudentsBenefitingFromTheBFPerClassroom';
    public const REPORTS_SCHOOLPROFESSIONALNUMBERBYCLASSROOMREPORT = 'schoolreport/reports/schoolProfessionalNumberByClassroomReport';
    public const REPORTS_SCHOOLTRANSFERREPORT = 'schoolreport/reports/schoolTransferReport';
    public const REPORTS_STATISTICALDATA = 'schoolreport/reports/statisticalData';
    public const REPORTS_STUDENTBYCLASSROOMREPORT = 'schoolreport/reports/studentByClassroomReport';
    public const REPORTS_STUDENTCPFRGNISALLCLASSROOMS = 'schoolreport/reports/studentCpfRgNisAllClassrooms';
    public const REPORTS_STUDENTCPFRGNISALLSCHOOLS = 'schoolreport/reports/studentCpfRgNisAllSchools';
    public const REPORTS_STUDENTCPFRGNISPERCLASSROOM = 'schoolreport/reports/studentCpfRgNisPerClassroom';
    public const REPORTS_STUDENTINSTRUCTORNUMBERSRELATIONREPORT = 'schoolreport/reports/studentInstructorNumbersRelationReport';
    public const REPORTS_STUDENTPENDINGDOCUMENT = 'schoolreport/reports/studentPendingDocument';
    public const REPORTS_STUDENTPERCLASSROOM = 'schoolreport/reports/studentPerClassroom';
    public const REPORTS_STUDENTSBETWEEN5AND14YEARSOLDREPORT = 'schoolreport/reports/studentsBetween5And14YearsOldReport';
    public const REPORTS_STUDENTSBYCLASSROOMREPORT = 'schoolreport/reports/studentsByClassroomReport';
    public const REPORTS_STUDENTSINALPHABETICALORDERRELATIONREPORT = 'schoolreport/reports/studentsInAlphabeticalOrderRelationReport';
    public const REPORTS_STUDENTSPECIALFOOD = 'schoolreport/reports/studentSpecialFood';
    public const REPORTS_STUDENTSUSINGSCHOOLTRANSPORTATIONRELATIONREPORT = 'schoolreport/reports/studentsUsingSchoolTransportationRelationReport';
    public const REPORTS_STUDENTSWITHDISABILITIESPERCLASSROOM = 'schoolreport/reports/studentsWithDisabilitiesPerClassroom';
    public const REPORTS_STUDENTSWITHDISABILITIESPERSCHOOL = 'schoolreport/reports/studentsWithDisabilitiesPerSchool';
    public const REPORTS_STUDENTSWITHDISABILITIESRELATIONREPORT = 'schoolreport/reports/studentsWithDisabilitiesRelationReport';
    public const REPORTS_STUDENTSWITHOTHERSCHOOLENROLLMENTREPORT = 'schoolreport/reports/studentsWithOtherSchoolEnrollmentReport';
    public const REPORTS_TEACHERSBYSCHOOL = 'schoolreport/reports/teachersBySchool';
    public const REPORTS_TEACHERSBYSTAGE = 'schoolreport/reports/teachersByStage';
    public const REPORTS_TEACHERTRAININGREPORT = 'schoolreport/reports/teacherTrainingReport';
    public const REPORTS_TOTALNUMBEROFSTUDENTSENROLLED = 'schoolreport/reports/totalNumberOfStudentsEnrolled';

    public static function url(string $route, array $params = []): string
    {
        return Yii::app()->createUrl($route, $params);
    }
}
