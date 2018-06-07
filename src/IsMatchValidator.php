<?php

declare(strict_types=1);

namespace Validation;

class IsMatchValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    public function isValid($value): bool
    {
        $result = true;
        $regex = $this->getOptions()['regex'] ?? '';

        if (!preg_match($regex, $value)) {
            $this->addErrorMessage("\"{$value}\" does not match pattern \"{$regex}\"");
            $result = false;
        }

        return $result;
    }
}
