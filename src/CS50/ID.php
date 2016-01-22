<?php

    namespace CS50;

    require_once("vendor/autoload.php");

    /**
     * CS50's client for OpenID Connect.
     */
    class ID
    {
        /**
         * Client's OpenID provider.
         */
        private $provider;

        /**
         * Configures a GenericProvider for CS50 ID.
         */
        public function __construct($client_id, $client_secret, $redirect_uri, $scope)
        {
            $this->provider = new \League\OAuth2\Client\Provider\GenericProvider([
                "clientId" => $client_id,
                "clientSecret" => $client_secret,
                "redirectUri" => $redirect_uri,
                "scopes" => $scope,
                "urlAccessToken" => "http://id.cs50.net/token", // TODO: change these to https
                "urlAuthorize" => "http://id.cs50.net/authorize",
                "urlResourceOwnerDetails" => "http://id.cs50.net/userinfo"
            ]);
        }

        /**
         * Authenticates user via CS50 ID. If user is returning from CS50 ID,
         * returns associative array of user's claims, else redirects to CS50 ID
         * for authentication.
         *
         * @param string client_id
         * @param string client_secret
         * @param string scope
         *
         * @return array
         */
        public static function authenticate($client_id, $client_secret, $scope = "openid profile")
        {
            // validate scope
            // https://tools.ietf.org/html/rfc6749#appendix-A.4
            if (!preg_match("/^[\x{21}\x{23}-\x{5B}\x{5D}-\x{7E}]([ \x{21}\x{23}-\x{5B}\x{5D}-\x{7E}])*$/", $scope))
            {
                trigger_error("invalid scope", E_USER_ERROR);
            }

            // redirection URI
            try
            {
                // sans username and password (and fragment)
                $uri = \League\Uri\Schemes\Http::createFromServer($_SERVER)->withUserInfo("");

                // sans code and state (which are reserved by OAuth2)
                $modifier = new \League\Uri\Modifiers\RemoveQueryKeys(["code", "state"]);
                $redirect_uri = $modifier->__invoke($uri)->__toString();
            }
            catch (\Exception $e)
            {
                trigger_error("unable to infer redirect_uri", E_USER_ERROR);
            }

            // configure client
            $id = new ID($client_id, $client_secret, $redirect_uri, $scope);

            // if user is returning from CS50 ID, return claims
            if (isset($_GET["code"], $_GET["state"]))
            {
                return $id->getUser();
            }
            
            // redirect to CS50 ID
            header("Location: " . $id->getLoginUrl());
            exit;
        }

        /**
         * Returns URL to which user should be redirected for authentication via CS50 ID.
         *
         * @return string URL
         */
        public function getLoginUrl()
        {
            // deprecate old usage
            if (func_num_args() !== 0)
            {
                trigger_error("too many arguments", E_USER_ERROR);
            }

            // return OP Endpoint URL with CSRF protection
            // https://tools.ietf.org/html/rfc6749#section-10.12
            @session_start();
            return $this->provider->getAuthorizationUrl(["state" => hash("sha256", session_id())]);
        }

        /**
         * Gets claims from an Authorization Response.
         *
         * @return array|false claims
         */
        public function getUser()
        {
            // deprecate old usage
            if (func_num_args() !== 0)
            {
                trigger_error("too many arguments", E_USER_ERROR);
            }
 
            // if returning from CS50 ID
            if (!isset($_GET["code"]))
            { 
                trigger_error("missing code", E_USER_ERROR);
            }

            // validate state to prevent CSRF
            // http://www.twobotechnologies.com/blog/2014/02/importance-of-state-in-oauth2.html
            if (!isset($_GET["state"]))
            {
                trigger_error("missing state in request", E_USER_WARNING);
                return false;
            }
            @session_start();
            if ($_GET["state"] !== hash("sha256", session_id()))
            {
                trigger_error("invalid state", E_USER_WARNING);
                return false;
            }
              
            // exchange code for token
            try
            {
                $token = $this->provider->getAccessToken("authorization_code", ["code" => $_GET["code"]]);
            }
            catch (\Exception $e)
            {
                trigger_error($e->getMessage(), E_USER_NOTICE);
                return false;
            }

            // get UserInfo with token
            try
            {
                $owner = $this->provider->getResourceOwner($token);
                return $owner->toArray();
            }
            catch (\Exception $e)
            {
                trigger_error($e->getMessage(), E_USER_NOTICE);
                return false;
            }
        }
    }

?>
