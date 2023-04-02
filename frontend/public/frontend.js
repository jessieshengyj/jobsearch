let myIndex = 0;
carousel();

// https://www.w3schools.com/howto/howto_js_slideshow.asp
function carousel() {
    let i;
    const x = document.getElementsByClassName("slideShow");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    myIndex++;
    if (myIndex > x.length) {myIndex = 1}
    x[myIndex-1].style.display = "block";
    setTimeout(carousel, 4000);
}


function showJoinTable() {
    let table = document.getElementById("jobPostsTable");
    table.style.visibility = "visible";
}

function showJobTable() {
    let table = document.getElementById("jobTable");
    table.style.visibility = "visible";
}

function showGroupByTable() {
    let table = document.getElementById("groupByTable");
    table.style.visibility = "visible";
}

function showHavingTable() {
    let table = document.getElementById("havingTable");
    table.style.visibility = "visible";
}

function showNestedTable() {
    let table = document.getElementById("nestedTable");
    table.style.visibility = "visible";
}

function showDivisionTable() {
    let table = document.getElementById("divisionTable");
    table.style.visibility = "visible";
}