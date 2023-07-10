<?php




function img_get($url){ 

    preg_match('/large\/(.*?)/',$url,$text_url);
     
        $data = file_get_contents('image.php');
      
        $url = str_replace('/','\\/',$url);
        

            $open = fopen('./image.php',"a+");
        
        fwrite($open,"\n".$url);
             
            fclose($open);
/*    }else{
        $text = str_replace($text_url[1],'',$data);
        
            if($text == $data){
         
            
                }else{
    
       
           $open = fopen('./image.php',"w");
           
                fwrite($open,$data."\n".$url);
                
                    fclose($open);
                 //   echo $data;
        
            }
        }*/

}

