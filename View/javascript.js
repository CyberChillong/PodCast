function redirect() {
    let optionToCreateList = window.document.getElementById("createList");

        optionToCreateList.addEventListener("click", function () {
        var nameOfList = prompt("What is the name of list?");
        window.location.replace("../Controller/Podcast.php/newList/" + nameOfList);
    });

}