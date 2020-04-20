<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;

class ImporterAction
{
    /**
     * @var User
     */
    private $user;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;

        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
        }
    }

    public function execute()
    {
        require __DIR__ . '/../view/importer.phtml';
    }
}