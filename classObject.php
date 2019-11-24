<?php
    
class GetObject {
    public $key = "";
    public $page = 1;
    public $str = "";
   function getKeyWord()
   { 
    error_reporting(0); // tắt hiện lỗi =)) 
    if (!is_null($_GET["key"]) && !empty($_GET["key"])) {
        $this->key = $_GET["key"];
        // if (!is_null($_GET["page"]) && !empty($_GET["page"])) {
        //     $this->page = $_GET["page"];
        // }
    } else {
        header("Location: ./index.html");
        die();
    }
   }

   function getSongName()
   {
    $resultKey = $this->key;

    echo $resultKey;
   }

   function getListSong()
   {
    $resultKey = $this->key;
    $this->str = "";
    if (!empty($this->key)) {
        $this->str = file_get_contents("https://www.nhaccuatui.com/tim-kiem/bai-hat?q=" . urlencode($this->key) . "&b=keyword&l=tat-ca&s=default&page=1");
        $this->str = trim(preg_replace('/\s+/', ' ', $this->str)); // supports line breaks
        $this->str = trim(preg_replace('/\n+/', ' ', $this->str)); // supports line breaks
        // if (preg_match("/<span>(\(Có [0-9,]+ kết quả\))<\/span>/i", $this->str, $count)) // ignore case
        // {
        //     $resultKey = $resultKey . " " . $count[1];
        // }
    }
   }

   function getDetailSong()
   {

    $stt = 1;
    $gottedLinks = [];
    if (!empty($this->str)) {
        if (preg_match_all("/<li class=\"sn_search_single_song\">.*?<\/li>/m", $this->str, $matches, PREG_SET_ORDER, 0)) {
            foreach ($matches as $li) {
                $link = "";
                if (preg_match("/href=\"(.*?)\"/i", $li[0], $got)) {
                    $link = $got[1];
                }
                $title = "";
                if (preg_match("/title=\"(.+?)\"/i", $li[0], $got)) {
                    $title = $got[1];
                }
                $singer = "";
                if (preg_match("/<h4 class=\"singer_song\">(.*)<\/h4>/i", $li[0], $got)) {
                    $singer = $got[1];
                    $singer = preg_replace("/<a.+?>|<\/a>/", '', $singer);
                }
                $img = "";
                $mp3 = "";
                // Lấy thực tế
                $this->str = file_get_contents($link);
                $this->str = trim(preg_replace('/\s+/', ' ', $this->str)); // supports line breaks
                if (preg_match("/(https:\/\/www\.nhaccuatui\.com\/flash\/xml\?html5=true&key1=.*?)\";/i", $this->str, $got)) {
                    $this->str = file_get_contents($got[1]);
                    $this->str = trim(preg_replace('/\s+/', ' ', $this->str)); // supports line breaks
                    //                                echo "<input type=\"text\" value=\"".$this->str."\"/>";
                    if (preg_match("/<location>.?<!\[CDATA\[(.*?)]]>.?<\/location>.*<avatar>.?<!\[CDATA\[(.*?)]]>.?<\/avatar>/i", $this->str, $got)) {
                        if (!is_null($got[1]) && !empty($got[1]))
                            $mp3 = $got[1];
                        if (!is_null($got[2]) && !empty($got[2]))
                            $img = $got[2];
                        else
                            $img = "./img/me.png"; // Ảnh của NGHĨA >>>>>>>>>>>>>>>>>>>>>>>>>>>>
                    } else { }
                }

                // Combine data
                if (!empty($title) && !empty($singer) && !empty($img) && !empty($mp3) && !in_array($link, $gottedLinks)) {
                    echo "<tr>";
                    echo "<td>" . $stt++ . "</td>";
                    echo "<td> <img class=\"imagesong\" src=" . $img . ">";
                    echo "</td>";
                    echo "<td class=\"font-center\">" . $singer . "</td>";
                    echo "<td>" . $title . "</td>";
                    echo "<td> <a class=\"btn btn-lg px-3 btn-info\" href=\"#\" role=\"button\">Chia sẻ <img src=\"./images/share.png\" height=\"30\" alt=\"share button\"></a>";
                    echo "</td>";
                    echo "<td> <a class=\"btn btn-lg px-3 btn-info\" href=" . $mp3 . " role=\"button\">Download <img src=\"./images/download-arrow.png\" height=\"30\" alt=\"share button\"></a>";
                    echo "</td>";
                    echo "</tr>";
                    array_push($gottedLinks, $link);
                }
            }
        }
    }
   }


}

