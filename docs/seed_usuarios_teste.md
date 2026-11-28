# Seed de usuários de teste

Cria um usuário de teste para cada um dos 9 perfis do sistema (`TRole`), para testes manuais, smoke HTTP e E2E em ambiente de desenvolvimento.

- Comando: `app/commands/SeedTestUsersCommand.php` (`yiic seedtestusers`).
- **Só roda em base de desenvolvimento**: se o banco não começar com `demo.`, o comando recusa.
- **Idempotente**: pode rodar quantas vezes precisar. Só mexe em usuários `teste_*`.
- **A senha nunca fica no repositório.** Ela vem de `--password=<senha>` ou da variável `TAG_SEED_PASSWORD`, e não é exibida nem gravada.

## Usuários criados

| Perfil | Usuário | Escola | Após o login |
|---|---|---|---|
| `superuser` | `teste_superuser` | todas as ativas | página inicial |
| `admin` | `teste_admin` | todas as ativas | página inicial |
| `manager` | `teste_manager` | 1 vinculada | página inicial |
| `coordinator` | `teste_coordinator` | 1 vinculada | página inicial |
| `instructor` | `teste_instructor` | 1 vinculada | página inicial |
| `reader` | `teste_reader` | todas as ativas | página inicial |
| `guardian` | `teste_guardian` | todas as ativas | redireciona para `enrollmentonline` |
| `nutritionist` | `teste_nutritionist` | todas as ativas | página inicial |
| `foodServiceWorker` | `teste_foodserviceworker` | 1 vinculada | página inicial |

Regras que o seed segue (de `UserIdentity::authenticate`):
- `admin`, `superuser`, `reader`, `nutritionist` e `guardian` enxergam todas as escolas ativas; não precisam de vínculo.
- `manager`, `coordinator`, `instructor` e `foodServiceWorker` precisam de um registro em `users_school`. O seed usa a escola ativa de menor INEP, ou a que você passar em `--school=<INEP>`.
- O `instructor` também ganha um cadastro mínimo em `instructor_identification` ("Teste Professor"), ligado por `users_fk`, porque as telas de professor dependem dele.
- Cada usuário de teste tem **exatamente um papel**. Se alguém atribuir outro papel a um `teste_*`, a próxima execução remove o excedente.

## Como executar

Na pasta do projeto, com o stack Docker no ar:

```bash
# simulação: mostra o que faria e desfaz tudo
docker exec -e TAG_SEED_PASSWORD='<senha>' tag-app php /app/app/yiic seedtestusers --dry-run

# criar ou completar os usuários
docker exec -e TAG_SEED_PASSWORD='<senha>' tag-app php /app/app/yiic seedtestusers

# redefinir a senha dos usuários que já existem
docker exec -e TAG_SEED_PASSWORD='<senha>' tag-app php /app/app/yiic seedtestusers --reset-password

# usar uma escola específica nos perfis que exigem vínculo
docker exec -e TAG_SEED_PASSWORD='<senha>' tag-app php /app/app/yiic seedtestusers --school=<INEP>
```

A senha precisa ter 6 caracteres ou mais (regra do modelo `Users`).

## Cuidados

- O console usa sempre o banco `demo.tag.ong.br` (`app/config/console.php`). Não há opção para apontar para outro tenant, e o comando recusa qualquer banco que não seja `demo.*`.
- São contas com o mesmo poder dos perfis reais, inclusive `superuser` e `admin`. Use uma senha de desenvolvimento, nunca a de produção.
- Esses usuários **não** devem existir em tenants de produção. Se um dia o seed for estendido para outros bancos, mantenha a trava e o prefixo `teste_`.
- Para o smoke HTTP de um card, lembre que o servidor local usa o cabeçalho `Host: localhost` para escolher o tenant `demo` (`instance.php`).
- Os testes de navegador continuam seguindo `.agents/rules/e2e-testing.md`: antes de rodar, peça a URL, as credenciais e a aprovação do plano de teste.

## Como verificar

```bash
# papel, escolas e situação de cada usuário de teste
docker exec mysql sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD" -t -e "SELECT u.username, GROUP_CONCAT(DISTINCT a.itemname) papeis, COUNT(DISTINCT us.school_fk) escolas, u.active FROM users u LEFT JOIN auth_assignment a ON a.userid=u.id LEFT JOIN users_school us ON us.user_fk=u.id WHERE u.username LIKE \"teste\\_%\" GROUP BY u.id;" "demo.tag.ong.br"'
```

Esperado: 9 linhas, um papel por usuário, `escolas = 1` para `manager`, `coordinator`, `instructor` e `foodServiceWorker`, e `0` para os demais.
