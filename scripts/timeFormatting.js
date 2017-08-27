/**
 * Created by Nick on 7/10/17.
 */
function userTime2(time){
    if(time == "None"){
        return "None";
    }
    var offset = new Date().getTimezoneOffset();
    var date = new Date(new Date(time));
    date = new Date(date.setMinutes(date.getMinutes() - offset));
    var dateString = (date.getMonth()+1)+"/"+date.getDate()+"/"+date.getFullYear();
    return dateString;
}