<?php
//data access object?

namespace App\Entity;

use \App\Db\Database;
use \PDO;

class Vaga
{
    /**
     * Identificador unico da vaga
     * @var integer
     */
    public $id;


    /**
     * Titulo da vaga
     * @var integer
     */
    public $titulo;


    /**
     * Descrição da vaga(pode conter html)
     * @var string
     */
    public $descricao;

    /**
     * Define se a vaga está ativa
     * @var integer(s/n)
     */
    public $ativo;

    /**
     * Define a data da vaga
     * @var string
     */
    public $data;

    /**
     * Metodo responsável por cadastrar uma nova vaga
     * @return boolean
     */
    public function cadastrar()
    {
        //definir data
        $this->data = date('Y-m-d H:i:s');

        //inserrir a vaga no banco
        $obDatabase = new Database('vagas');
        $this->id = $obDatabase->insert([
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'ativo' => $this->ativo,
            'data' => $this->data
        ]);

        //atribuir o ID da vaga na instancia

        //retornar sucesso
        return true;
    }


    /**
     * Metodo responsável por atualizar uma vaga no banco
     * @return boolean
     */
    public function atualizar()
    {
        return (new Database('vagas'))->update( 'id= ' .$this->id,[
                                                                    'titulo' => $this->titulo,
                                                                    'descricao' => $this->descricao,
                                                                    'ativo' => $this->ativo,
                                                                    'data' => $this->data]);
       
    }

    /**
     * Metodo responsável por excluir uma vaga no banco
     * @return boolean
     */
    public function excluir(){
        return (new Database('vagas'))->delete('id = '.$this->id);
    }


    /**
     * Metodo responsável por obter as vagas no banco de dados
     * @param string $where
     * @param string $order
     * @param string $limit
     * @return array    
     */
    public static function getVagas($where = null, $order = null, $limit = null)
    {
        return (new Database('vagas'))->select($where, $order, $limit)->fetchAll(PDO::FETCH_CLASS, self::class);
    }


    /**
     * Metodo responsável por buscar uma vaga pelo seu id
     * @param integer $id
     * @return Vaga
     */
    public static function getVaga($id)
    {
        return (new Database('vagas'))->select('id = ' . $id)->fetchObject(self::class);
    }
}
