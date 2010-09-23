<?php

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once __DIR__.'/../src/autoload.php';

use Symfony\Bootstrapper\Compiler;

$compiler = new Compiler();
$compiler->compile();
