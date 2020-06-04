function redirect() {
    var nameOfList = prompt("What is the name of list?");
        window.location.replace("../Controller/Podcast.php/newList/" + nameOfList);

}

function redirectActivateList() {
    let optionToCreateList = window.document.getElementById("ActivateList");

    optionToCreateList.addEventListener("click", function () {
        var nameOfList = prompt("What is the name of list?");
        window.location.replace("../Controller/Podcast.php/ActivateList/" + nameOfList);
    });

}


function redirectChangeNameOfList(av) {
        let nameOfList = prompt("What is the new  name of list?");
        if(nameOfList !== "Historic") {
            window.location.replace("../Controller/Podcast.php/changeName/" + av+"/"+nameOfList);
        }else{
            window.location.replace("../Controller/Podcast.php/getListPodcast");
        }

}