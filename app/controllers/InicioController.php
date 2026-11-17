<?php
// Controller do painel inicial

class InicioController
{
    private Livro $livro;
    private Genero $genero;

    public function __construct(PDO $pdo)
    {
        $this->livro  = new Livro($pdo);
        $this->genero = new Genero($pdo);
    }

    public function index(): void
    {
        $generos = $this->genero->listarComTotal();

        // Maior quantidade de livros em um gênero (base para o tamanho das barras)
        $maior = 0;
        foreach ($generos as $g) {
            $maior = max($maior, (int) $g['total_livros']);
        }

        renderizar('inicio/painel', [
            'titulo'   => 'Painel',
            'resumo'   => $this->livro->resumo(),
            'recentes' => $this->livro->recentes(5),
            'generos'  => $generos,
            'maior'    => $maior,
        ]);
    }
}
