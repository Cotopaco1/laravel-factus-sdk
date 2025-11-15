<?php

namespace Cotopaco\Factus\DTO;

class Invoice
{
    public function __construct(
        /** @var array<InvoiceItem> */
        public array $items,
        public Customer $customer,
        public string $referenceCode,
        public int $paymentForm = 1,
        public int $paymentMethodCode = 10,
        public string $operationType = '10',
        public bool $sendEmail = true,
        public ?int $numberingRangeId = null,
        public ?string $document = null,
        public ?string $observation = null,
        public ?string $paymentDueDate = null,
        public ?array $orderReference = null,
        public ?array $relatedDocuments = null,
        public ?array $billingPeriod = null,
        public ?array $establishment = null,
        public ?array $allowanceCharges = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'numbering_range_id' => $this->numberingRangeId,
            'document' => $this->document,
            'reference_code' => $this->referenceCode,
            'observation' => $this->observation,
            'payment_form' => $this->paymentForm,
            'payment_due_date' => $this->paymentDueDate,
            'payment_method_code' => $this->paymentMethodCode,
            'operation_type' => $this->operationType,
            'order_reference' => $this->orderReference,
            'send_email' => $this->sendEmail,
            'related_documents' => $this->relatedDocuments,
            'billing_period' => $this->billingPeriod,
            'establishment' => $this->establishment,
            'customer' => $this->customer->toArray(),
            'items' => array_map(fn(InvoiceItem $item) => $item->toArray(), $this->items),
            'allowance_charges' => $this->allowanceCharges,
        ], fn($value) => $value !== null);
    }
}
