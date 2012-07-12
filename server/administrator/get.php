<?php
include "./common.php";

ob_start();
$response = array();

if ($_GET['get'] == 'kinopoisk_info' || $_GET['get'] == 'kinopoisk_rating' || $_GET['get'] == 'kinopoisk_info_by_id'){

    try{
        if ($_GET['get'] == 'kinopoisk_info'){
            $response['result'] = Kinopoisk::getInfoByName($_GET['oname']);
        }else if ($_GET['get'] == 'kinopoisk_rating'){
            $response['result'] = Kinopoisk::getRatingByName($_GET['oname']);
        }else if ($_GET['get'] == 'kinopoisk_info_by_id'){
            $response['result'] = Kinopoisk::getInfoById($_GET['kinopoisk_id']);
        }
    }catch (KinopoiskException $e){
        echo $e->getMessage();

        $logger = new Logger();
        $logger->setPrefix("kinopoisk_");

        // format: [date] - error_message - [base64 encoded response];
        $logger->error(sprintf("[%s] - %s - \"%s\"\n",
            date("r"),
            $e->getMessage(),
            base64_encode($e->getResponse())
        ));
    }
}elseif ($_GET['get'] == 'tv_services'){
    $response['result'] = Itv::getServices();
}elseif ($_GET['get'] == 'video_services'){
    $response['result'] = Video::getServices();
}elseif ($_GET['get'] == 'radio_services'){
    $response['result'] = Radio::getServices();
}elseif ($_GET['get'] == 'module_services'){
    $response['result'] = Module::getServices();
}

$output = ob_get_contents();
ob_end_clean();

if ($output){
    $response['output'] = $output;
}

echo json_encode($response);
