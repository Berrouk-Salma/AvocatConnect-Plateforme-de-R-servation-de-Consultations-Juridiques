<?php
require_once '../includes/config.php';

// Check if user is logged in and is a lawyer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'avocat') {
    header('Location: ../auth/login.php');
    exit();
}

$lawyer_id = $_SESSION['user_id'];

// Handle status updates via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'], $_POST['status'])) {
    $reservation_id = intval($_POST['reservation_id']);
    $status = $mysqli->real_escape_string($_POST['status']);
    
    $stmt = $mysqli->prepare("UPDATE reservation SET status = ? WHERE id_reservation = ? AND id_avocat = ?");
    $stmt->bind_param("sii", $status, $reservation_id, $lawyer_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $mysqli->error]);
    }
    exit();
}

// Get all reservations for this lawyer
$stmt = $mysqli->prepare("
    SELECT r.*, 
           u.firstname as client_firstname, 
           u.lastname as client_lastname,
           u.email as client_email,
           u.phone as client_phone
    FROM reservation r
    JOIN users u ON r.id_client = u.id
    WHERE r.id_avocat = ?
    ORDER BY r.reservation_date DESC
");
$stmt->bind_param("i", $lawyer_id);
$stmt->execute();
$reservations = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les réservations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
        <h1 class="text-2xl font-semibold text-[#bb9a4f] mb-8">Gérer les réservations</h1>

        <!-- Filters -->
        <div class="mb-8">
            <div class="flex gap-4">
                <button onclick="filterReservations('all')" 
                        class="text-[#bb9a4f] px-4 py-2 rounded border border-[#bb9a4f] hover:bg-[#bb9a4f] hover:text-[#001a10]">
                    Toutes
                </button>
                <button onclick="filterReservations('pending')" 
                        class="text-yellow-500 px-4 py-2 rounded border border-yellow-500 hover:bg-yellow-500 hover:text-[#001a10]">
                    En attente
                </button>
                <button onclick="filterReservations('approved')" 
                        class="text-green-500 px-4 py-2 rounded border border-green-500 hover:bg-green-500 hover:text-[#001a10]">
                    Approuvées
                </button>
                <button onclick="filterReservations('completed')" 
                        class="text-blue-500 px-4 py-2 rounded border border-blue-500 hover:bg-blue-500 hover:text-[#001a10]">
                    Terminées
                </button>
            </div>
        </div>

        <!-- Reservations List -->
        <div class="bg-white/10 rounded-lg overflow-hidden">
            <?php if ($reservations->num_rows > 0): ?>
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-[#bb9a4f]/10">
                            <th class="px-6 py-3 text-left text-[#bb9a4f]">Date</th>
                            <th class="px-6 py-3 text-left text-[#bb9a4f]">Client</th>
                            <th class="px-6 py-3 text-left text-[#bb9a4f]">Contact</th>
                            <th class="px-6 py-3 text-left text-[#bb9a4f]">Statut</th>
                            <th class="px-6 py-3 text-left text-[#bb9a4f]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#bb9a4f]/10">
                        <?php while ($reservation = $reservations->fetch_assoc()): ?>
                            <tr class="reservation-row" data-status="<?php echo $reservation['status']; ?>">
                                <td class="px-6 py-4 text-white">
                                    <?php echo date('d/m/Y H:i', strtotime($reservation['reservation_date'])); ?>
                                </td>
                                <td class="px-6 py-4 text-white">
                                    <?php echo htmlspecialchars($reservation['client_firstname'] . ' ' . $reservation['client_lastname']); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="mailto:<?php echo $reservation['client_email']; ?>" 
                                       class="text-[#bb9a4f] hover:text-[#a68a45] block">
                                        <?php echo htmlspecialchars($reservation['client_email']); ?>
                                    </a>
                                    <a href="tel:<?php echo $reservation['client_phone']; ?>" 
                                       class="text-[#bb9a4f] hover:text-[#a68a45]">
                                        <?php echo htmlspecialchars($reservation['client_phone']); ?>
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-sm 
                                        <?php 
                                        switch($reservation['status']) {
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
                                        <?php echo ucfirst($reservation['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($reservation['status'] === 'pending'): ?>
                                        <button onclick="updateStatus(<?php echo $reservation['id_reservation']; ?>, 'approved')"
                                                class="text-green-500 hover:text-green-600 mr-2">
                                            Approuver
                                        </button>
                                        <button onclick="updateStatus(<?php echo $reservation['id_reservation']; ?>, 'rejected')"
                                                class="text-red-500 hover:text-red-600">
                                            Refuser
                                        </button>
                                    <?php elseif ($reservation['status'] === 'approved'): ?>
                                        <button onclick="updateStatus(<?php echo $reservation['id_reservation']; ?>, 'completed')"
                                                class="text-blue-500 hover:text-blue-600">
                                            Terminer
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-gray-300 p-6">Aucune réservation trouvée.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function filterReservations(status) {
        const rows = document.querySelectorAll('.reservation-row');
        rows.forEach(row => {
            if (status === 'all' || row.dataset.status === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function updateStatus(reservationId, newStatus) {
        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: `Voulez-vous ${newStatus === 'approved' ? 'approuver' : newStatus === 'rejected' ? 'refuser' : 'terminer'} ce rendez-vous?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#bb9a4f',
            cancelButtonColor: '#718096',
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('reservation_id', reservationId);
                formData.append('status', newStatus);

                fetch('reservation.php', {
                    method: 'POST',
                    body: formData
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