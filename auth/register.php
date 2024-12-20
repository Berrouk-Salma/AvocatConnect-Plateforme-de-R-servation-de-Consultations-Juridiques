<?php
require_once '../assets/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phone = trim($_POST['phone']);
    $role = $_POST['role'];
    
    // Validation simple
    $errors = [];
    if (empty($firstname)) $errors[] = "Le prénom est requis";
    if (empty($lastname)) $errors[] = "Le nom est requis";
    if (empty($email)) $errors[] = "L'email est requis";
    if (empty($password)) $errors[] = "Le mot de passe est requis";
    
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $email, $hashedPassword, $phone, $role]);
            
            $_SESSION['success'] = "Compte créé avec succès!";
            header("Location: login.php");
            exit();
        } catch(PDOException $e) {
            $errors[] = "Erreur d'inscription: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Cabinet d'Avocats</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6">Inscription</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php foreach($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-gray-700">Prénom</label>
                    <input type="text" name="firstname" class="w-full border rounded px-3 py-2" required>
                </div>
                
                <div>
                    <label class="block text-gray-700">Nom</label>
                    <input type="text" name="lastname" class="w-full border rounded px-3 py-2" required>
                </div>
                
                <div>
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
                </div>
                
                <div>
                    <label class="block text-gray-700">Téléphone</label>
                    <input type="tel" name="phone" class="w-full border rounded px-3 py-2">
                </div>
                
                <div>
                    <label class="block text-gray-700">Mot de passe</label>
                    <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
                </div>
                
                <div>
                    <label class="block text-gray-700">Type de compte</label>
                    <select name="role" class="w-full border rounded px-3 py-2" required>
                        <option value="client">Client</option>
                        <option value="avocat">Avocat</option>
                    </select>
                </div>
                
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">
                    S'inscrire
                </button>
            </form>
            
            <p class="mt-4 text-center">
                Déjà inscrit? <a href="login.php" class="text-blue-500">Connectez-vous</a>
            </p>
        </div>
    </div>
</body>
</html>