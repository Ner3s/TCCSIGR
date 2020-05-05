<?php

namespace App\Models;

use MF\Model\Model;

class Pedido extends Model {
    //adicionar idprod
    //preco
    //precototal
    
	private $id;
    private $idUser;
    private $nomeProd;
    private $precoTotal;
    private $observacao;

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }

    public function __get($atributo){
        return $this->$atributo;
    }

    //salvar
    public function inserePedido(){    
        $query = "insert into pedido (usuario_idusuario, precototal, observacao) values (:usuario_idusuario, :precototal, :observacao)";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':usuario_idusuario', $this->__get('idUser'));
		$stmt->bindValue(':precototal', $this->__get('precoTotal'));
		$stmt->bindValue(':observacao', $this->__get('observacao'));
		$stmt->execute();

        return $this;
    }
    //recuperar

    public function mostrarPedido(){
        $query = "
        select 
            ped.idpedido, u.login, ped.precototal, ped.observacao, ped.date
        from 
            pedido as ped
        inner join 
            usuario as u on u.idusuario = ped.usuario_idusuario
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function mostrarItemPedido($cont){

        $query = "
            select ped.idpedido, u.login, prod.nome, pp.quantidade, pp.preco, pp.subtotal, ped.precototal 
                from pedido as ped 
            inner join 
                usuario as u on u.idusuario = ped.usuario_idusuario 
            inner join 
                pedido_has_produto as pp on ped.idpedido = pp.pedido_idpedido 
            inner join 
                produto as prod on prod.idproduto = pp.produto_idproduto
            where
                ped.idpedido = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1,$cont);
		$stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function contPed(){
        
        $query = "select count(*) as contador from pedido";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch();
    }

    public function pedidoMCaro(){
        $query = "
        select 
            p.idpedido as 'numero_pedido', u.login as 'usuario', p.date as 'data', max(p.precototal) as total
        from 
            pedido as p
        inner join 
            usuario as u 
        on
            u.idusuario = p.usuario_idusuario";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function prodMPedido(){
        $query = "
        select
	        pp.pedido_idpedido as idpedido, p.nome, max(pp.quantidade) quantidade, p.preco
        from 
	        pedido_has_produto as pp
        inner join
	        produto as p on
            pp.produto_idproduto = p.idproduto";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function top10Prod(){
        $query = "
        SELECT 
            pp.produto_idproduto as id_produto, COUNT(*) n_produto, p.nome, sum(pp.quantidade) as quantidade_total
        FROM 
            pedido_has_produto as pp
        INNER JOIN
            produto as p on
            pp.produto_idproduto = p.idproduto
        GROUP BY 
            idproduto
        ORDER BY 
            quantidade_total DESC 
        LIMIT 10";

        $stmt = $this->db->prepare($query);
		$stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function resumDia(){
        $query = "
        SELECT
            pp.pedido_idpedido, p.nome, pp.quantidade, date_format(ped.date,'%d/%m/%Y') as data_formatada, pp.preco as preco_items, ped.precototal
        FROM 
            pedido_has_produto as pp
        inner join
            produto as p on
            pp.produto_idproduto = p.idproduto
        inner join
            pedido as ped on
            ped.idpedido = pp.pedido_idpedido
        where
            date_format(ped.date,'%d/%m/%Y') = date_format(now(),'%d/%m/%Y')
        order by
            ped.idpedido";

        $stmt = $this->db->prepare($query);
		$stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function resumMes(){
        $query = "
        SELECT
            pp.pedido_idpedido, p.nome, pp.quantidade, date_format(ped.date,'%m/%Y') as data_formatada, pp.preco as preco_items, ped.precototal
        FROM 
            pedido_has_produto as pp
        inner join
            produto as p on
            pp.produto_idproduto = p.idproduto
        inner join
            pedido as ped on
            ped.idpedido = pp.pedido_idpedido
        where
            date_format(ped.date,'%m/%Y') = date_format(now(),'%m/%Y')
        order by
            ped.idpedido";

        $stmt = $this->db->prepare($query);
		$stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function resumAnual(){
        $query = "
        SELECT
            pp.pedido_idpedido, p.nome, pp.quantidade, date_format(ped.date,'%Y') as data_formatada, pp.preco as preco_items, ped.precototal
        FROM 
            pedido_has_produto as pp
        inner join
            produto as p on
            pp.produto_idproduto = p.idproduto
        inner join
            pedido as ped on
            ped.idpedido = pp.pedido_idpedido
        where
            date_format(ped.date,'%Y') = date_format(now(),'%Y')
        order by
            ped.idpedido";

        $stmt = $this->db->prepare($query);
		$stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function totDia(){
        $query = "
        select
		    idpedido, precototal
	    from 
		    pedido
	    where
		    date_format(date,'%d/%m/%Y') = date_format(now(),'%d/%m/%Y')";

        $stmt = $this->db->prepare($query);
		$stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function totMes(){
        $query = "
        select
            idpedido, precototal
        from 
            pedido
        where
            date_format(date,'%m/%Y') = date_format(now(),'%m/%Y')";

        $stmt = $this->db->prepare($query);
		$stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function totAnual(){
        $query = "
        select
            idpedido, precototal
        from 
            pedido
        where
            date_format(date,'%Y') = date_format(now(),'%Y')";

        $stmt = $this->db->prepare($query);
		$stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
