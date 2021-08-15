<?php

namespace Snowdog\Academy\Command;

use Exception;
use Snowdog\Academy\Core\Migration;
use Snowdog\Academy\Model\CryptocurrencyManager;
use Symfony\Component\Console\Output\OutputInterface;

class UpdatePrices
{
    private CryptocurrencyManager $cryptocurrencyManager;

    public function __construct(CryptocurrencyManager $cryptocurrencyManager)
    {
        $this->cryptocurrencyManager = $cryptocurrencyManager;
    }

    public function __invoke(OutputInterface $output)
    {
        $url = "https://api.coincap.io/v2/assets/";

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        
        $r = json_decode($response);

        try
        {
            if($r == NULL) throw new Exception('Sorry API lose connection, try again.');
            $array = json_decode(json_encode($r), true);
            $array = $array['data'];

            $allCryptocurrencies = $this->cryptocurrencyManager->getAllCryptocurrencies();
        
            foreach($allCryptocurrencies as $crypto)
            {
                $idCrypto = $crypto->getId();
                foreach($array as $arr)
                {
                    if ($arr['id'] == $idCrypto) {
                        $this->cryptocurrencyManager->updatePrice($idCrypto, $arr['priceUsd']);
                        break;
                    }
                }         
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
        
    }
}
