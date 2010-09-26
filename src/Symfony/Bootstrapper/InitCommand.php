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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Util\Filesystem;
use Symfony\Bundle\FrameworkBundle\Util\Mustache;

/**
 * Initializes a new Symfony project.
 *
 * @author Fabien Potencier <fabien.potencier@symfony-project.org>
 */
class InitCommand extends Command
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputOption('name', '', InputOption::PARAMETER_REQUIRED, 'The application name (App)', 'App'),
                new InputOption('app-path', '', InputOption::PARAMETER_REQUIRED, 'The path to the application (app/)', 'app/'),
                new InputOption('src-path', '', InputOption::PARAMETER_REQUIRED, 'The path to the application (src/)', 'src/'),
                new InputOption('web-path', '', InputOption::PARAMETER_REQUIRED, 'The path to the public web root (web/)', 'web/'),
                new InputOption('format', '', InputOption::PARAMETER_REQUIRED, 'Use the format for configuration files (php, xml, or yml)', 'xml'),
            ))
            ->setName('init')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $finder = new Finder();
        if (iterator_count($finder->in(getcwd()))) {
            throw new \RuntimeException('The current directory is not empty.');
        }

        $parameters = array(
            'class'       => $input->getOption('name'),
            'application' => strtolower($input->getOption('name')),
            'format'      => $input->getOption('format'),
        );

        $filesystem = new Filesystem();

        $skeletonDir = __DIR__.'/../../skeleton';

        $appPath = getcwd().'/'.$input->getOption('app-path');
        $srcPath = getcwd().'/'.$input->getOption('src-path');
        $webPath = getcwd().'/'.$input->getOption('web-path');

        $filesystem->mirror($skeletonDir.'/application/generic', $appPath);
        $filesystem->mirror($skeletonDir.'/application/'.$input->getOption('format'), $appPath);
        $filesystem->mirror($skeletonDir.'/src', $srcPath);

        Mustache::renderDir($appPath, $parameters);

        $filesystem->chmod($appPath.'/console', 0777);
        $filesystem->chmod($appPath.'/logs', 0777);
        $filesystem->chmod($appPath.'/cache', 0777);

        $filesystem->rename($appPath.'/Kernel.php', $appPath.'/'.$input->getOption('name').'Kernel.php');
        $filesystem->rename($appPath.'/Cache.php', $appPath.'/'.$input->getOption('name').'Cache.php');

        $filesystem->copy($skeletonDir.'/web/front_controller.php', $file = $webPath.'/'.(file_exists($webPath.'/index.php') ? strtolower($input->getOption('name')) : 'index').'.php');
        Mustache::renderFile($file, $parameters);

        $filesystem->copy($skeletonDir.'/web/front_controller_debug.php', $file = $webPath.'/'.strtolower($input->getOption('name')).'_dev.php');
        Mustache::renderFile($file, $parameters);
    }
}
