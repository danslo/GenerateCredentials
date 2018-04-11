<?php

namespace Danslo\GenerateCredentials\Model\ResourceModel\Credential;

use Danslo\GenerateCredentials\Model\Credential;
use Danslo\GenerateCredentials\Model\ResourceModel\Credential as CredentialResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(Credential::class, CredentialResource::class);
    }
}
