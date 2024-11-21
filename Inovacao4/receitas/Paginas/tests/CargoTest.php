<?php
// cd C:\xampp\htdocs\AcervodeReceitas
// composer require --dev phpunit/phpunit

//php vendor/bin/phpunit --bootstrap vendor/autoload.php Inovacao4/receitas/Paginas/tests/CargoTest.php
define('BD_HOST', 'localhost');
define('BD_USER', 'root');
define('BD_PASSWORD', '#Ladynoir1');
define('BD_DATABASE', 'acervoreceita2');
define('BD_PORT', '3307'); // 3306 é a porta padrão

use PHPUnit\Framework\TestCase;

class CargoTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        $this->conn = mysqli_connect(BD_HOST, BD_USER, BD_PASSWORD, BD_DATABASE, BD_PORT);
    }

    protected function tearDown(): void
    {
        $this->conn->close();
    }

    public function testInsertCargo(): void
    {
        $categoria = 'Teste de Cargo';

        $sql_categoria = "INSERT INTO cargo (nome) VALUES (?)";
        $stmt = $this->conn->prepare($sql_categoria);
        $stmt->bind_param("s", $categoria);

        $this->assertTrue($stmt->execute(), "Falha ao inserir o cargo no banco de dados");

        $result = $this->conn->query("SELECT * FROM cargo WHERE nome = '$categoria'");
        $this->assertEquals(1, $result->num_rows, "O cargo não foi encontrado no banco de dados após inserção");

        $this->conn->query("DELETE FROM cargo WHERE nome = '$categoria'");
    }
}
