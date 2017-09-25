function rowOver_color(which, what,kolor) {
//alert(which);
var changed = document.getElementById(which);
//alert(changed);
if (which != clicked) {
if (what == 1)
changed.style.backgroundColor = '#FFFFFF';
else{
if(which%2)
changed.style.backgroundColor = kolor;
else
changed.style.backgroundColor = kolor';
}
}

}


function resetRow_color(which,kolor) {
var changed = document.getElementById(which);
if(which%2)
changed.style.backgroundColor = kolor;
else
changed.style.backgroundColor = kolor';

}


function changeSelect_color(which) {
var changed = document.getElementById(which);
changed.style.backgroundColor = '#FFFFFF';
changed.onMouseOver = '';
changed.onMouseOut = '';

}


function selectRow_color(which) { //,rowIndex
if (clicked != '') {
//alert('1');
resetRow_color(clicked);
clicked = which;
changeSelect_color(which);
} else if (clicked == '') {
//alert('2');
clicked = which;
changeSelect_color(which);
}
//currentRowId = rowIndex;

}


function deSelectRow(which) {
resetRow_color(which);
clicked = '';

}
