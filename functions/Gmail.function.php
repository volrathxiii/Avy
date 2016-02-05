<?php

/**
 * Check emails
 */
require_once INCLUDES_DIR . "Google.module.php";

class Gmail extends UserFunctions {

    public $Name = "Gmail";
    public $primarytriggers = array(
        array('emails'),
        array('email'),
    );
    public $triggers = array(
        'check_mail' => array(
            array('check'),
            array('check', 'new'),
        ),
    );

    public function check_mail() {
        $responses = array(
            "Okay. Checking for new emails.",
            "Please wait, while i check for new unread emails",
        );
        if ($this->speak)
            TTS::Speak($responses);

        echo "Gmail: Checking emails!\n";
        $newemails = $this->count_unread_mails();
        if ($newemails > 0) {
            if ($this->speak)
                TTS::Speak("You have " . $newemails . " unread emails.");

            // Prompt, do you want me to read them for you?
            Prompt::Set("Do you want me to read them for you?", 'Gmail', 'prompt_read_email_subject');
            shell_exec("sudo /etc/init.d/SAM listenquitely");
        }else {
            $response = array(
                "You have no new emails.",
                "No new emails found in your inbox.",
                "Your inbox is empty."
            );
            if ($this->speak)
                TTS::Speak($response);
        }
    }

    public function count_unread_mails() {
        $GoogleAPI = GoogleAppsAPI::getInstance();
        $client = $GoogleAPI->getClient();

        $service = new Google_Service_Gmail($client);

        // Print the labels in the user's account.
        $user = 'me';
        $results = $service->users_labels->get($user, 'INBOX');

        $unreadcount = $results->messagesUnread;

        return $unreadcount;
    }

    public function prompt_read_email_subject($message) {
        if (Prompt::MatchAnswer(Prompt::$Yes, $message)) {
            echo "Prompt Response: Yes\n";
            $responses = array(
                "Okay. Downloading emails.",
                "Sure thing. Give me a second to fetch your emails",
                "Please wait while I load your emails",
                "Sure. Downloading your emails now.");
            if ($this->speak)
                TTS::Speak($responses);
            $this->read_new_email_subject();
            Prompt::Clear();
            shell_exec("sudo /etc/init.d/SAM stopquitely");
        }
        elseif (Prompt::MatchAnswer(Prompt::$No, $message)) {
            //$Prompt = Prompt::Get();
            echo "Prompt Response: No\n";
            $responses = array(
                "Okay",
                "Dont forget to read them later",
                "Ok just call me if you need anything else",
                "Great! i cant read properly anyway");
            if ($this->speak)
                TTS::Speak($responses);
            Prompt::Clear();
            shell_exec("sudo /etc/init.d/SAM listenquitely");
        }
        else {
            echo "Not the answer im looking for!";
        }
    }

    public function read_new_email_subject() {
        $GoogleAPI = GoogleAppsAPI::getInstance();
        $client = $GoogleAPI->getClient();

        $service = new Google_Service_Gmail($client);

        $pageToken = NULL;

        $userId = 'me';
        $messages = array();
        $opt_param = array('labelIds' => 'UNREAD');
        do {
            try {
                if ($pageToken) {
                    $opt_param['pageToken'] = $pageToken;
                }

                $messagesResponse = $service->users_messages->listUsersMessages($userId, $opt_param);
                if ($messagesResponse->getMessages()) {
                    $messages = array_merge($messages, $messagesResponse->getMessages());
                    $pageToken = $messagesResponse->getNextPageToken();
                }
            } catch (Exception $e) {
                print 'An error occurred: ' . $e->getMessage();
            }
        } while ($pageToken);

        $speak_emails = array();
        $mail_key = 1;
        foreach ($messages as $message) {
            $message = $service->users_messages->get($userId, $message->getId());
            $headers = $message->payload['modelData']['headers'];

            foreach ($headers as $key => $header) {
                if ($header['name'] == 'Subject') {
                    $subject = $header['value'];
                }

                if ($header['name'] == 'From') {
                    $from = $header['value'];
                }

                if ($header['name'] == 'Date') {
                    $from = $header['value'];
                }
            }
            $speak_emails[$mail_key] = "mail " . $mail_key . ": " . $subject . " Sent by " . $from . " ";
            $mail_key += 1;
        }

        $responses = array(
            "Okay, here we go. ",
            "Here are your new mails. ",
        );

        $response = $responses[array_rand($responses, 1)] . implode(": ", $speak_emails);


        if ($this->speak)
            TTS::Speak($response);
    }

    public function fallback($parameters) {
        $response = "Cannot find any songs matching " . implode(" ", $parameters) . ".";
    }

}
