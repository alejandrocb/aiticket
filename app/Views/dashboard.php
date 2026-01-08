<!DOCTYPE html>
<html class="dark" lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Panel de Tickets</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="<?= base_url('css/tailwind.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body class="bg-background-light dark:bg-background-dark text-[#111418] dark:text-white font-display overflow-x-hidden">
<div class="relative flex h-full min-h-screen w-full flex-row">
    <!-- Desktop Sidebar -->
    <div class="hidden md:flex flex-col w-64 fixed h-full bg-surface-light dark:bg-surface-dark border-r border-[#e5e7eb] dark:border-[#283039] z-30">
        <div class="p-4 border-b border-[#e5e7eb] dark:border-[#283039]">
            <h2 class="text-[#111418] dark:text-white text-2xl font-bold leading-tight tracking-[-0.015em]">Soporte</h2>
        </div>
        <nav class="flex-1 overflow-y-auto p-4 space-y-2">
            <button class="flex items-center gap-3 w-full px-3 py-2 bg-primary/10 text-primary rounded-xl transition-colors">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="text-sm font-medium">Panel</span>
            </button>
            <button class="flex items-center gap-3 w-full px-3 py-2 text-text-secondary hover:text-[#111418] dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#2c3b4a] rounded-xl transition-colors">
                <span class="material-symbols-outlined">confirmation_number</span>
                <span class="text-sm font-medium">Tickets</span>
            </button>
            <button class="flex items-center gap-3 w-full px-3 py-2 text-text-secondary hover:text-[#111418] dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#2c3b4a] rounded-xl transition-colors">
                <span class="material-symbols-outlined">bar_chart</span>
                <span class="text-sm font-medium">Reportes</span>
            </button>
        </nav>
        <div class="p-4 border-t border-[#e5e7eb] dark:border-[#283039]">
            <button class="flex items-center gap-3 w-full px-3 py-2 text-text-secondary hover:text-[#111418] dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#2c3b4a] rounded-xl transition-colors">
                <div class="h-6 w-6 rounded-full bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDnDQpiODsqGs8p2ABwphK9A97hhZFah0pgGh7x6Uvw1ebRamKOBTnXBFgzPjCE_06miHRtm8NspJxj50xa3HCgOeNWWIn-RNMtOdLfX78W7DMtay9TWML4hU2hX7OzRL94ttCc1geP0pTKuGY9sJz7BghpR_tEHYZtkBWrxr8YrzsxFTzvu-kjIb9e0H9M2k2xPres1F8Vfdayt3SKUCjAzBhePrKkbD2_efnCm7i11yZMJUrHO_SHimiL9xt7LJLRjXbnJOZvK2g");'></div>
                <span class="text-sm font-medium">Perfil</span>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col md:ml-64 w-full">
        <div class="sticky top-0 z-20 bg-background-light dark:bg-background-dark/95 backdrop-blur-md border-b border-[#e5e7eb] dark:border-[#283039]">
            <div class="flex items-center p-4 pb-2 justify-between">
                <h2 class="text-[#111418] dark:text-white text-2xl font-bold leading-tight tracking-[-0.015em] flex-1 md:hidden">Panel de Tickets</h2>
                <h2 class="text-[#111418] dark:text-white text-2xl font-bold leading-tight tracking-[-0.015em] flex-1 hidden md:block">Dashboard</h2>
                <div class="flex items-center justify-end gap-3">
                    <button class="flex items-center justify-center rounded-full h-10 w-10 bg-transparent text-[#111418] dark:text-white hover:bg-black/5 dark:hover:bg-white/10 transition-colors">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>
                    <button class="flex items-center justify-center rounded-lg h-10 w-10 bg-primary text-white shadow-lg shadow-primary/30 hover:bg-primary/90 transition-colors">
                        <span class="material-symbols-outlined">add</span>
                    </button>
                </div>
            </div>
            <div class="px-4 py-2 pb-3">
                <label class="flex flex-col min-w-40 h-11 w-full">
                    <div class="flex w-full flex-1 items-stretch rounded-xl h-full shadow-sm">
                        <div class="text-text-secondary flex border-none bg-surface-light dark:bg-surface-dark items-center justify-center pl-4 rounded-l-xl border-r-0">
                            <span class="material-symbols-outlined text-[22px]">search</span>
                        </div>
                        <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#111418] dark:text-white focus:outline-0 focus:ring-0 border-none bg-surface-light dark:bg-surface-dark focus:border-none h-full placeholder:text-text-secondary px-4 rounded-l-none border-l-0 pl-3 text-base font-normal leading-normal" placeholder="Buscar por palabra clave o ID..." value=""/>
                    </div>
                </label>
            </div>
            <div class="flex gap-3 px-4 py-3 overflow-x-auto no-scrollbar items-center">
                <button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-primary pl-5 pr-5 shadow-lg shadow-primary/20 transition-transform active:scale-95">
                    <p class="text-white text-sm font-semibold leading-normal">Todos</p>
                </button>
                <button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-surface-light dark:bg-surface-dark border border-[#e5e7eb] dark:border-transparent pl-4 pr-5 hover:bg-gray-100 dark:hover:bg-[#2c3b4a] transition-colors active:scale-95">
                    <span class="material-symbols-outlined text-sm">filter_list</span>
                    <p class="text-[#111418] dark:text-white text-sm font-medium leading-normal">Estado</p>
                </button>
                <button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-surface-light dark:bg-surface-dark border border-[#e5e7eb] dark:border-transparent pl-4 pr-5 hover:bg-gray-100 dark:hover:bg-[#2c3b4a] transition-colors active:scale-95">
                    <span class="material-symbols-outlined text-sm">flag</span>
                    <p class="text-[#111418] dark:text-white text-sm font-medium leading-normal">Prioridad</p>
                </button>
                <button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-surface-light dark:bg-surface-dark border border-[#e5e7eb] dark:border-transparent pl-4 pr-5 hover:bg-gray-100 dark:hover:bg-[#2c3b4a] transition-colors active:scale-95">
                    <span class="material-symbols-outlined text-sm">person</span>
                    <p class="text-[#111418] dark:text-white text-sm font-medium leading-normal">Cliente</p>
                </button>
                <button class="flex h-9 shrink-0 items-center justify-center gap-x-2 rounded-full bg-surface-light dark:bg-surface-dark border border-[#e5e7eb] dark:border-transparent pl-4 pr-5 hover:bg-gray-100 dark:hover:bg-[#2c3b4a] transition-colors active:scale-95">
                    <span class="material-symbols-outlined text-sm">category</span>
                    <p class="text-[#111418] dark:text-white text-sm font-medium leading-normal">Escenario</p>
                </button>
            </div>
        </div>
        <div class="flex-1 overflow-y-auto px-4 pb-28 pt-4 space-y-4">
            <div class="flex items-center justify-between mb-2">
                <p class="text-sm font-semibold text-text-secondary uppercase tracking-wider">Lista de Tickets</p>
                <button class="flex items-center text-primary text-sm font-medium gap-1">
                    Fecha de creación <span class="material-symbols-outlined text-sm">sort</span>
                </button>
            </div>
            <!-- Ticket Items -->
            <div class="flex flex-col gap-3 bg-surface-light dark:bg-surface-dark p-4 rounded-xl shadow-sm border border-[#e5e7eb] dark:border-transparent active:scale-[0.99] transition-transform cursor-pointer">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-10 w-10 ring-2 ring-white dark:ring-[#283039]" data-alt="Avatar of user Sarah J." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBF1rHodov-40TUbkbcXVsQay16aOfoNEtWY8gtIy4TDfauoXSn8nSL7tHfGmJaQFOk4BKybEQhju7Uy6yTiXnF2CdPCSw6C0TcbAzX_PVKKMzxjP0vl1bqnbMYQ8CxEeiUolD2ly4oJhzzhlj7AnwEnYK_jbK54QPffuIjkWOpWF1290x2CGn56hnGoyv-A8FXuhpsloE-XU5Adv-YdcBDPywu28yNRXNRdLRyzp051_LZCCazPWybXSEtjiqrxLjKzMBmcNDWwO8");'></div>
                            <div class="absolute -bottom-1 -right-1 bg-green-500 h-3 w-3 rounded-full border-2 border-surface-light dark:border-surface-dark"></div>
                        </div>
                        <div>
                            <p class="text-[#111418] dark:text-white text-sm font-bold leading-tight">María González</p>
                            <p class="text-text-secondary text-xs font-normal">#Ticket-402 • 2h</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center rounded-md bg-red-400/10 px-2 py-1 text-xs font-medium text-red-400 ring-1 ring-inset ring-red-400/20">Alta</span>
                </div>
                <div>
                    <p class="text-[#111418] dark:text-white text-base font-semibold leading-normal mb-1">Error al iniciar sesión en iOS</p>
                    <p class="text-text-secondary text-sm font-normal leading-relaxed line-clamp-2">Los usuarios reportan un cierre inesperado de la aplicación inmediatamente después de ingresar credenciales.</p>
                </div>
                <div class="h-px w-full bg-[#e5e7eb] dark:bg-[#2c3b4a]"></div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-500 text-[18px]">radio_button_checked</span>
                        <p class="text-text-secondary text-sm font-medium">Abierto</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-text-secondary font-medium">Agente:</span>
                        <div class="flex items-center gap-2 bg-background-light dark:bg-background-dark/50 pr-3 pl-1 py-1 rounded-full">
                            <div class="h-5 w-5 rounded-full bg-gray-400 flex items-center justify-center text-[10px] text-white font-bold">SP</div>
                            <span class="text-xs font-medium text-[#111418] dark:text-white">Sin Asignar</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-3 bg-surface-light dark:bg-surface-dark p-4 rounded-xl shadow-sm border border-[#e5e7eb] dark:border-transparent active:scale-[0.99] transition-transform cursor-pointer">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-10 w-10 ring-2 ring-white dark:ring-[#283039]" data-alt="Avatar of user Mike T." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBC8HuOfCtV9gDibmx6lHASmQ6h9oaXra6XIzSmThqyGIu91OhS2VHhUH3WFKBExl_Wgq1dDcZgqxLpWefKxV2ht2LDZ7aipTlu2TvU95QWjBRFLLM5_WJTDlxAkC_VI4dvbw7NBoe_uUHmfWg4ucz3Qnw423uH-KQ8AU-BTrrTzp-jXFLUbMcbaVYONxqpB3EHAJq7sHdWndberexqVq1CtadTPCIs38KPkej9MHbo8-IsIp_VvkGRqxIP7TBjtWBEqWE4XszhGHI");'></div>
                        <div>
                            <p class="text-[#111418] dark:text-white text-sm font-bold leading-tight">Miguel Torres</p>
                            <p class="text-text-secondary text-xs font-normal">#Ticket-401 • 5h</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center rounded-md bg-amber-400/10 px-2 py-1 text-xs font-medium text-amber-400 ring-1 ring-inset ring-amber-400/20">Media</span>
                </div>
                <div>
                    <p class="text-[#111418] dark:text-white text-base font-semibold leading-normal mb-1">Solicitud de modo oscuro</p>
                    <p class="text-text-secondary text-sm font-normal leading-relaxed line-clamp-2">El cliente solicita una implementación completa del modo oscuro en todo el panel para reducir la fatiga visual.</p>
                </div>
                <div class="h-px w-full bg-[#e5e7eb] dark:bg-[#2c3b4a]"></div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[18px]">account_circle</span>
                        <p class="text-text-secondary text-sm font-medium">Asignado</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-text-secondary font-medium">Agente:</span>
                        <div class="flex items-center gap-2 bg-background-light dark:bg-background-dark/50 pr-3 pl-1 py-1 rounded-full">
                            <div class="h-5 w-5 rounded-full bg-cover bg-center" data-alt="Assignee avatar" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCvuXa8AJC1RQhYAeFOgv8yeQhEMdT7gHQJk9drixw6p39mrWs8YDr8EgzLhBpiSW_EkJ20o2kD4uJvlGrWNMoGFSh7m1zXcHFY5Rn5nHNsZfUOpgABVZ42UtA2eHxAohk5VPQQFruzOsfIQk9q-WFKj-VNEBPtwF96Fe1JB07JSei774SDWCQU9xPzvXJ4EcZhF8d3bc9PKKqvHa1h8NXnxGyFoHtuAuu77P5DTusSBd_IDTrDCvcfjAtWWNnBIH-yGbIG_s_8SvI");'></div>
                            <span class="text-xs font-medium text-[#111418] dark:text-white">Carlos R.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-3 bg-surface-light dark:bg-surface-dark p-4 rounded-xl shadow-sm border border-[#e5e7eb] dark:border-transparent active:scale-[0.99] transition-transform cursor-pointer opacity-75 hover:opacity-100">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full h-10 w-10 grayscale ring-2 ring-white dark:ring-[#283039]" data-alt="Avatar of user Emily R." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBbTa0YkCxj8RVALCiUqOq_dlE_4SJRZzjY1Dsh66OuQr3QhSpP6nbI8vhvYuVXDXV1cbtLFsNAcL-eCQnIPkbkGwZtm9bwhcy-EtwvZGry2hTC8RV7Jdyy7akRiUsaka5lPhThdnkIFSET9qIZSPQ59yv0X587HFZL7lsqIA4fZq63ZRTOebQPeFZ9ChTnl1upR_ID8BMNCWCIuDeK26YAdlTeMz2ybKlRV4tP0u0PQ3Mu_R3qctqYHC6coCjhi9-zGs-mFFi5Zj8");'></div>
                        <div>
                            <p class="text-[#111418] dark:text-white text-sm font-bold leading-tight">Emilia Rosas</p>
                            <p class="text-text-secondary text-xs font-normal">#Ticket-399 • 1d</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center rounded-md bg-blue-400/10 px-2 py-1 text-xs font-medium text-blue-400 ring-1 ring-inset ring-blue-400/20">Baja</span>
                </div>
                <div>
                    <p class="text-[#111418] dark:text-white text-base font-semibold leading-normal mb-1 line-through text-text-secondary">Restablecer contraseña</p>
                    <p class="text-text-secondary text-sm font-normal leading-relaxed line-clamp-2">El usuario olvidó su contraseña de administrador y el correo de recuperación llega a spam.</p>
                </div>
                <div class="h-px w-full bg-[#e5e7eb] dark:bg-[#2c3b4a]"></div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-500 text-[18px]">check_circle</span>
                        <p class="text-text-secondary text-sm font-medium">Finalizado</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-text-secondary font-medium">Agente:</span>
                        <div class="flex items-center gap-2 bg-background-light dark:bg-background-dark/50 pr-3 pl-1 py-1 rounded-full">
                            <div class="h-5 w-5 rounded-full bg-blue-600 flex items-center justify-center text-[10px] text-white font-bold">AD</div>
                            <span class="text-xs font-medium text-[#111418] dark:text-white">Admin</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-3 bg-surface-light dark:bg-surface-dark p-4 rounded-xl shadow-sm border border-[#e5e7eb] dark:border-transparent active:scale-[0.99] transition-transform cursor-pointer">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center bg-purple-600 rounded-full h-10 w-10 text-white font-bold text-sm ring-2 ring-white dark:ring-[#283039]">
                            JL
                        </div>
                        <div>
                            <p class="text-[#111418] dark:text-white text-sm font-bold leading-tight">Jaime López</p>
                            <p class="text-text-secondary text-xs font-normal">#Ticket-395 • 2d</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center rounded-md bg-amber-400/10 px-2 py-1 text-xs font-medium text-amber-400 ring-1 ring-inset ring-amber-400/20">Media</span>
                </div>
                <div>
                    <p class="text-[#111418] dark:text-white text-base font-semibold leading-normal mb-1">Discrepancia en facturación</p>
                    <p class="text-text-secondary text-sm font-normal leading-relaxed line-clamp-2">Al cliente se le cobró dos veces por el plan de suscripción de septiembre.</p>
                </div>
                <div class="h-px w-full bg-[#e5e7eb] dark:bg-[#2c3b4a]"></div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-500 text-[18px]">schedule</span>
                        <p class="text-text-secondary text-sm font-medium">En Progreso</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-text-secondary font-medium">Agente:</span>
                        <div class="flex items-center gap-2 bg-background-light dark:bg-background-dark/50 pr-3 pl-1 py-1 rounded-full">
                            <div class="h-5 w-5 rounded-full bg-teal-600 flex items-center justify-center text-[10px] text-white font-bold">SO</div>
                            <span class="text-xs font-medium text-[#111418] dark:text-white">Soporte</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bottom Nav (Mobile Only) -->
        <div class="fixed bottom-0 left-0 right-0 bg-surface-light dark:bg-surface-dark border-t border-[#e5e7eb] dark:border-[#2c3b4a] pb-safe z-30 md:hidden">
            <div class="flex justify-around items-center h-16 pb-2">
                <button class="flex flex-col items-center justify-center w-full h-full gap-1 group">
                    <span class="material-symbols-outlined text-text-secondary group-hover:text-primary transition-colors">dashboard</span>
                    <span class="text-[10px] font-medium text-text-secondary group-hover:text-primary">Panel</span>
                </button>
                <button class="flex flex-col items-center justify-center w-full h-full gap-1">
                    <span class="material-symbols-outlined text-primary">confirmation_number</span>
                    <span class="text-[10px] font-medium text-primary">Tickets</span>
                </button>
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
