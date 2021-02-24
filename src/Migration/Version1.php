<?php

namespace Snowdog\Academy\Migration;

use Snowdog\Academy\Core\Database;
use Snowdog\Academy\Model\UserManager;

class Version1
{
    private Database $database;
    private UserManager $userManager;

    public function __construct(Database $database, UserManager $userManager)
    {
        $this->database = $database;
        $this->userManager = $userManager;
    }

    public function __invoke()
    {
        $this->createUsersTable();
        $this->addUsers();
    }

    private function createUsersTable(): void
    {
        $createQuery = <<<SQL
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(128) NOT NULL,
  `funds` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($createQuery);
    }

    private function addUsers(): void
    {
        $this->userManager->create('john', 'snowdog', 10000);
        $this->userManager->create('baca', 'zaq12wsx', 200000);
        $this->userManager->create('maca', 'xsw23edc', 5000);
        $this->userManager->create('onuca', 'cde34rfv', 1000);
    }
}
