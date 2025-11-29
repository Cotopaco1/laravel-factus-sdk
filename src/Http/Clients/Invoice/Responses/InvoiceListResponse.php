<?php

namespace Cotopaco\Factus\Http\Clients\Invoice\Responses;

use Cotopaco\Factus\Http\HttpResponse;
use Illuminate\Http\Client\Response;

class InvoiceListResponse extends HttpResponse
{
    public array $data;
    public int $total;
    public int $per_page;
    public int $current_page;
    public int $last_page;
    public int $from;
    public int $to;
    public array $links;

    public function __construct(Response $response)
    {
        parent::__construct($response);

        $responseData = $this->rawData['data'] ?? [];
        
        // Map invoice data
        $this->data = $responseData['data'] ?? [];
        
        // Map pagination data
        $pagination = $responseData['pagination'] ?? [];
        $this->total = $pagination['total'] ?? 0;
        $this->per_page = $pagination['per_page'] ?? 0;
        $this->current_page = $pagination['current_page'] ?? 0;
        $this->last_page = $pagination['last_page'] ?? 0;
        $this->from = $pagination['from'] ?? 0;
        $this->to = $pagination['to'] ?? 0;
        $this->links = $pagination['links'] ?? [];
    }

    /**
     * Get invoices as individual invoice objects
     */
    public function getInvoices(): array
    {
        return $this->data;
    }

    /**
     * Get total number of invoices
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * Check if there is a next page
     */
    public function hasNextPage(): bool
    {
        return $this->current_page < $this->last_page;
    }

    /**
     * Check if there is a previous page
     */
    public function hasPreviousPage(): bool
    {
        return $this->current_page > 1;
    }

    /**
     * Get next page URL
     */
    public function getNextPageUrl(): ?string
    {
        foreach ($this->links as $link) {
            if (($link['label'] ?? '') === 'Siguiente &raquo;' && isset($link['url'])) {
                return $link['url'];
            }
        }
        return null;
    }

    /**
     * Get previous page URL
     */
    public function getPreviousPageUrl(): ?string
    {
        foreach ($this->links as $link) {
            if (($link['label'] ?? '') === '&laquo; Anterior' && isset($link['url'])) {
                return $link['url'];
            }
        }
        return null;
    }

    /**
     * Get pagination info
     */
    public function getPaginationInfo(): array
    {
        return [
            'total' => $this->total,
            'per_page' => $this->per_page,
            'current_page' => $this->current_page,
            'last_page' => $this->last_page,
            'from' => $this->from,
            'to' => $this->to,
        ];
    }
}
