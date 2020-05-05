<?php

namespace App\Models;

use MF\Model\Model;

class Carrinho extends Model
{
    private $carrinho;
    private $idproduto;
    private $quantidade;
    private $adiciona;

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function contarLinhaPedido(){
        
    }

    public function finalizar($carrinho)
    {
        $this->carrinho = $carrinho;

        unset($_SESSION['carrinho']);
        unset($_SESSION['totalcarrinho']);
        unset($_SESSION['observacao']);

        $queryPedido = "select count(*) as idpedido from pedido";
        $stmtPedido = $this->db->prepare($queryPedido);
        $stmtPedido->execute();
		$contador = $stmtPedido->fetch(); //para pegar o valor do pedido ---= $contador['idpedido'] =---
        
        foreach($this->carrinho as $indice => $item){
            $query = "
            insert into pedido_has_produto
                (pedido_idpedido, produto_idproduto, quantidade, preco, subtotal)
            values
                (:pedido_idpedido, :produto_idproduto, :quantidade, :preco, :subtotal)";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':pedido_idpedido', $contador['idpedido']);
            $stmt->bindValue(':produto_idproduto', $item['idproduto']);
            $stmt->bindValue(':quantidade', $item['quantidade']);
            $stmt->bindValue(':preco', $item['preco']);
            $stmt->bindValue(':subtotal', $item['subtotal']);
		
	    	$stmt->execute();
                       
        }

        return $this->carrinho;
    }

    //public function editar($carrinho)
    public function adicionarCarrinho($produtos)
    {
        $this->adiciona = true;

        foreach ($_SESSION['carrinho'] as $indice => $item) {
            if ($item['idproduto'] == $this->__get('idproduto')) {
                $_SESSION['carrinho'][$indice]['quantidade'] += $_POST['quantidade']; // altera a quantidade se um produto ja for existente
                $_SESSION['carrinho'][$indice]['subtotal'] = $item['preco'] * $_SESSION['carrinho'][$indice]['quantidade']; //altera o subtotal
                $this->__set('adiciona', false);
            }
        }

        if ($this->__get('adiciona')) {

            $aux[] = array_merge($produtos[0], array('quantidade' => $this->__get('quantidade'), 'subtotal' => $produtos[0]['preco'] * $this->__get('quantidade')));

            $_SESSION['carrinho'] = array_merge($aux, $_SESSION['carrinho']);

            $this->__set('adiciona', false);
        }
    }

    public function editarQuantidade()
    {
        $this->idproduto = $_POST['idproduto'];

        foreach ($_SESSION['carrinho'] as $indice => $item) {
            if ($item['idproduto'] == $this->idproduto) {
                $_SESSION['carrinho'][$indice]['quantidade'] = $_POST['quantidade'];
                $_SESSION['carrinho'][$indice]['subtotal'] = $item['preco'] * $_SESSION['carrinho'][$indice]['quantidade']; //altera o subtotal
            }
        }
    }

    public function excluiCarrinho(){
        unset($_SESSION['carrinho'][$_GET['id']]);
    }

    public function limpaCarrinho(){
        unset($_SESSION['carrinho']);
        unset($_SESSION['totalcarrinho']);
        unset($_SESSION['observacao']);
    }
}
