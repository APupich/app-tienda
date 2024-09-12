Este es el sistema de control de la empresa Morphyx

Backlog
=======

-El constructor del motor de plantillas debe ejecutar loadTPL, quitar loadTPL de los controladores

-Los controladores deben cargar {{PROJECT_SECTION}} con el nombre de la vista que cargan.

-El motor de plantillas debe validar y cargar solo las variables de proyecto que existan sin lanzar error si no estan.

-El motor de plantillas debe poder cargar externs dentro de externs (cuando se cargue un extern se debe analizar ese nuevo recursividad)

-Las urls de los links y redirecciones deben usar la variable de entorno $_ENV["PROJECT_URL"]

-Las variables de sesion deben estar ligadas al nombre del proyecto en env, colocar el reemplazo en los controladores y en los modelos.

-El email de validar usuario debe cargarse desde una plantilla utilizando el motor de plantillas pero sin imprimir en pantalla.

-El email de validar usuario debe trabajar con variables del motor de plantillas PROJECT para que sea generico.

-Evitar que inicie sesión al registrarse (debe haber validado el email)

-Bloquear el login si el usuario aún no valido el email (store procedure).

-Crear el método verify dentro de User.php para activar el usuario que valido su email, poner active en 1 y borrar el token de email.

-La vista verifyView.html debe mostrar un mensaje de verificación valida y el botón para ir a loguearse o directamente al presionarlo que logue (llevar a perfil)

-Evitar que se pueda enviar emails desde la api (bloquear el modelo)

