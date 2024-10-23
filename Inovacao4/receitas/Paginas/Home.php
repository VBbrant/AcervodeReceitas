<?php include '../elementoPagina/cabecalho.php'; ?>

<div class="page-content">
    <div class="background1"></div>

    <div class="container mt-4">
        <h2 class="section-title">Receitas em Destaque</h2>
        <div class="row featured-recipes">
            <?php
            include '../conn.php';

            // Consulta para buscar receitas
            $sqlReceitas = "SELECT idReceita AS id, nome_rec AS nome, descricao, link_imagem AS imagem FROM receita LIMIT 10";
            $resultReceitas = mysqli_query($conn, $sqlReceitas);

            if (mysqli_num_rows($resultReceitas) > 0) {
                $index = 0;
                while ($receita = mysqli_fetch_assoc($resultReceitas)) {
                    $hiddenClass = ($index >= 4) ? 'hidden extra-receitas' : '';
                    echo "
                    <div class='recipe-box {$hiddenClass}'>
                        <a href='receitas/verReceita.php?id={$receita['id']}'>
                            <div class='recipe-image' style='background-image: url(../imagens/{$receita['imagem']});'>
                                <div class='recipe-details'>
                                    <h5>{$receita['nome']}</h5>
                                    <p>{$receita['descricao']}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                ";
                    $index++;
                }
            } else {
                echo "<p>Nenhuma receita encontrada.</p>";
            }
            ?>
        </div>
        <div class="text-center">
            <button class="btn bg-dark text-white" id="more-recipes-btn">Mais Receitas</button>
        </div>
    </div>

    <div class="container mt-4">
        <h2 class="section-title">Livros em Destaque</h2>
        <div class="row featured-books">
            <?php
            // Consulta para buscar livros
            $sqlLivros = "SELECT idLivro AS id, titulo, 'Autor Desconhecido' AS autor FROM livro LIMIT 9";  
            $resultLivros = mysqli_query($conn, $sqlLivros);

            if (mysqli_num_rows($resultLivros) > 0) {
                $index = 0;
                while ($livro = mysqli_fetch_assoc($resultLivros)) {
                    $hiddenClass = ($index >= 3) ? 'hidden extra-livros' : '';
                    echo "
                    <div class='col-md-4 book-box text-center {$hiddenClass}'>
                        <div class='p-3'>
                            <h5>{$livro['titulo']}</h5>
                            <p>por {$livro['autor']}</p>
                        </div>
                    </div>";
                    $index++;
                }
            } else {
                echo "<p>Nenhum livro encontrado.</p>";
            }
            ?>
        </div>
        <div class="text-center">
            <button class="btn btn-primary" id="more-books-btn">Mais Livros</button>
        </div>
    </div>

    <footer class="mt-4 text-center">
        <p>&copy; 2024 SaborArte. Todos os direitos reservados.</p>
    </footer>
</div> <!-- Fim do .page-content -->

<?php include "../elementoPagina/rodape.php"; ?>
