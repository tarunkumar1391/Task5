<?php
/**
 * Created by PhpStorm.
 * User: tarun
 * Date: 12/7/15
 * Time: 2:07 PM
 */
session_start();


// DB credentials
include "config.php";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//form Validation Script

//Define all variables and set them to empty values --catergorised as per sections
//1.Administrative Details
$proj_title = $prop_inst  = $prop_state = "";
//Director of the institution
$dir_title = $dir_lname = $dir_fname = $dir_street = $dir_pcode = $dir_city = $dir_phone = $dir_email = "";
//Principal Investigator
$pi_title = $pi_lname = $pi_fname = $pi_street = $pi_pcode = $pi_city = $pi_phone = $pi_email = "";
//Project Manager
$pm_title = $pm_lname = $pm_fname = $pm_street = $pm_pcode = $pm_city = $pm_phone = $pm_fax = $pm_email = $pm_gender = $pm_nat = $pm_tuid = $pm_tu_id = $pm_lichtacc = "";
//Researcher- person 1
$res1_title = $res1_lname = $res1_fname = $res1_phone = $res1_email = $res1_nat = $res1_tuid = $res1_tu_id = $res1_lichtacc = "";
//Researcher- person 2
$res2_title = $res2_lname = $res2_fname = $res2_phone = $res2_email = $res2_nat = $res2_tuid = $res2_tu_id = $res2_lichtacc = "";
//Project partners
$pp_title = $pp_inst = $pp_lname = $pp_fname = $pp_street = $pp_pcode = $pp_city = $pp_email = $pp_phone = $pp_tuid = $pp_tu_id = $pp_lichtacc ="";
//project details
$research_area = $research_area_new = $proj_enddate = $proj_hours = "";
//Abstract
$abstract ="";
//Technical Description- project class
$proj_class = "";
//Detailed resource requirements
$cpu_time = $acce_nvidia = $acce_xeonphi = $mem_pc = $home_dir = $work_proj = $work_scratch = "";
//resource requirements for a typical batch run
$req_maxcores = $req_cputime = $req_mmpc = $req_dspace = "";
//Programming languages
$proglang = $proglang_other = "";
//programming models
$progmodels = $progmodel_other = "";
//tools
$tools = $tools_other = "";
//libraries
$lib = "";
//special requirements
$spl_req = "";
//final agreements
$agree1 = $agree2 = $agreefinal = "";

//errors array
$error = [
    "proj_title" => "", "prop_inst" => "", "prop_state" => "",
    "dir_title" => "", "dir_lname" => "", "dir_fname" => "", "dir_street" => "", "dir_pcode" => "", "dir_city" => "", "dir_phone" => "", "dir_email" => "",
    "pi_title" => "", "pi_lname" => "", "pi_fname" => "","pi_street" => "", "pi_pcode" => "", "pi_city" => "", "pi_phone" => "","pi_email" => "",
    "pm_title" => "", "pm_lname" => "", "pm_fname" => "", "pm_street" => "", "pm_pcode" => "", "pm_city" => "","pm_phone" => "","pm_fax" => "","pm_email" => "","pm_nat" => "","pm_tuid" => "","pm_tu_id" => "","pm_lichtacc" => "",
    "res1_title" => "", "res1_lname" => "", "res1_fname" => "", "res1_phone" => "","res1_email" => "","res1_nat" => "", "res1_tuid" => "", "res1_tu_id" => "","res1_lichtacc" => "",
    "res2_title" => "", "res2_lname" => "", "res2_fname" => "", "res2_phone" => "","res2_email" => "","res2_nat" => "", "res2_tuid" => "", "res2_tu_id" => "","res2_lichtacc" => "",
    "pp_title" => "", "pp_inst" => "", "pp_lname" => "", "pp_fname" => "", "pp_street" => "", "pp_pcode" => "", "pp_city" => "", "pp_email" => "", "pp_phone" => "", "pp_tuid" => "", "pp_tu_id" => "", "pp_lichtacc" => "",
    "research_area" => "", "research_area_new" => "", "proj_enddate" => "", "proj_hours" => "", "abstract" => "", "proj_class" => "", "cpu_time" => "", "acce_nvidia" => "", "acce_xeonphi" => "","mem_pc" => "","home_dir" => "", "work_proj" => "", "work_scratch" => "",
    "req_maxcores" => "", "req_cputime" => "","req_mmpc" => "", "req_dspace" => "","proglang" => "", "proglang_other" => "", "progmodels" => "","progmodel_other" => "", "tools" => "", "tool_other" => "",
    "lib" => "", "spl_req" => "", "agree1" => "", "agree2" => "", "agreefinal" => "","captchaerr" =>""
];

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
//Prepared statement : Total of 91 input form elements  + 1 field in database for s.no
$stmt = $conn->prepare("INSERT INTO formgen (proj_title,prop_inst,prop_state,dir_title,dir_lname,dir_fname,dir_street,dir_pcode,dir_city,dir_phone,dir_email,
                                pi_title,pi_lname,pi_fname,pi_street,pi_pcode,pi_city,pi_phone,pi_email,pm_title,pm_lname,pm_fname,pm_street,pm_pcode,pm_city,pm_phone,pm_fax,pm_email,
                                pm_nat,pm_tuid,pm_tu_id,pm_lichtacc,res1_title,res1_lname,res1_fname,res1_phone,res1_email,res1_nat,res1_tuid,res1_tu_id,res1_lichtacc,res2_title,
                                res2_lname,res2_fname,res2_phone,res2_email,res2_nat,res2_tuid,res2_tu_id,res2_lichtacc,pp_title,pp_inst,pp_lname,pp_fname,pp_street,
                                pp_pcode,pp_city,pp_email,pp_phone,pp_tuid,pp_tu_id,pp_lichtacc,research_area,research_area_new,proj_enddate,proj_hours,abstract,proj_class,
                                 cpu_time,acce_nvidia,acce_xeonphi,mem_pc,home_dir,work_proj,work_scratch,req_maxcores,req_cputime,req_mmpc,req_dspace,proglang,proglang_other,progmodels,progmodel_other,tools,
                                 tools_other,lib,spl_req,agree1,agree2,agreefinal,timestmp)
                            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
                            ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // First starting with validation and assignment of variables
    //Administrative details
    if (empty($_POST['proj_title']) ) {

        $error['proj_title'] = "Project Title is required";

    } else {
        $proj_title = test_input($_POST["proj_title"]);
        // check if name only contains letters and whitespace

    }

    if (empty($_POST["prop_inst"])) {
        $error['prop_inst'] = "Name of the proposing University/Institution is required";
    } else {
        $prop_inst = test_input($_POST["prop_inst"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/",$prop_inst)) {
            $error['prop_inst'] = "Only letters and white space allowed";
        }
    }

    if (empty($_POST["prop_state"])) {
        $error['prop_state'] = "Choose a state";
    } else {
        $prop_state = test_input($_POST["prop_state"]);

    }

    //1.1 Director of the institution
    if (empty($_POST["dir_title"])) {
        $error['dir_title'] = "Choose a Title";
    } else {
        $dir_title = test_input($_POST["dir_title"]);

    }
    if (empty($_POST["dir_lname"])) {
        $error['dir_lname'] = "Enter your Last name";
    } else {
        $dir_lname = test_input($_POST["dir_lname"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/",$dir_lname)) {
            $error['dir_lname'] = "Only letters and white space allowed";
        }
    }
    if (empty($_POST["dir_fname"])) {
        $error['dir_fname'] = "Enter your First name";
    } else {
        $dir_fname = test_input($_POST["dir_fname"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/",$dir_fname)) {
            $error['dir_fname'] = "Only letters and white space allowed";
        }
    }

    if (empty($_POST["dir_street"])) {
        $error['dir_street'] = "Enter your Street Address";
    } else {
        $dir_street = test_input($_POST["dir_street"]);
        if(!preg_match("/^[a-zA-Z]([a-zA-Z-]+\s)+\d{1,4}$/",$dir_street)){
            $error['dir_street'] = "The street entered is invalid";
        }

    }

    if (empty($_POST["dir_pcode"])) {
        $error['dir_pcode'] = "Enter your pin code";
    } else {
        $dir_pcode = test_input($_POST["dir_pcode"]);
        // check if name only contains letters and whitespace
        if (!preg_match("^((?:0[1-46-9]\d{3})|(?:[1-357-9]\d{4})|(?:[4][0-24-9]\d{3})|(?:[6][013-9]\d{3}))^",$dir_pcode)) {
            $error['dir_pcode'] = "The pincode entered is invalid";
        }
    }

    if (empty($_POST["dir_city"])) {
        $error['dir_city'] = "Enter your City";
    } else {
        $dir_city = test_input($_POST["dir_city"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z\x{0080}-\x{024F}\s\/\-\)\(\`\.\"\']+$/u",$dir_city)) {
            $error['dir_city'] = "The city entered is invalid";
        }

    }

    if (empty($_POST["dir_phone"])) {
        $error['dir_phone'] = "Enter your Phone number";
    } else {
        $dir_phone = test_input($_POST["dir_phone"]);


    }
    if (empty($_POST["dir_email"])){
        $dir_email = "Enter a valid email address";
    } else {
        $dir_email = test_input($_POST["dir_email"]);
        if (!filter_var($dir_email, FILTER_VALIDATE_EMAIL)) {
            $error['dir_email'] = "Invalid email format";
        }
    }

    //1.2 Principal Investigator
    $pi_title = test_input(isset($_POST['pi_title']) ? $_POST['pi_title'] : "0");
    $pi_lname = test_input(isset($_POST['pi_lname']) ? $_POST['pi_lname'] : "0");
    $pi_fname = test_input(isset($_POST['pi_fname']) ? $_POST['pi_fname'] : "0");
    $pi_street = test_input(isset($_POST['pi_street']) ? $_POST['pi_street'] : "0");
    $pi_pcode = test_input(isset($_POST['pi_pcode']) ? $_POST['pi_pcode'] : "0");
    $pi_city = test_input(isset($_POST['pi_city']) ? $_POST['pi_city'] : "0");
    $pi_phone = test_input(isset($_POST['pi_phone']) ? $_POST['pi_phone'] : "0");
    $pi_email = test_input(isset($_POST['pi_email']) ? $_POST['pi_email'] : "0");

    //1.3 Project Manager
    if (empty($_POST["pm_title"])) {
        $error['pm_title'] = "Choose a Title";
    } else {
        $pm_title = test_input($_POST["pm_title"]);

    }
    if (empty($_POST["pm_lname"])) {
        $error['pm_lname'] = "Enter your Last name";
    } else {
        $pm_lname = test_input($_POST["pm_lname"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/",$pm_lname)) {
            $error['pm_lname'] = "Only letters and white space allowed";
        }
    }
    if (empty($_POST["pm_fname"])) {
        $error['pm_fname'] = "Enter your First name";
    } else {
        $pm_fname = test_input($_POST["pm_fname"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/",$pm_fname)) {
            $error['pm_fname'] = "Only letters and white space allowed";
        }
    }
    if (empty($_POST["pm_street"])) {
        $error['pm_street'] = "Enter your street address";
    } else {
        $pm_street = test_input($_POST["pm_street"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z]([a-zA-Z-]+\s)+\d{1,4}$/",$pm_street)) {
            $error['pm_street'] = "Only letters and white space allowed";
        }
    }
    if (empty($_POST["pm_pcode"])) {
        $error['pm_pcode'] = "Enter your postal code";
    } else {
        $pm_pcode = test_input($_POST["pm_pcode"]);
        // check if name only contains letters and whitespace
        if (!preg_match("^((?:0[1-46-9]\d{3})|(?:[1-357-9]\d{4})|(?:[4][0-24-9]\d{3})|(?:[6][013-9]\d{3}))^",$pm_pcode)) {
            $error['pm_pcode'] = "Invalid format";
        }
    }
    if (empty($_POST["pm_city"])) {
        $error['pm_city'] = "Enter the name of your city";
    } else {
        $pm_city = test_input($_POST["pm_city"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z\x{0080}-\x{024F}\s\/\-\)\(\`\.\"\']+$/u",$pm_city)) {
            $error['pm_city'] = "Invalid Format";
        }
    }
    if (empty($_POST["pm_phone"])) {
        $error['pm_phone'] = "Enter your Phone number";
    } else {
        $pm_phone = test_input($_POST["pm_phone"]);
        // check if name only contains letters and whitespace

    }
        $pm_fax = test_input(isset($_POST['pm_fax']) ? $_POST['pm_fax'] : "0");
    if (empty($_POST["pm_email"])) {
        $error['pm_email'] = "Enter a email address";
    } else {
        $pm_email = test_input($_POST["pm_email"]);
        // check if e-mail address is well-formed
        if (!filter_var($pm_email, FILTER_VALIDATE_EMAIL)) {
            $error['pm_email'] = "Invalid email format";
        }
    }
    if (empty($_POST["pm_nat"])) {
        $error['pm_nat'] = "choose your nationality";
    } else {
        $pm_nat = test_input($_POST["pm_nat"]);


    }

    $pm_tuid = test_input(isset($_POST['pm_tuid']) ? $_POST['pm_tuid'] : "0");

    if ($_POST['pm_tuid']=="yes" && empty($_POST["pm_tu_id"])) {
        $error['pm_tu_id'] = "Enter your TU Id";
    } else if($_POST['pm_tuid']=="no"){
        $error['pm_tu_id'] = "";
    } else {
        $pm_tu_id = test_input($_POST["pm_tu_id"]);
    }

    if (empty($_POST["pm_lichtacc"])) {
        $error['pm_lichtacc'] = "Choose an option";
    } else {
        $pm_lichtacc = test_input($_POST["pm_lichtacc"]);

    }
    //1.4 Additional Researchers
    //Researcher- person 1
    $res1_title = test_input(isset($_POST['res1_title']) ? $_POST['res1_title'] : "0");
    $res1_lname = test_input(isset($_POST['res1_lname']) ? $_POST['res1_lname'] : "0");
    $res1_fname = test_input(isset($_POST['res1_fname']) ? $_POST['res1_fname'] : "0");
    $res1_phone = test_input(isset($_POST['res1_phone']) ? $_POST['res1_phone'] : "0");
    $res1_email = test_input(isset($_POST['res1_email']) ? $_POST['res1_email'] : "0");
    $res1_nat = test_input(isset($_POST['res1_nat']) ? $_POST['res1_nat'] : "0");
    $res1_tuid = test_input(isset($_POST['res1_tuid']) ? $_POST['res1_tuid'] : "0");
    $res1_tu_id = test_input(isset($_POST['res1_tu_id']) ? $_POST['res1_tu_id'] : "0");
    $res1_lichtacc = test_input(isset($_POST['res1_lichtacc']) ? $_POST['res1_lichtacc'] : "0");
    //Researcher- person 2
    $res2_title = test_input(isset($_POST['res2_title']) ? $_POST['res2_title'] : "0");
    $res2_lname = test_input(isset($_POST['res2_lname']) ? $_POST['res2_lname'] : "0");
    $res2_fname = test_input(isset($_POST['res2_fname']) ? $_POST['res2_fname'] : "0");
    $res2_phone = test_input(isset($_POST['res2_phone']) ? $_POST['res2_phone'] : "0");
    $res2_email = test_input(isset($_POST['res2_email']) ? $_POST['res2_email'] : "0");
    $res2_nat = test_input(isset($_POST['res2_nat']) ? $_POST['res2_nat'] : "0");
    $res2_tuid = test_input(isset($_POST['res2_tuid']) ? $_POST['res2_tuid'] : "0");
    $res2_tu_id = test_input(isset($_POST['res2_tu_id']) ? $_POST['res2_tu_id'] : "0");
    $res2_lichtacc = test_input(isset($_POST['res2_lichtacc']) ? $_POST['res2_lichtacc'] : "0");
    //1.5 Project Partners
    $pp_title = test_input(isset($_POST['pp_title']) ? $_POST['pp_title'] : "0");
    $pp_inst = test_input(isset($_POST['pp_inst']) ? $_POST['pp_inst'] : "0");
    $pp_lname = test_input(isset($_POST['pp_lname']) ? $_POST['pp_lname'] : "0");
    $pp_fname = test_input(isset($_POST['pp_fname']) ? $_POST['pp_fname'] : "0");
    $pp_street = test_input(isset($_POST['pp_street']) ? $_POST['pp_street'] : "0");
    $pp_pcode = test_input(isset($_POST['pp_pcode']) ? $_POST['pp_pcode'] : "0");
    $pp_city = test_input(isset($_POST['pp_city']) ? $_POST['pp_city'] : "0");
    $pp_email = test_input(isset($_POST['pp_email']) ? $_POST['pp_email'] : "0");
    $pp_phone = test_input(isset($_POST['pp_phone']) ? $_POST['pp_phone'] : "0");
    $pp_tuid = test_input(isset($_POST['pp_tuid']) ? $_POST['pp_tuid'] : "NA");
    $pp_tu_id = test_input(isset($_POST['pp_tu_id']) ? $_POST['pp_tu_id'] : "NA");
    $pp_lichtacc = test_input(isset($_POST['pp_lichtacc']) ? $_POST['pp_lichtacc'] : "0");
    //2. Project Details
    if (empty($_POST["research_area"])) {
        $error['research_area'] = "Choose your Research area";
    } else {
        $research_area = test_input($_POST["research_area"]);
    }
    $research_area_new = test_input(isset($_POST['research_area_new']) ? $_POST['research_area_new'] : "NA");

    if(empty($_POST["proj_enddate"])){
        $error['proj_enddate'] = "Choose project end date";
    }else {
        $proj_enddate = test_input($_POST["proj_enddate"]);

    }
    if(empty($_POST["proj_hours"])){
        $error['proj_hours'] = "Choose an option";
    }else {
        $proj_hours = test_input($_POST["proj_hours"]);

    }

    //Abstract
    $abstract = test_input(isset($_POST['abstract']) ? $_POST['abstract'] : "0");
    //Technical Description- project class
    $proj_class = test_input(isset($_POST['proj_class']) ? $_POST['proj_class'] : "0");
    //Detailed resource requirements
    $cpu_time = test_input(isset($_POST['cpu_time']) ? $_POST['cpu_time'] : "0");
    $acce_nvidia = test_input(isset($_POST['acce_nvidia']) ? $_POST['acce_nvidia'] : "0");
    $acce_xeonphi = test_input(isset($_POST['acce_xeonphi']) ? $_POST['acce_xeonphi'] : "0");
    $mem_pc = test_input(isset($_POST['mem_pc']) ? $_POST['mem_pc'] : "0");
    $home_dir = test_input(isset($_POST['home_dir']) ? $_POST['home_dir'] : "0");
    $work_proj = test_input(isset($_POST['work_proj']) ? $_POST['work_proj'] : "0");
    $work_scratch = test_input(isset($_POST['work_scratch']) ? $_POST['work_scratch'] : "0");
    //resource requirements for a typical batch run
    $req_maxcores = test_input(isset($_POST['req_maxcores']) ? $_POST['req_maxcores'] : "0");
    $req_cputime = test_input(isset($_POST['req_cputime']) ? $_POST['req_cputime'] : "0");
    $req_mmpc = test_input(isset($_POST['req_mmpc']) ? $_POST['req_mmpc'] : "0");
    $req_dspace = test_input(isset($_POST['req_dspace']) ? $_POST['req_dspace'] : "0");
    //Programming languages
    $proglang = 'No language selected';
    if (test_input(isset($_POST['prog_lang']))){
        $_SESSION['prog_lang'] = TRUE;
        $proglang = implode(', ', $_POST['prog_lang']);
    }
    $proglang_other = test_input(isset($_POST['proglang_other']) ? $_POST['proglang_other'] : "0");
    //programming models
    $progmodels ='No model selected';
    if (test_input(isset($_POST['prog_models']))){
        $_SESSION['prog_models'] = TRUE;
        $progmodels = implode(', ', $_POST['prog_models']);
    }
    $progmodel_other = test_input(isset($_POST['progmodel_other']) ? $_POST['progmodel_other'] : "0");
    //tools
    $tools = 'No tools selected';
    if (test_input(isset($_POST['tools']))){
        $_SESSION['tools'] = TRUE;
        $tools = implode(', ', $_POST['tools']);
    }
    $tools_other = test_input(isset($_POST['tools_other']) ? $_POST['tools_other'] : "0");
    //libraries
    $lib = 'No libraries selected';
    if (test_input(isset($_POST['libraries']))){
        $_SESSION['libraries'] = TRUE;
        $lib = implode(', ', $_POST['libraries']);
    }
    //special requirements
    $spl_req = test_input(isset($_POST['spl_req']) ? $_POST['spl_req'] : "0");
    //final agreements
    $agree1 = test_input((isset($_POST['agree1']) && $_POST['agree1']) ? "1" : "0");
    $agree2 = test_input((isset($_POST['agree2']) && $_POST['agree2']) ? "1" : "0");
    $agreefinal = test_input((isset($_POST['agreefinal']) && $_POST['agreefinal']) ? "1" : "0");
    $timestmp = date("Y-m-d H:i:s");

    //checking captcha
    include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
    $securimage = new Securimage();
    if ($securimage->check($_POST['captcha_code']) == false) {
        // the code was incorrect
        // you should handle the error so that the form processor doesn't continue

        // or you can use the following code if there is no validation or you do not know how
//        echo "The security code entered was incorrect.<br /><br />";
//        echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again.";
        $error['captchaerr'] = "captcha entered is either empty or wrong";

    }

    //
    if(empty(array_filter($error))){
        //Generate Error if prepare fails
        if (!$stmt) {
            echo "Prepare failed:" . $mysqli->error;
        }
//Binding all 97 parameters
        if (!$stmt->bind_param("ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssiiiiiiiiiiissssssssiiis", $proj_title, $prop_inst, $prop_state, $dir_title,
            $dir_lname, $dir_fname, $dir_street, $dir_pcode, $dir_city, $dir_phone, $dir_email,
            $pi_title, $pi_lname, $pi_fname, $pi_street, $pi_pcode, $pi_city, $pi_phone, $pi_email, $pm_title, $pm_lname, $pm_fname, $pm_street,
            $pm_pcode, $pm_city, $pm_phone, $pm_fax, $pm_email,$pm_nat, $pm_tuid,$pm_tu_id, $pm_lichtacc, $res1_title, $res1_lname, $res1_fname, $res1_phone,
            $res1_email, $res1_nat, $res1_tuid,$res1_tu_id,$res1_lichtacc, $res2_title,$res2_lname, $res2_fname, $res2_phone, $res2_email, $res2_nat,
            $res2_tuid,$res2_tu_id,$res2_lichtacc, $pp_title,$pp_inst, $pp_lname, $pp_fname, $pp_street,$pp_pcode, $pp_city, $pp_email, $pp_phone, $pp_tuid,
            $pp_tu_id, $pp_lichtacc, $research_area,$research_area_new, $proj_enddate, $proj_hours, $abstract, $proj_class,$cpu_time, $acce_nvidia,
            $acce_xeonphi, $mem_pc, $home_dir,$work_proj, $work_scratch, $req_maxcores, $req_cputime, $req_mmpc, $req_dspace, $proglang, $proglang_other, $progmodels,
            $progmodel_other, $tools, $tools_other, $lib, $spl_req, $agree1, $agree2, $agreefinal,$timestmp)
        ) {
            echo "Binding paramaters failed:(" . $stmt->errno . ")" . $stmt->error;
        }
//Generate Error if statement execution fails
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ")" . $stmt->error;
        }
        $projnr = $stmt->insert_id ;


        //content writing onto text files

        $tempfolder = tempnam($path, time().'-');
        if( file_exists($tempfolder)){
            unlink($tempfolder);
        }
        mkdir($tempfolder,0777,true);//generates a dir with a random name

        file_put_contents($tempfolder ."/proj_title.txt",$proj_title,FILE_APPEND); //file is created inside the random dir with the name specified by admin
        file_put_contents($tempfolder ."/prop_inst.txt",$prop_inst,FILE_APPEND);
        file_put_contents($tempfolder ."/prop_state.txt",$prop_state,FILE_APPEND);
        file_put_contents($tempfolder ."/dir_title.txt",$dir_title,FILE_APPEND);
        file_put_contents($tempfolder ."/dir_lname.txt",$dir_lname,FILE_APPEND);
        file_put_contents($tempfolder ."/dir_fname.txt",$dir_fname,FILE_APPEND);
        file_put_contents($tempfolder ."/dir_street.txt",$dir_street,FILE_APPEND);
        file_put_contents($tempfolder ."/dir_pcode.txt",$dir_pcode,FILE_APPEND);
        file_put_contents($tempfolder ."/dir_city.txt",$dir_city,FILE_APPEND);
        file_put_contents($tempfolder ."/dir_phone.txt",$dir_phone,FILE_APPEND);
        file_put_contents($tempfolder ."/dir_fax.txt",$dir_email,FILE_APPEND);
        file_put_contents($tempfolder ."/pi_title.txt",$pi_title,FILE_APPEND);
        file_put_contents($tempfolder ."/pi_lname.txt",$pi_lname,FILE_APPEND);
        file_put_contents($tempfolder ."/pi_fname.txt",$pi_fname,FILE_APPEND);
        file_put_contents($tempfolder ."/pi_street.txt",$pi_street,FILE_APPEND);
        file_put_contents($tempfolder ."/pi_pcode.txt",$pi_pcode,FILE_APPEND);
        file_put_contents($tempfolder ."/pi_city.txt",$pi_city,FILE_APPEND);
        file_put_contents($tempfolder ."/pi_phone.txt",$pi_phone,FILE_APPEND);
        file_put_contents($tempfolder ."/pi_fax.txt",$pi_email,FILE_APPEND);
        file_put_contents($tempfolder ."/pm_title.txt",$pm_title,FILE_APPEND);
        file_put_contents($tempfolder ."/pm_lname.txt",$pm_lname,FILE_APPEND);
        file_put_contents($tempfolder ."/pm_fname.txt",$pm_fname,FILE_APPEND);
        file_put_contents($tempfolder ."/pm_street.txt",$pm_street,FILE_APPEND);
        file_put_contents($tempfolder ."/pm_pcode.txt",$pm_pcode,FILE_APPEND);
        file_put_contents($tempfolder ."/pm_city.txt",$pm_city,FILE_APPEND);
        file_put_contents($tempfolder ."/pm_phone.txt",$pm_phone,FILE_APPEND);
        file_put_contents($tempfolder ."/pm_fax.txt",$pm_fax,FILE_APPEND);
        file_put_contents($tempfolder ."/pm_email.txt",$pm_email,FILE_APPEND);
        file_put_contents($tempfolder ."/pm_nat.txt",$pm_nat,FILE_APPEND);
        if($pm_tuid === 'yes'){
            file_put_contents($tempfolder ."/pm_tuid.txt",$pm_tu_id,FILE_APPEND);
        }else {
            file_put_contents($tempfolder ."/pm_tuid.txt",$pm_tuid,FILE_APPEND);
        }
        file_put_contents($tempfolder ."/pm_lichtacc.txt",$pm_lichtacc,FILE_APPEND);
        file_put_contents($tempfolder ."/res1_title.txt",$res1_title,FILE_APPEND);
        file_put_contents($tempfolder ."/res1_lname.txt",$res1_lname,FILE_APPEND);
        file_put_contents($tempfolder ."/res1_fname.txt",$res1_fname,FILE_APPEND);
        file_put_contents($tempfolder ."/res1_phone.txt",$res1_phone,FILE_APPEND);
        file_put_contents($tempfolder ."/res1_email.txt",$res1_email,FILE_APPEND);
        file_put_contents($tempfolder ."/res1_nat.txt",$res1_nat,FILE_APPEND);
        if($res1_tuid === 'yes'){
            file_put_contents($tempfolder ."/res1_tuid.txt",$res1_tu_id,FILE_APPEND);
        }else {
            file_put_contents($tempfolder ."/res1_tuid.txt",$res1_tuid,FILE_APPEND);
        }
        file_put_contents($tempfolder ."/res1_lichtacc.txt",$res1_lichtacc,FILE_APPEND);
        file_put_contents($tempfolder ."/res2_title.txt",$res2_title,FILE_APPEND);
        file_put_contents($tempfolder ."/res2_lname.txt",$res2_lname,FILE_APPEND);
        file_put_contents($tempfolder ."/res2_fname.txt",$res2_fname,FILE_APPEND);
        file_put_contents($tempfolder ."/res2_phone.txt",$res2_phone,FILE_APPEND);
        file_put_contents($tempfolder ."/res2_email.txt",$res2_email,FILE_APPEND);
        file_put_contents($tempfolder ."/res2_nat.txt",$res2_nat,FILE_APPEND);
        if($res2_tuid === 'yes'){
            file_put_contents($tempfolder ."/res2_tuid.txt",$res2_tu_id,FILE_APPEND);
        }else {
            file_put_contents($tempfolder ."/res2_tuid.txt",$res2_tuid,FILE_APPEND);
        }
        file_put_contents($tempfolder ."/res2_lichtacc.txt",$res2_lichtacc,FILE_APPEND);
        file_put_contents($tempfolder ."/pp_title.txt",$pp_title,FILE_APPEND);
        file_put_contents($tempfolder ."/pp_inst.txt",$pp_inst,FILE_APPEND);
        file_put_contents($tempfolder ."/pp_lname.txt",$pp_lname,FILE_APPEND);
        file_put_contents($tempfolder ."/pp_fname.txt",$pp_fname,FILE_APPEND);
        file_put_contents($tempfolder ."/pp_street.txt",$pp_street,FILE_APPEND);
        file_put_contents($tempfolder ."/pp_pcode.txt",$pp_pcode,FILE_APPEND);
        file_put_contents($tempfolder ."/pp_city.txt",$pp_city,FILE_APPEND);
        file_put_contents($tempfolder ."/pp_email.txt",$pp_email,FILE_APPEND);
        file_put_contents($tempfolder ."/pp_phone.txt",$pp_phone,FILE_APPEND);
        if($pp_tuid === 'yes'){
            file_put_contents($tempfolder ."/pp_tuid.txt",$pp_tu_id,FILE_APPEND);
        }else {
            file_put_contents($tempfolder ."/pp_tuid.txt",$pp_tuid,FILE_APPEND);
        }
        file_put_contents($tempfolder ."/pp_lichtacc.txt",$pp_lichtacc,FILE_APPEND);
        if($research_area === 'Other'){
            file_put_contents($tempfolder ."/research_area.txt",$research_area_new,FILE_APPEND);
        }else{
            file_put_contents($tempfolder ."/research_area.txt",$research_area,FILE_APPEND);
        }
        file_put_contents($tempfolder ."/proj_enddate.txt",$proj_enddate,FILE_APPEND);
        file_put_contents($tempfolder ."/proj_hours.txt",$proj_hours,FILE_APPEND);
        file_put_contents($tempfolder ."/abstract.txt",$abstract,FILE_APPEND);
        file_put_contents($tempfolder ."/proj_class.txt",$proj_class,FILE_APPEND);
        file_put_contents($tempfolder ."/cpu_time.txt",$cpu_time,FILE_APPEND);
        file_put_contents($tempfolder ."/acce_nvidia.txt",$acce_nvidia,FILE_APPEND);
        file_put_contents($tempfolder ."/acce_xeonphi.txt",$acce_xeonphi,FILE_APPEND);
        file_put_contents($tempfolder ."/mem_pc.txt",$mem_pc,FILE_APPEND);
        file_put_contents($tempfolder ."/home_dir.txt",$home_dir,FILE_APPEND);
        file_put_contents($tempfolder ."/work_proj.txt",$work_proj,FILE_APPEND);
        file_put_contents($tempfolder ."/work_scratch.txt",$work_scratch,FILE_APPEND);
        file_put_contents($tempfolder ."/req_maxcores.txt",$req_maxcores,FILE_APPEND);
        file_put_contents($tempfolder ."/req_cputime.txt",$req_cputime,FILE_APPEND);
        file_put_contents($tempfolder ."/req_mmpc.txt",$req_mmpc,FILE_APPEND);
        file_put_contents($tempfolder ."/req_dspace.txt",$req_dspace,FILE_APPEND);
        if($proglang === 'No language selected'){
            file_put_contents($tempfolder ."/proglang.txt",$proglang_other,FILE_APPEND);
        }else{
            file_put_contents($tempfolder ."/proglang.txt",$proglang,FILE_APPEND);
        }
        if($progmodels === 'No model selected'){
            file_put_contents($tempfolder ."/progmodels.txt",$progmodel_other,FILE_APPEND);
        }else{
            file_put_contents($tempfolder ."/progmodels.txt",$progmodels,FILE_APPEND);
        }
        if($tools === 'No tools selected'){
            file_put_contents($tempfolder ."/tools.txt",$tools_other,FILE_APPEND);
        }else{
            file_put_contents($tempfolder ."/tools.txt",$tools,FILE_APPEND);
        }

        file_put_contents($tempfolder ."/libraries.txt",$lib,FILE_APPEND);

        file_put_contents($tempfolder ."/spl_req.txt",$spl_req,FILE_APPEND);
        if($agree1 ==='1'){
            $agree1_new ="\CheckBox[name=agree1,charsize=12pt,checked]{}";
            file_put_contents($tempfolder ."/agree1.txt",$agree1_new,FILE_APPEND);
        }else{
            $agree1_new = "\CheckBox[name=agree1,charsize=12pt]{}";
            file_put_contents($tempfolder ."/agree1.txt",$agree1_new,FILE_APPEND);
        }
        if($agree2 ==='1'){
            $agree2_new ="\CheckBox[name=agree2,charsize=12pt,checked]{}";
            file_put_contents($tempfolder ."/agree2.txt",$agree2_new,FILE_APPEND);
        }else{
            $agree2_new = "\CheckBox[name=agree2,charsize=12pt]{}";
            file_put_contents($tempfolder ."/agree2.txt",$agree2_new,FILE_APPEND);
        }
        if($agreefinal ==='1'){
            $agreefinal_new ="\CheckBox[name=agreefinal,charsize=12pt,checked]{}";
            file_put_contents($tempfolder ."/agreefinal.txt",$agreefinal_new,FILE_APPEND);
        }else{
            $agreefinal_new = "\CheckBox[name=agreefinal,charsize=12pt]{}";
            file_put_contents($tempfolder ."/agreefinal.txt",$agreefinal_new,FILE_APPEND);
        }
        file_put_contents($tempfolder ."/projnr.txt",$projnr,FILE_APPEND);
        file_put_contents($tempfolder ."/timestamp.txt",$timestmp,FILE_APPEND);
        //shell commands : copies the template.tex to the unique dir
        shell_exec( 'cp "' . $lattemp . '/"* "' . $tempfolder . '"' );
//shell commands : control enters into the unique dir and executes the pdflatex command also renames the template.pdf to your_application_'$projnr'.pdf
        shell_exec('cd "' . $tempfolder . '"; pdflatex --interaction=nonstopmode template; mv template.pdf your_application_'.$projnr.'.pdf');
        $filetomail = file_get_contents($tempfolder.'/'.'your_application_'.$projnr.'.pdf');

        //code for mailing the attachment comes here
        require('./phpmailer/PHPMailerAutoload.php');
        //Create a new PHPMailer instance
        $mail = new PHPMailer;
        //Tell PHPMailer to use SMTP
    $mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
    $mail->SMTPDebug = 0;
//Ask for HTML-friendly debug output
    $mail->Debugoutput = 'html';
//Set the hostname of the mail server
    $mail->Host = 'smtp.gmail.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6
//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = 587;
//Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = 'tls';
//Whether to use SMTP authentication
    $mail->SMTPAuth = true;
//Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = "tarunkumar0191@gmail.com";
//Password to use for SMTP authentication
    $mail->Password = "REMOVED";
//Set who the message is to be sent from
    $mail->setFrom('tarunkumar0191@gmail.com', 'Tarun Kumar');  //from HRZ address
//Set an alternative reply-to address
    $mail->addReplyTo('tarunkumar2@gmail.com', 'Tarun Kumar');
//Set who the message is to be sent to
    $mail->addAddress('tarunkumar1391@outlook.com', 'John Doe');  //recipient address
//Set the subject line
    $mail->Subject = 'PHPMailer GMail SMTP test';  //subject needs to change
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
    $mail->msgHTML(file_get_contents('confirmation.html'), dirname(__FILE__));
//Replace the plain text body with one created manually
    $mail->AltBody = 'This is a plain-text message body';
//Attach an image file
    $mail->addAttachment($tempfolder.'/'.'your_application_'.$projnr.'.pdf');
//send the message, check for errors
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        //echo "<html><body><H3>Thank you for your submission.We will email your application shortly.Kindly review it ,sign it and submit it at our office.</H3><h4>An Email has been delivered now.</h4></body></html>";
        header('Location: thankyou.html');

            //After successfully sending email, delete user submitted data
//        if( is_dir($tempfolder)){
//            rmdir($tempfolder);
//        }
            if (is_dir($tempfolder)) {
                $objects = scandir($tempfolder);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (filetype($tempfolder."/".$object) == "dir") rrmdir($tempfolder."/".$object); else unlink($tempfolder."/".$object);
                    }
                }
                reset($objects);
                rmdir($tempfolder);
            }

        }


    //end of script


    $stmt->close();
    $conn->close();
        session_destroy();
        exit();
} else {
        echo "<script>alert('Please correct the errors');</script>";
    }

}
?>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>HRZ - TU Darmstadt</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/pagedecor.css" rel="stylesheet">
    <script src="js/functions.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container">    <!--Main Container-->
    <div class="row ">  <!--Header Section-->
        <div class="jumbotron custom col-lg-12 col-md-12">
            <div class="logo"><img src="images/hrz_logo.jpg"></div>
            <div class="header_text">
                <h2>Technische Universität Darmstadt</h2>
            </div>
        </div>
    </div> <!--End of Header Section-->
    <div class="row"> <!--Body-->
        <div class="intro col-md-12 col-lg-12">
            <h3>Project Proposal, Computing time on the Lichtenberg Hochleistungsrechner</h3>
            <div class="span12"></div>
        </div>
        <form role="form" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

            <h3>1. Administrative Details</h3>
            <div class="span5"></div>
            <div class="form-group">
                <label class="control-label col-sm-3">Project Title<span class="req">*</span>: </label>
                <div class="col-sm-6 controls">
                    <input type="text" class="form-control input-sm" name="proj_title" id="title" size="200" value="<?php echo $proj_title;?>" maxlength="255" required placeholder="Enter Project Title"><span class="req"><?php echo $error['proj_title'];?></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">University/Institution<span class="req">*</span>:</label>
                <div class="col-sm-6 controls">
                    <input type="text" class="form-control input-sm " size="200" name="prop_inst" value="<?php echo $prop_inst;?>"  placeholder="Name of the University/Institution" ><span class="req"><?php echo $error['prop_inst'];?></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Federal state of the Proposing Institution<span class="req">*</span>:</label>
                <div class="col-sm-3">
                    <select class="form-control" name="prop_state"  id="prop_st"  required>
                        <option value="Hessen" <?php if (isset($prop_state) && $prop_state=="Hessen") echo "selected";?> >Hessen</option><option value="Baden-Württemberg" <?php if (isset($prop_state) && $prop_state=="Baden-Württemberg") echo "selected";?>>Baden-Württemberg</option>
                        <option value="Bayern" <?php if (isset($prop_state) && $prop_state=="Bayern") echo "selected";?>>Bayern</option> <option value="Berlin" <?php if (isset($prop_state) && $prop_state=="Berlin") echo "selected";?>>Berlin</option>
                        <option value="Brandenburg" <?php if (isset($prop_state) && $prop_state=="Brandenburg") echo "selected";?>>Brandenburg</option> <option value="Bremen" <?php if (isset($prop_state) && $prop_state=="Bremen") echo "selected";?>>Bremen</option>
                        <option value="Hamburg" <?php if (isset($prop_state) && $prop_state=="Hamburg") echo "selected";?>>Hamburg</option><option value="Mecklenburg-Vorpommern" <?php if (isset($prop_state) && $prop_state=="Mecklenburg-Vorpommern") echo "selected";?>>Mecklenburg-Vorpommern</option> <option value="Niedersachsen" <?php if (isset($prop_state) && $prop_state=="Niedersachsen") echo "selected";?>>Niedersachsen</option>
                        <option value="Nordrhein-Westfalen" <?php if (isset($prop_state) && $prop_state=="Nordrhein-Westfalen") echo "selected";?>>Nordrhein-Westfalen</option> <option value="Rheinland-Pfalz " <?php if (isset($prop_state) && $prop_state=="Rheinland-Pfalz") echo "selected";?>>Rheinland-Pfalz</option>
                        <option value="Saarland" <?php if (isset($prop_state) && $prop_state=="Saarland") echo "selected";?>>Saarland</option> <option value="Sachsen" <?php if (isset($prop_state) && $prop_state=="Sachsen") echo "selected";?>>Sachsen</option> <option value="Sachsen-Anhalt" <?php if (isset($prop_state) && $prop_state=="Sachsen-Anhalt") echo "selected";?>>Sachsen-Anhalt</option>
                        <option value="Schleswig-Holstein"<?php if (isset($prop_state) && $prop_state=="Schleswig-Holstein") echo "selected";?>>Schleswig-Holstein</option> <option value="Thüringen" <?php if (isset($prop_state) && $prop_state=="Thüringen") echo "selected";?>>Thüringen</option>
                    </select><span class="req"><?php echo $error['prop_state'];?></span>

                </div>
            </div>
            <h4>1.1 Director of the Institute</h4>
            <div class="span5"></div>
            <div class="form-group">
                <label class="control-label col-sm-3">Title<span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <select class="form-control"  name="dir_title"  required>
                        <option value="">-Title-</option><option value="Dr." <?php if (isset($dir_title) && $dir_title=="Dr.") echo "selected";?>>Dr.</option><option value="Prof." <?php if (isset($dir_title) && $dir_title=="Prof.") echo "selected";?>>Prof.</option>
                    </select><span class="req"><?php echo $error['dir_title'];?></span>
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3" >Last Name<span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="dir_lname" placeholder="Last Name" value="<?php echo $dir_lname;?>" required><span class="req"><?php echo $error['dir_lname'];?></span>
                </div>
                <label class="control-label col-sm-2"  >First Name<span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <input type="text"  size="200" class="form-control input-sm" name="dir_fname" placeholder="First Name" value="<?php echo $dir_fname;?>" required><span class="req"><?php echo $error['dir_fname'];?></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Street<span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control input-sm" name="dir_street" placeholder="Street" value="<?php echo $dir_street;?>" required><span class="req"><?php echo $error['dir_street'];?></span>
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Postal code<span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="dir_pcode" placeholder="Postal code" value="<?php echo $dir_pcode;?>" required><span class="req"><?php echo $error['dir_pcode'];?></span>
                </div>
                <label class="control-label col-sm-2">City<span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="dir_city" placeholder="City" value="<?php echo $dir_city;?>" required ><span class="req"><?php echo $error['dir_city'];?></span>
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Phone<span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="dir_phone" placeholder="Phone number" value="<?php echo $dir_phone;?>" required><span class="req"><?php echo $error['dir_phone'];?></span>
                </div>
                <label class="control-label col-sm-2">Email<span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <input type="email" class="form-control input-sm" name="dir_email" placeholder="Email" value="<?php echo $dir_email;?>"><span class="req"><?php echo $error['dir_email'];?></span>
                </div>
            </div>
            <h4>1.2 Principal Investigator (If it is not the director)</h4>
            <div class="span5"></div>
            <div class="form-group">
                <label class="control-label col-sm-3">Title:</label>
                <div class="col-sm-2">
                    <select class="form-control"  name="pi_title" >
                        <option value="">-Title-</option><option value="Dr." <?php if (isset($pi_title) && $pi_title=="Dr.") echo "selected";?>>Dr.</option><option value="Prof." <?php if (isset($pi_title) && $pi_title=="Prof.") echo "selected";?>>Prof.</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3" >Last Name:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pi_lname" placeholder="Last Name" value="<?php echo $pi_lname;?>">
                </div>
                <label class="control-label col-sm-2"  >First Name:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pi_fname" placeholder="First Name" value="<?php echo $pi_fname;?>" >
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Street:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pi_street"  placeholder="Street" value="<?php echo $pi_street;?>" >
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Postal code:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pi_pcode" placeholder="Postal code" value="<?php echo $pi_pcode;?>" >
                </div>
                <label class="control-label col-sm-2">City:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pi_city" placeholder="City" value="<?php echo $pi_city;?>" >
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Phone:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pi_phone" placeholder="Phone number" value="<?php echo $pi_phone;?>">
                </div>
                <label class="control-label col-sm-2">Email:</label>
                <div class="col-sm-2">
                    <input type="email" class="form-control input-sm" name="pi_email"  placeholder="Email" value="<?php echo $pi_email;?>">
                </div>
            </div>
            <h4>1.3 Project Manager/Main researcher</h4>
            <div class="span5"></div>
            <p>The project manager is also responsible for the administrative tasks of the project, e.g. distribution und supervision of the granted logins and resources.</p>
            <div class="form-group">
                <label class="control-label col-sm-3">Title<span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <select class="form-control"  name="pm_title" required>
                        <option value="">-Title-</option><option value="Dr." <?php if (isset($pm_title) && $pm_title=="Dr.") echo "selected";?>>Dr.</option><option value="Prof." <?php if (isset($pm_title) && $pm_title=="Prof.") echo "selected";?>>Prof.</option>
                    </select><span><?php echo $error['pm_title'];?></span>
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Last Name<span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pm_lname" placeholder="Last Name" value="<?php echo $pm_lname;?>" required><?php echo $error['pm_lname'];?></span>
                </div>
                <label class="control-label col-sm-2">First Name<span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pm_fname" placeholder="First Name" value="<?php echo $pm_fname;?>" required><?php echo $error['pm_fname'];?></span>
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Street<span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pm_street" placeholder="Street" value="<?php echo $pm_street;?>" required><?php echo $error['pm_street'];?></span>
                </div>
                <label class="control-label col-sm-2">Postal Code<span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pm_pcode" placeholder="Postal code" value="<?php echo $pm_pcode;?>" required><?php echo $error['pm_pcode'];?></span>
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">City<span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pm_city" placeholder="City" value="<?php echo $pm_city;?>" required><?php echo $error['pm_city'];?></span>
                </div>
                <label class="control-label col-sm-2">Phone<span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pm_phone" placeholder="Phone number" value="<?php echo $pm_phone;?>" required><?php echo $error['pm_phone'];?></span>
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Fax:</label>
                <div class="col-sm-2">
                    <input type="number" class="form-control input-sm" name="pm_fax" placeholder="Fax" value="<?php echo $pm_fax;?>">
                </div>
                <label class="control-label col-sm-2">Email<span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <input type="email" class="form-control input-sm" name="pm_email" placeholder="Valid Email address" value="<?php echo $pm_email;?>" required><?php echo $error['pm_email'];?></span>
                </div>

            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Nationality<span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <select class="form-control" name="pm_nat" required>
                        <option value="">-- select one --</option>
                        <option value="afghan">Afghan</option>
                        <option value="albanian">Albanian</option>
                        <option value="algerian">Algerian</option>
                        <option value="american">American</option>
                        <option value="andorran">Andorran</option>
                        <option value="angolan">Angolan</option>
                        <option value="antiguans">Antiguans</option>
                        <option value="argentinean">Argentinean</option>
                        <option value="armenian">Armenian</option>
                        <option value="australian">Australian</option>
                        <option value="austrian">Austrian</option>
                        <option value="azerbaijani">Azerbaijani</option>
                        <option value="bahamian">Bahamian</option>
                        <option value="bahraini">Bahraini</option>
                        <option value="bangladeshi">Bangladeshi</option>
                        <option value="barbadian">Barbadian</option>
                        <option value="barbudans">Barbudans</option>
                        <option value="batswana">Batswana</option>
                        <option value="belarusian">Belarusian</option>
                        <option value="belgian">Belgian</option>
                        <option value="belizean">Belizean</option>
                        <option value="beninese">Beninese</option>
                        <option value="bhutanese">Bhutanese</option>
                        <option value="bolivian">Bolivian</option>
                        <option value="bosnian">Bosnian</option>
                        <option value="brazilian">Brazilian</option>
                        <option value="british">British</option>
                        <option value="bruneian">Bruneian</option>
                        <option value="bulgarian">Bulgarian</option>
                        <option value="burkinabe">Burkinabe</option>
                        <option value="burmese">Burmese</option>
                        <option value="burundian">Burundian</option>
                        <option value="cambodian">Cambodian</option>
                        <option value="cameroonian">Cameroonian</option>
                        <option value="canadian">Canadian</option>
                        <option value="cape verdean">Cape Verdean</option>
                        <option value="central african">Central African</option>
                        <option value="chadian">Chadian</option>
                        <option value="chilean">Chilean</option>
                        <option value="chinese">Chinese</option>
                        <option value="colombian">Colombian</option>
                        <option value="comoran">Comoran</option>
                        <option value="congolese">Congolese</option>
                        <option value="costa rican">Costa Rican</option>
                        <option value="croatian">Croatian</option>
                        <option value="cuban">Cuban</option>
                        <option value="cypriot">Cypriot</option>
                        <option value="czech">Czech</option>
                        <option value="danish">Danish</option>
                        <option value="djibouti">Djibouti</option>
                        <option value="dominican">Dominican</option>
                        <option value="dutch">Dutch</option>
                        <option value="east timorese">East Timorese</option>
                        <option value="ecuadorean">Ecuadorean</option>
                        <option value="egyptian">Egyptian</option>
                        <option value="emirian">Emirian</option>
                        <option value="equatorial guinean">Equatorial Guinean</option>
                        <option value="eritrean">Eritrean</option>
                        <option value="estonian">Estonian</option>
                        <option value="ethiopian">Ethiopian</option>
                        <option value="fijian">Fijian</option>
                        <option value="filipino">Filipino</option>
                        <option value="finnish">Finnish</option>
                        <option value="french">French</option>
                        <option value="gabonese">Gabonese</option>
                        <option value="gambian">Gambian</option>
                        <option value="georgian">Georgian</option>
                        <option value="german" default>German</option>
                        <option value="ghanaian">Ghanaian</option>
                        <option value="greek">Greek</option>
                        <option value="grenadian">Grenadian</option>
                        <option value="guatemalan">Guatemalan</option>
                        <option value="guinea-bissauan">Guinea-Bissauan</option>
                        <option value="guinean">Guinean</option>
                        <option value="guyanese">Guyanese</option>
                        <option value="haitian">Haitian</option>
                        <option value="herzegovinian">Herzegovinian</option>
                        <option value="honduran">Honduran</option>
                        <option value="hungarian">Hungarian</option>
                        <option value="icelander">Icelander</option>
                        <option value="indian">Indian</option>
                        <option value="indonesian">Indonesian</option>
                        <option value="iranian">Iranian</option>
                        <option value="iraqi">Iraqi</option>
                        <option value="irish">Irish</option>
                        <option value="israeli">Israeli</option>
                        <option value="italian">Italian</option>
                        <option value="ivorian">Ivorian</option>
                        <option value="jamaican">Jamaican</option>
                        <option value="japanese">Japanese</option>
                        <option value="jordanian">Jordanian</option>
                        <option value="kazakhstani">Kazakhstani</option>
                        <option value="kenyan">Kenyan</option>
                        <option value="kittian and nevisian">Kittian and Nevisian</option>
                        <option value="kuwaiti">Kuwaiti</option>
                        <option value="kyrgyz">Kyrgyz</option>
                        <option value="laotian">Laotian</option>
                        <option value="latvian">Latvian</option>
                        <option value="lebanese">Lebanese</option>
                        <option value="liberian">Liberian</option>
                        <option value="libyan">Libyan</option>
                        <option value="liechtensteiner">Liechtensteiner</option>
                        <option value="lithuanian">Lithuanian</option>
                        <option value="luxembourger">Luxembourger</option>
                        <option value="macedonian">Macedonian</option>
                        <option value="malagasy">Malagasy</option>
                        <option value="malawian">Malawian</option>
                        <option value="malaysian">Malaysian</option>
                        <option value="maldivan">Maldivan</option>
                        <option value="malian">Malian</option>
                        <option value="maltese">Maltese</option>
                        <option value="marshallese">Marshallese</option>
                        <option value="mauritanian">Mauritanian</option>
                        <option value="mauritian">Mauritian</option>
                        <option value="mexican">Mexican</option>
                        <option value="micronesian">Micronesian</option>
                        <option value="moldovan">Moldovan</option>
                        <option value="monacan">Monacan</option>
                        <option value="mongolian">Mongolian</option>
                        <option value="moroccan">Moroccan</option>
                        <option value="mosotho">Mosotho</option>
                        <option value="motswana">Motswana</option>
                        <option value="mozambican">Mozambican</option>
                        <option value="namibian">Namibian</option>
                        <option value="nauruan">Nauruan</option>
                        <option value="nepalese">Nepalese</option>
                        <option value="new zealander">New Zealander</option>
                        <option value="ni-vanuatu">Ni-Vanuatu</option>
                        <option value="nicaraguan">Nicaraguan</option>
                        <option value="nigerien">Nigerien</option>
                        <option value="north korean">North Korean</option>
                        <option value="northern irish">Northern Irish</option>
                        <option value="norwegian">Norwegian</option>
                        <option value="omani">Omani</option>
                        <option value="pakistani">Pakistani</option>
                        <option value="palauan">Palauan</option>
                        <option value="panamanian">Panamanian</option>
                        <option value="papua new guinean">Papua New Guinean</option>
                        <option value="paraguayan">Paraguayan</option>
                        <option value="peruvian">Peruvian</option>
                        <option value="polish">Polish</option>
                        <option value="portuguese">Portuguese</option>
                        <option value="qatari">Qatari</option>
                        <option value="romanian">Romanian</option>
                        <option value="russian">Russian</option>
                        <option value="rwandan">Rwandan</option>
                        <option value="saint lucian">Saint Lucian</option>
                        <option value="salvadoran">Salvadoran</option>
                        <option value="samoan">Samoan</option>
                        <option value="san marinese">San Marinese</option>
                        <option value="sao tomean">Sao Tomean</option>
                        <option value="saudi">Saudi</option>
                        <option value="scottish">Scottish</option>
                        <option value="senegalese">Senegalese</option>
                        <option value="serbian">Serbian</option>
                        <option value="seychellois">Seychellois</option>
                        <option value="sierra leonean">Sierra Leonean</option>
                        <option value="singaporean">Singaporean</option>
                        <option value="slovakian">Slovakian</option>
                        <option value="slovenian">Slovenian</option>
                        <option value="solomon islander">Solomon Islander</option>
                        <option value="somali">Somali</option>
                        <option value="south african">South African</option>
                        <option value="south korean">South Korean</option>
                        <option value="spanish">Spanish</option>
                        <option value="sri lankan">Sri Lankan</option>
                        <option value="sudanese">Sudanese</option>
                        <option value="surinamer">Surinamer</option>
                        <option value="swazi">Swazi</option>
                        <option value="swedish">Swedish</option>
                        <option value="swiss">Swiss</option>
                        <option value="syrian">Syrian</option>
                        <option value="taiwanese">Taiwanese</option>
                        <option value="tajik">Tajik</option>
                        <option value="tanzanian">Tanzanian</option>
                        <option value="thai">Thai</option>
                        <option value="togolese">Togolese</option>
                        <option value="tongan">Tongan</option>
                        <option value="trinidadian or tobagonian">Trinidadian or Tobagonian</option>
                        <option value="tunisian">Tunisian</option>
                        <option value="turkish">Turkish</option>
                        <option value="tuvaluan">Tuvaluan</option>
                        <option value="ugandan">Ugandan</option>
                        <option value="ukrainian">Ukrainian</option>
                        <option value="uruguayan">Uruguayan</option>
                        <option value="uzbekistani">Uzbekistani</option>
                        <option value="venezuelan">Venezuelan</option>
                        <option value="vietnamese">Vietnamese</option>
                        <option value="welsh">Welsh</option>
                        <option value="yemenite">Yemenite</option>
                        <option value="zambian">Zambian</option>
                        <option value="zimbabwean">Zimbabwean</option>
                    </select><?php echo $error['pm_nat'];?></span>
                </div>
                <label class="control-label col-sm-2">TU-ID<span class="req">*</span>:</label>
                <label class="radio-inline col-sm-2">
                    <input type="radio" name="pm_tuid" id="yescheck1"  value="yes" <?php if (isset($pm_tuid) && $pm_tuid=="yes") echo "checked";?> onclick="pm();" required>yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"  id="nocheck1" name="pm_tuid" onclick="pm();" value="no" <?php if (isset($pm_tuid) && $pm_tuid=="no") echo "checked";?>  required>No
                </label><?php echo $error['pm_tuid'];?></span>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm-2" name="pm_tu_id" id="pmtuid" value="<?php echo $pm_tu_id;?>" style="display: none;">
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Lichtenberg Account exists?<span class="req">*</span>  </label>
                <label class="radio-inline ">
                    <input type="radio" name="pm_lichtacc" value="yes" <?php if (isset($pm_lichtacc) && $pm_lichtacc=="yes") echo "checked";?> required>yes
                </label>
                <label class="radio-inline ">
                    <input type="radio" name="pm_lichtacc" value="no" <?php if (isset($pm_lichtacc) && $pm_lichtacc=="no") echo "checked";?> required>No
                </label><?php echo $error['pm_lichtacc'];?></span>
            </div>
            <h4>1.4 Additional Researchers</h4>
            <div class="span5"></div>
            <p>All "researchers" will be added to the mailing list hpc-nutzer. Please give your professional e-mail address. E-mail addresses such as Gmail and Hotmail are not accepted</p>
            <div class="form-group">
                <div class="table-responsive">
                    <table class="table custom">
                        <thead>
                        <tr>
                            <th>1</th>
                            <th class="col-md-2">Title</th>
                            <th class="col-md-2">Last name</th>
                            <th class="col-md-2">First name</th>
                            <th class="col-md-2">Phone</th>
                            <th class="col-md-5">Email</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td ></td>
                            <td ><input type="text" size="200" class="form-control input-sm" name="res1_title" placeholder=""></td>
                            <td ><input type="text" size="200" class="form-control input-sm" name="res1_lname" placeholder=""></td>
                            <td ><input type="text" size="200"class="form-control input-sm" name="res1_fname" placeholder=""></td>
                            <td ><input type="text" size="200"class="form-control input-sm" name="res1_phone" placeholder=""></td>
                            <td ><input type="email" class="form-control input-sm" name="res1_email" placeholder=""></td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Nationality (required):</label>
                <div class="col-sm-3">
                    <select class="form-control" name="res1_nat">
                        <option value="">-- select one --</option>
                        <option value="afghan">Afghan</option>
                        <option value="albanian">Albanian</option>
                        <option value="algerian">Algerian</option>
                        <option value="american">American</option>
                        <option value="andorran">Andorran</option>
                        <option value="angolan">Angolan</option>
                        <option value="antiguans">Antiguans</option>
                        <option value="argentinean">Argentinean</option>
                        <option value="armenian">Armenian</option>
                        <option value="australian">Australian</option>
                        <option value="austrian">Austrian</option>
                        <option value="azerbaijani">Azerbaijani</option>
                        <option value="bahamian">Bahamian</option>
                        <option value="bahraini">Bahraini</option>
                        <option value="bangladeshi">Bangladeshi</option>
                        <option value="barbadian">Barbadian</option>
                        <option value="barbudans">Barbudans</option>
                        <option value="batswana">Batswana</option>
                        <option value="belarusian">Belarusian</option>
                        <option value="belgian">Belgian</option>
                        <option value="belizean">Belizean</option>
                        <option value="beninese">Beninese</option>
                        <option value="bhutanese">Bhutanese</option>
                        <option value="bolivian">Bolivian</option>
                        <option value="bosnian">Bosnian</option>
                        <option value="brazilian">Brazilian</option>
                        <option value="british">British</option>
                        <option value="bruneian">Bruneian</option>
                        <option value="bulgarian">Bulgarian</option>
                        <option value="burkinabe">Burkinabe</option>
                        <option value="burmese">Burmese</option>
                        <option value="burundian">Burundian</option>
                        <option value="cambodian">Cambodian</option>
                        <option value="cameroonian">Cameroonian</option>
                        <option value="canadian">Canadian</option>
                        <option value="cape verdean">Cape Verdean</option>
                        <option value="central african">Central African</option>
                        <option value="chadian">Chadian</option>
                        <option value="chilean">Chilean</option>
                        <option value="chinese">Chinese</option>
                        <option value="colombian">Colombian</option>
                        <option value="comoran">Comoran</option>
                        <option value="congolese">Congolese</option>
                        <option value="costa rican">Costa Rican</option>
                        <option value="croatian">Croatian</option>
                        <option value="cuban">Cuban</option>
                        <option value="cypriot">Cypriot</option>
                        <option value="czech">Czech</option>
                        <option value="danish">Danish</option>
                        <option value="djibouti">Djibouti</option>
                        <option value="dominican">Dominican</option>
                        <option value="dutch">Dutch</option>
                        <option value="east timorese">East Timorese</option>
                        <option value="ecuadorean">Ecuadorean</option>
                        <option value="egyptian">Egyptian</option>
                        <option value="emirian">Emirian</option>
                        <option value="equatorial guinean">Equatorial Guinean</option>
                        <option value="eritrean">Eritrean</option>
                        <option value="estonian">Estonian</option>
                        <option value="ethiopian">Ethiopian</option>
                        <option value="fijian">Fijian</option>
                        <option value="filipino">Filipino</option>
                        <option value="finnish">Finnish</option>
                        <option value="french">French</option>
                        <option value="gabonese">Gabonese</option>
                        <option value="gambian">Gambian</option>
                        <option value="georgian">Georgian</option>
                        <option value="german">German</option>
                        <option value="ghanaian">Ghanaian</option>
                        <option value="greek">Greek</option>
                        <option value="grenadian">Grenadian</option>
                        <option value="guatemalan">Guatemalan</option>
                        <option value="guinea-bissauan">Guinea-Bissauan</option>
                        <option value="guinean">Guinean</option>
                        <option value="guyanese">Guyanese</option>
                        <option value="haitian">Haitian</option>
                        <option value="herzegovinian">Herzegovinian</option>
                        <option value="honduran">Honduran</option>
                        <option value="hungarian">Hungarian</option>
                        <option value="icelander">Icelander</option>
                        <option value="indian">Indian</option>
                        <option value="indonesian">Indonesian</option>
                        <option value="iranian">Iranian</option>
                        <option value="iraqi">Iraqi</option>
                        <option value="irish">Irish</option>
                        <option value="israeli">Israeli</option>
                        <option value="italian">Italian</option>
                        <option value="ivorian">Ivorian</option>
                        <option value="jamaican">Jamaican</option>
                        <option value="japanese">Japanese</option>
                        <option value="jordanian">Jordanian</option>
                        <option value="kazakhstani">Kazakhstani</option>
                        <option value="kenyan">Kenyan</option>
                        <option value="kittian and nevisian">Kittian and Nevisian</option>
                        <option value="kuwaiti">Kuwaiti</option>
                        <option value="kyrgyz">Kyrgyz</option>
                        <option value="laotian">Laotian</option>
                        <option value="latvian">Latvian</option>
                        <option value="lebanese">Lebanese</option>
                        <option value="liberian">Liberian</option>
                        <option value="libyan">Libyan</option>
                        <option value="liechtensteiner">Liechtensteiner</option>
                        <option value="lithuanian">Lithuanian</option>
                        <option value="luxembourger">Luxembourger</option>
                        <option value="macedonian">Macedonian</option>
                        <option value="malagasy">Malagasy</option>
                        <option value="malawian">Malawian</option>
                        <option value="malaysian">Malaysian</option>
                        <option value="maldivan">Maldivan</option>
                        <option value="malian">Malian</option>
                        <option value="maltese">Maltese</option>
                        <option value="marshallese">Marshallese</option>
                        <option value="mauritanian">Mauritanian</option>
                        <option value="mauritian">Mauritian</option>
                        <option value="mexican">Mexican</option>
                        <option value="micronesian">Micronesian</option>
                        <option value="moldovan">Moldovan</option>
                        <option value="monacan">Monacan</option>
                        <option value="mongolian">Mongolian</option>
                        <option value="moroccan">Moroccan</option>
                        <option value="mosotho">Mosotho</option>
                        <option value="motswana">Motswana</option>
                        <option value="mozambican">Mozambican</option>
                        <option value="namibian">Namibian</option>
                        <option value="nauruan">Nauruan</option>
                        <option value="nepalese">Nepalese</option>
                        <option value="new zealander">New Zealander</option>
                        <option value="ni-vanuatu">Ni-Vanuatu</option>
                        <option value="nicaraguan">Nicaraguan</option>
                        <option value="nigerien">Nigerien</option>
                        <option value="north korean">North Korean</option>
                        <option value="northern irish">Northern Irish</option>
                        <option value="norwegian">Norwegian</option>
                        <option value="omani">Omani</option>
                        <option value="pakistani">Pakistani</option>
                        <option value="palauan">Palauan</option>
                        <option value="panamanian">Panamanian</option>
                        <option value="papua new guinean">Papua New Guinean</option>
                        <option value="paraguayan">Paraguayan</option>
                        <option value="peruvian">Peruvian</option>
                        <option value="polish">Polish</option>
                        <option value="portuguese">Portuguese</option>
                        <option value="qatari">Qatari</option>
                        <option value="romanian">Romanian</option>
                        <option value="russian">Russian</option>
                        <option value="rwandan">Rwandan</option>
                        <option value="saint lucian">Saint Lucian</option>
                        <option value="salvadoran">Salvadoran</option>
                        <option value="samoan">Samoan</option>
                        <option value="san marinese">San Marinese</option>
                        <option value="sao tomean">Sao Tomean</option>
                        <option value="saudi">Saudi</option>
                        <option value="scottish">Scottish</option>
                        <option value="senegalese">Senegalese</option>
                        <option value="serbian">Serbian</option>
                        <option value="seychellois">Seychellois</option>
                        <option value="sierra leonean">Sierra Leonean</option>
                        <option value="singaporean">Singaporean</option>
                        <option value="slovakian">Slovakian</option>
                        <option value="slovenian">Slovenian</option>
                        <option value="solomon islander">Solomon Islander</option>
                        <option value="somali">Somali</option>
                        <option value="south african">South African</option>
                        <option value="south korean">South Korean</option>
                        <option value="spanish">Spanish</option>
                        <option value="sri lankan">Sri Lankan</option>
                        <option value="sudanese">Sudanese</option>
                        <option value="surinamer">Surinamer</option>
                        <option value="swazi">Swazi</option>
                        <option value="swedish">Swedish</option>
                        <option value="swiss">Swiss</option>
                        <option value="syrian">Syrian</option>
                        <option value="taiwanese">Taiwanese</option>
                        <option value="tajik">Tajik</option>
                        <option value="tanzanian">Tanzanian</option>
                        <option value="thai">Thai</option>
                        <option value="togolese">Togolese</option>
                        <option value="tongan">Tongan</option>
                        <option value="trinidadian or tobagonian">Trinidadian or Tobagonian</option>
                        <option value="tunisian">Tunisian</option>
                        <option value="turkish">Turkish</option>
                        <option value="tuvaluan">Tuvaluan</option>
                        <option value="ugandan">Ugandan</option>
                        <option value="ukrainian">Ukrainian</option>
                        <option value="uruguayan">Uruguayan</option>
                        <option value="uzbekistani">Uzbekistani</option>
                        <option value="venezuelan">Venezuelan</option>
                        <option value="vietnamese">Vietnamese</option>
                        <option value="welsh">Welsh</option>
                        <option value="yemenite">Yemenite</option>
                        <option value="zambian">Zambian</option>
                        <option value="zimbabwean">Zimbabwean</option>
                    </select>
                </div>
                <label class="control-label col-sm-2">TU-ID:</label>
                <label class="radio-inline col-sm-2">
                    <input type="radio" name="res1_tuid" id="yescheck2"  value="yes" onclick="res1();" >yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"  id="nocheck2" name="res1_tuid" onclick="res1();" value="no" >No
                </label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm-2" name="res1_tu_id" id="res1tuid" style="display: none;">
                </div>

            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Lichtenberg Account exists?  </label>
                <label class="radio-inline ">
                    <input type="radio" name="res1_lichtacc" value="yes" >yes
                </label>
                <label class="radio-inline ">
                    <input type="radio" name="res1_lichtacc" value="no" >No
                </label>

            </div>
            <div class="form-group">
                <div class="table-responsive">
                    <table class="table custom">
                        <thead>
                        <tr>
                            <th>2</th>
                            <th class="col-md-1">Title</th>
                            <th class="col-md-3">Last name</th>
                            <th class="col-md-3">First name</th>
                            <th class="col-md-2">Phone</th>
                            <th class="col-md-3">Email</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td ></td>
                            <td ><input type="text" size="200" class="form-control input-sm" name="res2_title" placeholder="Title"></td>
                            <td ><input type="text" size="200" class="form-control input-sm" name="res2_lname" placeholder="Last name"></td>
                            <td ><input type="text" size="200" class="form-control input-sm" name="res2_fname" placeholder=">First name"></td>
                            <td ><input type="text" size="200" class="form-control input-sm" name="res2_phone" placeholder="Phone"></td>
                            <td ><input type="email" class="form-control input-sm" name="res2_email" placeholder="Enter a valid email address"></td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Nationality (required):</label>
                <div class="col-sm-3">
                    <select class="form-control" name="res2_nat">
                        <option value="">-- select one --</option>
                        <option value="afghan">Afghan</option>
                        <option value="albanian">Albanian</option>
                        <option value="algerian">Algerian</option>
                        <option value="american">American</option>
                        <option value="andorran">Andorran</option>
                        <option value="angolan">Angolan</option>
                        <option value="antiguans">Antiguans</option>
                        <option value="argentinean">Argentinean</option>
                        <option value="armenian">Armenian</option>
                        <option value="australian">Australian</option>
                        <option value="austrian">Austrian</option>
                        <option value="azerbaijani">Azerbaijani</option>
                        <option value="bahamian">Bahamian</option>
                        <option value="bahraini">Bahraini</option>
                        <option value="bangladeshi">Bangladeshi</option>
                        <option value="barbadian">Barbadian</option>
                        <option value="barbudans">Barbudans</option>
                        <option value="batswana">Batswana</option>
                        <option value="belarusian">Belarusian</option>
                        <option value="belgian">Belgian</option>
                        <option value="belizean">Belizean</option>
                        <option value="beninese">Beninese</option>
                        <option value="bhutanese">Bhutanese</option>
                        <option value="bolivian">Bolivian</option>
                        <option value="bosnian">Bosnian</option>
                        <option value="brazilian">Brazilian</option>
                        <option value="british">British</option>
                        <option value="bruneian">Bruneian</option>
                        <option value="bulgarian">Bulgarian</option>
                        <option value="burkinabe">Burkinabe</option>
                        <option value="burmese">Burmese</option>
                        <option value="burundian">Burundian</option>
                        <option value="cambodian">Cambodian</option>
                        <option value="cameroonian">Cameroonian</option>
                        <option value="canadian">Canadian</option>
                        <option value="cape verdean">Cape Verdean</option>
                        <option value="central african">Central African</option>
                        <option value="chadian">Chadian</option>
                        <option value="chilean">Chilean</option>
                        <option value="chinese">Chinese</option>
                        <option value="colombian">Colombian</option>
                        <option value="comoran">Comoran</option>
                        <option value="congolese">Congolese</option>
                        <option value="costa rican">Costa Rican</option>
                        <option value="croatian">Croatian</option>
                        <option value="cuban">Cuban</option>
                        <option value="cypriot">Cypriot</option>
                        <option value="czech">Czech</option>
                        <option value="danish">Danish</option>
                        <option value="djibouti">Djibouti</option>
                        <option value="dominican">Dominican</option>
                        <option value="dutch">Dutch</option>
                        <option value="east timorese">East Timorese</option>
                        <option value="ecuadorean">Ecuadorean</option>
                        <option value="egyptian">Egyptian</option>
                        <option value="emirian">Emirian</option>
                        <option value="equatorial guinean">Equatorial Guinean</option>
                        <option value="eritrean">Eritrean</option>
                        <option value="estonian">Estonian</option>
                        <option value="ethiopian">Ethiopian</option>
                        <option value="fijian">Fijian</option>
                        <option value="filipino">Filipino</option>
                        <option value="finnish">Finnish</option>
                        <option value="french">French</option>
                        <option value="gabonese">Gabonese</option>
                        <option value="gambian">Gambian</option>
                        <option value="georgian">Georgian</option>
                        <option value="german">German</option>
                        <option value="ghanaian">Ghanaian</option>
                        <option value="greek">Greek</option>
                        <option value="grenadian">Grenadian</option>
                        <option value="guatemalan">Guatemalan</option>
                        <option value="guinea-bissauan">Guinea-Bissauan</option>
                        <option value="guinean">Guinean</option>
                        <option value="guyanese">Guyanese</option>
                        <option value="haitian">Haitian</option>
                        <option value="herzegovinian">Herzegovinian</option>
                        <option value="honduran">Honduran</option>
                        <option value="hungarian">Hungarian</option>
                        <option value="icelander">Icelander</option>
                        <option value="indian">Indian</option>
                        <option value="indonesian">Indonesian</option>
                        <option value="iranian">Iranian</option>
                        <option value="iraqi">Iraqi</option>
                        <option value="irish">Irish</option>
                        <option value="israeli">Israeli</option>
                        <option value="italian">Italian</option>
                        <option value="ivorian">Ivorian</option>
                        <option value="jamaican">Jamaican</option>
                        <option value="japanese">Japanese</option>
                        <option value="jordanian">Jordanian</option>
                        <option value="kazakhstani">Kazakhstani</option>
                        <option value="kenyan">Kenyan</option>
                        <option value="kittian and nevisian">Kittian and Nevisian</option>
                        <option value="kuwaiti">Kuwaiti</option>
                        <option value="kyrgyz">Kyrgyz</option>
                        <option value="laotian">Laotian</option>
                        <option value="latvian">Latvian</option>
                        <option value="lebanese">Lebanese</option>
                        <option value="liberian">Liberian</option>
                        <option value="libyan">Libyan</option>
                        <option value="liechtensteiner">Liechtensteiner</option>
                        <option value="lithuanian">Lithuanian</option>
                        <option value="luxembourger">Luxembourger</option>
                        <option value="macedonian">Macedonian</option>
                        <option value="malagasy">Malagasy</option>
                        <option value="malawian">Malawian</option>
                        <option value="malaysian">Malaysian</option>
                        <option value="maldivan">Maldivan</option>
                        <option value="malian">Malian</option>
                        <option value="maltese">Maltese</option>
                        <option value="marshallese">Marshallese</option>
                        <option value="mauritanian">Mauritanian</option>
                        <option value="mauritian">Mauritian</option>
                        <option value="mexican">Mexican</option>
                        <option value="micronesian">Micronesian</option>
                        <option value="moldovan">Moldovan</option>
                        <option value="monacan">Monacan</option>
                        <option value="mongolian">Mongolian</option>
                        <option value="moroccan">Moroccan</option>
                        <option value="mosotho">Mosotho</option>
                        <option value="motswana">Motswana</option>
                        <option value="mozambican">Mozambican</option>
                        <option value="namibian">Namibian</option>
                        <option value="nauruan">Nauruan</option>
                        <option value="nepalese">Nepalese</option>
                        <option value="new zealander">New Zealander</option>
                        <option value="ni-vanuatu">Ni-Vanuatu</option>
                        <option value="nicaraguan">Nicaraguan</option>
                        <option value="nigerien">Nigerien</option>
                        <option value="north korean">North Korean</option>
                        <option value="northern irish">Northern Irish</option>
                        <option value="norwegian">Norwegian</option>
                        <option value="omani">Omani</option>
                        <option value="pakistani">Pakistani</option>
                        <option value="palauan">Palauan</option>
                        <option value="panamanian">Panamanian</option>
                        <option value="papua new guinean">Papua New Guinean</option>
                        <option value="paraguayan">Paraguayan</option>
                        <option value="peruvian">Peruvian</option>
                        <option value="polish">Polish</option>
                        <option value="portuguese">Portuguese</option>
                        <option value="qatari">Qatari</option>
                        <option value="romanian">Romanian</option>
                        <option value="russian">Russian</option>
                        <option value="rwandan">Rwandan</option>
                        <option value="saint lucian">Saint Lucian</option>
                        <option value="salvadoran">Salvadoran</option>
                        <option value="samoan">Samoan</option>
                        <option value="san marinese">San Marinese</option>
                        <option value="sao tomean">Sao Tomean</option>
                        <option value="saudi">Saudi</option>
                        <option value="scottish">Scottish</option>
                        <option value="senegalese">Senegalese</option>
                        <option value="serbian">Serbian</option>
                        <option value="seychellois">Seychellois</option>
                        <option value="sierra leonean">Sierra Leonean</option>
                        <option value="singaporean">Singaporean</option>
                        <option value="slovakian">Slovakian</option>
                        <option value="slovenian">Slovenian</option>
                        <option value="solomon islander">Solomon Islander</option>
                        <option value="somali">Somali</option>
                        <option value="south african">South African</option>
                        <option value="south korean">South Korean</option>
                        <option value="spanish">Spanish</option>
                        <option value="sri lankan">Sri Lankan</option>
                        <option value="sudanese">Sudanese</option>
                        <option value="surinamer">Surinamer</option>
                        <option value="swazi">Swazi</option>
                        <option value="swedish">Swedish</option>
                        <option value="swiss">Swiss</option>
                        <option value="syrian">Syrian</option>
                        <option value="taiwanese">Taiwanese</option>
                        <option value="tajik">Tajik</option>
                        <option value="tanzanian">Tanzanian</option>
                        <option value="thai">Thai</option>
                        <option value="togolese">Togolese</option>
                        <option value="tongan">Tongan</option>
                        <option value="trinidadian or tobagonian">Trinidadian or Tobagonian</option>
                        <option value="tunisian">Tunisian</option>
                        <option value="turkish">Turkish</option>
                        <option value="tuvaluan">Tuvaluan</option>
                        <option value="ugandan">Ugandan</option>
                        <option value="ukrainian">Ukrainian</option>
                        <option value="uruguayan">Uruguayan</option>
                        <option value="uzbekistani">Uzbekistani</option>
                        <option value="venezuelan">Venezuelan</option>
                        <option value="vietnamese">Vietnamese</option>
                        <option value="welsh">Welsh</option>
                        <option value="yemenite">Yemenite</option>
                        <option value="zambian">Zambian</option>
                        <option value="zimbabwean">Zimbabwean</option>
                    </select>
                </div>
                <label class="control-label col-sm-2">TU-ID:</label>
                <label class="radio-inline col-sm-2">
                    <input type="radio" name="res2_tuid" id="yescheck3"  value="yes" onclick="res2();" >yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"  id="nocheck3" name="res2_tuid" onclick="res2();" value="no" >No
                </label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm-2" name="res2_tu_id" id="res2tuid" style="display: none;">
                </div>

            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Lichtenberg Account exists?  </label>
                <label class="radio-inline ">
                    <input type="radio" name="res2_lichtacc" value="yes" >yes
                </label>
                <label class="radio-inline ">
                    <input type="radio" name="res2_lichtacc" value="no" >No
                </label>
            </div>
            <p>If the fields above are not sufficient, please send an email to hhlr@hrz.tu-darmstadt.de.</p>
            <h4>1.5 Project Partners</h4>
            <div class="span5"></div>
            <p>If project partners from outside the proposing institute are involved, please list them here. Project partners will not be granted access to the Lichtenberg computer unless you specify them as "Researchers" (in section 1.4), too.</p>
            <div class="form-group row">
                <label class="control-label col-sm-3">Title:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pp_title" placeholder="Title" >
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Name of Institute:</label>
                <div class="col-sm-3">
                    <input type="text" size="200" class="form-control input-sm" name="pp_inst" placeholder="Name of the Institute" >
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Last Name:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pp_lname" placeholder="Last Name" >
                </div>
                <label class="control-label col-sm-2">First Name:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pp_fname" placeholder="First Name" >
                </div>

            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Street:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pp_street" placeholder="Street" >
                </div>
                <label class="control-label col-sm-2">Postal code:</label>
                <div class="col-sm-2">
                    <input type="number" class="form-control input-sm" name="pp_pcode" placeholder="Postal code" >
                </div>

            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">City:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pp_city" placeholder="City" >
                </div>
                <label class="control-label col-sm-2">Email:</label>
                <div class="col-sm-2">
                    <input type="email" class="form-control input-sm" name="pp_email" placeholder="Email" >
                </div>


            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Phone:</label>
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm" name="pp_phone" placeholder="Phone" >
                </div>
                <label class="control-label col-sm-2">TU-ID exists?  </label>
                <label class="radio-inline col-sm-2">
                    <input type="radio" name="pp_tuid" id="yescheck"  value="yes" onclick="pp();" >yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"  id="nocheck" name="pp_tuid" onclick="pp();" value="no" >No
                </label>

                <!--<label class="control-label col-sm-1">TU-ID:  </label>-->
                <div class="col-sm-2">
                    <input type="text" size="200" class="form-control input-sm-2" name="pp_tu_id" id="pptuid" style="display: none;">
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Lichtenberg Account exists?  </label>
                <label class="radio-inline ">
                    <input type="radio" name="pp_lichtacc" value="yes" >yes
                </label>
                <label class="radio-inline ">
                    <input type="radio" name="pp_lichtacc" value="no" >No
                </label>
            </div>

            <p>If the fields above are not sufficient, please send an email to hhlr@hrz.tu-darmstadt.de.</p>
            <h3>2. Project Details</h3>
            <div class="span5"></div>
            <div class="form-group row">
                <label class="control-label col-sm-3">Research area <span class="req">*</span>:</label>
                <div class="col-sm-3">
                    <select class="form-control" name="research_area" id="res_ar" onclick="resarea()" required> <option value="">-- please select --</option>
                        <option value="Astrophysics/Comology" <?php if (isset($research_area) && $research_area=="Astrophysics/Comology") echo "selected";?>>Astrophysics/Cosmology</option>
                        <option value="Biophysics/Biology/Bioinformatics" <?php if (isset($research_area) && $research_area=="Biophysics/Biology/Bioinformatics") echo "selected";?>>Biophysics/Biology/Bioinformatics</option>
                        <option value="Chemistry" <?php if (isset($research_area) && $research_area=="Chemistry") echo "selected";?>>Chemistry</option> <option value="Metorology/Climatology/Oceanography" <?php if (isset($research_area) && $research_area=="Metorology/Climatology/Oceanography") echo "selected";?>>Climatology/Oceanography/Meteorology</option>
                        <option value="Crystallography" <?php if (isset($research_area) && $research_area=="Crystallography") echo "selected";?>>Crystallography/Mineralogy</option> <option value="Computational Fluid Dynamics" <?php if (isset($research_area) && $research_area=="Computational Fluid Dynamics") echo "selected";?>>Computational Fluid Dynamics</option>
                        <option value="Economics" <?php if (isset($research_area) && $research_area=="Economics") echo "selected";?>>Economics</option> <option value="Engineering - Electrical Engineering" <?php if (isset($research_area) && $research_area=="Engineering - Electrical Engineering") echo "selected";?>>Engineering - Electrical Engineering</option>
                        <option value="Engineering - Sturctural Mechanics"<?php if (isset($research_area) && $research_area=="Engineering - Sturctural Mechanics") echo "selected";?>>Engineering - Structural Mechanics</option> <option value="Engineering - others" <?php if (isset($research_area) && $research_area=="Engineering - others") echo "selected";?>>Engineering - others</option>
                        <option value="Enviromental Sciences" <?php if (isset($research_area) && $research_area=="Enviromental Sciences") echo "selected";?>>Enviromental Sciences</option> <option value="Geophysics" <?php if (isset($research_area) && $research_area=="Geophysics") echo "selected";?>>Geophysics</option>
                        <option value="Informatics/Computer Sciences" <?php if (isset($research_area) && $research_area=="Informatics/Computer Sciences") echo "selected";?>>Informatics/Computer Sciences</option> <option value="Material Science" <?php if (isset($research_area) && $research_area=="Material Science") echo "selected";?>>Material Science</option>
                        <option value="Mathematics" <?php if (isset($research_area) && $research_area=="Mathematics") echo "selected";?>>Mathematics</option> <option value="Medicine">Medicine</option> <option value="Physics - Solid State" <?php if (isset($research_area) && $research_area=="Physics - Solid State") echo "selected";?>>Physics - Solid State Physics</option>
                        <option value="Physics - High Energy Physics" <?php if (isset($research_area) && $research_area=="Physics - High Energy Physics") echo "selected";?>>Physics - High Energy Physics</option> <option value="Physics - others" <?php if (isset($research_area) && $research_area=="Physics - others") echo "selected";?>>Physics - others</option>
                        <option value="Plasma Physics" <?php if (isset($research_area) && $research_area=="Plasma Physics") echo "selected";?>>Plasma Physics</option> <option value="Social Sciences" <?php if (isset($research_area) && $research_area=="Social Sciences") echo "selected";?>>Social Sciences</option>
                        <option value="Support/Benchmarking" <?php if (isset($research_area) && $research_area=="Support/Benchmarking") echo "selected";?>>Support/Benchmarking</option> <option value="Other" <?php if (isset($research_area) && $research_area=="Other") echo "selected";?>>Others</option>
                    </select>
                </div>
                <label class="control-label col-sm-3" id="reslabel" style="display: none;">If not in the list, please specify:</label>
                <div class="col-sm-2" id="resareavalue" style="display: none;">
                    <input type="text" size="200" class="form-control input-sm" name="research_area_new" placeholder="Enter a research area" value="<?php echo $research_area_new;?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-3">Estimated end date of the entire project <span class="req">*</span>:</label>
                <div class="col-sm-3">
                    <input type="date" class="form-control input-sm" name="proj_enddate" placeholder="" value="<?php echo $proj_enddate;?>" required>
                </div>
                <label class="control-label col-sm-3">Number of months (max. 12 months per proposal period) <span class="req">*</span>:</label>
                <div class="col-sm-2">
                    <select class="form-control" name="proj_hours"  onkeyup="cputimecompute();" id="months" required>
                        <option value="">--Please Select--</option>
                        <option value="12" <?php if (isset($proj_hours) && $proj_hours=="12") echo "selected";?>>12</option><option value="11" <?php if (isset($proj_hours) && $proj_hours=="11") echo "selected";?>>11</option><option value="10" <?php if (isset($proj_hours) && $proj_hours=="10") echo "selected";?>>10</option><option value="09" <?php if (isset($proj_hours) && $proj_hours=="09") echo "selected";?>>09</option><option value="08" <?php if (isset($proj_hours) && $proj_hours=="08") echo "selected";?>>08</option>
                        <option value="07" <?php if (isset($proj_hours) && $proj_hours=="07") echo "selected";?>>07</option><option value="06" <?php if (isset($proj_hours) && $proj_hours=="06") echo "selected";?>>06</option><option value="05" <?php if (isset($proj_hours) && $proj_hours=="05") echo "selected";?>>05</option><option value="04" <?php if (isset($proj_hours) && $proj_hours=="04") echo "selected";?>>04</option><option value="03" <?php if (isset($proj_hours) && $proj_hours=="03") echo "selected";?>>03</option>
                    </select>
                </div>
            </div>
            <h4>2.1 Abstract</h4>
            <div class="span5"></div>
            <p>The abstract of the project should be written in English, since this text will be published to demonstrate ongoing work on Lichtenberg computer.
                It should consist 800 to 2500 characters. Typically, this abstract will be published by HRZ on the project web pages (see also 5.)</p>
            <div class="form-group">
                <div class="col-xs-12 ">
                    <textarea name="abstract" style="overflow-x: hidden;" onKeyUp="toCount('gBann','uBann','{CHAR} characters remaining',2500);" type="text" id="gBann"  class="form-control" rows="10" minlength="800" maxlength="2500"><?php echo $abstract;?></textarea>
                    <span id="uBann" >2500 characters remaining</span>
                </div>
            </div>
            <h3>3. Technical Description</h3>
            <div class="span5"></div>
            <p>Please have a look at the <a href="http://www.hhlr.tu-darmstadt.de/hhlr/betrieb/hardware_hlr/index.de.jsp" title="hlrb-hardware">hardware overview</a> of the HRZ first, and verify that you really need a machine of this size for your project.
                This CPU time requirement for MIDDLE and LARGE project class must be justified in a detailed project description.</p>
            <p><em>In the following, always count the number of individual cores that your program will need(estimated).</em></p>
            <h4>3.1 Project class</h4>
            <div class="span5"></div>
            <p>For this project the following project class is planned.</p>
            <div class="form-group">
                <label class="radio-inline control-label col-sm-3">
                    <input type="radio" name="proj_class" value="SMALL" <?php if (isset($proj_class) && $proj_class=="SMALL") echo "checked";?> required> SMALL (17,000 core-hours/<strong>month</strong>)
                </label>
                <label class="radio-inline control-label col-sm-4  ">
                    <input type="radio" name="proj_class" value="MIDDLE" <?php if (isset($proj_class) && $proj_class=="MIDDLE") echo "checked";?> required> MIDDLE (17,000-560,000 core-hours/<strong>month</strong>)
                </label>
                <label class="radio-inline control-label col-sm-4">
                    <input type="radio" name="proj_class" value="LARGE" <?php if (isset($proj_class) && $proj_class=="LARGE") echo "checked";?> required> LARGE (560,000-2,040,000 core-hours/<strong>month</strong>)
                </label>
            </div>
            <h4>3.2 Detailed resource requirements of the project</h4>
            <div class="span5"></div>

            <div class="form-group">
                <div class="table-responsive">
                    <table class="table custom">
                        <thead>
                        <tr>

                            <th class="col-sm-2"></th>
                            <th class="col-sm-6"></th>
                            <th class="col-sm-4"></th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td ><label class="control-label ">CPU Time for proposed period:</label></td>
                            <td ><input type="number" class="form-control input-sm" name="cpu_time" id="cpu_time" min="0" onkeyup="cputimecompute();" placeholder="core-hours" value="<?php echo $cpu_time;?>" required>
                                <span class="text-muted"><p id="info"></p></span></td>
                            <td ><p>(This is the total compute time for your project, e.g.:<br> wall clock time for parallel application*number of cores*number of runs + testing + etc.)</p></td>

                        </tr>
                        <tr>
                            <td ><label class="control-label ">Accelerator Type for proposed period:</label></td>
                            <td >
                                <label class="control-label col-sm-3 ">NVIDIA</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control input-sm"  min="0" name="acce_nvidia" placeholder="card-hours" value="<?php echo $acce_nvidia;?>" required>
                                </div>
                                <label class="control-label col-sm-3 ">Xeon-PHI</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control input-sm"  min="0" name="acce_xeonphi" placeholder="card-hours" value="<?php echo $acce_xeonphi;?>" required>
                                </div>
                            </td>
                            <td ><p>(This is the Accelerator Type and Time.)</p></td>

                        </tr>
                        <tr>
                            <td ><label class="control-label">Main memory per core (in GB):</label></td>
                            <td ><input type="number" class="form-control input-sm"  min="0" name="mem_pc" placeholder="GB" value="<?php echo $mem_pc;?>" required></td>
                            <td ></td>

                        </tr>
                        <tr>
                            <td ><label class="control-label">Home (in GB):</label></td>
                            <td ><input type="number" class="form-control input-sm"  min="0" name="home_dir" placeholder="Default 10GB" value="<?php echo $home_dir;?>" required> </td>
                            <td ><p>(Total for home directories. Change this default value only if it is really necessary! This is an expensive resource.
                                    Files are backed up regularly.)</p></td>

                        </tr>
                        <tr>
                            <td ><label class="control-label">Work (in GB):</label></td>
                            <td >
                                <label class="control-label col-sm-3 ">/work/projects</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control input-sm"  min="0" name="work_proj" placeholder="" value="<?php echo $work_proj;?>" required>
                                </div>
                                <label class="control-label col-sm-3 ">/work/scratch</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control input-sm"  min="0" name="work_scratch" placeholder="" value="<?php echo $work_scratch;?>" required>
                                </div>
                            </td>
                            <td ><p>(No Backup)</p></td>


                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
            <h4>3.3 Resource requirements of a typical single batch run</h4>
            <div class="span5"></div>
            <p>For a typical single run (as well as for a typical interactive run), fill in the expected average values in the production phase of your project (in contrast to developping and debugging phases).
                Of course, the numbers can only be estimated. Use section 3.6 (Special Requirements) for extraordinary maximum resource requirements.</p>
            <div class="form-group">
                <div class="table-responsive">
                    <table class="table custom2">
                        <thead >
                        <tr>
                            <th class="col-sm-1"></th>
                            <th class="col-sm-2"></th>
                        </tr>
                        </thead>
                        <tbody >
                        <tr>
                            <td ><label class="control-label">Max. number of cores:</label></td>
                            <td ><input type="number" class="form-control input-sm"  min="0" name="req_maxcores" placeholder="Cores" value="<?php echo $req_maxcores;?>" required> </td>
                        </tr>
                        <tr>
                            <td ><label class="control-label">Run time (wall-clock time):</label></td>
                            <td ><input type="number" class="form-control input-sm"  min="0" name="req_cputime" placeholder="Core-hours" value="<?php echo $req_cputime;?>" required> </td>
                        </tr>
                        <tr>
                            <td ><label class="control-label">main memory/per core:</label></td>
                            <td ><input type="number" class="form-control input-sm"  min="0" name="req_mmpc" placeholder="GByte" value="<?php echo $req_mmpc;?>" required> </td>
                        </tr>
                        <tr>
                            <td ><label class="control-label">disk space:</label></td>
                            <td ><input type="number" class="form-control input-sm"  min="0" name="req_dspace" placeholder="GByte" value="<?php echo $req_dspace;?>" required> </td>
                        </tr>

                        </tbody>
                    </table>

                </div>
            </div>
            <h4>3.4 Software</h4>
            <div class="span5"></div>
            <p>Please check which programming languages, programming models, tools, and libraries you intend to use (multiple checks are possible). You may list other software packages as well. The HRZ will then check if the software is available or can be ported with reasonable effort.</p>
            <label class="control-label">Programming Languages</label>
            <div class="form-group row">
                <div class="checkbox">
                    <label class="control-label col-sm-2"><input type="checkbox" name="prog_lang[]" value="Fortran 77" <?php if(isset($_POST['prog_lang'])) echo "checked='checked'"; ?>> Fortran 77</label>
                    <label class="control-label col-sm-2"><input type="checkbox" name="prog_lang[]" value="Fortran 90/95" <?php if(isset($_POST['prog_lang'])) echo "checked='checked'"; ?>> Fortran 90/95</label>
                    <label class="control-label col-sm-2"><input type="checkbox" name="prog_lang[]" value="Fortran 2003" <?php if(isset($_POST['prog_lang'])) echo "checked='checked'"; ?>> Fortran 2003</label>
                    <label class="control-label col-sm-1"><input type="checkbox" name="prog_lang[]" value="c"> c</label <?php if(isset($_POST['prog_lang'])) echo "checked='checked'"; ?>>
                    <label class="control-label col-sm-1"><input type="checkbox" name="prog_lang[]" value="cplusplus" <?php if(isset($_POST['prog_lang'])) echo "checked='checked'"; ?>> c++</label>
                    <label class="control-label col-sm-1"><input type="checkbox" name="prog_lang[]" value="Java" <?php if(isset($_POST['prog_lang'])) echo "checked='checked'"; ?>> Java</label>
                    <label class="control-label col-sm-1">Others:</label>
                    <div class="col-sm-2">
                        <input type="text" size="200" class="form-control input-sm" name="proglang_other"  placeholder="" value="<?php echo $proglang_other?>">
                    </div>

                </div>
            </div>

            <label class="control-label">Programming models for parallelization</label>
            <div class="form-group row">
                <div class="checkbox">
                    <label class="control-label col-sm-2"><input type="checkbox" name="prog_models[]" value="MPI" <?php if(isset($_POST['prog_models'])) echo "checked='checked'"; ?>> MPI</label>
                    <label class="control-label col-sm-2"><input type="checkbox" name="prog_models[]" value="OpenMP" <?php if(isset($_POST['prog_models'])) echo "checked='checked'"; ?>> OpenMP</label>
                    <label class="control-label col-sm-2"><input type="checkbox" name="prog_models[]" value="SHMEM" <?php if(isset($_POST['prog_models'])) echo "checked='checked'"; ?>> SHMEM</label>
                    <label class="control-label col-sm-2"><input type="checkbox" name="prog_models[]" value="Hybrid(MPI + OpenMP)" <?php if(isset($_POST['prog_models'])) echo "checked='checked'"; ?>> Hybrid(MPI + OpenMP)</label>
                    <label class="control-label col-sm-2" name="progmodels_other" >Others:</label>
                    <div class="col-sm-2">
                        <input type="text" size="200" class="form-control input-sm"  name="progmodel_other" placeholder="" value="<?php echo $progmodel_other ?>">
                    </div>
                </div>
            </div>
            <label class="control-label">Tools</label>
            <div class="form-group row">
                <div class="checkbox">
                    <label class="control-label col-sm-3"><input type="checkbox" name="tools[]" value="Performance Monitor(histx, pfmon, etc.)" <?php if(isset($_POST['tools'])) echo "checked='checked'"; ?>> Performance Monitor(histx,pfmon,etc.)</label>
                    <label class="control-label col-sm-2"><input type="checkbox" name="tools[]" value="Vampir" <?php if(isset($_POST['tools'])) echo "checked='checked'"; ?>> Vampir(Intel Tracing Tools)</label>
                    <label class="control-label col-sm-1"><input type="checkbox" name="tools[]" value="TotalView" <?php if(isset($_POST['tools'])) echo "checked='checked'"; ?>> TotalView</label>
                    <label class="control-label col-sm-1"><input type="checkbox" name="tools[]" value="Vtune" <?php if(isset($_POST['tools'])) echo "checked='checked'"; ?>> Vtune</label>
                    <label class="control-label col-sm-2"><input type="checkbox" name="tools[]" value="Intel Threading Tools" <?php if(isset($_POST['tools'])) echo "checked='checked'"; ?>> Intel Threading Tools</label>
                    <label class="control-label col-sm-1">Others:</label>
                    <div class="col-sm-2">
                        <input type="text" size="200" class="form-control input-sm" name="tools_other" placeholder="" value="<?php echo $tools_other ?>" >
                    </div>
                </div>
            </div>
            <label class="control-label">Libraries</label>
            <div class="form-group row">
                <div class="checkbox">
                    <label class="control-label col-sm-2"><input type="checkbox" name="libraries[]" value="MKL" <?php if(isset($_POST['libraries'])) echo "checked='checked'"; ?>> MKL Intel Math Kernel Library </label>
                    <!--<label class="control-label col-sm-2"><input type="checkbox" name="libraries[]" value="LAPACK"> LAPACK</label>-->
                    <label class="control-label col-sm-2"><input type="checkbox" name="libraries[]" value="ScalAPACK" <?php if(isset($_POST['libraries'])) echo "checked='checked'"; ?>> ScalAPACK</label>
                    <label class="control-label col-sm-2"><input type="checkbox" name="libraries[]" value="NAG" <?php if(isset($_POST['libraries'])) echo "checked='checked'"; ?>> NAG</label>
                    <label class="control-label col-sm-2"><input type="checkbox" name="libraries[]" value="PETSC" <?php if(isset($_POST['libraries'])) echo "checked='checked'"; ?>> PETSC</label>
                    <label class="control-label col-sm-2"><input type="checkbox" name="libraries[]" value="FFTW" <?php if(isset($_POST['libraries'])) echo "checked='checked'"; ?>> FFTW</label>
                </div>
            </div>
            <h4>3.5 Test Accounts</h4>
            <div class="span5"></div>
            <p>The steering committee grants SMALL project accounts with restricted resources before  MIDDLE and LARGE projects are finally reviewed. Those test accounts are granted for any project proposal that comes with a sufficiently explanatory abstract and/or detailed description.</p>
            <p>However, the risk of useless work in case the project is rejected remains with the project proposer.</p>
            <p>The duration of a test project is at most one year.</p>
            <h4>3.6 Special Requirements</h4>
            <div class="span5"></div>
            <p>In case you have special requirements for your project (time-critical execution of the project, software, licenses etc.), you may list them here:</p>
            <div class="form-group">
                <div class="col-xs-12 ">
                    <textarea  class="form-control" rows="10" name="spl_req"><?php echo $spl_req; ?></textarea>
                </div>
            </div>
            <h3>4.Submission</h3>
            <div class="span5"></div>
            <p>Please check the following conditions affirming that any person entered in 1.1, 1.2, 1.3 and 1.4 or being added later to the project</p>
            <div class="form-group">
                <div class="table-responsive">
                    <table class="table custom">
                        <thead>
                        <tr>
                            <th></th>
                            <th class=""></th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td ><input type="checkbox" name="agree1" required <?php if(isset($_POST['agree1'])) echo "checked='checked'"; ?>></td>
                            <td >will report on the progress of the project and publish the results in adequate form, see regulations and hints on status reports (In case of substantiated interest, the proposer may be released from this obligation),</td>

                        </tr>
                        <tr>
                            <td><input type="checkbox" name="agree2" required <?php if(isset($_POST['agree2'])) echo "checked='checked'"; ?>></td></td>
                            <td>Confirms that, publications arising from this project, the computing time grant from Lichtenberg cluster will be acknowledged and that references to these publications
                                will be sent to HRZ.</td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="form-group">
                <div class="table-responsive">
                    <table class="table custom">
                        <thead>
                        <tr>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td ><input type="checkbox" name="agreefinal" required <?php if(isset($_POST['agreefinal'])) echo "checked='checked'"; ?>></td>
                            <td >I have verified that the results which will be achieved by the project are not liable to any EC Dual Use Regulation.</td>

                        </tr>
                        </tbody>
                    </table>

                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4"><img id="captcha" src="../securimage/securimage_show.php" alt="CAPTCHA Image" /></label>
                    <div class="col-sm-3">
                        <input type="text" name="captcha_code" class="form-control input-sm" size="10" maxlength="6" /><a href="#" onclick="document.getElementById('captcha').src = '../securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>
                        <p><span class="req"><?php echo $error['captchaerr'];?></span></p>
                    </div>
                </div>
            </div>
            <p><strong>Please note: Unless you accept all  conditions above, your new proposal won't be posted!</strong><br> Please contact HRZ if you have reasons not to accept all points.</p>
            <h3>5. Signature</h3>
            <div class="span5"></div>
            <p>After pressing the "Submit data to HRZ" button you will receive a pdf of this proposal.Please print it, sign it (responsible Principal Investigator) and send this signed document to <br>
            <address>HPC, HRZ, Mornewegestraße 30, 64287 Darmstadt</address></p>
            <div class="form-group">
                <div class=" col-sm-10">
                    <button type="submit" name="submitform" class="btn btn-primary">Submit data to HRZ</button>

                </div>
            </div>
            <hr>
            <p>Don't hit this button unwillingly: <input type="reset" value="Reset all form entries"><br> It cancels all data entries that you have typed in so far!</p>




        </form>
    </div>

</div> <!--End of Main Container-->
<script>
    pp();
    pm();
    res1();
    res2();
    cputimecompute();
    resarea();
</script>
</body>

</html>
