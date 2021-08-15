<?php

namespace Snowdog\Academy\Controller;

use Snowdog\Academy\Model\Cryptocurrency;
use Snowdog\Academy\Model\CryptocurrencyManager;
use Snowdog\Academy\Model\UserCryptocurrencyManager;
use Snowdog\Academy\Model\UserManager;

class Cryptos
{
    private CryptocurrencyManager $cryptocurrencyManager;
    private UserCryptocurrencyManager $userCryptocurrencyManager;
    private UserManager $userManager;
    private Cryptocurrency $cryptocurrency;

    public function __construct(
        CryptocurrencyManager $cryptocurrencyManager,
        UserCryptocurrencyManager $userCryptocurrencyManager,
        UserManager $userManager
    ) {
        $this->cryptocurrencyManager = $cryptocurrencyManager;
        $this->userCryptocurrencyManager = $userCryptocurrencyManager;
        $this->userManager = $userManager;
    }

    public function index(): void
    {
        require __DIR__ . '/../view/cryptos/index.phtml';
    }

    public function buy(string $id): void
    {
        $user = $this->userManager->getByLogin((string) $_SESSION['login']);
        if (!$user) {
            header('Location: /cryptos');
            return;
        }

        $cryptocurrency = $this->cryptocurrencyManager->getCryptocurrencyById($id);
        if (!$cryptocurrency) {
            header('Location: /cryptos');
            return;
        }

        $this->cryptocurrency = $cryptocurrency;
        require __DIR__ . '/../view/cryptos/buy.phtml';
    }

    public function buyPost(string $id): void
    {
        $user = $this->userManager->getByLogin((string) $_SESSION['login']);
        if (!$user) {
            header('Location: /cryptos');
            return;
        }

        $cryptocurrency = $this->cryptocurrencyManager->getCryptocurrencyById($id);
        if (!$cryptocurrency) {
            header('Location: /cryptos');
            return;
        }

        $amount = $_POST['amount'];
        $userId = $user->getId();
        $userFunds = $user->getFunds();
        $cryptocurrencyId = $cryptocurrency->getId();
        $cryptocurrencyPrice = $cryptocurrency->getPrice();
        $cost = $cryptocurrencyPrice * $amount;
        $userHaveThisCrypto = true;

        ($this->userCryptocurrencyManager->getUserCryptocurrency($userId, $cryptocurrencyId))
        ? $userHaveThisCrypto = true
        : $userHaveThisCrypto = false;

        if ($cost > $userFunds) {
            $_SESSION['flash'] = "Sorry, you have not enough money";
            header('Location: /cryptos');
            return;
        } else {
            $this->userCryptocurrencyManager->addCryptocurrencyToUser($userId, $cryptocurrency, $amount, $userHaveThisCrypto);
            $this->userManager->balanceFundsbyBuyCryptocurrency($cost, $userId);
            $_SESSION['flash'] = "Successful transaction, congratulations.";
        }
        
        header('Location: /cryptos');
    }

    public function sell(string $id): void
    {
        $user = $this->userManager->getByLogin((string) $_SESSION['login']);
        if (!$user) {
            header('Location: /account');
            return;
        }

        $cryptocurrency = $this->cryptocurrencyManager->getCryptocurrencyById($id);
        if (!$cryptocurrency) {
            header('Location: /account');
            return;
        }

        $this->cryptocurrency = $cryptocurrency;
        require __DIR__ . '/../view/cryptos/sell.phtml';
    }

    public function sellPost(string $id): void
    {
        $user = $this->userManager->getByLogin((string) $_SESSION['login']);
        if (!$user) {
            header('Location: /cryptos');
            return;
        }

        $cryptocurrency = $this->cryptocurrencyManager->getCryptocurrencyById($id);
        if (!$cryptocurrency) {
            header('Location: /cryptos');
            return;
        }

        $amount = $_POST['amount'];
        $cryptocurrencyPrice = $cryptocurrency->getPrice();
        $cryptocurrencyCost = $cryptocurrencyPrice * $amount;
        $userId = $user->getId();
        $cryptocurrencyId = $cryptocurrency->getId();
        $userAmountCrypto = $this->userCryptocurrencyManager->getUserCryptocurrency($userId, $cryptocurrencyId)->getAmount();

        if ($amount > $userAmountCrypto) {
            // jesli uzytkownik chce sprzedac wiecej niz ma
            $_SESSION['flash'] = "Sorry, you have not enough Crypto Currency";
            header('Location: /cryptos');
            return;
        } else {
            $this->userCryptocurrencyManager->subtractCryptocurrencyFromUser($userId, $cryptocurrency, $amount);
            $this->userManager->balanceFundsBySellCryptocurrency($cryptocurrencyCost, $userId);
            $_SESSION['flash'] = "Successful transaction, congratulations.";
        }
    
        header('Location: /cryptos');
    }

    public function getCryptocurrencies(): array
    {
        return $this->cryptocurrencyManager->getAllCryptocurrencies();
    }
}