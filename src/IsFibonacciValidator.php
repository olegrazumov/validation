<?php

declare(strict_types=1);

namespace Validation;

class IsFibonacciValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    public function isValid($value): bool
    {
        $result = true;

        if (!$this->isFibonacci($value)) {
            $this->addErrorMessage("\"{$value}\" is not fibonacci");
            $result = false;
        }

        return $result;
    }

    /**
     * @param int $number
     * @return bool
     */
    protected function isFibonacci(int $number): bool
    {
        $sqrt5 = sqrt(5);
        $phi = (1 + $sqrt5) / 2;
        $rphi = (1 - $sqrt5) / 2;
        $approximateNumber = (log($number) + log($sqrt5)) / log($phi);
        $floorNumber = floor($approximateNumber);
        $ceilNumber = ceil($approximateNumber);
        $roundNumber = ($approximateNumber - $floorNumber) < ($ceilNumber - $approximateNumber) ? $floorNumber : $ceilNumber;
        $fibonacci = intval((pow($phi, $roundNumber) - pow($rphi, $roundNumber)) / $sqrt5);

        return $number === $fibonacci;
    }
}
