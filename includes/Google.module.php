<?php
/**
 * GOOGLE API MODULE
 */
require_once MODULES_DIR.'/Google/autoload.php';

class GoogleAppsAPI extends Singleton
{
	protected $application_name = "Avy-Google";
	protected $client_secret_path = FALSE;
	protected $credential_path = FALSE;
	protected $scopes = FALSE;
	protected $scopelist = array('gmail','calendar','task','drive');
        protected $clientConnection  = false;

	public function _init()
	{
            //Set variables
            $this->client_secret_path = DOCUMENT_ROOT.'client_secret.json';
            $this->credential_path = TEMP_DIR.'google-credential.json';
            $this->setScope(true);
	}

	public function setCredentialPath($path){
		if($path) {
			// Do some validation
			$this->credential_path = $path;
			return true;
		}
	}

	public function setClientSecret($path){
		if($path) {
			// Do some validation
			$this->client_secret_path = $path;
			return true;
		}
	}

	public function setScope($scopes)
	{
		if(is_array($scopes)){
			$scopelist = $scopes;
		}else{
			$scopelist = $this->scopelist;
		}

		foreach($scopelist as $scope){
			switch ($scope){
				case "gmail":
					$this->scopes[1] = Google_Service_Gmail::GMAIL_READONLY;
					break;
				case "calendar":
					$this->scopes[2] = Google_Service_Calendar::CALENDAR_READONLY;
					break;
				case "task":
					$this->scopes[3] = Google_Service_Tasks::TASKS_READONLY;
					break;
				case "drive":
					$this->scopes[4] = Google_Service_Drive::DRIVE_METADATA_READONLY;
					break;
				}
		}
	}
	/**
	 * Returns an authorized API client.
	 * @return Google_Client the authorized client object
	 */
	public function getClient()
	{
            // Check if it is already connected
            if(!$this->clientConnection ==  FALSE) {
                return $this->clientConnection;
            }
		if(!$this->credential_path || !$this->client_secret_path || !$this->scopes){
			echo "Google API Credential is not properly set!";
			return false;
		}

		  $client = new Google_Client();
		  $client->setApplicationName($this->application_name);
		  $client->setScopes($this->scopes);
		  $client->setAuthConfigFile($this->client_secret_path);
		  $client->setAccessType('offline');

		  // Load previously authorized credentials from a file.
		  $credentialsPath = $this->expandHomeDirectory($this->credential_path);
		  if (file_exists($credentialsPath)) {
		    $accessToken = file_get_contents($credentialsPath);
		  } else {
		    // Request authorization from the user.
		    $authUrl = $client->createAuthUrl();
		    printf("Open the following link in your browser:\n%s\n", $authUrl);
		    print 'Enter verification code: ';
		    $authCode = trim(fgets(STDIN));

		    // Exchange authorization code for an access token.
		    $accessToken = $client->authenticate($authCode);

		    // Store the credentials to disk.
		    if(!file_exists(dirname($credentialsPath))) {
		      mkdir(dirname($credentialsPath), 0700, true);
		    }
		    file_put_contents($credentialsPath, $accessToken);
		    printf("Credentials saved to %s\n", $credentialsPath);
		  }
		  $client->setAccessToken($accessToken);

		  // Refresh the token if it's expired.
		  if ($client->isAccessTokenExpired()) {
		    $client->refreshToken($client->getRefreshToken());
		    file_put_contents($credentialsPath, $client->getAccessToken());
		  }
                  
                  $this->clientConnection = $client;
                  
		  return $client;
	}
	/**
	 * Expands the home directory alias '~' to the full path.
	 * @param string $path the path to expand.
	 * @return string the expanded path.
	 */
	function expandHomeDirectory($path) {
	  $homeDirectory = getenv('HOME');
	  if (empty($homeDirectory)) {
	    $homeDirectory = getenv("HOMEDRIVE") . getenv("HOMEPATH");
	  }
	  return str_replace('~', realpath($homeDirectory), $path);
	}
}
