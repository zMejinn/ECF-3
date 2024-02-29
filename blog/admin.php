<?php
session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['user_role']) !== 'admin') {
    header('Location: index.php');
    exit;
}

require __DIR__ . '/header.php';
require_once 'src\database.php'; // Ajustez ce chemin si nécessaire
require_once __DIR__ . '/vendor/autoload.php'; // Assurez-vous que le chemin est correct
$pageRequiresAuth = true;

// Assurez-vous que le chemin et le nom de l'espace de noms sont corrects
use Daeme\SRC\Post; // Utilisez PostModel si c'est le nom de votre classe
use Daeme\SRC\Database;

// Obtenez la connexion PDO
$pdo = Database::getConnection();



// Créez une instance de votre modèle de post
$postModel = new Post($pdo); // Ou new PostModel($pdo) si c'est le nom de votre classe

// Maintenant, utilisez $postModel pour obtenir tous les posts
$posts = $postModel->getAll(); // Assurez-vous que la méthode s'appelle bien getAll()



?>
<!-- Bootstrap JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

<div class="container mt-4">
    <h1>Tableau de bord</h1>
    <div>
    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createPostModal">Ajouter un Post</button>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Contenu</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    
                    <tr>
                        <td><?php echo htmlspecialchars($post['id']); ?></td>
                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                        <td><?php echo htmlspecialchars($post['body']); ?></td>
                        <td>
                            <!-- Dans dashboard.php -->
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $post['id']; ?>">Modifier</button>
                            
                            <a href="delete-post.php?id=<?php echo $post['id']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Aucun article trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal pour la création d'un nouveau post -->
<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createPostModalLabel">Nouveau Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="createPostForm">
          <div class="mb-3">
            <label for="newPostTitle" class="form-label">Titre</label>
            <input type="text" class="form-control" id="newPostTitle" name="title" required>
          </div>
          <div class="mb-3">
            <label for="newPostContent" class="form-label">Contenu</label>
            <textarea class="form-control" id="newPostContent" name="content" rows="3" required></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Créer Post</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Ajoutez ceci à la fin de votre fichier dashboard.php, juste avant la fermeture de la balise <body> ou </html> -->
<!-- Modal pour l'édition -->
<?php foreach ($posts as $post): ?>
<div class="modal fade" id="editModal<?php echo $post['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $post['id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel<?php echo $post['id']; ?>">Modifier le Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm<?php echo $post['id']; ?>">
                    <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre</label>
                        <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($post['title']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Contenu</label>
                        <textarea class="form-control" name="body"><?php echo htmlspecialchars($post['body']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="updatedDate" class="form-label">Date de Mise à Jour</label>
                        <input type="date" class="form-control" name="updatedDate" id="updatedDate<?= $post['id']; ?>" value="<?= date('Y-m-d'); // Utilisez la date actuelle comme valeur par défaut ?>">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Sauvegarder les changements</button>
                    </div>
                    <td>
                    <a href="show.php?id=<?= htmlspecialchars($post['id']) ?>" class="btn btn-primary">Voir le post</a>
                    </td>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>



<!-- JavaScript pour gérer la soumission des formulaires d'édition -->
<script>
    document.getElementById('createPostForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    fetch('create-post.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload(); // Rechargez la page pour afficher le nouveau post
        } else {
            alert("Erreur lors de la création du post. Veuillez réessayer.");
        }
    })
    .catch(error => console.error('Error:', error));
});


document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelectorAll('form[id^="editForm"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            fetch('edit-post.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    let modalId = '#editModal' + formData.get('id');
                    let modalInstance = bootstrap.Modal.getInstance(document.querySelector(modalId));
                    modalInstance.hide();

                    // Mettre à jour le titre, le contenu, et la date du post sur la page
                    document.querySelector(`#postTitle${formData.get('id')}`).textContent = formData.get('title');
                    document.querySelector(`#postBody${formData.get('id')}`).textContent = formData.get('body');
                    // Supposons que vous avez un élément pour afficher la date de mise à jour
                    document.querySelector(`#postUpdatedDate${formData.get('id')}`).textContent = `Mis à jour le: ${data.updatedDate}`;
                } else {
                    alert("La mise à jour a échoué. Veuillez réessayer.");
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        });
    });
});

</script>

