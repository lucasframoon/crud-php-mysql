<?php

namespace App\Db;

use \PDO;
use \PDOException;

class Database
{
    //credenciais de conexão com banco de dados
    const HOST = 'localhost';

    /**
     * Nome do DB
     * @var string
     */

    const NAME = 'lfvagas';


    /**
     * Nome do usuário do DB
     * @var string
     */
    const USER = 'root';


    /**
     * Senha para acesso DB
     * @var string
     */
    const PASS = '';

    /**
     * Nome da tabela a ser manipulada
     * @var string
     */
    private $table;

    /**
     * Instancia de conexão com o banco de dados
     * @var PDO
     */
    private $connection;

    /**
     * Define a tabela e instancia a conexão
     * @var string $table
     */
    public function __construct($table = null)
    {
        $this->table = $table;
        $this->setConnection();
    }

    /**
     * Metodo responsável por criar uma conexção com o banco de dados
     */
    private function setConnection()
    {
        try {
            $this->connection = new PDO('mysql: host=' . self::HOST . ';dbname=' . self::NAME, self::USER, self::PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('ERROR' . $e->getMessage());
        }
    }

    /**
     * Metodo responsável por executar no banco de dados
     * @param string $query
     * @param array $params
     * @return PDOStatement
     */
    public function execute($query, $params = [])
    {
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            return $statement;
        } catch (PDOException $e) {
            die('ERROR' . $e->getMessage());
        }
    }


    /**
     * Metodo responsável por inserir dados no banco de dados
     * @param array $values [field => value]
     * @return integer ID INSERIDO
     */
    public function insert($values)
    {
        //DADOS DA QUERY
        $fields = array_keys($values); //vai trazer as chaves do array em ordem
        $binds = array_pad([], count($fields), '?');

        //MONTA A QUERY
        $query = 'INSERT INTO ' . $this->table . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $binds) . ')';

        //EXECUTA O INSERT
        $this->execute($query, array_values($values));
        // echo "<pre>"; print_r($query); echo "</pre>"; exit;

        return $this->connection->lastInsertId();
    }


    /**
     * Metodo responsável por executar uma consulta no banco de dados
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement    
     */
    public function select($where = null, $order = null, $limit = null, $fields = '*')
    {
        //DADOS DA QUERY
        $where = strlen($where) ? 'WHERE ' . $where : '';
        $order = strlen($order) ? 'WHERE ' . $order : '';
        $limit = strlen($limit) ? 'WHERE ' . $limit : '';

        $query = 'SELECT' . $fields . ' FROM ' . $this->table . ' ' . $where . ' ' . $order . ' ' . $limit;

        //EXECUTA A QUERY
        return $this->execute($query);
    }


    /**
     * Metodo responsável por atualizar dados no banco de dados
     * @param string $where
     * @param array $values [field=> value]
     * @return boolean    
     */
    public function update($where, $values)
    {
        //DADOS DA QUERY
        $fields = array_keys($values);

        //MONTA A QUERY
        $query = 'UPDATE ' . $this->table .
            ' SET ' . implode('=?, ', $fields) .
            '=?
                    WHERE
                    id = 1';

        //EXECUTAR A QUERY
        $this->execute($query, array_values($values));

        return true;
    }


    /**
     * Metodo responsável por excluir dados no banco de dados
     * @param string $where
     * @return boolean    
     */
    public function delete($where)
    {
        //MONTA A QUERY
        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $where;

        //EXECUTA A QUERY
        $this->execute($query);

        return true;
    }
}
