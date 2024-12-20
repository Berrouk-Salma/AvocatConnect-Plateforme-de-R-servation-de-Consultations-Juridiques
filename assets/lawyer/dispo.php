<?php
require_once '../includes/config.php';

// Check if user is logged in and is a lawyer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'avocat') {
    header('Location: ../auth/login.php');
    exit();
}

$lawyer_id = $_SESSION['user_id'];

// Handle form submission for new availability
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_debut = $mysqli->real_escape_string($_POST['date_debut']);
    $date_fin = $mysqli->real_escape_string($_POST['date_fin']);
    
    $query = "INSERT INTO disponibilite (date_debut, date_fin, id_user) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssi", $date_debut, $date_fin, $lawyer_id);
    
    if ($stmt->execute()) {
        $success = "Disponibilité ajoutée avec succès";
    } else {
        $error = "Erreur lors de l'ajout de la disponibilité";
    }
}

// Get current availabilities
$query = "SELECT * FROM disponibilite WHERE id_user = ? AND date_debut >= NOW() ORDER BY date_debut";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $lawyer_id);
$stmt->execute();
$availabilities = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer mes disponibilités</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        <h1 class="text-2xl font-semibold text-[#bb9a4f] mb-8">Gérer mes disponibilités</h1>

        <!-- Add Availability Form -->
        <div class="bg-white/10 rounded-lg p-6 mb-8">
            <h2 class="text-xl text-[#bb9a4f] mb-4">Ajouter une disponibilité</h2>
            <form method="POST" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[#bb9a4f] mb-2">Date et heure de début</label>
                        <input type="datetime-local" name="date_debut" required
                               class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white">
                    </div>
                    <div>
                        <label class="block text-[#bb9a4f] mb-2">Date et heure de fin</label>
                        <input type="datetime-local" name="date_fin" required
                               class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white">
                    </div>
                </div>
                <button type="submit" 
                        class="bg-[#bb9a4f] text-[#001a10] px-4 py-2 rounded hover:bg-[#a68a45]">
                    Ajouter
                </button>
            </form>
        </div>

        <!-- Current Availabilities -->
        <div class="bg-white/10 rounded-lg p-6">
            <h2 class="text-xl text-[#bb9a4f] mb-4">Mes disponibilités</h2>
            <?php if ($availabilities->num_rows > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="text-[#bb9a4f]">
                                <th class="px-6 py-3 text-left">Début</th>
                                <th class="px-6 py-3 text-left">Fin</th>
                                <th class="px-6 py-3 text-left">Statut</th>
                                <th class="px-6 py-3 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#bb9a4f]/20">
                            <?php while ($row = $availabilities->fetch_assoc()): ?>
                                <tr class="text-white">
                                    <td class="px-6 py-4">
                                        <?php echo date('d/m/Y H:i', strtotime($row['date_debut'])); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php echo date('d/m/Y H:i', strtotime($row['date_fin'])); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded text-sm 
                                            <?php echo $row['status'] === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($row['status'] === 'available'): ?>
                                            <button onclick="deleteAvailability(<?php echo $row['id_disponibilite']; ?>)"
                                                    class="text-red-500 hover:text-red-600">
                                                Supprimer
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-300">Aucune disponibilité définie.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr('input[type="datetime-local"]', {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today"
        });

        function deleteAvailability(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette disponibilité ?')) {
                // Add AJAX call to delete availability
            }
        }
    </script>
</body>
</html>