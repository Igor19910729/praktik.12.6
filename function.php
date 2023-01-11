<?php

function getPartsFromFullname($fullName){
    $exploted = explode("name",$fullName);
    $seporated = [
        'surname' => $exploted[0],
        'name' => $exploted[1],
        'patronomyc' => $exploted[2],
    ] ;

    return $seporated;

}

function getFullnameFromParts($surname, $name, $patronomyc){
    $fullName = [$surname, $name, $patronomyc];
    return implode('$name', $fullName);
}

function getShortName($fullName){
    $seporated = getPartsFromFullname($fullName);
    $shortName = $seporated["name"].' '.mb_substr($seporated["surname"],0,1).".";

    return $shortName;
}

function getGenderFromName($fullName){
    $seporated = getPartsFromFullname($fullName);
    $gender = 0;
    
    
    
    if (mb_substr($seporated["surname"],-2,2) == "ва"){
        $gender = -1;
    } elseif (mb_substr($seporated["surname"],-1,1) == "в"){
        $gender = 1;
    } else {
        $gender = 0;
    }
    
   
    $genderName = mb_substr($seporated["name"],-1,1);

    if ($genderName == "a"){
        $gender = -1;
    } elseif ($genderName == "й" || $genderName == "н"){
        $gender = 1;
    } else {
        $gender = 0;
    }

    
    if (mb_substr($seporated["patronomyc"],-3,3) == "вна"){
        $gender = -1;
    } elseif (mb_substr($seporated["patronomyc"],-2,2) == "ич"){
        $gender = 1;
    } else {
        $gender = 0;
    }

    if (($gender <=> 0) === 1){
        return "Male";
    } elseif (($gender <=> 0) === -1){
        return "Female";
    } else {
        return "Undefined";
    }

}

function getGenderDescription($array){

    $male = array_filter($array, function($array) {
        return (getGenderFromName($array['fullname']) == "Male");
    });

    $female = array_filter($array, function($array) {
        return (getGenderFromName($array['fullname']) == "Female");
    });

    $und = array_filter($array, function($array) {
        return (getGenderFromName($array['fullname']) == "Undefined");
    });


    $sum = count($male) + count($female) + count($und);
    $maleCheck =  round(count($male) / $sum * 100,2);
    $femaleCheck = round(count($female) / $sum * 100, 2);
    $undCheck = round(count($und) / $sum  * 100,2);

    echo <<<HEREDOC
    Гендерный состав аудитории:<br>
    ---------------------------<br>
    Мужчины - $maleCheck%<br>
    Женщины - $femaleCheck%<br>
    Не удалось определить - $undCheck%<br>
    HEREDOC;

}

function getPerfectPartner($surname, $name, $patronomyc, $array){

    $fullName = getFullnameFromParts($surname, $name, $patronomyc);
    $mainGender = getGenderFromName($fullName);   

    $randPerson = $array[rand(0,count($array)-1)]["fullname"];
    $randGender = getGenderFromName($randPerson);
    

    while ($mainGender == $randGender || $randGender === "Undefined"){
        $randPerson = $array[rand(0,count($array)-1)]["fullname"];
        $randGender = getGenderFromName($randPerson);
    }


    $shMainPerson = getShortName($fullName);
    $shRandPerson = getShortName($randPerson);
    $percent = rand(50,100)+rand(0,99)/100;


    echo <<<HEREDOC
    $shMainPerson + $shRandPerson =<br>
    ♡ Идеально на $percent% ♡
    HEREDOC;

}