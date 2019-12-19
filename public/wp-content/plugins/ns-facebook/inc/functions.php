<?php
function nsfb_get_facebook() {
	if (!is_front_page()) {
		return;
    }
    $numberofposts = get_option('nsfb_number_fbposts');
    $fbtoken = get_option('nsfb_token');
    $showmessage = get_option('nsfb_show_message');
    $showpicture = get_option('nsfb_show_picture');
    $showauthor = get_option('nsfb_show_author');
    $showdate = get_option('nsfb_show_date');
    $result = wp_remote_get("https://graph.facebook.com/v5.0/me?fields=name%2Cposts.limit(" . $numberofposts . ")%7Bmessage%2Cpicture%2Cfrom%2Ccreated_time%7D&access_token=" . $fbtoken . "");
    $newresult = explode(',', $result['body']);
    foreach ($newresult as $e) {
        if($showmessage === "1"){
            if(strpos($e, '"message') !== false){
                $messagestageone = $e;
                $messagestagetwo = explode('"message":"', $messagestageone);
                $messagestagethree = explode('"', $messagestagetwo[1]);
                $replacedmessagestagethree = str_replace("\\","", $messagestagethree);
                $newreplacedmessagestagethree = explode(' ', $replacedmessagestagethree[0]);
                foreach($newreplacedmessagestagethree as $stringmessage){
                    if(substr($stringmessage, 0, 8) === "https://" || substr($stringmessage, 0, 7) === "http://"){
                        $stringmessage = "<a href='{$stringmessage}'>" . $stringmessage . "</a> ";
                        echo $stringmessage;
                    }else{
                        echo $stringmessage . " ";
                    }
                }
            }
        }
        
        if($showpicture === "1"){
            if(strpos($e, '"picture') !== false){
                $srcstageone = $e;
                $srcstagetwo = explode('"picture":"', $srcstageone);
                $srcstagethree = explode('"', $srcstagetwo[1]);
                $replacedsrcstagethree = str_replace("\\","", $srcstagethree);
                echo '<img src="' . $replacedsrcstagethree[0] . '" width="200px" height="100px"><br>';
            }
        }
        if($showauthor === "1"){
            if(strpos($e, '"from":{"name') !== false){
                $fromstageone = $e;
                $fromstagetwo = explode('"from":{"name', $fromstageone);
                $fromstagethree = explode('"', $fromstagetwo[1]);
                echo '<p>-' . $fromstagethree[2] . '</p>';
            }
        }
        if($showdate === "1"){
            if(strpos($e, '"created_time') !== false){
                $timestageone = $e;
                $timestagetwo = explode('"created_time":"', $timestageone);
                $timestagethree = explode('"', $timestagetwo[1]);
                $timestagefour = explode('T', $timestagethree[0]);
                echo '<p>' . $timestagefour[0] . '</p>';
            }
        }
    }
}