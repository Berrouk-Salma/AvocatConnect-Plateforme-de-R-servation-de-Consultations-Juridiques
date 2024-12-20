<?php
require_once '../includes/config.php';
require_once '../includes/function.php';
    session_start();
// Vérification si l'utilisateur est connecté et est un avocat
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'avocat') {
//     header('Location: ./');
//     exit();
// }
echo $_SESSION['user_id'] ." " . $_SESSION['role'] ;

// Récupérer les informations de l'avocat
$avocat_id = $_SESSION['user_id'];
$stmt = $mysqli->prepare("
    SELECT u.*, i.* 
    FROM users u 
    LEFT JOIN information i ON u.id = i.user_id 
    WHERE u.id = ?
");
$stmt->bind_param("i", $avocat_id);
$stmt->execute();
$result = $stmt->get_result();
$avocat = $result->fetch_assoc();

// Récupérer les rendez-vous du jour
$today = date('Y-m-d');
$stmt = $mysqli->prepare("
    SELECT r.*, u.firstname, u.lastname, u.email, u.phone
    FROM reservation r
    JOIN users u ON r.id_client = u.id
    WHERE r.id_avocat = ? 
    AND DATE(r.reservation_date) = ?
    ORDER BY r.reservation_date ASC
");
$stmt->bind_param("is", $avocat_id, $today);
$stmt->execute();
$appointments_today = $stmt->get_result();

// Statistiques
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM reservation WHERE id_avocat = ? AND status = 'pending'");
$stmt->bind_param("i", $avocat_id);
$stmt->execute();
$pending_count = $stmt->get_result()->fetch_row()[0];

$stmt = $mysqli->prepare("SELECT COUNT(*) FROM reservation WHERE id_avocat = ? AND status = 'approved'");
$stmt->bind_param("i", $avocat_id);
$stmt->execute();
$approved_count = $stmt->get_result()->fetch_row()[0];

$stmt = $mysqli->prepare("
    SELECT COUNT(*) 
    FROM reservation 
    WHERE id_avocat = ? 
    AND status = 'approved' 
    AND DATE(reservation_date) = ?
");
$stmt->bind_param("is", $avocat_id, $today);
$stmt->execute();
$today_count = $stmt->get_result()->fetch_row()[0];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Avocat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body class="bg-[#001a10]">
    <!-- Navigation -->
    <nav class="bg-[#001a10] border-b border-[#bb9a4f]/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="../index.php" class="text-[#bb9a4f] text-2xl">⚖ LawQuill</a>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-[#bb9a4f] mr-4">
                        <?php echo htmlspecialchars($avocat['firstname'] . ' ' . $avocat['lastname']); ?>
                    </span>
                    <a href="../../auth/logout.php" class="text-[#bb9a4f] hover:text-[#a68a45]">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white/10 p-6 rounded-lg">
                <h3 class="text-[#bb9a4f] text-lg font-semibold mb-2">Demandes en attente</h3>
                <p class="text-4xl text-white"><?php echo $pending_count; ?></p>
            </div>
            <div class="bg-white/10 p-6 rounded-lg">
                <h3 class="text-[#bb9a4f] text-lg font-semibold mb-2">Rendez-vous approuvés</h3>
                <p class="text-4xl text-white"><?php echo $approved_count; ?></p>
            </div>
            <div class="bg-white/10 p-6 rounded-lg">
                <h3 class="text-[#bb9a4f] text-lg font-semibold mb-2">Rendez-vous aujourd'hui</h3>
                <p class="text-4xl text-white"><?php echo $today_count; ?></p>
            </div>
            <div class="bg-white/10 p-6 rounded-lg">
                <h3 class="text-[#bb9a4f] text-lg font-semibold mb-2">Actions rapides</h3>
                <div class="flex gap-2">
                    <a href="dispo.php" class="text-[#bb9a4f] hover:text-[#a68a45] text-sm">Gérer disponibilités</a>
                    <span class="text-[#bb9a4f]">|</span>
                    <a href="profile.php" class="text-[#bb9a4f] hover:text-[#a68a45] text-sm">Voir profil</a>
                </div>
            </div>
        </div>

        <!-- Rendez-vous du jour -->
        <div class="bg-white/10 rounded-lg p-6 mb-8">
            <h2 class="text-[#bb9a4f] text-xl font-semibold mb-4">Rendez-vous du jour</h2>
            <?php if ($appointments_today->num_rows > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="text-[#bb9a4f] text-left">
                                <th class="py-3 px-4">Heure</th>
                                <th class="py-3 px-4">Client</th>
                                <th class="py-3 px-4">Contact</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($appointment = $appointments_today->fetch_assoc()): ?>
                                <tr class="border-t border-[#bb9a4f]/20">
                                    <td class="py-3 px-4 text-white">
                                        <?php echo date('H:i', strtotime($appointment['reservation_date'])); ?>
                                    </td>
                                    <td class="py-3 px-4 text-white">
                                        <?php echo htmlspecialchars($appointment['firstname'] . ' ' . $appointment['lastname']); ?>
                                    </td>
                                    <td class="py-3 px-4">
                                        <a href="mailto:<?php echo $appointment['email']; ?>" class="text-[#bb9a4f] hover:text-[#a68a45]">
                                            <?php echo htmlspecialchars($appointment['email']); ?>
                                        </a>
                                        <br>
                                        <a href="tel:<?php echo $appointment['phone']; ?>" class="text-[#bb9a4f] hover:text-[#a68a45]">
                                            <?php echo htmlspecialchars($appointment['phone']); ?>
                                        </a>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 rounded text-sm 
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
                                    <td class="py-3 px-4">
                                        <?php if ($appointment['status'] === 'pending'): ?>
                                            <button 
                                                onclick="updateStatus(<?php echo $appointment['id_reservation']; ?>, 'approved')"
                                                class="text-green-500 hover:text-green-600 mr-2">
                                                Approuver
                                            </button>
                                            <button 
                                                onclick="updateStatus(<?php echo $appointment['id_reservation']; ?>, 'rejected')"
                                                class="text-red-500 hover:text-red-600">
                                                Refuser
                                            </button>
                                        <?php elseif ($appointment['status'] === 'approved'): ?>
                                            <button 
                                                onclick="updateStatus(<?php echo $appointment['id_reservation']; ?>, 'completed')"
                                                class="text-blue-500 hover:text-blue-600">
                                                Marquer comme terminé
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-300">Aucun rendez-vous aujourd'hui</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function updateStatus(reservationId, newStatus) {
        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: `Voulez-vous ${newStatus === 'approved' ? 'approuver' : newStatus === 'rejected' ? 'refuser' : 'marquer comme terminé'} ce rendez-vous?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#bb9a4f',
            cancelButtonColor: '#718096',
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Ici, ajoutez la logique pour mettre à jour le statut via AJAX
                fetch(`update_status.php?id=${reservationId}&status=${newStatus}`, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'Mise à jour!',
                            'Le statut a été mis à jour avec succès.',
                            'success'
                        ).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Erreur!',
                            'Une erreur est survenue.',
                            'error'
                        );
                    }
                });
            }
        });
    }
    </script>
</body>
</html>