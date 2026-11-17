<?php
// Controller de Livros: recebe o pedido, valida, chama o model e escolhe a view

class LivroController
{
    private Livro $livro;
    private Genero $genero;

    public function __construct(PDO $pdo)
    {
        $this->livro  = new Livro($pdo);
        $this->genero = new Genero($pdo);
    }

    // READ: lista com busca e filtros
    public function listar(): void
    {
        $filtros = [
            'busca'  => texto($_GET['busca'] ?? ''),
            'genero' => (int) ($_GET['genero'] ?? 0),
            'status' => texto($_GET['status'] ?? ''),
        ];
        if (!isset(STATUS_LIVRO[$filtros['status']])) {
            $filtros['status'] = '';
        }

        renderizar('livros/listar', [
            'titulo'  => 'Livros',
            'livros'  => $this->livro->listar($filtros),
            'generos' => $this->genero->listar(),
            'filtros' => $filtros,
        ]);
    }

    // READ: detalhes de um livro
    public function ver(int $id): void
    {
        $livro = $this->buscarOuVoltar($id);
        renderizar('livros/ver', ['titulo' => $livro['titulo'], 'livro' => $livro]);
    }

    // CREATE: mostra o formulário vazio
    public function novo(): void
    {
        $this->mostrarFormulario('Novo livro', ['status' => 'disponivel', 'exemplares' => 1], [], url('livros', 'criar'), false);
    }

    // CREATE: recebe o formulário
    public function criar(): void
    {
        [$dados, $erros] = $this->validar($_POST);

        if ($erros) {
            $this->mostrarFormulario('Novo livro', $dados, $erros, url('livros', 'criar'), false);
            return;
        }

        $this->livro->criar($dados);
        guardar_mensagem('sucesso', 'Livro "' . $dados['titulo'] . '" cadastrado com sucesso!');
        redirecionar(url('livros'));
    }

    // UPDATE: mostra o formulário preenchido
    public function editar(int $id): void
    {
        $livro = $this->buscarOuVoltar($id);
        $this->mostrarFormulario('Editar livro', $livro, [], url('livros', 'atualizar', ['id' => $id]), true);
    }

    // UPDATE: recebe o formulário
    public function atualizar(int $id): void
    {
        $this->buscarOuVoltar($id);
        [$dados, $erros] = $this->validar($_POST, $id);

        if ($erros) {
            $this->mostrarFormulario('Editar livro', $dados, $erros, url('livros', 'atualizar', ['id' => $id]), true);
            return;
        }

        $this->livro->atualizar($id, $dados);
        guardar_mensagem('sucesso', 'Livro "' . $dados['titulo'] . '" atualizado com sucesso!');
        redirecionar(url('livros'));
    }

    // DELETE
    public function excluir(int $id): void
    {
        $livro = $this->buscarOuVoltar($id);
        $this->livro->excluir($id);
        guardar_mensagem('sucesso', 'Livro "' . $livro['titulo'] . '" excluído.');
        redirecionar(url('livros'));
    }

    // ---------- métodos de apoio ----------

    // Procura o livro; se não existir, avisa e volta para a lista
    private function buscarOuVoltar(int $id): array
    {
        $livro = $id > 0 ? $this->livro->buscar($id) : null;
        if ($livro === null) {
            guardar_mensagem('erro', 'Livro não encontrado.');
            redirecionar(url('livros'));
        }
        return $livro;
    }

    private function mostrarFormulario(string $titulo, array $dados, array $erros, string $acaoForm, bool $editando): void
    {
        renderizar('livros/formulario', [
            'titulo'    => $titulo,
            'dados'     => $dados,
            'erros'     => $erros,
            'acaoForm'  => $acaoForm,
            'editando'  => $editando,
            'generos'   => $this->genero->listar(),
        ]);
    }

    // Confere os campos do formulário. Devolve [dados limpos, lista de erros].
    private function validar(array $entrada, int $id = 0): array
    {
        $dados = [
            'titulo'         => texto($entrada['titulo'] ?? ''),
            'autor'          => texto($entrada['autor'] ?? ''),
            'ano_publicacao' => texto($entrada['ano_publicacao'] ?? ''),
            'isbn'           => strtoupper(preg_replace('/[\s-]/', '', texto($entrada['isbn'] ?? ''))),
            'sinopse'        => texto($entrada['sinopse'] ?? ''),
            'exemplares'     => texto($entrada['exemplares'] ?? ''),
            'status'         => texto($entrada['status'] ?? ''),
            'genero_id'      => (int) ($entrada['genero_id'] ?? 0),
        ];
        $erros = [];

        if ($dados['titulo'] === '') {
            $erros['titulo'] = 'Informe o título do livro.';
        } elseif (mb_strlen($dados['titulo']) > 150) {
            $erros['titulo'] = 'O título pode ter no máximo 150 caracteres.';
        }

        if ($dados['autor'] === '') {
            $erros['autor'] = 'Informe o autor.';
        } elseif (mb_strlen($dados['autor']) > 100) {
            $erros['autor'] = 'O nome do autor pode ter no máximo 100 caracteres.';
        }

        if ($dados['ano_publicacao'] !== '') {
            $anoMaximo = (int) date('Y') + 1;
            if (!ctype_digit($dados['ano_publicacao'])) {
                $erros['ano_publicacao'] = 'Informe o ano usando apenas números.';
            } elseif ((int) $dados['ano_publicacao'] < 1000 || (int) $dados['ano_publicacao'] > $anoMaximo) {
                $erros['ano_publicacao'] = "O ano deve ficar entre 1000 e {$anoMaximo}.";
            }
        }

        if ($dados['isbn'] !== '') {
            if (!preg_match('/^(\d{13}|\d{9}[\dX])$/', $dados['isbn'])) {
                $erros['isbn'] = 'O ISBN deve ter 10 ou 13 dígitos.';
            } elseif ($this->livro->isbnExiste($dados['isbn'], $id)) {
                $erros['isbn'] = 'Já existe um livro cadastrado com este ISBN.';
            }
        }

        if (mb_strlen($dados['sinopse']) > 2000) {
            $erros['sinopse'] = 'A sinopse pode ter no máximo 2000 caracteres.';
        }

        if ($dados['exemplares'] === '') {
            $erros['exemplares'] = 'Informe a quantidade de exemplares.';
        } elseif (!ctype_digit($dados['exemplares'])) {
            $erros['exemplares'] = 'Use apenas números inteiros (0 ou mais).';
        } elseif ((int) $dados['exemplares'] > 999) {
            $erros['exemplares'] = 'O máximo é 999 exemplares.';
        }

        if (!isset(STATUS_LIVRO[$dados['status']])) {
            $erros['status'] = 'Escolha um status válido.';
        }

        if ($this->genero->buscar($dados['genero_id']) === null) {
            $erros['genero_id'] = 'Escolha um gênero da lista.';
        }

        return [$dados, $erros];
    }
}
