<?php
namespace App\Classes\Ldap\Rules;

use Adldap\Laravel\Validation\Rules\Rule;
use Illuminate\Support\Str; // Laravel >= 5.7

class OnlyOUs extends Rule
{
    /**
     * Determines if the user is allowed to authenticate.
     *
     * @return bool
     */   
    public function isValid()
    {
        $ous = [
        		'OU=Branches,OU=Sampath Bank,'.env ( 'LDAP_BASE_DN', '' ),
        		'OU=HO,OU=Sampath Bank,'.env ( 'LDAP_BASE_DN', '' ),
        ];
        
        // Laravel <= 5.6
//         return str_contains($this->user->getDN(), $ous);

        // Laravel >= 5.7
        return Str::contains($this->user->getDn(), $ous);
    }
}