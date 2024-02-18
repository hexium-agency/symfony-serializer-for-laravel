<?php

namespace HexiumAgency\SymfonySerializerForLaravel\Configuration;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConfigurationInvalid extends \InvalidArgumentException
{
    public function __construct(public readonly ConstraintViolationListInterface $violations)
    {
        $messages = array_map(static function (ConstraintViolationInterface $violation) {
            return sprintf('{%s: %s}', $violation->getPropertyPath(), $violation->getMessage());
        }, iterator_to_array($violations));

        parent::__construct(message: sprintf(
            'The serializer configuration is invalid. Violations: %s',
            implode(separator: ',', array: $messages))
        );
    }
}
