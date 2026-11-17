# Biblioteca Viva — CRUD em PHP e MySQL

> **Disciplina:** Linguagem e Técnicas de Programação
> **Trabalho:** Sistema CRUD completo em PHP com MySQL (Parte 02)
 - **Integrantes do grupo:**
 - 26008802-2 - João Marchezan
 - 26013906-2 - Lucas Gabriel
 - 26010098-2 - Rafael José Dos Santos
 - 26011979-2 - Rafael.K
 - 26008950-2 - Vinicius de Medeiros
 - 26009345-2 - vithor do carmo

Sistema web para gerenciar o acervo de uma biblioteca. Ele cadastra **livros** e **gêneros**, e cada livro pertence a um gênero (relacionamento 1:N). O projeto usa PHP com PDO, MySQL e uma organização em camadas (MVC simplificado).

## Funcionalidades

- **Painel inicial** com números do acervo (títulos, exemplares, disponíveis, emprestados/reservados), gráfico de barras por gênero e últimos livros cadastrados.
- **Livros:** cadastrar, listar, ver detalhes, editar e excluir; busca por título ou autor; filtro por gênero e por status.
- **Gêneros:** cadastrar, listar (com a quantidade de livros de cada um), editar e excluir, cada um com uma cor de etiqueta.
- **Validação** dos formulários no servidor, com mensagem em cada campo e os dados preservados em caso de erro.
- **Avisos** de sucesso e erro após cada operação.
- **Exclusão segura:** feita por formulário POST, com confirmação, e o banco impede apagar um gênero que ainda tenha livros.

## Requisitos

- XAMPP (Apache + MySQL/MariaDB) com PHP 7.4 ou superior
- Navegador atualizado


## Banco de dados

```
generos                         livros
-------------------             ------------------------------
id (PK)                         id (PK)
nome (único)                    titulo
descricao                       autor
cor                             ano_publicacao
criado_em                       isbn (único, opcional)
                                sinopse
        1 ───────────── N       exemplares
                                status (disponivel | emprestado | reservado)
                                genero_id (FK → generos.id)
                                criado_em
                                atualizado_em (atualizado automaticamente)
```

A chave estrangeira usa `ON DELETE RESTRICT`: não é possível excluir um gênero que ainda tenha livros.

## Estrutura do projeto

```
biblioteca/
├── index.php                  # redireciona para public/
├── banco/
│   └── biblioteca.sql         # script de criação do banco + dados de exemplo
├── config/
│   ├── config.php             # nome do sistema, URL base automática, status dos livros
│   └── database.php           # conexão PDO com o MySQL
├── app/
│   ├── models/                # acesso ao banco (Genero.php, Livro.php)
│   ├── controllers/           # validação e fluxo (Inicio, Livro, Genero)
│   ├── helpers/funcoes.php    # funções auxiliares (e(), url(), mensagens, renderizar())
│   └── views/                 # telas: layout/, inicio/, livros/, generos/
└── public/
    ├── index.php              # roteador (ponto de entrada)
    └── assets/                # css/, js/ e img/
```

