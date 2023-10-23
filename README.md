Manual de Despliegue en AWS para Abuelitas 😊
Parte 1: Preparativos
1.	Tener una taza de café o té a mano. Este es un proceso que requiere paciencia, ¡así que vamos a empezar bien!
Parte 2: Desplegando el Backend en Amazon EC2
Paso 1: Iniciar sesión en AWS
1.	Ve a https://aws.amazon.com/es/.
2.	Haz clic en "Iniciar sesión en la consola".
3.	Ingresa tu correo y contraseña.
Paso 2: Crear una instancia EC2 para el backend
1.	En el panel de AWS, busca "EC2" y haz clic en él.
2.	Haz clic en "Lanzar instancia".
3.	Elige "Amazon Linux 2 AMI" que ya tiene soporte para PHP.
4.	Elige el tipo de instancia (t2.micro debería estar bien para empezar, ¡y es gratis!).
5.	Haz clic en "Revisar y lanzar" y luego en "Lanzar".
Paso 3: Conectar y Configurar la instancia
1.	Una vez que la instancia esté en marcha, descarga la llave (un archivo .pem) que te permitirá conectarte tu usuario será “ec2-user”.
2.	Abre tu terminal o Command Prompt.
3.	Usa el comando ssh y la dirección de tu instancia para conectarte.
4.	Una vez conectado, instala un servidor web y PHP:
sudo yum update -y 
sudo yum install -y httpd php 
sudo yum install php-pgsql
5.	Inicia el servidor web:
sudo service httpd start 
Paso 4: Subir el Backend (PHP)
1.	Copia los archivos PHP a tu instancia usando scp (asegúrate de estar en la misma carpeta que tu archivo .pem):
scp -i "tuarchivo.pem" ruta/del/archivo/api.php ec2-user@direccion-de-tu-instancia:/var/www/html/ 
si no funciona usa un cliente ftp “te recomiendo usa filezilla” y antes ejecuta este comendo:

sudo chown -R ec2-user:ec2-user /var/www/html
Paso 5: Configurar seguridad
1.	Regresa a la consola de EC2.
2.	Selecciona tu instancia.
3.	En el panel inferior, busca el grupo de seguridad vinculado y haz clic en él.
4.	Agrega una regla para permitir el tráfico HTTP (puerto 80).
5.	Tu url quedaría algo asi : http://34.203.197.208/api.php
Parte 3: Desplegando el Frontend en Amazon S3
Paso 1: Crear un bucket en S3
1.	En el panel de AWS, busca "S3" y haz clic en él.
2.	Haz clic en "Crear bucket".
3.	Da un nombre a tu bucket, algo como "frontend-decameron".
4.	Haz clic en "Crear".
Paso 2: Subir archivos de Frontend
1.	Una vez creado, haz clic en tu bucket.
2.	Haz clic en "Subir" y selecciona tu archivo HTML.
3.	Una vez cargados, selecciona cada archivo, haz clic en "Acciones" y elige "Hacer público".
Paso 3: Habilitar el alojamiento de sitios web
1.	Con tu bucket seleccionado, ve a la pestaña "Propiedades".
2.	Haz clic en "Alojamiento de sitios web estáticos".
3.	Elige "Usar este bucket para alojar un sitio web" y coloca el nombre de tu archivo principal, como index.html.
4.	En las políticas pon el siguiente Json:

{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Sid": "PublicRead",
            "Effect": "Allow",
            "Principal": "*",
            "Action": "s3:GetObject",
            "Resource": "arn:aws:s3:::frontend-decameron/*"
        }
    ]
}
5.	¡Toma nota del enlace de endpoint! Esa es la URL de tu frontend, en este case es: http://frontend-decameron.s3-website-us-east-1.amazonaws.com/

Parte 4: Desplegando la Base de Datos en Amazon RDS
Paso 1: Crear una instancia de base de datos en RDS
1.	En el panel de AWS, busca "RDS" y haz clic en él.
2.	Haz clic en "Crear instancia de base de datos".
3.	Selecciona "PostgreSQL".
4.	Haz clic en "Configuración de base de datos". Aquí:
•	Da un nombre identificable a tu instancia, como "decameron".
•	Establece un nombre de usuario y una contraseña que recordarás (anótalos).
5.	Haz clic en "Crear instancia de base de datos".
Paso 2: Configurar seguridad para RDS
1.	Una vez creada tu instancia de RDS, haz clic en ella.
2.	En el panel inferior, busca el grupo de seguridad vinculado y haz clic en él.
3.	Agrega una regla para permitir el tráfico PostgreSQL (puerto 5432) desde la dirección IP de tu instancia EC2 (esto es para que tu backend pueda comunicarse con la base de datos).
Paso 3: Conectarse y configurar la base de datos
1.	Desde tu instancia EC2 (donde está tu backend), instala un cliente PostgreSQLbo también puedes descargar (ems postgresql client):
sudo yum install -y https://download.postgresql.org/pub/repos/yum/12/redhat/rhel-7-x86_64/pgdg-redhat-repo-latest.noarch.rpm
sudo yum install -y postgresql 
2.	Conéctate a tu base de datos:
psql -h [direccion-de-tu-instancia-rds] -U [nombre-de-usuario] [nombre-de-la-base-de-datos] 
•	Es posible que te pida la contraseña que configuraste antes.
3.	Una vez conectado, puedes ejecutar comandos SQL o ejecutar el script sql.txt para configurar tu base de datos, como crear tablas.
Paso 4: Actualizar tu Backend
Asegúrate de que tu archivo api.php se conecte a la dirección correcta de RDS y utilice el nombre de usuario y contraseña adecuados. Así tu backend podrá comunicarse con la base de datos.
Parte 5: ¡Todo listo!
Con tu frontend en S3, tu backend en EC2 y tu base de datos en RDS, ¡todo debería funcionar a la perfección! Si tienes algún problema, recuerda: todo tiene solución con un poco de paciencia y amor.
¡Bravo, abuelita! Eres una experta en despliegues tecnológicos. ¡Ahora a disfrutar de tu aplicación en línea! 💖

