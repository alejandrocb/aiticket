<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Cómo llama cada instalación a las cosas.
 *
 * Las dos instalaciones salen del mismo código, así que lo que las distingue
 * va en configuración y no escrito en las vistas. En soporte una incidencia la
 * reporta un "Cliente"; en el Puesto de Mando la reporta un grupo de acción, y
 * llamarlo cliente sería incorrecto.
 *
 * Cada propiedad se sobrescribe desde `.env` con su nombre en minúsculas:
 *
 *     etiquetas.cliente  = 'Grupo actuante'
 *     etiquetas.clientes = 'Grupos actuantes'
 *     etiquetas.app      = 'Puesto de Mando'
 *
 * Los valores de aquí son los que se usan si `.env` no dice otra cosa, de modo
 * que una instalación que no configure nada sigue viéndose igual que siempre.
 *
 * En las vistas se leen con el ayudante `etiqueta()`:
 *
 *     <?= etiqueta('cliente') ?>
 */
class Etiquetas extends BaseConfig
{
    /**
     * Nombre de la aplicación. Aparece en la cabecera y en el lateral.
     */
    public string $app = 'Soporte';

    /**
     * Ruta del logotipo dentro de public/, por ejemplo 'images/logo.png'.
     *
     * Vacío significa que no hay logotipo y se muestra el nombre en texto, que
     * es como se ha visto siempre. En cuanto se deje el fichero y se apunte
     * aquí, aparece en la cabecera de la aplicación, en el acceso y en los
     * informes, sin tocar código.
     */
    public string $logo = '';

    /**
     * Variante del logotipo para el modo oscuro de la aplicación.
     *
     * Un logotipo pensado para fondo claro suele desaparecer sobre fondo
     * oscuro. Si se deja vacío se usa el mismo de arriba en ambos modos.
     *
     * En los informes impresos se usa siempre el claro: el papel es blanco.
     */
    public string $logoOscuro = '';

    /**
     * Quien reporta la incidencia, en singular. Se usa en etiquetas de
     * formulario y en cabeceras de tabla.
     */
    public string $cliente = 'Cliente';

    /**
     * El mismo concepto en plural: menú lateral, títulos de listado.
     */
    public string $clientes = 'Clientes';
}
