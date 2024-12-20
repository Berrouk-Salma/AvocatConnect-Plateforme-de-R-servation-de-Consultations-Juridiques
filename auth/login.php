<?php
// require_once './includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $mysqli->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];
    
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['firstname'] . ' ' . $user['lastname'];
            
            switch($user['role']) {
                case 'avocat':
                    header("Location: ../avocat/dashboard.php");
                    break;
                case 'client':
                    header("Location: ../client/dashboard.php");
                    break;
                case 'admin':
                    header("Location: ../admin/dashboard.php");
                    break;
            }
            exit();
        }
    }
    $error = "Email ou mot de passe incorrect";
    
    $stmt->close();
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
            <a href="index.php" class="text-[#bb9a4f] text-2xl">⚖ LawQuill</a>
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
                    <input 
                        type="email" 
                        name="email" 
                        class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white focus:border-[#bb9a4f] focus:ring-1 focus:ring-[#bb9a4f] outline-none" 
                        required
                    >
                </div>

                <!-- Mot de passe -->
                <div>
                    <label class="block text-[#bb9a4f] mb-2">Mot de passe</label>
                    <input 
                        type="password" 
                        name="password" 
                        class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white focus:border-[#bb9a4f] focus:ring-1 focus:ring-[#bb9a4f] outline-none" 
                        required
                    >
                </div>

                <!-- Options de connexion -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember" 
                            name="remember" 
                            class="h-4 w-4 border-[#bb9a4f]/20 rounded text-[#bb9a4f] focus:ring-[#bb9a4f]"
                        >
                        <label for="remember" class="ml-2 block text-sm text-[#bb9a4f]">
                            Se souvenir de moi
                        </label>
                    </div>
                    <a href="forgot-password.php" class="text-sm text-[#bb9a4f] hover:text-[#a68a45]">
                        Mot de passe oublié?
                    </a>
                </div>

                <!-- Bouton de connexion -->
                <button 
                    type="submit" 
                    class="w-full bg-[#bb9a4f] text-[#001a10] py-2 rounded hover:bg-[#a68a45] transition-colors font-bold"
                >
                    Se connecter
                </button>

                <!-- Lien vers l'inscription -->
                <p class="text-center text-gray-300 mt-4">
                    Pas encore de compte? 
                    <a href="register.php" class="text-[#bb9a4f] hover:text-[#a68a45]">
                        Inscrivez-vous
                    </a>
                </p>
            </form>

            <!-- Séparateur -->
            <div class="relative mt-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-[#bb9a4f]/20"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-[#001a10] text-[#bb9a4f]">
                        Ou connectez-vous avec
                    </span>
                </div>
            </div>

            <!-- Boutons sociaux -->
            <div class="mt-6 grid grid-cols-2 gap-3">
                <button class="flex justify-center items-center px-4 py-2 border border-[#bb9a4f]/20 rounded text-[#bb9a4f] hover:bg-[#bb9a4f] hover:text-[#001a10]">
                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.477 2 2 6.477 2 12c0 4.418 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.604-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.532 1.03 1.532 1.03.892 1.529 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.836c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.202 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.137 20.164 22 16.417 22 12c0-5.523-4.477-10-10-10z"/>
                    </svg>
                    Github
                </button>
                <button class="flex justify-center items-center px-4 py-2 border border-[#bb9a4f]/20 rounded text-[#bb9a4f] hover:bg-[#bb9a4f] hover:text-[#001a10]">
                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"/>
                    </svg>
                    Facebook
                </button>
            </div>
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