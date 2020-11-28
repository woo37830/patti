

var width = 100;
 top = 75;
 left = 60;
var rotation = 0;
var margintop = 0;
var xoffset = 150;
var yoffset = 100;
var fudge = 10;

$("#draw").css("background", "#cccccc");

function drawBox(id, str, x, y, h, w)
{
    $("#"+id).text(str);
    $("#"+id).css({
        "position": "absolute",
        "top": yoffset+y+"px",
        "left":  xoffset+x+"px",
        "height": h+"px",
        "width": w+"px",
        "background-color": "yellow",
        "border": "1px solid black",
        "text-align": "center",
        "line-height": "100px"

    });

}
// draw horizontal line;
function drawLine(width,x,y,rotation) 
{  
    if( rotation == 90 )
        {
            x = x - 1/2 * width;
            y = y + 1/2 * width;
        }
            $("<draw>").css({
           "height": "2px",
           "background": "black",
           "width": width,
           "position": "absolute",
           "top": y,
           "left": x,
            "margin-top": 0-margintop,
            "-webkit-transform": "rotate("+rotation+"deg)",
            "-moz-transform": "rotate("+rotation+"deg)",
            "-ms-transform": "rotate("+rotation+"deg)",
            "-o-transform": "rotate("+rotation+"deg)",
            "transform": "rotate("+rotation+"deg)"
            
        }).appendTo("#draw");
}
function drawStr(id, str, x, y)
{
    $("#"+id).text(str);
    $("#"+id).css({
        "position": "absolute",
        "top": 1.0*(y),
        "left": x,
        "text-align": "center"
    });
}

function drawArrowHead(x, y)
{
    drawLine( 20, x-.866*10-5, y, 60 );
    drawLine( 20, x-5, y, -60 );
}

function drawReverseArrowHead( x, y )
{
    drawLine( 20, x-.866*10-5, y, -60 );
    drawLine( 20, x-5, y, 60 );
    
}
function drawForwardConnection(x1, width1, y1, x2, width2,y2, labelId, label )
{
    
    var w1 = x1 - x2;
    var w2 = y2 - y1;
    //$("#box4").text(x1+","+y1+","+x2+","+y2+","+w1+","+w2);
    if( w2 < 0 )
        {
            w2 = -w2;
        }
    if( w1 < 0 )
        {
            w1 = -w1;
        }
    if( x2 < x1 )
    {  // child is on the left  
        drawLine( w1-fudge, x1-w1+fudge, y1, 0 );      
        drawArrowHead( x2+fudge, y2 );       
        drawLine( w2, x2+fudge, y1, 90 );
    }
    else
        { // child is on the right
            drawLine( w1-width1-fudge, x1+width1, y1, 0 );
            drawArrowHead( x2-fudge, y2 );       
            drawLine( w2, x2-fudge, y1, 90 );
       }

   $('#draw').append("<div id='label"+labelId+"'>"+label+"</div>");
   if( x2 < x1 ) {
       drawStr("label"+labelId, label, x2+fudge+5, 0.5*(y1+y2)  );
   } else {
      var L = label.length;
      drawStr("label"+labelId, label, x2-30-5, 0.5*(y1+y2)  );
   }
}

function drawReverseConnection( x1, y1, x2, y2, labelId, label )
{
    var w1 = x1 - x2;
    var w2 = y2 - y1;
    if( w2 < 0 )
        {
            w2 = - w2;
        }
    drawLine( w1, x2, y1, 0 );
    drawLine( w2, x2, y2, 90 );
    drawReverseArrowHead( x2, y2 );
  $('#draw').append("<div id='label"+labelId+"'>"+label+"</div>");
  if( x2 < x1 ) {
       drawStr("label"+labelId, label, x2+fudge+5, 0.5*(y1+y2)  );
   } else {
      var L = label.length;
      drawStr("label"+labelId, label, x2-30-5, 0.5*(y1+y2)  );
   }
}

// Need to know list of parents and labels of links to child
// Or create all states, then add links to children with labels.
function addState(id, label, x, y, h, w )
{
    $('#draw').append("<div id='box"+id+"'>"+label+"</div>");
    drawBox("box"+id,label, x, y, h, w);
 }
function addLink(parentId, childId, id, transition)
{
    var parent = $('#draw').find("#box"+parentId);
    //parent.css("background-color","red");
    var position = parent.position();
    var parentHeight = parent.height();
    var parentWidth = parent.width();
    var child = $('#draw').find("#box"+childId);
    var childPosition = child.position();
    //child.css("background-color","green");
    var x = childPosition.left;
    var y = childPosition.top;
    var h = child.height();
    var w = child.width();
    //parent.text(position.left+","+position.top+","+parentHeight+","+parentWidth);
    //child.text(x+","+y+","+h+","+w);
   // $('#draw').append("<div id='label"+id+"'>"+label+"</div>");
   var pX = position.left;
   var pY = position.top+.5*parentHeight;
   var cX = x+w*.5;
   var cY = y-5;
   drawForwardConnection(pX, parentWidth, pY, cX, w, cY, "label"+id, transition);
}
function addReverseLink(parentId, childId, id, transition)
{
    var parent = $('#draw').find("#box"+parentId);
    //parent.css("background-color","red");
    var position = parent.position();
    var parentHeight = parent.height();
    var parentWidth = parent.width();
    var child = $('#draw').find("#box"+childId);
    var childPosition = child.position();
    //child.css("background-color","green");
    var x = childPosition.left;
    var y = childPosition.top;
    var h = child.height();
    var w = child.width();
    //parent.text(position.left+","+position.top+","+parentHeight+","+parentWidth);
    //child.text(x+","+y+","+h+","+w);
   // $('#draw').append("<div id='label"+id+"'>"+label+"</div>");
   var pX = position.left;
   var pY = position.top+.5*parentHeight;
   var cX = x+w*.5;
   var cY = y-5;
   drawForwardConnection(pX, parentWidth, pY, cX, w, cY, "label"+id, transition);
}

    function Transition( name, action, role, target, visible )
    {
        var t = {};
        t.name = name;
        t.action = action;
        t.role = role;
        t.target = target;
        t.visible = visible;
        return t;
    }
    function State(name, description, role) {
        var state = {};
        state.name = name;
        state.description = description;
        state.role = role;
        state.transitions = [];
        state.addTransition = addTransition;
        function addTransition( t ) {
            this.transitions.push(t);
        }
        state.outComes = outComes;
        function outComes(role) {
            var o = [];
            for( var i=0;i<this.transitions.length; i++)
                {
                    if( this.transitions[i].role === role ||
                        this.transitions[i].role === '*' ||
                        role === '*')
                    {
                    o.push( this.transitions[i].name );
                    }
                }
            return o;
        }
        state.advance = advance;
        function advance( selection ) {
            for( var i=0; i<this.transitions.length; i++ )
                {
                    if( selection === this.transitions[i].name )
                        {
                            return this.transitions[i].target;
                        }
                }
                return 'quit';
        }
        return state;
    }
    
        function printModel( hashMap )
    {
       // console.log("Showing the hashMap");
            hashMap.each(function(key, value){
                console.log("'"+value.name +"' -> "+value.description);
        });
        console.log("Showing a value '"+findValue('createDependents', hashMap).name+"'");
    }
    function findValue( s, hashMap )
    {   
        var obj = [null];
       // console.log("Find: '"+s+"'");
            hashMap.each(function(key, value){
         //       console.log("value = '"+value.name+"'");
                if( s == value.name )
                    {
                        obj = value;
                       // console.log("Saved '"+value.name+"' as return value");
                    }
            });
        return obj;
    }
    function showModel( model, hashMap )
    {
        console.log( 'State: '+model.name+", outcomes: "+ model.outComes('*'));
        if( model.name == 'done' || model.name == 'exit'  )
            {
                return;
            }
        var oC = model.outComes('*');
        for( var i=0; i<oC.length; i++ )
            { 
                var t = oC[i];
                console.log("  Transition: "+t);
                var s = model.advance(t);
                console.log( "  To State name: '"+s+"'");
                if( s != model.name )
                    {   
                        var aS = findValue( s, hashMap );
                        //console.log("aS = "+aS.name);
                        if( aS !== null )
                            {
                                showModel( aS, hashMap );
                            } else {
                                console.log('Failed to find state: '+s);
                            }
                    }
            }
    }
     var iS = null;
     var states = new HashMap();
 
    var parseModel = function( data )
    {
            var model = $(data).find('name').first();
            $('h1').text( model.text() ) ;
            var desc = $(data).find('description').text();
            $('#description').append(desc+'<p />');
            var initialState = $(data).find('initialState');
            $('h2').text('Initial State: '+initialState.text());
            iS = new State(initialState.text(), desc, '*');
            states( initialState.text(), iS );
            $('#container').text("States");
            // Loop over states
            $(data).find('state').each( function() {
                var sN = $(this).find('name').first();
                var dN = "";
                $(this).find('init-param').each( function() {
                    var ddN = $(this).find('param-name').first().text();
                    if( ddN === 'description')
                    {
                        dN = $(this).find('param-value').first().text();
                        console.log('stateName:' + sN.text()+ ', dN: '+dN);
                   }
                });
                $('#container').append('<br />'+sN.text()+" ("+dN+")");
                var s = null;
                if( sN.text() === initialState.text() )
                    {
                        s = iS;
                    } else {
                        s = new State(sN.text(), dN, '*' );
                        states( sN, s );
                    }
                // Loop over transitions
                $(this).find('transition').each( function () 
                {
                    var tName = $(this).find('name').text();
                    var target = $(this).find('success').text();
                    var t = new Transition(tName,'',"*",target, true );
                    s.addTransition( t );
                    $('#container').append('<br />-------'+tName);
                    $('#container').append(' ------> '+target);
                })
            });
            $('#container').append("<br />");
            console.log("States: ");
            states.each(function(key, value){
                console.log("'"+value.name +"' -> "+value.description);
            });
            //printModel( states );
            showModel( iS, states );
            console.log('All Done!');
        }