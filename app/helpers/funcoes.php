<?php
// Funções auxiliares usadas em todo o sistema

// Escapa texto antes de mostrar na tela (protege contra XSS)
function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

// Monta o endereço de uma página: url('livros', 'editar', ['id' => 3])
function url(string $pagina = 'inicio', string $acao = 'listar', array $extra = []): string
{
    $parametros = array_merge(['pagina' => $pagina, 'acao' => $acao], $extra);
    return BASE_URL . '/index.php?' . http_build_query($parametros);
}

// Pega um valor vindo de formulário como texto sem espaços nas pontas
// (se alguém mandar algo estranho, como uma lista, devolve texto vazio)
function texto($valor): string
{
    return is_string($valor) ? trim($valor) : '';
}

function redirecionar(string $destino): void
{
    header('Location: ' . $destino);
    exit;
}

// Guarda uma mensagem para aparecer na próxima página ('sucesso' ou 'erro')
function guardar_mensagem(string $tipo, string $texto): void
{
    $_SESSION['mensagem'] = ['tipo' => $tipo, 'texto' => $texto];
}

// Lê a mensagem guardada e já a apaga (aparece só uma vez)
function pegar_mensagem(): ?array
{
    if (!isset($_SESSION['mensagem'])) {
        return null;
    }
    $mensagem = $_SESSION['mensagem'];
    unset($_SESSION['mensagem']);
    return $mensagem;
}

// Converte a data do banco (2026-11-15 10:30:00) para 15/11/2026
function data_br(?string $data): string
{
    if (!$data) {
        return '—';
    }
    return date('d/m/Y', strtotime($data));
}

// Mostra a página: topo + conteúdo da view + rodapé
function renderizar(string $view, array $dados = []): void
{
    extract($dados);   // cada chave do array vira uma variável para a view
    require RAIZ . '/app/views/layout/topo.php';
    require RAIZ . '/app/views/' . $view . '.php';
    require RAIZ . '/app/views/layout/rodape.php';
}
