<table class="table-border">
  <tr class="font-bold text-center text-uppercase">
    <td class="blue-background">Especificação</td>
    <td class="no-padding no-border-top">
      <table class="blue-background">
        <tr >
          <td class="no-border bl-print">
            Anos
          </td>
        </tr>
      </table>
      <table>
        <tr>
          <? for($i=1; $i<=4; $i++): ?>
            <td class="width-3 <?= $i == 1 ? 'no-border-left' : '' ?> <?= $i == 4 ? 'no-border-right' : '' ?>" colspan="2">
              <? if ($i == 1)  echo 'Infantil I'; ?>
              <? if ($i == 2)  echo 'Infantil II'; ?>
              <? if ($i == 3)  echo 'Infantil III'; ?>
              <? if ($i == 4)  echo 'Total'; ?>
            </td>
          <? endfor; ?>
        </tr>
        <tr>
          <? for($i=1; $i<=4; $i++): ?>
            <td class="width-1 <?= $i == 1 ? 'no-border-left' : '' ?> <?= $i == 4 ? 'no-border-right' : '' ?> no-border-bottom">M</td>
            <td class="width-1 <?= $i == 1 ? 'no-border-left' : '' ?> <?= $i == 4 ? 'no-border-right' : '' ?> no-border-bottom">F</td>
          <? endfor; ?>
        </tr>
      </table>
    </td>
    <td class="blue-background">Total Geral</td>
  </tr>
  <tr>
    <td class="no-padding font-bold text-center text-uppercase">
      <table>
        <tr><td class="no-border-left no-border-right no-border-top no-border-bottom">Matrícula Inicial</td><tr>
      </table>
    </td>
    <td class="no-padding">
      <table>
        <tr>
          <? for($j=1; $j<=4; $j++): ?>
            <td class="width-1 <?= $j == 1 ? 'no-border-left' : '' ?> <?= $j == 4 ? 'no-border-right' : '' ?> no-border-bottom no-border-top">
              <div class="contentEditable no-border" contenteditable="true"></div>
            </td>
            <td class="width-1 <?= $j == 1 ? 'no-border-left' : '' ?> <?= $j == 4 ? 'no-border-right' : '' ?> no-border-bottom no-border-top">
              <div class="contentEditable no-border" contenteditable="true"></div>
            </td>
          <? endfor; ?>
        </tr>
      </table>
    </td>
    <td><div class="contentEditable no-border" contenteditable="true"></div></td>
  </tr>
  <tr>
    <td class="no-padding font-bold text-center text-uppercase">
      <table>
        <tr><td class="no-border-left no-border-right no-border-top no-border-bottom">ALUNOS RECEBIDOS APÓS MATRÝCULA INICIAL</td><tr>
      </table>
    </td>
    <td class="no-padding">
      <table>
        <tr>
          <? for($j=1; $j<=4; $j++): ?>
            <td class="width-1 <?= $j == 1 ? 'no-border-left' : '' ?> <?= $j == 4 ? 'no-border-right' : '' ?> no-border-bottom no-border-top">
              <div class="contentEditable no-border" contenteditable="true"></div>
            </td>
            <td class="width-1 <?= $j == 1 ? 'no-border-left' : '' ?> <?= $j == 4 ? 'no-border-right' : '' ?> no-border-bottom no-border-top">
              <div class="contentEditable no-border" contenteditable="true"></div>
            </td>
          <? endfor; ?>
        </tr>
      </table>
    </td>
    <td><div class="contentEditable no-border" contenteditable="true"></div></td>
  </tr>
  <tr>
    <td class="no-padding  no-border-bottom font-bold text-center text-uppercase">
      <table>
        <tr>
          <td class="no-border-left no-border-top">Afastamento por</td>
          <td class=" no-border-bottom no-border-left no-border-top no-border-right no-padding ">
            <table>
              <tr><td class="no-border-left no-border-top no-border-right">EVASÃO</td></tr>
              <tr><td class="no-border-left no-border-top no-border-right">TRANSFERÊNCIA</td></tr>
              <tr><td class="no-border">FALECIMENTO</td></tr>
            </table>
          </td>
        <tr>
      </table>
    </td>
    <td class="no-padding">
      <table>
        <? for($i=1; $i<=3; $i++): ?>
          <tr>
            <? for($j=1; $j<=4; $j++): ?>
              <td class="width-1 <?= $i == 3 ? 'no-border-bottom' : '' ?> <?= $j == 1 ? 'no-border-left' : '' ?> <?= $j == 4 ? 'no-border-right' : '' ?> no-border-top">
                <div class="contentEditable no-border" contenteditable="true"></div>
              </td>
              <td class="width-1 <?= $i == 3 ? 'no-border-bottom' : '' ?> <?= $j == 1 ? 'no-border-left' : '' ?> <?= $j == 4 ? 'no-border-right' : '' ?> no-border-top">
                <div class="contentEditable no-border" contenteditable="true"></div>
              </td>
            <? endfor; ?>
          </tr>
        <? endfor; ?>
      </table>
    </td>
    <td class="no-padding">
      <table>
        <? for($i=1; $i<=3; $i++): ?>
          <tr>
            <td class="no-border-left no-border-right no-border-top <?= $i == 3 ? 'no-border-bottom' : '' ?>">
              <div class="contentEditable no-border" contenteditable="true"></div>
            </td>
          </tr>
        <? endfor; ?>
      </table>
    </td>
  </tr>
  <tr>
    <td class="no-padding font-bold text-center text-uppercase">
      <table>
        <tr><td class="no-border-left no-border-right no-border-top no-border-bottom">Matrícula Atual</td><tr>
      </table>
    </td>
    <td class="no-padding">
      <table>
        <tr>
          <? for($j=1; $j<=4; $j++): ?>
            <td class="width-1 <?= $j == 1 ? 'no-border-left' : '' ?> <?= $j == 4 ? 'no-border-right' : '' ?> no-border-bottom no-border-top">
              <div class="contentEditable no-border" contenteditable="true"></div>
            </td>
            <td class="width-1 <?= $j == 1 ? 'no-border-left' : '' ?> <?= $j == 4 ? 'no-border-right' : '' ?> no-border-bottom no-border-top">
              <div class="contentEditable no-border" contenteditable="true"></div>
            </td>
          <? endfor; ?>
        </tr>
      </table>
    </td>
    <td><div class="contentEditable no-border" contenteditable="true"></div></td>
  </tr>
  <tr>
    <td class="no-padding  no-border-bottom font-bold text-center text-uppercase">
      <table>
        <tr>
          <td class="no-border-left no-border-top">Matrícula Final</td>
          <td class=" no-border-bottom no-border-left no-border-top no-border-right no-padding ">
            <table>
              <tr><td class="no-border-left no-border-top no-border-right">Promovidos</td></tr>
              <tr><td class="no-border">Retidos</td></tr>
            </table>
          </td>
        <tr>
      </table>
    </td>
    <td class="no-padding">
      <table>
        <? for($i=1; $i<=2; $i++): ?>
          <tr>
            <? for($j=1; $j<=4; $j++): ?>
              <td class="width-1 <?= $i == 2 ? 'no-border-bottom' : '' ?> <?= $j == 1 ? 'no-border-left' : '' ?> <?= $j == 4 ? 'no-border-right' : '' ?> no-border-top">
                <div class="contentEditable no-border" contenteditable="true"></div>
              </td>
              <td class="width-1 <?= $i == 2 ? 'no-border-bottom' : '' ?> <?= $j == 1 ? 'no-border-left' : '' ?> <?= $j == 4 ? 'no-border-right' : '' ?> no-border-top">
                <div class="contentEditable no-border" contenteditable="true"></div>
              </td>
            <? endfor; ?>
          </tr>
        <? endfor; ?>
      </table>
    </td>
    <td class="no-padding">
      <table>
        <? for($i=1; $i<=2; $i++): ?>
          <tr>
            <td class="no-border-left no-border-right no-border-top <?= $i == 2 ? 'no-border-bottom' : '' ?>">
              <div class="contentEditable no-border" contenteditable="true"></div>
            </td>
          </tr>
        <? endfor; ?>
      </table>
    </td>
  </tr>
  <tr>
    <td class="no-padding font-bold text-center text-uppercase">
      <table>
        <tr><td class="no-border-left no-border-right no-border-top no-border-bottom">Matrícula Atual</td><tr>
      </table>
    </td>
    <td class="no-padding">
      <table>
        <tr>
          <? for($j=1; $j<=4; $j++): ?>
            <td class="width-2 <?= $j == 1 ? 'no-border-left' : '' ?> <?= $j == 4 ? 'no-border-right' : '' ?> no-border-bottom no-border-top">
              <div class="contentEditable no-border" contenteditable="true"></div>
            </td>
          <? endfor; ?>
        </tr>
      </table>
    </td>
    <td><div class="contentEditable no-border" contenteditable="true"></div></td>
  </tr>
  <tr>
    <td class="font-bold text-center text-uppercase">Observação</td>
    <td colspan="2">
      <div class="contentEditable height-1" contenteditable="true"></div>
    </td>
  </tr>
</table>