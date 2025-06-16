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

document.querySelector('.logo').addEventListener('mousedown', (event)=>{
    window.location = '/home.php';
})