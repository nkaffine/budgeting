/**
 * Created by Nick on 8/11/17.
 */
function downloadUrl(url, callback) {
    var request = window.ActiveXObject ?
        new ActiveXObject('Microsoft.XMLHTTP') :
        new XMLHttpRequest;

    request.onreadystatechange = function () {
        if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
        }
    };

    request.open('GET', url, true);
    request.send(null);
}

function doNothing(){}

function newFromAccount(){
    var fromAccountType = document.getElementById('newFromAccountType').value;
    var fromAccountName = document.getElementById('newFromAccountName').value;
    var fromAccountBalance = document.getElementById('newFromAccountBalance').value;
    var fromAccountBalanceType = document.getElementById('newFromAccountBalanceType').value;
}

function newToAccount(){
    var toAccountType = document.getElementById('newToAccountType').value;
    var toAccountName = document.getElementById('newToAccountName').value;
    var toAccountBalance = document.getElementById('newToAccountBalance').value;
    var toAccountBalanceType = document.getElementById('newToAccountBalanceType').value;
}

$(document).ready(function() {
    $('#newFromBtn').click(function(){

    });
    $('#newToBtn').click(function(){

    });
});