<?php

use BotMan\BotMan\Messages\Conversations\Conversation;

class OnboardingConversation extends Conversation
{

    protected $firstname;

    public function askFirstname()
    {
        $this->ask('Hola, dime cual es tu nombre por favor', function($answer) {
            $firstName = $answer->getText();
            $this->say('Un gusto conocerte '.$firstName);
        });
    }


    public function run()
    {
        $this->askFirstname();
    }
}

/*
<?php

use BotMan\BotMan\Messages\Conversations\Conversation;

class OnboardingConversation extends Conversation
{

    protected $firstname;

    public function askFirstname()
    {
        $this->ask('Hi, what is your name?', function($answer) {
            $firstName = $answer->getText();
            $this->say('Nice to meet you '.$firstName);
        });
    }


    public function run()
    {
        $this->askFirstname();
    }
}
*/