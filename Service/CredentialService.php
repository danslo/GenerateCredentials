<?php

namespace Danslo\GenerateCredentials\Service;

use Danslo\GenerateCredentials\Model\Credential;
use Danslo\GenerateCredentials\Model\CredentialFactory;
use Danslo\GenerateCredentials\Model\ResourceModel\Credential as CredentialResource;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Store\Model\StoreManagerInterface;

class CredentialService
{
    const CONFIG_PATH_REPO_URL    = 'generate_credentials/general/repo_url';
    const CONFIG_PATH_MODULE_NAME = 'generate_credentials/general/module_name';

    /**
     * @var CredentialFactory
     */
    private $credentialFactory;

    /**
     * @var CredentialResource
     */
    private $credentialResource;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param CredentialFactory $credentialFactory
     * @param CredentialResource $credentialResource
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        CredentialFactory $credentialFactory,
        CredentialResource $credentialResource,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->credentialFactory = $credentialFactory;
        $this->credentialResource = $credentialResource;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Sends the credentials e-mail.
     *
     * @param OrderInterface $order
     * @param Credential $credential
     */
    public function emailCredential($order, $credential)
    {
        $senderEmail = $this->scopeConfig->getValue('trans_email/ident_general/email');
        $transport = $this->transportBuilder
            ->setTemplateIdentifier('credentials_email_template')
            ->setTemplateOptions([
                'area'  => Area::AREA_FRONTEND,
                'store' => $this->storeManager->getDefaultStoreView()->getId()
            ])
            ->setTemplateVars([
                'order'         => $order,
                'credential'    => $credential,
                'repositoryUrl' => $this->scopeConfig->getValue(self::CONFIG_PATH_REPO_URL),
                'moduleName'    => $this->scopeConfig->getValue(self::CONFIG_PATH_MODULE_NAME)
            ])
            ->setFrom([
                'name'  => $this->scopeConfig->getValue('trans_email/ident_general/name'),
                'email' => $senderEmail,
            ])
            ->addTo($order->getCustomerEmail())
            ->setReplyTo($senderEmail)
            ->getTransport();

        $transport->sendMessage();
    }

    /**
     * Creates new repo credentials for specific order ID.
     *
     * @param int $orderId
     * @return Credential
     */
    public function createCredential($orderId)
    {
        /** @var Credential $credential */
        $credential = $this->credentialFactory->create();
        $credential->setData([
            'order_id' => $orderId,
            'username' => bin2hex(random_bytes(16)),
            'password' => bin2hex(random_bytes(16))
        ]);
        $this->credentialResource->save($credential);
        return $credential;
    }
}