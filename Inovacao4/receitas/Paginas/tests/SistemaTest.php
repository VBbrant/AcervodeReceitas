<?php
// php vendor/bin/phpunit --bootstrap vendor/autoload.php Inovacao4/receitas/Paginas/tests/SistemaTest.php
define('BD_HOST', 'localhost');
define('BD_USER', 'root');
define('BD_PASSWORD', '#Ladynoir1');
define('BD_DATABASE', 'acervoreceita2');
define('BD_PORT', '3307');

use PHPUnit\Framework\TestCase;

class SistemaTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        $this->conn = new mysqli(BD_HOST, BD_USER, BD_PASSWORD, BD_DATABASE, BD_PORT);
        if ($this->conn->connect_error) {
            die("Erro de conexão: " . $this->conn->connect_error);
        }
    }

    protected function tearDown(): void
    {
        $this->conn->close();
    }

    public function testInsertReceitaWithFuncionarioAndUsuario(): void
    {
        // Criar um usuário
        $nomeUsuario = 'Usuario Teste';
        $emailUsuario = 'usuario@teste.com';
        $senhaUsuario = password_hash('senha123', PASSWORD_DEFAULT);

        $sql_usuario = "INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)";
        $stmt_usuario = $this->conn->prepare($sql_usuario);
        $stmt_usuario->bind_param("sss", $nomeUsuario, $emailUsuario, $senhaUsuario);
        $stmt_usuario->execute();
        $idUsuario = $this->conn->insert_id; // Pega o ID do usuário inserido

        $this->assertGreaterThan(0, $idUsuario, "O ID do usuário não foi gerado corretamente");

        // Criar um cargo para o funcionário
        $nomeCargo = 'Cargo Teste';
        $sql_cargo = "INSERT INTO cargo (nome) VALUES (?)";
        $stmt_cargo = $this->conn->prepare($sql_cargo);
        $stmt_cargo->bind_param("s", $nomeCargo);
        $stmt_cargo->execute();
        $idCargo = $this->conn->insert_id; // Pega o ID do cargo inserido

        $this->assertGreaterThan(0, $idCargo, "O ID do cargo não foi gerado corretamente");

        // Criar um funcionário
        $nomeFuncionario = 'Funcionario Teste';
        $rg = '123456789';
        $dataNascimento = '1990-01-01';
        $dataAdmissao = '2022-01-01';
        $salario = 3000.00;
        $telefone = '987654321';

        $sql_funcionario = "INSERT INTO funcionario (nome, rg, data_nascimento, data_admissao, salario, telefone, idCargo) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_funcionario = $this->conn->prepare($sql_funcionario);
        $stmt_funcionario->bind_param("ssssdsi", $nomeFuncionario, $rg, $dataNascimento, $dataAdmissao, $salario, $telefone, $idCargo);
        $stmt_funcionario->execute();
        $idFuncionario = $this->conn->insert_id; // Pega o ID do funcionário inserido

        $this->assertGreaterThan(0, $idFuncionario, "O ID do funcionário não foi gerado corretamente");

        // Atribuindo o idUsuario ao funcionário (atualizando o funcionário com idLogin)
        $sql_update_funcionario = "UPDATE funcionario SET idLogin = ? WHERE idFun = ?";
        $stmt_update_funcionario = $this->conn->prepare($sql_update_funcionario);
        $stmt_update_funcionario->bind_param("ii", $idUsuario, $idFuncionario);
        $stmt_update_funcionario->execute();

        // Criar a receita, associando o idCozinheiro (funcionário) à receita
        $nomeReceita = 'Receita Teste';
        $modoPreparo = 'Modo de preparo teste';
        $descricao = 'Descrição da receita de teste';
        $numPorcao = 4;
        $inedita = 'S'; // 'S' para receita inédita
        $linkImagem = 'link_da_imagem_teste.jpg';
        $arquivoImagem = 'arquivo_imagem_teste.jpg';
        $idCategoria = 1; // Supondo que já exista uma categoria com id = 1

        $sql_receita = "INSERT INTO receita (nome_rec, modo_preparo, descricao, num_porcao, inedita, link_imagem, arquivo_imagem, idCozinheiro, idCategoria) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_receita = $this->conn->prepare($sql_receita);
        $stmt_receita->bind_param("ssssssiii", $nomeReceita, $modoPreparo, $descricao, $numPorcao, $inedita, $linkImagem, $arquivoImagem, $idFuncionario, $idCategoria);
        $stmt_receita->execute();
        $idReceita = $this->conn->insert_id; // Pega o ID da receita inserida

        $this->assertGreaterThan(0, $idReceita, "O ID da receita não foi gerado corretamente");

        // Limpeza dos dados inseridos
        $this->conn->query("DELETE FROM receita WHERE idReceita = '$idReceita'");
        $this->conn->query("DELETE FROM funcionario WHERE idFun = '$idFuncionario'");
        $this->conn->query("DELETE FROM cargo WHERE idCargo = '$idCargo'");
        $this->conn->query("DELETE FROM usuario WHERE idLogin = '$idUsuario'");
    }
}
