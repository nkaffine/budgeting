/**
 * Created by Nick on 8/10/17.
 */
function postFbInfo(userId, sessionId, first, last, gender, email, min_age, max_age) {
    method = "post";

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    form = document.getElementById("fbinfo");
    form.setAttribute("method", method);
    form.setAttribute("action", "process/fblogin.php");

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "userId");
    hiddenField.setAttribute("value", userId);

    form.appendChild(hiddenField);

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "sessionId");
    hiddenField.setAttribute("value", sessionId);

    form.appendChild(hiddenField);

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "first");
    hiddenField.setAttribute("value", first);

    form.appendChild(hiddenField);

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "last");
    hiddenField.setAttribute("value", last);

    form.appendChild(hiddenField);

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "gender");
    hiddenField.setAttribute("value", gender);

    form.appendChild(hiddenField);

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "email");
    hiddenField.setAttribute("value", email);

    form.appendChild(hiddenField);

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "min_age");
    hiddenField.setAttribute("value", min_age);

    form.appendChild(hiddenField);

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "max_age");
    hiddenField.setAttribute("value", max_age);

    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();
}

// Creates a cookie at the current time with an offset of the given number of days
function createCookie(name, value, days) {
    var date, expires;
    if (days) {
        date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = name+"="+value+expires+"; path=/";
}