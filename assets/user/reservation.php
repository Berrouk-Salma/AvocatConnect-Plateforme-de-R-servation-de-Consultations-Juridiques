<?php
require_once '../includes/config.php';

// Check if user is logged in and is a client
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header('Location: ../auth/login.php');
    exit();
}

$client_id = $_SESSION['user_id'];

// Handle new reservation submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lawyer_id'], $_POST['datetime'])) {
    $lawyer_id = intval($_POST['lawyer_id']);
    $datetime = $mysqli->real_escape_string($_POST['datetime']);
    
    // Check if the time slot is available
    $stmt = $mysqli->prepare("
        SELECT * FROM disponibilite 
        WHERE id_user = ? 
        AND ? BETWEEN date_debut AND date_fin 
        AND status = 'available'
    ");
    $stmt->bind_param("is", $lawyer_id, $datetime);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        // Create the reservation
        $stmt = $mysqli->prepare("
            INSERT INTO reservation (id_client, id_avocat, reservation_date) 
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iis", $client_id, $lawyer_id, $datetime);
        
        if ($stmt->execute()) {
            $success = "Réservation effectuée avec succès";
        } else {
            $error = "Erreur lors de la réservation";
        }
    } else {
        $error = "Ce créneau n'est plus disponible";
    }
}

// Get available lawyers
$stmt = $mysqli->prepare("
    SELECT u.*, i.specialties, i.biography, i.photo_url 
    FROM users u 
    LEFT JOIN information i ON u.id = i.user_id 
    WHERE u.role = 'avocat'
");
$stmt->execute();
$lawyers = $stmt->get_result();

// Get user's reservations
$stmt = $mysqli->prepare("
    SELECT r.*, 
           u.firstname as lawyer_firstname, 
           u.lastname as lawyer_lastname,
           u.email as lawyer_email
    FROM reservation r
    JOIN users u ON r.id_avocat = u.id
    WHERE r.id_client = ?
    ORDER BY r.reservation_date DESC
");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$reservations = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver une consultation</title>
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
        <h1 class="text-2xl font-semibold text-[#bb9a4f] mb-8">Réserver une consultation</h1>

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

        <!-- Available Lawyers -->
        <section class="mb-12">
            <h2 class="text-xl text-[#bb9a4f] mb-6">Choisir un avocat</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($lawyer = $lawyers->fetch_assoc()): ?>
                    <div class="bg-white/10 rounded-lg overflow-hidden">
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
                                    <h3 class="text-[#bb9a4f] font-semibold">
                                        <?php echo htmlspecialchars($lawyer['firstname'] . ' ' . $lawyer['lastname']); ?>
                                    </h3>
                                    <p class="text-gray-400 text-sm">
                                        <?php echo htmlspecialchars($lawyer['specialties'] ?? 'Avocat généraliste'); ?>
                                    </p>
                                </div>
                            </div>
                            <p class="text-gray-300 text-sm mb-4">
                                <?php echo htmlspecialchars(substr($lawyer['biography'] ?? 'Aucune biographie disponible.', 0, 150) . '...'); ?>
                            </p>
                            <button onclick="showReservationModal(<?php echo $lawyer['id']; ?>)"
                                    class="w-full bg-[#bb9a4f] text-[#001a10] py-2 rounded hover:bg-[#a68a45]">
                                Prendre rendez-vous
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <!-- My Reservations -->
        <section>
            <h2 class="text-xl text-[#bb9a4f] mb-6">Mes rendez-vous</h2>
            <div class="bg-white/10 rounded-lg overflow-hidden">
                <?php if ($reservations->num_rows > 0): ?>
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-[#bb9a4f]/10">
                                <th class="px-6 py-3 text-left text-[#bb9a4f]">Date</th>
                                <th class="px-6 py-3 text-left text-[#bb9a4f]">Avocat</th>
                                <th class="px-6 py-3 text-left text-[#bb9a4f]">Statut</th>
                                <th class="px-6 py-3 text-left text-[#bb9a4f]">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#bb9a4f]/10">
                            <?php while ($reservation = $reservations->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-6 py-4 text-white">
                                        <?php echo date('d/m/Y H:i', strtotime($reservation['reservation_date'])); ?>
                                    </td>
                                    <td class="px-6 py-4 text-white">
                                        <?php echo htmlspecialchars($reservation['lawyer_firstname'] . ' ' . $reservation['lawyer_lastname']); ?>
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
                                            <button onclick="cancelReservation(<?php echo $reservation['id_reservation']; ?>)"
                                                    class="text-red-500 hover:text-red-600">
                                                Annuler
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-gray-300 p-6">Vous n'avez pas encore de rendez-vous.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <!-- Reservation Modal -->
    <div id="reservationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-[#001a10] rounded-lg p-8 max-w-md w-full mx-4">
            <h3 class="text-xl text-[#bb9a4f] mb-4">Nouvelle réservation</h3>
            <form id="reservationForm" method="POST" class="space-y-4">
                <input type="hidden" name="lawyer_id" id="lawyer_id">
                <div>
                    <label class="block text-[#bb9a4f] mb-2">Date et heure</label>
                    <input type="datetime-local" name="datetime" required
                           class="w-full bg-white/5 border border-[#bb9a4f]/20 rounded px-3 py-2 text-white">
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="hideReservationModal()"
                            class="px-4 py-2 text-[#bb9a4f]">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-[#bb9a4f] text-[#001a10] rounded hover:bg-[#a68a45]">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr('input[type="datetime-local"]', {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            minuteIncrement: 30
        });

        function showReservationModal(lawyerId) {
            document.getElementById('lawyer_id').value = lawyerId;
            document.getElementById('reservationModal').classList.remove('hidden');
        }

        function hideReservationModal() {
            document.getElementById('reservationModal').classList.add('hidden');
        }

        function cancelReservation(reservationId) {
            if (confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')) {
                // Add AJAX call to cancel reservation
            }
        }
    </script>
</body>
</html>