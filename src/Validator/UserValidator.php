<?php

declare(strict_types=1);

namespace App\Validator;

use App\Service\Account;
use Laminas\Validator\AbstractValidator;
use RuntimeException;

use function array_key_exists;
use function filter_var;
use function is_array;
use function password_verify;

use const FILTER_VALIDATE_EMAIL;

class UserValidator extends AbstractValidator
{
    private string $token;
    private Account $account;
    private $userId;
    protected const USER              = 'userNotExist';
    protected const EMAIL             = 'emailNotExist';
    protected array $messageTemplates = [
        self::USER  => 'User not exist. Try another one',
        self::EMAIL => 'Value should be email or username',
    ];

    public function __construct($token = null)
    {
        if (is_array($token) && array_key_exists('token', $token)) {
            $this->setToken($token['token']);
        } elseif (null !== $token) {
            $this->setToken('');
        }

        $this->account = new Account();
        parent::__construct(is_array($token) ? $token : null);
    }

    public function isValid($value, ?array $context = null): bool
    {
        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->error(static::EMAIL);
            return false;
        }

        if (! $this->isUser($value)) {
            $this->error(static::USER);
            return false;
        }

        if (! $this->isIdenticalPassword($value, $context)) {
            $this->error(static::USER);
            return false;
        }

        return true;
    }

    public function isUser(string $email): bool
    {
        try {
            $this->account->getUserByEmail($email);
            return true;
        } catch (RuntimeException $exception) {
            return false;
        }
    }

    public function isIdenticalPassword($value, array $context): bool
    {
        $token        = $this->getToken();
        $userPassword = $context[$token] ?? '';
        $user         = $this->account->getUserByEmail($value);
        $passwordHash = $user->getPassword();
        return password_verify($userPassword, $passwordHash);
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }
}
