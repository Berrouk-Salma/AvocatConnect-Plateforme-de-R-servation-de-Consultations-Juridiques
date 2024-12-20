<?php
session_start();
// require '../includes/config.php';
require '../includes/function.php';

// if (!isset($_SESSION['user_id'])){
//     header('Location: ./../../auth/login.php');
//     exit();
// }

$user_id = $_SESSION['user_id'];
$stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();


$stmt = $mysqli->prepare("
    SELECT u.*, i.specialties, i.biography, i.photo_url 
    FROM users u 
    LEFT JOIN information i ON u.id = i.user_id 
    WHERE u.role = 'avocat'
");
$stmt->execute();
$lawyers = $stmt->get_result();


$stmt = $mysqli->prepare("
    SELECT r.*, u.firstname as lawyer_firstname, u.lastname as lawyer_lastname 
    FROM reservation r 
    JOIN users u ON r.id_avocat = u.id 
    WHERE r.id_client = ? 
    ORDER BY r.reservation_date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$appointments = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace - Cabinet d'Avocats</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#001a10]">
    <?php include './../includes/header.php' ?>
    <!-- Navigation -->
    <nav class="bg-[#001a10] border-b border-[#bb9a4f]/20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <a href="../index.php" class="text-[#bb9a4f] text-2xl flex items-center">⚖ LawQuill</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="profile.php" class="text-[#bb9a4f] hover:text-[#a68a45]">Mon Profil</a>
                    <span class="text-[#bb9a4f] mr-4">
                        <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>
                    </span>
                    <a href="../logout.php" class="text-[#bb9a4f] hover:text-[#a68a45] border border-[#bb9a4f] px-4 py-2 rounded hover:bg-[#bb9a4f] hover:text-[#001a10]">
                        Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4">
        <!-- Mes Rendez-vous -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-[#bb9a4f] mb-6">Mes Rendez-vous</h2>
            <?php if ($appointments->num_rows > 0): ?>
                <div class="bg-white/10 rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-[#bb9a4f]/10">
                                <th class="px-6 py-3 text-left text-[#bb9a4f]">Date</th>
                                <th class="px-6 py-3 text-left text-[#bb9a4f]">Avocat</th>
                                <th class="px-6 py-3 text-left text-[#bb9a4f]">Statut</th>
                                <th class="px-6 py-3 text-left text-[#bb9a4f]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#bb9a4f]/10">
                            <?php while ($appointment = $appointments->fetch_assoc()): ?>
                                <tr class="hover:bg-white/5">
                                    <td class="px-6 py-4 text-white">
                                        <?php echo date('d/m/Y H:i', strtotime($appointment['reservation_date'])); ?>
                                    </td>
                                    <td class="px-6 py-4 text-white">
                                        <?php echo htmlspecialchars($appointment['lawyer_firstname'] . ' ' . $appointment['lawyer_lastname']); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-1 rounded text-sm 
                                            <?php 
                                            switch($appointment['status']) {
                                                case 'pending':
                                                    echo 'bg-yellow-100 text-yellow-800';
                                                    break;
                                                case 'approved':
                                                    echo 'bg-green-100 text-green-800';
                                                    break;
                                                case 'rejected':
                                                    echo 'bg-red-100 text-red-800';
                                                    break;
                                                case 'completed':
                                                    echo 'bg-blue-100 text-blue-800';
                                                    break;
                                            }
                                            ?>">
                                            <?php echo ucfirst($appointment['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($appointment['status'] === 'pending'): ?>
                                            <button onclick="cancelReservation(<?php echo $appointment['id_reservation']; ?>)"
                                                    class="text-red-500 hover:text-red-600">
                                                Annuler
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-300">Vous n'avez pas encore de rendez-vous.</p>
            <?php endif; ?>
        </section>

        <!-- Liste des Avocats -->
        <section>
            <h2 class="text-2xl font-semibold text-[#bb9a4f] mb-6">Nos Avocats</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php while ($lawyer = $lawyers->fetch_assoc()): ?>
                    <div class="bg-white/10 rounded-lg overflow-hidden border border-[#bb9a4f]/20">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <?php if ($lawyer['photo_url']): ?>
                                    <img src="../assets/uploads/<?php echo htmlspecialchars($lawyer['photo_url']); ?>" 
                                         alt="Photo" 
                                         class="w-16 h-16 rounded-full object-cover mr-4">
                                <?php else: ?>
                                    <div class="w-16 h-16 rounded-full bg-[#bb9a4f]/20 flex items-center justify-center mr-4">
                                        <span class="text-[#bb9a4f] text-2xl">
                                            <?php echo strtoupper(substr($lawyer['firstname'], 0, 1)); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <h3 class="text-[#bb9a4f] text-lg font-semibold">
                                        <?php echo htmlspecialchars($lawyer['firstname'] . ' ' . $lawyer['lastname']); ?>
                                    </h3>
                                    <p class="text-gray-400 text-sm">
                                        <?php echo htmlspecialchars($lawyer['specialties'] ?? 'Avocat généraliste'); ?>
                                    </p>
                                </div>
                            </div>
                            <p class="text-gray-300 text-sm mb-4">
                                <?php echo htmlspecialchars(substr($lawyer['biography'] ?? 'Aucune biographie disponible.', 0, 150)) . '...'; ?>
                            </p>
                            <button onclick="openReservationModal(<?php echo $lawyer['id']; ?>)" 
                                    class="w-full bg-[#bb9a4f] text-[#001a10] py-2 rounded hover:bg-[#a68a45] transition-colors">
                                Prendre rendez-vous
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </div>

    <!-- Script -->
    <script>
    function openReservationModal(lawyerId) {
        // TODO: Implémenter le modal de réservation
        alert('Modal de réservation à implémenter');
    }

    function cancelReservation(reservationId) {
        if (confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous?')) {
            // TODO: Implémenter l'annulation
            alert('Annulation à implémenter');
        }
    }
    </script>
</body>
</html>