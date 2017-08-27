/**
 * Created by Nick on 7/10/17.
 */
function userTime2(time){
    if(time == "None"){
        return "None";
    }
    var date = new Date(new Date(time));
    var dateString = (date.getMonth()+1)+"/"+date.getDate()+"/"+date.getFullYear();
    return dateString;
}