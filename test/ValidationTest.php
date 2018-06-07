<?php

declare(strict_types=1);

namespace Testing;

use Validation\{
    ValidatorInterface,
    AbstractValidator,
    IsEmailValidator,
    IsMatchValidator,
    IsFibonacciValidator,
    ValidationManager
};

use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    /**
     * @var AbstractValidator
     */
    protected $abstractValidator;

    /**
     * @var IsEmailValidator
     */
    protected $emailValidator;

    /**
     * @var IsMatchValidator
     */
    protected $isMatchValidator;

    /**
     * @var IsFibonacciValidator
     */
    protected $isFibonacciValidator;

    /**
     * @var ValidationManager
     */
    protected $validationManager;

    
    public function setUp()
    {
        $this->abstractValidator = $this->getMockForAbstractClass(AbstractValidator::class);
        $this->emailValidator = new IsEmailValidator;
        $this->isMatchValidator = new IsMatchValidator;
        $this->isFibonacciValidator = new IsFibonacciValidator;
        $this->isFibonacciValidator = new IsFibonacciValidator;
        $this->validationManager = new ValidationManager;
    }

    public function testAbstractValidator()
    {
        $optionName = 'optionName';
        $optionValue = 'optionValue';
        $options = [$optionName => $optionValue];
        $this->abstractValidator->setOptions($options);
        $this->assertEquals($this->abstractValidator->getOptions()[$optionName], $optionValue);

        $errorMessage = 'errorMessage';
        $this->abstractValidator->addErrorMessage($errorMessage);
        $this->assertEquals(current($this->abstractValidator->getErrors()), $errorMessage);
    }

    public function testIsEmailValidator()
    {
        $this->assertValidatorSuccess($this->emailValidator, 'asd@asd.asd');
        $this->assertValidatorFail($this->emailValidator, 'asdasd.asd');
    }

    public function testIsMatchValidator()
    {
        $this->isMatchValidator->setOptions(['regex' => '/\d+/']);
        $this->assertValidatorSuccess($this->isMatchValidator, '1');
        $this->assertValidatorFail($this->isMatchValidator, 'a');
    }

    public function testIsFibonacciValidator()
    {
        $this->assertValidatorSuccess($this->isFibonacciValidator, 1548008755920);
        $this->assertValidatorFail($this->isFibonacciValidator, 11);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidationManager()
    {
        $validValidators = [
            'email' => [
                'validators' => [
                    'isEmail',
                ],
            ],
            'regex' => [
                'validators' => [
                    ['name' => 'isMatch', 'options' => ['regex' => '/\d+/']]
                ],
            ],
            'fibonacci' => [
                'validators' => [
                    'isFibonacci',
                ],
            ],
        ];

        $this->validationManager->setValidators($validValidators);

        $validData = [
            'email' => 'test@example.com',
            'regex' => '1',
            'fibonacci' => 1548008755920,
        ];

        $this->validationManager->setData($validData);
        $this->assertTrue($this->validationManager->validate());

        $invalidData = [
            'email' => 'testexample.com',
            'regex' => '1a',
            'fibonacci' => 1548008755921,
        ];

        $this->validationManager->setData($invalidData);
        $this->assertFalse($this->validationManager->validate());
        $this->assertNotEmpty($this->validationManager->getErrors());

        $invalidValidators = [
            'datakey' => ['validators' => ['wrongvalidor']],
        ];

        $this->validationManager->setValidators($invalidValidators);
    }

    protected function assertValidatorSuccess(ValidatorInterface $validator, $value)
    {
        $this->assertTrue($validator->isValid($value));
    }

    protected function assertValidatorFail(ValidatorInterface $validator, $value)
    {
        $this->assertFalse($validator->isValid($value));
    }
}
