<?php
// Mesmo formulário para cadastrar e editar. Variáveis: $dados, $erros, $acaoForm, $editando
$erroDe = function (string $campo) use ($erros): string {
    return isset($erros[$campo]) ? '<p class="erro-campo">' . e($erros[$campo]) . '</p>' : '';
};
$marca = function (string $campo) use ($erros): string {
    return isset($erros[$campo]) ? 'invalido' : '';
};
$valor = function (string $campo) use ($dados): string {
    return e($dados[$campo] ?? '');
};
?>
<section class="cabecalho-pagina">
    <div>
        <p class="trilha"><a href="<?= e(url('generos')) ?>">Gêneros</a> / <?= $editando ? 'Editar' : 'Novo' ?></p>
        <h1><?= e($titulo) ?></h1>
    </div>
</section>

<form class="cartao formulario formulario-estreito" method="post" action="<?= e($acaoForm) ?>">
    <div class="campo">
        <label for="nome">Nome *</label>
        <input type="text" id="nome" name="nome" maxlength="60" required class="<?= $marca('nome') ?>" value="<?= $valor('nome') ?>">
        <?= $erroDe('nome') ?>
    </div>

    <div class="campo">
        <label for="descricao">Descrição</label>
        <input type="text" id="descricao" name="descricao" maxlength="255" class="<?= $marca('descricao') ?>" value="<?= $valor('descricao') ?>">
        <?= $erroDe('descricao') ?>
    </div>

    <div class="campo">
        <label for="cor">Cor da etiqueta</label>
        <input type="color" id="cor" name="cor" value="<?= $valor('cor') ?: '#1d5c63' ?>">
        <?= $erroDe('cor') ?>
    </div>

    <div class="formulario-acoes">
        <button type="submit" class="btn btn-primario"><?= $editando ? 'Salvar alterações' : 'Cadastrar gênero' ?></button>
        <a class="btn btn-claro" href="<?= e(url('generos')) ?>">Cancelar</a>
    </div>
</form>
