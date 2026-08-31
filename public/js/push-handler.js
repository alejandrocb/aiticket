/**
 * Suscripción a las notificaciones push del navegador.
 *
 * La plantilla llama a PushHandler.init() con la clave pública VAPID. Desde
 * ahí: registra el service worker, pide permiso al usuario, crea la
 * suscripción y la manda a PushController::subscribe, que la guarda en
 * push_subscriptions. Sin esa fila no hay a quién enviar, así que este
 * fichero es el primer eslabón de toda la cadena.
 *
 * Vive en public/js/ y no en public/assets/, que está en .gitignore: la
 * versión anterior se guardaba ahí y por eso nunca llegó a los despliegues.
 *
 * Requiere contexto seguro (https o localhost). En http el navegador no
 * expone serviceWorker y no hay nada que hacer desde el código.
 */
window.PushHandler = (function () {
    'use strict';

    var RUTA_SW = 'sw.js';
    var RUTA_SUSCRIPCION = 'push/subscribe';

    function log(mensaje) {
        if (window.console && console.log) {
            console.log('[Push] ' + mensaje);
        }
    }

    /**
     * La clave VAPID viaja en base64url y applicationServerKey la espera como
     * Uint8Array. Hay que reponer el relleno y cambiar los dos caracteres que
     * base64url sustituye.
     */
    function claveAUint8Array(base64UrlSinRelleno) {
        var relleno = '='.repeat((4 - (base64UrlSinRelleno.length % 4)) % 4);
        var base64 = (base64UrlSinRelleno + relleno).replace(/-/g, '+').replace(/_/g, '/');
        var crudo = window.atob(base64);
        var salida = new Uint8Array(crudo.length);

        for (var i = 0; i < crudo.length; i++) {
            salida[i] = crudo.charCodeAt(i);
        }

        return salida;
    }

    function enviarAlServidor(suscripcion, baseUrl) {
        return fetch(baseUrl + RUTA_SUSCRIPCION, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            // La ruta está tras el filtro de autenticación: sin la cookie de
            // sesión responde con una redirección al login.
            credentials: 'same-origin',
            body: JSON.stringify(suscripcion)
        }).then(function (respuesta) {
            if (!respuesta.ok) {
                throw new Error('El servidor respondió ' + respuesta.status);
            }
            return respuesta.json();
        }).then(function (datos) {
            if (datos && datos.success) {
                log('Suscripción registrada.');
            } else {
                log('El servidor rechazó la suscripción: ' + (datos && datos.message));
            }
        });
    }

    function init(clavePublicaVapid, baseUrl) {
        if (!clavePublicaVapid) {
            log('No hay clave VAPID configurada. Revisa VAPID_PUBLIC_KEY en el .env.');
            return;
        }

        if (!('serviceWorker' in navigator)) {
            log('El navegador no admite service workers, o la página no se sirve por https.');
            return;
        }

        if (!('PushManager' in window)) {
            log('El navegador no admite notificaciones push.');
            return;
        }

        navigator.serviceWorker.register(baseUrl + RUTA_SW)
            .then(function (registro) {
                log('Service worker registrado.');
                // Si ya está activo se resuelve de inmediato; si acaba de
                // instalarse, espera a que lo esté antes de suscribir.
                return navigator.serviceWorker.ready.then(function () {
                    return registro;
                });
            })
            .then(function (registro) {
                return Notification.requestPermission().then(function (permiso) {
                    if (permiso !== 'granted') {
                        throw new Error('El usuario no concedió permiso (' + permiso + ')');
                    }
                    return registro;
                });
            })
            .then(function (registro) {
                return registro.pushManager.getSubscription().then(function (existente) {
                    if (existente) {
                        log('Ya existía una suscripción; se reenvía por si el servidor la perdió.');
                        return existente;
                    }

                    return registro.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: claveAUint8Array(clavePublicaVapid)
                    });
                });
            })
            .then(function (suscripcion) {
                return enviarAlServidor(suscripcion.toJSON(), baseUrl);
            })
            .catch(function (error) {
                log('No se pudo activar el push: ' + error.message);
            });
    }

    return { init: init, log: log };
})();
