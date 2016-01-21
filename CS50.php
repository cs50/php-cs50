<?php

    /**
     * @author David J. Malan <dmalan@harvard.edu>
     * @link https://manual.cs50.net/CS50_Library
     * @package CS50
     * @version 0.10
     *
     * Creative Commons Attribution-ShareAlike 3.0 Unported Licence
     * http://creativecommons.org/licenses/by-sa/3.0/
     */

    class CS50
    {
        /**
         * Returns URL to which user can be directed for 
         * authentication via CS50 ID.
         *
         * @param directory   path to directory in which to store state
         * @param trust_root  URL that CS50 ID should prompt user to trust
         * @param return_to   URL to which CS50 ID should return user
         *
         * @return URL for CS50 ID
         */
        static function getLoginUrl($directory, $trust_root, $return_to)
        {
            // ignore Janrain's use of deprecated functions
            $error_reporting = error_reporting();
            error_reporting($error_reporting ^ E_DEPRECATED);

            // require Janrain OpenID libary
            $include_path = get_include_path();
            set_include_path($include_path . PATH_SEPARATOR . dirname(__FILE__) . "/lib/openid-php-openid-2.2.2");
            require_once("Auth/OpenID/AX.php");
            require_once("Auth/OpenID/Consumer.php");
            require_once("Auth/OpenID/FileStore.php");
            require_once("Auth/OpenID/SReg.php");

            // ensure $_SESSION exists for Yadis
            @session_start();

            // prepare request
            $consumer = new Auth_OpenID_Consumer(new Auth_OpenID_FileStore($directory));
            $auth_request = $consumer->begin("https://id.cs50.net/");

            // request Simple Registration fields
            $sreg_request = Auth_OpenID_SRegRequest::build(array("email"), array("fullname"));
            $auth_request->addExtension($sreg_request);

            // generate URL for redirection
            $redirect_url = $auth_request->redirectURL($trust_root, $return_to);

            // restore error_reporting
            error_reporting($error_reporting);

            // restore include_path
            set_include_path($include_path);

            // return URL unless error
            if (Auth_OpenID::isFailure($redirect_url))
            {
                trigger_error($redirect_url->message);
                return false;
            }
            else
                return $redirect_url;
        }
 
        /**
         * Iff user was authenticated (at URL returned by getLoginUrl),
         * returns associative array that WILL contain user's Harvard email
         * address (mail) and that MAY contain user's name (displayName).
         *
         * @param directory  path to directory in which to store state
         * @param return_to  URL to which CS50 ID returned user
         *
         * @return user as associative array
         */
        static function getUser($directory, $return_to)
        {
            // ignore Janrain's use of deprecated functions
            $error_reporting = error_reporting();
            error_reporting($error_reporting ^ E_DEPRECATED);

            // require Janrain OpenID libary
            $include_path = get_include_path();
            set_include_path($include_path . PATH_SEPARATOR . dirname(__FILE__) . "/lib/openid-php-openid-2.2.2");
            require_once("Auth/OpenID/AX.php");
            require_once("Auth/OpenID/Consumer.php");
            require_once("Auth/OpenID/FileStore.php");
            require_once("Auth/OpenID/SReg.php");

            // ensure $_SESSION exists for Yadis
            @session_start();

            // get response
            $consumer = new Auth_OpenID_Consumer(new Auth_OpenID_FileStore($directory));
            $response = $consumer->complete($return_to);
            if ($response->status == Auth_OpenID_SUCCESS)
            {
                // get Simple Registration fields, if any
                $sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
                $contents = $sreg_resp->contents();

                // get Attribute Exchange attributes, if any
                $ax_resp = Auth_OpenID_AX_FetchResponse::fromSuccessResponse($response);
                $data = $ax_resp->data;

                // get user's identifier
                if (preg_match("#^https://id.cs50.net/([0123456789abcdef]{64})$#", $response->identity_url, $matches))
                    $user = array("id" => $matches[1]);
                else
                    return false;

                // get user's mail, if any
                if (isset($contents["email"]))
                    $user["email"] = $contents["email"];
                else if (isset($data["http://axschema.org/contact/email"]))
                    $user["email"] = $data["http://axschema.org/contact/email"];

                // get user's displayName, if any
                if (isset($contents["fullname"]))
                    $user["name"] = $contents["fullname"];
                else if (isset($data["http://axschema.org/contact/namePerson"]))
                    $user["name"] = $data["http://axschema.org/contact/namePerson"];

                // return user
                return $user;
            }
            else
                return false;
        }
    }

?>
