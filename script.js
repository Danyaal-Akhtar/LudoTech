var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
  showDivs(slideIndex += n);
}

function showDivs(n) {
  var i;
  var x = document.getElementsByClassName("card");
  if (n > x.length) {slideIndex = 1}
  if (n < 1) {slideIndex = x.length} ;
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";
  }
  x[slideIndex-1].style.display = "block";
}

let active=false;
document.querySelector('#userimg').addEventListener('click', (event)=>{
    if(active==true){
        document.querySelector('.dashboard').style.display = 'none';
        active=false;
    }   
    else{
        document.querySelector('.dashboard').style.display = 'flex';
        active=true;        
    }
});