<!-- recurrentes/form_modern.php -->
<div class="flex-1 flex flex-col gap-5 px-4 pt-5 pb-32">
    <div>
        <h2 class="text-[#111418] dark:text-white tracking-tight text-[22px] font-bold leading-tight"><?= isset($ticket) ? 'Editar Recurrencia' : 'Nueva Recurrencia' ?></h2>
        <p class="text-[#9dabb9] text-sm mt-1">Configure la creación automática de tickets.</p>
    </div>
    
    <form action="<?= isset($ticket) ? base_url('recurrentes/actualizar/'.$ticket['id']) : base_url('recurrentes/insertar') ?>" method="POST">
        <div class="flex flex-col gap-4">
            
            <!-- Standard Ticket Fields -->
             <div class="flex flex-col gap-2">
                <label class="text-[#111418] dark:text-white text-base font-medium leading-normal"><?= etiqueta('cliente') ?></label>
                <div class="relative">
                    <select class="w-full appearance-none rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] text-[#111418] dark:text-white h-14 px-4 pr-10 text-base font-normal focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" name="cliente_id" required>
                        <option disabled="" selected="" value="">Seleccionar <?= etiqueta('cliente') ?></option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= $cliente['id'] ?>" <?= (isset($ticket) && $ticket['cliente_id'] == $cliente['id']) ? 'selected' : '' ?>><?= $cliente['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#9dabb9]">
                        <span class="material-symbols-outlined">keyboard_arrow_down</span>
                    </div>
                </div>
            </div>

             <div class="flex flex-col gap-2">
                <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Responsable</label>
                <div class="relative">
                    <select class="w-full appearance-none rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] text-[#111418] dark:text-white h-14 px-4 pr-10 text-base font-normal focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors" name="responsable_id">
                        <option value="">Asignar a...</option>
                        <?php foreach ($usuarios as $usuario): ?>
                             <?php if (in_array($usuario['rol_id'], [1, 2])): ?>
                                <option value="<?= $usuario['id'] ?>" <?= (isset($ticket) && $ticket['responsable_id'] == $usuario['id']) ? 'selected' : '' ?>><?= $usuario['nombre'] ?></option>
                             <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#9dabb9]">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                </div>
            </div>

            <!-- Recurrence Config -->
            <div class="p-4 bg-gray-50 dark:bg-[#1c2127] rounded-lg border border-gray-200 dark:border-[#3b4754]">
                <h3 class="text-sm font-bold uppercase text-gray-500 mb-3">Configuración de Frecuencia</h3>
                <div class="flex flex-col gap-3">
                     <div class="flex flex-col gap-2">
                        <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Frecuencia</label>
                        <select class="w-full h-12 rounded-lg border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#101922] text-[#111418] dark:text-white px-3" name="frecuencia" id="frecuencia" required>
                            <option value="diaria" <?= (isset($ticket) && $ticket['frecuencia'] == 'diaria') ? 'selected' : '' ?>>Diaria</option>
                            <option value="semanal" <?= (isset($ticket) && $ticket['frecuencia'] == 'semanal') ? 'selected' : '' ?>>Semanal</option>
                            <option value="mensual" <?= (isset($ticket) && $ticket['frecuencia'] == 'mensual') ? 'selected' : '' ?>>Mensual</option>
                            <option value="anual" <?= (isset($ticket) && $ticket['frecuencia'] == 'anual') ? 'selected' : '' ?>>Anual</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-2" id="dia_semana_container">
                        <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Día de la Semana</label>
                        <select class="w-full h-12 rounded-lg border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#101922] text-[#111418] dark:text-white px-3" name="dia_semana">
                             <?php $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']; ?>
                             <?php foreach ($dias as $dia): ?>
                                <option value="<?= $dia ?>" <?= (isset($ticket) && $ticket['dia_semana'] == $dia) ? 'selected' : '' ?>><?= $dia ?></option>
                             <?php endforeach; ?>
                        </select>
                    </div>

                     <div class="flex flex-col gap-2" id="dia_mes_container">
                        <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Día del Mes</label>
                        <input class="w-full h-12 rounded-lg border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#101922] text-[#111418] dark:text-white px-3" type="number" min="1" max="31" name="dia_mes" value="<?= isset($ticket) ? $ticket['dia_mes'] : '1' ?>"/>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Tipo y Prioridad</label>
                <div class="grid grid-cols-2 gap-2">
                     <select class="w-full h-12 rounded-lg border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] text-[#111418] dark:text-white px-3" name="tipo_ticket_id" required>
                         <?php foreach ($tipos as $tipo): ?>
                            <option value="<?= $tipo['id'] ?>" <?= (isset($ticket) && $ticket['tipo_ticket_id'] == $tipo['id']) ? 'selected' : '' ?>><?= $tipo['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select class="w-full h-12 rounded-lg border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] text-[#111418] dark:text-white px-3" name="prioridad_ticket_id" required>
                         <?php foreach ($prioridades as $prioridad): ?>
                            <option value="<?= $prioridad['id'] ?>" <?= (isset($ticket) && $ticket['prioridad_ticket_id'] == $prioridad['id']) ? 'selected' : '' ?>><?= $prioridad['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <!-- Default state usually 'New' for recurrences -->
                    <input type="hidden" name="estado_ticket_id" value="1"> 
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[#111418] dark:text-white text-base font-medium leading-normal">Asunto / Descripción</label>
                <textarea class="w-full rounded-lg border border-gray-300 dark:border-[#3b4754] bg-white dark:bg-[#1c2127] text-[#111418] dark:text-white min-h-[100px] p-4 text-base font-normal placeholder:text-[#9dabb9] focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-colors resize-none" name="descripcion" placeholder="Descripción del ticket generado..."><?= isset($ticket) ? $ticket['descripcion'] : '' ?></textarea>
            </div>
        </div>

        <div class="fixed bottom-0 left-0 right-0 p-4 bg-background-light dark:bg-background-dark border-t border-gray-200 dark:border-[#222a33] z-40 md:static md:bg-transparent md:border-none md:p-0 md:mt-4">
            <button type="submit" class="w-full h-12 bg-primary hover:bg-blue-600 text-white font-bold rounded-lg shadow-lg flex items-center justify-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[20px]">update</span>
                <?= isset($ticket) ? 'Actualizar Recurrencia' : 'Programar Recurrencia' ?>
            </button>
        </div>
    </form>
</div>
