<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (! function_exists('etiqueta')) {
    /**
     * Devuelve cómo llama esta instalación a algo.
     *
     * Los textos viven en Config\Etiquetas y se sobrescriben desde `.env`, de
     * forma que las dos instalaciones comparten el mismo código pero cada una
     * usa su vocabulario. Ver app/Config/Etiquetas.php.
     *
     *     <?= etiqueta('cliente') ?>   // 'Cliente' o 'Grupo actuante'
     *
     * Si la clave no existe se devuelve tal cual, para que un error de
     * escritura se vea en pantalla en lugar de dejar un hueco en blanco.
     */
    function etiqueta(string $clave): string
    {
        static $etiquetas = null;

        if ($etiquetas === null) {
            $etiquetas = config('Etiquetas');
        }

        return $etiquetas->{$clave} ?? $clave;
    }
}

if (! function_exists('marca')) {
    /**
     * Pinta el logotipo de la instalación, o su nombre en texto si todavía no
     * hay fichero de logotipo configurado.
     *
     * Se emiten las dos variantes —clara y oscura— y se deja que el CSS enseñe
     * la que toque, porque el tema se decide en el navegador antes de que
     * cargue nada. Si no hay variante oscura configurada, se usa la clara en
     * los dos modos.
     *
     * @param string $clasesImg   Alto del logotipo, p. ej. 'h-9'
     * @param string $clasesTexto Estilo del nombre cuando no hay logotipo
     * @param bool   $paraImprimir Fuerza la variante clara: el papel es blanco
     */
    function marca(string $clasesImg = 'h-9', string $clasesTexto = '', bool $paraImprimir = false): string
    {
        $claro  = etiqueta('logo');
        $nombre = etiqueta('app');

        if ($claro === '') {
            return '<span class="' . $clasesTexto . '">' . esc($nombre) . '</span>';
        }

        $alt = ' alt="' . esc($nombre, 'attr') . '"';

        if ($paraImprimir) {
            return '<img src="' . base_url($claro) . '" class="' . $clasesImg . '"' . $alt . '>';
        }

        $oscuro = etiqueta('logoOscuro');

        // Sin variante oscura configurada se reutiliza la clara y se pasa a
        // blanco por CSS: brightness(0) la vuelve negra conservando la
        // transparencia, e invert(1) la deja en blanco. Pierde el color de
        // marca, pero un logotipo de trazo negro sobre fondo oscuro sería
        // sencillamente invisible. En cuanto se configure logoOscuro, manda
        // el fichero y no se toca nada.
        if ($oscuro === '') {
            return '<img src="' . base_url($claro) . '" class="' . $clasesImg . ' dark:brightness-0 dark:invert"' . $alt . '>';
        }

        return '<img src="' . base_url($claro) . '" class="' . $clasesImg . ' dark:hidden"' . $alt . '>'
             . '<img src="' . base_url($oscuro) . '" class="' . $clasesImg . ' hidden dark:block"' . $alt . '>';
    }
}
