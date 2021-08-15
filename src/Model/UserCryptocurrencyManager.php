<?php

namespace Snowdog\Academy\Model;

use Snowdog\Academy\Core\Database;

class UserCryptocurrencyManager
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getCryptocurrenciesByUserId(int $userId): array
    {
        $query = $this->database->prepare('SELECT c.id, c.name, uc.amount FROM user_cryptocurrencies AS uc LEFT JOIN cryptocurrencies AS c ON uc.cryptocurrency_id = c.id WHERE uc.user_id = :user_id');
        $query->bindParam(':user_id', $userId, Database::PARAM_INT);
        $query->execute();

        return $query->fetchAll(Database::FETCH_CLASS, UserCryptocurrency::class);
    }

    public function addCryptocurrencyToUser(int $userId, Cryptocurrency $cryptocurrency, int $amount, bool $userHaveThisCrypto): void
    {
        $cryptocurrencyId = $cryptocurrency->getId();

        if ($userHaveThisCrypto) {
            $query = $this->database->prepare("UPDATE user_cryptocurrencies SET amount = amount + :amount WHERE 
            user_id = :user_id AND
            cryptocurrency_id = :cryptocurrency_id");

            $query->execute([
            ':amount' => $amount,
            ':user_id' => $userId,
            ':cryptocurrency_id' => $cryptocurrencyId
            ]);
        } else {
            $query = $this->database->prepare("INSERT INTO user_cryptocurrencies (user_id, cryptocurrency_id, amount) VALUES (:userId, :cryptocurrencyId, :amount)");
            
            $query->execute([
            ':userId' => $userId,
            ':cryptocurrencyId' => $cryptocurrencyId,
            ':amount' => $amount,
            ]);
        }
    }

    public function subtractCryptocurrencyFromUser(int $userId, Cryptocurrency $cryptocurrency, int $amount): void
    {
        // TODO
    }

    public function getUserCryptocurrency(int $userId, string $cryptocurrencyId): ?UserCryptocurrency
    {
        $query = $this->database->prepare('SELECT * FROM user_cryptocurrencies WHERE user_id = :user_id AND cryptocurrency_id = :cryptocurrency_id');
        $query->bindParam(':user_id', $userId, Database::PARAM_INT);
        $query->bindParam(':cryptocurrency_id', $cryptocurrencyId, Database::PARAM_STR);
        $query->execute();

        /** @var UserCryptocurrency $result */
        $result = $query->fetchObject(UserCryptocurrency::class);

        return $result ?: null;
    }
}
