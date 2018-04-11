<?php

namespace Danslo\GenerateCredentials\Observer;

use Danslo\GenerateCredentials\Service\CredentialService;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;

class CreateCredentialObserver implements ObserverInterface
{
    /**
     * @var CredentialService
     */
    private $credentialService;

    /**
     * @param CredentialService $credentialService
     */
    public function __construct(CredentialService $credentialService)
    {
        $this->credentialService = $credentialService;
    }

    /**
     * Creates and e-mails credentials on order completion.
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getEvent()->getData('order');
        if ($order->getState() == 'complete') {
            $credential = $this->credentialService->createCredential($order->getId());
            $this->credentialService->emailCredential($order, $credential);
        }
    }
}