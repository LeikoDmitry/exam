<?php

declare(strict_types=1);

namespace App\Service;

use App\Controller\AccountController;
use App\Model\User;
use PDO;
use RuntimeException;

use function bcsub;
use function sprintf;

class Account
{
    private Connection $connection;
    private const TABLE_USER    = 'user';
    private const TABLE_ACCOUNT = 'account';

    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    public function getUserAccountById(int $id): User
    {
        $sql = sprintf(
            'SELECT 
            u.id, 
            u.email, 
            u.username, 
            u.roles, 
            a.balance, 
            a.created_at 
            FROM %s AS u LEFT JOIN %s AS a ON u.id = a.user_id WHERE u.id = :id LIMIT 1',
            static::TABLE_USER,
            static::TABLE_ACCOUNT
        );
        $row = $this->query($sql, [':id' => $id], User::class);

        if (! $row) {
            throw new RuntimeException('Account not exist');
        }

        return $row;
    }

    public function getUserById(int $id): User
    {
        $sql = sprintf('SELECT * FROM %s WHERE id = :id LIMIT 1', static::TABLE_USER);
        $row = $this->query($sql, [':id' => $id], User::class);

        if (! $row) {
            throw new RuntimeException('User not exist');
        }

        return $row;
    }

    public function getUserByEmail(string $email): User
    {
        $sql = sprintf('SELECT * FROM %s WHERE email = :email LIMIT 1', static::TABLE_USER);
        $row = $this->query($sql, [':email' => $email], User::class);

        if (! $row) {
            throw new RuntimeException('User not exist');
        }

        return $row;
    }

    protected function query(string $sql, array $args, ?string $class = null): User
    {
        $adapter = $this->connection->getAdapter()->prepare($sql);

        if (null !== $class) {
            $adapter->setFetchMode(PDO::FETCH_CLASS, $class);
        }

        $adapter->execute($args);

        if (null !== $class) {
            $result = $adapter->fetch();

            if (! $result) {
                throw new RuntimeException('User not exist');
            }

            return $result;
        }

        return new User();
    }

    public function updateUser(array $data): void
    {
        $adapter = $this->connection->getAdapter();
        $adapter->beginTransaction();

        try {
            $user      = $this->getUserAccountById($data[AccountController::USER_SESSION_KEY]);
            $sql       = sprintf(
                'UPDATE %s SET balance = :amount WHERE user_id = :user_id LIMIT 1',
                static::TABLE_ACCOUNT
            );
            $operation = bcsub((string) $user->balance, (string) $data[AccountController::USER_SESSION_AMOUNT], 2);
            $this->query($sql, [':amount' => $operation, ':user_id' => $user->getId()]);
            $adapter->commit();
        } catch (RuntimeException $exception) {
            //TODO logger
            $adapter->rollBack();
        }
    }
}
