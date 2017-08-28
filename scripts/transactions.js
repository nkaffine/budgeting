/**
 * Created by Nick on 8/11/17.
 */
/**
 * Created by Nick on 7/30/17.
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

function getTransactions(start, end, from, to, query, type, to_name, from_name){
    var html = "<table class='col-lg-12 table table-striped'>"+
        "<thead>"+
        "<th>Name</th>"+
        "<th>Amount</th>"+
        "<th>Description</th>"+
        "<th>Date</th>"+
        "<th>From Account</th>"+
        "<th>To Account</th>"+
        "<th></th>"+
        "<th></th>"+
        "</thead>"+
        "<tbody>";
    if(start != null || end != null || from != null || to != null || query != null || type != null) {
        var link = 'query/getTransactions.php?'
        if(start != null){
            link = link + "start=" + encodeURIComponent(start) + "&";
        }
        if(end != null){
            link = link + "end=" + encodeURIComponent(end) + "&";
        }
        if(from != null){
            link = link + "from=" + encodeURIComponent(from) + "&";
            link = link + "from_name=" + encodeURIComponent(from_name) + "&";
        }
        if(to != null){
            link = link + "to=" + encodeURIComponent(to) + "&";
            link = link + "to_name=" + encodeURIComponent(to_name) + "&";
        }
        if(query != null){
            link = link + "query=" + encodeURIComponent(query) + "&";
        }
        if(type != null){
            link = link + "type=" + encodeURIComponent(type) + "&";
        }
        link = link.substr(0, link.length - 1);
    } else {
        var link = 'query/getTransactions.php'
    }
    console.log(link);
    downloadUrl(link, function (data) {
        var xml = data.responseXML;
        var transactions = xml.documentElement.getElementsByTagName('transaction');
        if(transactions.length > 0){
            Array.prototype.forEach.call(transactions, function(transactionElem){
                var name = transactionElem.getAttribute('name');
                var type = transactionElem.getAttribute('type');
                var description = transactionElem.getAttribute('description');
                var date = userTime2(transactionElem.getAttribute('date'));
                var from_name = transactionElem.getAttribute('from_name');
                var to_name = transactionElem.getAttribute('to_name');
                var id = transactionElem.getAttribute('id');
                var amount = transactionElem.getAttribute('amount');
                var to_type = transactionElem.getAttribute('to_type');
                var from_type = transactionElem.getAttribute('from_type');
                html = html + "<tr>"+
                    "<td>"+name+"</td>";
                if(type == 0 || type == 6){
                    html = html + "<td style='color: red;'>-$" + amount + "</td>";
                } else if (type == 1 || type == 4) {
                    html = html + "<td style='color: green;'>+$" + amount + "</td>";
                } else {
                    if(to_type = from_type){
                        html = html + "<td style='color: grey;'>$" + amount + "</td>";
                    } else {
                        html = html + "<td style='color: red;'>-$" + amount + "</td>";
                    }
                }
                html = html + "<td>" + description + "</td>"+
                    "<td class='time'>"+date+"</td>"+
                    "<td>"+from_name+"</td>"+
                    "<td>"+to_name+"</td>"+
                    "<td>"+
                    "<form action='editTransaction.php' method='post'>"+
                    "<input type='hidden' name='transaction_id' value='"+id+"'>"+
                    "<input type='submit' value='Edit' class='btn btn-default'>"+
                    "</form>"+
                    "</td>"+
                    "<td>"+
                    "<form action='removeTransaction.php' method='post'>"+
                    "<input type='hidden' name='id' value='"+id+"'>"+
                    "<input type='submit' value='Remove' class='btn form-control btn-default'>"+
                    "</form>"+
                    "</td>"+
                    "</tr>";
            });
        } else {
            html = html + "<tr><td>There were no results</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
        }
        html = html + "</tbody></table>";
        document.getElementById('results').innerHTML = html;
    });
}

function sumTransactions(start, end, from, to, query, type, to_name, from_name, method){
    var html = "<table class='col-lg-12 table table-striped'>"+
        "<thead>"+
        "<th>Sum</th>"+
        "</thead>"+
        "<tbody>";
    var link = 'query/sumTransactions.php?'
    if(start != null){
        link = link + "start=" + encodeURIComponent(start) + "&";
    }
    if(end != null){
        link = link + "end=" + encodeURIComponent(end) + "&";
    }
    if(from != null){
        link = link + "from=" + encodeURIComponent(from) + "&";
        link = link + "from_name=" + encodeURIComponent(from_name) + "&";
    }
    if(to != null){
        link = link + "to=" + encodeURIComponent(to) + "&";
        link = link + "to_name=" + encodeURIComponent(to_name) + "&";
    }
    if(query != null){
        link = link + "query=" + encodeURIComponent(query) + "&";
    }
    if(type != null){
        link = link + "type=" + encodeURIComponent(type) + "&";
    }
    if(method != null){
        link = link + "method=" + encodeURIComponent(method) + "&";
    }
    link = link.substr(0, link.length - 1);
    downloadUrl(link, function (data) {
        var xml = data.responseXML;
        var transactions = xml.documentElement.getElementsByTagName('transaction');
        if(transactions.length > 0){
            Array.prototype.forEach.call(transactions, function(transactionElem){
                var amount = transactionElem.getAttribute('amount');
                html = html + "<tr>";
                if(method == "COUNT"){
                    html = html + "<td>"+amount+"</td>"
                } else {
                    if(amount < 0){
                        html = html + "<td style='color: red;'>-$" + amount*-1 + "</td>";
                    } else if(amount > 0) {
                        html = html + "<td style='color: green;'>+$" + amount + "</td>";
                    } else {
                        html = html + "<td>There were no results</td>";
                    }
                    html = html + "</tr>";
                }
            });
        } else {
            html = html + "<tr><td>There were no results</td></tr>";
        }
        html = html + "</tbody></table>";
        document.getElementById('results').innerHTML = html;
    });
}
