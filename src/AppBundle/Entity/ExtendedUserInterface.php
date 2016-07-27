<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface ExtendedUserInterface
{

    /**
     * Returns the user unique id.
     *
     * @return mixed
     */
    public function getPerson();
}
