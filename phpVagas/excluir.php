<?php

require __DIR__ . '/vendor/autoload.php';

use \App\Entity\Vaga;

//VALIDAÇÃO DO ID
if (!isset($_GET['id']) or !is_numeric($_GET['id'])) {
    header('location: index.php?status=error');
    exit;
}

$obVaga = Vaga::getVaga($_GET['id']);

//VALIDAÇÃO DA VAGA
// if(!$obVaga instanceof Vaga) { //se era uma instancia de Vaga, mas assim nao estava pegando
if (empty($obVaga)) { //só está verificando se é uma array vazio
    header('location: index.php?status=error');
    exit;
}

// echo "<pre>"; print_r($obVaga); echo "</pre>"; exit;


//VALIDAÇÃO DO POST
if (isset($_POST['excluir'])) {
    $obVaga->excluir();

    header('location: index.php?status=success');
    exit;
}

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/confirmar-exclusao.php';
include __DIR__ . '/includes/footer.php';
