<?php

declare(strict_types=1);

namespace Validation;

abstract class AbstractValidator implements ValidatorInterface
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var array
     */
    private $options = [];

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return void
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param string $errorMessage
     * @return void
     */
    public function addErrorMessage(string $errorMessage): void
    {
        $this->errors[] = $errorMessage;
    }
}
