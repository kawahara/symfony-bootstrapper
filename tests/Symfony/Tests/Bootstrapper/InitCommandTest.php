<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Tests\Bootstrapper;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Bootstrapper\InitCommand;
use Symfony\Bundle\FrameworkBundle\Util\Filesystem;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpFoundation\Request;

class InitCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getFormat
     * @runInSeparateProcess
     */
    public function testExecution($format)
    {
        $tmpDir = sys_get_temp_dir().'/sf_hello';
        $filesystem = new Filesystem();
        $filesystem->remove($tmpDir);
        $filesystem->mkdirs($tmpDir);

        chdir($tmpDir);

        $tester = new CommandTester(new InitCommand());
        $tester->execute(array(
            '--name'     => 'Hello'.$format,
            '--app-path' => 'hello'.$format,
            '--web-path' => 'web',
            '--src-path' => 'src',
            '--format'   => $format,
        ));

        // autoload
        $content = file_get_contents($file = $tmpDir.'/src/autoload.php');
        $content = str_replace(
            "__DIR__.'/vendor",
            "'".__DIR__."/../../../../src/vendor",
            $content
        );
        file_put_contents($file, $content);

        // Kernel
        $class = 'Hello'.$format.'Kernel';
        $file = $tmpDir.'/hello'.$format.'/'.$class.'.php';
        $this->assertTrue(file_exists($file));

        $content = file_get_contents($file);
        $content = str_replace(
            "__DIR__.'/../src/vendor/Symfony/src/Symfony/Bundle'",
            "'".__DIR__."/../../../../src/vendor/Symfony/src/Symfony/Bundle'",
            $content
        );
        file_put_contents($file, $content);

        require_once $file;

        $kernel = new $class('dev', true);
        $response = $kernel->handle(Request::create('/'));

        $this->assertRegExp('/successfully/', $response->getContent());

        $filesystem->remove($tmpDir);
    }

    public function getFormat()
    {
        return array(
            array('xml'),
            array('yml'),
            array('php'),
        );
    }

    protected function prepareTemplate(\Text_Template $template)
    {
        $template->setFile(__DIR__.'/TestCaseMethod.tpl');
    }
}
