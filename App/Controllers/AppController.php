<?php

namespace App\Controllers;

// recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action{

    public function timeline(){

            $this->validaAutenticacao();

            //recuperacao dos tweets
            $tweet = Container::getModel("Tweet");

            $tweet->__set("id_usuario", $_SESSION["id"]);

            
            // variaveis de paginacao
            $total_registros_pagina = 10;
            $pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 1;
            $deslocamento = ($pagina - 1) * $total_registros_pagina;
            
            $tweets = $tweet->getPorPagina($total_registros_pagina,$deslocamento);
            $total_tweets = $tweet->GetTotalRegistros();
            $this->view->total_de_paginas = ceil($total_tweets["total"] / $total_registros_pagina);
            $this->view->pagina_ativa = $pagina;
            
            

            $usuario = Container::getModel("Usuario");
            $usuario->__set("id", $_SESSION["id"]);

            $this->view->info_usuario = $usuario->getInfoUsuario();
            $this->view->total_seguindo = $usuario->getTotalSeguindo();
            $this->view->total_seguidores = $usuario->getTotalSeguidores();
            $this->view->total_tweets = $usuario->getTotalTweets();

            $this->view->tweets = $tweets;


            $this->render("timeline");
       
    }

    public function tweet(){


        

            $this->validaAutenticacao();
       
            $tweet = Container::getModel("tweet");
            $tweet->__set("tweet", $_POST["tweet"]);
            $tweet->__set("id_usuario", $_SESSION["id"]);

            $tweet->salvar();
            header("location: /timeline");

        
    }

    public function validaAutenticacao(){
        session_start();
        if (!isset($_SESSION["id"]) || $_SESSION["id"] == "" && !isset($_SESSION["nome"]) || $_SESSION["nome"] == "") {
            header("location: /?login=erro");
        }
    }

    public function quemSeguir(){
        $this->validaAutenticacao();

        $pesquisar_por = isset($_GET["pesquisarPor"]) ? $_GET["pesquisarPor"] : "";
        $usuarios = array();

        if($pesquisar_por != ""){
            $usuario = Container::getModel("Usuario");
            $usuario->__set("nome",$pesquisar_por);
            $usuario->__set("id", $_SESSION["id"]);
            $usuarios = $usuario->getAll();

            // echo "<br><br><br><br><pre>";
            // print_r($usuarios);
            // echo "</pre>";

        }

        $usuario = Container::getModel("Usuario");
        $usuario->__set("id", $_SESSION["id"]);

        $this->view->info_usuario = $usuario->getInfoUsuario();
        $this->view->total_seguindo = $usuario->getTotalSeguindo();
        $this->view->total_seguidores = $usuario->getTotalSeguidores();
        $this->view->total_tweets = $usuario->getTotalTweets();

        
        
        $this->view->usuarios = $usuarios;
        $this->render("quemSeguir");
    }

    public function acao(){
        $this->validaAutenticacao();
        // acao
        echo "<pre>";
        print_r($_GET);
        echo "</pre>";

        $acao = isset($_GET["acao"]) ? $_GET["acao"] : "";
        $id_usuario_seguindo = isset($_GET["id_usuario"]) ? $_GET["id_usuario"] : "";
        
        // id usuario
        $usuario = Container::getModel("usuario");
        $usuario->__set("id",$_SESSION["id"]);

        if($acao == "seguir"){
            $usuario->seguirUsuario($id_usuario_seguindo);
        }else if($acao == "deixar_de_seguir"){
            $usuario->deixarSeguirUsuario($id_usuario_seguindo);
        }

        header("location: /quem_seguir");
    }

    public function removerTweet(){
        print_r($_POST);
        $this->validaAutenticacao();
        $usuario = Container::getModel("Usuario");
        $usuario->id_tweet_selecionado = $_POST["id_tweet"];
        $usuario->removerTweet();

        header("location: /timeline");
    }
}

?>