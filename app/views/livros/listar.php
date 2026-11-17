<section class="cabecalho-pagina">
    <div>
        <h1>Livros</h1>
        <p class="subtitulo">Pesquise, filtre e gerencie o acervo.</p>
    </div>
    <a class="btn btn-primario" href="<?= e(url('livros', 'novo')) ?>">+ Novo livro</a>
</section>

<form class="cartao filtros" method="get" action="<?= e(BASE_URL) ?>/index.php">
    <input type="hidden" name="pagina" value="livros">
    <input type="hidden" name="acao" value="listar">

    <div class="campo">
        <label for="busca">Buscar</label>
        <input type="search" id="busca" name="busca" placeholder="Título ou autor" value="<?= e($filtros['busca']) ?>">
    </div>

    <div class="campo">
        <label for="genero">Gênero</label>
        <select id="genero" name="genero">
            <option value="">Todos</option>
            <?php foreach ($generos as $g): ?>
                <option value="<?= (int) $g['id'] ?>" <?= $filtros['genero'] === (int) $g['id'] ? 'selected' : '' ?>><?= e($g['nome']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="campo">
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="">Todos</option>
            <?php foreach (STATUS_LIVRO as $valor => $rotulo): ?>
                <option value="<?= e($valor) ?>" <?= $filtros['status'] === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="filtros-botoes">
        <button type="submit" class="btn btn-primario">Filtrar</button>
        <a class="btn btn-claro" href="<?= e(url('livros')) ?>">Limpar</a>
    </div>
</form>

<p class="contagem"><?= count($livros) ?> livro(s) encontrado(s)</p>

<?php if (!$livros): ?>
    <div class="cartao"><p class="vazio">Nenhum livro encontrado com esses critérios.</p></div>
<?php else: ?>
    <div class="tabela-wrap">
        <table>
            <thead>
                <tr>
                    <th>Livro</th>
                    <th>Gênero</th>
                    <th>Ano</th>
                    <th>Exemplares</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($livros as $l): ?>
                    <tr>
                        <td>
                            <a class="titulo-livro" href="<?= e(url('livros', 'ver', ['id' => $l['id']])) ?>"><?= e($l['titulo']) ?></a>
                            <span class="autor"><?= e($l['autor']) ?></span>
                        </td>
                        <td><span class="chip"><i style="background: <?= e($l['genero_cor']) ?>"></i><?= e($l['genero_nome']) ?></span></td>
                        <td><?= $l['ano_publicacao'] ? (int) $l['ano_publicacao'] : '—' ?></td>
                        <td><?= (int) $l['exemplares'] ?></td>
                        <td><span class="selo selo-<?= e($l['status']) ?>"><?= e(STATUS_LIVRO[$l['status']] ?? $l['status']) ?></span></td>
                        <td>
                            <div class="acoes-linha">
                                <a class="btn btn-claro btn-pequeno" href="<?= e(url('livros', 'editar', ['id' => $l['id']])) ?>">Editar</a>
                                <form class="form-inline" method="post"
                                      action="<?= e(url('livros', 'excluir', ['id' => $l['id']])) ?>"
                                      data-confirma="<?= e('Excluir o livro "' . $l['titulo'] . '"? Esta ação não pode ser desfeita.') ?>">
                                    <button type="submit" class="btn btn-perigo btn-pequeno">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
