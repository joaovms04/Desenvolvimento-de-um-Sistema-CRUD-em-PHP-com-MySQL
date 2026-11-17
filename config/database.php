<?php
// Conexão com o banco de dados MySQL usando PDO

class Database
{
    private string $host    = 'localhost';
    private string $banco   = 'biblioteca_db';
    private string $usuario = 'root';
    private string $senha   = '';   // no XAMPP a senha padrão do root é vazia

    public function conectar(): PDO
    {
        try {
            return new PDO(
                "mysql:host={$this->host};dbname={$this->banco};charset=utf8mb4",
                $this->usuario,
                $this->senha,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // erros viram exceções
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // resultados como array associativo
                    PDO::ATTR_EMULATE_PREPARES   => false,                   // prepared statements de verdade
                ]
            );
        } catch (PDOException $e) {
            // O detalhe técnico vai para o log do servidor, não para a tela do usuário
            error_log('Erro de conexão: ' . $e->getMessage());
            http_response_code(500);
            exit('Não foi possível conectar ao banco de dados. '
               . 'Confira se o MySQL está ligado e se o arquivo banco/biblioteca.sql foi importado.');
        }
    }
}
