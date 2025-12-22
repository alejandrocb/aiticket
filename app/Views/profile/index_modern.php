<!-- profile/index_modern.php -->
<div class="flex-1 w-full max-w-md mx-auto pb-12">
    <div class="flex p-6 flex-col items-center gap-4">
        <div class="relative group cursor-pointer">
            <div class="bg-center bg-no-repeat bg-cover rounded-full h-28 w-28 ring-4 ring-background-light dark:ring-surface-border shadow-lg" data-alt="User profile avatar showing a professional headshot" style='background-image: url("<?= $usuario['imagen'] ?: 'https://lh3.googleusercontent.com/aida-public/AB6AXuB2iQs-P5zUg6HToNK61crR5QytvAS7y3No4CndGppj8vdjvuxiy2aXdDY8XDidjkdOVDkpk7U-ZJ2HqpJbUKtHEj7ZFjpO9FJzfoHq6lYHEtSVBGi6Sy9Ifp1AQyBM-FN0F8Jb_uYWHGDNtLJW7EU57046NXf3G4nq_jFlLissa1HDGHdvPI3mvl-xH9UrViB3RZE5D9eoegQ_oux4amcZ0UvHeHJ8HbzuU1jQdxhWC0iCIS-KEsn9O0EP_5hIsA9dT8dA-ZNMils' ?>");'>
            </div>
            <div class="absolute bottom-0 right-0 bg-primary text-white p-1.5 rounded-full border-2 border-background-light dark:border-background-dark flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-[16px]">edit</span>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center">
            <p class="text-[22px] font-bold leading-tight tracking-tight dark:text-white"><?= $usuario['nombre'] ?></p>
            <p class="text-text-secondary text-sm font-medium mt-1">
                <?php 
                    $roles = [1 => 'Admin', 2 => 'Soporte', 3 => 'Cliente'];
                    echo $roles[$usuario['rol_id']] ?? 'Usuario';
                ?>
            </p>
            <div class="mt-2 px-3 py-1 rounded-full bg-primary/20 border border-primary/30">
                <p class="text-primary text-xs font-semibold tracking-wide uppercase"><?= $roles[$usuario['rol_id']] ?? 'Rol Desconocido' ?></p>
            </div>
        </div>
    </div>
    <div class="px-4 mt-2">
        <h3 class="text-text-secondary text-xs font-bold uppercase tracking-wider mb-2 ml-1">Información de la Cuenta</h3>
        <div class="flex flex-col gap-3">
            <div class="flex flex-col">
                <label class="text-sm font-medium leading-normal mb-1.5 ml-1 dark:text-white">Nombre Completo</label>
                <div class="relative">
                    <input class="w-full rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary border border-gray-200 dark:border-surface-border bg-white dark:bg-surface-dark h-12 px-4 text-base font-normal shadow-sm" value="<?= $usuario['nombre'] ?>"/>
                    <span class="material-symbols-outlined absolute right-4 top-3 text-text-secondary pointer-events-none">person</span>
                </div>
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium leading-normal mb-1.5 ml-1 dark:text-white">Correo Electrónico</label>
                <div class="relative">
                    <input class="w-full rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary border border-gray-200 dark:border-surface-border bg-white dark:bg-surface-dark h-12 px-4 text-base font-normal shadow-sm" type="email" value="<?= $usuario['email'] ?>"/>
                    <span class="material-symbols-outlined absolute right-4 top-3 text-text-secondary pointer-events-none">mail</span>
                </div>
            </div>
            <button class="flex w-full items-center justify-between p-4 mt-1 bg-white dark:bg-surface-dark border border-gray-200 dark:border-surface-border rounded-lg hover:bg-gray-50 dark:hover:bg-surface-border/30 transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 text-primary">
                        <span class="material-symbols-outlined text-[20px]">lock</span>
                    </div>
                    <span class="text-base font-medium dark:text-white">Cambiar Contraseña</span>
                </div>
                <span class="material-symbols-outlined text-text-secondary group-hover:text-primary transition-colors">chevron_right</span>
            </button>
        </div>
    </div>
    <!-- ... Rest of the static profile sections (Escenarios, Preferences, Support) ... -->
    <!-- Keeping them static for now as they are presentational in the prototype -->
    <div class="px-4 mt-8">
        <h3 class="text-text-secondary text-xs font-bold uppercase tracking-wider mb-2 ml-1">Preferencias</h3>
        <div class="bg-white dark:bg-surface-dark border border-gray-200 dark:border-surface-border rounded-lg overflow-hidden divide-y divide-gray-100 dark:divide-surface-border/50">
             <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-surface-border/30 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-purple-500/10 text-purple-500">
                        <span class="material-symbols-outlined text-[20px]">dark_mode</span>
                    </div>
                    <span class="text-base font-medium dark:text-white">Apariencia</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-text-secondary">Oscuro</span>
                    <span class="material-symbols-outlined text-text-secondary text-[20px]">chevron_right</span>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 mt-10 mb-6 flex flex-col gap-4">
        <button class="w-full bg-primary hover:bg-blue-600 text-white font-bold h-12 rounded-lg transition-all shadow-lg shadow-blue-900/20 active:scale-[0.98]">
            Guardar Cambios
        </button>
        <a href="<?= base_url('logout') ?>" class="w-full bg-transparent border border-red-500/20 text-red-500 hover:bg-red-500/10 font-medium h-12 rounded-lg transition-all flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[20px]">logout</span>
            Cerrar Sesión
        </a>
    </div>
</div>
