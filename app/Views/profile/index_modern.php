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
