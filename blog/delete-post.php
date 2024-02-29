<?php
require_once 'src\database.php'; // Ajustez le chemin
require_once 'src\Post.php';
 // Ajustez le chemin

 use Daeme\SRC\Post;
 use Daeme\SRC\Database;

$pdo = Database::getConnection();
$postModel = new Post($pdo);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $postModel->deletePost($id);
    // Dans delete-post.php et edit-post.php après avoir effectué l'opération
header('Location: admin.php'); // Chemin relatif correct
exit();

}

?>
