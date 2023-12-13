<?php
require_once('database.php');
$hamle=$_POST['hamle'];

$sadeceSayilar = preg_replace("/[^0-9]/", "", $hamle);

session_start();

if(isset($_SESSION['kullanici_eposta'])) 
{
    $posta = $_SESSION['kullanici_eposta'];
}

$kayit="SELECT * FROM kayitli WHERE eposta='$posta'";
$result = $conn->query($kayit);
$kullaniciId="0";
if ($result) {
    // Sorgudan dönen sonuçların kontrolü
    if ($result->num_rows > 0) {
        // Eğer bir sonuç varsa, ID'yi al
        $row = $result->fetch_assoc();
        $kullaniciId = $row['kullanici_id'];
    }
}

$sql="SELECT * FROM leaderboard WHERE kullanici_id = $kullaniciId";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $sql= "DELETE FROM leaderboard WHERE kullanici_id = $kullaniciId";
    $conn->query($sql);
    $sql = "INSERT INTO leaderboard (kullanici_id, hamle) VALUES ('$kullaniciId', '$sadeceSayilar')";
    $conn->query($sql);

} else {
    $sql = "INSERT INTO leaderboard (kullanici_id, hamle) VALUES ('$kullaniciId', '$sadeceSayilar')";
    $conn->query($sql);

}

$conn->close();

?>
<!DOCTYPE html>
<html lang="tr">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="ISO-8859-1"> 
    <title>MatchIt</title>
    <!-- Google Fonts -->
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap"
      rel="stylesheet"
    />
    <!-- Stylesheet -->
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div class="wrapper">
      <div class="stats-container">
        <div id="moves-count"></div>
        <div id="time"></div>
      </div>
      <div class="game-container"></div>
      <button id="stop" class="hide">Oyunu Bitir</button>
    </div>
    <div class="controls-container">
      <form action="leaderboard.php" method="post">
      <p id="result"></p>
      <button >Kaydet</button>
      
    </form>
    <button id="start" >Oyunu Başlat</button>
    </div>
    
    <!-- Script -->
    <script>
        const moves = document.getElementById("moves-count");
const timeValue = document.getElementById("time");
const startButton = document.getElementById("start");
const stopButton = document.getElementById("stop");
const gameContainer = document.querySelector(".game-container");
const result = document.getElementById("result");
const controls = document.querySelector(".controls-container");
let cards;
let interval;
let firstCard = false;
let secondCard = false;

//Rastegele olarak gelicek kartlar
const items = [
  { name: "kopek", image: "assets/kartlar/dog.png" },
  { name: "kedi", image: "assets/kartlar/cat.png" },
  { name: "civciv", image: "assets/kartlar/chick.png" },
  { name: "tilki", image: "assets/kartlar/fox.png" },
  { name: "kaplumbaga", image: "assets/kartlar/turtle.png" },
  { name: "penguen", image: "assets/kartlar/penguin.png" },
  { name: "bukalemun", image: "assets/kartlar/chameleon.png" },
  { name: "fil", image: "assets/kartlar/elephant.png" },
  { name: "sincap", image: "assets/kartlar/squirrel.png" },
  { name: "tavsan", image: "assets/kartlar/rabbit.png" },
  { name: "panda", image: "assets/kartlar/panda.png" },
  { name: "zurafa", image: "assets/kartlar/giraffe.png" },
];

//Initial Time
let seconds = 0,
  minutes = 0;
//Initial moves and win count
let movesCount = 0,
  winCount = 0;

//For timer
const timeGenerator = () => {
  seconds += 1;
  //minutes logic
  if (seconds >= 60) {
    minutes += 1;
    seconds = 0;
  }
  //format time before displaying
  let secondsValue = seconds < 10 ? `0${seconds}` : seconds;
  let minutesValue = minutes < 10 ? `0${minutes}` : minutes;
  timeValue.innerHTML = `<span>Süre:</span>${minutesValue}:${secondsValue}`;
};

//For calculating moves
const movesCounter = () => {
  movesCount += 1;
  moves.innerHTML = `<span>Hamleler:</span>${movesCount}`;
};

//Pick random objects from the items array
const generateRandom = (size = 4) => {
  //temporary array
  let tempArray = [...items];
  //initializes cardValues array
  let cardValues = [];
  //size should be double (4*4 matrix)/2 since pairs of objects would exist
  size = (size * size) / 2;
  //Random object selection
  for (let i = 0; i < size; i++) {
    const randomIndex = Math.floor(Math.random() * tempArray.length);
    cardValues.push(tempArray[randomIndex]);
    //once selected remove the object from temp array
    tempArray.splice(randomIndex, 1);
  }
  return cardValues;
};

const matrixGenerator = (cardValues, size = 4) => {
  gameContainer.innerHTML = "";
  cardValues = [...cardValues, ...cardValues];
  //simple shuffle
  cardValues.sort(() => Math.random() - 0.5);
  for (let i = 0; i < size * size; i++) {
    /*
        Create Cards
        before => front side (contains question mark)
        after => back side (contains actual image);
        data-card-values is a custom attribute which stores the names of the cards to match later
      */
    gameContainer.innerHTML += `
     <div class="card-container" data-card-value="${cardValues[i].name}">
        <div class="card-before">?</div>
        <div class="card-after">
        <img src="${cardValues[i].image}" class="image"/></div>
     </div>
     `;
  }
  //Grid
  gameContainer.style.gridTemplateColumns = `repeat(${size},auto)`;

  //Cards
  cards = document.querySelectorAll(".card-container");
  cards.forEach((card) => {
    card.addEventListener("click", () => {
      //If selected card is not matched yet then only run (i.e already matched card when clicked would be ignored)
      if (!card.classList.contains("matched")) {
        //flip the cliked card
        card.classList.add("flipped");
        //if it is the firstcard (!firstCard since firstCard is initially false)
        if (!firstCard) {
          //so current card will become firstCard
          firstCard = card;
          //current cards value becomes firstCardValue
          firstCardValue = card.getAttribute("data-card-value");
        } else {
          //increment moves since user selected second card
          movesCounter();
          //secondCard and value
          secondCard = card;
          let secondCardValue = card.getAttribute("data-card-value");
          if (firstCardValue == secondCardValue) {
            //if both cards match add matched class so these cards would beignored next time
            firstCard.classList.add("matched");
            secondCard.classList.add("matched");
            //set firstCard to false since next card would be first now
            firstCard = false;
            //winCount increment as user found a correct match
            winCount += 1;
            //check if winCount ==half of cardValues
            if (winCount == Math.floor(cardValues.length / 2)) {
              result.innerHTML = `<h2>Kazandın</h2>
            <h4 id="hamle" name="hamle">Hamle Sayısı: ${movesCount}</h4>`;
              stopGame();
            }
          } else {
            //if the cards dont match
            //flip the cards back to normal
            let [tempFirst, tempSecond] = [firstCard, secondCard];
            firstCard = false;
            secondCard = false;
            let delay = setTimeout(() => {
              tempFirst.classList.remove("flipped");
              tempSecond.classList.remove("flipped");
            }, 900);
          }
        }
      }
    });
  });
};

//Start game
startButton.addEventListener("click", () => {
  movesCount = 0;
  seconds = 0;
  minutes = 0;
  //controls amd buttons visibility
  controls.classList.add("hide");
  stopButton.classList.remove("hide");
  startButton.classList.add("hide");
  //Start timer
  interval = setInterval(timeGenerator, 1000);
  //initial moves
  moves.innerHTML = `<span>Hamle Sayısı: </span> ${movesCount}`;
  initializer();
});

//Stop game
stopButton.addEventListener(
  "click",
  (stopGame = () => {
    controls.classList.remove("hide");
    stopButton.classList.add("hide");
    startButton.classList.remove("hide");
    clearInterval(interval);
  })
);

//Initialize values and func calls
const initializer = () => {
  result.innerText = "";
  winCount = 0;
  let cardValues = generateRandom();
  console.log(cardValues);
  matrixGenerator(cardValues);
};
    </script>
  </body>
</html>