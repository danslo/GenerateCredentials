<?php

namespace Danslo\GenerateCredentials\Command;

use Danslo\GenerateCredentials\Model\ResourceModel\Credential\Collection as CredentialCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
    /**
     * @var CredentialCollection
     */
    private $credentialCollection;

    /**
     * @param CredentialCollection $credentialCollection
     */
    public function __construct(CredentialCollection $credentialCollection)
    {
        parent::__construct();
        $this->credentialCollection = $credentialCollection;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('repo:generate-credentials')
            ->setDescription('Generates package repository credential list.');
    }

    /**
     * Generates a basic auth credentials line.
     *
     * @param string $username
     * @param string $password
     * @return string
     */
    private function generateLine($username, $password)
    {
        return $username . ':{SHA}' . base64_encode(sha1($password, true));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contents = '';
        foreach ($this->credentialCollection as $credential) {
            $contents .= $this->generateLine($credential->getData('username'), $credential->getData('password')) . "\n";
        }
        echo $contents;
    }
}