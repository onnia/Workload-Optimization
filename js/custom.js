$(document).ready(function() {

    var input = $( "form input:checkbox" );
    var update_checkbox = function () {
        var row = $(this).attr("class");
        $('.field.' + row).prop('disabled', 'disabled');
        $('.field.' + row).toggleClass('disabled');
    };

    $(update_checkbox);
    input.change(update_checkbox);
} );


function randomFrom(array){
    return array[Math.floor(Math.random() * array.length)];
}
var courses = ['Kalastus', 'Vakoilujärjestelmät', 'Selviytymiskurssi', 'Pikajuoksu', 'Tulostutekniikat', 'Mekaniikka', 'Kestäväkehitys ja IT',];
var op = ['1', '3', '4', '5', '6', '9', '12',];
var time = ['8', '20', '40', '60', '80', '10', '25',];

function addrow() {
    var rowCount = $('#mytable tr').length;
    var table = document.getElementById("mytable");
    var row = table.insertRow(-1);
    var randcourse = randomFrom(courses);
    var randop = randomFrom(op);
    var randtime = randomFrom(time);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
    var cell4 = row.insertCell(3);
    cell1.innerHTML = '<input class="row-'+ rowCount +'" type="checkbox" checked="checked" name="enabled[]" id="">';
    cell2.innerHTML = '<input class="field row-'+ rowCount +'" name="name[]" type="text" value="' + randcourse +'">';
    cell3.innerHTML = '<input class="field row-'+ rowCount +'" name="op[]" type="number" value="' + randop + '">';
    cell4.innerHTML = '<input class="field row-'+ rowCount +'" name="time[]" type="number" value="' + randtime + '">';
}