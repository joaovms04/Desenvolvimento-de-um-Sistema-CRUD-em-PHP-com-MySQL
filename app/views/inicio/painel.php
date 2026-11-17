<section class="cabecalho-pagina">
    <div>
        <h1>Painel da biblioteca</h1>
        <p class="subtitulo">Um resumo do acervo e dos últimos livros cadastrados.</p>
    </div>
    <a class="btn btn-primario" href="<?= e(url('livros', 'novo')) ?>">+ Novo livro</a>
</section>

<section class="estatisticas" aria-label="Resumo do acervo">
    <article class="estatistica">
        <span class="numero"><?= $resumo['titulos'] ?></span>
        <span class="rotulo">Títulos no acervo</span>
    </article>
    <article class="estatistica">
        <span class="numero"><?= $resumo['exemplares'] ?></span>
        <span class="rotulo">Exemplares ao todo</span>
    </article>
    <article class="estatistica verde">
        <span class="numero"><?= $resumo['disponiveis'] ?></span>
        <span class="rotulo">Disponíveis na estante</span>
    </article>
    <article class="estatistica ambar">
        <span class="numero"><?= $resumo['emprestados'] + $resumo['reservados'] ?></span>
        <span class="rotulo">Emprestados ou reservados</span>
    </article>
</section>

<div class="duas-colunas">
    <section class="cartao">
        <h2>Acervo por gênero</h2>

        <?php if (!$generos): ?>
            <p class="vazio">Nenhum gênero cadastrado ainda.</p>
        <?php else: ?>
            <ul class="barras">
                <?php foreach ($generos as $g): ?>
                    <?php $porcento = $maior > 0 ? round(((int) $g['total_livros']) / $maior * 100) : 0; ?>
                    <li>
                        <div class="barra-linha">
                            <span><?= e($g['nome']) ?></span>
                            <strong><?= (int) $g['total_livros'] ?></strong>
                        </div>
                        <div class="barra"><span style="width: <?= $porcento ?>%; background: <?= e($g['cor']) ?>"></span></div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

    <section class="cartao">
        <h2>Últimos cadastrados</h2>

        <?php if (!$recentes): ?>
            <p class="vazio">Nenhum livro cadastrado ainda.</p>
        <?php else: ?>
            <ul class="lista-simples">
                <?php foreach ($recentes as $livro): ?>
                    <li>
                        <div>
                            <a class="titulo-livro" href="<?= e(url('livros', 'ver', ['id' => $livro['id']])) ?>"><?= e($livro['titulo']) ?></a>
                            <span class="autor"><?= e($livro['autor']) ?></span>
                        </div>
                        <span class="selo selo-<?= e($livro['status']) ?>"><?= e(STATUS_LIVRO[$livro['status']] ?? $livro['status']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
</div>
