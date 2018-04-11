<?php

namespace Danslo\GenerateCredentials\Model;

use Danslo\GenerateCredentials\Model\ResourceModel\Credential as CredentialResource;
use Magento\Framework\Model\AbstractModel;

class Credential extends AbstractModel
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(CredentialResource::class);
    }
}
