<?php

function gen_html($images,$names,$owner,$followers){
    $html = '
    <div class="viber_wrapper">
        <div class="viber_header">
            <img src="'.$images[0].'" alt="" class="viber_owner_avatar">
            <p class="viber_owner_name">'.ucfirst($owner).'</p>
            <p class="viber_followers">'.$followers.'</p>
        </div>
        <div class="viber_body">
            <p class="viber_part">Participants</p>
            <hr class="viber_hr">
                <ul>
                    <li><img src="'.$images[2].'" alt="" class="viber_part_avatar"><p>'.$names[0].'</p></li>
                    <li><img src="'.$images[4].'" alt="" class="viber_part_avatar"><p>'.$names[1].'</p></li>
                    <li><img src="'.$images[6].'" alt="" class="viber_part_avatar"><p>'.$names[2].'</p></li>
                    <li><img src="'.$images[8].'" alt="" class="viber_part_avatar"><p>'.$names[3].'</p></li>
                </ul>
            <a href="http://chats.viber.com/'.$owner.'" class="viber_follow" target="blank"><img src="'.plugin_dir_url(__FILE__).'viber-logo.png" class="viber_logo_phone">Follow</a>
        </div>
    </div>
    ';

    return $html;
}

function viber_my_scripts_method(){
    wp_register_style('viber_widget_style', plugin_dir_url(__FILE__).'viber_widget_style.css');
    wp_enqueue_style('viber_widget_style');
}

function get_viber($owner){
    $conn = curl_init();
    if($conn != false) {
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($conn, CURLOPT_CONNECTTIMEOUT, 5);
        $url = 'http://chats.viber.com/'.$owner;
        curl_setopt($conn, CURLOPT_URL, $url);
        $res = curl_exec($conn);
        if($res!=false){
            curl_close($conn);
            $doc = new DOMDocument();
            @$doc->loadHTML($res);
            $tags = $doc->getElementsByTagName('img');
            $images=array();
            $i = 0;
            foreach ($tags as $tag) {
                $images[$i] = $tag->getAttribute('src');
                //echo $images[$i].'<br>';
                $i++;
            }
            $xpath = new DomXpath($doc);
            $classname = 'participant-name';
            $par_names = array();
            $results = $xpath->query("//*[@class and contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

            if ($results->length > 0) {
                $par_names[0] = $results->item(0)->nodeValue;
                if(strlen($par_names[0])>15){$par_names[0]=substr($par_names[0],0,12).'...';}
                $par_names[1] = $results->item(1)->nodeValue;
                if(strlen($par_names[1])>15){$par_names[1]=substr($par_names[1],0,12).'...';}
                $par_names[2] = $results->item(2)->nodeValue;
                if(strlen($par_names[2])>15){$par_names[2]=substr($par_names[2],0,12).'...';}
                $par_names[3] = $results->item(3)->nodeValue;
                if(strlen($par_names[3])>15){$par_names[3]=substr($par_names[3],0,12).'...';}
            }

            $classname2 = 'followers';
            $results2 = $xpath->query("//*[@class and contains(concat(' ', normalize-space(@class), ' '), ' $classname2 ')]");
            if($results2->length > 0){
                $followers = $results2->item(0)->nodeValue;
            }

            $html = gen_html($images,$par_names,$owner,$followers);
            return $html;

        }else return '<h2>Content not available!</h2>';
    }
}

?>