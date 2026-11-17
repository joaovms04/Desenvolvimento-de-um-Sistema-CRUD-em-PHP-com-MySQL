<?php
// Ponto de entrada do sistema: todas as páginas passam por aqui.
// Endereço no padrão:  index.php?pagina=livros&acao=editar&id=3

require_once __DIR__ . '/../config/config.php';
require_once RAIZ . '/config/database.php';
require_once RAIZ . '/app/helpers/funcoes.php';
require_once RAIZ . '/app/models/Genero.php';
require_once RAIZ . '/app/models/Livro.php';
require_once RAIZ . '/app/controllers/InicioController.php';
require_once RAIZ . '/app/controllers/LivroController.php';
require_once RAIZ . '/app/controllers/GeneroController.php';

$pdo = (new Database())->conectar();

// Mapa de rotas:  página => [controller, [ação => método do controller]]
$rotas = [
    'inicio' => ['InicioController', [
        'listar' => 'index',
    ]],
    'livros' => ['LivroController', [
        'listar'    => 'listar',
        'ver'       => 'ver',
        'novo'      => 'novo',
        'criar'     => 'criar',
        'editar'    => 'editar',
        'atualizar' => 'atualizar',
        'excluir'   => 'excluir',
    ]],
    'generos' => ['GeneroController', [
        'listar'    => 'listar',
        'novo'      => 'novo',
        'criar'     => 'criar',
        'editar'    => 'editar',
        'atualizar' => 'atualizar',
        'excluir'   => 'excluir',
    ]],
];

// Ações que alteram dados: só funcionam por POST (nunca por link)
$acoesPost = ['criar', 'atualizar', 'excluir'];

$pagina = $_GET['pagina'] ?? 'inicio';
$acao   = $_GET['acao'] ?? 'listar';
$id     = (int) ($_GET['id'] ?? 0);

// Página ou ação que não existe
if (!is_string($pagina) || !is_string($acao) || !isset($rotas[$pagina][1][$acao])) {
    http_response_code(404);
    renderizar('erro404', ['titulo' => 'Página não encontrada']);
    exit;
}

// Se tentarem abrir uma ação de alteração por link, volta para a lista
if (in_array($acao, $acoesPost, true) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirecionar(url($pagina));
}

[$classe, $metodos] = $rotas[$pagina];
$metodo     = $metodos[$acao];
$controller = new $classe($pdo);
$controller->$metodo($id);
