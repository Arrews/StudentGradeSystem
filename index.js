var imageActive = 1;
function left(){
    imageActive = imageActive-1
    if (imageActive <= 0){
        imageActive=6;
    }
    document.getElementById("image").src = "images/"+imageActive+".jpg";
}
function right(){
    imageActive = imageActive+1
    if (imageActive >= 6){
        imageActive=1;
    }
    document.getElementById("image").src = "images/"+imageActive+".jpg";
}