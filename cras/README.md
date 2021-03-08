## Staks de Desenvolvimento

- React
- Husky
- Lint Staged
- ESlint
- Prettier
- Commit Lint

## Commit Convencional

O commit segue o padrão
[convetional commit](https://www.conventionalcommits.org/en/v1.0.0/#summary).

Estrutura do padrão do commit:

```
<type>[optional scope]: <description>

[optional body]

[optional footer(s)]

```

Os tipos para commit são:

- build:, chore:, ci:, docs:, style:, refactor:, perf:, test:

A explicação para o uso de cada tipo está na documentação do link disponibilizado acima.

Exemplo de commit:

`git commit -m "chore: add files of configuration"`

### Variáveis de ambiente

Crie uma cópia do arquivo .env.example e e renomeie para .env

```bash
cp .env.example .env
```

Edite o arquivo criado no passo anterior e informe a URL da API

```bash
REACT_APP_API_URL=http://localhost/api
```

### Instalar dependências

Para instalar as dependências da aplicação execute o comando abaixo:

```bash
yarn add "dependência"
```

# Scripts disponíveis

### Execução em modo de desenvolvimento

```bash
yarn start
```

### Executar linter do código

```bash
yarn lint
```

### Criar build de produção

```bash
yarn build
```
