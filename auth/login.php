<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $mysqli->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    
    $sql = "SELECT id, email, password, role, firstname, lastname FROM users WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['firstname'] . ' ' . $user['lastname'];
            
            // Redirect based on role
            if ($user['role'] === 'avocat') {
                header("Location: ../lawyer/dashboard.php");
            } else {
                header("Location: ../user/dashboard.php");
            }
            exit();
        } else {
            $error = "Mot de passe incorrect";
        }
    } else {
        $error = "Email non trouvé";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Cabinet d'Avocats</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#001a10]">
    <!-- Navigation -->
    <nav class="p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="../index.php" class="text-[#bb9a4f] text-2xl">⚖ LawQuill</a>
            <a href="register.php" class="text-[#bb9a4f] border border-[#bb9a4f] px-4 py-2 hover:bg-[#bb9a4f] hover:text-[#001a10]">
                S'inscrire
            </a>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white/10 rounded-lg shadow-xl p-8">
            <h2 class="text-2xl font-bold mb-6 text-[#bb9a4f] text-center">Connexion</h2>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <!-- Email -->
                <div>
                    <label class="block text-[#bb9a4f] mb-2">Email</label>
                    <input type="email" name="email" required
                           class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white focus:border-[#bb9a4f] focus:ring-1 focus:ring-[#bb9a4f] outline-none">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-[#bb9a4f] mb-2">Mot de passe</label>
                    <input type="password" name="password" required
                           class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white focus:border-[#bb9a4f] focus:ring-1 focus:ring-[#bb9a4f] outline-none">
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-[#bb9a4f] text-[#001a10] py-2 rounded hover:bg-[#a68a45] transition-colors font-bold">
                    Se connecter
                </button>

                <!-- Register Link -->
                <p class="text-center text-gray-300 mt-4">
                    Pas encore de compte? 
                    <a href="register.php" class="text-[#bb9a4f] hover:text-[#a68a45]">
                        Inscrivez-vous
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