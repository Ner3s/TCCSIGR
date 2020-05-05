<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action {


	public function autenticar() {
		
		$usuario = Container::getModel('Usuario');

		$usuario->__set('login', $_POST['login']);
		$usuario->__set('senha', md5($_POST['senha']));

		$usuario->autenticar();

		if($usuario->__get('id') != '' && $usuario->__get('nome')) {
			
			session_start();

			$_SESSION['id'] = $usuario->__get('id');
			$_SESSION['nome'] = $usuario->__get('nome');
			$_SESSION['login'] = $usuario->__get('login');
			$_SESSION['tipo_usuario'] = $usuario->__get('tipo_usuario');

			header('Location: /home');

		} else {
			echo 'Erro ao autenticar';
			header('Location: /?login=erro');
		}

	}

	public function sair() {
		session_start();
		session_destroy();
		header('Location: /');
	}
}