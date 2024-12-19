<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Cabinet d'Avocats</title>
</head>
<body class="bg-[#001a10]">
    <!-- En-tête -->
    <header class="p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <span class="text-yellow-500 text-2xl">⚖ LawQuill</span>
            </div>
            <button class="text-yellow-500 border border-yellow-500 px-4 py-2">
                (+123) 567-89
            </button>
        </div>
    </header>

    <!-- Section Hero -->
    <section class="relative min-h-screen">
        <div class="absolute inset-0">
            <img src="./assets/images/bg .jpg" alt="Palais de Justice" class="w-full h-full object-cover opacity-30"/>
        </div>
        <div class="relative max-w-4xl mx-auto text-center pt-32 px-4">
            <p class="text-white mb-4">Bienvenue au Cabinet d'Avocats</p>
            <h1 class="text-yellow-500 text-5xl font-bold mb-4">
                Fiable. Experts Juridiques.<br/>
                Engagement.
            </h1>
            <p class="text-gray-300 mb-8">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lobortis vitae, consectetur risus. 
                Nam venenatis id ex eu congue.
            </p>
            <button class="border-2 border-yellow-500 text-yellow-500 px-8 py-3 hover:bg-yellow-500 hover:text-black transition-colors">
                ÉVALUATION GRATUITE
            </button>
        </div>
    </section>

    <!-- Section À Propos -->
    <section class="py-20 px-4">
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
            <div class="border border-yellow-500/30 p-4">
                <img src="./assets/images/lawyer.jpg" alt="Statue de la Justice" class="w-full"/>
            </div>
            <div>
                
                <h2 class="text-yellow-500 text-4xl mb-6   ">À Propos du Cabinet</h2>
                <p class="text-gray-300 mb-6">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam hendrerit nisi sed sollicitudin pellentesque. 
                    Nunc posuere purus rhoncus pulvinar aliquam.
                </p>
                <p class="text-gray-300 mb-6">
                    Aliquam elementum magna eros, ac posuere elvit tempus et. Suspendisse vel tempus odio, in interdutm nisi. 
                    Suspendisse eu ornare nisl. Nullam convallis augue justo, at imperdiet metus scelerisque quis.
                </p>
                <div class="text-yellow-500 italic">Nom de l'Avocat</div>
            </div>
        </div>
    </section>

    <!-- Domaines de Pratique -->
    <section class="py-24 bg-[#001a10]">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-[#bb9a4f] text-5xl font-light text-center mb-16">Practice Areas</h2>
        
        <!-- Practice Areas Grid -->
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Sports Card -->
            <div class="relative border border-[#bb9a4f]/20 p-12 group hover:border-[#bb9a4f] transition-all duration-500 cursor-pointer overflow-hidden">
                <div class="absolute -right-4 -top-4 text-[#002a1a] text-[120px] font-bold opacity-20 select-none transition-transform duration-500 group-hover:scale-110 group-hover:opacity-30">
                    01
                </div>
                <div class="relative z-10 transform transition-transform duration-500 group-hover:-translate-y-2">
                    <h3 class="text-[#bb9a4f] text-3xl font-light mb-6">Sports — Offline and Online</h3>
                    <div class="flex gap-2 mb-6 opacity-100 transition-opacity duration-500">
                        <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
                        <span class="w-8 h-0.5 bg-[#bb9a4f]"></span>
                        <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
                        <span class="w-8 h-0.5 bg-[#bb9a4f]"></span>
                        <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
                    </div>
                    <p class="text-gray-400 leading-relaxed transform transition-all duration-500 group-hover:text-gray-300">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc malesuada orci, sit amet.
                    </p>
                </div>
                <!-- Hover Background Effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-[#002a1a] to-transparent opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>
            </div>

            <!-- Corporate Commercial Card -->
            <div class="relative border border-[#bb9a4f]/20 p-12 group hover:border-[#bb9a4f] transition-all duration-500 cursor-pointer overflow-hidden">
                <div class="absolute -right-4 -top-4 text-[#002a1a] text-[120px] font-bold opacity-20 select-none transition-transform duration-500 group-hover:scale-110 group-hover:opacity-30">
                    02
                </div>
                <div class="relative z-10 transform transition-transform duration-500 group-hover:-translate-y-2">
                    <h3 class="text-[#bb9a4f] text-3xl font-light mb-6">Corporate Commercial</h3>
                    <div class="flex gap-2 mb-6 opacity-100 transition-opacity duration-500">
                        <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
                        <span class="w-8 h-0.5 bg-[#bb9a4f]"></span>
                        <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
                        <span class="w-8 h-0.5 bg-[#bb9a4f]"></span>
                        <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
                    </div>
                    <p class="text-gray-400 leading-relaxed transform transition-all duration-500 group-hover:text-gray-300">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc malesuada orci, sit amet.
                    </p>
                </div>
                <div class="absolute inset-0 bg-gradient-to-r from-[#002a1a] to-transparent opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>
            </div>

            <!-- Add similar structure for other cards -->
            <!-- Mergers Card -->
            <div class="relative border border-[#bb9a4f]/20 p-12 group hover:border-[#bb9a4f] transition-all duration-500 cursor-pointer overflow-hidden">
                <!-- Same structure as above -->
                <div class="absolute -right-4 -top-4 text-[#002a1a] text-[120px] font-bold opacity-20 select-none transition-transform duration-500 group-hover:scale-110 group-hover:opacity-30">
                    03
                </div>
                <div class="relative z-10 transform transition-transform duration-500 group-hover:-translate-y-2">
                    <h3 class="text-[#bb9a4f] text-3xl font-light mb-6">Mergers and Acquisitions</h3>
                    <div class="flex gap-2 mb-6 opacity-100 transition-opacity duration-500">
                        <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
                        <span class="w-8 h-0.5 bg-[#bb9a4f]"></span>
                        <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
                        <span class="w-8 h-0.5 bg-[#bb9a4f]"></span>
                        <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
                    </div>
                    <p class="text-gray-400 leading-relaxed transform transition-all duration-500 group-hover:text-gray-300">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc malesuada orci, sit amet.
                    </p>
                </div>
                <div class="absolute inset-0 bg-gradient-to-r from-[#002a1a] to-transparent opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>
            </div>

            <!-- Repeat the same structure for the remaining cards (4, 5, 6) -->
            <!-- Just update the number and title for each -->
        </div>
    </div>
</section>
<!-- yellow -->
<section class="bg-[#f3e8d2] py-24">
    <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-16 items-center">
        <div>
            <h2 class="text-[#121921] text-5xl font-light mb-6">Why Choose Us</h2>
            <!-- Decorative Line -->
            <div class="flex gap-2 mb-8">
                <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
                <span class="w-8 h-0.5 bg-[#bb9a4f]"></span>
                <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
                <span class="w-8 h-0.5 bg-[#bb9a4f]"></span>
                <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
            </div>
            
            <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc 
                quis malesuada orci, sit amet sagittis ligula. Aliquam pulvinar 
                nunc a arcu eleifend pellentesque. Donec mollis tempus urna non 
                condimentum. Nunc sed erat ut ante laoreet fermentum.
            </p>

            <p class="text-gray-600 text-lg mb-12 leading-relaxed">
                Aliquam rhoncus velit purus, id vestibulum nulla ornare a. 
                Maecenas odio erat, sodales sed mauris id, euismod tempor ante. 
                Donec non luctus ipsum. Maecenas pulvinar turpis ac turpis 
                vehicula ultrices at sed dui.
            </p>

            <button class="bg-[#bb9a4f] text-[#121921] px-12 py-4 text-lg font-semibold hover:bg-[#a68a45] transition-colors duration-300">
                FREE EVALUATION
            </button>
        </div>
        <div>
            <img src="./assets/images/bw.jpg" alt="Professional Lawyer Speaking" 
                 class="w-full rounded-lg shadow-xl"/>
        </div>
    </div>
</section>

<!-- Section Statistiques -->
<section class="relative py-16">
    <div class="absolute inset-0">
        <img src="./assets/images/books.jpg" alt="Bibliothèque" class="w-full h-full object-cover opacity-30"/>
    </div>
    <div class="relative max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-4 gap-8 text-center">
            <div>
                <h3 class="text-white text-4xl font-bold mb-2">2100</h3>
                <p class="text-gray-300">AFFAIRES RÉUSSIES</p>
            </div>
            <div>
                <h3 class="text-yellow-500 text-4xl font-bold mb-2">3200+</h3>
                <p class="text-gray-300">CLIENTS SATISFAITS</p>
            </div>
            <div>
                <h3 class="text-yellow-500 text-4xl font-bold mb-2">600</h3>
                <p class="text-gray-300">PROCÈS GAGNÉS</p>
            </div>
            <div>
                <h3 class="text-yellow-500 text-4xl font-bold mb-2">72+</h3>
                <p class="text-gray-300">AVOCATS</p>
            </div>
        </div>
    </div>
</section>

<!-- Domaines de Pratique avec Images -->
<section class="py-16 bg-[#110801]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-6">
            <!-- Lydia C. Ward -->
            <div class="relative group">
                <h3 class="text-[#bb9a4f] text-xl mb-4">Lydia C. Ward</h3>
                <div class="relative overflow-hidden">
                    <img src="./assets/images/women 2.jpg" alt="Lydia C. Ward" class="w-full h-[400px] object-cover"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-100">
                        <div class="absolute bottom-0 left-0 right-0 p-6 text-center">
                            <h3 class="text-[#bb9a4f] text-2xl font-light mb-2">Lydia C. Ward</h3>
                            <p class="text-[#bb9a4f] text-sm tracking-wider">PARTNER</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dany J. Barton -->
            <div class="relative group">
                <h3 class="text-[#bb9a4f] text-xl mb-4">Dany J. Barton</h3>
                <div class="relative overflow-hidden">
                    <img src="./assets/images/man2.jpg" alt="Dany J. Barton" class="w-full h-[400px] object-cover"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-100">
                        <div class="absolute bottom-0 left-0 right-0 p-6 text-center">
                            <h3 class="text-[#bb9a4f] text-2xl font-light mb-2">Dany J. Barton</h3>
                            <p class="text-[#bb9a4f] text-sm tracking-wider">PARTNER</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Patrice J. Ross -->
            <div class="relative group">
                <h3 class="text-[#bb9a4f] text-xl mb-4">Patrice J. Ross</h3>
                <div class="relative overflow-hidden">
                    <img src="./assets/images/women1.jpg" alt="Patrice J. Ross" class="w-full h-[400px] object-cover"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-100">
                        <div class="absolute bottom-0 left-0 right-0 p-6 text-center">
                            <h3 class="text-[#bb9a4f] text-2xl font-light mb-2">Patrice J. Ross</h3>
                            <p class="text-[#bb9a4f] text-sm tracking-wider">PARTNER</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Brian S. Hower -->
            <div class="relative group">
                <h3 class="text-[#bb9a4f] text-xl mb-4">Brian S. Hower</h3>
                <div class="relative overflow-hidden">
                    <img src="./assets/images/man1.jpg" alt="Brian S. Hower" class="w-full h-[400px] object-cover"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-100">
                        <div class="absolute bottom-0 left-0 right-0 p-6 text-center">
                            <h3 class="text-[#bb9a4f] text-2xl font-light mb-2">Brian S. Hower</h3>
                            <p class="text-[#bb9a4f] text-sm tracking-wider">PARTNER</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Témoignages -->
<section class="relative py-32 overflow-hidden">
    <!-- Background with gradient -->
    <div class="absolute inset-0">
        <img src="./assets/images/books.jpg" alt="Background" class="w-full h-full object-cover opacity-20"/>
    </div>

    <!-- Main Content -->
    <div class="relative max-w-7xl mx-auto px-4">
        <!-- Title with decorative elements -->
        <h2 class="text-[#bb9a4f] text-5xl font-light text-center mb-16">What Our Clients Say</h2>
        <div class="flex justify-center mb-4">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
                <span class="w-12 h-0.5 bg-[#bb9a4f]"></span>
                <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
                <span class="w-12 h-0.5 bg-[#bb9a4f]"></span>
                <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
            </div>
        </div>

        <!-- Testimonial Container -->
        <div class="relative mt-20">
            <!-- Curved Dark Background -->
            <div class="absolute inset-0 bg-[#110801] rounded-[100px] transform -skew-y-3"></div>
            
            <!--lawyees -->
            <div class="relative grid md:grid-cols-2 gap-12 items-center p-16">
                <div class="max-w-md mx-auto">
                    <img src="./assets/images/lawyers.jpg" alt="John Doe" 
                         class="w-full rounded-lg shadow-xl"/>
                </div>
                <div class="max-w-xl">
                    <blockquote class="text-gray-300 text-lg leading-relaxed mb-8">
                        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                        Nunc quis malesu ada orci, sit amet sagittis ligula. Aliquam 
                        pulvinar nunc a arcu eleifend pelle ntesque. Donec mollis 
                        tempus urna non condimentum. Nunc sed erat ut ante laoreet 
                        fermentum. Nunc venenatis, nunc eget maximus aliquam, 
                        tellus nisi condimentum dui Maecenas pulvinar turpis ac 
                        turpis vehicula ultrices at sed dui. Sed non ullamcorper"
                    </blockquote>
                    <div class="flex items-center gap-4">
                        <span class="text-[#bb9a4f] text-2xl font-light">— John doe</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Contact -->
<section class="py-24 bg-[#001a10]">
    <!-- Contact Section -->
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-[#bb9a4f] text-5xl font-light text-center mb-6">Do You Need Legal Help?</h2>
        
        <!-- Decorative Line -->
        <div class="flex justify-center gap-2 mb-8">
            <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
            <span class="w-8 h-0.5 bg-[#bb9a4f]"></span>
            <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
            <span class="w-8 h-0.5 bg-[#bb9a4f]"></span>
            <span class="w-2 h-2 bg-[#bb9a4f] rounded-full"></span>
        </div>

        <!-- Description Text -->
        <p class="text-gray-400 text-center max-w-3xl mx-auto mb-20 text-lg">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quis malesuada orci, sit amet sagittis ligula. 
            Aliquam pulvinar nunc a arcu eleifend pellentesque.
        </p>

        <!-- Contact  -->
        <div class="grid md:grid-cols-3 gap-16 text-center mt-8">
            <div class="group cursor-pointer">
                <h3 class="text-[#bb9a4f] text-3xl font-light mb-6 transition-transform duration-300 group-hover:-translate-y-1">
                    Email Us At
                </h3>
                <p class="text-gray-400 transition-colors duration-300 group-hover:text-gray-300">
                    salmaberrouk4@gmail.com
                </p>
            </div>

            <div class="group cursor-pointer">
                <h3 class="text-[#bb9a4f] text-3xl font-light mb-6 transition-transform duration-300 group-hover:-translate-y-1">
                    Give Us A Call
                </h3>
                <p class="text-gray-400 transition-colors duration-300 group-hover:text-gray-300">
                    +212-649236388
                </p>
            </div>

            <div class="group cursor-pointer">
                <h3 class="text-[#bb9a4f] text-3xl font-light mb-6 transition-transform duration-300 group-hover:-translate-y-1">
                    Visit Us At
                </h3>
                <p class="text-gray-400 transition-colors duration-300 group-hover:text-gray-300">
                https://github.com/Berrouk-Salma
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="py-8 border-t border-[#bb9a4f]/20 bg-[#001a10]">
    <div class="max-w-6xl mx-auto px-4 text-center">
        <p class="text-gray-400">Lawyers © 2024. All Rights Reserved
       <br> Created by Salma Berrouk</p>
    </div>
</footer>
</body>
</html>