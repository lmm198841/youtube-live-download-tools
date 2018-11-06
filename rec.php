<?php
            /*Settings*/
                $youtube_channel_id     =       'KAYIT_KANAL_ID';
                $youtube_api_key        =       'YOUTUBE_APİ_KEY';
                $recordingfolder        =       '/var/www/html/rec/';
                $record_time            =       '00:16:00';
                $headers                =       '\"User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36\"';
            /*Settings*/

            /*Youtube ID*/
                $vurl			        =       "https://www.googleapis.com/youtube/v3/search?eventType=live&part=snippet&channelId=$youtube_channel_id&type=video&key=$youtube_api_key&maxResults=1";
                $json 			        =       file_get_contents($vurl,0,null,null);
                $json_output 		    =       json_decode($json);
                foreach ($json_output->items as $data){ $findId = $data->id->videoId; }
            /*Youtube ID*/

                $folder                 =       $recordingfolder.date('F-d-m-Y',strtotime('- 1 hour -52 minute'));
                if (file_exists($folder)){ }else{ mkdir($folder ,0777 );  echo "Klasör olusturuldu.";
                }

                $date          		    =   	date('d.m.Y-H-i-s',strtotime('- 1 hour -52 minute'));
                $youtube_mp4    		=   	$folder."/".$findId."_".$date;
                $youtube       		    =   	"https://www.youtube.com/watch?v=".$findId;
                $download_txt   		=   	$folder.'/'.$date.".txt";
                /* best yerine 260p 360p 480p yazılıp o kalitede görüntü alınabilir. */
                $youtube_xml    		=   	"sudo livestreamer  ".$youtube."  best --stream-url --yes-run-as-root > ".$download_txt;
                exec($youtube_xml, $sonuc_xml);
                $txt            		=       fopen($download_txt, 'r');
                $txt_xml        		=       trim(fread($txt, filesize($download_txt)));
                $komut			        =       "sudo ffmpeg -headers '.$headers.' -i ".$txt_xml."  -ss 00:00:00 -t ".$record_time." -strict -2 ".$youtube_mp4.".mp4 ";
                $download_sh		    =       $folder."/download-".$date.".sh";
                $dosya			        =       fopen($download_sh, 'w');
                fwrite($dosya, $komut);
                fclose($dosya);
                $sh				        =       'sh '.$download_sh;
                exec($sh, $sonuc);
                $sh				        =       'rm -rf '.$download_sh;
                exec($sh, $sonuc);
                $sh				        =       'rm -rf '.$download_txt;
                exec($sh, $sonuc);

?>
