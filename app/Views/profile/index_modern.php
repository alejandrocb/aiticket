<!-- profile/index_modern.php -->
<div class="flex-1 w-full max-w-md mx-auto pb-12">
    <!-- Feedback Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="mx-4 mt-4 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-600 flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <p class="text-sm font-medium"><?= session()->getFlashdata('success') ?></p>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="mx-4 mt-4 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-600 flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            <p class="text-sm font-medium"><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>

    <div class="flex p-6 flex-col items-center gap-4">
        <form id="avatar-form" action="<?= base_url('profile/updateAvatar') ?>" method="POST" enctype="multipart/form-data" class="hidden">
            <input type="file" name="avatar" id="avatar-input" onchange="document.getElementById('avatar-form').submit()">
        </form>

        <div class="relative group cursor-pointer" onclick="document.getElementById('avatar-input').click()">
            <div class="rounded-full h-28 w-28 ring-4 ring-background-light dark:ring-surface-border shadow-lg bg-primary/10 text-primary flex items-center justify-center font-bold text-4xl relative overflow-hidden">
                <!-- Initials Fallback -->
                <span><?= strtoupper(substr($usuario['nombre'], 0, 2)) ?></span>
                
                <!-- Image Overlay -->
                <?php if ($usuario['imagen']): ?>
                    <div class="absolute inset-0 bg-center bg-no-repeat bg-cover" style='background-image: url("<?= base_url($usuario['imagen']) ?>");'></div>
                <?php endif; ?>

                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <span class="text-white text-xs font-bold uppercase tracking-widest">Cambiar</span>
                </div>
            </div>
            
            <div class="absolute bottom-0 right-0 bg-primary text-white p-1.5 rounded-full border-2 border-background-light dark:border-background-dark flex items-center justify-center shadow-sm z-10">
                <span class="material-symbols-outlined text-[16px]">photo_camera</span>
            </div>
        </div>

        <div class="flex flex-col items-center justify-center">
            <p class="text-[22px] font-bold leading-tight tracking-tight dark:text-white"><?= $usuario['nombre'] ?></p>
            <p class="text-text-secondary text-sm font-medium mt-1">
                <?php 
                    $roles = [1 => 'Administrador', 2 => 'Soporte', 3 => 'Cliente'];
                    echo $roles[$usuario['tipo_usuario_id']] ?? 'Usuario';
                ?>
            </p>
        </div>
    </div>

    <form action="<?= base_url('profile/update') ?>" method="POST" class="px-4 mt-2">
        <h3 class="text-text-secondary text-xs font-bold uppercase tracking-wider mb-2 ml-1">Información de la Cuenta</h3>
        <div class="flex flex-col gap-3">
            <div class="flex flex-col">
                <label class="text-sm font-medium leading-normal mb-1.5 ml-1 dark:text-white">Nombre Completo</label>
                <div class="relative">
                    <input name="nombre" class="w-full rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary border border-gray-200 dark:border-surface-border bg-white dark:bg-surface-dark h-12 px-4 text-base font-normal shadow-sm" value="<?= $usuario['nombre'] ?>" required/>
                    <span class="material-symbols-outlined absolute right-4 top-3 text-text-secondary pointer-events-none">person</span>
                </div>
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium leading-normal mb-1.5 ml-1 dark:text-white">Correo Electrónico</label>
                <div class="relative">
                    <input name="email" class="w-full rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary border border-gray-200 dark:border-surface-border bg-white dark:bg-surface-dark h-12 px-4 text-base font-normal shadow-sm" type="email" value="<?= $usuario['email'] ?>" required/>
                    <span class="material-symbols-outlined absolute right-4 top-3 text-text-secondary pointer-events-none">mail</span>
                </div>
            </div>
            
            <button type="submit" class="mt-2 w-full bg-primary hover:bg-blue-600 text-white font-bold h-12 rounded-lg transition-all shadow-md active:scale-[0.98]">
                Guardar Información
            </button>
        </div>
    </form>

    <div class="px-4 mt-8">
        <button type="button" onclick="togglePasswordForm()" class="flex w-full items-center justify-between p-4 bg-white dark:bg-surface-dark border border-gray-200 dark:border-surface-border rounded-lg hover:bg-gray-50 dark:hover:bg-surface-border/30 transition-colors group text-left">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-orange-500/10 text-orange-500">
                    <span class="material-symbols-outlined text-[20px]">lock</span>
                </div>
                <div>
                    <span class="text-base font-medium dark:text-white block">Seguridad</span>
                    <span class="text-xs text-text-secondary block">Cambiar contraseña</span>
                </div>
            </div>
            <span class="material-symbols-outlined text-text-secondary group-hover:text-primary transition-colors" id="pass-chevron">chevron_right</span>
        </button>

        <form id="password-form" action="<?= base_url('profile/updatePassword') ?>" method="POST" class="hidden mt-3 p-4 bg-slate-50 dark:bg-surface-dark/50 border border-slate-200 dark:border-surface-border rounded-xl space-y-4">
            <div class="flex flex-col">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Nueva Contraseña</label>
                <input type="password" name="password" class="w-full rounded-lg text-gray-900 focus:outline-0 border border-gray-200 h-11 px-4 text-sm" required>
            </div>
            <div class="flex flex-col">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Confirmar Contraseña</label>
                <input type="password" name="confirm_password" class="w-full rounded-lg text-gray-900 focus:outline-0 border border-gray-200 h-11 px-4 text-sm" required>
            </div>
            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold h-11 rounded-lg transition-all shadow-sm">
                Actualizar Contraseña
            </button>
        </form>
    </div>

    <div class="px-4 mt-8">
        <h3 class="text-text-secondary text-xs font-bold uppercase tracking-wider mb-2 ml-1">Escenarios de Trabajo</h3>
        <div class="p-4 bg-white dark:bg-surface-dark border border-gray-200 dark:border-surface-border rounded-lg shadow-sm">
            <form action="<?= base_url('profile/updateEscenarios') ?>" method="POST" class="space-y-4">
                <div class="space-y-2">
                    <?php if (empty($escenarios)): ?>
                        <p class="text-sm text-text-secondary italic">No hay escenarios configurados.</p>
                    <?php else: ?>
                        <?php foreach ($escenarios as $esc): ?>
                            <label class="flex items-center justify-between p-3 rounded-lg border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer group">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 text-primary">
                                        <span class="material-symbols-outlined text-[20px]">account_tree</span>
                                    </div>
                                    <span class="text-sm font-medium dark:text-white"><?= $esc['nombre'] ?></span>
                                </div>
                                <div class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="escenarios[]" value="<?= $esc['id'] ?>" class="sr-only peer" <?= $esc['activo'] == 1 ? 'checked' : '' ?>>
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if (!empty($escenarios)): ?>
                    <button type="submit" class="w-full bg-slate-800 dark:bg-primary hover:bg-slate-900 dark:hover:bg-blue-600 text-white font-bold h-11 rounded-xl transition-all shadow-sm flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">save</span>
                        Guardar Escenarios
                    </button>
                    <p class="text-[10px] text-center text-text-secondary mt-2">Determina qué tickets y datos serán visibles en tu sesión.</p>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="px-4 mt-8">
        <h3 class="text-text-secondary text-xs font-bold uppercase tracking-wider mb-2 ml-1">Personalización</h3>
        <button type="button" id="theme-toggle" onclick="toggleTheme(event)" class="flex w-full items-center justify-between p-4 bg-white dark:bg-surface-dark border border-gray-200 dark:border-surface-border rounded-lg hover:bg-gray-50 dark:hover:bg-surface-border/30 transition-colors group text-left shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 text-primary">
                    <span id="theme-toggle-dark-icon" class="hidden material-symbols-outlined text-[20px]">dark_mode</span>
                    <span id="theme-toggle-light-icon" class="hidden material-symbols-outlined text-[20px]">light_mode</span>
                </div>
                <div>
                    <span class="text-base font-medium dark:text-white block">Tema Visual</span>
                    <span class="text-xs text-text-secondary block">Alternar entre modo claro y oscuro</span>
                </div>
            </div>
            <span class="text-[10px] font-bold text-primary px-2 py-1 bg-primary/5 rounded-md uppercase tracking-wider border border-primary/10">Cambiar</span>
        </button>
    </div>
    <div class="px-4 mt-8">
        <div class="p-4 bg-white dark:bg-surface-dark border border-gray-200 dark:border-surface-border rounded-lg">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-500/10 text-blue-500">
                    <span class="material-symbols-outlined text-[20px]">notifications_active</span>
                </div>
                <div>
                    <span class="text-base font-medium dark:text-white block">Centro de Notificaciones</span>
                    <span class="text-xs text-text-secondary block">Configura las alertas push</span>
                </div>
            </div>

            <button type="button" onclick="PushHandler.requestSubscription()" class="w-full bg-primary hover:bg-blue-600 text-white font-bold h-11 rounded-lg transition-all shadow-sm flex items-center justify-center gap-2 mb-3">
                <span class="material-symbols-outlined text-[20px]">send</span>
                Activar Notificaciones
            </button>

            <button type="button" onclick="PushHandler.resetSystem()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold h-11 rounded-lg transition-all flex items-center justify-center gap-2 mb-3 text-sm">
                <span class="material-symbols-outlined text-[18px]">refresh</span>
                Resetear Sistema
            </button>

            <!-- Debug Console for Mobile -->
            <div class="mt-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1 ml-1">Estado del Sistema</p>
                <div id="push-debug-log" class="text-[10px] font-mono p-3 bg-slate-50 dark:bg-black/20 border border-slate-100 dark:border-slate-800 rounded-md max-h-32 overflow-y-auto text-slate-500 dark:text-slate-400 leading-tight">
                    Esperando interacción...
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 mt-10 mb-6">
        <a href="<?= base_url('logout') ?>" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold h-12 rounded-lg transition-all flex items-center justify-center gap-2 shadow-lg shadow-red-900/10">
            <span class="material-symbols-outlined text-[20px]">logout</span>
            Cerrar Sesión
        </a>
    </div>
</div>

<script>
function togglePasswordForm() {
    const form = document.getElementById('password-form');
    const chevron = document.getElementById('pass-chevron');
    form.classList.toggle('hidden');
    chevron.style.transform = form.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(90deg)';
}
</script>
