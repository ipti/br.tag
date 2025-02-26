# Organização das Migrações

Este repositório contém migrações organizadas por `ano` e `mês` para facilitar a manutenção e a navegação das alterações no banco de dados ao longo do tempo.

## Estrutura de Diretórios

- `1v2` e `1v3`: Migrações específicas de versões antigas do projeto.
- `2022`, `2023`, `2024`: Diretórios de migrações organizados por ano.
  - `01`, `02`, ..., `12`: Subdiretórios dentro de cada ano, correspondentes a cada mês.
  - Dentro de cada subdiretório mensal, as migrações são organizadas por data e nome.

    ```
    2023
        ├───01
        ├───02
        ├───03
            ├───2023-03-05_edcenso_alias
            └───2023-03-05_grade_results
        ...
        └───12
            ├───2023-12-06_create_id_alias_column
            ├───2023-12-11_create_instance_config
            ├───2023-12-15_create_food_request_table
            └───2023-12-26_refactor_grades_rules
    ```


## Como Adicionar Novas Migrações

1. **Navegue até o diretório do ano atual**
2. **Dentro do diretório do mês atual, crie uma nova pasta para a migração**
    - Nomeie a pasta seguindo o padrão `YYYY-MM-DD_descrição`, onde YYYY-MM-DD é a data da migração e descrição é um breve resumo da mudança.

        ```
        2024-07-15_add_new_feature  # Exemplo de nomeação
        ```
    
3. **Adicione os arquivos da migração à nova pasta.**


## Notas Adicionais
Certifique-se de manter a consistência na nomeação das pastas para facilitar a navegação e manutenção.