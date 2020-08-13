
$(document).ready(function() {
    // This is done on the client in the browser after the html of the page has loaded
    $("#form-div").load(".//form.jsp");
    $('#info').hide();
    $('#debug').hide();

    $(document).on('click', "li", function(evt) {
        var processName = $(this).text();
        var parent = $(this).parent('ul eq(1)');
        var parentName = $(this).parent('ul').parent('li').attr('id');
        var theToken = $('#token').text();
        try {
            window.location.href = "./dispatcher.php?event=_start&process=" +
                    processName + "&app=" + parentName +
                    "&userToken=" + theToken;
        } catch (err) {
            var errMsg = "<doc><errors><error>" + err + "</error></errors><tasks></tasks></doc>";
            window.location.href = ".//home.jsp?process=tasklist&app=Home&error=" + errMsg + "&userToken=" + theToken;
            evt.stopPropagation();
        }
        evt.stopPropagation();
    });
    $(document).on('click', '#home-td', function() {
        var theToken = $('#token').text();
        window.location.href = ".//home.jsp?process=tasklist&app=Home&userToken=" + theToken;
    });
    $("#bugs").click(function() {
        showBugList();
    });
    $("#notes").click(function() {
        showNoteList();
    });
    $("#title").click(function() {
        var aName = $(this).text();
        var appX = $(document).find("#doApplication").text();
        window.open(".//organizations/Shoulders/doc/" + appX + "/" + aName + ".html");
    });

    $("#doApplication").click(function() {
        var appName = $(this).text();
        window.open(".//organizations/Shoulders/doc/" + appName + "/application-summary.html");
    });

    $("#doProcess").click(function() {
        var processName = $(this).text();
        var aName = $(document).find("#doApplication").text();
        var theToken = $('#token').text();
        var oName = "Shoulders";
        window.open(".//workflow_viewer.jsp?process=" + processName + "&app=" + aName + "&organization=" + oName);
    });
    $("#doJob").click(function() {
        alert("Show the Job History for: " + $(this).text());
    });
    // get the style from the user token and set that as the css for the page(s)
    var _n = $('#token').text().lastIndexOf(":") + 1;
    var style = $('#token').text().substring(_n);
    if (_n > 0) {
        var cssFile = "_css/" + style + ".css";
        var logoFile = "_images/" + style + "_logo.gif";
        $('#styleid').attr('href', cssFile); // should also set logo depending upon style and put style into userToken
        $(document).find("#logo").attr('src', logoFile);
    }
    // Change the style sheet and reset that portion of the user token
    $('#styleCombo').change(function() {
        var cssFile = "_css/" + $('#styleCombo option:selected').val() + ".css";
        var logoFile = "_images/" + style + "_logo.gif";
        var __n = $('#token').text().lastIndexOf(":") + 1;
        if (__n > 0) {
            var _style = $('#styleCombo option:selected').val();
            var new_token = $('#token').text().substring(0, __n) + _style;
            $('#token').text(new_token);
        }
        $(document).find("#logo").attr('src', logoFile);
        //$('#logo').attr('src', logoFile);
        $('#styleid').attr('href', cssFile); // should also set logo depending upon style and put style into userToken
    });
    $(document).on('click', "#button-table td", function() {

        var t = $(this).attr("id");
        $('input[name=transition]').val(t);
        $('input[name=process]').val($('#doProcess').text());
        $('input[name=activity]').val($('#activity-name').text());
        $('input[name=app]').val($('#doApplication').text());
        $('input[name=oid]').val($('#oid-div').text());

        $('form#data_form').submit();

    });
    $("#info-div").click(function() {
        var $messageDiv = $('#info'); // get the reference of the div
        $messageDiv.slideDown(function() {
            $messageDiv.css("visibility", "visible"); // show and set the message
            setTimeout(function() {
                $messageDiv.slideUp();
            }, 10000);
        });
        var $debugDiv = $('#debug'); // get the reference of the div

        $debugDiv.slideDown(function() {
            $debugDiv.css("visibility", "visible"); // show and set the message
            setTimeout(function() {
                $debugDiv.slideUp();
            }, 10000);
        });

        var $debugToken = $('tr #debug-token');
        $debugToken.slideDown(function() {
            $debugToken.css("visibility", "visible");
            setTimeout(function() {
                $debugToken.slideUp();
            }, 10000);
        })
    });

    $('#remainingTime').countdown({until: $("#time-div").text(), format: "mS", expiryText: "Entry Released", description: 'Remaining', onTick: watchCountdown});

    function watchCountdown(periods) {
        if (periods[5] * 60 + periods[6] < 5) {
            $('#remainingTime').css({'background-color': 'red'});
        }
        else if (periods[5] * 60 + periods[6] < 15) {
            $('#remainingTime').css({'background-color': 'yellow'});
        } else {
            $('#remainingTime').css({'background-color': '#9999CC'});
        }
    }

    var request;
    // bind to the submit event of our form
    $(document).find('#renew-form').submit(function(event) {
        // abort any pending request
        if (request) {
            request.abort();
        }
        // setup some local variables
        var $form = $(this);
        var $timeVal = $(document).find('#time-div').text();
        $timeVale = $timeVal.trim();
        $('input[name=oid]').val($('#oid-div').text());
        $('input[name=userToken]').val($('#token').text());
        $('input[name=expectedServiceTime]').val($timeVal);
        // let's select and cach all the fields
        var $inputs = $form.find("input, select, button, textarea");
        // serialize the data in the form
        var serializedData = $form.serialize();
        // disable the inputs for the duration of the ajax request
        $inputs.prop("disabled", true);
        // fire off the event to the dispatcher
        event.preventDefault();
        request = $.get("./dispatcher.php", serializedData, processReset).error(errorResponse);
        request = null;
        $inputs.prop("disabled", false);

        return true;
        //var request = $.ajax({
        //    url: "./dispatcher.php",
        //    type: "post",
        //    data: serializedData})
        //    .done(function(){
        //    })
        //    .fail(function(request,statusText,errorThrown){alert('Failure: '+statusText+", "+errorThrown);})
        //    .always(function(){$inputs.prop("disabled", false)});

        // prevent default posting of form
    });

});
function errorResponse() {
    $('#remainingTime').countdown('option', {
        until: $("#time-div").text(), format: "mS", expiryText: "Entry Released", description: 'Remaining'});
}
function processReset(data) {
    alert('Done, data = ' + data);
    $('#remainingTime').countdown('option', {
        until: $("#time-div").text(), format: "mS", expiryText: "Entry Released", description: 'Remaining'});
}

//highlight colors for background and foreground
var highlightColor = "#ffd700";
var highlightFColor = "#ffffff";

//normal colors for background and foreground
var normalColor = "#9999CC";
var normalFColor = "#000000";

//comma delimited list of chosen item values
var str = "";

//Highlight a menu tab
function mouseOver_Color(myObj)
{
    myObj.style.backgroundColor = highlightColor;
    myObj.style.color = highlightFColor;
    myObj.style.cursor = "hand";
}

//Show the normal menu tab
function mouseOut_Color(myObj)
{
    myObj.style.backgroundColor = normalColor;
    myObj.style.color = normalFColor;
    myObj.style.cursor = "default";
}

function disabledMenuClick(menuObj)
{
    alert('This function is not available.');
}

function logout()
{
    document.location.href = './/login.jsp?event=logout';
    //document.location.href = 'login.jsp';

}

function resetTimer()
{
    $('#remainingTime').countdown('option', {
        until: $("#time-div").text(), format: "mS", expiryText: "Entry Released", description: 'Remaining'});
}

function showTaskList()
{
    document.location.href = 'home.jsp';
}


function showBugList()
{
    window.open(
            'http://jwooten37830.com/mantisbt-1.2.10',
            '_blank');
}

function showNoteList()
{
    window.open(
            'http://jwooten37830.com/blog',
            '_blank');

}


function supportCenter(menuObj)
{
    document.location.href = 'http://www.blivenow.com/live/352187325499';
}



function toggleChecks(theForm, menuObj)
{
    var text = menuObj.toString();
    if (text.length > 0) {
        if (str.indexOf('|' + text + '|') != -1)
            removeSelectedItem(text);
        else
            addSelectedItem(text);
    }
    if (str.length != 0) {
        setDirtyFlag(true);
        document.forms.hitherForm.selectionStr.value = stripCharsInBag(str, "|");
    }
    else {
        setDirtyFlag(false);
        document.forms.hitherForm.selectionStr.value = '';
    }
}

function addSelectedItem(menuObj)
{
    var lText = menuObj.toString();
    if (str.length == 0)
        str += '|' + lText + '|';
    else
        str += ',' + '|' + lText + '|';
}
function removeSelectedItem(menuObj)
{
    var mText = menuObj.toString();
    var loc = str.indexOf('|' + mText + '|');
    var firstPart = str.substring(0, loc);
    var secondPart = str.substring(loc + mText.length + 3, str.length);
    str = firstPart + secondPart;
    if (str.indexOf(",") == 0 && str.length > 1)
        str = str.substring(1, str.length);
    if (str.lastIndexOf(",") == str.length && str.length > 1)
        str = str.substring(0, str.length - 1);
    if (str == ",")
        str = "";
}


