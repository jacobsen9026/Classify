<?php

/*
 * The MIT License
 *
 * Copyright 2019 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace App\Auth;

/**
 * Description of LDAP
 *
 * @author cjacobsen
 */

use App\Models\Database\AuthDatabase;
use App\Models\User\User;
use System\App\Auth\AuthException;
use App\Api\AD;
use App\Models\Database\PrivilegeLevelDatabase;
use System\App\AppLogger;
use System\Traits\DomainTools;

class ADAuth
{

    /**
     * Load Domain Tools Trait
     *
     */
    use DomainTools;

    private $connection;

//put your code here
    public function authenticate($username, $password)
    {

        $passed = false;
        $username = strtolower($username);
        $logger = AppLogger::get();
        $server = AuthDatabase::getLDAPServer();
        $domain = AuthDatabase::getLDAP_FQDN();

// Prepare connection username by appending domain name if not already provided
        if (!is_null($domain) and !strpos($username, $domain)) {
            $ldapUser = $username . "@" . $domain;
        }
// Connect to LDAP server
        $this->connection = AD::connect($server, $ldapUser, $password);


        if (is_resource($this->connection)) {
            $superAdmin = false;
            $userLevels = [];
            $adAPI = AD::get();
            $allPrivilegeLevels = PrivilegeLevelDatabase::get();
            foreach ($allPrivilegeLevels as $privilegeLevel) {
                $logger->info($privilegeLevel->getAdGroup());
                if ($adAPI->isUserInGroup($username, $privilegeLevel->getAdGroup())) {
                    if ($privilegeLevel->getSuperAdmin()) {
                        $superAdmin = true;
                    }
                    $userLevels[] = $privilegeLevel;
                    $passed = true;
                }
            }
            $logger->info($userLevels);
            $logger->info($adAPI->isUserInGroup($username, 'SAM Tech'));
//return false;



// If bind was successful, user was found in LDAP continue processing

            $logger->info("LDAP bind successful to " . $server . " using credentials: " . $ldapUser);


            if ($passed) {

                //var_dump("here");
                $adUser = $adAPI->getDomainUser($username);
                // var_dump($adUser);
                $logger->info($username . " successfully logged in");

                if (!$fullName = $adUser['displayname'][0]) {
                    $fullName = $username;
                }
                $user = new User();
                $user->authenticated()
                    ->setFullName($fullName)
                    ->setUsername($username)
                    ->setPrivilegeLevels($userLevels)
                    ->setSuperUser($superAdmin);
                $logger->info($user);
                return $user;

            } else {
                throw new AuthException(AuthException::NOT_AUTHORIZED);
            }

        } else {
            throw new AuthException(AuthException::NOT_AUTHORIZED);
        }
    }

    /**
     *
     * @param type $connection
     * @param type $baseDN
     * @param type $groupName
     *
     * @return string
     */
    public static function getGroupDN($connection, $baseDN, $groupName)
    {
        $filter = "(&(objectClass=group)(cn=" . $groupName . "))";
        $result = ldap_search($connection, $baseDN, $filter);
        $info = ldap_get_entries($connection, $result);
//var_dump($info);
        if (is_array($info)) {
            if (key_exists("count", $info)) {
                if ($info["count"] == 1) {
                    if (key_exists("distinguishedname", $info[0])) {
                        return $info[0]["distinguishedname"][0];
                    }
                }
            }
        }
    }

    public static function testConnection($server, $username, $password)
    {
        $logger = AppLogger::get();
        $ldapconn = ldap_connect("ldap://" . $server)

        or die("Could not connect to LDAP server.");

        if ($ldapconn) {

// binding anonymously
            $ldapbind = ldap_bind($ldapconn, $username, $password);

            if ($ldapbind) {
                $logger->info("LDAP Auth bind successful to " . $server . " using credentials: " . $username . ' ' . $password);
                ldap_unbind($ldapconn);
                return true;
            } else {
                $logger->warning("LDAP Auth bind failed  to " . $server . " using credentials: " . $username . ' ' . $password);
                $error = ldap_error($ldapconn);
                ldap_close($ldapconn);
                return $error;
            }
        }
    }

}
