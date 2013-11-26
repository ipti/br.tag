<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name . ' - Adicionar escola';
$this->breadcrumbs = array(
    'Adicionar escola',
);
?>
<h3 class="heading-mosaic">Adicionar escola</h3>

<div class="innerLR">
    <!-- Widget -->
    <div class="widget widget-tabs border-bottom-none">

        <!-- Widget heading -->
        <div class="widget-head">
            <ul>
                <li class="active"><a class="glyphicons edit" href="#account-details" data-toggle="tab"><i></i>Identificação</a></li>
                <li><a class="glyphicons settings" href="#account-settings" data-toggle="tab"><i></i>Infraestrutura</a></li>
            </ul>
        </div>
        <!-- // Widget heading END -->

        <div class="widget-body">
            <form class="form-horizontal" style="margin: 0;">
                <div class="tab-content" style="padding: 0;">

                    <!-- Tab content -->
                    <div class="tab-pane  active" id="account-details">

                        <!-- Row -->
                        <div class="row-fluid">

                            <!-- Column -->
                            <div class="span6">

                                <div class="separator"></div>

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Nome da escola</label>
                                    <div class="controls">
                                        <input type="text" value="" class="span10" />
                                        <span style="margin: 0;" class="btn-action single glyphicons circle_question_mark" data-toggle="tooltip" data-placement="top" data-original-title="Nome completo da escola"><i></i></span>
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <div class="separator"></div>

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">CEP</label>
                                    <div class="controls">
                                        <input type="text" value="" class="span10" />
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Endereço</label>
                                    <div class="controls">
                                        <input type="text" value="" class="span7" />
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Número</label>
                                    <div class="controls">
                                        <input type="text" value="" class="input-mini" />
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Complemento</label>
                                    <div class="controls">
                                        <input type="text" value="" class="span10" />
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Bairro</label>
                                    <div class="controls">
                                        <input type="text" value="" class="span10" />
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Município</label>
                                    <div class="controls">
                                        <input type="text" value="" class="span10" />
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Distrito</label>
                                    <div class="controls">
                                        <input type="text" value="" class="span10" />
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <div class="separator"></div>

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Telefone</label>
                                    <div class="controls">
                                        <input type="text" value="" class="span10" />
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Outro telefone</label>
                                    <div class="controls">
                                        <input type="text" value="" class="span10" />
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">E-mail</label>
                                    <div class="controls">
                                        <input type="text" value="" class="span10" />
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <div class="separator"></div>	
                            </div>
                            <!-- // Column END -->

                            <!-- Column -->
                            <div class="span6">

                                <div class="separator"></div>

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Situação</label>
                                    <div class="controls">
                                        <select class="span7">
                                            <option>Em atividade</option>
                                            <option>Paralisada</option>
                                            <option>Extinta</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Início do ano letivo</label>
                                    <div class="controls">
                                        <div class="input-append">
                                            <input type="text" id="datepicker" class="span12" value="13/06/1988" />
                                            <span class="add-on glyphicons calendar"><i></i></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Término do ano letivo</label>
                                    <div class="controls">
                                        <div class="input-append">
                                            <input type="text" id="datepicker" class="span12" value="13/06/1988" />
                                            <span class="add-on glyphicons calendar"><i></i></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- // Group END -->
                            </div>
                            <!-- // Column END -->

                        </div>
                        <!-- // Row END -->

                        <div class="separator line bottom"></div>

                        <!-- Form actions -->
                        <div class="form-actions" style="margin: 0;">
                            <button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Cadastrar</button>
                            <button type="button" class="btn btn-icon btn-default glyphicons circle_remove"><i></i>Cancelar</button>
                        </div>
                        <!-- // Form actions END -->

                    </div>
                    <!-- // Tab content END -->

                    <!-- Tab content -->
                    <div class="tab-pane" id="account-settings">

                        <!-- Row -->
                        <div class="row-fluid">

                            <!-- Column -->
                            <div class="span3">
                                <strong>Funcionamento da escola</strong>
                                <p class="muted">Informações sobre o espaço utilizado pela escola</p>
                            </div>							
                            <!-- // Colunm END -->

                            <!-- Column -->
                            <div class="span9">

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Local de funcionamento</label>
                                    <div class="widget-body uniformjs margin-left">
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Pŕedio escolar
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Templo/Igreja
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Sala de empresa
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Casa do professor
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Salas em outra escola
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Galpão/rancho/paiol/galpão
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Unidade de internação/prisional
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Outros
                                        </label>
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Forma de ocupação do prédio</label>
                                    <div class="widget-body uniformjs margin-left">
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Pŕoprio
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Alugado
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Cedido
                                        </label>
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Prédio compartilhado com outra escola</label>
                                    <div class="widget-body uniformjs margin-left">
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Sim
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Não
                                        </label><br/>
                                    </div>
                                    <!-- // Group END -->

                                    <!-- Group -->
                                    <div class="control-group">
                                        <label class="control-label">Código da escola com a qual compartilha</label>
                                        <div class="widget-body">
                                            <div class="controls">
                                                <input type="text" value="" class="input-large" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- // Group END -->
                                </div>
                                <!-- // Colunm END -->

                            </div>

                        </div>
                        <!-- // Row END -->

                        <div class="separator"></div>

                        <!-- Row -->
                        <div class="row-fluid">

                            <!-- Column -->
                            <div class="span3">
                                <strong>Abastecimento</strong>
                                <p class="muted">Informações de água, luz, esgoto.</p>
                            </div>							
                            <!-- // Colunm END -->

                            <!-- Column -->
                            <div class="span9">

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Água consumida pelos alunos</label>
                                    <div class="widget-body uniformjs margin-left">
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Filtrada
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Não filtrada
                                        </label><br/>
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Abastecimento de água</label>
                                    <div class="widget-body uniformjs margin-left">
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Rede pública
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Poço artesiano
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Cacimba/cisterna/poço
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Fonte/rio/igarapé/riacho/córrego
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Inexistente
                                        </label><br/>
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Abastecimento de energia elétrica</label>
                                    <div class="widget-body uniformjs margin-left">
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Rede pública
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Gerador
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Outros
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Inexistente
                                        </label><br/>
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Esgoto sanitário</label>
                                    <div class="widget-body uniformjs margin-left">
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Rede pública
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Fossa
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Inexistente
                                        </label><br/>
                                    </div>
                                </div>
                                <!-- // Group END -->

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Destinação do lixo</label>
                                    <div class="widget-body uniformjs margin-left">
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Coleta periódica
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Queima
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Joga em ouitra área
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Recicla
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Enterra
                                        </label><br/>
                                        <label class="radio">
                                            <input type="radio" class="radio" name="radio" value="1" />
                                            Outros
                                        </label><br/>
                                    </div>
                                </div>
                                <!-- // Group END -->

                            </div>

                        </div>
                        <!-- // Row END -->

                        <div class="separator"></div>

                        <!-- Row -->
                        <div class="row-fluid">

                            <!-- Column -->
                            <div class="span3">
                                <strong>Estrutura</strong>
                                <p class="muted">Dependências e estrtura da escola</p>
                            </div>							
                            <!-- // Colunm END -->

                            <!-- Column -->
                            <div class="span9">

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label">Local de funcionamento</label>
                                    <div class="widget-body uniformjs margin-left">
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Sala de diretoria
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Sala de professores
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Sala de secretaria
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Laboratório de informática
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Laboratório de ciências
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Sala de recursos multifuncionais para Atendimento Educacional Especializado 
                                            (AEE)
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Quadra de esportes coberta
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Quadra de esporte descoberta
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Cozinha
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Biblioteca
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Sala de leitura
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Parque infantil
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Berçário
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Banheiro fora do prédio
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Banheiro dentro do prédio
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Banheiro adequado a educação infantil
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Banheiro adequado a alunos com deficiência ou mobilidade reduzida
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Dependências e vias adequadas a alunos com deficiência ou mobildiade reduzida
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Banheiro com chuveiro
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Refeitório
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Despensa
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Almoxarifado
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Auditório
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Pátio coberto
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Pátio descoberto
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Alojamento de aluno
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Alojamento de professor
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Área verde
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Lavanderia
                                        </label>
                                        <label class="checkbox">
                                            <input type="checkbox" class="checkbox" value="1" />
                                            Nenhuma das relacionadas
                                        </label>
                                    </div>
                                </div>
                                <!-- // Group END -->


                            </div>

                        </div>
                        <!-- // Row END -->

                        <div class="separator line bottom"></div>

                        <!-- Form actions -->
                        <div class="form-actions" style="margin: 0;">
                            <button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Cadastrar</button>
                            <button type="button" class="btn btn-icon btn-default glyphicons circle_remove"><i></i>Cancelar</button>
                        </div>
                        <!-- // Form actions END -->

                    </div>
                    <!-- // Tab content END -->
                </div>
            </form>
        </div>
    </div>
    <!-- // Widget END -->
</div>