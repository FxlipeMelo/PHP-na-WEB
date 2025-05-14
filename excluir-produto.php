<?php

require "src/conexao-bd.php";
require "src/Modelo/Produto.php";
require "src/Repository/ProdutoRepository.php";

$produtoRepositorio = new ProdutoRepository($pdo);
$produtoRepositorio->deletarProduto($_POST['id']);

header("Location: admin.php");