<!--Test Oracle file for UBC CPSC304 2018 Winter Term 1
  Created by Jiemin Zhang
  Modified by Simona Radu
  Modified by Jessica Wong (2018-06-22)
  This file shows the very basics of how to execute PHP commands
  on Oracle.
  Specifically, it will drop a table, create a table, insert values
  update values, and then query for values

  IF YOU HAVE A TABLE CALLED "demoTable" IT WILL BE DESTROYED

  The script assumes you already have a server set up
  All OCI commands are commands to the Oracle libraries
  To get the file to work, you must place it somewhere where your
  Apache server can run it, and you must rename it to have a ".php"
  extension.  You must also change the username and password on the
  OCILogon below to be your ORACLE username and password -->

  <html>
    <head>
        <title>Other Info</title>
        <link rel="icon" type="image/x-icon" href="images/favico.ico">
        <style>
            <?php include 'style.css'; ?>
        /* <link rel="stylesheet" type='text/css' href="style.php" /> */
        html, body {
            background: linear-gradient(100grad, #937ea2, #1f2560);
        }

        body, div, form, input, select, p {
            font-family: Roboto, Arial, sans-serif;
            font-size: 16px;
            color: #eee;
        }

        select{
            min-height: 150px;
            background-color: black;
        }

        body {
            margin: 0;
            font-family: Helvetica, serif;
            background-size: cover;
        }

        h1, h2 {
            text-transform: uppercase;
            font-weight: 400;
        }

        h2 {
            margin: 0 0 0 5px;
        }
        .main-block {
            padding-top: 100px;
            /* display: flex; */
            margin-top: 5rem;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding: 10px;
            background: rgba(0, 0, 0, 0);
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        form {
            padding: 25px;
            padding-bottom: 0px;
        }

        form {
            background: rgba(0,0,0,0.5);
            border-radius: 15px;
        }

        .title {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .info {
            display: flex;
            flex-direction: column;
        }

        input, select {
            padding: 5px;
            margin-bottom: 30px;
            /* background: transparent; */
            border: none;
            border-bottom: 1px solid #eee;
        }

        button {
            padding: 10px 5px;
            /*border-radius: 5px;*/
            background: #26a9e0;
            font-size: 15px;
            font-weight: 400;
            color: #fff;
            border: none;
        }

        button {
            width: 100%;
        }
        button:hover {
            background: #85d6de;
        }
        @media (min-width: 568px) {
            html, body {
                height: 100%;
            }

            .main-block {
                flex-direction: row;
                height: calc(100% - 50px);
            }

            form {
                flex: 1;
                height: auto;
            }

            .navbar_container{
                padding-bottom: 50px;
            }


        }
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 70%;
            margin: auto;
            padding-top: 20px;
            padding-bottom: 0px;
        }

        .resultTable {
            overflow: auto;
            padding-top: 20px;
            padding-bottom: 40px;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        div.blacktd{
            font-family: Roboto, Arial, sans-serif;
            color: #eee;
        }

        select.select-1 {
             min-height: 50px;
             background-color: black;
             color: white;
        }

    </style>
    </head>
    <body>
        <div class="navbar_container">
            <div class="navbar">
                <a href="home.php">Home</a>
                <a href="otherinfo.php" class="right">Other Info</a>
                <div class="dropdown">
                    <button class="dropbtn">Company
                    </button>
                    <div class="dropdown-content">
                        <a href="company_account.php">Account</a>
                        <a href="company_postjob.php">Post Job</a>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="dropbtn">Applicant
                    </button>
                    <div class="dropdown-content">
                        <a href="applicant_account.php">Account</a>
                        <a href="applicant_browsejobs.php">Browse Jobs</a>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <body>
        <?php
            if (isset($_POST['reset']) || isset($_POST['updateAccountInfoSubmit']) ||
                    isset($_POST['createAccount']) || isset($_POST['createCompany']) ||
                    isset($_POST['createJob']) || isset($_POST['deleteCompany']) ||
                    isset($_POST['findAllJobaForComp']) || isset($_POST['FindAttributes']) ||
                    isset($_POST['FindAttributes']) || isset($_POST['SelectColumnNames'])) {
                    handlePOSTRequest();
                } else if (isset($_GET['countJobsRequest']) || isset($_GET['employersMultipleEmployees']) ||
                isset($_GET['employersRemoteInPerson']) || isset($_GET['catWithHighestAvgSal'])) {
                    handleGETRequest();
                }
        ?>
    </body>
    <body>
        <div class="main-block">
            <form action="otherinfo.php" method="post">
                <div class="title">
                    <h2>View tables</h2>
                </div>
                <div class="info">
                    <select name="selectedTableToView" class = "select-1">
                    <option value="" disabled selected>Choose a table to view</option>
                        <?php
                        handleDisplayTables();
                        ?>
                    </select>
                    <input type="submit" name="FindAttributes" value="Find Attributes" class="button">
                </div>
            </form>
        </div>

        <?php
        // start_session();
        // echo $_SESSION['SavedSelectedTable'];
        // echo "AT START";


        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        $selectedTable = NULL;
        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr);
            //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }

			return $statement;
		}

        function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

			global $db_conn, $success;
			$statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    //echo $val;
                    //echo "<br>".$bind."<br>";
                    OCIBindByName($statement, $bind, $val);
                    unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
				}
                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
            return $statement;
        }

        function printBrowseJobs($result) { //prints results from a select statement
            // print_r($result);
            echo "<table>";
            echo "<tr><th>Job ID</th><th>Position Name</th><th>Job Category</th><th>Job is Remote</th><th>Job Start Date</th><th>Application Deadline</th><th>Company ID</th></tr>";
            // echo "<tr><th>TestHeader</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["JOBID"] . "</td><td>" . $row["POSITIONNAME"] . "</td><td>" . $row["JOBCATEGORY"] . "</td><td>" . $row["REMOTE"] . "</td><td>" . $row["STARTDATE"] . "</td><td>" . $row["APPLICATIONDEADLINE"] . "</td><td>" . $row["COMPANYID"] . "</td></tr>"; //or just use "echo $row[0]"
                // echo "<tr><td>test</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example,
			// ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("ora_devr07", "a80666621", "dbhost.students.cs.ubc.ca:1522/stu");

            if ($db_conn) {
                debugAlertMessage("Database is Connected");
                return true;
            } else {
                debugAlertMessage("Cannot connect to Database");
                $e = OCI_Error(); // For OCILogon errors pass no handle
                echo htmlentities($e['message']);
                return false;
            }
        }

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }

        function handleUpdateApplicantRequest() {
            global $db_conn;
            $tuple = array (
                ":bind1" => $_POST['UpdUserID'],
                ":bind2" => $_POST['UpdEmail'],
                ":bind3" => $_POST['UpdName'],
                ":bind4" => $_POST['UpdAddress'],
                ":bind5" => $_POST['UpdPhoneNumber']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("update Applicant set Email = :bind2, Name = :bind3, Addy = :bind4, PhoneNumber = :bind5 where UserID = :bind1", $alltuples);
            OCICommit($db_conn);
        }

        function handleResetRequest() {
            global $db_conn;
            // Drop old table
            executePlainSQL("DROP TABLE demoTable");

            // Create new table
            echo "<br> creating new table <br>";
            executePlainSQL("CREATE TABLE demoTable (id int PRIMARY KEY, name char(30))");
            OCICommit($db_conn);
        }

        function handleInsertApplicantRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['InsUserID'],
                ":bind2" => $_POST['InsEmail'],
                ":bind3" => $_POST['InsName'],
                ":bind4" => $_POST['InsAddress'],
                ":bind5" => $_POST['InsPhoneNumber']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into Applicant values (:bind1, :bind2, :bind3, :bind4, :bind5)", $alltuples);
            OCICommit($db_conn);
        }



        function handleInsertJobRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['InsJobID'],
                ":bind2" => $_POST['InsJobDeadline'],
                ":bind3" => $_POST['InsJobRemote'],
                ":bind4" => $_POST['InsJobPosName'],
                ":bind5" => $_POST['InsJobStartDate'],
                ":bind6" => $_POST['InsJobCompID']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into Job1 values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6)", $alltuples);
            $tuple = array (
                ":bind1" => $_POST['InsJobPosName'],
                ":bind2" => $_POST['InsJobCatagory']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into Job2 values (:bind1, :bind2) ", $alltuples);
            OCICommit($db_conn);
        }

        function handleInsertCompanyRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['InsCompanyCompanID'],
                ":bind2" => $_POST['InsCompanyName'],
                ":bind3" => $_POST['InsCompanyNumberOfEmployees']
            );
            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into Company values (:bind1, :bind2, :bind3)", $alltuples);
            OCICommit($db_conn);
        }

        function handleDeleteCompanyRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['DelCompanyCompanID'],
            );
            $alltuples = array (
                $tuple
            );

            executeBoundSQL("delete from Company where CompanyID = :bind1", $alltuples);
            OCICommit($db_conn);
        }

        function handleBrowseJobCategoriesRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['JobCategory'],
            );
            $alltuples = array (
                $tuple
            );
            // echo "<h2>" . $_POST['JobCategory'] . "</h2>";
            $result = executeBoundSQL("select J1.JobID, J1.PositionName, J2.JobCategory, J1.Remote, J1.StartDate, J1.ApplicationDeadline, J1.CompanyID from Job1 J1, Job2 J2 where J2.JobCategory = :bind1 AND J1.PositionName = J2.PositionName ", $alltuples);

            printBrowseJobs($result);
        }

        function handleJoinCompJobs() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['joinCompID']
            );
            $alltuples = array (
                $tuple
            );

            $result  = executeBoundSQL("SELECT J1.JobID, J2.JobCategory, J1.positionName, C.name FROM Job1 J1, Job2 J2, Company C WHERE C.CompanyID = J1.CompanyID AND J1.PositionName = J2.positionName AND C.CompanyID = :bind1", $alltuples);
            // echo "<br>All jobs for the company: " . OCI_Fetch_Array($result, OCI_BOTH)[3] . "<br>";
            echo "<table>";
            echo "<tr><th>Job ID</th><th>Job Category</th><th>Position Name</th><th>Company Name</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function handleRenderingColumnNamesDropDown() {

            $tuple = array (
                ":bind1" => $_POST['selectedTableToView']
            );
            $alltuples = array (
                $tuple
            );
            $temp = $_POST['selectedTableToView'];


        // <div class="main-block">
        //     <form method="POST" action="applicant_account.php"> <!--refresh page when submitted-->
        //         <div class="title">
        //             <h2>Create Account</h2>
        //         </div>
        //         <div class="info">
        //             <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
        //             UserID (Number) [Required Field]: <input type="text" name="InsUserID"> <br /><br />
        //             Name: <input type="text" name="InsName"> <br /><br />
        //             Email [Required Field]: <input type="text" name="InsEmail"> <br /><br />
        //             Address: <input type="text" name="InsAddress"> <br /><br />
        //             Phone Number: <input type="text" name="InsPhoneNumber"> <br /><br />
        //             <input type="submit" value="Insert" name="insertSubmit" class="button"></p>
        //         </div>
        //     </form>
        // </div>

            echo "<div class=\"main-block\"> \n
            <form action=\"\" method=\"post\"> \n
            <div class=\"title\"> \n
            <h2>Select Attributes from the table </h2> \n
            </div> \n
            <div class=\"info\"> \n
            <input type=\"hidden\" id=\"TableNameLastStep\" name=\"TableNameLastStep\" value =" . $_POST['selectedTableToView'] . "> \n
            <select name=\"ColumnNames[]\" multiple = \"multiple\"> \n
            <option value=\"\" disabled selected>Select Attributes To Include</option> \n";
                    if (connectToDB()) {
                        $result= executeBoundSQL("select column_name from USER_TAB_COLUMNS WHERE table_name= :bind1",  $alltuples);

                        while ($row = oci_fetch_array($result, OCI_BOTH))
                            {
                                 echo "<option value= ". $row[0] . ">" . $row[0] . "</option>";
                            }
                        disconnectFromDB();
                    }
            echo   "</select> \n
                <input type=\"submit\" name=\"SelectColumnNames\" value=\"Select Attributes\" class = \"button\"> \n
                </div> \n
            </form> \n
            </div>";
        }


        function handleProcessColumnNames() {
            $sqlquery = "select ";
            foreach($_POST['ColumnNames'] as $row) {
                $sqlquery .= " " . $row . ",";
            }

            $sqlquery = rtrim($sqlquery, ", ");
            $tableName = $_POST['TableNameLastStep'];
            // $tuple = array (
            //     ":bind1" => $_POST['tableStringLastStep']
            // );
            // $alltuples = array (
            //     $tuple
            // );
            // $sqlquery .=  " FROM :bind1";
            // $result  = executeBoundSQL($sqlquery, $alltuples); this doesnt work?

            $result  = executePlainSQL($sqlquery . " FROM " . $tableName);
            // echo $_SESSION['SavedSelectedTable'];
            // echo $_COOKIE["cookieSelectedTable"];
            echo "<table>";
            echo "<tr>";
            foreach($_POST['ColumnNames'] as $row) {
                echo "<th>" . $row . "</th>";
            }
            echo "</tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr>";
                $myint = 0;
                foreach($_POST['ColumnNames'] as $col) {
                    echo "<td>" . $row[$myint] . "</td>";
                    $myint += 1;
                }
                echo "</tr>";

            }
            echo "</table>";
        }


        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('resetTablesRequest', $_POST)) {
                    handleResetRequest();
                } else if (array_key_exists('updateQueryRequest', $_POST)) {
                    handleUpdateApplicantRequest();
                } else if (array_key_exists('insertQueryRequest', $_POST)) {
                    handleInsertApplicantRequest();
                } else if (array_key_exists('insertJobRequest', $_POST)) {
                    handleInsertJobRequest();
                } else if (array_key_exists('insertCompanyRequest', $_POST)) {
                    handleInsertCompanyRequest();
                }  else if (array_key_exists('deleteCompanyRequest', $_POST)) {
                    handleDeleteCompanyRequest();
                } else if (array_key_exists('JobCategory', $_POST)) {
                    handleBrowseJobCategoriesRequest();
                }  else if (array_key_exists('joinAllJobsFoCompany', $_POST)) {
                    handleJoinCompJobs();
                } else if (array_key_exists('selectedTableToView', $_POST)) {
                    handleRenderingColumnNamesDropDown();
                } else if (array_key_exists('ColumnNames', $_POST)) {
                    handleProcessColumnNames();
                }

                disconnectFromDB();
            }
        }


        function handleCountJobByTypeRequest() {
            global $db_conn;
            $result = executePlainSQL("SELECT J2.JobCategory, Count(*) FROM Job1 J1, Job2 J2 WHERE J1.PositionName = J2.PositionName GROUP BY J2.JobCategory");
            echo "<br>Found Number of Jobs grouped by category:<br>";
            echo "<table>";
            echo "<tr><th>Job Category</th><th>Number of Jobs in Category</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";

            // $result = executePlainSQL("SELECT J2.JobCategory, Count(*) FROM Job1 J1, Job2 J2 WHERE J1.PositionName = J2.PositionName GROUP BY J2.JobCategory");
            // echo "<br>Found Number of Jobs grouped by category:<br>";
            // echo "<table>";
            // echo "<tr><th>Job Category</th><th>Number of Jobs in Category</th></tr>";
            // while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            //     echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
            // }

            // echo "</table>";

        }

        function handleFindEmplyersMultEmployees() {
            global $db_conn;

            $result = executePlainSQL("SELECT C.Name, Count(*) FROM Job1 J1, Company C WHERE J1.CompanyID = C.CompanyID GROUP BY C.Name HAVING Count(*) >= 2");
            echo "<br>Found Number employers looking for more than 1 employee:<br>";
            echo "<table>";
            echo "<tr><th>Employer</th><th>Number of employees being hired</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";

        }

        function handleFindCompRemoteInPerson() {
            $result = executePlainSQL("SELECT c1.CompanyID, c1.Name FROM Company c1 WHERE NOT EXISTS ((SELECT j1.Remote FROM  job1 j1) MINUS  (SELECT j2.Remote FROM Company c2, Job1 j2 WHERE c2.companyID = j2.companyID AND c1.companyID = c2.companyID))");
            echo "<br>Found Companies hiring for both in person and remote jobs:<br>";
            echo "<table>";
            echo "<tr><th>Company ID</th><th>Company Name</th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function handleFindCatWithHighestAverageSalary() {
            $result = executePlainSQL("SELECT JobCategory, avg(salary) FROM FullTimeJob f, Job1 j1, Job2 j2 WHERE  f.jobID = j1.jobID AND j1.positionName = j2.positionName GROUP BY j2.JobCategory HAVING avg(salary) >= ALL (SELECT avg(salary) FROM FullTimeJob f2, Job1 j3, Job2 j4 WHERE  f2.jobID = j3.jobID AND j3.positionName =  j4.positionName GROUP BY j4.JobCategory)");
            echo "<br>The job sector with the highest average salary in current job postings is: " . OCI_Fetch_Array($result, OCI_BOTH)[0]. "<br>";

        }
        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('countJobsByType', $_GET)) {
                    handleCountJobByTypeRequest();
                } else if (array_key_exists('FindEmployersMultEmployees', $_GET)) {
                    handleFindEmplyersMultEmployees();
                }  else if (array_key_exists('FindCompRemoteInPerson', $_GET)) {
                    handleFindCompRemoteInPerson();
                }  else if (array_key_exists('findCatWithHighestAvgSal', $_GET)) {
                    handleFindCatWithHighestAverageSalary();
                }

                disconnectFromDB();
            }
        }

        function handleDisplayTables() {
            if (connectToDB()) {
                $result= executePlainSQL("SELECT table_name FROM user_tables");
                echo "<div class = \"blacktd\">";
                while ($row = oci_fetch_array($result, OCI_BOTH))
                    {
                         echo "<option value= ". $row[0] . ">" . $row[0] . "</option>";
                    }
                disconnectFromDB();
                echo "</div>";
            }
        }

        function handleDisplayJobCatDropdown() {
            if (connectToDB()) {
                $result= executePlainSQL("SELECT DISTINCT JobCategory FROM Job2");
                while ($row = oci_fetch_array($result, OCI_BOTH))
                    {
                         echo "<option value= ". $row[0] . ">" . $row[0] . "</option>";
                    }
                disconnectFromDB();
            }
        }



		?>
	</body>
</html>