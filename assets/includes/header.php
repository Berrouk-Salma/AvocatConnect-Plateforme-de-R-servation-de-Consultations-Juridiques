<nav class="p-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <span class="text-yellow-500 text-2xl">⚖ LawQuill</span>
        <div class="flex gap-4">
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['role'] === 'avocat'): ?>
                    <a href="avocat/dashboard.php" class="text-[#bb9a4f] hover:text-[#a68a45]">Dashboard</a>
                <?php elseif($_SESSION['role'] === 'client'): ?>
                    <a href="client/dashboard.php" class="text-[#bb9a4f] hover:text-[#a68a45]">Dashboard</a>
                <?php endif; ?>
                <a href="logout.php" class="text-[#bb9a4f] border border-[#bb9a4f] px-4 py-2 hover:bg-[#bb9a4f] hover:text-[#001a10]">
                    Déconnexion
                </a>
            <?php else: ?>
                <a href="login.php" class="text-[#bb9a4f] hover:text-[#a68a45]">Se connecter</a>
                <a href="register.php" class="text-[#bb9a4f] border border-[#bb9a4f] px-4 py-2 hover:bg-[#bb9a4f] hover:text-[#001a10]">
                    S'inscrire
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>