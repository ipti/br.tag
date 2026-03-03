<?php

/**
 * Robot para interações com o Histórico do Professor.
 */
class InstructorHistoryRobots
{
    public AcceptanceTester $tester;

    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * Navega para a página de edição do professor (aba Histórico).
     */
    public function pageInstructorUpdate(int $id): void
    {
        $this->tester->amOnPage("?r=instructor/update&id={$id}");
    }

    /**
     * Clica na aba "Histórico" do formulário do professor.
     */
    public function clickHistoryTab(): void
    {
        $this->tester->click('Histórico');
        $this->tester->waitForElement('.history-table', 5);
    }

    /**
     * Verifica que o botão "Imprimir Resumo do Ano" existe para o ano informado.
     */
    public function seeYearPrintButton(string $year): void
    {
        $this->tester->see($year);
        $this->tester->seeElement("a[href*='printYearHistory'][href*='year={$year}']");
    }

    /**
     * Clica no botão de imprimir resumo do ano.
     */
    public function clickYearPrintButton(string $year): void
    {
        $this->tester->click("a[href*='printYearHistory'][href*='year={$year}']");
    }

    /**
     * Navega diretamente para a página de impressão do resumo do ano.
     */
    public function pagePrintYearHistory(int $id, string $year): void
    {
        $this->tester->amOnPage("?r=instructor/printYearHistory&id={$id}&year={$year}");
    }

    /**
     * Navega diretamente para a página de impressão do histórico individual.
     */
    public function pagePrintHistory(int $id, ?int $teachingId = null): void
    {
        $url = "?r=instructor/printHistory&id={$id}";
        if ($teachingId !== null) {
            $url .= "&teaching_id={$teachingId}";
        }
        $this->tester->amOnPage($url);
    }

    /**
     * Verifica que a página de resumo do ano contém o cabeçalho correto.
     */
    public function seeYearSummaryHeader(string $year): void
    {
        $this->tester->see("Resumo de Vínculos");
        $this->tester->see($year);
    }

    /**
     * Verifica que a tabela de vínculos contém dados.
     */
    public function seeTeachingLinksTable(): void
    {
        $this->tester->seeElement('table');
    }

    /**
     * Verifica que o logo da escola está sendo exibido no cabeçalho de relatório.
     */
    public function seeReportLogo(): void
    {
        $this->tester->seeElement('#logo');
    }

    /**
     * Verifica que o logo usa a URL do displayLogo e não base64.
     */
    public function seeLogoUsesDisplayLogoUrl(): void
    {
        $this->tester->seeElement("img#logo[src*='displayLogo']");
    }

    /**
     * Verifica que o texto "Regência" aparece (turma de fundamental menor).
     */
    public function seeRegencia(): void
    {
        $this->tester->see('Regência');
    }

    /**
     * Verifica que NÃO há quebra por disciplina na tabela de aulas (fundamental menor).
     */
    public function dontSeeDisciplineBreakdown(): void
    {
        $this->tester->dontSee('Disciplina', 'th');
    }

    /**
     * Verifica que a seção de totais do ano está visível.
     */
    public function seeYearTotals(): void
    {
        $this->tester->see('Total');
    }
}
