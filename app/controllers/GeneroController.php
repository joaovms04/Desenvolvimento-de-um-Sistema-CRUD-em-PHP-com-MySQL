<?php
// Controller de Gêneros: recebe o pedido, valida, chama o model e escolhe a view

class GeneroController
{
    private Genero $genero;

    public function __construct(PDO $pdo)
    {
        $this->genero = new Genero($pdo);
    }

    // READ: lista os gêneros com a quantidade de livros de cada um
    public function listar(): void
    {
        renderizar('generos/listar', [
            'titulo'  => 'Gêneros',
            'generos' => $this->genero->listarComTotal(),
        ]);
    }

    // CREATE: formulário vazio
    public function novo(): void
    {
        $this->mostrarFormulario('Novo gênero', ['cor' => '#1d5c63'], [], url('generos', 'criar'), false);
    }

    // CREATE: recebe o formulário
    public function criar(): void
    {
        [$dados, $erros] = $this->validar($_POST);

        if ($erros) {
            $this->mostrarFormulario('Novo gênero', $dados, $erros, url('generos', 'criar'), false);
            return;
        }

        $this->genero->criar($dados);
        guardar_mensagem('sucesso', 'Gênero "' . $dados['nome'] . '" cadastrado com sucesso!');
        redirecionar(url('generos'));
    }

    // UPDATE: formulário preenchido
    public function editar(int $id): void
    {
        $genero = $this->buscarOuVoltar($id);
        $this->mostrarFormulario('Editar gênero', $genero, [], url('generos', 'atualizar', ['id' => $id]), true);
    }

    // UPDATE: recebe o formulário
    public function atualizar(int $id): void
    {
        $this->buscarOuVoltar($id);
        [$dados, $erros] = $this->validar($_POST, $id);

        if ($erros) {
            $this->mostrarFormulario('Editar gênero', $dados, $erros, url('generos', 'atualizar', ['id' => $id]), true);
            return;
        }

        $this->genero->atualizar($id, $dados);
        guardar_mensagem('sucesso', 'Gênero "' . $dados['nome'] . '" atualizado com sucesso!');
        redirecionar(url('generos'));
    }

    // DELETE: só exclui se o gênero não tiver livros (protege o acervo)
    public function excluir(int $id): void
    {
        $genero = $this->buscarOuVoltar($id);
        $total  = $this->genero->totalDeLivros($id);

        if ($total > 0) {
            guardar_mensagem('erro', 'Não é possível excluir "' . $genero['nome'] . '": ainda existem '
                . $total . ' livro(s) nesse gênero. Mude ou exclua esses livros primeiro.');
            redirecionar(url('generos'));
        }

        try {
            $this->genero->excluir($id);
            guardar_mensagem('sucesso', 'Gênero "' . $genero['nome'] . '" excluído.');
        } catch (PDOException $e) {
            // O banco também bloqueia (chave estrangeira), caso algum livro tenha sido criado agora
            guardar_mensagem('erro', 'Não foi possível excluir o gênero porque ele está em uso.');
        }
        redirecionar(url('generos'));
    }

    // ---------- métodos de apoio ----------

    private function buscarOuVoltar(int $id): array
    {
        $genero = $id > 0 ? $this->genero->buscar($id) : null;
        if ($genero === null) {
            guardar_mensagem('erro', 'Gênero não encontrado.');
            redirecionar(url('generos'));
        }
        return $genero;
    }

    private function mostrarFormulario(string $titulo, array $dados, array $erros, string $acaoForm, bool $editando): void
    {
        renderizar('generos/formulario', [
            'titulo'   => $titulo,
            'dados'    => $dados,
            'erros'    => $erros,
            'acaoForm' => $acaoForm,
            'editando' => $editando,
        ]);
    }

    private function validar(array $entrada, int $id = 0): array
    {
        $dados = [
            'nome'      => texto($entrada['nome'] ?? ''),
            'descricao' => texto($entrada['descricao'] ?? ''),
            'cor'       => strtolower(texto($entrada['cor'] ?? '')),
        ];
        $erros = [];

        if ($dados['nome'] === '') {
            $erros['nome'] = 'Informe o nome do gênero.';
        } elseif (mb_strlen($dados['nome']) < 2 || mb_strlen($dados['nome']) > 60) {
            $erros['nome'] = 'O nome deve ter entre 2 e 60 caracteres.';
        } elseif ($this->genero->nomeExiste($dados['nome'], $id)) {
            $erros['nome'] = 'Já existe um gênero com esse nome.';
        }

        if (mb_strlen($dados['descricao']) > 255) {
            $erros['descricao'] = 'A descrição pode ter no máximo 255 caracteres.';
        }

        if (!preg_match('/^#[0-9a-f]{6}$/', $dados['cor'])) {
            $erros['cor'] = 'Escolha uma cor válida.';
        }

        return [$dados, $erros];
    }
}
