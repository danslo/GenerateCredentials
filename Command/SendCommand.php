<?php

namespace Danslo\GenerateCredentials\Command;

use Danslo\GenerateCredentials\Model\Credential;
use Danslo\GenerateCredentials\Model\ResourceModel\Credential\Collection as CredentialCollection;
use Danslo\GenerateCredentials\Service\CredentialService;
use Magento\Framework\App\State;
use Magento\Sales\Api\OrderRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendCommand extends Command
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var CredentialCollection
     */
    private $credentialCollection;

    /**
     * @var CredentialService
     */
    private $credentialService;

    /**
     * @param State $state
     * @param OrderRepositoryInterface $orderRepository
     * @param CredentialCollection $credentialCollection
     * @param CredentialService $credentialService
     */
    public function __construct(
        State $state,
        OrderRepositoryInterface $orderRepository,
        CredentialCollection $credentialCollection,
        CredentialService $credentialService
    ) {
        parent::__construct();
        $state->setAreaCode('adminhtml');
        $this->orderRepository = $orderRepository;
        $this->credentialCollection = $credentialCollection;
        $this->credentialService = $credentialService;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('repo:send-credentials')
            ->setDescription('Sends the credentials e-mail for a specific order ID.')
            ->addArgument('order_id', InputArgument::REQUIRED);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $orderId = $input->getArgument('order_id');
        $order = $this->orderRepository->get($orderId);

        /** @var Credential $credential */
        $credential = $this->credentialCollection->addFieldToFilter('order_id', ['eq' => $orderId])
            ->getFirstItem();

        $this->credentialService->emailCredential($order, $credential);
    }
}