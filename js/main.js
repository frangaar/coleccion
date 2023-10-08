

document.addEventListener("DOMContentLoaded", (event) => {

let cardImageHeight = document.querySelectorAll('.card img')[0].offsetHeight;
function setHeight(){
    let cardImageHeight = document.querySelectorAll('.card img')[0].offsetHeight;
    let scenes = document.querySelectorAll('.scene > .card');
    let extraHeight = 3;
    for (let i = 0; i < scenes.length; i++) {
        let cardScene = scenes[i];
        cardScene.style.height = cardImageHeight - extraHeight;
    }
}


let cards = document.querySelectorAll('.scene > .card');
let buttonModif = document.getElementsByName('modificar');
let buttonBorrar = document.getElementsByName('borrar');
let stopToggle = false;

    for(let i = 0; i < cards.length; i++){
        cards[i].addEventListener( 'click', function() {  
            
            buttonModif[i].addEventListener('click',function(){
                stopToggle = true;
            });
    
            buttonBorrar[i].addEventListener('click',function(){
                stopToggle = true;
            });

            if(!stopToggle){
                cards[i].classList.toggle('is-flipped');
            }            
        });

        
    }



setHeight();

window.addEventListener('resize', setHeight);
});

