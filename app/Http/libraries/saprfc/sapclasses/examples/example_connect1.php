<html>
<head>
   <title>SAPConnection Class: Connection to Application Server</title>
</head>
<body>
<h1>SAPConnection Class: Connection to Application Server</h1>
<?php
    include_once ("../sap.php");

    $sap = new SAPConnection();
    // Params:                        hostname   sysnr
    $sap->ConnectToApplicationServer ("10.1.24.52", "01");
    // Params:  client username  password  language
    $sap->Open ("300", "intranet","informat","ES");
    $sap->PrintStatus();
    $sap->GetSystemInfo();
    echo "<BR><PRE>"; print_r ($sap); echo ("</PRE>");
    $sap->Close();
?>
</body>
</html>
