<?php
    include('../assets/includes/config.php');

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
        $mysqli->begin_transaction();
        
        try {
            // Insertion dans la table users
            $query = "INSERT INTO users (firstname, lastname, email, password, phone, role, cin) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($query);
            $cin = isset($_POST['cin']) ? $_POST['cin'] : null;
            $stmt->bind_param("sssssss", $firstname, $lastname, $email, $hashedPassword, $phone, $role, $cin);
            $stmt->execute();
            $user_id = $mysqli->insert_id;

            // Si c'est un avocat, insérer dans la table information
            if ($role === 'avocat') {
                $specialties = $mysqli->real_escape_string($_POST['specialties']);
                $biography = $mysqli->real_escape_string($_POST['biography']);
                
                // Gérer l'upload de la photo
                $photo_url = null;
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                    $allowed = ['jpg', 'jpeg', 'png'];
                    $filename = $_FILES['photo']['name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    
                    if (in_array($ext, $allowed)) {
                        $newName = uniqid() . '.' . $ext;
                        $destination = '../assets/uploads/' . $newName;
                        
                        if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                            $photo_url = $newName;
                        }
                    }
                }

                $query = "INSERT INTO information (user_id, specialties, biography, photo_url) VALUES (?, ?, ?, ?)";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("isss", $user_id, $specialties, $biography, $photo_url);
                $stmt->execute();
            }

            $mysqli->commit();
            $_SESSION['success'] = "Compte créé avec succès!";
            header("Location: login.php");
            exit();
            
        } catch (Exception $e) {
            $mysqli->rollback();
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
<body class="bg-[#001a10]">
    <!-- Le nav reste le même -->
    
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white/10 rounded-lg shadow-xl p-8">
            <h2 class="text-2xl font-bold mb-6 text-[#bb9a4f] text-center">Créer un compte</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php foreach($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Informations de base -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[#bb9a4f] mb-2">Prénom</label>
                        <input type="text" name="firstname" required
                               class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white">
                    </div>
                    <div>
                        <label class="block text-[#bb9a4f] mb-2">Nom</label>
                        <input type="text" name="lastname" required
                               class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[#bb9a4f] mb-2">Email</label>
                        <input type="email" name="email" required
                               class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white">
                    </div>
                    <div>
                        <label class="block text-[#bb9a4f] mb-2">Téléphone</label>
                        <input type="tel" name="phone"
                               class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white">
                    </div>
                </div>

                <div>
                    <label class="block text-[#bb9a4f] mb-2">Mot de passe</label>
                    <input type="password" name="password" required
                           class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white">
                </div>

                <div>
                    <label class="block text-[#bb9a4f] mb-2">Type de compte</label>
                    <select name="role" id="roleSelect"
                            class="w-full bg-[#bb9a4f]/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white">
                        <option  class="text-black"    value="client">Client</option>
                        <option class="text-black"    value="avocat">Avocat</option>
                    </select>
                </div>

                <!-- Champs spécifiques pour avocat -->
                <div id="avocatFields" class="hidden space-y-6">
                    <div>
                        <label class="block text-[#bb9a4f] mb-2">Numéro CIN</label>
                        <input type="text" name="cin"
                               class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white">
                    </div>

                    <div>
                        <label class="block text-[#bb9a4f] mb-2">Spécialités</label>
                        <textarea name="specialties" rows="2"
                                  class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white"></textarea>
                    </div>

                    <div>
                        <label class="block text-[#bb9a4f] mb-2">Biographie</label>
                        <textarea name="biography" rows="4"
                                  class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white"></textarea>
                    </div>

                    <div>
                        <label class="block text-[#bb9a4f] mb-2">Photo de profil</label>
                        <input type="file" name="photo" accept="image/*"
                               class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white">
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-[#bb9a4f] text-[#001a10] py-2 rounded hover:bg-[#a68a45] transition-colors font-bold">
                    S'inscrire
                </button>

                <p class="text-center text-gray-300">
                    Déjà insert? 
                    <a href="login.php" class="text-[#bb9a4f] hover:text-[#a68a45]">Connectez-vous</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        const roleSelect = document.getElementById('roleSelect');
        const avocatFields = document.getElementById('avocatFields');

        roleSelect.addEventListener('change', function() {
            if (this.value === 'avocat') {
                avocatFields.classList.remove('hidden');
            } else {
                avocatFields.classList.add('hidden');
            }
        });
    </script>

    <!-- Footer reste le même -->
</body>
</html>