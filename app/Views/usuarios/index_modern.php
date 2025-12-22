<!-- usuarios/index_modern.php -->
<div class="flex-1 overflow-y-auto no-scrollbar bg-background-light dark:bg-background-dark pb-24">
    <div class="sticky top-0 z-30 bg-background-light/95 dark:bg-background-dark/95 backdrop-blur-sm border-b border-gray-200 dark:border-gray-800">
        <div class="px-4 py-3 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Gestión de Usuarios</h1>
            <a href="<?= base_url('usuarios/new') ?>" class="bg-primary hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">add</span>
                Nuevo Usuario
            </a>
        </div>
    </div>

    <div class="p-4 flex flex-col gap-3">
        <?php if (!empty($usuarios)): ?>
            <div class="bg-white dark:bg-surface-dark rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-800 text-xs uppercase font-medium text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3">Nombre</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Rol</th>
                            <th class="px-6 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"><?= esc($usuario['nombre']) ?></td>
                                <td class="px-6 py-4"><?= esc($usuario['email']) ?></td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        <?= esc($usuario['rol_nombre']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    <a href="<?= base_url('usuarios/'.$usuario['id'].'/edit') ?>" class="text-gray-400 hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                    <!-- Delete Form (Optional confirmation in JS) -->
                                    <form action="<?= base_url('usuarios/'.$usuario['id']) ?>" method="POST" onsubmit="return confirm('¿Estás seguro?');" class="inline">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <p class="text-gray-500">No hay usuarios registrados.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
