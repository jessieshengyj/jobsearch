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
        <title>Company Post Job</title>
        <style>
            <?php include 'style.css'; ?>
            <?php include 'images/favicon.ico'; ?>
        /* <link rel="stylesheet" type='text/css' href="style.php" /> */
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

    <body style="background: linear-gradient(100grad, #bda7cb, #6f7ca2)">
        <div class="main_company_postjob" style="padding-bottom: 100px; padding-top: 30px">
            <div class="form" style="text-align: center" id="FullTime">
                <form class="job_form" method="POST" action="company_postjob.php">
                <h1>Post a New Job</h1>
                <fieldset><br>
                <legend>Job Info</legend>

                <input type="hidden" id="insertJobRequest" name="insertJobRequest">
                Job ID (Number) [Required Field]: <input type="text" name="InsJobID"> <br /><br />
                Application Deadline (yyyy--mm--dd): <input type="text" name="InsJobDeadline"> <br /><br />
                Remote (yes/no): <input type="text" name="InsJobRemote"> <br /><br />
                Position Name [Required Field]: <input type="text" name="InsJobPosName"> <br /><br />
                Start Date (yyyy--mm--dd): <input type="text" name="InsJobStartDate"> <br /><br />
                Company ID (Number) [Required Field]: <input type="text" name="InsJobCompID"> <br /><br />
                Job Category: <input type="text" name="InsJobCatagory"> <br /><br />

                </fieldset><br>
                <input class="button" type="submit" value="Post" name="insertSubmit"></p>
                <!-- <input class="button" type="submit" value="Submit" name="fulltimeSubmit"><br> -->
                </form>
            </div>
        </div>
    </body>

    <body>


        <?php
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $job2OK = 0;
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

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
                // echo htmlentities($e['message']);
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
                    // echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    // $emessage = $e['message'];
                    echo $emessage;
                    $JobIDNotUnique = "ORA-00001: unique constraint (ORA_DEVR07.SYS_C001878700) violated";
                    $NullJobID = "ORA-01400: cannot insert NULL into (\"ORA_DEVR07\".\"JOB1\".\"JOBID\")";
                    $JobCompIDNotNumber = "ORA-01722: invalid number";
                    $NoSuchCompany = "ORA-02291: integrity constraint (ORA_DEVR07.SYS_C001878701) violated - parent key not found";
                    $NullJobPosition = "ORA-01400: cannot insert NULL into (\"ORA_DEVR07\".\"JOB2\".\"POSITIONNAME\")";
                    $NullCompanyID = "ORA-01400: cannot insert NULL into (\"ORA_DEVR07\".\"JOB1\".\"COMPANYID\")";
                    echo "<script>";    
                    switch ($emessage) {
                        case $JobIDNotUnique:
                            echo "alert('Job post unsuccessful, there is already another job with that ID, please try again with a different ID');";
                            break;
                        case $NullJobID:
                            echo "alert('Job post unsuccessful, ensure you entered a value for job ID and try again');";
                            break;
                        case $JobCompIDNotNumber:
                            echo "alert('Job post unsuccessful, ensure that you entered a number for the Company ID and Job ID, please try again');";
                            break;
                        case $NoSuchCompany:
                            echo "alert('Job post unsuccessful, there is no company that goes by that company ID, ensure that the company ID is correct and that the company has been created and try again');";
                            break;
                        case $NullJobPosition:
                            // echo "Job post unsuccessful, ensure that you enetered a value for position name and try again"; 
                            echo "alert('Job post unsuccessful, ensure that you entered a value for position name and try again');";
                            // $job2NullFail = 1;
                            break;
                        case $NullCompanyID:
                            echo "alert('Job post unsuccessful, ensure that you entered a value for the company ID and try again');";
                            break;
                        default:
                            if (str_contains($emessage, "ORA-00001")) {   
                                global $job2OK;
                                if ($job2OK == 0) {
                                    // here we are in job2, cant be in job1 bc if job2 failed job1 doesnt run
                                } else {
                                    echo "alert('Job post unsuccessful, there is already another job with that ID, please try again with a different ID');";
                                }        
                            } else if (str_contains($emessage, "ORA-02291")) {
                                echo "alert('Job post unsuccessful, there is no company that goes by that company ID, ensure that the company ID is correct and that the company has been created and try again');";
                            } else {
                                echo "alert('Job post unsuccessful, An unexpected error has occured, please double check all information and try again');";  
                            }             
                    }
                    echo "</script>";
                    $success = False;
                } else {
                    global $job2OK;
                    if ($job2OK == 0) {
                        // here were sucess where job2 failed, but job1 went through 
                        // or job1 passed (in which the message will just be the error alert from job2 failing so no messages needed here)
                        $job2OK = 1;
                    } else {
                        // here were going through second success, means both job2, job1 were good
                        echo "<script>";
                        echo "alert('Job post successfully created');";
                        echo "</script>";
                    }
                    
                }
            }
            return $statement;
        }

        function printBrowseJobs($result) { //prints results from a select statement
            echo "<br>Retrieved data from table demoTable:<br>";
            echo "<table>";
            echo "<tr><th>Job ID</th><th>Position Name</th><th>Job Category</th><th>Job is Remote</th><th>Job Start Date</th><th>Application Deadline</th><th>Company ID</th></tr>";
            // echo "<tr><th>TestHeader</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                print_r($row);
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
                ":bind1" => $_POST['InsJobPosName'],
                ":bind2" => $_POST['InsJobCatagory']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into Job2 values (:bind1, :bind2) ", $alltuples);
            OCICommit($db_conn);  
            global $job2OK;
            if ($job2OK) {
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
                OCICommit($db_conn);
            }
           
            
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

            executeBoundSQL("delete from Company where CompanyID = :bind1:", $alltuples);
            OCICommit($db_conn);
        }

        function handleBrowseJobCategoriesRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['BrowseJobCatergory'],
            );
            $alltuples = array (
                $tuple
            );

            $result = executeBoundSQL("select J1.JobID, J1.PositionName, J2.JobCategory, J1.Remote, J1.StartDate, J1.ApplicationDeadline, J1.CompanyID from Job1 J1, Job2 J2 where J2.JobCategory = :bind1 AND J1.PositionName = J2.PositionName ", $alltuples);
            print_r($result);
            printBrowseJobs($result);
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
                } else if (array_key_exists('BrowseJobCatergory', $_POST)) {
                    handleBrowseJobCategoriesRequest();
                }

                disconnectFromDB();
            }
        }


        function handleCountJobByTypeRequest() {
            global $db_conn;

            $result= executePlainSQL("SELECT DISTINCT JobCategory FROM Job2");
             echo "<table>";
            echo "<tr><th>Job Category</th></tr>";
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
        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('countJobsByType', $_GET)) {
                    handleCountJobByTypeRequest();
                } else if (array_key_exists('FindEmployersMultEmployees', $_GET)) {
                    handleFindEmplyersMultEmployees();
                }

                disconnectFromDB();
            }
        }

        function handleDisplayJobCatDropdown() {
            if (connectToDB()) {
                $result= executePlainSQL("SELECT DISTINCT JobCategory FROM Job2");
                while ($row = oci_fetch_array($result, OCI_BOTH))
                    {
                         echo "<option value=\"JobCategory\">" . $row[0] . "</option>";
                    }
                disconnectFromDB();
            }
        }


		if (isset($_POST['reset']) || isset($_POST['updateAccountInfoSubmit']) || isset($_POST['insertSubmit']) || isset($_POST['DeleteSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countJobsRequest']) || isset($_GET['employersMultipleEmployees'])) {
            handleGETRequest();
        }
		?>
	</body>
</html>
