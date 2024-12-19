<?php 
session_start();
require_once 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // 'client' or 'avocat'

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
        $stmt->execute([$email, $role]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            
            // Redirect based on role
            if ($role === 'avocat') {
                header('Location: avocat/dashboard.php');
            } else if ($role === 'admin') {
                header('Location: admin/dashboard.php');
            } else {
                header('Location: client/dashboard.php');
            }
            exit();
        } else {
            $error = "Identifiants invalides";
        }
    } catch(PDOException $e) {
        $error = "Erreur: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LawQuill - Connexion</title>
</head>
<body class="bg-[#001a10]">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-[#110801] p-8 rounded-lg shadow-xl w-full max-w-md border border-[#bb9a4f]/20">
            <div class="text-center mb-8">
                <h2 class="text-[#bb9a4f] text-3xl font-light">Connexion</h2>
            </div>

            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" class="space-y-6">
                <div>
                    <label class="text-[#bb9a4f]" for="role">Je suis un:</label>
                    <select name="role" id="role" required class="mt-1 block w-full rounded-md bg-[#001a10] border-[#bb9a4f] text-gray-300">
                        <option value="client">Client</option>
                        <option value="avocat">Avocat</option>
                    </select>
                </div>

                <div>
                    <label class="text-[#bb9a4f]" for="email">Email</label>
                    <input type="email" name="email" id="email" required 
                           class="mt-1 block w-full rounded-md bg-[#001a10] border-[#bb9a4f] text-gray-300">
                </div>

                <div>
                    <label class="text-[#bb9a4f]" for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" required 
                           class="mt-1 block w-full rounded-md bg-[#001a10] border-[#bb9a4f] text-gray-300">
                </div>

                <button type="submit" class="w-full bg-[#bb9a4f] text-[#001a10] py-2 px-4 rounded hover:bg-[#a68a45] transition-colors">
                    Se connecter
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="register.php" class="text-[#bb9a4f] hover:text-[#a68a45]">Créer un compte</a>
            </div>
        </div>
    </div>
</body>
</html>