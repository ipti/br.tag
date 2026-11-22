# API do TAG

Rotas para sistemas e aparelhos externos. Hoje ela atende o reconhecimento facial da entrada dos alunos, que conta a presença para a merenda. Cada escola tem um Raspberry Pi.

Novas integrações entram neste módulo, e não espalhadas pelos outros.

## Endereço

Cada município tem o seu endereço do TAG, e a API responde nele mesmo:

```
https://<municipio>.tag.ong.br/?r=api/<controller>/<acao>
```

Exemplo: `https://santaluzia.tag.ong.br/?r=api/auth/token`.

Todas as respostas vêm em JSON (`Content-Type: application/json; charset=utf-8`).

## Autenticação

1. Cada aparelho recebe um `client_id` e um `client_secret`. A credencial fica presa a uma escola.
2. O aparelho troca essa credencial por um token JWT em `POST api/auth/token`. O token vale **1 hora**.
3. As outras rotas recebem o token no header `Authorization: Bearer <token>`.

Quando o token expira, a rota responde `401` com `token_expirado`. Basta pedir um novo token.

O token só funciona no município em que foi emitido e só dá acesso aos dados da escola da credencial. Se a credencial for desativada, o aparelho perde o acesso na hora, mesmo com um token ainda válido.

### `POST ?r=api/auth/token`

```json
{ "client_id": "tag_...", "client_secret": "..." }
```

Resposta `200`:

```json
{
  "access_token": "eyJ...",
  "token_type": "Bearer",
  "expires_in": 3600,
  "escola_id": "28012345"
}
```

## Alunos da escola

### `GET ?r=api/students/index[&ano=2026]`

Lista os alunos com matrícula ativa em alguma turma da escola do aparelho no ano letivo. Sem o parâmetro `ano`, usa o ano atual. Cada aluno aparece uma vez só, mesmo que tenha mais de uma matrícula na escola.

O `aluno_id` é o id do aluno no TAG. Ele **não muda de um ano para o outro**, enquanto a matrícula muda. É esse id que volta no envio da presença.

```json
{
  "escola_id": "28012345",
  "ano": 2026,
  "total": 2,
  "alunos": [
    { "aluno_id": 1532, "nome": "ANA MARIA DOS SANTOS" },
    { "aluno_id": 1877, "nome": "JOÃO PEDRO SOUZA" }
  ]
}
```

## Presença do dia para a merenda

### `POST ?r=api/mealattendance/save`

```json
{
  "escola_id": "28012345",
  "dia": "2026-11-18",
  "entrega": 3,
  "gerado_em": "2026-11-18T09:30:04-03:00",
  "total": 47,
  "presentes": [
    { "aluno_id": 1532, "nome": "ANA MARIA DOS SANTOS" }
  ],
  "meta": { "conferida": false, "completo": true, "sem_matricula": 0 }
}
```

Campos:

| Campo | Obrigatório | Regra |
|---|---|---|
| `escola_id` | sim | INEP da escola. Tem que ser a escola da credencial. |
| `dia` | sim | Dia da chamada, no formato `AAAA-MM-DD`. |
| `entrega` | sim | Número do envio no dia, a partir de 1. |
| `gerado_em` | não | Data e hora ISO 8601 em que o aparelho gerou a lista. |
| `total` | sim | Quantidade de presentes informada pelo aparelho. |
| `presentes` | sim | Lista dos presentes, que pode ser vazia. Cada item precisa de `aluno_id`. O `nome` é opcional e o TAG não usa. |
| `meta.conferida` | não | `true` se alguém revisou a lista. O padrão é `false`. |
| `meta.completo` | não | `false` enquanto ainda há alunos esperando reconhecimento. O padrão é `true`. |
| `meta.sem_matricula` | não | Deveria ser sempre 0. O padrão é `0`. |

Regras de gravação:

- Há **uma presença por escola e dia**. Fica gravada a entrega de **maior número**.
- **Entrega maior ou igual à gravada:** substitui a lista. Por isso reenviar a mesma entrega depois de uma falha é seguro.
- **Entrega menor que a gravada:** é ignorada. A resposta volta `200` com `status: "ignorado"`.
- **`aluno_id` que não existe no TAG:** o aluno não é gravado e volta em `alunos_ignorados`. Os demais alunos são gravados normalmente.

Resposta `200`:

```json
{
  "status": "gravado",
  "mensagem": "Presença gravada.",
  "escola_id": "28012345",
  "dia": "2026-11-18",
  "entrega_recebida": 3,
  "entrega_gravada": 3,
  "alunos_gravados": 47,
  "alunos_ignorados": []
}
```

## Erros

Todo erro vem no mesmo formato:

```json
{ "erro": { "codigo": "dados_invalidos", "mensagem": "Dados inválidos na presença.", "detalhes": { "dia": "Obrigatório, no formato AAAA-MM-DD." } } }
```

| Status | `codigo` | Quando |
|---|---|---|
| 400 | `json_invalido`, `credenciais_ausentes` | O corpo não é JSON, ou faltou `client_id` ou `client_secret`. |
| 401 | `credenciais_invalidas` | `client_id` ou `client_secret` errado, ou credencial desativada. |
| 401 | `token_ausente`, `token_invalido`, `token_expirado`, `cliente_inativo` | Falta o token, o token é inválido ou expirou, ou a credencial foi desativada. |
| 403 | `escola_nao_permitida`, `cliente_sem_escola` | A escola do envio não é a da credencial. |
| 405 | `metodo_nao_permitido` | Método HTTP errado para a rota. |
| 409 | `envio_simultaneo` | Duas entregas do mesmo dia chegaram ao mesmo tempo. Basta reenviar. |
| 422 | `dados_invalidos` | Campos inválidos. O `detalhes` lista cada um. |
| 500 | `api_nao_configurada`, `erro_interno` | Falta configuração no servidor, ou houve um erro inesperado. |

## Operação

**Chave de assinatura dos tokens.** Fica na variável de ambiente `TAG_API_JWT_SECRET`, com pelo menos 32 caracteres. Sem ela, todas as rotas respondem `500 api_nao_configurada`. Para gerar uma chave:

```
php -r "echo bin2hex(random_bytes(32));"
```

Trocar a chave invalida todos os tokens emitidos. Os aparelhos simplesmente pedem um token novo.

**Credenciais.** São criadas e gerenciadas com o comando de console, informando o banco do município:

```
php app/yiic apiclient create --db=santaluzia.tag.ong.br --school=28012345 --name="Raspberry Escola X"
php app/yiic apiclient list --db=santaluzia.tag.ong.br
php app/yiic apiclient deactivate --db=santaluzia.tag.ong.br --client=tag_...
php app/yiic apiclient activate --db=santaluzia.tag.ong.br --client=tag_...
```

O `client_secret` aparece uma única vez, no `create`. No banco fica só o hash. Se ele for perdido, crie uma credencial nova e desative a antiga.

**Tabelas.** Criadas pela migration `app/migrations/3.18.28/api_school_meal_attendance.sql`:
- `api_client`
- `school_meal_attendance`
- `school_meal_attendance_student`
