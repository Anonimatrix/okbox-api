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

Esta api utiliza otras varias para poder recopilar la informacion. A continuacion se explica cada una:

### Wordpress

Este consumo es de los mas sencillos. Ya que unicamente utiliza un servicio generico **GenericService** que hace una llamada a los endpoints de wordpress de produccion o desarrollo, configurada en *config/apis* dependiendo de la variable de entorno *APP_ENV* en el .env. Otra variable necesaria es *DEV_API_TOKEN_LEADS* para desarrollo y *PROD_API_TOKEN_LEADS* para produccion. Luego de eso el parseo se hace en WpResource, pero es minimo.

### Space Manager

Aunque su consumo es un poco mas complejo que en wordpress, porque este consulta a varios endpoints, recorriendo todos los centros y obteniendo la data para cada uno y luego parseandola en *SpManagerResource*

### Google Analytics

En este endpoint se utiliza la libreria **google/analytics-data** diseñada para consumir datos de analytics. Para la autenticacion se usa una cuenta de servicio que utiliza las credenciales almacenadas en la carpeta **credentials** y la ubicacion de ese json con las crendenciales se determinan con la variable de entorno *GOOGLE_APPLICATION_CREDENTIALS*. En esta se obtienen datos de las propiedades establecidas en configuracion *apis.analytics.properties* y para no tener lentitud en la carga de la informacion en los endpoints de google se utilizan ya paginas, para cargar por chunks de propiedades. La data devuelta sera la de las fechas establecidas en los parametros *start_date*, *end_date*.

### Google Ads

En este endpoint se utiliza la libreria **googleads/google-ads-php** diseñada para consumir datos de google ads. Y al igual que en la anterior usa una cuenta de servicio con credenciales en la direccion establecida en *GOOGLE_APPLICATION_CREDENTIALS* y "haciendose pasar por la cuenta" *jfs@ndodigital.com* y con el developer key en la variable de entorno *GOOGLE_ADS_DEVELOPER_KEY*. En esta se obtienen datos de las customers_ids establecidos en configuracion *apis.google-ads.customer_ids* e igualmente que en google analytics utiliza paginacion establecida por los parametros *page*, *per_page*, aparte de fechas establecidas por *start_date*, *end_date*.

### Google Search Console

En este endpoint se utiliza la libreria **google/apiclient** diseñada para consumir datos de varios servicios de google. Y al igual que en la anterior usa una cuenta de servicio con credenciales en la direccion establecida en *GOOGLE_APPLICATION_CREDENTIALS*. Se utiliza el nuevo modelo ga-4 de google search-console. *apis.search-console.dimensions* contiene la configuracion de la informacion que debe venir dentro de la llamada a la api y *apis.search-console.row-limit* la cantidad que vendran. Consulta a los dominios *apis.search-console.domains* eliminando el prefijo. Igualmente que en todos los endpoints de google utiliza paginacion establecida por los parametros *page*, *per_page*, aparte de fechas establecidas por *start_date*, *end_date*.
