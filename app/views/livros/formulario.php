<?php
// Mesmo formulário para cadastrar e editar. Variáveis: $dados, $erros, $acaoForm, $editando, $generos

// Mostra a mensagem de erro de um campo (se houver)
$erroDe = function (string $campo) use ($erros): string {
    return isset($erros[$campo]) ? '<p class="erro-campo">' . e($erros[$campo]) . '</p>' : '';
};
// Classe extra para destacar o campo com erro
$marca = function (string $campo) use ($erros): string {
    return isset($erros[$campo]) ? 'invalido' : '';
};
$valor = function (string $campo) use ($dados): string {
    return e($dados[$campo] ?? '');
};
$anoMaximo = (int) date('Y') + 1;
?>
<section class="cabecalho-pagina">
    <div>
        <p class="trilha"><a href="<?= e(url('livros')) ?>">Livros</a> / <?= $editando ? 'Editar' : 'Novo' ?></p>
        <h1><?= e($titulo) ?></h1>
    </div>
</section>

<?php if (!$generos): ?>
    <div class="cartao">
        <p class="vazio">Antes de cadastrar um livro, é preciso ter pelo menos um gênero.</p>
        <p style="text-align:center"><a class="btn btn-primario" href="<?= e(url('generos', 'novo')) ?>">Cadastrar gênero</a></p>
    </div>
<?php else: ?>
    <form class="cartao formulario" method="post" action="<?= e($acaoForm) ?>">
        <div class="campo">
            <label for="titulo">Título *</label>
            <input type="text" id="titulo" name="titulo" maxlength="150" required class="<?= $marca('titulo') ?>" value="<?= $valor('titulo') ?>">
            <?= $erroDe('titulo') ?>
        </div>

        <div class="grade-2">
            <div class="campo">
                <label for="autor">Autor *</label>
                <input type="text" id="autor" name="autor" maxlength="100" required class="<?= $marca('autor') ?>" value="<?= $valor('autor') ?>">
                <?= $erroDe('autor') ?>
            </div>
            <div class="campo">
                <label for="genero_id">Gênero *</label>
                <select id="genero_id" name="genero_id" required class="<?= $marca('genero_id') ?>">
                    <option value="">Selecione...</option>
                    <?php foreach ($generos as $g): ?>
                        <option value="<?= (int) $g['id'] ?>" <?= (int) ($dados['genero_id'] ?? 0) === (int) $g['id'] ? 'selected' : '' ?>><?= e($g['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
                <?= $erroDe('genero_id') ?>
            </div>
        </div>

        <div class="grade-4">
            <div class="campo">
                <label for="ano_publicacao">Ano de publicação</label>
                <input type="number" id="ano_publicacao" name="ano_publicacao" min="1000" max="<?= $anoMaximo ?>" class="<?= $marca('ano_publicacao') ?>" value="<?= $valor('ano_publicacao') ?>">
                <?= $erroDe('ano_publicacao') ?>
            </div>
            <div class="campo">
                <label for="isbn">ISBN</label>
                <input type="text" id="isbn" name="isbn" maxlength="17" placeholder="10 ou 13 dígitos" class="<?= $marca('isbn') ?>" value="<?= $valor('isbn') ?>">
                <?= $erroDe('isbn') ?>
            </div>
            <div class="campo">
                <label for="exemplares">Exemplares *</label>
                <input type="number" id="exemplares" name="exemplares" min="0" max="999" required class="<?= $marca('exemplares') ?>" value="<?= $valor('exemplares') ?>">
                <?= $erroDe('exemplares') ?>
            </div>
            <div class="campo">
                <label for="status">Status *</label>
                <select id="status" name="status" required class="<?= $marca('status') ?>">
                    <?php foreach (STATUS_LIVRO as $chave => $rotulo): ?>
                        <option value="<?= e($chave) ?>" <?= ($dados['status'] ?? 'disponivel') === $chave ? 'selected' : '' ?>><?= e($rotulo) ?></option>
                    <?php endforeach; ?>
                </select>
                <?= $erroDe('status') ?>
            </div>
        </div>

        <div class="campo">
            <label for="sinopse">Sinopse</label>
            <textarea id="sinopse" name="sinopse" rows="5" maxlength="2000" class="<?= $marca('sinopse') ?>"><?= $valor('sinopse') ?></textarea>
            <?= $erroDe('sinopse') ?>
        </div>

        <div class="formulario-acoes">
            <button type="submit" class="btn btn-primario"><?= $editando ? 'Salvar alterações' : 'Cadastrar livro' ?></button>
            <a class="btn btn-claro" href="<?= e(url('livros')) ?>">Cancelar</a>
        </div>
    </form>
<?php endif; ?>
