<?php

namespace Cotopaco\Factus\Http\Clients\Invoice;

use Cotopaco\Factus\DTO\Invoice;
use Cotopaco\Factus\Http\Clients\Invoice\Responses\InvoiceDeleteResponse;
use Cotopaco\Factus\Http\Clients\Invoice\Responses\InvoiceListResponse;
use Cotopaco\Factus\Http\Clients\Invoice\Responses\InvoicePdfResponse;
use Cotopaco\Factus\Http\Clients\Invoice\Responses\InvoiceResponse;
use Cotopaco\Factus\Http\FactusHttpClient;

class InvoiceClient extends FactusHttpClient
{
    /**
     * Solicita crear y valir una factura
     *
     * @throws
     * */
    public function createAndValidate(Invoice $invoice): InvoiceResponse
    {
        return $this->handleError(function () use ($invoice) {
            $response = $this->jsonClient()->post('/bills/validate', $invoice->toArray());

            return new InvoiceResponse($response);
        });

    }

    /**
     * Lista facturas con filtros opcionales
     *
     * @param  array|null  $filters  - Filtros disponibles:
     *                               - identification: número de identificación del cliente
     *                               - names: nombre del cliente
     *                               - number: número de factura
     *                               - prefix: prefijo de factura
     *                               - reference_code: código de referencia
     *                               - status: estado de factura (1=validada, 0=pendiente)
     *                               - page: número de página para paginación
     *
     * @throws
     */
    public function list(?array $filters = null): InvoiceListResponse
    {
        return $this->handleError(function () use ($filters) {
            $queryParams = [];

            if ($filters) {
                // Map filters to the expected API format
                foreach ($filters as $key => $value) {
                    if (in_array($key, ['identification', 'names', 'number', 'prefix', 'reference_code', 'status'])) {
                        $queryParams["filter[{$key}]"] = $value;
                    } elseif ($key === 'page') {
                        $queryParams['page'] = $value;
                    }
                }
            }

            $url = '/bills';
            if (! empty($queryParams)) {
                $url .= '?'.http_build_query($queryParams);
            }

            $response = $this->jsonClient()->get($url);

            return new InvoiceListResponse($response);
        });
    }

    /**
     * Obtiene una factura específica por su número
     *
     * @param  string  $number  - Número de factura (ej: SETP990000493)
     *
     * @throws
     */
    public function show(string $number): InvoiceResponse
    {
        return $this->handleError(function () use ($number) {
            $response = $this->jsonClient()->get("/bills/show/{$number}");

            return new InvoiceResponse($response);
        });
    }

    /**
     * Descarga el PDF de una factura específica en formato base64
     *
     * @param  string  $number  - Número de factura (ej: SETP990000493)
     *
     * @throws
     */
    public function downloadPdf(string $number): InvoicePdfResponse
    {
        return $this->handleError(function () use ($number) {
            $response = $this->jsonClient()->get("/bills/download-pdf/{$number}");

            return new InvoicePdfResponse($response);
        });
    }

    /**
     * Elimina una factura no validada usando su código de referencia
     *
     * @param  string  $referenceCode  - Código de referencia de la factura
     *
     * @throws
     */
    public function delete(string $referenceCode): InvoiceDeleteResponse
    {
        return $this->handleError(function () use ($referenceCode) {
            $response = $this->jsonClient()->delete("/bills/destroy/reference/{$referenceCode}");

            return new InvoiceDeleteResponse($response);
        });
    }
}
