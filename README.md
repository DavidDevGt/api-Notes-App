# Mi Proyecto de API de Notas

## Descripción

Este proyecto es una API desarrollada en PHP que permite manejar operaciones CRUD para dos entidades principales: usuarios y notas. La API permite crear, leer, actualizar y eliminar tanto usuarios como notas, y utiliza JSON Web Tokens (JWT) para autenticar las solicitudes a los endpoints.

## Estructura del Proyecto

El proyecto está estructurado en varios directorios y archivos que separan la lógica de la aplicación, los modelos de datos y los controladores.

### Directorios Principales

- `controllers`: Contiene los controladores para las entidades de usuario y nota.
- `models`: Alberga los modelos de datos para usuario y nota.
- `middleware`: Incluye el middleware para manejar la autenticación a través de JWT.
- `utils`: Contiene utilidades como el manejador de JWT.
- `config`: Incluye configuraciones, como la configuración de la base de datos.

## Autenticación

La API utiliza JWT para autenticar las solicitudes. Los tokens deben ser proporcionados en el encabezado de autorización de las solicitudes HTTP.

## Contribuir

Para contribuir al proyecto, por favor crea una Pull Request con los cambios propuestos.

## Licencia

Este proyecto está bajo la licencia MIT. Ver el archivo [LICENSE.md](LICENSE.md) para más detalles.
