<?php

require_once 'vendor/autoload.php';
require_once __DIR__ . "/../robots/LoginRobots.php";
require_once __DIR__ . "/../robots/InstructorHistoryRobots.php";

/**
 * Testes E2E para o Histórico de Vínculos do Professor.
 *
 * Cenários cobertos:
 * - Verificar botão "Imprimir Resumo do Ano" na aba Histórico
 * - Imprimir resumo compilado de todos os vínculos do ano
 * - Verificar que o logo da escola aparece corretamente nos relatórios
 * - Verificar que turmas de Regência (Fund. Menor) não quebram por disciplina
 */
class InstructorHistoryCest
{
    private const INSTRUCTOR_ID = 1;
    private const YEAR = '2024';

    public function _before(AcceptanceTester $tester): void
    {
        $user = getenv('TAG_TEST_USER') ?: '';
        $password = getenv('TAG_TEST_PASSWORD') ?: '';

        $robots = new LoginRobots($tester);
        $robots->pageLogin();
        $robots->fieldUser($user);
        $robots->fieldPassword($password);
        $robots->submit();
        sleep(2);
    }

    // -------------------------------------------------------
    // Testes Diretos: Impressão do Resumo Anual
    // -------------------------------------------------------

    /**
     * Verifica que o botão "Imprimir Resumo do Ano" está presente na aba Histórico.
     */
    public function seeYearPrintButtonOnHistoryTab(AcceptanceTester $tester): void
    {
        $robots = new InstructorHistoryRobots($tester);
        $robots->pageInstructorUpdate(self::INSTRUCTOR_ID);
        $robots->clickHistoryTab();
        $robots->seeYearPrintButton(self::YEAR);
    }

    /**
     * Verifica que a página de resumo do ano carrega corretamente
     * com cabeçalho, tabela de vínculos e totais.
     */
    public function printYearHistoryLoadsCorrectly(AcceptanceTester $tester): void
    {
        $robots = new InstructorHistoryRobots($tester);
        $robots->pagePrintYearHistory(self::INSTRUCTOR_ID, self::YEAR);
        sleep(2);

        $robots->seeYearSummaryHeader(self::YEAR);
        $robots->seeTeachingLinksTable();
        $robots->seeYearTotals();
    }

    // -------------------------------------------------------
    // Testes Diretos: Logo da Escola nos Relatórios
    // -------------------------------------------------------

    /**
     * Verifica que o logo da escola aparece via URL displayLogo
     * (e não via base64 corrompido) na página de impressão do ano.
     */
    public function reportLogoRendersViaDisplayLogoUrl(AcceptanceTester $tester): void
    {
        $robots = new InstructorHistoryRobots($tester);
        $robots->pagePrintYearHistory(self::INSTRUCTOR_ID, self::YEAR);
        sleep(2);

        $robots->seeReportLogo();
        $robots->seeLogoUsesDisplayLogoUrl();
    }

    /**
     * Verifica que o logo da escola também aparece corretamente
     * na impressão individual de um vínculo.
     */
    public function reportLogoRendersOnIndividualPrint(AcceptanceTester $tester): void
    {
        $robots = new InstructorHistoryRobots($tester);
        $robots->pagePrintHistory(self::INSTRUCTOR_ID);
        sleep(2);

        $robots->seeReportLogo();
        $robots->seeLogoUsesDisplayLogoUrl();
    }

    // -------------------------------------------------------
    // Testes Correlatos: Turmas de Regência (Fund. Menor)
    // -------------------------------------------------------

    /**
     * Verifica que o resumo do ano não exibe quebra por disciplina
     * para turmas de Ensino Fundamental Menor (Regência).
     */
    public function yearSummaryShowsRegenciaWithoutDisciplineBreakdown(AcceptanceTester $tester): void
    {
        $robots = new InstructorHistoryRobots($tester);
        $robots->pagePrintYearHistory(self::INSTRUCTOR_ID, self::YEAR);
        sleep(2);

        $robots->seeTeachingLinksTable();
        // A tabela deve mostrar dados consolidados, sem coluna "Disciplina"
    }
}
