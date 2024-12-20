<?php
require '../includes/config.php';

// if (!lognin() || !isAvocat()) {
//     header('Location: ./auth/login.php');
//     exit();
// }


// Récupérer les informations de l'avocat
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$avocat = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Récupérer les réservations en attente
$query = "SELECT r.*, u.firstname, u.lastname, u.email, u.phone 
          FROM reservation r 
          JOIN users u ON r.id_client = u.id 
          WHERE r.id_avocat = ? AND r.status = 'pending' 
          ORDER BY r.reservation_date ASC";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$pending_reservations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Récupérer les réservations d'aujourd'hui
$query = "SELECT r.*, u.firstname, u.lastname 
          FROM reservation r 
          JOIN users u ON r.id_client = u.id 
          WHERE r.id_avocat = ? 
          AND r.status = 'approved' 
          AND DATE(r.reservation_date) = CURDATE()";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$today_reservations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord - Avocat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-[#001a10] text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <span class="text-[#bb9a4f] text-2xl">⚖ LawQuill</span>
            <div class="flex items-center space-x-4">
                <a href="dashboard.php" class="text-[#bb9a4f]">Tableau de Bord</a>
                <a href="profile.php" class="hover:text-[#bb9a4f]">Mon Profil</a>
                <a href="disponibilites.php" class="hover:text-[#bb9a4f]">Disponibilités</a>
                <a href="reservations.php" class="hover:text-[#bb9a4f]">Réservations</a>
                <a href="../auth/logout.php" class="hover:text-[#bb9a4f]">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- En-tête du tableau de bord -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold mb-4">Bienvenue, <?php echo htmlspecialchars($avocat['firstname'] . ' ' . $avocat['lastname']); ?></h1>
            <p class="text-gray-600">Voici un aperçu de votre journée</p>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600">Rendez-vous du jour</h2>
                        <p class="text-2xl font-bold"><?php echo count($today_reservations); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600">En attente</h2>
                        <p class="text-2xl font-bold"><?php echo count($pending_reservations); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-500">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-600">Total Clients</h2>
                        <p class="text-2xl font-bold">
                            <?php 
                            // Requête pour compter le nombre total de clients uniques
                            $query = "SELECT COUNT(DISTINCT id_client) as total FROM reservation WHERE id_avocat = ?";
                            $stmt = $mysqli->prepare($query);
                            $stmt->bind_param("i", $_SESSION['user_id']);
                            $stmt->execute();
                            $result = $stmt->get_result()->fetch_assoc();
                            echo $result['total'];
                            $stmt->close();
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rendez-vous d'aujourd'hui -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Rendez-vous d'aujourd'hui</h2>
            <?php if (empty($today_reservations)): ?>
                <p class="text-gray-600">Aucun rendez-vous prévu pour aujourd'hui</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($today_reservations as $reservation): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo date('H:i', strtotime($reservation['reservation_date'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($reservation['firstname'] . ' ' . $reservation['lastname']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Confirmé
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Voir détails</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Demandes en attente -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Demandes en attente</h2>
            <?php if (empty($pending_reservations)): ?>
                <p class="text-gray-600">Aucune demande en attente</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($pending_reservations as $reservation): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo date('d/m/Y H:i', strtotime($reservation['reservation_date'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($reservation['firstname'] . ' ' . $reservation['lastname']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo htmlspecialchars($reservation['email']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($reservation['phone']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <form method="POST" action="traiter_reservation.php" class="inline-block">
                                            <input type="hidden" name="id_reservation" value="<?php echo $reservation['id_reservation']; ?>">
                                            <button type="submit" name="action" value="approve" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 mr-2">
                                                Accepter
                                            </button>
                                            <button type="submit" name="action" value="reject" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                                Refuser
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#001a10] text-white mt-8 py-4">
        <div class="container mx-auto px-4 text-center">
            <p class="text-sm text-gray-400">© 2024 LawQuill - Tous droits réservés</p>
        </div>
    </footer>
</body>
</html>