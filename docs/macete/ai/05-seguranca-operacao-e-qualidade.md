# Segurança, operação e qualidade

Status: requisitos para desenvolvimento e homologação — não implementado.

## Privacidade e minimização

- Não enviar ao serviço de IA nomes, CPF, e-mail, matrículas, dados sensíveis ou desempenho individual de estudantes.
- Enviar somente dados necessários do plano: etapa, componente, habilidades, tema e conteúdo do rascunho que o professor decidiu usar na conversa.
- Usar identificador interno/pseudonimizado do professor no lugar do nome.
- Definir formalmente retenção, descarte e acesso às conversas antes de produção.
- Validar base legal, transferências internacionais, contrato com o provedor e requisitos da LGPD com as áreas responsáveis.

## Isolamento e autorização

- O TAG valida feature, escola, ano e autoria antes de cada chamada.
- O serviço valida `tenant_id` em criação, leitura e mensagem de conversa; uma conversa não pode ser acessada por outro tenant.
- Serviço e banco próprio ficam em rede privada quando possível.
- Secrets são variáveis de ambiente/secret manager; nunca código, JavaScript, banco do TAG, payload ou log.
- Logs devem mascarar tokens e não registrar dados desnecessários do prompt.

## Auditoria e rastreabilidade

Para cada resposta, registrar:

- conversa, tenant e identificador pseudonimizado do solicitante;
- versão do agente/prompt, provedor e modelo;
- data, latência, resultado e consumo;
- IDs e versões dos trechos recuperados;
- propostas devolvidas e, no TAG, propostas aplicadas quando o produto exigir essa evidência.

Não registrar raciocínio interno do modelo.

## Observabilidade

Métricas mínimas:

- quantidade de conversas e mensagens;
- latência do serviço, recuperação e provedor;
- taxa de erro por código e por provedor;
- quantidade de respostas sem fonte ou com alerta;
- custo/tokens por tenant e por modelo;
- taxa de propostas aplicadas, quando disponível.

Alertas iniciais: indisponibilidade do provedor, aumento de `5xx`, timeout, falha de indexação e consumo acima do limite definido.

## Estratégia de testes

| Camada | Cenários mínimos |
| --- | --- |
| Serviço Python | validação dos schemas, isolamento por tenant, filtros de recuperação, saída inválida, timeout e mapeamento de provedor. |
| RAG | perguntas de referência com fonte esperada, filtros por etapa/componente, versão inativa e ausência de evidência. |
| TAG/PHP | autorização antes da chamada, payload mínimo, normalização de erro e bloqueio de campos não permitidos. |
| Frontend | criação/retomada, estado de carregamento, erro sem perda de rascunho, aplicação granular e sincronização do editor rico. |
| E2E | conversa com plano permitido, bloqueio de usuário sem feature, aplicação e salvamento posterior. |

Mocks devem substituir provedores externos nos testes automatizados. A homologação pode usar credencial separada e corpus de documentos aprovado para testes.

## Implantação por fases

1. Criar serviço, banco isolado, healthcheck e CI sem expor rota ao TAG.
2. Implementar ingestão e ativar somente uma versão revisada da BNCC em homologação.
3. Integrar o TAG atrás de feature flag/configuração de instância, inicialmente para um grupo piloto.
4. Avaliar respostas, fontes, custos e erros com professores responsáveis.
5. Ativar gradualmente para novas escolas/rede após aprovação funcional e de privacidade.

## Critérios de aceite da primeira entrega

- O professor autorizado conversa com o assistente e recebe uma resposta completa, sem streaming.
- Uma sugestão só altera o rascunho quando o professor usa “Aplicar”.
- Salvar o plano continua passando pelas validações atuais do MACETE.
- Cada alegação curricular baseada em RAG mostra fonte, versão e localização verificável.
- Conversas e documentos são isolados por tenant; o serviço não acessa o MySQL do TAG.
- Falha do serviço/modelo não impede o preenchimento e salvamento manual do plano.
- Nenhuma chave ou endpoint interno do provedor aparece no navegador.
- Os testes e a homologação definidos para cada camada possuem evidência registrada.
