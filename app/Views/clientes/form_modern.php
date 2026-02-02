<!-- clientes/form_modern.php -->
<div class="flex-1 flex flex-col gap-5 px-4 pt-5 pb-32">
    <div>
        <h2 class="text-[#111418] dark:text-white tracking-tight text-[22px] font-bold leading-tight"><?= isset($cliente) ? 'Editar Cliente' : 'Nuevo Cliente' ?></h2>
        <p class="text-[#9dabb9] text-sm mt-1">Complete la información del cliente.</p>
    </div>
    
    <form action="<?= isset($cliente) ? base_url('clientes/actualizar/'.$cliente['id']) : base_url('clientes/insertar') ?>" method="POST">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col gap-2">
                <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Nombre del Cliente</label>
                <input class="w-full rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] text-[#111418] dark:text-white h-14 px-4 text-base font-normal placeholder:text-[#9dabb9] focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" type="text" name="nombre" value="<?= isset($cliente) ? $cliente['nombre'] : '' ?>" required placeholder="Ej. Acme Corp"/>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Correo Electrónico</label>
                <input class="w-full rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] text-[#111418] dark:text-white h-14 px-4 text-base font-normal placeholder:text-[#9dabb9] focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" type="email" name="email" value="<?= isset($cliente) ? $cliente['email'] : '' ?>" required placeholder="contacto@empresa.com"/>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Teléfono</label>
                <input class="w-full rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] text-[#111418] dark:text-white h-14 px-4 text-base font-normal placeholder:text-[#9dabb9] focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" type="tel" name="telefono" value="<?= isset($cliente) ? $cliente['telefono'] : '' ?>" placeholder="+1 234 567 890"/>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Dirección</label>
                <textarea class="w-full rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] text-[#111418] dark:text-white min-h-[100px] p-4 text-base font-normal placeholder:text-[#9dabb9] focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors resize-none" name="direccion" placeholder="Dirección completa..."><?= isset($cliente) ? $cliente['direccion'] : '' ?></textarea>
            </div>

            <?php if (!isset($cliente)): ?>
            <div class="flex flex-col gap-2">
                <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Escenario</label>
                <div class="relative">
                    <select class="w-full appearance-none rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] text-[#111418] dark:text-white h-14 px-4 pr-10 text-base font-normal focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" name="escenario_id" required>
                        <option disabled="" selected="" value="">Seleccionar Escenario</option>
                        <?php if (isset($escenarios)): ?>
                            <?php foreach ($escenarios as $escenario): ?>
                                <option value="<?= $escenario['id'] ?>"><?= $escenario['nombre'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#9dabb9]">
                        <span class="material-symbols-outlined">keyboard_arrow_down</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="fixed bottom-0 left-0 right-0 p-4 bg-background-light dark:bg-background-dark border-t border-gray-200 dark:border-[#222a33] z-40 md:static md:bg-transparent md:border-none md:p-0 md:mt-4">
            <button type="submit" class="w-full h-12 bg-primary hover:bg-blue-600 text-white font-bold rounded-lg shadow-lg flex items-center justify-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[20px]">save</span>
                <?= isset($cliente) ? 'Guardar Cambios' : 'Crear Cliente' ?>
            </button>
        </div>
    </form>
</div>
