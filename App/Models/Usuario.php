<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model {
	// diagrama de classes -- Usuario
	//remover endereço
	//remover herança
	//adicionar tipo de usuario

	private $id;
	private $nome;
	private $login;
	private $senha;
	private $tipo_usuario;
	
	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}

	//salvar
	public function salvar() {

		$query = "insert into usuario(nome, login, senha, tipo_usuario)values(:nome, :login, :senha, :tipo_usuario)";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':nome', $this->__get('nome'));
		$stmt->bindValue(':login', $this->__get('login'));
		$stmt->bindValue(':senha', $this->__get('senha')); //md5() -> hash 32 caracteres
		$stmt->bindValue(':tipo_usuario', $this->__get('tipo_usuario'));
		$stmt->execute();

		return $this;
	}

	//validar se um cadastro pode ser feito
	public function validarCadastro(){
		$valido = true;
		if(strlen($this->__get('nome'))<3){
			$valido = false;
		}
		if(strlen($this->__get('login'))<3){
			$valido = false;
		}
		if(strlen($this->__get('senha'))<3){
			$valido = false;
		}

		return $valido;
	}


	//recuperar um usuário por login
	public function getUsuarioPorlogin() {
		$query = "select nome, login from usuario where login = :login";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':login', $this->__get('login'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function autenticar() {

		$query = "select idusuario, nome, login, tipo_usuario from usuario where login = :login and senha = :senha";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':login', $this->__get('login'));
		$stmt->bindValue(':senha', $this->__get('senha'));
		$stmt->execute();

		$usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

		if($usuario['idusuario'] != '' && $usuario['nome'] != '') {
			$this->__set('id', $usuario['idusuario']);
			$this->__set('nome', $usuario['nome']);
			$this->__set('tipo_usuario', $usuario['tipo_usuario']);
		}

		return $this;
	}

	//recuperar
	public function getAll(){
		$query = "select idusuario, nome, login, tipo_usuario from usuario";

		$stmt = $this->db->prepare($query);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	//recuperar filtro
	public function getFiltro($filtro){
		$query = "select idusuario, nome, login, tipo_usuario from usuario where tipo_usuario = :tipo_usuario";

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':tipo_usuario',$filtro);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function editar(){
		// altera o usuario pelo id
		$query = "update usuario set nome = :nome, login = :login, tipo_usuario = :tipo_usuario, senha = :senha where idusuario = :idusuario";

		$stmt = $this->db->prepare($query);
		
		$stmt->bindValue(':idusuario', $this->__get('id'));
		$stmt->bindValue(':nome', $this->__get('nome'));
		$stmt->bindValue(':login', $this->__get('login'));
		$stmt->bindValue(':senha', $this->__get('senha')); //md5() -> hash 32 caracteres
		$stmt->bindValue(':tipo_usuario', $this->__get('tipo_usuario'));

		$stmt->execute();

	}

	public function excluir($id){
		// deleta o usuario pelo id
		$query = "delete from usuario where idusuario = :idusuario";

		$stmt = $this->db->prepare($query);
		$stmt->bindValue('idusuario',$id);
		$stmt->execute();
	}
}

?>