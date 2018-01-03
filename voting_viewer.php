<?php

class voting{
    const HOST = "localhost";
    const USERNAME = "root";
    const  PASSWD = "autoset";
    const DBNAME = "ycj_test";
    const TABLENAME = "vote_info";
    private $DBCON;
    function __construct()
    {
        $this -> DBCON = new mysqli(self::HOST,self::USERNAME,self::PASSWD,self::DBNAME);
    }
    function voting_save($vote){
        /*$name = array("김대권", "정야망", "강희망");
        for ($a = 0; $a < 3; $a ++) {
            //$query = "insert into ".self::TABLENAME." (candidate_name,candidate_vote) values ('$name[$a]', 0)";
            $resert = $this -> DBCON -> query($query);
        }*/
        $query = "select * from ".self::TABLENAME;
        $resert = $this -> DBCON -> query($query);
        $count = 0;
        $countDefort = 0;
        $votenum[] = 0;
        $whole = 0;//백분율 전체값
        while ($row = $resert -> fetch_array()){
            $count;
            if($row['candidate_name'] == $vote){
                $countDefort = $row['candidate_vote'];
                $countDefort++;//투표수 증가
                $votenum[$count] = $countDefort;
                $whole += $votenum[$count];
            }else {
                $votenum[$count] = $row['candidate_vote'];
                $whole += $votenum[$count];
            }
            $count++;
        }
        $percentage[] = 0;
        $percentage300[] = 0;
        for ($a = 0;$a < 3;$a++ ){
            $percentage[$a] = $votenum[$a]/$whole*100;//백분율 계산
            $percentage[$a] = round($percentage[$a]);//반올림 한 값
            $percentage300[$a] = $votenum[$a]/$whole*300;

        }
        $query = "update ".self::TABLENAME." set candidate_vote = $countDefort where candidate_name = '$vote'";
        $resert = $this -> DBCON -> query($query);

        $height = 190;
        $width = 400;

        //캠버스
        $im = imagecreatetruecolor($width, $height);
        //색지정
        $red = imagecolorallocate($im,255,0,0);
        $blue = imagecolorallocate($im,0, 0, 255);
        $green = imagecolorallocate($im,0, 255, 0);
        $white = imagecolorallocate($im,255,255,255);
        $black = imagecolorallocate($im,0, 0, 0);

        for ($i =0 ;$i < 3; $i++){
            $X2_1[$i] = 80+$percentage300[$i];
            $X2_2[$i] = 90+$percentage300[$i];
        }

        //캠버스색
        imageFill($im,0,0,$white);
        imagefilledrectangle($im, 80,30,$X2_1[0],60, $red);
        imagestring($im, 30, $X2_2[0], 40, "$percentage[0]%", $black);
        //imagestring($im, 30, $X2_2[0]/2, 40, "$percentage[0]표", $black);
        imagettftext($im,11,0,$X2_2[0]/2+20,51, $black,'H2GTRM.ttf',"$votenum[0]표");
        imagettftext($im,11,0,20,51, $black,'H2GTRM.ttf',"김대권");

        imagefilledrectangle($im,80,80,$X2_1[1],110, $blue);
        imagestring($im, 30, $X2_2[1], 90, "$percentage[1]%", $black);
        imagettftext($im,11,0,$X2_2[1]/2+20,101, $black,'H2GTRM.ttf',"$votenum[1]표");
        imagettftext($im,11,0,20,101, $black,'H2GTRM.ttf',"정야망");

        imagefilledrectangle($im,80,130,$X2_1[2],160, $green);
        imagestring($im, 30, $X2_2[2], 140, "$percentage[2]%", $black);
        imagettftext($im,11,0,$X2_2[2]/2+20,151, $black,'H2GTRM.ttf',"$votenum[2]표");
        imagettftext($im,11,0,20,151, $black,'H2GTRM.ttf',"강희망");

        Header('Content-type: image/png');
        imagepng($im);

        imagedestroy($im);
    }


}
$vote = $_GET['voting'];
$obj = new voting();
$obj -> voting_save($vote);
?>