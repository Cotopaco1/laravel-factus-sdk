<?php

use Cotopaco\Factus\Http\FactusHttpClient;
use Cotopaco\Factus\DTO\Invoice;
use Cotopaco\Factus\DTO\InvoiceItem;
use Cotopaco\Factus\DTO\Customer;
use Cotopaco\Factus\DTO\InvoiceResponse;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    logger("Probando este log ");
    // Carga las variables del .env.testing
    if (file_exists(__DIR__ . '/../../.env.testing')) {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../..', '.env.testing');
        $dotenv->load();
    }

    // Configura los valores desde el .env.testing
    Config::set('factus.sandbox', env('FACTUS_PRODUCTION', false));
    Config::set('factus.sandbox_base_url', env('FACTUS_SANDBOX_BASE_URL'));
    Config::set('factus.base_url', env('FACTUS_BASE_URL'));
    Config::set('factus.client.id', env('FACTUS_CLIENT_ID'));
    Config::set('factus.client.secret', env('FACTUS_CLIENT_SECRET'));
    Config::set('factus.username', env('FACTUS_USERNAME'));
    Config::set('factus.password', env('FACTUS_PASSWORD'));
});

it('puede obtener access token de la API real', function () {
    $client = new FactusHttpClient();

    $token = $client->getAccessToken();

    expect($token)->toBeString()->not->toBeEmpty();

    dump('Access Token obtenido: ' . substr($token, 0, 20) . '...');
})->group('integration');

it('puede crear y validar una factura en la API real', function () {
    $client = new FactusHttpClient();

    $customer = new Customer(
        identificationDocumentId: 3,
        identification: "123456789",
        legalOrganizationId: 1,
        tributeId: 18, // 18 aplica iva, 21 no aplica.
        dv: 0,
        company: 'Empresa Test',
        tradeName: 'Test Trade',
        names: 'Cliente de Prueba',
        address: 'Calle 123 #45-67',
        email: 'test@example.com',
        phone: '3001234567',
        municipalityId: 1
    );

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

    $invoice = new Invoice(
        items: [$item],
        customer: $customer,
        referenceCode: 'TEST-' . time(),
        sendEmail: false
    );

    $response = $client->createAndValidateInvoice($invoice);

    expect($response)->toBeInstanceOf(InvoiceResponse::class);
})->group('integration');

it('maneja errores de autenticación correctamente', function () {
    // Configura credenciales inválidas para probar manejo de errores
    Config::set('factus.username', 'invalid-user');
    Config::set('factus.password', 'invalid-password');

    $client = new FactusHttpClient();

    // Esto debe lanzar un abort(500)
    expect(fn() => $client->getAccessToken())->toThrow(Exception::class);
})->group('integration');
