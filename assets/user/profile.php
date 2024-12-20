<?php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les infos de l'utilisateur
$stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Traitement de la mise à jour du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $mysqli->real_escape_string(trim($_POST['firstname']));
    $lastname = $mysqli->real_escape_string(trim($_POST['lastname']));
    $phone = $mysqli->real_escape_string(trim($_POST['phone']));
    $address = $mysqli->real_escape_string(trim($_POST['address']));
    
    // Validation
    $errors = [];
    if (empty($firstname)) $errors[] = "Le prénom est requis";
    if (empty($lastname)) $errors[] = "Le nom est requis";
    
    if (empty($errors)) {
        $stmt = $mysqli->prepare("
            UPDATE users 
            SET firstname = ?, lastname = ?, phone = ?, address = ?
            WHERE id = ?
        ");
        $stmt->bind_param("ssssi", $firstname, $lastname, $phone, $address, $user_id);
        
        if ($stmt->execute()) {
            $success = "Profil mis à jour avec succès";
            // Mettre à jour les données de session
            $_SESSION['name'] = $firstname . ' ' . $lastname;
            // Recharger les infos utilisateur
            $stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
        } else {
            $errors[] = "Erreur lors de la mise à jour du profil";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Cabinet d'Avocats</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#001a10]">
    <!-- Navigation -->
    <nav class="bg-[#001a10] border-b border-[#bb9a4f]/20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <a href="../index.php" class="text-[#bb9a4f] text-2xl flex items-center">⚖ LawQuill</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-[#bb9a4f] hover:text-[#a68a45]">Tableau de bord</a>
                    <a href="../logout.php" class="text-[#bb9a4f] hover:text-[#a68a45] border border-[#bb9a4f] px-4 py-2 rounded hover:bg-[#bb9a4f] hover:text-[#001a10]">
                        Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-2xl font-semibold text-[#bb9a4f] mb-8">Mon Profil</h1>

                <?php if (isset($success)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php foreach($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="bg-white/10 rounded-lg shadow overflow-hidden">
                    <form method="POST" class="p-6 space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[#bb9a4f] mb-2">Prénom</label>
                                <input type="text" name="firstname" 
                                       value="<?php echo htmlspecialchars($user['firstname']); ?>"
                                       class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white" 
                                       required>
                            </div>
                            <div>
                                <label class="block text-[#bb9a4f] mb-2">Nom</label>
                                <input type="text" name="lastname" 
                                       value="<?php echo htmlspecialchars($user['lastname']); ?>"
                                       class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white" 
                                       required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[#bb9a4f] mb-2">Email</label>
                            <input type="email" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>"
                                   class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white" 
                                   disabled>
                            <p class="text-sm text-gray-400 mt-1">L'email ne peut pas être modifié</p>
                        </div>

                        <div>
                            <label class="block text-[#bb9a4f] mb-2">Téléphone</label>
                            <input type="tel" name="phone" 
                                   value="<?php echo htmlspecialchars($user['phone']); ?>"
                                   class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white">
                        </div>

                        <div>
                            <label class="block text-[#bb9a4f] mb-2">Adresse</label>
                            <textarea name="address" rows="3"
                                      class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white"><?php echo htmlspecialchars($user['address']); ?></textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" 
                                    class="w-full bg-[#bb9a4f] text-[#001a10] py-2 rounded hover:bg-[#a68a45] transition-colors">
                                Mettre à jour le profil
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Section changement de mot de passe -->
                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-[#bb9a4f] mb-4">Changer le mot de passe</h2>
                    <div class="bg-white/10 rounded-lg shadow overflow-hidden">
                        <form action="change_password.php" method="POST" class="p-6 space-y-6">
                            <div>
                                <label class="block text-[#bb9a4f] mb-2">Mot de passe actuel</label>
                                <input type="password" name="current_password"
                                       class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white" 
                                       required>
                            </div>

                            <div>
                                <label class="block text-[#bb9a4f] mb-2">Nouveau mot de passe</label>
                                <input type="password" name="new_password"
                                       class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white" 
                                       required>
                            </div>

                            <div>
                                <label class="block text-[#bb9a4f] mb-2">Confirmer le nouveau mot de passe</label>
                                <input type="password" name="confirm_password"
                                       class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white" 
                                       required>
                            </div>

                            <div class="pt-4">
                                <button type="submit" 
                                        class="w-full bg-[#bb9a4f] text-[#001a10] py-2 rounded hover:bg-[#a68a45] transition-colors">
                                    Changer le mot de passe
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>