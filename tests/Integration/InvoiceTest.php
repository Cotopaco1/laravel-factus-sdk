<?php

use Cotopaco\Factus\DTO\Customer;
use Cotopaco\Factus\DTO\Invoice;
use Cotopaco\Factus\DTO\InvoiceItem;
use Cotopaco\Factus\Factus;
use Cotopaco\Factus\Http\Clients\Invoice\Responses\InvoiceListResponse;
use Cotopaco\Factus\Http\Clients\Invoice\Responses\InvoicePdfResponse;
use Cotopaco\Factus\Http\Clients\Invoice\Responses\InvoiceResponse;

describe('Invoices', function () {

    it('create and validate an invoice', function () {

        $factus = app(Factus::class);

        $customer = new Customer(
            identificationDocumentId: 3,
            identification: '123456789',
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
            referenceCode: 'TEST-'.time(),
            sendEmail: false
        );

        $response = $factus->invoice()->createAndValidate($invoice);

        expect($response)->toBeInstanceOf(InvoiceResponse::class);

    })->group('integration', 'invoice.store');

    // it('maneja errores de autenticación correctamente', function () {
    //    // Configura credenciales inválidas para probar manejo de errores
    //    Config::set('factus.username', 'invalid-user');
    //    Config::set('factus.password', 'invalid-password');
    //
    //    $client = new FactusHttpClient();
    //
    //    // Esto debe lanzar un abort(500)
    //    expect(fn() => $client->getAccessToken())->toThrow(Exception::class);
    // })->group('integration');

    it('can list invoices ', function () {
        $factus = app(Factus::class);
        $response = $factus->invoice()->list();

        expect($response)->toBeInstanceOf(InvoiceListResponse::class)
            ->and($response->status)->toBe('OK')
            ->and($response->getInvoices())->toBeArray()
            ->and($response->statusCode)->toBe(200);
    })->group('invoices.get');

    it('can show a specific invoice by number', function () {
        $factus = app(Factus::class);

        // First, get a list to have a valid invoice number
        $listResponse = $factus->invoice()->list(['page' => 1]);
        expect($listResponse)->toBeInstanceOf(InvoiceListResponse::class);

        $invoices = $listResponse->getInvoices();

        // Only test if we have invoices
        if (count($invoices) > 0) {
            $invoiceNumber = $invoices[0]['number'];

            // Now get the specific invoice
            $showResponse = $factus->invoice()->show($invoiceNumber);

            expect($showResponse)->toBeInstanceOf(InvoiceResponse::class);
            expect($showResponse->status)->toBe('OK');
            expect($showResponse->getBillNumber())->toBe($invoiceNumber);

            // Verify the response has all expected data structure
            expect($showResponse->company)->toBeArray();
            expect($showResponse->establishment)->toBeArray();
            expect($showResponse->customer)->toBeArray();
            expect($showResponse->bill)->toBeArray();
            expect($showResponse->items)->toBeArray();
        }
    })->group('invoices.show');

    it('can download PDF for a specific invoice', function () {
        $factus = app(Factus::class);

        // First, get a list to have a valid invoice number
        $listResponse = $factus->invoice()->list(['page' => 1]);
        expect($listResponse)->toBeInstanceOf(InvoiceListResponse::class);

        $invoices = $listResponse->getInvoices();

        // Only test if we have invoices
        if (count($invoices) > 0) {
            $invoiceNumber = $invoices[0]['number'];

            // Download the PDF
            $pdfResponse = $factus->invoice()->downloadPdf($invoiceNumber);

            expect($pdfResponse)->toBeInstanceOf(InvoicePdfResponse::class);
            expect($pdfResponse->status)->toBe('OK');
            expect($pdfResponse->isSuccessful())->toBeTrue();

            // Verify PDF data
            expect($pdfResponse->getFileName())->toBeString();
            expect($pdfResponse->getPdfBase64())->toBeString();
            expect(strlen($pdfResponse->getPdfBase64()))->toBeGreaterThan(0);

            // Verify binary conversion
            $binaryPdf = $pdfResponse->getPdfBinary();
            expect($binaryPdf)->toBeString();
            expect(strlen($binaryPdf))->toBeGreaterThan(0);

            // Check PDF header (should start with %PDF)
            expect(substr($binaryPdf, 0, 4))->toBe('%PDF');

            // Test download response structure
            $downloadResponse = $pdfResponse->getPdfDownloadResponse();
            expect($downloadResponse)->toHaveKeys(['content', 'headers']);
            expect($downloadResponse['headers'])->toHaveKeys([
                'Content-Type', 'Content-Disposition', 'Content-Length',
            ]);
        }
    })->group('invoices.pdf');

})->group('invoices');
