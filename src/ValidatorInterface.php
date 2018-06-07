<?php

declare(strict_types=1);

namespace Validation;

interface ValidatorInterface
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value): bool;

    /**
     * @return array
     */
    public function getErrors(): array;
}
