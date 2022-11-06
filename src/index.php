<?php
$status="";
$msg="";
$city="";


if(isset($_POST['submit'])){
    $city=$_POST['city'];
    
    $url="http://api.openweathermap.org/data/2.5/weather?q=$city&appid=49c0bad2c7458f1c76bec9654081a661";
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $cord=curl_exec($ch);
    curl_close($ch);
    $cord=json_decode($cord,true);
    if($cord['cod']==200){
        $status="yes";
    }else{
        $msg=$cord['message'];
    }
    $lon=$cord['coord']['lon'];
    $lat=$cord['coord']['lat'];
}



if(isset($_POST['submit'])){
    
    $url="https://api.openweathermap.org/data/2.5/onecall?lat=$lat&lon=$lon&exclude=hourly&appid=2faf31722afcede18dc1edd471fe97e9";
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $result=curl_exec($ch);
    curl_close($ch);
    $result=json_decode($result,true);
    
}

?>

<html lang="en" class=" -webkit-">
   <head>
      <meta charset="UTF-8">
      <title>Weather Card</title>
      <style>
         @import url(https://cdnjs.cloudflare.com/ajax/libs/weather-icons/2.0.9/css/weather-icons.min.css);
      </style>
      <link rel="stylesheet" href="../dist/output.css">
      <script src="https://cdn.tailwindcss.com"></script>
   </head>
   <body class= " bg-[#5f9ea0] p-0 m-0 font-mono">
      <div class="absolute left-1/4 top-16 ">
         <form class="flex " method="post">
            <input type="text" class=" w-52 text-lg p-2 rounded" placeholder="Enter city name" name="city" value="<?php   echo $city ?>"/>
            <input type="submit" value="Search" class="p-2  rounded bg-[#2f4f4f] ml-2" name="submit"/>
            <?php echo $msg?>
         </form>
      </div>
      
      <?php if($status=="yes"){?>
      <article style="height:300px;width:500px" class=" bg-[#2f4f4f] absolute left-1/4 flex flex-col  rounded shadow-md shadow-black opacity-90 translate-y-1/2 items-center">
         <div class=" flex items-center bg-white w-full ">
            <img src="http://openweathermap.org/img/wn/<?php echo $cord['weather'][0]['icon']?>@4x.png"/>
         </div>
         <div class="bg-[#2f4f4f] flex">
            <div class="content-around flex items-end pr-24 pt-5 text-lg">
               <span><?php echo round($cord['main']['temp']-273.15)?>°</span>
            </div>
            <div class="flex content-center ml-15 flex-col pr-24 pt-5 text-lg">
               <div class="flex flex-col-reverse h-6 "><?php echo $cord['weather'][0]['main']?></div>
               <div class="place"><?php echo $cord['name']?></div>
            </div>
            <div class="pr-6 pt-5 text-lg">
               <div class="weatherCondition">Wind</div>
               <div class="place"><?php echo $cord['wind']['speed']?> M/H</div>
            </div>
            <div class="flex flex-col-reverse text-xl font-semibold leading-10 ">
               <?php echo date('d M',$cord['dt'])?> 
            </div>
         </div>
         
      </article>
      <div class="flex justify-evenly pt-[30%]">
        <?php
        $key=1;
        
            while($key!=5){
                echo "
        <div class='bg-[#2f4f4f] flex flex-row flex-wrap  rounded shadow-md shadow-black opacity-90 translate-y-1/2 items-start w-60 '>
            <div class='flex items-center bg-white  rounded-tl-lg w-40 h-24'>
                <img src='http://openweathermap.org/img/wn/". $result['daily'][$key]['weather'][0]['icon']."@4x.png'/>
                <div class='flex flex-col text-sm font-semibold pl-[30px] '>
                     ".date('d M',$result['daily'][$key]['dt'])." 
                  </div>
            </div>
               <div class='flex flex-row'>
                  <div class='bg-[#2f4f4f] flex'>
                     <div class='content-around flex items-start flex-col pt-3 pr-3 pl-5 text-xs'>
                        <span>day ".round($result['daily'][$key]['temp']['day']-273.15)."°</span>
                        <span>night ".round($result['daily'][$key]['temp']['night']-273.15)."°</span>
                     </div>
                  </div>
                  <div class='flex content-center  flex-col pt-3 pr-5 text-xs'>
                     <div class='weatherCondition'>".$result['daily'][$key]['weather'][0]['main']."</div>
                     <div class='place'>".$cord['name']."</div>
         
                  </div>
                  <div class='flex content-center flex-col pt-3 text-xs'>
                     <div class='weatherCondition'>Wind</div>
                     <div class='place'>".$result['daily'][$key]['wind_speed']." M/H</div>
                  </div>
                  
               </div>
        </div>";
        $key+=1;
    }
        ?>
        </div>
      <?php } ?>
   </body>
</html>