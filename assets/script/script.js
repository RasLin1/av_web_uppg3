//Declaring our globals that are going to be used throughout the program
const playingField = document.getElementById("playing-field");
const imageField = document.getElementById("image-field");
let moves = 0;
let matches = 0;
let time = 0;
let doneStatus = 1;
let timer;
const successAudio = document.getElementById("successAudio");
const failAudio = document.getElementById("failureAudio");
const gamePieces = ["row-1-column-1.jpg","row-1-column-2.jpg","row-1-column-3.jpg","row-1-column-4.jpg","row-1-column-5.jpg","row-2-column-1.jpg","row-2-column-2.jpg","row-2-column-3.jpg","row-2-column-4.jpg","row-2-column-5.jpg","row-3-column-1.jpg","row-3-column-2.jpg","row-3-column-3.jpg","row-3-column-4.jpg","row-3-column-5.jpg","row-4-column-1.jpg","row-4-column-2.jpg","row-4-column-3.jpg","row-4-column-4.jpg","row-4-column-5.jpg","row-5-column-1.jpg","row-5-column-2.jpg","row-5-column-3.jpg","row-5-column-4.jpg","row-5-column-5.jpg"];


//For loop that places the game pieces and gives them attributes
for(x=0; x<25; x++){
	playingField.innerHTML += `<div class='square' id='${x}' ondrop="drop(event)" ondragover='allowDrop(event)'></div>`;
	imageField.innerHTML += `<img class='puzzle-piece' id='piece${x}' src='assets/images/${gamePieces[x]}' data-tilematch='${x}' draggable="true" ondragstart="drag(event)">`;

}

//image shuffler for the puzzle
var parentDiv = document.querySelector("#image-field");
for (var i = parentDiv.children.length; i >= 0; i--) {
    parentDiv.appendChild(parentDiv.children[Math.random() * i | 0]);
}



function allowDrop(ev) {
	ev.preventDefault();
  }
  
  function drag(ev) {
	if(timer === undefined){
		timer = setInterval(timeCounter, 1000);
	}
	//Save id or the piece you are dragging in dataTransfer
	ev.dataTransfer.setData("text", ev.target.id);
	
  }
  
  function drop(ev) {
	ev.preventDefault();
	//Move counter is updated even if you try to place tiles on each other
	addMoves();
	//Retrive id of dragged piecce
	var pieceId = ev.dataTransfer.getData("text");
	//Target dragged img element
	draggedPiece = document.getElementById(pieceId);
	//makes sure that images arent stacked
	if(ev.target.tagName === "DIV"){
	//Move img elemnt inside the target of the drop event
	ev.target.appendChild(draggedPiece);
	//check if the img is dropped in the right tile
	if(draggedPiece.dataset.tilematch === ev.target.id){
		matches++;
		//check if pieces are in place, game won
		if(matches === 25){
			clearInterval(timer);
			if(moves < 25 || time < 10){
				alert("Cheater");
				return false;
			}
			alert ("Congrats, you've solved the puzzle succesfully!");
			//save highscore
			sessionStorage.setItem("moves", moves);
			sessionStorage.setItem("time", time);
			document.getElementById("shs-moves-counter").innerHTML = moves;
			document.getElementById("shs-time-counter").innerHTML = time;
			document.hiddenForm.moves.value = moves;
			document.hiddenForm.time.value = time;
			document.getElementById("hiddenForm").submit();
		}
	}
	
	
  }
  //gets angry if you try to stack images
  else{
	console.log("illegal move");
  }
  console.log(draggedPiece.dataset.tilematch);
  console.log(ev.target.id);
}

function addMoves(){
	moves++;
	document.getElementById("moves-counter").innerHTML = moves;
}

function timeCounter(){
	time++;
	document.getElementById("time-counter").innerHTML = time;
}

// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}