<?php
namespace OCA\NextAD\Service;

use OCP\IL10N;
use OCP\LDAP\ILDAPProvider;
use OCP\LDAP\ILDAPProviderFactory;

class LDAPService {

    private $ldapProviderFactory;
    private $l10n;

    public function __construct(ILDAPProviderFactory $ldapProviderFactory, IL10N $l10n) {
        $this->ldapProviderFactory = $ldapProviderFactory;
        $this->l10n = $l10n;
    }

    public function connect() {
        $ldapProvider = $this->ldapProviderFactory->getLDAPProvider();

        // Get User UID from NextCloud Users or LDAP settings and pass it into getLDAPConnection() & getUserDN()
        $ldapConnection = $ldapProvider->getLDAPConnection("");

        $filter = "(objectClass=*)"; 
        $baseDn = $ldapProvider->getUserDN("");
        
        $search = ldap_search($ldapConnection, $baseDn, $filter);
        $entries = ldap_get_entries($ldapConnection, $search);
        
        $userData = [
            'displayName' => $entries[0]['displayname'][0],
            'company' => $entries[0]['company'][0],
            'streetAddress' => $entries[0]['streetaddress'][0],
            'email' => $entries[0]['mail'][0], 
            'telephone' => $entries[0]['telephonenumber'][0],
            'webPage' => $entries[0]['wwwhomepage'][0],
            'description' => $entries[0]['description'][0],
        ];
        
        return $userData; 
    }
}
