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

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Compiler.
 *
 * @author Fabien Potencier <fabien.potencier@symfony-project.org>
 */
class Compiler
{
    public function compile()
    {
        if (file_exists('symfony.phar')) {
            unlink('symfony.phar');
        }

        $phar = new \Phar('symfony.phar', 0, 'Symfony');
        $phar->setSignatureAlgorithm(\Phar::SHA1);
        $phar->startBuffering();

        // Files
        foreach ($this->getFiles() as $file) {
            $path = str_replace(__DIR__.'/', '', $file);

            if (false !== strpos($file, '.php')) {
                $content = Kernel::stripComments(file_get_contents($file));
            } else {
                $content = file_get_contents($file);
            }

            $phar->addFromString($path, $content);
        }

        // Stubs
        $phar['_cli_stub.php'] = $this->getCliStub();
        $phar['_web_stub.php'] = $this->getWebStub();
        $phar->setDefaultStub('_cli_stub.php', '_web_stub.php');
        $phar->stopBuffering();
        //$phar->compressFiles(\Phar::GZ);

        unset($phar);
    }

    protected function getCliStub()
    {
        return <<<'EOF'
<?php

require_once __DIR__.'/src/autoload.php';

use Symfony\Bootstrapper\Application;

$application = new Application();
$application->run();

__HALT_COMPILER();
EOF;
    }

    protected function getWebStub()
    {
        return "<?php throw new \LogicException('This PHAR file can only be used from the CLI.'); __HALT_COMPILER();";
    }

    protected function getFiles()
    {
        $files = array(
            'LICENSE',
            'src/autoload.php',
            'src/vendor/symfony/src/Symfony/Component/HttpFoundation/UniversalClassLoader.php',
            'src/vendor/symfony/src/Symfony/Bundle/FrameworkBundle/Util/Filesystem.php',
            'src/vendor/symfony/src/Symfony/Bundle/FrameworkBundle/Util/Mustache.php',
        );

        $dirs = array(
            'src/vendor/symfony/src/Symfony/Component/Console',
            'src/vendor/symfony/src/Symfony/Component/Finder',
            'src/Symfony',
        );

        $iterator = new Finder();
        $iterator->files()->name('*.php')->in($dirs);

        $skeleton = new Finder();
        $skeleton->files()->notName('.DS_Store')->in('src/skeleton');

        return array_merge($files, iterator_to_array($iterator), iterator_to_array($skeleton));
    }
}
