<div class='container-report mt-20'>
    <table class="table-border">
        <tr>
        <th colspan="16">
            <span class="pull-left">Nome do Aluno(a):</span>
            <div class="pull-left contentEditable no-border name-student" contenteditable="true">  </div>
        </th>
        </tr>
        <tr class="blue-background font-head ">
            <th width="180">Componentes Curiculares</th>
            <th class="no-padding">
                <div class="rotate">Lingua Portuguesa</div>
            </th>
            <th class="no-padding"><div class="rotate">Matermática</div></th>
            <th class="no-padding"><div class="rotate">Ciências</div></th>
            <th class="no-padding"><div class="rotate">História</div></th>
            <th class="no-padding"><div class="rotate">Geografia</div></th>
            <th class="no-padding"><div class="rotate">Arte</div></th>
            <th class="no-padding"><div class="rotate">Educação Física</div></th>
            <th class="no-padding"><div class="rotate">Ensino Religioso</div></th>
            <th class="no-padding"><div class="rotate">Filosofia</div></th>
            <th class="no-padding"><div class="rotate">Inglês</div></th>
            <th class="no-padding"><div class="rotate contentEditable no-border" contenteditable="true">  </div></th>
            <th class="no-padding"><div class="rotate contentEditable no-border" contenteditable="true">  </div></th>
            <th class="no-padding"><div class="rotate contentEditable no-border" contenteditable="true">  </div></th>
            <th class="no-padding"><div class="rotate contentEditable no-border" contenteditable="true">  </div></th>
            <th class="no-padding"><div class="rotate">CH/ANUAL</div></th>
        </tr>
        <? for($j=1; $j<=9; $j++): ?>
        <tr class="font-size-10">
            <td class="no-padding">
            <table>
                <tr>
                <td class="no-border-top no-border-left no-border-bottom"><?= $j ?>° Ano</td>
                <td class="no-border-top no-border-right no-border-bottom">Resultados</td>
                </tr>
            </table>
            </td>
            <? for($i=1; $i<=14; $i++): ?>
            <td>
                <div class="contentEditable no-border" contenteditable="true">  </div>
            </td>
            <? endfor; ?>
            <td><div class="contentEditable no-border" contenteditable="true">  </div></td>
        </tr>
        
        <tr class="font-size-10">
            <td class="no-padding">
            <table>
                <tr>
                <td class="size-cel no-border-top no-border-left no-border-bottom"><div class="contentEditable no-border" contenteditable="true">  </div</td>
                <td class="no-border-top no-border-right no-border-bottom size-2">Total de h/aulas	</td>
                </tr>
            </table>
            </td>
            <? for($i=1; $i<=14; $i++): ?>
            <td>
                <div class="contentEditable no-border <?= $i >= 11 ? 'width-20' : '' ?>" contenteditable="true">  </div>
            </td>
            <? endfor; ?>
            <td><div class="contentEditable no-border" contenteditable="true">  </div></td>
        </tr>
        <tr class="font-size-10">
            <td colspan="3">
            Estabelecimento de Ensino:
            <div class="contentEditable" contenteditable="true">  </div>
            </td>
            <td colspan="5">
            Cidade - Estado:
            <div class="contentEditable" contenteditable="true">  </div>
            </td>
            <td></td>
            <td colspan="7">
            Situação Final:
            <div class="contentEditable" contenteditable="true">  </div>
            </td>
        </tr>
        <? endfor ?>
    </table>
</div>