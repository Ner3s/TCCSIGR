<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {
		// ***** Inicio rotas principais ***** //
		$routes['index'] = array(
			'route' => '/',
			'controller' => 'IndexController',
			'action' => 'index'
		);

		$routes['home'] = array(
			'route' => '/home',
			'controller' => 'AppController',
			'action' => 'home'
		);

		$routes['autenticar'] = array(
			'route' => '/autenticar',
			'controller' => 'AuthController',
			'action' => 'autenticar'
		);

		$routes['editar'] = array(
			'route' => '/editar',
			'controller' => 'AppController',
			'action' => 'editar'
		);

		$routes['sair'] = array(
			'route' => '/sair',
			'controller' => 'AuthController',
			'action' => 'sair'
		);
		##### Fim rotas principais #####

		// ***** Inicio usuario ***** //
		$routes['usuario'] = array(
			'route' => '/usuario',
			'controller' => 'AppController',
			'action' => 'usuario'
		);

		$routes['cadastrar'] = array(
			'route' => '/cadastrar',
			'controller' => 'AppController',
			'action' => 'cadastrar'
		);

		$routes['registrar'] = array(
			'route' => '/registrar',
			'controller' => 'AppController',
			'action' => 'registrar'
		);

		$routes['acaousuario'] = array(
			'route' => '/acaousuario',
			'controller' => 'AppController',
			'action' => 'botoesUsuario'
		);

		$routes['editaruser'] = array(
			'route' => '/editaruser',
			'controller' => 'AppController',
			'action' => 'editarUser'
		);
		##### Fim usuario #####

		// ***** Inicio Produto ***** //
		$routes['produto'] = array(
			'route' => '/produto',
			'controller' => 'AppController',
			'action' => 'produto'
		);

		$routes['cadastrarprod'] = array(
			'route' => '/cadastrarprod',
			'controller' => 'AppController',
			'action' => 'cadastrarProd'
		);

		$routes['registrarprod'] = array(
			'route' => '/registrarprod',
			'controller' => 'AppController',
			'action' => 'registrarProd'
		);

		$routes['acaoproduto'] = array(
			'route' => '/acaoproduto',
			'controller' => 'AppController',
			'action' => 'botoesProduto'
		);

		$routes['editarprod'] = array(
			'route' => '/editarprod',
			'controller' => 'AppController',
			'action' => 'editarProd'
		);
		##### Fim Produtos #####
		
		// ***** Inicio carrinho ***** //
		$routes['carrinho'] = array(
			'route' => '/carrinho',
			'controller' => 'AppController',
			'action' => 'carrinho'
		);

		$routes['finalizar'] = array(
			'route' => '/finalizar',
			'controller' => 'AppController',
			'action' => 'finalizar'
		);
		##### Fim carrinho #####
		
		// ***** Inicio pedido ***** //
		$routes['pedido'] = array(
			'route' => '/pedido',
			'controller' => 'AppController',
			'action' => 'pedido'
		);
		##### Fim pedido #####

		// ***** Inicio Relatorio ***** //
		$routes['relatorio'] = array(
			'route' => '/relatorio',
			'controller' => 'AppController',
			'action' => 'relatorio'
		);
		##### Fim Relatorio #####
		$this->setRoutes($routes);
	}

}

?>