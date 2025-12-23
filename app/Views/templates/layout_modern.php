<!-- layout_modern.php -->
<!DOCTYPE html>
<html class="dark" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $title ?? 'Panel de Tickets'; ?></title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                        "surface-dark": "#1c2630",
                        "surface-light": "#ffffff",
                        "text-secondary": "#9dabb9",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-background-light dark:bg-background-dark text-[#111418] dark:text-white font-display overflow-x-hidden">
<div class="relative flex h-full min-h-screen w-full flex-row">
    <!-- Desktop Sidebar -->
    <div class="hidden md:flex flex-col w-64 fixed h-full bg-surface-light dark:bg-surface-dark border-r border-[#e5e7eb] dark:border-[#283039] z-30">
        <div class="p-4 border-b border-[#e5e7eb] dark:border-[#283039]">
            <h2 class="text-[#111418] dark:text-white text-2xl font-bold leading-tight tracking-[-0.015em]">Soporte</h2>
        </div>
        <nav class="flex-1 overflow-y-auto p-4 space-y-2">
            <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-3 w-full px-3 py-2 <?= (uri_string() == 'dashboard' || uri_string() == '/') ? 'bg-primary/10 text-primary' : 'text-text-secondary hover:text-[#111418] dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#2c3b4a]' ?> rounded-xl transition-colors">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="text-sm font-medium">Panel</span>
            </a>
            <a href="<?= base_url('tickets') ?>" class="flex items-center gap-3 w-full px-3 py-2 <?= (strpos(uri_string(), 'tickets') !== false && strpos(uri_string(), 'tickets/crear') === false) ? 'bg-primary/10 text-primary' : 'text-text-secondary hover:text-[#111418] dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#2c3b4a]' ?> rounded-xl transition-colors">
                <span class="material-symbols-outlined">confirmation_number</span>
                <span class="text-sm font-medium">Tickets</span>
            </a>
            <a href="<?= base_url('clientes') ?>" class="flex items-center gap-3 w-full px-3 py-2 <?= (strpos(uri_string(), 'clientes') !== false) ? 'bg-primary/10 text-primary' : 'text-text-secondary hover:text-[#111418] dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#2c3b4a]' ?> rounded-xl transition-colors">
                <span class="material-symbols-outlined">groups</span>
                <span class="text-sm font-medium">Clientes</span>
            </a>
            <a href="<?= base_url('usuarios') ?>" class="flex items-center gap-3 w-full px-3 py-2 <?= (strpos(uri_string(), 'usuarios') !== false) ? 'bg-primary/10 text-primary' : 'text-text-secondary hover:text-[#111418] dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#2c3b4a]' ?> rounded-xl transition-colors">
                <span class="material-symbols-outlined">person</span>
                <span class="text-sm font-medium">Usuarios</span>
            </a>
            
            <div class="pt-2 pb-1 px-3">
                <p class="text-xs font-semibold text-text-secondary uppercase tracking-wider">Maestros</p>
            </div>
            
            <a href="<?= base_url('tiposticket') ?>" class="flex items-center gap-3 w-full px-3 py-2 <?= (strpos(uri_string(), 'tiposticket') !== false) ? 'bg-primary/10 text-primary' : 'text-text-secondary hover:text-[#111418] dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#2c3b4a]' ?> rounded-xl transition-colors">
                <span class="material-symbols-outlined">category</span>
                <span class="text-sm font-medium">Tipos de Ticket</span>
            </a>
            <a href="<?= base_url('estadosticket') ?>" class="flex items-center gap-3 w-full px-3 py-2 <?= (strpos(uri_string(), 'estadosticket') !== false) ? 'bg-primary/10 text-primary' : 'text-text-secondary hover:text-[#111418] dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#2c3b4a]' ?> rounded-xl transition-colors">
                <span class="material-symbols-outlined">flag</span>
                <span class="text-sm font-medium">Estados</span>
            </a>
            <a href="<?= base_url('prioridadesticket') ?>" class="flex items-center gap-3 w-full px-3 py-2 <?= (strpos(uri_string(), 'prioridadesticket') !== false) ? 'bg-primary/10 text-primary' : 'text-text-secondary hover:text-[#111418] dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#2c3b4a]' ?> rounded-xl transition-colors">
                <span class="material-symbols-outlined">priority_high</span>
                <span class="text-sm font-medium">Prioridades</span>
            </a>
            
            <a href="#" class="flex items-center gap-3 w-full px-3 py-2 text-text-secondary hover:text-[#111418] dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#2c3b4a] rounded-xl transition-colors">
                <span class="material-symbols-outlined">bar_chart</span>
                <span class="text-sm font-medium">Reportes</span>
            </a>
        </nav>
        <div class="p-4 border-t border-[#e5e7eb] dark:border-[#283039]">
            <button class="flex items-center gap-3 w-full px-3 py-2 text-text-secondary hover:text-[#111418] dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#2c3b4a] rounded-xl transition-colors">
                <div class="h-6 w-6 rounded-full bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDnDQpiODsqGs8p2ABwphK9A97hhZFah0pgGh7x6Uvw1ebRamKOBTnXBFgzPjCE_06miHRtm8NspJxj50xa3HCgOeNWWIn-RNMtOdLfX78W7DMtay9TWML4hU2hX7OzRL94ttCc1geP0pTKuGY9sJz7BghpR_tEHYZtkBWrxr8YrzsxFTzvu-kjIb9e0H9M2k2xPres1F8Vfdayt3SKUCjAzBhePrKkbD2_efnCm7i11yZMJUrHO_SHimiL9xt7LJLRjXbnJOZvK2g");'></div>
                <span class="text-sm font-medium">Perfil</span>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col md:ml-64">
        <!-- Top Sticky Header -->
        <div class="sticky top-0 z-20 bg-background-light dark:bg-background-dark/95 backdrop-blur-md border-b border-[#e5e7eb] dark:border-[#283039]">
            <div class="flex items-center p-4 pb-2 justify-between">
                <h2 class="text-[#111418] dark:text-white text-2xl font-bold leading-tight tracking-[-0.015em] flex-1 md:hidden">Soporte</h2>
                <h2 class="text-[#111418] dark:text-white text-2xl font-bold leading-tight tracking-[-0.015em] flex-1 hidden md:block"><?= $title ?? 'Dashboard'; ?></h2>
                <div class="flex items-center justify-end gap-3">
                    <button class="flex items-center justify-center rounded-full h-10 w-10 bg-transparent text-[#111418] dark:text-white hover:bg-black/5 dark:hover:bg-white/10 transition-colors">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>
                    <a href="<?= base_url('tickets/crear') ?>" class="flex items-center justify-center rounded-lg h-10 w-10 bg-primary text-white shadow-lg shadow-primary/30 hover:bg-primary/90 transition-colors">
                        <span class="material-symbols-outlined">add</span>
                    </a>
                </div>
            </div>
            <!-- Dynamic Sub-header/Filters (Optional, can be passed from view) -->
        </div>

        <!-- Dynamic Content -->
        <div class="flex-1 overflow-y-auto px-4 pb-28 pt-4 space-y-4">
            <?php include(APPPATH . "Views/{$content}.php"); ?>
        </div>

        <!-- Bottom Nav (Mobile Only) -->
        <div class="fixed bottom-0 left-0 right-0 bg-surface-light dark:bg-surface-dark border-t border-[#e5e7eb] dark:border-[#2c3b4a] pb-safe z-30 md:hidden">
            <div class="flex justify-around items-center h-16 pb-2">
                <a href="<?= base_url('dashboard') ?>" class="flex flex-col items-center justify-center w-full h-full gap-1 group">
                    <span class="material-symbols-outlined <?= (uri_string() == 'dashboard' || uri_string() == '/') ? 'text-primary' : 'text-text-secondary group-hover:text-primary' ?> transition-colors">dashboard</span>
                    <span class="text-[10px] font-medium <?= (uri_string() == 'dashboard' || uri_string() == '/') ? 'text-primary' : 'text-text-secondary group-hover:text-primary' ?>">Panel</span>
                </a>
                <a href="<?= base_url('tickets') ?>" class="flex flex-col items-center justify-center w-full h-full gap-1 group">
                    <span class="material-symbols-outlined <?= (strpos(uri_string(), 'tickets') !== false) ? 'text-primary' : 'text-text-secondary group-hover:text-primary' ?> transition-colors">confirmation_number</span>
                    <span class="text-[10px] font-medium <?= (strpos(uri_string(), 'tickets') !== false) ? 'text-primary' : 'text-text-secondary group-hover:text-primary' ?>">Tickets</span>
                </a>
                <button class="flex flex-col items-center justify-center w-full h-full gap-1 group">
                    <span class="material-symbols-outlined text-text-secondary group-hover:text-primary transition-colors">bar_chart</span>
                    <span class="text-[10px] font-medium text-text-secondary group-hover:text-primary">Reportes</span>
                </button>
                <button class="flex flex-col items-center justify-center w-full h-full gap-1 group">
                    <div class="h-6 w-6 rounded-full bg-cover bg-center border border-transparent group-hover:border-primary transition-colors" data-alt="User profile picture" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDnDQpiODsqGs8p2ABwphK9A97hhZFah0pgGh7x6Uvw1ebRamKOBTnXBFgzPjCE_06miHRtm8NspJxj50xa3HCgOeNWWIn-RNMtOdLfX78W7DMtay9TWML4hU2hX7OzRL94ttCc1geP0pTKuGY9sJz7BghpR_tEHYZtkBWrxr8YrzsxFTzvu-kjIb9e0H9M2k2xPres1F8Vfdayt3SKUCjAzBhePrKkbD2_efnCm7i11yZMJUrHO_SHimiL9xt7LJLRjXbnJOZvK2g");'></div>
                    <span class="text-[10px] font-medium text-text-secondary group-hover:text-primary">Perfil</span>
                </button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
