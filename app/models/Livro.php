<?php
// Model Livro: conversa com a tabela "livros"

class Livro
{
    private PDO $pdo;

    // Colunas que as telas de lista e detalhe precisam (livro + dados do gênero)
    private string $selecao = 'l.id, l.titulo, l.autor, l.ano_publicacao, l.isbn, l.sinopse, l.exemplares,
                               l.status, l.genero_id, l.criado_em, l.atualizado_em,
                               g.nome AS genero_nome, g.cor AS genero_cor';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Lista os livros aplicando os filtros opcionais: busca, genero e status
    public function listar(array $filtros = []): array
    {
        $sql = "SELECT {$this->selecao}
                FROM livros l
                INNER JOIN generos g ON g.id = l.genero_id
                WHERE 1 = 1";
        $parametros = [];

        if (!empty($filtros['busca'])) {
            // Um nome de parâmetro não pode se repetir, por isso :busca_titulo e :busca_autor
            $sql .= ' AND (l.titulo LIKE :busca_titulo OR l.autor LIKE :busca_autor)';
            $termo = '%' . $filtros['busca'] . '%';
            $parametros[':busca_titulo'] = $termo;
            $parametros[':busca_autor']  = $termo;
        }
        if (!empty($filtros['genero'])) {
            $sql .= ' AND l.genero_id = :genero';
            $parametros[':genero'] = (int) $filtros['genero'];
        }
        if (!empty($filtros['status'])) {
            $sql .= ' AND l.status = :status';
            $parametros[':status'] = $filtros['status'];
        }

        $sql .= ' ORDER BY l.titulo';

        $st = $this->pdo->prepare($sql);
        $st->execute($parametros);
        return $st->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $st = $this->pdo->prepare(
            "SELECT {$this->selecao}
             FROM livros l
             INNER JOIN generos g ON g.id = l.genero_id
             WHERE l.id = :id"
        );
        $st->execute([':id' => $id]);
        $livro = $st->fetch();
        return $livro ?: null;
    }

    // Últimos livros cadastrados (usado no painel inicial)
    public function recentes(int $limite = 5): array
    {
        $limite = max(1, $limite);   // convertido para inteiro, então é seguro usar direto no SQL
        return $this->pdo->query(
            "SELECT {$this->selecao}
             FROM livros l
             INNER JOIN generos g ON g.id = l.genero_id
             ORDER BY l.criado_em DESC, l.id DESC
             LIMIT {$limite}"
        )->fetchAll();
    }

    // Números do painel inicial
    public function resumo(): array
    {
        $sql = "SELECT COUNT(*) AS titulos,
                       COALESCE(SUM(exemplares), 0) AS exemplares,
                       COALESCE(SUM(CASE WHEN status = 'disponivel' THEN 1 ELSE 0 END), 0) AS disponiveis,
                       COALESCE(SUM(CASE WHEN status = 'emprestado' THEN 1 ELSE 0 END), 0) AS emprestados,
                       COALESCE(SUM(CASE WHEN status = 'reservado' THEN 1 ELSE 0 END), 0) AS reservados
                FROM livros";
        $resumo = $this->pdo->query($sql)->fetch();
        return array_map('intval', $resumo);
    }

    // Verifica se já existe outro livro com o mesmo ISBN
    public function isbnExiste(string $isbn, int $ignorarId = 0): bool
    {
        $st = $this->pdo->prepare('SELECT COUNT(*) FROM livros WHERE isbn = :isbn AND id <> :id');
        $st->execute([':isbn' => $isbn, ':id' => $ignorarId]);
        return (int) $st->fetchColumn() > 0;
    }

    public function criar(array $dados): int
    {
        $st = $this->pdo->prepare(
            'INSERT INTO livros (titulo, autor, ano_publicacao, isbn, sinopse, exemplares, status, genero_id)
             VALUES (:titulo, :autor, :ano, :isbn, :sinopse, :exemplares, :status, :genero_id)'
        );
        $st->execute($this->parametros($dados));
        return (int) $this->pdo->lastInsertId();
    }

    public function atualizar(int $id, array $dados): void
    {
        $st = $this->pdo->prepare(
            'UPDATE livros
             SET titulo = :titulo, autor = :autor, ano_publicacao = :ano, isbn = :isbn,
                 sinopse = :sinopse, exemplares = :exemplares, status = :status, genero_id = :genero_id
             WHERE id = :id'
        );
        $st->execute($this->parametros($dados) + [':id' => $id]);
    }

    public function excluir(int $id): void
    {
        $st = $this->pdo->prepare('DELETE FROM livros WHERE id = :id');
        $st->execute([':id' => $id]);
    }

    // Prepara os valores para o INSERT/UPDATE (campos opcionais vazios viram NULL)
    private function parametros(array $dados): array
    {
        return [
            ':titulo'     => $dados['titulo'],
            ':autor'      => $dados['autor'],
            ':ano'        => $dados['ano_publicacao'] !== '' ? (int) $dados['ano_publicacao'] : null,
            ':isbn'       => $dados['isbn'] !== '' ? $dados['isbn'] : null,
            ':sinopse'    => $dados['sinopse'] !== '' ? $dados['sinopse'] : null,
            ':exemplares' => (int) $dados['exemplares'],
            ':status'     => $dados['status'],
            ':genero_id'  => (int) $dados['genero_id'],
        ];
    }
}
