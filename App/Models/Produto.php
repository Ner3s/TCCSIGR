<?php

namespace App\Models;

use MF\Model\Model;

class Produto extends Model {

    private $id;
    private $nome;
    private $descricao;
    private $imagem;
    private $preco;
	private $promocao;
	private $tipo;

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }

    public function __get($atributo){
        return $this->$atributo;
	}
	
	//Valida cadastro
	public function validarCadastro(){
		$valido = true;
		
		if(strlen($this->__get('nome'))<3){
			$valido = false;
		}
		if(strlen($this->__get('descricao'))<3){
			$valido = false;
		}

		return $valido;
	}


	//recuperar um produto pelo nome
	public function getProdutoPorNome() {
		$query = "select nome from produto where nome = :nome";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':nome', $this->__get('nome'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getProdutoPorId($id) {
		$query = "select idproduto, nome, preco from produto where idproduto = :idproduto";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':idproduto', $id);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

   	//salvar
	public function salvar() {

		$query = "insert into produto (nome, descricao, imagem, preco, promocao, tipo) values (:nome, :descricao, :imagem, :preco, :promocao, :tipo)";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':nome', $this->__get('nome'));
		$stmt->bindValue(':descricao', $this->__get('descricao'));
		$stmt->bindValue(':imagem', $this->__get('imagem'));
		$stmt->bindValue(':preco', $this->__get('preco'));
		$stmt->bindValue(':promocao', $this->__get('promocao'));
		$stmt->bindValue(':tipo', $this->__get('tipo'));
		
		$stmt->execute();

		return $this;
	}

    //recupera
	public function getAll(){
		$query = "select idproduto, nome, descricao, imagem, preco, promocao, tipo from produto";

		$stmt = $this->db->prepare($query);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getAllImagens(){
		$query = "select id, nome from imagem";

		$stmt = $this->db->prepare($query);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	//recupera filtro
	public function getFiltro($filtro){
		$query = "select idproduto, nome, descricao, imagem, preco, promocao, tipo from produto where tipo = :tipo";

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':tipo',$filtro);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function editar(){
		// altera o produto pelo id
		$query = "update produto set nome = :nome, descricao = :descricao, imagem = :imagem, preco = :preco, promocao = :promocao, tipo = :tipo where idproduto = :idproduto";

		$stmt = $this->db->prepare($query);
		
		$stmt->bindValue(':idproduto', $this->__get('id'));
		$stmt->bindValue(':nome', $this->__get('nome'));
		$stmt->bindValue(':descricao', $this->__get('descricao'));
		$stmt->bindValue(':imagem', $this->__get('imagem'));
		$stmt->bindValue(':preco', $this->__get('preco'));
		$stmt->bindValue(':promocao', $this->__get('promocao'));
		$stmt->bindValue(':tipo', $this->__get('tipo'));

		$stmt->execute();

	}

	public function excluir($id){
		// deleta o produto pelo id
		$query = "delete from produto where idproduto = :idproduto";

		$stmt = $this->db->prepare($query);
		$stmt->bindValue('idproduto',$id);
		$stmt->execute();
	}

}
?>