<?php

define('BD_HOST', 'localhost');
define('BD_USER', 'root');
define('BD_PASSWORD', '#Ladynoir1');
define('BD_DATABASE', 'acervoreceita2');
define('BD_PORT', '3307');

use PHPUnit\Framework\TestCase;

class LivroTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        $this->conn = mysqli_connect(BD_HOST, BD_USER, BD_PASSWORD, BD_DATABASE, BD_PORT);
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    protected function tearDown(): void
    {
        $this->conn->close();
    }

    public function testInsertLivro(): void
    {
        // Criando dados para as tabelas dependentes
        $categoria = 'Categoria Teste';
        $restaurante = 'Restaurante Teste';
        $funcionario = 'Funcionario Teste';
        $cargo = 'Cargo Teste';
        $usuario = 'usuario@teste.com';

        // Inserir categorias
        $this->conn->query("INSERT INTO categoria (nome) VALUES ('$categoria')");
        $idCategoria = $this->conn->insert_id;

        // Inserir restaurante
        $this->conn->query("INSERT INTO restaurante (nome) VALUES ('$restaurante')");
        $idRestaurante = $this->conn->insert_id;

        // Inserir cargo
        $this->conn->query("INSERT INTO cargo (nome) VALUES ('$cargo')");
        $idCargo = $this->conn->insert_id;

        // Inserir usuário
        $this->conn->query("INSERT INTO usuario (nome, email, senha) VALUES ('$funcionario', '$usuario', 'senha123')");
        $idUsuario = $this->conn->insert_id;

        // Associando o funcionário a um cargo e restaurante
        $this->conn->query("INSERT INTO funcionario (nome, idCargo, idRestaurante, idLogin) 
                            VALUES ('$funcionario', $idCargo, $idRestaurante, $idUsuario)");
        $idFuncionario = $this->conn->insert_id;

        // Inserir o livro
        $titulo = 'Livro de Teste';
        $isbn = '1234567890';
        $linkImagem = 'link_para_imagem.jpg';
        $arquivoImagem = 'imagem.jpg';

        $sqlLivro = "INSERT INTO livro (titulo, isbn, idEditor, link_imagem, arquivo_imagem) 
                     VALUES ('$titulo', '$isbn', $idFuncionario, '$linkImagem', '$arquivoImagem')";
        $result = $this->conn->query($sqlLivro);

        // Verificar se a inserção foi bem-sucedida
        if (!$result) {
            $this->fail("Erro ao inserir livro: " . $this->conn->error);
        }

        // Verificar se o livro foi inserido corretamente
        $result = $this->conn->query("SELECT * FROM livro WHERE titulo = '$titulo'");
        $this->assertEquals(1, $result->num_rows, "O livro não foi encontrado no banco de dados após inserção");

        // Limpar os dados de teste
        $this->conn->query("DELETE FROM livro WHERE titulo = '$titulo'");
        $this->conn->query("DELETE FROM funcionario WHERE idFun = $idFuncionario");
        $this->conn->query("DELETE FROM categoria WHERE idCategoria = $idCategoria");
        $this->conn->query("DELETE FROM restaurante WHERE idRestaurante = $idRestaurante");
        $this->conn->query("DELETE FROM cargo WHERE idCargo = $idCargo");
        $this->conn->query("DELETE FROM usuario WHERE idLogin = $idUsuario");
    }

    public function testInsertLivroReceita(): void
    {
        // Criar ingrediente
        $this->conn->query("INSERT INTO ingrediente (nome) VALUES ('Ingrediente Teste')");
        $idIngrediente = $this->conn->insert_id;

        // Criar categoria
        $this->conn->query("INSERT INTO categoria (nome) VALUES ('Categoria Teste')");
        $idCategoria = $this->conn->insert_id;

        // Criar funcionário
        $this->conn->query("INSERT INTO funcionario (nome) VALUES ('Funcionario Teste')");
        $idFuncionario = $this->conn->insert_id;

        // Inserir receita
        $this->conn->query("INSERT INTO receita (nome_rec, modo_preparo, descricao, idCozinheiro, idCategoria) 
                            VALUES ('Receita Teste', 'Modo de preparo', 'Descrição', $idFuncionario, $idCategoria)");
        $idReceita = $this->conn->insert_id;

        // Inserir livro
        $titulo = 'Livro de Teste';
        $isbn = '1234567890';
        $linkImagem = 'link_para_imagem.jpg';
        $arquivoImagem = 'imagem.jpg';
        $this->conn->query("INSERT INTO livro (titulo, isbn, idEditor, link_imagem, arquivo_imagem) 
                            VALUES ('$titulo', '$isbn', $idFuncionario, '$linkImagem', '$arquivoImagem')");
        $idLivro = $this->conn->insert_id; // Captura o idLivro gerado

        // Inserir livro_receita com o idLivro correto
        $this->conn->query("INSERT INTO livro_receita (idLivro, idReceita) VALUES ($idLivro, $idReceita)");

        // Verificar se a associação foi feita corretamente
        $result = $this->conn->query("SELECT * FROM livro_receita WHERE idLivro = $idLivro AND idReceita = $idReceita");
        $this->assertEquals(1, $result->num_rows, "A associação livro e receita não foi realizada corretamente");

        // Limpar os dados de teste
        $this->conn->query("DELETE FROM livro_receita WHERE idLivro = $idLivro AND idReceita = $idReceita");
        $this->conn->query("DELETE FROM receita WHERE idReceita = $idReceita");
        $this->conn->query("DELETE FROM ingrediente WHERE idIngrediente = $idIngrediente");
        $this->conn->query("DELETE FROM categoria WHERE idCategoria = $idCategoria");
        $this->conn->query("DELETE FROM funcionario WHERE idFun = $idFuncionario");
        $this->conn->query("DELETE FROM livro WHERE idLivro = $idLivro");
    }
}
