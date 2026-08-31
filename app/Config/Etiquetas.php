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
     * Quien reporta la incidencia, en singular. Se usa en etiquetas de
     * formulario y en cabeceras de tabla.
     */
    public string $cliente = 'Cliente';

    /**
     * El mismo concepto en plural: menú lateral, títulos de listado.
     */
    public string $clientes = 'Clientes';
}
