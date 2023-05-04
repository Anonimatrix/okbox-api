# OkBox Api

## Sobre el proyecto

Este proyecto agrupa la informacion de diferentes apis en una. Los principales medios de obtencion de estos datos son:

* [Wordpress](#wordpress)
* [Space Manager](#space-manager)
* [Google Analytics](#google-analytics)
* [Google Ads](#google-ads)
* [Google Search Console](#google-search-console)

## Arquitectura del proyecto

### Autenticacion

Primero, comentar el sistema de autenticacion, aunque tratare de ser breve debido a que es un sistema sumamente basico.
Este esta basado en tokens que se deben pasar en la cabezera *Authorization* en cada peticion a un endpoint que lo requiera. El middleware encargado de verificar la autenticidad de tokens es por parte de [Laravel Sanctum](https://laravel.com/docs/10.x/sanctum), puede averiguar mas sobre este en su documentacion.
Luego la implementacion del los metodos basicos para poder crear este token(login), registrar usuarios, verificar su email, resetear password, esta dentro de la carpeta **Controllers>Auth**. No profundizare mucho en el codigo de estos, porque como ya comente, es una implementacion muy basica y la puede encontrar en la documentacion de *Laravel Sanctum*

### Flujo de cada endpoint

Todos los metodos actuales estan dentro de un unico controlador: **AnalyticsController**, este esta encargado de ejecutar los servicios que tienen la logica abstraida de cada proceso. Estos servicios se cargan a traves de injeccion de dependencias en el metodo.

## Apis y centros de informacion

### Wordpress

### Space Manager

### Google Analytics

### Google Ads

### Google Search Console
