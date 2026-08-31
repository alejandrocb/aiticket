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
     * Nombre corto para debajo del icono en la pantalla de inicio.
     *
     * Android muestra ahí muy poco texto. Si se deja vacío se usa el nombre
     * completo y es el sistema quien lo abrevia con puntos suspensivos, que
     * queda mejor que cortarlo por donde caiga: 'Puesto de Mando' recortado a
     * doce caracteres se convierte en 'Puesto de Ma'.
     *
     * Conviene que no pase de unos 12 caracteres. Por ejemplo: 'PMA'.
     */
    public string $appCorto = '';

    /**
     * Icono cuadrado de la aplicación instalada, dentro de public/.
     *
     * Debe ser PNG de 512x512, opaco y a sangre —sin esquinas redondeadas
     * propias— porque Android le aplica su propia máscara. Y todo lo
     * importante dentro del 80% central, o el recorte circular se lo come.
     *
     * Vacío significa que el manifiesto no declara iconos y el navegador no
     * podrá ofrecer instalar la aplicación, solo un acceso directo.
     */
    public string $icono = '';

    /**
     * Pictograma del aviso push, dentro de public/.
     *
     * PNG de 96x96, forma blanca sobre fondo transparente. Android lo pinta
     * como silueta en la barra de estado: descarta el color y solo usa la
     * transparencia, así que un fondo blanco se vería como un cuadrado.
     */
    public string $badge = '';

    /**
     * Color de acento de la instalación, en hexadecimal.
     *
     * Lo usan la barra del navegador en móvil y el manifiesto de la PWA. Es
     * solo el color del sistema operativo: la paleta de la interfaz se define
     * en public/css/src/tailwind-input.css y hay que recompilar para cambiarla.
     */
    public string $colorTema = '#137fec';

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
