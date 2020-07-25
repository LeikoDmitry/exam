<?php

declare(strict_types=1);

namespace App\Service;

use PDO;
use PDOException;

use function sprintf;

class Connection
{
    private static array $instances = [];
    private PDO $adapter;

    protected function __construct()
    {
        try {
            $config        = include_once __DIR__ . '/../../config/application.config.php';
            $this->adapter = new PDO(
                sprintf(
                    'mysql:host=%s;dbname=%s;charset=utf8',
                    $config['adapter']['host'] ?? '',
                    $config['adapter']['database'] ?? ''
                ),
                $config['adapter']['username'] ?? '',
                $config['adapter']['password'] ?? ''
            );
        } catch (PDOException $exception) {
            //TODO logger
        }
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
    }

    public static function getInstance(): self
    {
        $cls = static::class;
        if (! isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }
        return self::$instances[$cls];
    }

    public function getAdapter(): PDO
    {
        return $this->adapter;
    }
}
