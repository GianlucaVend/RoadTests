<?php 
require_once('asstInclude.php');
require_once('clsCreateRoadTestTable.php');

//http://localhost/VendittiGianlucaCodingAsst/asstMain.php

//Main 
date_default_timezone_set ('America/Toronto');
$mysqlObj;

$TableName = "RoadTest";

advancedHeader();

//Nested if statement to display the appropriate forms
if(isset($_POST["f_DisplayData"]))
    DisplayDataForm($mysqlObj, $TableName);
else 
    if(isset($_POST["f_CreateTable"]))
        CreateTableForm($TableName); 
    else 
        if(isset($_POST["f_AddRecord"]))
            AddRecordForm(); 
        else 
            if(isset($_POST["f_Save"]))
                SaveRecordToTableForm($TableName);
            else 
                if (isset($_POST["f_Home"]))
                    DisplayMainForm();
                else 
                    DisplayMainForm(); 

if (isset($mysqlObj)) $mysqlObj->close();

WriteFooters();

Function DisplayMainForm()
{
    echo "<form action = ? method=post>";
    DisplayButton("f_CreateTable", "Create Table" , "Table.png", "Table.png");
    DisplayButton("f_AddRecord", "Add Record" , "Add.png", "Add.png");
    DisplayButton("f_DisplayData", "DisplayData", "Display.png", "Display.png");
    echo "</form>";
}

Function CreateTableForm($TableName)
{
    $tableForm = new clsCreateRoadTestTable;
    $tableForm->createTheTable($mysqlObj,$TableName);
    echo "<form action = ? method=post>";
    DisplayButton("f_Home", "Home" , "Home.png", "Home.png");
    echo"</form>";
}

Function AddRecordForm()
{
    $Date = date('Y-m-d');
    $Time = date('H:i');

    echo "<form action = ? method=post>";
    echo "<div class=\"datapair\">";
    DisplayLabel("Add Record");
    DisplayTextBox("text", "f_LicensePlate",10, "ABCD 123");
    echo "</div>";

    echo "<div class=\"datapair\">";
    DisplayLabel("Date Stamp");
    DisplayTextBox("date", "f_DateStamp",10,"$Date");
    echo "</div>";

    echo "<div class=\"datapair\">";
    DisplayLabel("Time Stamp");
    DisplayTextBox("time", "f_TimeStamp",10,"$Time");
    echo "</div>";

    echo "<div class=\"datapair\">";
    DisplayLabel("Number of Passengers");
    DisplayTextBox("number", "f_NumberOfPassengers",10, "3");
    echo "</div>";
    
    echo "<div class=\"datapair\">";
    DisplayLabel("Incident free");
    DisplayTextBox("checkbox", "f_IncidentFree","", "");
    echo "</div>";

    echo "<div class=\"datapair\">";
    DisplayLabel("Danger Status");
    echo"<select name=\"f_DangerStatus\" id=\"danger\">
        <option value=\"Low\">Low</option>
        <option value=\"Medium\" selected >Medium</option>
        <option value=\"High\">High</option>
        <option value=\"Crtical\">Crtical</option>
    </select>";
     echo "</div>";

    echo "<div class=\"datapair\">";
    DisplayLabel("Speed");
    DisplayTextBox("text", "f_Speed","5", "100");
    echo "</div>";

    DisplayButton("f_Save", "Save", "save.png", "save.png");
    DisplayButton("f_Home", "Home" , "Home.png", "Home.png");
    echo "</form>";
}


Function SaveRecordToTableForm($TableName)
{
    $mysqlObj = CreateConnectionObject();
    $LicensePlate = $_POST ["f_LicensePlate"];
    $DateAndTime = $_POST["f_DateStamp"] . " " . $_POST["f_TimeStamp"];
    $NumberOfPassengers = $_POST["f_NumberOfPassengers"];
    
    if(isset($_POST ["IncidentFree"])) // if theres a value in the checkbox
        $IncidentFree = true; 
    else 
        $IncidentFree = false; 

    $DangerStatus = substr($_POST["f_DangerStatus"], 0, 1); 

    $Speed = $_POST["f_Speed"];

    $Cost =  5000 + (100 * $NumberOfPassengers); 

    $mysqlObj = CreateConnectionObject();

    $query = "Insert into $TableName(licensePlate, dateTimeStamp,
                                    nbrPassengers,incidentFree, dangerStatus,speed, cost) 
                                    Values( ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqlObj->prepare($query);
    if ($stmt == false)
    {
        echo "Prepare failed on query $query" . $mysqlObj->error;
        exit; 
    }

    $BindSuccess = $stmt->bind_param("ssiisdd" ,
                                    $LicensePlate, $DateAndTime,$NumberOfPassengers,$IncidentFree,
                                    $DangerStatus, $Speed, $Cost); 

    if($BindSuccess)
        $Success = $stmt->execute();
    else
        echo "Bind Faild" . $stmt->error; 

    if($Success)
        echo "Record successfully added to $TableName";
    else 
        echo "Unable to add record to $TableName";
    $stmt->close();

    echo "<form action = ? method=post>";
    DisplayButton("f_Home", "Home" , "Home.png", "Home.png");
    echo"</form>";
}

Function DisplayDataForm(&$mysqlObj, $TableName)
{
    $mysqlObj = CreateConnectionObject();
    $query = "Select licensePlate, dateTimeStamp,
              nbrPassengers,incidentFree, dangerStatus,speed, cost 
            From $TableName
            order by dangerStatus DESC";
    $stmt = $mysqlObj->prepare($query);

    $stmt->execute();

   $stmt->bind_result($LicensePlate, $DateAndTime,$NumberOfPassengers,$IncidentFree,
   $DangerStatus, $Speed, $Cost);

   echo "
   <table class=\"table table-bordered\">
   <thead>
       <tr>
        <th> License Plate </th>
        <th> Date Time Stamp </th>
        <th> Number of Passengers </th>
        <th> Incident Free </th>
        <th> Danger Status </th>
        <th> Speed </th>
        <th> Cost </th>
       </tr>
       </thead>";

       while($stmt->fetch())
       {
        if ($IncidentFree = true)
        {
            $IncidentFree = "Yes";
        }
        else 
        {
            $IncidentFree = "No"; 
        }

        switch($DangerStatus)
        {
            case "L":
                $DangerStatus = "Low"; 
                break; 
            case "M":
                $DangerStatus = "Medium"; 
                break; 
            case "H":
                $DangerStatus = "High"; 
                break; 
            case "C":
                $DangerStatus = "Crtical"; 
                break; 
        }

        echo "
        <tr>
        <td>$LicensePlate</td>
        <td>". substr($DateAndTime, 0, 9) . " at " . substr($DateAndTime,11) ." </td>
        <td>$NumberOfPassengers</td>
        <td> $IncidentFree</td>
        <td>$DangerStatus</td>
        <td>$Speed</td>
        <td>\$$Cost</td>
        </tr> ";
       }
   echo "</table>";

   echo "<form action = ? method=post>";
   DisplayButton("f_Home", "Home" , "Home.png", "Home.png");
   echo"</form>";
}
?>