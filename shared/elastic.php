<?php

class Elastic
{
    public static function bulk( $json )
    {            
        $sets = array();

        foreach($json as $doc){

            $params = array(
                '_id' => $doc['ID'],
                '_index' => ES_INDEX,
                '_type' =>  ES_TYPE
            );  

            $set = array(
                array('index' => $params),
                $doc
            );
            $sets[] = $set;
        }

        foreach($sets as $set){
            foreach ($set as $s) {
                $rows[] = json_encode($s);
            }
        }

        //armo lotes de a 100
        $send = array();        
        $count = 0;
        foreach($rows as $row){
            $send[] = $row;
            $count ++;

            if ($count === 100){
                $body =  join("\n", $send) . "\n";
                Elastic::curl($body);
                $send = array();
                $count = 0;
            }            
        }

        if ($count > 0){
            $body =  join("\n", $send) . "\n";
            Elastic::curl($body);            
        }
    }

    public static function curl( $body ) {
        $conn = curl_init();
        $requestURL = ES_HOST .'/'. ES_INDEX .'/'. ES_TYPE .'/_bulk';
        curl_setopt($conn, CURLOPT_URL, $requestURL);
        curl_setopt($conn, CURLOPT_TIMEOUT, 5);
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($conn, CURLOPT_FAILONERROR, FALSE);
        curl_setopt($conn, CURLOPT_CUSTOMREQUEST, strtoupper('POST'));
        curl_setopt($conn, CURLOPT_FORBID_REUSE, 0);

        if (is_array($body) && count($body) > 0) {
            curl_setopt($conn, CURLOPT_POSTFIELDS, json_encode($body));
        } else {
            curl_setopt($conn, CURLOPT_POSTFIELDS, $body);
        }

        $response = curl_exec($conn);
        echo $response;
    }
}
?>