<%@ include file="jspf/javaclassesinc.jspf" %>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<SCRIPT language=javascript>

    function loadval()
    {
        selindex=document.assignip.popipaddress.selectedIndex;
        window.opener.document.globeacct.ipaddress.value = document.assignip.popipaddress[selindex].value;
        window.close();
    }

    function reload(dropdown)
    {
        var myindex  = dropdown.selectedIndex;
        var SelValue = dropdown.options[myindex].value;
        if( myindex == 1 )
        {
            var baseURL = "assignip.jsp?index=1";
            top.location.href = baseURL;
        }
        if( myindex == 2 )
        {
            var baseURL = "assignip.jsp?index=2";
            top.location.href = baseURL;
        }
        return true;

    }

</SCRIPT>
<HTML>
    <HEAD>
        <TITLE>Assign IP Address</TITLE>
        <META http-equiv=Content-Type content="text/html; charset=iso-8859-1"><LINK
            href="css/default.css" type="text/css" rel=stylesheet>
        <META NAME="Generator" CONTENT="EditPlus">
        <META NAME="Author" CONTENT="">
        <META NAME="Keywords" CONTENT="">
        <META NAME="Description" CONTENT="">
    </HEAD>

    <%!    String loadlocation = null;
    String uid = null;
    String ip = null;
    String init = null;
    String index = null;
    String foundRealm = null;
    int itr = 0;
    List<Rbipaddress> ipAddresses;
    %>

    <%
            index = request.getParameter("index");

            if (index.equals("1")) {
                loadlocation = "<OPTION value=0>-select one-</OPTION><OPTION value=CEBU selected>CEBU</OPTION> <OPTION value=MAKATI>MAKATI</OPTION>";
            } else if (index.equals("2")) {
                loadlocation = "<OPTION value=0>-select one-</OPTION><OPTION value=CEBU>CEBU</OPTION> <OPTION value=MAKATI selected>MAKATI</OPTION>";
            } else {
                loadlocation = "<OPTION value=0>-select one-</OPTION><OPTION value=CEBU>CEBU</OPTION> <OPTION value=MAKATI>MAKATI</OPTION>";
            }
    %>
    <BODY>
        <FORM name=assignip >

            <SELECT name=iplocation onchange="reload(assignip.iplocation)">
                <%= loadlocation%>
            </SELECT>
            <SELECT id=ipid name=popipaddress>
                <%
            out.println("<OPTION value=''>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</OPTION>");
            String location = "";
            boolean isLuzon = true;
            if (index.equals("1") || index.equals("2")) {
                if (index.equals("1")) {
                    location = "CEBU";
                    isLuzon = false;
                } else if (index.equals("2")) {
                    location = "MAKATI";
                    isLuzon = true;
                }
                ipAddresses = OradbHelper.findAllUnusedIpAddresses(isLuzon); // Two DB, ignore location column, filter determines physical location
                int size = ipAddresses.size();
                for (int a = 0; a < size; a++) {
                    String addr = ipAddresses.get(a).getIpaddress();
                    if (a == 0) {
                        out.println("<OPTION value=" + addr + " selected>" + addr + "</OPTION>");
                    } else {
                        out.println("<OPTION value=" + addr + ">" + addr + "</OPTION>");
                    }
                }
            }
                %>
            </SELECT>
            <INPUT class=button onclick="loadval()" type=button value="Select">
            <INPUT class=button onclick="window.close()" type=button value="Cancel">
        </FORM>
    </BODY>
</HTML>

