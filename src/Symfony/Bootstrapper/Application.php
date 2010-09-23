<?php

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\Bootstrapper;

use Symfony\Bootstrapper\InitCommand;
use Symfony\Component\Console\Application as BaseApplication;

/**
 * Application.
 *
 * @author Fabien Potencier <fabien.potencier@symfony-project.org>
 */
class Application extends BaseApplication
{
    const VERSION = '2.0.0-DEV';

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('Symfony Bootstrapper', self::VERSION);

        $this->addCommand(new InitCommand());
    }
}
