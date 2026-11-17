<section class="cabecalho-pagina">
    <div>
        <h1>Gêneros</h1>
        <p class="subtitulo">Organize o acervo por categorias de leitura.</p>
    </div>
    <a class="btn btn-primario" href="<?= e(url('generos', 'novo')) ?>">+ Novo gênero</a>
</section>

<?php if (!$generos): ?>
    <div class="cartao"><p class="vazio">Nenhum gênero cadastrado ainda.</p></div>
<?php else: ?>
    <div class="tabela-wrap">
        <table>
            <thead>
                <tr>
                    <th>Gênero</th>
                    <th>Descrição</th>
                    <th>Livros</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($generos as $g): ?>
                    <?php $total = (int) $g['total_livros']; ?>
                    <tr>
                        <td><span class="chip"><i style="background: <?= e($g['cor']) ?>"></i><strong><?= e($g['nome']) ?></strong></span></td>
                        <td><?= $g['descricao'] ? e($g['descricao']) : '—' ?></td>
                        <td>
                            <?php if ($total > 0): ?>
                                <a href="<?= e(url('livros', 'listar', ['genero' => $g['id']])) ?>"><?= $total ?> livro(s)</a>
                            <?php else: ?>
                                <span class="texto-suave">nenhum</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="acoes-linha">
                                <a class="btn btn-claro btn-pequeno" href="<?= e(url('generos', 'editar', ['id' => $g['id']])) ?>">Editar</a>
                                <form class="form-inline" method="post"
                                      action="<?= e(url('generos', 'excluir', ['id' => $g['id']])) ?>"
                                      data-confirma="<?= e('Excluir o gênero "' . $g['nome'] . '"?') ?>">
                                    <button type="submit" class="btn btn-perigo btn-pequeno"
                                        <?= $total > 0 ? 'disabled title="Este gênero ainda tem livros"' : '' ?>>Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
