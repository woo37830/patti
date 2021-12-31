<%@ page language="Java" import="java.util.*, java.io.*, skins.UserProfileBean" %>

<%-- Load Property bundle --%>
<%
	Properties props  = new Properties();
	try {
    	 props.load( new FileInputStream( application.getRealPath("./data/skin.properties") )  ) ;
   	}
   	catch( Exception e ) {
      	e.printStackTrace( System.err ) ;
   	}
   	String p1 = request.getParameter("skin");
   	String p2 = request.getParameter("id");
   	if( p1 == null )
   		p1 = "preferred";
   	if( p2 == null )
   		p2 = "logo";
	String parameter = p1 + "." +  p2 ;

	if( parameter == null || props.getProperty( parameter ) == null )	{
		%><h1>parameter or props.getProperty() for <%=parameter%> is null</h1><%
	} else	{
	%><h1><%=parameter%></h1><%
%>

  <jsp:forward page="<%=props.getProperty( parameter )%>"/>


<% } %>
