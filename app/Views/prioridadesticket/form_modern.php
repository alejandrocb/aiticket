<!-- prioridadesticket/form_modern.php -->
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

        <div class="mt-4">
            <button type="submit" class="w-full h-12 bg-primary hover:bg-blue-600 text-white font-bold rounded-lg shadow-lg flex items-center justify-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[20px]">save</span>
                <?= isset($item) ? 'Actualizar' : 'Crear' ?>
            </button>
        </div>
    </form>
</div>
