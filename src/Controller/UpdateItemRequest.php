<?php

namespace App\Controller;

use DigitalRevolution\SymfonyRequestValidation\AbstractValidatedRequest;
use DigitalRevolution\SymfonyRequestValidation\ValidationRules;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class UpdateItemRequest extends AbstractValidatedRequest
{

    /**
     * Get all the constraints for the current query params
     */
    protected function getValidationRules(): ValidationRules
    {
        return new ValidationRules([
            'request' => [
                'id' => 'required|int|min:0',
                'data' => 'filled|string'
            ]
        ]);
    }

    /**
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->request->request->get('data');
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->request->request->get('id');
    }

    /**
     * @param ConstraintViolationListInterface $violationList
     */
    protected function handleViolations(ConstraintViolationListInterface $violationList): void
    {

    }
}