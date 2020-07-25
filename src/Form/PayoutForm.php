<?php

declare(strict_types=1);

namespace App\Form;

use App\Controller\AccountController;
use App\Validator\BalanceValidator;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToFloat;
use Laminas\Filter\ToInt;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\I18n\Validator\IsFloat;
use Laminas\I18n\Validator\IsInt;
use Laminas\InputFilter\InputFilterProviderInterface;

class PayoutForm extends Form implements InputFilterProviderInterface
{
    public function init(): void
    {
        $this->add(
            [
                'name' => AccountController::USER_SESSION_AMOUNT,
                'type' => Text::class,
            ]
        );
        $this->add(
            [
                'name' => AccountController::USER_SESSION_KEY,
                'type' => Hidden::class,
            ]
        );
    }

    public function getInputFilterSpecification(): array
    {
        return [
            [
                'name'       => AccountController::USER_SESSION_KEY,
                'required'   => true,
                'filters'    => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                    ['name' => ToInt::class],
                ],
                'validators' => [
                    [
                        'name' => IsInt::class,
                    ],
                ],
            ],
            [
                'name'       => AccountController::USER_SESSION_AMOUNT,
                'required'   => true,
                'filters'    => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                    ['name' => ToFloat::class],
                ],
                'validators' => [
                    [
                        'name' => IsFloat::class,
                    ],
                    [
                        'name'    => BalanceValidator::class,
                        'options' => [
                            AccountController::USER_SESSION_KEY => AccountController::USER_SESSION_KEY,
                        ],
                    ],
                ],
            ],
        ];
    }
}
