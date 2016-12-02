//-------------------------------------------------------------------
// Globals variables
//-------------------------------------------------------------------
myApp =
{
    general:
    {
        timerCount:5
    }
}
//-------------------------------------------------------------------


//-------------------------------------------------------------------
// This method performs countdown before redirecting to index.
//-------------------------------------------------------------------
function countDown()
{
    var timer = document.getElementById("timer");

    if(myApp.general.timerCount > 0)
    {
        timer.innerHTML = "This page will redirect in "+myApp.general.timerCount+" seconds.";
        setTimeout("countDown()", 1000);
        myApp.general.timerCount--;
    }
    else
    {
        window.location.href = "http://".concat(window.location.hostname);
    }
}
//-------------------------------------------------------------------