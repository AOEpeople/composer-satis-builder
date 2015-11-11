<?php
namespace AOE\Composer\Satis\Generator\Command;

use AOE\Composer\Satis\Generator\Builder\SatisBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BuilderCommand extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('build')
            ->setDescription('Generate satis.json file from composer.json')
            ->addArgument(
                'composer',
                InputArgument::REQUIRED,
                'Path to composer.json file'
            )
            ->addArgument(
                'satis',
                InputArgument::REQUIRED,
                'Path to satis.json file'
            )
            ->addOption(
                'require-dev-dependencies',
                'rdd',
                InputOption::VALUE_REQUIRED,
                'sets "require-dev-dependencies"'
            )
            ->addOption(
                'require-dependencies',
                'rd',
                InputOption::VALUE_REQUIRED,
                'sets "require-dependencies"'
            )
            ->addOption(
                'add-requirements',
                'rc',
                InputOption::VALUE_NONE,
                'sets "require-dependencies"'
            )
            ->addOption(
                'add-dev-requirements',
                'drc',
                InputOption::VALUE_NONE,
                'sets "require-dependencies"'
            )
            ->addOption(
                'reset-requirements',
                'rr',
                InputOption::VALUE_NONE,
                'sets "require-dependencies"'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $satisFile = $input->getArgument('satis');
        if (false === file_exists($satisFile)) {
            throw new \InvalidArgumentException(sprintf('required file does not exists: "%s"', $satisFile), 1446115325);
        }
        $composerFile = $input->getArgument('composer');
        if (false === file_exists($composerFile)) {
            throw new \InvalidArgumentException(sprintf('required file does not exists: "%s"', $composerFile), 1446115336);
        }

        $satis = json_decode(file_get_contents($satisFile));
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(json_last_error() . ':' . json_last_error_msg(), 1447257223);
        }

        $composer = json_decode(file_get_contents($composerFile));
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(json_last_error() . ':' . json_last_error_msg(), 1447257260);
        }

        $builder = new SatisBuilder($composer, $satis);

        if ($input->getOption('reset-requirements')) {
            $builder->resetSatisRequires();
        }

        if ($input->getOption('require-dependencies')) {
            $builder->setRequireDependencies($input->getOption('require-dependencies'));
        }

        if ($input->getOption('require-dev-dependencies')) {
            $builder->setRequireDevDependencies($input->getOption('require-dependencies'));
        }

        if ($input->getOption('add-requirements')) {
            $builder->addRequiresFromComposer();
        }

        if ($input->getOption('add-dev-requirements')) {
            $builder->addDevRequiresFromComposer();
        }

        file_put_contents($satisFile, json_encode($builder->build(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}