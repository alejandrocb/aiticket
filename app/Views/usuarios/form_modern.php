<!-- usuarios/form_modern.php -->
<div class="flex-1 flex flex-col gap-5 px-4 pt-5 pb-32">
    <div>
        <h2 class="text-[#111418] dark:text-white tracking-tight text-[22px] font-bold leading-tight"><?= isset($usuario) ? 'Editar Usuario' : 'Nuevo Usuario' ?></h2>
        <p class="text-[#9dabb9] text-sm mt-1">Complete la información del usuario.</p>
    </div>

    <form action="<?= isset($usuario) ? base_url('usuarios/'.$usuario['id']) : base_url('usuarios') ?>" method="POST" class="flex flex-col gap-4">
        <?php if(isset($usuario)): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="flex flex-col gap-2">
            <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Nombre</label>
            <input type="text" name="nombre" value="<?= isset($usuario) ? esc($usuario['nombre']) : '' ?>" class="form-input w-full rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] h-12 px-4 text-base focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" required>
        </div>

        <div class="flex flex-col gap-2">
            <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Email</label>
            <input type="email" name="email" value="<?= isset($usuario) ? esc($usuario['email']) : '' ?>" class="form-input w-full rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] h-12 px-4 text-base focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" required>
        </div>

        <div class="flex flex-col gap-2">
            <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Contraseña <?= isset($usuario) ? '<span class="text-xs text-gray-500 font-normal">(Dejar en blanco para mantener)</span>' : '' ?></label>
            <input type="password" name="password" class="form-input w-full rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] h-12 px-4 text-base focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" <?= isset($usuario) ? '' : 'required' ?>>
        </div>

        <div class="flex flex-col gap-2">
            <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Rol</label>
            <div class="relative">
                <select name="tipo_usuario_id" class="w-full appearance-none rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] h-12 px-4 pr-10 text-base focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" required>
                    <option value="" disabled selected>Seleccione un rol</option>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?= $rol['id'] ?>" <?= (isset($usuario) && $usuario['tipo_usuario_id'] == $rol['id']) ? 'selected' : '' ?>><?= esc($rol['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="flex flex-col gap-3 mt-2">
            <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Escenarios de Trabajo</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <?php foreach ($escenarios as $esc): ?>
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] hover:bg-gray-50 dark:hover:bg-[#2c3b4a] transition-colors cursor-pointer group">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="escenarios[]" value="<?= $esc['id'] ?>" 
                                class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary dark:bg-slate-900 dark:border-slate-700"
                                <?= (isset($esc['activo']) && $esc['activo'] == 1) ? 'checked' : '' ?>>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900 dark:text-white"><?= esc($esc['nombre']) ?></span>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
            <?php if (empty($escenarios)): ?>
                <p class="text-xs text-text-secondary italic">No hay escenarios creados en el sistema.</p>
            <?php endif; ?>
        </div>

        <div class="mt-4">
            <button type="submit" class="w-full h-12 bg-primary hover:bg-blue-600 text-white font-bold rounded-lg shadow-lg flex items-center justify-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[20px]">save</span>
                <?= isset($usuario) ? 'Actualizar Usuario' : 'Crear Usuario' ?>
            </button>
        </div>
    </form>
</div>
