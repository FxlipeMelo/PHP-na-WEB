<?php

class ProdutoRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function formarObjeto($dados)
    {
        return new Produto(
            $dados['id'],
            $dados['tipo'],
            $dados['nome'],
            $dados['descricao'],
            $dados['preco'],
            $dados['imagem']
        );
    }

    public function opcoesCafe(): array
    {
        $sql = "SELECT * FROM produtos WHERE tipo = 'Café' ORDER BY preco";
        $statement = $this->pdo->query($sql);
        $produtoCafe = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dadosCafe = array_map(function ($cafe) {
            return $this->formarObjeto($cafe);
        }, $produtoCafe);

        return $dadosCafe;
    }

    public function opcoesAlmoco(): array
    {
        $sql = "SELECT * FROM produtos WHERE tipo = 'Almoço' ORDER BY preco";
        $statement = $this->pdo->query($sql);
        $produtoAlmoco = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dadosAlmoco = array_map(function ($almoco) {
            return $this->formarObjeto($almoco);
        }, $produtoAlmoco);

        return $dadosAlmoco;
    }

    public function buscarTodosProdutos(): array
    {
        $sql = "SELECT * FROM produtos ORDER BY preco";
        $statement = $this->pdo->query($sql);
        $produtos = $statement->fetchAll(PDO::FETCH_ASSOC);

        $dadosProdutos = array_map(function ($dados) {
            return $this->formarObjeto($dados);
        }, $produtos);

        return $dadosProdutos;
    }

    public function deletarProduto(int $id)
    {
        $sql = "DELETE FROM produtos WHERE id = ?";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->execute();
    }

    public function cadastrarProduto(Produto $produto)
    {
        $statement = $this->pdo->prepare("INSERT INTO produtos(tipo, nome, descricao, preco, imagem) VALUES (:tipo, :nome, :descricao, :preco, :imagem)");
        $statement->bindValue(':tipo', $produto->getTipo());
        $statement->bindValue(':nome', $produto->getNome());
        $statement->bindValue(':descricao', $produto->getDescricao());
        $statement->bindValue(':preco', $produto->getPreco());
        $statement->bindValue(':imagem', $produto->getImagem());
        $statement->execute();
    }

    public function buscar(int $id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM produtos WHERE id = ?");
        $statement->bindValue(1, $id);
        $statement->execute();
        $dados = $statement->fetch(PDO::FETCH_ASSOC);

        return $this->formarObjeto($dados);
    }

    public function atualizarProduto(Produto $produto)
    {
        $statement = $this->pdo->prepare("UPDATE produtos SET tipo = :tipo, nome = :nome, descricao = :descricao, preco = :preco WHERE id = :id");
        $statement->bindValue(':tipo', $produto->getTipo());
        $statement->bindValue(':nome', $produto->getNome());
        $statement->bindValue(':descricao', $produto->getDescricao());
        $statement->bindValue(':preco', $produto->getPreco());
        $statement->bindValue(':id', $produto->getId());
        $statement->execute();

        if ($produto->getImagem() !== 'logo-serenatto.png') {
            $this->atualizarFoto($produto);
        }
    }

    private function atualizarFoto(Produto $produto)
    {
        $statement = $this->pdo->prepare("UPDATE produtos SET imagem = :imagem WHERE id = :id");
        $statement->bindValue(':imagem', $produto->getImagem());
        $statement->bindValue(':id', $produto->getId());
        $statement->execute();
    }

}
