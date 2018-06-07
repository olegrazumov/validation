<?php

declare(strict_types=1);

namespace Validation;

class ValidationManager
{
    /**
     * @var array
     */
    private $validators = [];

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param array $validators
     * @param array $data
     */
    public function __construct(array $validators = [], array $data = [])
    {
        $this->setValidators($validators);
        $this->setData($data);
    }

    /**
     * @param array $validators
     * @return void
     */
    public function setValidators(array $validators): void
    {
        foreach ($validators as $inputName => $inputData) {
            $inputValidators = $inputData['validators'] ?? [];

            foreach ($inputValidators as $inputValidator) {
                if (is_array($inputValidator)) {
                    ['name' => $validatoName, 'options' => $validatorOptions] = $inputValidator;
                } else {
                    $validatoName =  $inputValidator;
                    $validatorOptions = [];
                }

                $validatorClassName =  '\\Validation\\' . ucfirst($validatoName) . 'Validator';

                if (!class_exists($validatorClassName)) {
                    throw new \InvalidArgumentException('Unknown validator: ' . $validatoName);
                }

                $this->validators[$inputName][] = new $validatorClassName($validatorOptions);
            }
        }
    }

    /**
     * @param array $data
     * @return void
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $result = true;

        foreach ($this->data as $inputName => $inputValue) {
            $inputValidators = $this->validators[$inputName] ?? [];

            foreach ($inputValidators as $inputValidator) {
                if ($inputValidator->isValid($inputValue)) {
                    continue;
                }

                $this->errors[$inputName][] = $inputValidator->getErrors();
                $result = false;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return array_merge(...array_values($this->errors));
    }
}
