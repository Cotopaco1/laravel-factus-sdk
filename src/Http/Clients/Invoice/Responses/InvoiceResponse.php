<?php

namespace Cotopaco\Factus\Http\Clients\Invoice\Responses;

use Cotopaco\Factus\Http\HttpResponse;
use Illuminate\Http\Client\Response;

class InvoiceResponse extends HttpResponse
{
    public ?array $company;

    public ?array $establishment;

    public ?array $customer;

    public ?array $numberingRange;

    public ?array $billingPeriod;

    public ?array $bill;

    public ?array $relatedDocuments;

    public ?array $items;

    public ?array $allowanceCharges;

    public ?array $withholdingTaxes;

    public ?array $creditNotes;

    public ?array $debitNotes;

    public function __construct(Response $response)
    {
        parent::__construct($response);

        $data = $response->json();

        $responseData = $data['data'] ?? [];
        $this->company = $responseData['company'] ?? null;
        $this->establishment = $responseData['establishment'] ?? null;
        $this->customer = $responseData['customer'] ?? null;
        $this->numberingRange = $responseData['numbering_range'] ?? null;
        $this->billingPeriod = $responseData['billing_period'] ?? null;
        $this->bill = $responseData['bill'] ?? null;
        $this->relatedDocuments = $responseData['related_documents'] ?? null;
        $this->items = $responseData['items'] ?? null;
        $this->allowanceCharges = $responseData['allowance_charges'] ?? null;
        $this->withholdingTaxes = $responseData['withholding_taxes'] ?? null;
        $this->creditNotes = $responseData['credit_notes'] ?? null;
        $this->debitNotes = $responseData['debit_notes'] ?? null;
    }

    // Métodos helper útiles
    public function isSuccessful(): bool
    {
        return $this->status === 'Created';
    }

    public function getBillNumber(): ?string
    {
        return $this->bill['number'] ?? null;
    }

    public function getCufe(): ?string
    {
        return $this->bill['cufe'] ?? null;
    }

    public function getQrUrl(): ?string
    {
        return $this->bill['qr'] ?? null;
    }

    public function getPublicUrl(): ?string
    {
        return $this->bill['public_url'] ?? null;
    }

    public function getTotal(): ?float
    {
        return isset($this->bill['total']) ? (float) $this->bill['total'] : null;
    }

    public function hasErrors(): bool
    {
        return ! empty($this->bill['errors'] ?? []);
    }

    public function getErrors(): array
    {
        return $this->bill['errors'] ?? [];
    }
}
