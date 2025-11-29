### ⚠️ Este paquete esta en desarrollo y puede cambiar su estructura, no se recomienda para produccion por el momento, Si te es util puedes realizar un Fork al paquete y acomodarlo a tus necesidades 

# SDK Factus para interactuar facilmente con la API de Factus

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cotopaco/laravel-factus-sdk.svg?style=flat-square)](https://packagist.org/packages/cotopaco/laravel-factus-sdk)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/cotopaco/laravel-factus-sdk/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/cotopaco/laravel-factus-sdk/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/cotopaco/laravel-factus-sdk/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/cotopaco/laravel-factus-sdk/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/cotopaco/laravel-factus-sdk.svg?style=flat-square)](https://packagist.org/packages/cotopaco/laravel-factus-sdk)

[Factus](https://developers.factus.com.co/) es un proveedor tecnologico de Colombia, que provee como servicio una API para interactuar mas facil con la DIAN.
**Este paquete no es oficial de Halltec(Empresa dueña de Factus).**
Interactua con Factus facilmente con las herramientas disponibles en este paquete !

ejemplo simplicifado:
```php
$factus = app(Cotopaco\Factus\Factus::class);
$response = $factus->invoice()->createAndValidate($invoice);
$response->rawData; // Respuesta de factus en un array asociativo.
$response->getCufe(); // Cufe de la factura
```

## Instalación

Instala el paquete via composer:

```bash
composer require cotopaco/laravel-factus-sdk
```

Ahora en nuestro archivo .env de la raiz del proyecto debemos agregar las siguientes variables

```dotenv
FACTUS_PRODUCTION=false # true si es produccion
FACTUS_USERNAME=myuusername
FACTUS_PASSWORD=mypassword
FACTUS_CLIENT_ID=myclientid
FACUTS_CLIENT_SECRET=myclientsecret
```

## Conceptos Clave
- CLIENT: Representa un cliente que puede solicitar recursos en la API de factus, ej: InvoiceClient puede realizar solicitdes acorde a la facutración.
- DTO: Son clases que transportan la información referente a una entidad, estos debes instanciarlos y pasarlos a los metodos del cliente correspondiente.

## Uso

Ya con las credenciales configuradas, podemos utilizar la clase Cotopaco\Factus\Factus::class para interactuar con la API de factus.

### Crear una factura

```php
use Cotopaco\Factus\DTO\Customer;
use Cotopaco\Factus\DTO\Invoice;
use Cotopaco\Factus\DTO\InvoiceItem;
use Cotopaco\Factus\Factus;

/* Instanciamos Singleton */
$factus = app(Factus::class);

/* Instanciamos un Customer */
$customer = new Customer(
    identificationDocumentId: 3,
    identification: "123456789",
    legalOrganizationId: 1,
    tributeId: 18, 
    dv: 0,
    company: 'Empresa Test',
    tradeName: 'Test Trade',
    names: 'Cliente de Prueba',
    address: 'Calle 123 #45-67',
    email: 'test@example.com',
    phone: '3001234567',
    municipalityId: 1
);

/* Instanciamos un item */
$item = new InvoiceItem(
    codeReference: 'PROD-001',
    name: 'Producto de Prueba',
    quantity: 2,
    discountRate: 0.0,
    price: 100000.0,
    taxRate: 19.0,
    unitMeasureId: 70,
    standardCodeId: 1,
    isExclude: 0,
    tributeId: 1
);

/* Instanciamos una factura con el customer y los items */
$invoice = new Invoice(
    items: [$item],
    customer: $customer,
    referenceCode: 'TEST-111',
    sendEmail: false
);

/* Realizamos peticion y obtenemos respuesta */
$response = $factus->invoice()->createAndValidate($invoice);
$response->rawData; // Body de la respuesta de Factus en un array asociativo.
$response->getCufe() // Cufe de la factura
$response->statusCode // Status code de la respuesta

```

### Listar factura
````php
use Cotopaco\Factus\Factus;

/* Instanciamos Singleton */
$factus = app(Factus::class);

$response = $factus->invoice()->list(); // Realizar peticion

$response->getInvoices(); // Data de las facturas

$response->rawData // Obtener body de la respuesta de FACTUS


/* Peticion con filtros */
$params = [
    'page' => 2, // Pagina a solicitar
    'status' => 1, // estado de las facturas
];
$response = $factus->invoice()->list($params);
````

### Mostrar una factura
````php
use Cotopaco\Factus\Factus;

/* Instanciamos Singleton */
$factus = app(Factus::class);

$response = $factus->invoice()->show('ETP990000493'); // Realizamos solicitud
$response->customer // Obtener cliente de la factura
$response->rawData //  Obtener body de la respuesta de FACTUS

````

### Descargar pdf de una factura
````php
use Cotopaco\Factus\Factus;

/* Instanciamos Singleton */
$factus = app(Factus::class);

$response = $factus->invoice()->downloadPdf('ETP990000493'); // Realizamos solicitud
$response->getPdfDownloadResponse(); // Retorna respuesta para descargar el PDF.
/* Otros metodos utiles */
$response->getFileName();
$response->getPdfBase64();
$response->getPdfBinary();

````

### Eliminar una factura no validada por la DIAN
````php
use Cotopaco\Factus\Factus;

/* Instanciamos Singleton */
$factus = app(Factus::class);

$response = $factus->invoice()->delete('ETP990000493'); // Realizamos solicitud
$response->isDeleted(); // Retorna true si es eliminada validando el mensaje recibido de la API.
$response->rawData // Respuesta del body de la API FACTUS.

````

Opcional: Publica las configuraciones


```bash
php artisan vendor:publish --tag="laravel-factus-sdk-config"
```

Se publicara el archivo 'config/factus.php' con el siguiente contenido.

```php
return [
    'base_url' => env('FACTUS_BASE_URL', 'https://api.factus.com.co'),
    'sandbox_base_url' => env('FACTUS_SANDBOX_BASE_URL', 'https://api-sandbox.factus.com.co'),
    'client' => [
        'id' => env('FACTUS_CLIENT_ID'),
        'secret' => env('FACTUS_CLIENT_SECRET')
    ],
    'username' => env('FACTUS_USERNAME'),
    'password' => env('FACTUS_PASSWORD'),
    'production' => env('FACTUS_PRODUCTION', false)
];
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contribución

Eres libre de contribuir, crea una PR y con gusto lo revisaré.


## Credits

- [Sergio](https://github.com/Cotopaco)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
