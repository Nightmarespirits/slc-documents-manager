
# Contribuir con el proyecto

Proyecto estudiantil de un Sistema para la Subida, Carga, Descarga de Documentos


## Instalacion
Para instalar el proyecto es preferible tener un servidor como XAMPP o WAMP 

### Pasos para instalar en modo desarrollo usando XAMPP

* Descarga la repo de github como ZIP.
* Descomprime el repositorio y colocalo en la carpeta htdocs de XAMPP (Generalmente la ruta es C:\xampp\htdocs).
* Inicializa Apache y MySQL en XAMPP y accede al Admin de este ultimo.
#### Ahora Tenemos que crear la Base de Datos MySQL para que el programa funcione correctamente.

* En la interfaz de php My Admin crea una nueva Base de datos (En la parte Izquierda presiona: Nueva]) , Asignale un nombre y presiona el boton Crear.

* Ahora Presiona la opcion: SQL y copia el script que esta en la carpeta `config/script.sql` del proyecto.

* Una vez que ejecutes el script y no te salguen errores ya tendras la base de datos lista para conectar.

#### Ahora tenemos que configurar las Variables de Entorno para que nuestro programa se conecte a la base de datos
* Las variables de entorno estan definidas en la carpeta /config/database.php si no encuentras la carpeta o archivo creala en la raiz del proyecto

* Luego solo copia pega y CAMBIA el nombre de la base de datos con la que creaste, tambien si configuraste contraseña en XAMPP en el codigo:

```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', '[BaseDeDatos]');

```
* Con Esto ya deberian quedar configuradas las variables de entorno.

#### El ultimo paso es Acceder a la aplicacion web para ello:
* Cambia la ruta de http://localhost/phpmyadmin/  a http://localhost/slc-documents-manager/

*  Deberas ver la intefaz de inicio de sesion.
*  Las credenciales de prueba por defecto son super | super123


## 🚀 ¿Listo para Contribuir?

¡Cualquier contribución es más que bienvenida!

**¡Tu aportación puede hacer la diferencia!** Ya sea que tengas una gran idea, un pequeño ajuste o una corrección, cada contribución cuenta y es valiosa para nuestra comunidad. 

### 💪 Suma Nuevos Cambios

- **Comparte tu creatividad**: Si tienes nuevas ideas, características o mejoras, ¡no dudes en compartirlas!
- **Ayuda a mejorar**: Tu feedback y sugerencias son cruciales para hacer de este proyecto algo aún mejor.
- **Participa en la conversación**: Comenta en las issues, abre nuevas, y colabora con otros contribuyentes.

**Recuerda**: Por favor, asegúrate de adherirte al [`Código de Conducta`](link_al_codigo_de_conducta) de este proyecto. Respetemos a todos los miembros de nuestra comunidad y trabajamos juntos para crear un ambiente positivo y acogedor.

¡Muestranos lo que puedes aportar! 🎉
