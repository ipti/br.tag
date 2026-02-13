# Comandos SASS - Docker

## Visão Geral

O projeto usa o container Docker `michalklempa/dart-sass` para compilar arquivos SASS para CSS.

## Comandos Disponíveis

### 1. Build Único (Compilar uma vez)

```bash
composer sass:build
```

Compila todos os arquivos SASS de `sass/scss/` para `sass/css/` uma única vez.

**Quando usar:**
- Após fazer mudanças nos arquivos SASS
- Antes de fazer commit
- Para build de produção

---

### 2. Watch Mode (Compilação automática)

```bash
composer sass:watch
```

Inicia o container SASS em modo watch. Ele fica monitorando mudanças nos arquivos `.scss` e recompila automaticamente.

**Quando usar:**
- Durante desenvolvimento
- Quando estiver fazendo várias mudanças no SASS

**Para parar:** Pressione `Ctrl+C`

---

### 3. Comando Docker Direto

Se preferir usar Docker Compose diretamente:

```bash
# Build único
docker compose run --rm sass /opt/dart-sass/sass --no-source-map --style=compressed /sass:/css

# Watch mode
docker compose up sass
```

---

## Estrutura de Diretórios

```
br.tag/
├── sass/
│   ├── scss/          # Arquivos fonte SASS (.scss)
│   │   ├── main.scss
│   │   ├── _table.scss
│   │   ├── _button.scss
│   │   └── ...
│   └── css/           # Arquivos compilados CSS (gerados automaticamente)
│       └── main.css
```

---

## Configuração do Container

O container SASS está configurado no `docker-compose.yml`:

```yaml
sass:
  image: michalklempa/dart-sass
  volumes:
    - ./sass/scss:/sass
    - ./sass/css:/css
  entrypoint: [ "/opt/dart-sass/sass", "--watch", "--no-source-map", "--style=compressed", "/sass:/css" ]
```

**Opções:**
- `--watch` - Monitora mudanças automaticamente
- `--no-source-map` - Não gera arquivos .map
- `--style=compressed` - CSS minificado

---

## Exemplo de Uso

### Cenário 1: Mudança Rápida

```bash
# 1. Editar arquivo SASS
vim sass/scss/_table.scss

# 2. Compilar
composer sass:build

# 3. Verificar resultado
cat sass/css/main.css
```

### Cenário 2: Desenvolvimento Contínuo

```bash
# 1. Iniciar watch mode
composer sass:watch

# 2. Editar arquivos SASS (em outro terminal)
# Os arquivos CSS são atualizados automaticamente

# 3. Parar watch (quando terminar)
# Ctrl+C
```

---

## Troubleshooting

### Container não inicia

```bash
# Verificar se o container existe
docker compose ps

# Recriar o container
docker compose up --force-recreate sass
```

### CSS não está sendo gerado

```bash
# Verificar permissões dos diretórios
ls -la sass/scss
ls -la sass/css

# Executar build manualmente
docker compose run --rm sass /opt/dart-sass/sass /sass:/css
```

### Mudanças não aparecem no navegador

1. Limpar cache do navegador (Ctrl+Shift+R)
2. Verificar se o arquivo CSS foi realmente atualizado
3. Verificar se o layout está carregando o CSS correto
