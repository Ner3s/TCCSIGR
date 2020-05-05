<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{

	public function validaAutenticacao()
	{

		session_start();

		if (!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == '') {
			header('Location: /?login=erro');
		}
	}

	public function home()
	{
		$this->validaAutenticacao();
		$this->render('home', 'dashboard');
	}

	//abre a tela de usuario
	public function usuario()
	{
		$this->validaAutenticacao();
		if ($_SESSION['tipo_usuario'] == 1) {
			
			$usuario = Container::getModel('Usuario');

			if(!isset($_GET['filtro'])){
				$usuarios = $usuario->getAll();
				$this->view->usuario = $usuarios;
			}
			if(isset($_GET['filtro'])){
				$usuarios = $usuario->getFiltro($_GET['filtro']);
				$this->view->usuario = $usuarios;
			}

			$this->render('usuario', 'dashboard');
		} else {
			$this->render('home', 'dashboard');
			header('Location:home?erro=1');
		}
	}

	// abre a reder cadastrar
	public function cadastrar()
	{
		$this->validaAutenticacao();
		if ($_SESSION['tipo_usuario'] == 1) {

			$this->view->usuario = array(
				'nome' => '',
				'login' => '',
				'senha' => '',
			);

			$this->view->erroCadastro = false;

			$this->render('cadastrar', 'dashboard');
		} else {
			$this->render('home', 'dashboard');
			header('Location:home?erro=1');
		}
	}

	// registra usuario
	public function registrar()
	{
		$this->validaAutenticacao();
		if ($_SESSION['tipo_usuario'] == 1) {
			//receber os dados do formulário
			$usuario = Container::getModel('Usuario');

			$usuario->__set('nome', $_POST['nome']);
			$usuario->__set('login', $_POST['login']);
			$usuario->__set('tipo_usuario', $_POST['tipo_usuario']);
			$usuario->__set('senha', md5($_POST['senha'])); // senha md5 em hash		

			//sucesso
			if ($usuario->validarCadastro() && count($usuario->getUsuarioPorLogin()) == 0) {
				$usuario->salvar();

				header('Location: home?acao=cadastraruser');
			} else {
				$this->view->usuario = array(
					'nome' => $_POST['nome'],
					'login' => $_POST['login'],
					'senha' => $_POST['senha'],
					'tipo_usuario' => $_POST['tipo_usuario'],
				);
				$this->view->erroCadastro = true;
				$this->render('cadastrar', 'dashboard');
			}
		} else {
			$this->render('home', 'dashboard');
			header('Location:home?erro=1');
		}
	}

	//verificando testes
	public function botoesUsuario()
	{
		$this->validaAutenticacao();
		$usuario = Container::getModel('Usuario');

		if ($_SESSION['tipo_usuario'] == 1) {

			switch ($_GET) {

				case isset($_GET['editar']): {
						$url = '?usuario=' . $_GET['editar']; // pega a url e passa com GET o valor do id do usuario para ser editado.

						$usuarios = $usuario->getAll();
						//envia para a view os dados
						$this->view->usuario = $usuarios;
						//renderiza a view usuario

						header('Location: editar' . $url);
						break;
					}

				case isset($_GET['excluir']): {
						if ($_GET['excluir'] != 1) {
							$usuario->excluir($_GET['excluir']);

							header('Location: home?acao=excluiruser');
						} else {
							header('Location: home?erro=1');
						}
						break;
					}
			}
		} else {
			//Exibe erro por falta de permissão
			header('Location:home?erro=1');
		}
	}

	//verificar se editar ta setado corretamente!

	public function editar()
	{
		$imagem = Container::getModel('Produto');
		$imagens = $imagem->getAllImagens();
		$this->view->imagem = $imagens;

		$this->validaAutenticacao();
		$this->render('editar', 'dashboard');
	}

	public function editarUser()
	{
		$this->validaAutenticacao();
		if ($_SESSION['tipo_usuario'] == 1) {
			//receber os dados do formulário
			$usuario = Container::getModel('Usuario');

			$usuario->__set('id', $_POST['id']);
			$usuario->__set('nome', $_POST['nome']);
			$usuario->__set('login', $_POST['login']);
			$usuario->__set('tipo_usuario', $_POST['tipo_usuario']);
			$usuario->__set('senha', md5($_POST['senha'])); // senha md5 em hash		

			//sucesso
			if ($usuario->validarCadastro() && count($usuario->getUsuarioPorLogin()) == 0) {
				$usuario->editar();

				header('Location: home?acao=editaruser');
			} else {
				$this->view->usuario = array(
					'nome' => $_POST['nome'],
					'login' => $_POST['login'],
					'senha' => $_POST['senha'],
					'tipo_usuario' => $_POST['tipo_usuario'],
				);
				$this->view->erroEditar = true; // caso precise de tratamento futuro
				$this->render('editar', 'dashboard');
			}
		}
	}

	/* ############# INICIA PRODUTO ############# */

	public function produto()
	{
		$this->validaAutenticacao();
		if ($_SESSION['tipo_usuario'] == 1 || $_SESSION['tipo_usuario'] == 3) {

			// REMOVER LINHAS DE COMENTARIOS PARA MOSTRAR CONTEUDO DA VARIAVEL PRODUTOS // 
			##echo '<pre>';
			##print_r ($produtos);
			##echo '</pre>';

			//busca o modelo do produto
			$produto = Container::getModel('Produto');

			if (!isset($_GET['filtro'])){
				$produtos = $produto->getAll(); //recupera todos os usuarios do banco
				$this->view->produto = $produtos; //envia para a view os dados
			}

			if (isset($_GET['filtro'])){
				$produtos = $produto->getFiltro($_GET['filtro']);
				$this->view->produto = $produtos;
			}

			$this->render('produto','dashboard');
		} else {
			header('Location: home?erro=1');
		}
	}

	public function botoesProduto()
	{
		$this->validaAutenticacao();
		$produto = Container::getModel('Produto');

		if ($_SESSION['tipo_usuario'] == 1) {

			switch ($_GET) {

				case isset($_GET['editar']): {
						$url = '?produto=' . $_GET['editar']; // pega a url e passa com GET o valor do id do usuario para ser editado.

						$produtos = $produto->getAll();
						//envia para a view os dados
						$this->view->produto = $produtos;
						//renderiza a view usuario

						header('Location: editar' . $url);
						break;
					}

				case isset($_GET['excluir']): {
						$produto->excluir($_GET['excluir']);
						header('Location: home?acao=excluirprod');
						break;
					}

				default: {
						header('Location: home?erro=1');
						break;
					}
			}
		} else {
			//Exibe erro por falta de permissão
			header('Location:home?erro=1');
		}
	}

	public function cadastrarProd()
	{
		$this->validaAutenticacao();
		if ($_SESSION['tipo_usuario'] == 1) {
			
			$imagem = Container::getModel('Produto');
			$imagens = $imagem->getAllImagens();
			$this->view->imagem = $imagens;

			$this->view->erroCadastro = false;

			$this->render('cadastrarprod', 'dashboard');
		} else {
			header('Location:home?erro=1');
		}
	}

	// registra usuario
	public function registrarProd()
	{
		$this->validaAutenticacao();
		if ($_SESSION['tipo_usuario'] == 1) {
			//receber os dados do formulário
			$produto = Container::getModel('Produto');

			$produto->__set('nome', $_POST['nome']);
			$produto->__set('descricao', $_POST['descricao']);
			$produto->__set('imagem', $_POST['imagem']);
			$produto->__set('preco', $_POST['preco']);
			$produto->__set('promocao', $_POST['promocao']);
			$produto->__set('tipo', $_POST['tipo']);

			//sucesso
			if ($produto->validarCadastro() && count($produto->getProdutoPorNome()) == 0) {
				$produto->salvar();

				header('Location: home?acao=cadastrarprod');
			} else {
				$this->view->produto = array(
					'nome' => $_POST['nome'],
					'descricao' => $_POST['descricao'],
					'imagem' => $_POST['imagem'],
					'preco' => $_POST['preco'],
					'promocao' => $_POST['promocao'],
					'tipo' => $_POST['tipo'],
				);
				$this->view->erroCadastro = true;
				$this->render('cadastrarprod', 'dashboard');
			}
		} else {
			$this->render('home', 'dashboard');
			header('Location:home?erro=1');
		}
	}

	public function editarProd()
	{
		$this->validaAutenticacao();
		if ($_SESSION['tipo_usuario'] == 1) {
			//receber os dados do formulário
			$produto = Container::getModel('Produto');

			$produto->__set('id', $_POST['id']);
			$produto->__set('nome', $_POST['nome']);
			$produto->__set('descricao', $_POST['descricao']);
			$produto->__set('imagem', $_POST['imagem']);
			$produto->__set('preco', $_POST['preco']);
			$produto->__set('promocao', $_POST['promocao']);
			$produto->__set('tipo', $_POST['tipo']);

			//sucesso
			if ($produto->validarCadastro()) {
				$produto->editar();

				header('Location: home?acao=editarprod');
			} else {
				$this->view->produto = array(
					'id' => $_POST['id'],
					'nome' => $_POST['nome'],
					'descricao' => $_POST['descricao'],
					'imagem' => $_POST['imagem'],
					'preco' => $_POST['preco'],
					'promocao' => $_POST['promocao'],
					'tipo' => $_POST['tipo'],
				);
				$this->view->erroEditar = true; // caso precise de tratamento futuro
				$this->render('editar', 'dashboard');
			}
		}
	}

	public function carrinho(){
		$this->validaAutenticacao();
		if($_SESSION['tipo_usuario'] == 3){
			
			$carrinho = Container::getModel('Carrinho');
			$produto = Container::getModel('Produto');
			$pedido = Container::getModel('Pedido');

			$carrinho->__set('adiciona',true);	//será a variavel condicional para saber se um produto ja foi adicionado.

			//se a sessão carrinho não estiver setada.
			if(!isset($_SESSION['carrinho'])){
				$_SESSION['carrinho'] = array();//cria a sessão
				$_SESSION['totalcarrinho'] = 0;
			}

			if(isset($_GET['acao']) && $_GET['acao'] == 'add'){

				$carrinho->__set('idproduto', $_POST['idproduto']);
				$carrinho->__set('quantidade', $_POST['quantidade']);

				$produtos = $produto->getProdutoPorId($carrinho->__get('idproduto'));	//recupera produto pelo id
				
				$carrinho->adicionarCarrinho($produtos);

			}
			
			if(isset($_GET['acao']) && $_GET['acao'] == 'remover'){
				$carrinho->excluiCarrinho();
			}

			if(isset($_GET['acao']) && $_GET['acao'] == 'removerall'){
				$carrinho->limpaCarrinho();
				header('Location: produto');
			}

			if(isset($_GET['acao']) && $_GET['acao'] == 'quantidade'){
				$carrinho->editarQuantidade();
			}

			if(isset($_GET['acao']) && $_GET['acao'] == 'observacao'){
				$_SESSION['observacao'] = $_POST['observacao'];
			}

			if(isset($_GET['acao']) && $_GET['acao'] == 'finalizar'){
				
				$pedido->__set('idUser',$_SESSION['id']);
				$pedido->__set('precoTotal',$_SESSION['totalcarrinho']);
				isset($_SESSION['observacao']) ? $pedido->__set('observacao',$_SESSION['observacao']) : '' ; 
				$pedido->inserePedido();

				$carrinho->finalizar($_SESSION['carrinho']);
				
				header('Location: finalizar');
			}
					//variavel array	 indice // valor
			//print_r($_SESSION['carrinho'][0]['quantidade']);

			$this->render('carrinho','dashboard');
		}else{
			header('Location: home?erro=1');
		}
	}

	public function finalizar(){
		$this->validaAutenticacao();
		if($_SESSION['tipo_usuario'] == 3){
			if(isset($_SESSION['carrinho']) || isset($_SESSION['totalcarrinho'])){
				header('Location: home?erro=2');
			}else{
				$this->render('finalizar','dashboard');
			}
		}
		else{
			header('Location: home?erro=1');	
		}
	}

	public function pedido(){
		$this->validaAutenticacao();
		if($_SESSION['tipo_usuario'] == 2 || $_SESSION['tipo_usuario'] == 1){
			$pedido = Container::getModel('Pedido');
			$pedidos = $pedido->mostrarPedido();
			$this->view->pedido = $pedidos;

			$contador = $pedido->contPed();
			$this->view->contador = $contador['contador'];

			while($contador['contador'] != 0){
				$itens = $pedido->mostrarItemPedido($contador['contador']);
			
				$this->view->carrinho[$contador['contador']] = $itens;

				$contador['contador'] --;
			}
			
			$this->render('pedido','dashboard');
		}else{
			header('Location: home?erro=1');
		}
	}

	public function relatorio(){
		$this->validaAutenticacao();
		if($_SESSION['tipo_usuario'] == 1){
			$relatorio = Container::getModel('Pedido');
			// GERAL
			$pedidoMCaro = $relatorio->pedidoMCaro();
			$this->view->pedidoMCaro = $pedidoMCaro;

			$prodMPedido = $relatorio->prodMPedido();
			$this->view->prodMPedido = $prodMPedido;

			$top10Prod = $relatorio->top10Prod();
			$this->view->top10Prod = $top10Prod;
			// ***
			// DIA
			$resumDia = $relatorio->resumDia();
			$this->view->resumDia = $resumDia;

			$totDia = $relatorio->totDia();
			$this->view->totDia = $totDia;
			// ***
			// MES
			$resumMes = $relatorio->resumMes();
			$this->view->resumMes = $resumMes;

			$totMes = $relatorio->totMes();
			$this->view->totMes = $totMes;
			// ***
			// ANUAL
			$resumAnual = $relatorio->resumAnual();
			$this->view->resumAnual = $resumAnual;

			$totAnual = $relatorio->totAnual();
			$this->view->totAnual = $totAnual;
			// ***

			$this->render('relatorio','layout2');
		}else{
			header('Location: home?erro=1');
		}
	}
}