<section class="cabecalho-pagina">
    <div>
        <p class="trilha"><a href="<?= e(url('livros')) ?>">Livros</a> / Detalhes</p>
        <h1><?= e($livro['titulo']) ?></h1>
        <p class="subtitulo">por <?= e($livro['autor']) ?></p>
    </div>
    <div class="acoes-linha">
        <a class="btn btn-claro" href="<?= e(url('livros', 'editar', ['id' => $livro['id']])) ?>">Editar</a>
        <form class="form-inline" method="post"
              action="<?= e(url('livros', 'excluir', ['id' => $livro['id']])) ?>"
              data-confirma="<?= e('Excluir o livro "' . $livro['titulo'] . '"? Esta ação não pode ser desfeita.') ?>">
            <button type="submit" class="btn btn-perigo">Excluir</button>
        </form>
    </div>
</section>

<section class="cartao">
    <div class="selos">
        <span class="chip"><i style="background: <?= e($livro['genero_cor']) ?>"></i><?= e($livro['genero_nome']) ?></span>
        <span class="selo selo-<?= e($livro['status']) ?>"><?= e(STATUS_LIVRO[$livro['status']] ?? $livro['status']) ?></span>
    </div>

    <dl class="dados">
        <div><dt>Ano de publicação</dt><dd><?= $livro['ano_publicacao'] ? (int) $livro['ano_publicacao'] : '—' ?></dd></div>
        <div><dt>ISBN</dt><dd><?= $livro['isbn'] ? e($livro['isbn']) : '—' ?></dd></div>
        <div><dt>Exemplares</dt><dd><?= (int) $livro['exemplares'] ?></dd></div>
        <div><dt>Cadastrado em</dt><dd><?= e(data_br($livro['criado_em'])) ?></dd></div>
        <div><dt>Última atualização</dt><dd><?= e(data_br($livro['atualizado_em'])) ?></dd></div>
    </dl>

    <h2>Sinopse</h2>
    <?php if ($livro['sinopse']): ?>
        <p class="sinopse"><?= nl2br(e($livro['sinopse'])) ?></p>
    <?php else: ?>
        <p class="sinopse texto-suave">Este livro ainda não tem sinopse.</p>
    <?php endif; ?>
</section>
