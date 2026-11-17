<?php
// Model Genero: conversa com a tabela "generos"

class Genero
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Lista simples (usada nos selects dos formulários)
    public function listar(): array
    {
        return $this->pdo->query('SELECT id, nome, descricao, cor FROM generos ORDER BY nome')->fetchAll();
    }

    // Lista cada gênero já com a quantidade de livros que ele possui
    public function listarComTotal(): array
    {
        $sql = 'SELECT g.id, g.nome, g.descricao, g.cor, COUNT(l.id) AS total_livros
                FROM generos g
                LEFT JOIN livros l ON l.genero_id = g.id
                GROUP BY g.id, g.nome, g.descricao, g.cor
                ORDER BY g.nome';
        return $this->pdo->query($sql)->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $st = $this->pdo->prepare('SELECT id, nome, descricao, cor FROM generos WHERE id = :id');
        $st->execute([':id' => $id]);
        $genero = $st->fetch();
        return $genero ?: null;
    }

    // Verifica se já existe outro gênero com o mesmo nome
    public function nomeExiste(string $nome, int $ignorarId = 0): bool
    {
        $st = $this->pdo->prepare('SELECT COUNT(*) FROM generos WHERE nome = :nome AND id <> :id');
        $st->execute([':nome' => $nome, ':id' => $ignorarId]);
        return (int) $st->fetchColumn() > 0;
    }

    public function totalDeLivros(int $id): int
    {
        $st = $this->pdo->prepare('SELECT COUNT(*) FROM livros WHERE genero_id = :id');
        $st->execute([':id' => $id]);
        return (int) $st->fetchColumn();
    }

    public function criar(array $dados): int
    {
        $st = $this->pdo->prepare('INSERT INTO generos (nome, descricao, cor) VALUES (:nome, :descricao, :cor)');
        $st->execute([
            ':nome'      => $dados['nome'],
            ':descricao' => $dados['descricao'] !== '' ? $dados['descricao'] : null,
            ':cor'       => $dados['cor'],
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function atualizar(int $id, array $dados): void
    {
        $st = $this->pdo->prepare('UPDATE generos SET nome = :nome, descricao = :descricao, cor = :cor WHERE id = :id');
        $st->execute([
            ':nome'      => $dados['nome'],
            ':descricao' => $dados['descricao'] !== '' ? $dados['descricao'] : null,
            ':cor'       => $dados['cor'],
            ':id'        => $id,
        ]);
    }

    public function excluir(int $id): void
    {
        $st = $this->pdo->prepare('DELETE FROM generos WHERE id = :id');
        $st->execute([':id' => $id]);
    }
}
