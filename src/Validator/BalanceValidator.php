<?php

declare(strict_types=1);

namespace App\Validator;

use App\Controller\AccountController as ControllerAccount;
use App\Service\Account;
use Laminas\Validator\AbstractValidator;
use RuntimeException;

use function bccomp;

class BalanceValidator extends AbstractValidator
{
    private Account $account;
    protected const BALANCE           = 'balance';
    protected array $messageTemplates = [
        self::BALANCE => 'Something went wrong. Try later.',
    ];

    public function __construct(?array $options = null)
    {
        parent::__construct($options);
        $this->account = new Account();
    }

    public function isValid($value, ?array $context = null): bool
    {
        $this->setValue($value);
        $userId = (int) $context[ControllerAccount::USER_SESSION_KEY] ?? 0;

        try {
            $user      = $this->account->getUserAccountById($userId);
            $operation = bccomp((string) $value, (string) $user->balance);

            if ($operation === 1) {
                $this->error(self::BALANCE);

                return false;
            }
        } catch (RuntimeException $exception) {
            $this->error(self::BALANCE);

            return false;
        }

        return true;
    }
}
