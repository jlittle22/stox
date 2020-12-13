<?php
    $curl = curl_init();

    curl_setopt_array($curl, [
	    CURLOPT_URL => "http://newsapi.org/v2/top-headlines?country=us&category=business&apiKey=60431960b82d40a5a0477fd57c1b156e",
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_FOLLOWLOCATION => true,
	    CURLOPT_ENCODING => "",
	    CURLOPT_MAXREDIRS => 10,
	    CURLOPT_TIMEOUT => 30,
	    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	    CURLOPT_CUSTOMREQUEST => "GET",
    ]);

    $response = curl_exec($curl);

    curl_close($curl);
    $response = json_decode($response);

    foreach ($response->{"articles"} as $article_obj) {
        $source = $article_obj->{"source"}->{"name"};
        $title = $article_obj->{"title"};
        if (strlen($title) > 90) {
        	$title = substr($title, 0, 85) . " ... ";
        }
        $desc = $article_obj->{"description"};
        $url = $article_obj->{"url"};

        $html = "<div 
                    class='news_res' 
                    onclick='window.open(\"$url\",\"mywindow\");'' 
                    style='cursor: pointer;'
                >
                    <div class='news_source'>
                        <p class='news_title'>$title<p>
                        <p class='news_comp'>$source</p>
                    </div>
                    <div class='news_desc'>
                        <p class='news_text'>$desc</p>
                    </div>
                </div>";
        echo $html;
    }


?>