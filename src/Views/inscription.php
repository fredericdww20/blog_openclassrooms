<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

?>


<div class="container">
    <section class="jumbotron mt-5">
        <article class="text-center col-12 col-md-8 offset-md-2">
            <svg class="mt-5 mb-5 bi bi-lock" width="20%" height="20%" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M11.5 8h-7a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1zm-7-1a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-7zm0-3a3.5 3.5 0 1 1 7 0v3h-1V4a2.5 2.5 0 0 0-5 0v3h-1V4z"/>
            </svg>
            <h2> Inscription </h2>
            <form role="form" action="" method="post">
                <div class="form-group">
                    <label for="">Nom</label>
                    <input type="text" class="form-control text-center" name = "name" placeholder="Indiquez votre nom">
                </div>
                <div class="form-group">
                    <label for="">Prenom</label>
                    <input type="text" class="form-control text-center" name = "first_name" placeholder="Indiquez votre prenom">
                </div>
                <div class="form-group">
                    <label for="">Adresse mail</label>
                    <input type="email" class="form-control text-center" name = "email" placeholder="Indiquez votre adresse mail">
                </div>
                <div class="form-group">
                    <label for="">Mot de passe</label>
                    <input type="password" class="form-control text-center" name="password"  id="pw" placeholder="Indiquez votre mot de passe">
                </div>
                <button type="submit" class="btn btn-primary mt-4 mb-4">Connexion</button>
            </form>
        </article>
    </section>
</div>