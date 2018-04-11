<?php

namespace Danslo\GenerateCredentials\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Credential extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('repo_credentials', 'credential_id');
    }
}