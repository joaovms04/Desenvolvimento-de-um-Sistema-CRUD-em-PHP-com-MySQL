<?php
// Configurações gerais do sistema Biblioteca Viva

define('APP_NOME', 'Biblioteca Viva');

// Pasta raiz do projeto (uma pasta acima de /config)
define('RAIZ', dirname(__DIR__));

// URL base calculada automaticamente a partir do arquivo que recebeu a requisição.
// Assim o sistema funciona em qualquer pasta do htdocs, sem caminhos fixos.
$pastaPublica = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
define('BASE_URL', $pastaPublica);

// Opções de status do livro (valor no banco => texto exibido)
const STATUS_LIVRO = [
    'disponivel' => 'Disponível',
    'emprestado' => 'Emprestado',
    'reservado'  => 'Reservado',
];

// Sessão usada apenas para as mensagens de aviso (sucesso/erro)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
