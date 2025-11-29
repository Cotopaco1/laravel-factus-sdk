<?php

namespace Cotopaco\Factus\Http\Clients\Invoice\Responses;

use Cotopaco\Factus\Http\HttpResponse;
use Illuminate\Http\Client\Response;

class InvoiceDeleteResponse extends HttpResponse
{
    public function __construct(Response $response)
    {
        parent::__construct($response);
    }

    /**
     * Verifica si la factura fue eliminada exitosamente
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'OK';
    }

    /**
     * Obtiene el mensaje de confirmación de eliminación
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Verifica si el mensaje contiene la confirmación de eliminación
     */
    public function isDeleted(): bool
    {
        return $this->isSuccessful() &&
               str_contains(strtolower($this->message), 'eliminado');
    }
}
