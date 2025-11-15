<?php

namespace Cotopaco\Factus\Contracts;

use Cotopaco\Factus\DTO\Invoice;
use Cotopaco\Factus\DTO\InvoiceResponse;

interface FactusClient
{
    /**
     * Crear y validar factura
     * POST /v1/bills/validate
     */
    public function createAndValidateInvoice(Invoice $invoice): InvoiceResponse;

    /**
     * Ver una factura por número
     * GET /v1/bills/show/:number
     */
    // public function getInvoice(string $number): InvoiceResponse;

    /**
     * Ver y filtrar facturas
     * GET /v1/bills?filter[...]
     */
    // public function listInvoices(
    //    ?InvoiceFilters $filters = null,
    //    int $page = 1,
    //    int $perPage = 20,
    // ): InvoiceCollectionResponse;

    /**
     * Descargar PDF de la factura
     * GET /v1/bills/download-pdf/:number
     * (la response viene con el PDF en base64 y el nombre del archivo)
     */
    // public function downloadInvoicePdf(string $number): InvoicePdfResponse;

    /**
     * Eliminar factura no validada por reference_code
     * DELETE /v1/bills/:reference_code
     * (según la doc: elimina usando el código de referencia,
     * sólo si no está validada por la DIAN) :contentReference[oaicite:0]{index=0}
     */
    // public function deleteNotValidatedInvoice(string $referenceCode): void;
}
