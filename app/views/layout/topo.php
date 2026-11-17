<?php
// Topo comum a todas as páginas: <head>, menu e mensagem de aviso
$paginaAtual = $_GET['pagina'] ?? 'inicio';
$mensagem    = pegar_mensagem();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(($titulo ?? 'Início') . ' · ' . APP_NOME) ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= e(BASE_URL) ?>/assets/img/logo.svg">
    <link rel="stylesheet" href="<?= e(BASE_URL) ?>/assets/css/style.css">
</head>
<body>
    <header class="topo">
        <div class="topo-interno">
            <a class="marca" href="<?= e(url('inicio')) ?>">
                <img src="<?= e(BASE_URL) ?>/assets/img/logo.svg" alt="" width="36" height="36">
                <span><?= e(APP_NOME) ?></span>
            </a>
            <nav class="menu" aria-label="Menu principal">
                <a href="<?= e(url('inicio')) ?>" class="<?= $paginaAtual === 'inicio' ? 'ativo' : '' ?>">Painel</a>
                <a href="<?= e(url('livros')) ?>" class="<?= $paginaAtual === 'livros' ? 'ativo' : '' ?>">Livros</a>
                <a href="<?= e(url('generos')) ?>" class="<?= $paginaAtual === 'generos' ? 'ativo' : '' ?>">Gêneros</a>
            </nav>
        </div>
    </header>

    <main class="conteudo">
        <?php if ($mensagem): ?>
            <div class="alerta alerta-<?= e($mensagem['tipo']) ?>" role="status"><?= e($mensagem['texto']) ?></div>
        <?php endif; ?>
