<?php
require_once '../includes/config.php';

// Check if user is logged in and is a lawyer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'avocat') {
    header('Location: ../auth/login.php');
    exit();
}

$lawyer_id = $_SESSION['user_id'];

// Get current information
$stmt = $mysqli->prepare("
    SELECT u.*, i.* 
    FROM users u 
    LEFT JOIN information i ON u.id = i.user_id 
    WHERE u.id = ?
");
$stmt->bind_param("i", $lawyer_id);
$stmt->execute();
$lawyer_info = $stmt->get_result()->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle photo upload
    $photo_url = $lawyer_info['photo_url']; // Keep existing photo by default
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['photo']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $newname = uniqid() . '.' . $filetype;
            $upload_dir = "../assets/uploads/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $newname)) {
                $photo_url = $newname;
            }
        }
    }

    $specialties = $mysqli->real_escape_string($_POST['specialties']);
    $biography = $mysqli->real_escape_string($_POST['biography']);
    
    // Check if information record exists
    $check = $mysqli->prepare("SELECT id_information FROM information WHERE user_id = ?");
    $check->bind_param("i", $lawyer_id);
    $check->execute();
    
    if ($check->get_result()->num_rows > 0) {
        // Update existing record
        $query = "UPDATE information SET specialties = ?, biography = ?, photo_url = ? WHERE user_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sssi", $specialties, $biography, $photo_url, $lawyer_id);
    } else {
        // Insert new record
        $query = "INSERT INTO information (user_id, specialties, biography, photo_url) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("isss", $lawyer_id, $specialties, $biography, $photo_url);
    }
    
    if ($stmt->execute()) {
        $success = "Informations mises à jour avec succès";
        // Refresh lawyer info
        $stmt = $mysqli->prepare("
            SELECT u.*, i.* 
            FROM users u 
            LEFT JOIN information i ON u.id = i.user_id 
            WHERE u.id = ?
        ");
        $stmt->bind_param("i", $lawyer_id);
        $stmt->execute();
        $lawyer_info = $stmt->get_result()->fetch_assoc();
    } else {
        $error = "Erreur lors de la mise à jour";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer mon profil professionnel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#001a10]">
    <nav class="bg-[#001a10] border-b border-[#bb9a4f]/20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <a href="../index.php" class="text-[#bb9a4f] text-2xl flex items-center">⚖ LawQuill</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-[#bb9a4f] hover:text-[#a68a45]">Dashboard</a>
                    <a href="../auth/logout.php" class="text-[#bb9a4f] hover:text-[#a68a45]">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-semibold text-[#bb9a4f] mb-8">Gérer mon profil professionnel</h1>

        <?php if (isset($success)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white/10 rounded-lg p-8">
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Photo -->
                <div>
                    <label class="block text-[#bb9a4f] mb-2">Photo de profil</label>
                    <?php if ($lawyer_info['photo_url']): ?>
                        <img src="../assets/uploads/<?php echo htmlspecialchars($lawyer_info['photo_url']); ?>" 
                             alt="Photo de profil" 
                             class="w-32 h-32 object-cover rounded-full mb-4">
                    <?php endif; ?>
                    <input type="file" name="photo" accept="image/*"
                           class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white">
                </div>

                <!-- Specialties -->
                <div>
                    <label class="block text-[#bb9a4f] mb-2">Spécialités</label>
                    <input type="text" name="specialties" 
                           value="<?php echo htmlspecialchars($lawyer_info['specialties'] ?? ''); ?>"
                           class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white"
                           placeholder="Ex: Droit civil, Droit des affaires, ...">
                </div>

                <!-- Biography -->
                <div>
                    <label class="block text-[#bb9a4f] mb-2">Biographie</label>
                    <textarea name="biography" rows="6" 
                              class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white"
                              placeholder="Présentez votre parcours professionnel..."><?php echo htmlspecialchars($lawyer_info['biography'] ?? ''); ?></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="bg-[#bb9a4f] text-[#001a10] px-6 py-2 rounded hover:bg-[#a68a45]">
                    Mettre à jour le profil
                </button>
            </form>
        </div>
    </div>
</body>
</html>