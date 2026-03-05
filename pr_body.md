## 🚀 Motivação
Release version 3.12.0 with complete student module refactoring and general improvements.

## 🔧 Alterações Realizadas
- Refatoração completa do módulo de estudantes para melhor arquitetura e desempenho (app/modules/student)
- Ajustes finos no arquivo de layout de menus e superusuários
- Melhorias na validação de importação de estudantes e integração Sentry
- Correções para as regras do Educacenso e módulos de frequência
- Atualizações contínuas de segurança e auditorias do sistema

## 📌 Requisitos
Nenhum novo requisito de infraestrutura.

## 🛠️ Fluxo de Teste

🧪 Fluxo de Teste 1 (FT1):
```
1. Fazer login no sistema dev
2. Criar ou editar um estudante
3. Verificar a matrícula e validação de documentos
```
✅ Sucesso:
❌ Falha:

## ✨ Migrations Utilizadas
Nenhum novo arquivo de migration na pasta app/migrations

## ✔️ Checklist - Padrões para PR
- [x] O número da versão foi alterado no arquivo ` config.php `?
- [x] Foi adicionada uma descrição das alterações no arquivo de   ` CHANGELOG `?
- [x] O pull request passou na avaliação do SonarLint?
- [x] O pull request está nomeado corretamente seguindo o padrão de nomes de branchs?

### Documentação
Houve alteração nos fluxo de uso?
*(Lembrete: Em caso afirmativo, adicionar label Atualização de manual)*
- [ ] Sim
- [x] Não
