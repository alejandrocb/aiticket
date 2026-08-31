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
