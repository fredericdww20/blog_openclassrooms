<?php

namespace App\Controllers;

use App\Models\CommentManager;
use App\Models\PostManager;
use App\Request;
use PDOException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class CommentController
 */
class CommentController extends Controller
{
    private CommentManager $commentManager;

    public function __construct()
    {
        $this->commentManager = new CommentManager();

        parent::__construct();
    }

    /**
     * @return string
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function addcomment(): string
    {
        if (!empty($_POST['title']) && !empty($_POST['commentary']) && !empty($_POST['id_post'])) {
            $commentManager = new CommentManager();

            $postId = intval($_POST['id_post']);

            // Vérifiez si l'ID de l'utilisateur est défini dans $_SESSION
            if (isset($_SESSION['user_id']) && is_int($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];

                try {
                    $commentManager->commentate($_POST['title'], $_POST['commentary'], $postId, $userId);
                    $this->addSuccess('Commentaire envoyé');
                } catch (PDOException $e) {
                    $this->addError('Une erreur s\'est produite lors de l\'envoi du commentaire : ' . $e->getMessage());
                }
            } else {
                $this->addError('Veuillez remplir tous les champs du formulaire.');
            }
        } else {
            $this->addError('ID d\'utilisateur invalide.');
        }

        $postManager = new PostManager();
        $post = $postManager->fetch($postId);

        $this->redirect('/OpenClassrooms/post/' . $postId);
    }

    public function deleteComment($id)
    {

        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $role = isset($_SESSION['roles']) && $_SESSION['roles'] === 'ROLE_ADMIN';


        $post = $this->commentManager->fetchcomment($id);

        if (!$post) {

            $this->addError('Commentaire introuvable.');
            $this->redirect('/OpenClassrooms/post/' . $id);
            return;
        }

        // Vérifier les permissions pour la suppression
        if ($role || $post->getIdUser() === $userId) {
            $this->commentManager->delete($id);
            $this->addSuccess('Suppression réussie');
        } else {
            $this->addError('Vous ne pouvez pas supprimer ce post');
        }
        $postId = $post->getId();
        $postManager = new PostManager();
        $post = $postManager->fetch($postId);
        $this->redirect('/OpenClassrooms/post/' . $postId);
    }

    public function updatecomment($id)
    {
        $request = new Request($_POST);
        $title = $request->get('title');
        $commentary = $request->get('commentary');

        $commentuser = $this->commentManager->fetch($id);
        $postId = $commentuser['post_id'] ?? null;

        $comment = $this->commentManager->fetchcomment($id);

        $userId = $_SESSION['user_id'] ?? null;
        $role = isset($_SESSION['roles']) && $_SESSION['roles'] === 'ROLE_ADMIN';

        $errors = [];

        if ($request->isPost() && $comment) {
            if (!$title) {
                $errors[] = 'Le titre est requis.';
            }
            if (!$commentary) {
                $errors[] = 'Le commentaire est requis.';
            }

            if (!$role && $comment->getIdUser() !== $userId) {
                $errors[] = 'Vous ne pouvez pas mettre à jour ce commentaire';
            }

            if (empty($errors)) {

                $this->commentManager->update($id, $title, $commentary);
                $this->addSuccess('Modification du commentaire validée');
                $this->redirect('/OpenClassrooms/post/' . $postId);
            } else {
                foreach ($errors as $error) {
                    $this->addError($error);
                }
                $this->redirect('/OpenClassrooms/post/' . $postId);
            }
        }

        return $this->twig->render('list/editcomment.html.twig', [
            'comment' => $comment,
        ]);
    }

    public function showComment($id)
    {
        $commentRepository = new CommentManager();

        $comments = $commentRepository->fetch($id);

        return $this->twig->render('list/post.html.twig', [
            'comments' => $comments
        ]);
    }

}