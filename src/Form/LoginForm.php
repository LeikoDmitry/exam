<?php

declare(strict_types=1);

namespace App\Form;

use App\Controller\AccountController;
use App\Validator\UserValidator;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Form\Element\Email;
use Laminas\Form\Element\Password;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\StringLength;

class LoginForm extends Form implements InputFilterProviderInterface
{
    public function init(): void
    {
        $this->add(
            [
                'name' => AccountController::USER_SESSION_EMAIL,
                'type' => Email::class,
            ]
        );
        $this->add(
            [
                'name' => AccountController::USER_SESSION_PASSWORD,
                'type' => Password::class,
            ]
        );
    }

    public function getInputFilterSpecification(): array
    {
        return [
            [
                'name'       => AccountController::USER_SESSION_PASSWORD,
                'required'   => true,
                'filters'    => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'min' => 5,
                            'max' => 100,
                        ],
                    ],
                ],
            ],
            [
                'name'       => AccountController::USER_SESSION_EMAIL,
                'required'   => true,
                'filters'    => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name'    => StringLength::class,
                        'options' => [
                            'min' => 8,
                            'max' => 100,
                        ],
                    ],
                    [
                        'name' => EmailAddress::class,
                    ],
                    [
                        'name'    => UserValidator::class,
                        'options' => [
                            'token' => AccountController::USER_SESSION_PASSWORD,
                        ],
                    ],
                ],
            ],
        ];
    }
}
