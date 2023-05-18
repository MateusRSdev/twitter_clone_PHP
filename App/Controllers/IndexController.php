<?php

namespace App\Controllers;

// recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

// os models


class IndexController extends Action
{


	public function index(){
		$this->view->login = isset($_GET["login"]) ? isset($_GET["login"]) : "";
		$this->render("Index");
	}

	public function inscreverse(){
		$this->view->usuario =  array(
			"nome" => "",
			"email" => "",
			"senha" => ""
		);
		$this->view->erroCadastro = false;
		$this->render("inscreverse");
	}

	public function registrar()
	{
		// echo "<pre>";
		// print_r($_POST);
		// recebendo os dados do formulario

		$usuario = Container::getModel("Usuario");

		$usuario->__set("nome", $_POST["nome"]);
		$usuario->__set("email", $_POST["email"]);
		$usuario->__set("senha", md5($_POST["senha"]));

		// print_r($usuario);

		if ($usuario->validarCadastro() && count($usuario->GetUsuarioPorEmail()) == 0) {
			$usuario->salvar();
			$this->render("cadastro");

		} else {
			
			$this->view->usuario =  array(
				"nome" => $_POST["nome"],
				"email" => $_POST["email"],
				"senha" => $_POST["senha"]
			);

			$this->view->erroCadastro = true;
			$this->render("inscreverse");
		}


		// sucesso



		// erro

	}



}


?>