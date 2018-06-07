<?php

declare(strict_types=1);

namespace Validation;

class IsEmailValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    public function isValid($value): bool
    {
        $result = true;

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addErrorMessage("\"{$value}\" is invalid email");
            $result = false;
        }

        return $result;
    }
}
