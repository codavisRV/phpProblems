<?php 
/*
Notes from Courtney: 
* Both calls for the Spades game and the Blackjack game are currently commented out. This is set up to only print to console, rather than implementing HTML tags. 
* There's no output for the Normal and Medium challenges because they're both used in the Hard and Insane problems anyway. 
* Uncomment line 185 to see the Hard Problem output.

* Uncomment line 412 to see the Insane Problem output.

* I forgot to add the functionality that Aces can equal 1 or 11, but I'm just going to move on.
* I didn't get to the extra credit piece. 
*/

/*NORMAL:
Use two foreach loops to create a master array of all 52 cards in the array $deck, each represented as a key value pair in the format ‘face of suit’, use the print_r function to print out the contents of the $deck to make sure it is correct.
EX: “King of Spades” should be one of the elements of the array and it should have a value of 13.  
*/


$suits = array ("clubs", "diamonds", "hearts", "spades");
$faces = array (
    "Ace" => 1, 
    "2" => 2,
    "3" => 3,
    "4" => 4, 
    "5" => 5, 
    "6" => 6, 
    "7" => 7, 
    "8" => 8, 
    "9" => 9, 
    "10" => 10, 
    "Jack" => 11, 
    "Queen" => 12, 
    "King" => 13);
$deck = array();

//loop through array 1 and for each loop through array 2
foreach($faces as $face => $value) {
    foreach ($suits as $suit) {        
        $deck["$face of " . ucwords($suit)] = $value;
    };
};

/*
MEDIUM:
Let’s bring in the deck code from the past example (your normal challenge).
Create a function that will create a deck of cards, randomize it and then return the deck.
We will now create a function to deal these cards to each user. Modify this function so that it returns the number of cards specified for the user. Also, it must modify the deck so that those cards are no longer available to be distributed.
*/

function createDeck() {
//create the deck
    $suits = array ("clubs", "diamonds", "hearts", "spades");
    $faces = array (
        "Ace" => 1, 
        "2" => 2,
        "3" => 3,
        "4" => 4, 
        "5" => 5, 
        "6" => 6, 
        "7" => 7, 
        "8" => 8, 
        "9" => 9, 
        "10" => 10, 
        "Jack" => 11, 
        "Queen" => 12, 
        "King" => 13);
    $deck = array();
    
    //loop through array 1 and for each loop through array 2
    foreach($faces as $face => $value) {
        foreach ($suits as $suit) {        
            $deck["$face of " . ucwords($suit)] = $value;
        };
    };
    //randomize the deck
    $shuffledKeys = array_keys($deck);
    shuffle($shuffledKeys);
    foreach($shuffledKeys as $key) {
        $shuffledDeck[$key] = $deck[$key];
    }
    $deck = $shuffledDeck;
    return $deck;
}

//We will now create a function to deal these cards to each user. Modify this function so that it returns the number of cards specified for the user. Also, it must modify the deck so that those cards are no longer available to be distributed.

function dealCards(&$deck, $num_cards, &$players) { //takes in # of cards user should get and a shuffled deck and players
    foreach ($players as $name=>&$cards) {
            $cards[0] = [];
            //take the deck and slice $num_cards from the beginning
            $cards[0] = array_slice($deck, 0, $num_cards, $preserve_keys = true);
            //remove cards from deck
            array_splice($deck, 0, $num_cards);
    }
    
}
  
/*HARD:
Bring in your createDeck and dealCards function from the previous challenges. For the specified number of players below, assign each player an even set of cards.
We will do this by counting out how many players there are, counting how many cards are in the deck and then dividing them so we know how many cards each player should get.

Use a for loop to add the "dealt hands" to the $players array -- moved to function later*/

/*Let’s create a simple game. Each player will play a card and whoever has the highest value wins. If there are 2 cards played that have the same value, everyone loses and that round is a draw. Store the results of each game and also who won that round as the value.
If the round is a draw, store the value as DRAW. Use a loop to play each game until all opponents are out of cards. Print out the array of all the rounds. If there was a draw, the round should say DRAW.
If a player has won, it should displayer “Player X” where X is the index of the player.
*/

class Game 
{
    private $deck;
    public $players;
    private $num_players;
    private $num_cards_in_deck;
    private $num_cards_to_give_each_player;
    private $num_rounds;
    
    public function __construct($players)
    {
        $this->deck = createDeck();
        $this->players = $players;
        $this->num_players = count($players);
        $this->num_cards_in_deck = count($this->deck);
        $this->num_cards_to_give_each_player = $this->num_cards_in_deck / $this->num_players;
        $this->num_rounds = $this->num_cards_to_give_each_player;
        dealCards($this->deck, $this->num_cards_to_give_each_player, $this->players);
    }

    public function play() 
    {   
        for ($i = 0; $i < $this->num_rounds; $i++) {
            $this->playRound($i);
        }
        $finalTotal=[];
        foreach($this->players as $name => $stats) {
            $finalTotal[$name] = $stats[1];
        }
        arsort($finalTotal);
        $totalKeys = array_keys($finalTotal);
        if ($finalTotal[$totalKeys[0]] !== $finalTotal[$totalKeys[1]]) {
            print_r("\n!!!!!!!!!!\n" . $totalKeys[0] . " is the winner with a score of " . $finalTotal[$totalKeys[0]] . "!\n");
        } else if ($finalTotal[$totalKeys[1]] !== $finalTotal[$totalKeys[2]]) {
            print_r("!!!!!!!!!!\n" . $totalKeys[0] . " and " . $totalKeys[1] . " tied, with a score of " . $finalTotal[$totalKeys[0]] . "!\n");
        } else if ($finalTotal[$totalKeys[2]] !== $finalTotal[$totalKeys[3]]) {
            print_r("\n!!!!!!!!!!\n" . $totalKeys[0] . ",  " . $totalKeys[1] . ", and " . $totalKeys[2] . " tied, with a score of " . $finalTotal[$totalKeys[0]] . "!\n");
        }
    }

    public function playRound($i) 
    {
        $roundInfo = "";
        foreach($this->players as $name => &$card) {
            //take one card off and store in round array
            $round[$name] = array_pop($card[0]);
            $roundInfo .= "\n$name played a card valued at " . $round[$name];
        }
        //sort $round
        arsort($round);
        //set up output
        $output = "";
        $output .= "\n// Round " . ($i+1) . "//\n";
        $output .= $roundInfo;
        if (($round["Andy"] !== $round["Baylee"]) && ($round["Andy"] !== $round["Courtney"]) && ($round["Andy"] !== $round["Shane"]) && ($round["Baylee"] !== $round["Courtney"]) && ($round["Baylee"] !== $round["Shane"]) && ($round["Courtney"] !== $round["Shane"])) {
            //modify winner's score
            $winner = array_keys($round)[0];
            $this->players[$winner][1] += 1;
            $output .= "\n\n$winner wins round. \n";
            $output .= "\n---------------\n";
            echo $output;
        } else {
            $output .= "\nThere was a tie. No winner this round.\n";
            echo $output;
        }
    }
}

$players = [
    "Andy" => [[], 0],
    "Baylee" => [[], 0],
    "Courtney" => [[], 0],
    "Shane" => [[], 0],
];
$game1 = new Game($players);
// $game1->play(); //<<<< Uncomment to see game play





/* INSANE CHALLENGE:
Create a game of Blackjack.
Rules:
1. At any given time, there will only be two players. The dealer and player one.
2. 4 cards will be dealt out each round, 2 to the dealer and 2 to the player.
3. If the amount in the player’s hand is less than or equal to the amount in the dealer’s hand, you must draw a card.
4. If the player draws a card and the amount they have goes over 21, the dealer has won that round.
5. If the player ever reaches an amount greater than the dealer’s, they should stay then it will be the dealer’s turn.
6. The dealer must draw until he reaches an amount greater than the player’s or until he loses.
7. Subtract $100 from the player’s bank every time they lose
8. Add $200 to the player’s bank every time they win
9. Player starts with $1000 in the bank account
10. Aces can either be an 11 or 1
The game will continue as long as there are enough cards in the deck OR the player runs out of money.
Output:
1. How many games were played?
2. Who won the game?
3. Which round did the player’s bank reach half way?
4. How many times did the player get blackjack?
*/

function createBlackjackDeck() {
    //create the deck
    $suits = array ("clubs", "diamonds", "hearts", "spades");
    $faces = array (
        "Ace" => 11, 
        "2" => 2,
        "3" => 3,
        "4" => 4, 
        "5" => 5, 
        "6" => 6, 
        "7" => 7, 
        "8" => 8, 
        "9" => 9, 
        "10" => 10, 
        "Jack" => 10, 
        "Queen" => 10, 
        "King" => 10);
    $deck = array();
    
    //loop through array 1 and for each loop through array 2
    foreach($faces as $face => $value) {
        foreach ($suits as $suit) {        
            $deck["$face of " . ucwords($suit)] = $value;
        };
    };
    //randomize the deck
    $shuffledKeys = array_keys($deck);
    shuffle($shuffledKeys);
    foreach($shuffledKeys as $key) {
        $shuffledDeck[$key] = $deck[$key];
    }
    $deck = $shuffledDeck;
    return $deck;
}

function dealBlackjackCards(&$deck, $num_cards, &$players) { //takes in a deck, # of cards users should get, and players array
    foreach ($players as $player) {
        $player->hand = array_slice($deck, 0, $num_cards, $preserve_keys = true);
        array_splice($deck, 0, $num_cards);
    }
}

class Player 
{
    public function __construct($name, $cash) 
    {
        $this->name = $name;
        $this->cash = $cash;
        $this->hand = [];
        $this->isTurn = false;
        $this->currentScore = 0;
        $this->num_blackjack = 0;
        $this->num_wins = 0;
    }
    
} 

class Blackjack 
{
    public function __construct() 
    {
        $this->player = new Player("Player", 1000);
        $this->dealer = new Player("Dealer", null);
        $this->players = [$this->player, $this->dealer];
        $this->deck = createBlackjackDeck();
        $this->num_cards_to_give_each_player = 2;
        $this->roundCounter = 0;
        $this->halfwayPoint = 0;
        $this->halfwayCash = $this->player->cash / 2;
    }

    public function drawCard(&$deck, &$hand) //take in deck and player's hand
    {
        $cardFace = array_keys($deck)[0];
        $cardValue = array_shift($deck);
        $hand[$cardFace] = $cardValue;
    }

    public function playGame() 
    {
        $activePlayer = null;
        $scoreToBeat = 0;
        dealBlackjackCards($this->deck, $this->num_cards_to_give_each_player, $this->players); 
        //first, check to see if either hand already has 21, and that they aren't tying.
        if (array_sum($this->player->hand) === 21 && $this->player->hand !== $this->dealer->hand) {
            echo "Player wins!\n";
            $this->player->cash += 200;
            $this->player->num_blackjack ++;
            $this->player->num_wins ++;
            $this->roundCounter ++;
        }     
        if (array_sum($this->dealer->hand) === 21 && $this->player->hand !== $this->dealer->hand) {
            echo "Dealer wins!\n";
            $this->player->cash -= 100;
            $this->dealer->num_blackjack ++;
            $this->dealer->num_wins ++;
            $this->roundCounter ++;
        }
        if (array_sum($this->dealer->hand) === 21 && array_sum($this->player->hand) === 21) {
            echo "Both players started with 21. This round is a draw.\n";
            $this->roundCounter ++;
        }
            
        if ((!$this->player->cash <= 0) && count($this->deck) > 0) {
            if ($this->player->cash === $this->halfwayCash) {
                $this->halfwayPoint = $this->roundCounter;
                $this->halfwayCash = -1;
            }
            $this->roundCounter ++;
            // check to see total of both hands, if either is 21 then winner, if either is over 21 then loser 
            $this->player->currentScore = array_sum($this->player->hand);
            $this->dealer->currentScore = array_sum($this->dealer->hand);
            // check to see which is higher, set isTurn to true.
            if ($this->player->currentScore < $this->dealer->currentScore) {
                $this->player->isTurn = true;
                $activePlayer = $this->player;
                $scoreToBeat = $this->dealer->currentScore;
            } else {
                $this->dealer->isTurn = true; 
                $activePlayer = $this->dealer;  
                $scoreToBeat = $this->player->currentScore;
            } 
            // while isTurn === true, draw card until sum of cur player is > sum of opponent, then isTurn = false
            while ($activePlayer->isTurn) {
                //draw a card
                if (count($this->deck) > 0) { //make sure the deck isn't at 0 before drawing.
                    $this->drawCard($this->deck, $activePlayer->hand);
                } else {
                    break;
                }
                $activePlayer->currentScore = array_sum($activePlayer->hand);
                //if current player hits 21, winner --> break loop
                if ($activePlayer->currentScore === 21) {
                    echo $activePlayer->name . " wins!\n";
                    if ($activePlayer === $this->player) {
                        $this->player->cash += 200;
                        $this->player->num_blackjack ++;
                        $this->player->num_wins ++;
                    } else {
                        $this->player->cash -= 100;
                        $this->dealer->num_wins ++;
                    }
                    break;
                // check if current player busted
                } else if ($activePlayer->currentScore > 21){
                    echo $activePlayer->name . " busted!\n";
                    if ($activePlayer === $this->player) {
                        $this->player->cash -= 100;
                        $this->dealer->num_wins ++;
                    } else {
                        $this->player->cash += 200;
                        $this->player->num_wins ++;
                    }
                    break;
                // If not 21 or busted, check if the current player's score is higher than the opponent
                } else if ($activePlayer->currentScore > $scoreToBeat) {
                    $activePlayer->isTurn = false;
                    if ($activePlayer === $this->player) {
                        $activePlayer = $this->dealer;
                        $scoreToBeat = $this->player->currentScore;
                        $activePlayer->isTurn = true;                        
                    } else {
                        $activePlayer = $this->player;
                        $scoreToBeat = $this->dealer->currentScore;
                        $activePlayer->isTurn = true;
                    }
                }
            } 
            //discard the hads and start a new round
            $this->player->hand = [];
            $this->dealer->hand = [];
            $this->playGame(); // recursive call
        //if there are no cards left or the player is out of money, display game results: 
        } else {
            //make sure they didn't tie
            if ($this->player->num_wins > $this->dealer->num_wins) {
                $winner = $this->player->name;
                echo "Game is over. $winner is the winner! \n";
            } else if ($this->player->num_wins !== $this->dealer->num_wins) {
                $winner = $this->dealer->name;
                echo "Game is over. $winner is the winner! \n";
            } else {
                echo "Game is over. The player and dealer tied! \n";                
            }
            echo "Player has $" . $this->player->cash . " cash remaining.\n";
            echo "There are " . count($this->deck) . " cards remaining.\n";
            echo "They played " . $this->roundCounter . " rounds.\n";
            // see if the player ever went below half of his money
            if ($this->halfwayPoint === 0) {
                echo "The player never went below half of his cash.\n";
            } else {
                echo "The player had half of his cash remaining at round " . $this->halfwayPoint . ".\n";
            }
            echo "The player hit blackjack " . $this->player->num_blackjack . " times during the game and had " . $this->player->num_wins . " wins.\n";
            echo "The dealer hit blackjack " . $this->dealer->num_blackjack . " times during the game and had " . $this->dealer->num_wins . " wins.\n";
        }
    } 
}

$game2 = new Blackjack();
//$game2->playGame(); //<<Uncomment to run the game




/*
EXTRA CREDIT:
Create a function called countCards and enable it for the player, NOT the dealer. This function must analyze the deck and determine if the player should draw again even if the amount in his hand is greater than the dealer.
EX: If the dealer has a sum of 9 on the table, you might have two 6’s (12 total). Player should draw again because it is more likely the dealer can beat your 12. */
