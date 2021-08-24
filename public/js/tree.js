function zwin(id) {
    if (document.getElementById(id).innerHTML == "-") {
        document.getElementById(id).innerHTML = "+";
    }
    else {
        document.getElementById(id).innerHTML = "-";
    }
}
document.write("<div id='uldrzewo'>");
for (var i = 0; i < wynik.length; i++) {
    document.write("<div class='treeBranch' id='MainBranch"+wynik[i].id+"'>"+wynik[i].name+" ");
    if(wynik[i].level == 0)
    {
        document.getElementById("MainBranch"+wynik[i].id).classList.add("MainBranch");
    }
    if (i == wynik.length - 1) {
        document.write("</div>");
        for (var j = wynik[i].level; j > 0; j--) {
            document.write('</div>');
        }
    }
    else if (wynik[i].level < wynik[i + 1].level) {
        document.write("<button class='rozwinGuzik' id='" + wynik[i].id + "' aria-expanded='false' data-toggle='collapse' data-target='#zwin"+wynik[i].id+"' onclick=zwin(" + wynik[i].id + ");>-</buttton></div><div id='zwin" + wynik[i].id + "' class='zwijany filter-content collapse show'>");

    }
    else if (wynik[i].level > wynik[i + 1].level) {
        document.write("</div>");
        for (var j = wynik[i].level - wynik[i + 1].level; j > 0; j--) {
            document.write('</div>');
        }
    }
    else {
        document.write("</div>");
    }
}
document.write("</div>");