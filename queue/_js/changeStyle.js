$(document).ready(function() {
    // This is done on the client in the browser after the html of the page has loaded   // Change the style sheet and reset that portion of the user token
    $("#form-div").load("...//form.jsp");
    $(document).on('click', '#home-td', function() {
        var theToken = $('#token').text();
        window.location.href = "../home.jsp?process=tasklist&app=Home&userToken=" + theToken;
    });

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
});
