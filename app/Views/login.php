<!DOCTYPE html>
<html lang="es">
<head>
    <script>
        // Anti-flash script: Aplicar el tema antes de que se renderice la página
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Soporte de Tickets - Iniciar Sesión / Registro</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Noto+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="<?= base_url('css/tailwind.css?v=' . filemtime(FCPATH . 'css/tailwind.css')) ?>">
    <link rel="stylesheet" href="<?= base_url('css/auth.css?v=' . filemtime(FCPATH . 'css/auth.css')) ?>">
</head>
<body class="bg-gray-50 dark:bg-background-dark font-display text-[#111418] dark:text-white antialiased overflow-x-hidden transition-colors duration-200">
<div class="relative flex h-auto min-h-screen w-full flex-col max-w-md mx-auto shadow-2xl bg-surface-light dark:bg-surface-dark overflow-hidden">
    <div class="w-full h-64 relative overflow-hidden shrink-0">
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 to-transparent z-10"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-surface-light dark:from-surface-dark to-transparent z-10 opacity-30"></div>
        <div class="w-full h-full bg-center bg-no-repeat bg-cover transform scale-105" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCPCBmsRY9ConI6vAwrAx4WbwSHdb2IZSA3WghgqyhbwElGXi_-Zcx2KceMr8hGUVB6JPG6VV-1FAZLgkwmK_VsO8RZWJGsi4v3BbMIte8_k_WyXWMGjTKmSeH_GLYXN8J6WbvMJT0fKDkd5i_gWf6W-2KGiZ084iUW93JBMgf6UcqCyNmq7187DNxsYjNsj9G19SfLxkN2vMaiyiQM7mRTsjlvsrDkeskHO_FtgiRVZZni5UPLtGf_SsFFcI16DxnTUIfmLQWR6SQ");'>
        </div>
    </div>
    <div class="flex-1 flex flex-col px-8 -mt-8 relative z-20 pb-8 bg-surface-light dark:bg-surface-dark rounded-t-3xl border-t border-white/20 dark:border-white/5">
        <div class="mt-6 mb-8 text-center">
            <h1 class="text-[#111418] dark:text-white tracking-tight text-3xl font-bold leading-tight pb-2">
                Soporte de Tickets
            </h1>
            <p class="text-[#637588] dark:text-[#9dabb9] text-sm font-medium">
                Gestiona tus tickets e historial de actividad.
            </p>
        </div>
        <div class="mb-8">
            <div class="flex h-12 w-full items-center justify-center rounded-xl bg-gray-100 dark:bg-[#1a2026] p-1.5 border border-gray-100 dark:border-[#343e49]">
                <label class="flex cursor-pointer h-full grow items-center justify-center overflow-hidden rounded-[0.5rem] px-2 transition-all has-[:checked]:bg-white dark:has-[:checked]:bg-[#283039] has-[:checked]:shadow-sm has-[:checked]:text-primary dark:has-[:checked]:text-white text-gray-500 dark:text-[#9dabb9] text-sm font-semibold leading-normal relative z-10">
                    <span class="truncate">Iniciar Sesión</span>
                    <input checked="" class="invisible w-0 absolute" name="auth-toggle" type="radio" value="Log In"/>
                </label>
                <label class="flex cursor-pointer h-full grow items-center justify-center overflow-hidden rounded-[0.5rem] px-2 transition-all has-[:checked]:bg-white dark:has-[:checked]:bg-[#283039] has-[:checked]:shadow-sm has-[:checked]:text-primary dark:has-[:checked]:text-white text-gray-500 dark:text-[#9dabb9] text-sm font-semibold leading-normal relative z-10">
                    <span class="truncate">Registrarse</span>
                    <input class="invisible w-0 absolute" name="auth-toggle" type="radio" value="Sign Up"/>
                </label>
            </div>
        </div>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 flex items-center gap-3">
                <span class="material-symbols-outlined text-red-500 dark:text-red-400">error</span>
                <p class="text-sm font-medium text-red-600 dark:text-red-300"><?= session()->getFlashdata('error') ?></p>
            </div>
        <?php endif; ?>
        <form class="flex flex-col gap-6" action="<?= site_url('login') ?>" method="post">
            <div class="flex flex-col w-full space-y-2">
                <label class="text-[#111418] dark:text-white text-sm font-semibold leading-normal ml-1" for="email">Correo Electrónico</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-gray-400 group-focus-within:text-primary transition-colors" style="font-size: 20px;">mail</span>
                    </div>
                    <input autocomplete="email" style="padding-left: 3.5rem;" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#111418] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 focus:border-primary border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#1a2026] h-12 placeholder:text-gray-400 dark:placeholder:text-[#637588] pr-4 text-sm font-medium leading-normal transition-all" id="email" name="email" placeholder="usuario@ejemplo.com" required="" type="email"/>
                </div>
            </div>
            <div class="flex flex-col w-full space-y-2">
                <div class="flex justify-between items-center ml-1">
                    <label class="text-[#111418] dark:text-white text-sm font-semibold leading-normal" for="password">Contraseña</label>
                    <a class="text-xs font-medium text-primary hover:text-blue-400 transition-colors" href="#">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-gray-400 group-focus-within:text-primary transition-colors" style="font-size: 20px;">lock</span>
                    </div>
                    <input autocomplete="current-password" style="padding-left: 3.5rem;" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[#111418] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 focus:border-primary border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#1a2026] h-12 placeholder:text-gray-400 dark:placeholder:text-[#637588] pr-12 text-sm font-medium leading-normal transition-all" id="password" name="password" placeholder="••••••••" required="" type="password"/>
                    <button class="absolute inset-y-0 right-0 flex items-center justify-center px-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors focus:outline-none" type="button">
                        <span class="material-symbols-outlined" style="font-size: 20px;">visibility</span>
                    </button>
                </div>
            </div>
            <button class="mt-4 flex w-full items-center justify-center rounded-xl bg-primary h-12 px-5 text-white text-sm font-bold leading-normal tracking-wide hover:bg-blue-600 active:scale-[0.98] transition-all shadow-lg shadow-blue-500/25 ring-offset-2 focus:ring-2 ring-primary" type="submit">
                Iniciar Sesión
            </button>
        </form>
        <div class="relative flex py-8 items-center">
            <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
            <span class="flex-shrink-0 mx-4 text-gray-400 dark:text-gray-500 text-xs font-medium uppercase tracking-wider">O continuar con</span>
            <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <button class="flex items-center justify-center gap-2 h-12 rounded-xl bg-white dark:bg-[#1a2026] border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-[#202830] transition-all hover:shadow-md active:scale-95">
                <img alt="Google Logo" class="w-5 h-5" data-alt="Google logo icon" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDF6qeJzd4xyH3fpxp4ygLAtjlRr-7-9bGnR0iFVeNL2JakhsfOznP7I3HczacQBYjWZ6DBrbUqkvbXrbgrRAbFF1sVLQJC3DwrchdfdCMhLW2E3OGpGL-FYtTKgDLGkuc14M2k9PVpSnwp-ueSrq2K8WhXCk5pkB-0rg5SjcpFAngXJhujfI-KNA4bzFFHkuCbw3XXjG6etUVt6ojYfeadzHFmMOyr0jFv7xErDqR7OM7iMBhOIZNOLg_6jWzFBvLEapV4bMeOaNI"/>
                <span class="text-[#111418] dark:text-white text-sm font-semibold">Google</span>
            </button>
            <button class="flex items-center justify-center gap-2 h-12 rounded-xl bg-white dark:bg-[#1a2026] border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-[#202830] transition-all hover:shadow-md active:scale-95">
                <span class="material-symbols-outlined text-[#111418] dark:text-white" style="font-size: 22px;">ios</span>
                <span class="text-[#111418] dark:text-white text-sm font-semibold">Apple</span>
            </button>
        </div>
        <div class="mt-auto pt-6 text-center">
            <div class="flex items-center justify-center gap-2 text-gray-400 dark:text-gray-600 text-xs">
                <span class="material-symbols-outlined" style="font-size: 14px;">verified_user</span>
                <span>Autenticación Segura</span>
            </div>
        </div>
    </div>
</div>
</body>
</html>


