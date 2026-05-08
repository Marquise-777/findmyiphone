<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iPhone 12 Pro | It's a leap year.</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            transition: background-color 0.3s, color 0.2s;
        }
        .hero-gradient-light {
            background: linear-gradient(145deg, #f5f5f7 0%, #ffffff 100%);
        }
        .dark .hero-gradient-light {
            background: linear-gradient(145deg, #0a0a1f 0%, #0e0e10 100%);
        }
        .hero-gradient-dark {
            background: linear-gradient(145deg, #222224 0%, #0e0e10 100%);
        }
        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 35px -12px rgba(0,0,0,0.2);
        }
        .dark .product-card {
            background-color: #1f1f24;
            border-color: #2c2c30;
        }
        .dark .hero-gradient {
            background: linear-gradient(145deg, #1c1c1e 0%, #0e0e10 100%);
        }
    </style>
</head>
<body class="bg-white text-gray-900 dark:bg-gray-950 dark:text-gray-100 transition-colors">

    <!-- Navigation Bar (same as before, includes dark mode toggle) -->
    <nav class="sticky top-0 z-50 bg-white/80 dark:bg-gray-950/90 backdrop-blur-md border-b border-gray-200/60 dark:border-gray-800/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 md:h-16">
                <div class="flex items-center space-x-6">
                    <div class="text-xl font-semibold tracking-tight"><img src="{{ asset('logo.jpeg') }}" alt="" srcset="" style="width: 30px; border-radius: 50%;"></div>
                    <div class="hidden md:flex space-x-6 text-sm font-medium text-gray-700 dark:text-gray-300">
                        <a href="#" class="hover:text-black dark:hover:text-white transition">Mac</a>
                        <a href="#" class="hover:text-black dark:hover:text-white transition">iPad</a>
                        <a href="#" class="hover:text-black dark:hover:text-white transition">iPhone</a>
                        <a href="#" class="hover:text-black dark:hover:text-white transition">Watch</a>
                        <a href="#" class="hover:text-black dark:hover:text-white transition">TV</a>
                        <a href="#" class="hover:text-black dark:hover:text-white transition">Music</a>
                        <a href="#" class="hover:text-black dark:hover:text-white transition">Support</a>
                    </div>
                </div>
                <div class="flex items-center space-x-5">
                    <a href="#" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white hidden sm:inline">Overview</a>
                    <a href="#" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white hidden sm:inline">Tech Specs</a>
                    <a href="#" class="bg-gray-900 dark:bg-white dark:text-gray-900 text-white px-4 py-1.5 rounded-full text-sm font-semibold hover:bg-gray-800 dark:hover:bg-gray-200 transition">Shop</a>
                    <button id="theme-toggle" class="ml-2 p-1.5 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Carousel -->
    <div class="hero-gradient-light dark:hero-gradient-dark pt-8 pb-16 md:pt-16 md:pb-24 transition-colors">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="flex-1 text-center lg:text-left">
                    <div class="inline-block bg-gray-200/70 dark:bg-gray-800/70 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold tracking-wide text-gray-700 dark:text-gray-300 mb-4">New</div>
                    <h1 class="text-5xl md:text-6xl font-bold tracking-tight text-gray-900 dark:text-white mb-3">iPhone 17 Pro Max</h1>
                    <p class="text-2xl md:text-3xl text-gray-600 dark:text-gray-400 italic mb-4">It's a leap year.</p>
                    <div class="space-y-2 mb-6">
                        <div class="text-2xl font-semibold text-gray-800 dark:text-gray-200">From ₹3,999/mo. or ₹89,999 before trade‑in</div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">0% APR financing available. Terms apply.</p>
                    </div>
                    <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                        <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full text-sm font-semibold shadow-md transition">Learn more</a>
                        <a href="#" class="border border-gray-400 dark:border-gray-600 text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 px-6 py-3 rounded-full text-sm font-semibold transition">Buy</a>
                    </div>
                    <div class="mt-6 text-xs text-gray-400 dark:text-gray-500">* Price includes ₹30 connectivity discount. Requires activation.</div>
                </div>

                <!-- Carousel -->
                <div class="flex-1 relative">
                    <div id="hero-carousel" class="relative w-full max-w-md mx-auto lg:mx-0 overflow-hidden rounded-2xl shadow-2xl">
                        <div class="relative h-80 md:h-96">
                            <div id="carousel-slide-0" class="carousel-slide absolute inset-0 transition-opacity duration-500 opacity-100">
                                <img src="https://images.unsplash.com/photo-1759588071814-f960ed8f7ee8?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="w-full h-full object-cover" alt="iPhone 17 Pro Max">
                            </div>
                            <div id="carousel-slide-1" class="carousel-slide absolute inset-0 transition-opacity duration-500 opacity-0">
                                <img src="https://images.unsplash.com/photo-1759588071814-f960ed8f7ee8?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="w-full h-full object-cover" alt="iPhone 17 Pro Max side">
                            </div>
                            <div id="carousel-slide-2" class="carousel-slide absolute inset-0 transition-opacity duration-500 opacity-0">
                                <img src="https://images.unsplash.com/photo-1759588071814-f960ed8f7ee8?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="w-full h-full object-cover" alt="iPhone 17 Pro Max camera">
                            </div>
                        </div>
                        <button id="carousel-prev" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 text-white p-2 rounded-full hover:bg-black/70 transition">❮</button>
                        <button id="carousel-next" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 text-white p-2 rounded-full hover:bg-black/70 transition">❯</button>
                        <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-2">
                            <button data-index="0" class="carousel-dot w-2 h-2 rounded-full bg-white/70"></button>
                            <button data-index="1" class="carousel-dot w-2 h-2 rounded-full bg-white/30"></button>
                            <button data-index="2" class="carousel-dot w-2 h-2 rounded-full bg-white/30"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rest of the page (Products, Tech Specs, Support, Shop, Footer) unchanged -->
    <div class="py-16 bg-gray-50/50 dark:bg-gray-900/30 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Explore the lineup.</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Find the perfect fit for your life.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                <!-- Mac -->
                <div class="product-card bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition">
                    <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=300&auto=format" alt="MacBook" class="w-full h-40 object-cover">
                    <div class="p-4 text-center">
                        <h3 class="font-semibold text-lg dark:text-white">Mac</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Supercharged by M3</p>
                    </div>
                </div>
                <div class="product-card bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition">
                    <img src="https://images.unsplash.com/photo-1607452258545-943d7243463c?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="iPad Pro" class="w-full h-40 object-cover">
                    <div class="p-4 text-center">
                        <h3 class="font-semibold text-lg dark:text-white">iPad</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Versatile. Powerful.</p>
                    </div>
                </div>
                <div class="product-card bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition">
                    <img src="https://images.unsplash.com/photo-1609692814858-f7cd2f0afa4f?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="iPhone 15" class="w-full h-40 object-cover">
                    <div class="p-4 text-center">
                        <h3 class="font-semibold text-lg dark:text-white">iPhone</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">New camera. New chip.</p>
                    </div>
                </div>
                <div class="product-card bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition">
                    <img src="https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=300&auto=format" alt="Apple Watch" class="w-full h-40 object-cover">
                    <div class="p-4 text-center">
                        <h3 class="font-semibold text-lg dark:text-white">Watch</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Better health, every day.</p>
                    </div>
                </div>
                <div class="product-card bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition">
                    <img src="https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=300&auto=format" alt="Apple TV" class="w-full h-40 object-cover">
                    <div class="p-4 text-center">
                        <h3 class="font-semibold text-lg dark:text-white">TV + Music</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Entertainment reimagined.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-16 bg-white dark:bg-gray-950 transition-colors">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-10 items-center">
                <div>
                    <h2 class="text-2xl font-bold mb-4 dark:text-white">Tech Specs</h2>
                    <ul class="space-y-3 text-gray-600 dark:text-gray-400">
                        <li>✅ A14 Bionic chip — fastest ever in a smartphone</li>
                        <li>✅ Pro camera system: 12MP Ultra Wide, Wide, and Telephoto</li>
                        <li>✅ 6.1‑inch Super Retina XDR display with Ceramic Shield</li>
                        <li>✅ 5G capable, MagSafe wireless charging</li>
                        <li>✅ Up to 17 hours video playback</li>
                    </ul>
                    <div class="mt-6">
                        <a href="#" class="inline-flex items-center text-blue-600 dark:text-blue-400 font-medium">Full specifications →</a>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 rounded-2xl p-6 transition-colors">
                    <h2 class="text-2xl font-bold mb-4 dark:text-white">Support</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-3">Get expert help with your iPhone, warranty, repairs, or trade‑in.</p>
                    <div class="space-y-2">
                        <a href="#" class="block text-blue-600 dark:text-blue-400">Contact Apple Support →</a>
                        <a href="#" class="block text-blue-600 dark:text-blue-400">Check coverage →</a>
                        <a href="#" class="block text-blue-600 dark:text-blue-400">AppleCare+ plans →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gray-900 dark:bg-black text-white py-12 transition-colors">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h3 class="text-2xl font-bold mb-2">Shop iPhone 12 Pro</h3>
            <p class="text-gray-300 dark:text-gray-400 mb-4">Customize your storage and choose a carrier.</p>
            <div class="flex justify-center gap-3 flex-wrap">
                <span class="bg-gray-800 dark:bg-gray-800 px-4 py-1.5 rounded-full text-sm">128GB – ₹999</span>
                <span class="bg-gray-800 dark:bg-gray-800 px-4 py-1.5 rounded-full text-sm">256GB – ₹1099</span>
                <span class="bg-gray-800 dark:bg-gray-800 px-4 py-1.5 rounded-full text-sm">512GB – ₹1299</span>
            </div>
            <div class="mt-6">
                <a href="#" class="bg-white text-gray-900 px-6 py-2.5 rounded-full font-semibold hover:bg-gray-200 transition">Buy now →</a>
            </div>
            <p class="text-xs text-gray-400 mt-6">*Monthly pricing is for 24‑month financing with 0% APR. See terms.</p>
        </div>
    </div>

    <footer class="bg-white dark:bg-gray-950 border-t border-gray-200 dark:border-gray-800 py-8 text-center text-sm text-gray-500 dark:text-gray-400 transition-colors">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center">
            <span>© 2025 Apple Inc. All rights reserved.</span>
            <div class="flex gap-4 mt-2 md:mt-0">
                <a href="#" class="hover:text-gray-800 dark:hover:text-gray-300">Privacy Policy</a>
                <a href="#" class="hover:text-gray-800 dark:hover:text-gray-300">Terms of Use</a>
                <a href="#" class="hover:text-gray-800 dark:hover:text-gray-300">Sales Policy</a>
            </div>
        </div>
    </footer>

    <!-- Carousel script (included after DOM) -->
    <script>
        (function() {
            let currentIndex = 0;
            const slides = document.querySelectorAll('.carousel-slide');
            const dots = document.querySelectorAll('.carousel-dot');
            const total = slides.length;
            if (!total) return;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.style.opacity = i === index ? '1' : '0';
                });
                dots.forEach((dot, i) => {
                    dot.classList.toggle('bg-white/70', i === index);
                    dot.classList.toggle('bg-white/30', i !== index);
                });
                currentIndex = index;
            }

            function nextSlide() { showSlide((currentIndex + 1) % total); }
            function prevSlide() { showSlide((currentIndex - 1 + total) % total); }

            const nextBtn = document.getElementById('carousel-next');
            const prevBtn = document.getElementById('carousel-prev');
            if (nextBtn) nextBtn.addEventListener('click', nextSlide);
            if (prevBtn) prevBtn.addEventListener('click', prevSlide);
            dots.forEach(dot => {
                dot.addEventListener('click', () => {
                    const idx = parseInt(dot.getAttribute('data-index'), 10);
                    if (!isNaN(idx)) showSlide(idx);
                });
            });

            let interval = setInterval(nextSlide, 4000);
            const carousel = document.getElementById('hero-carousel');
            if (carousel) {
                carousel.addEventListener('mouseenter', () => clearInterval(interval));
                carousel.addEventListener('mouseleave', () => {
                    interval = setInterval(nextSlide, 4000);
                });
            }
        })();
    </script>

    <script>
        // Dark mode toggle (same as before)
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        const themeToggle = document.getElementById('theme-toggle');
        themeToggle.addEventListener('click', () => {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        });
    </script>
</body>
</html>