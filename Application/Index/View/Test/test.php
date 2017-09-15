<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
    <meta charset="UTF-8">
    <script src="js/jquery-1.7.min.js" type="text/javascript"></script>
   <script src="http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js" type="text/ecmascript"></script>
    <script type="text/javascript">
        function getIpPlace() {


        alert(remote_ip_info["province"] + "省" + ',' + remote_ip_info["city"] + "市")


        } </script>
</head>
<body>
    <form id="form1" runat="server">
    <div>
        <input id="Button1" type="button" value="根据IP地址获取所在城市" onclick="getIpPlace();" />
    </div>
    </form>
</body>
</html>
