<?php
declare(strict_types=1);

namespace Examples\ConsoleAccess;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class HelloWorldCommand extends Command
{

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        // the name of the command (the part after "bin/console.php")
        $this->setName('examples:hello-world');

        // the short description shown while running "php bin/console list"
        $this->setDescription('Hello world example.');

        // the full command description shown when running the command with the "--help" option
        $this->setHelp('Hello world help...');

        // hide command or show command
        $this->setHidden(false);

        // add arguments
        #$this->addArgument('name', InputArgument::REQUIRED, 'Please enter your name', null);

        // add options
        #$this->addOption('xvalue', null, InputOption::VALUE_REQUIRED, 'xvalue description', null);
    }

    /**
     * Executes the current command.
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface $input An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     * @throws \LogicException When this abstract method is not implemented
     * @see setCode()
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello world!');
        return null;
    }

    /**
     * Initializes the command just after the input has been validated.
     * This is mainly useful when a lot of commands extends one main command
     * where some things need to be initialized based on the input arguments and options.
     *
     * @param InputInterface $input An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
    }

    /**
     * Interacts with the user.
     * This method is executed before the InputDefinition is validated.
     * This means that this is the only place where the command can
     * interactively ask for values of missing required arguments.
     *
     * @param InputInterface $input An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
    }

}