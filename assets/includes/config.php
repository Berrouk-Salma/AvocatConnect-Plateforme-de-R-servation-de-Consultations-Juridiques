<?php
// require '../assets/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $mysqli->real_escape_string(trim($_POST['firstname']));
    $lastname = $mysqli->real_escape_string(trim($_POST['lastname']));
    $email = $mysqli->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];
    $phone = $mysqli->real_escape_string(trim($_POST['phone']));
    $role = $mysqli->real_escape_string($_POST['role']);
    
    // Validation
    $errors = [];
    if (empty($firstname)) $errors[] = "Le prénom est requis";
    if (empty($lastname)) $errors[] = "Le nom est requis";
    if (empty($email)) $errors[] = "L'email est requis";
    if (empty($password)) $errors[] = "Le mot de passe est requis";
    
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO users (firstname, lastname, email, password, phone, role) 
                 VALUES (?, ?, ?, ?, ?, ?)";
                 
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssssss", $firstname, $lastname, $email, $hashedPassword, $phone, $role);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Compte créé avec succès!";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Erreur d'inscription: " . $mysqli->error;
        }
        $stmt->close();
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
<body class="bg-[#001a10]">
    <!-- Navigation -->
    <nav class="p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="index.php" class="text-[#bb9a4f] text-2xl">⚖ LawQuill</a>
            <a href="login.php" class="text-[#bb9a4f] border border-[#bb9a4f] px-4 py-2 hover:bg-[#bb9a4f] hover:text-[#001a10]">
                Se connecter
            </a>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white/10 rounded-lg shadow-xl p-8">
            <h2 class="text-2xl font-bold mb-6 text-[#bb9a4f] text-center">Créer un compte</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php foreach($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <!-- Nom et Prénom -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[#bb9a4f] mb-2">Prénom</label>
                        <input type="text" name="firstname" 
                               class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white focus:border-[#bb9a4f] focus:ring-1 focus:ring-[#bb9a4f] outline-none" 
                               required>
                    </div>
                    <div>
                        <label class="block text-[#bb9a4f] mb-2">Nom</label>
                        <input type="text" name="lastname" 
                               class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white focus:border-[#bb9a4f] focus:ring-1 focus:ring-[#bb9a4f] outline-none" 
                               required>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-[#bb9a4f] mb-2">Email</label>
                    <input type="email" name="email" 
                           class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white focus:border-[#bb9a4f] focus:ring-1 focus:ring-[#bb9a4f] outline-none" 
                           required>
                </div>

                <!-- Téléphone -->
                <div>
                    <label class="block text-[#bb9a4f] mb-2">Téléphone</label>
                    <input type="tel" name="phone" 
                           class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white focus:border-[#bb9a4f] focus:ring-1 focus:ring-[#bb9a4f] outline-none">
                </div>

                <!-- Mot de passe -->
                <div>
                    <label class="block text-[#bb9a4f] mb-2">Mot de passe</label>
                    <input type="password" name="password" 
                           class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white focus:border-[#bb9a4f] focus:ring-1 focus:ring-[#bb9a4f] outline-none" 
                           required>
                </div>

                <!-- Type de compte -->
                <div>
                    <label class="block text-[#bb9a4f] mb-2">Type de compte</label>
                    <select name="role" 
                            class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white focus:border-[#bb9a4f] focus:ring-1 focus:ring-[#bb9a4f] outline-none" 
                            required>
                        <option value="client">Client</option>
                        <option value="avocat">Avocat</option>
                    </select>
                </div>

                <!-- Bouton d'inscription -->
                <button type="submit" 
                        class="w-full bg-[#bb9a4f] text-[#001a10] py-2 rounded hover:bg-[#a68a45] transition-colors font-bold">
                    S'inscrire
                </button>

                <!-- Lien vers la connexion -->
                <p class="text-center text-gray-300 mt-4">
                    Déjà inscrit? 
                    <a href="login.php" class="text-[#bb9a4f] hover:text-[#a68a45]">
                        Connectez-vous
                    </a>
                </p>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-8 border-t border-[#bb9a4f]/20 mt-8">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <p class="text-gray-400">
                Lawyers © 2024. All Rights Reserved
                <br> 
                Created by Salma Berrouk
            </p>
        </div>
    </footer>
</body>
</html>