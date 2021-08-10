<?php 
/**
 * Author: Ahmad Raza
 * Description: Recieves all http requests
 */
require('./core/db.php');

try {
    if(!empty($_GET['route']) && $_GET['route'] == 'fetch_sessions') {

        $db = DB::getInstance();

        $searchTerm = !empty($_GET['search_term']) ? $_GET['search_term'] : null; 
        
        $sessions = $db->fetchSessions($searchTerm);

        header('Content-type: application/json');
        http_response_code(200);
        echo json_encode($sessions);

    } else {
        throw new Exception('401 Forbidden');
    }

    
} catch(Exception $e) {
    header('Content-type: application/json');
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
