<!-- tiposticket/form_modern.php -->
<div class="flex-1 flex flex-col gap-5 px-4 pt-5 pb-32">
    <div>
        <h2 class="text-[#111418] dark:text-white tracking-tight text-[22px] font-bold leading-tight"><?= isset($item) ? 'Editar '.$singular_name : 'Crear '.$singular_name ?></h2>
        <p class="text-[#9dabb9] text-sm mt-1">Complete la información.</p>
    </div>

    <form action="<?= isset($item) ? base_url($resource_name.'/'.$item['id']) : base_url($resource_name) ?>" method="POST" class="flex flex-col gap-4">
        <?php if(isset($item)): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="flex flex-col gap-2">
            <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Nombre</label>
            <input type="text" name="nombre" value="<?= isset($item) ? esc($item['nombre']) : '' ?>" class="form-input w-full rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] h-12 px-4 text-base focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" required>
        </div>

        <div class="flex flex-col gap-2">
            <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Cliente</label>
            <div class="relative">
                <select name="cliente_id" class="w-full appearance-none rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] text-[#111418] dark:text-white h-12 px-4 pr-10 text-base focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors">
                    <option value="">Global — disponible para todos</option>
                    <?php foreach (($clientes ?? []) as $cliente): ?>
                        <option value="<?= $cliente['id'] ?>" <?= (isset($item) && $item['cliente_id'] == $cliente['id']) ? 'selected' : '' ?>><?= esc($cliente['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#9dabb9]">
                    <span class="material-symbols-outlined">keyboard_arrow_down</span>
                </div>
            </div>
            <p class="text-[#9dabb9] text-xs">Si se asigna a un cliente, este tipo solo aparecerá al crear incidencias suyas. Dejándolo en global, aparece siempre.</p>
        </div>

        <div class="mt-4">
            <button type="submit" class="w-full h-12 bg-primary hover:bg-blue-600 text-white font-bold rounded-lg shadow-lg flex items-center justify-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[20px]">save</span>
                <?= isset($item) ? 'Actualizar' : 'Crear' ?>
            </button>
        </div>
    </form>
</div>
