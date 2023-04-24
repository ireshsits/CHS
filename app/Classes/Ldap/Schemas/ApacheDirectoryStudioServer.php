<?php

namespace App\Classes\Ldap\Schemas;

use Adldap\Schemas\OpenLDAP;

class ApacheDirectoryStudioServer extends OpenLDAP
{
    /**
     * {@inheritdoc}
     */
    public function objectClassGroup()
    {
        return 'posixgroup';
    }

    /**
     * {@inheritdoc}
     */
    public function member()
    {
        return 'memberuid';
    }

    /**
     * {@inheritdoc}
     */
    public function memberIdentifier()
    {
        return 'dn';
    }
}