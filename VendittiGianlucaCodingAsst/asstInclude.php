<?php

//http://localhost/VendittiGianlucaCodingAsst/asstMain.php

function CreateConnectionObject()
{
    $fh = fopen('auth.txt','r');
    $Host =  trim(fgets($fh));
    $UserName = trim(fgets($fh));
    $Password = trim(fgets($fh));
    $Database = trim(fgets($fh));
    $Port = trim(fgets($fh)); 
    fclose($fh);
    $mysqlObj = new mysqli($Host, $UserName, $Password,$Database,$Port);
    // if the connection and authentication are successful, 
    // the error number is 0
    // connect_errno is a public attribute of the mysqli class.
    if ($mysqlObj->connect_errno != 0) 
    {
     echo "<p>Connection failed. Unable to open database $Database. Error: "
              . $mysqlObj->connect_error . "</p>";
     // stop executing the php script
     exit;
    }
    return ($mysqlObj);
}

function advancedHeader($Heading="COMP 220 Fall 2021 Coding Assignment", $TitleBar="COMP 220 Assignment")
{
echo "
        <!DOCTYPE html>
        <html lang=\"en\">
        <head>
        <title>$TitleBar</title>
        <meta charset=\"utf-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
        <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css\">
        <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>
        <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js\"></script>
        <link rel =\"stylesheet\" type = \"text/css\" href=\"asstStyle.css\"/>
        </head>
        <body>

        <div class=\"jumbotron jumbotron-fluid\">
        <div class=\"container\">
        <h1 class=\"display-3\">$Heading</h1>
        <p class=\"lead\">The purpose of this application is to track road tests for an autonomous vehicle. The user will be able to create a table, add records and display all records in the table.</p>
        </div>
    </div>";
}
                

function WriteHeaders($Heading="Welcome", $TitleBar="MySite")
{
    echo "
    <!doctype html>                  
    <html lang = \"en\">
    <head>
        <meta charset = \"UTF-8\">
        <title>$TitleBar</title>
        <link rel =\"stylesheet\" type = \"text/css\" href=\"asstStyle.css\"/>
        <link rel=\"stylesheet\" href=\" https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css\">
        <link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css\">
    </head>
    <body>\n     
    <h1>$Heading - Gianluca Venditti </h1>
    ";
}

function DisplayLabel($prompt)
{
    echo "<label>" . $prompt . "</label>";
}

function DisplayTextBox($Type,$Name, $Size, $value=0)
{
    echo " <Input type = \"$Type\"  name = \"$Name\" Size = \"$Size\" value = \"$value\">";
}

function DisplayContactInfo()
{
    echo "<footer>Questions? Comments?
    <a href = \"mailto:gianluca.venditti@student.sl.on.ca\">gianluca.venditti@student.sl.on.ca
    </a></footer>";
}

function DisplayImage($Filename, $Alt, $Width, $Height)
{
    echo" <img src=\"$Filename\" alt=\"$Alt\" width=\"$Width\" height=\"$Height\">";
}

function DisplayButton($Name, $Text, $Filename = "", $Alt = "")
{   
   if ($Filename != "") // 
   {
       echo"<button type=Submit  name = \"$Name\">";
       DisplayImage("$Filename", "$Alt", 30,40); // add transparent imgaes 
       echo "</button>";
   }
   else //otherwise display a normal button
   {
    
    echo "<button type=Submit class=\"btn btn-primary\" name = \"$Name\">$Text</button>"; 
   
   }  
}

function WriteFooters()
{
    DisplayContactInfo();
    echo "</body>\n";
    echo "</html>\n";
}


?>