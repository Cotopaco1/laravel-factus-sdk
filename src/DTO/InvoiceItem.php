<?php

namespace Cotopaco\Factus\DTO;

class InvoiceItem
{
    public function __construct(
        public string $codeReference,
        public string $name,
        public int $quantity,
        public float $discountRate,
        public float $price,
        public float $taxRate,
        public int $unitMeasureId,
        public int $standardCodeId,
        public int $isExclude,
        public int $tributeId,
        public ?int $schemeId = null,
        public ?string $note = null,
        public ?array $withholdingTaxes = null,
        public ?array $mandate = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'scheme_id' => $this->schemeId,
            'note' => $this->note,
            'code_reference' => $this->codeReference,
            'name' => $this->name,
            'quantity' => $this->quantity,
            'discount_rate' => $this->discountRate,
            'price' => $this->price,
            'tax_rate' => $this->taxRate,
            'unit_measure_id' => $this->unitMeasureId,
            'standard_code_id' => $this->standardCodeId,
            'is_excluded' => $this->isExclude,
            'tribute_id' => $this->tributeId,
            'withholding_taxes' => $this->withholdingTaxes,
            'mandate' => $this->mandate,
        ], fn ($value) => $value !== null);
    }

    /**
     * Create DTO from array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            codeReference: $data['code_reference'],
            name: $data['name'],
            quantity: $data['quantity'],
            discountRate: $data['discount_rate'],
            price: $data['price'],
            taxRate: $data['tax_rate'],
            unitMeasureId: $data['unit_measure_id'],
            standardCodeId: $data['standard_code_id'],
            isExclude: $data['is_excluded'],
            tributeId: $data['tribute_id'],
            schemeId: $data['scheme_id'] ?? null,
            note: $data['note'] ?? null,
            withholdingTaxes: $data['withholding_taxes'] ?? null,
            mandate: $data['mandate'] ?? null,
        );
    }

    /**
     * Create many DTO from array
     *
     * @return self[] List of InvoiceItems
     */
    public static function manyFromArray(array $data): array
    {
        return array_map(fn (array $item) => self::fromArray($item), $data);
    }
}
