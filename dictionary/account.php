<?php
include_once 'setting.php';
include_once 'connect.php';

if (isset($_POST['name']) && $_POST['name'] === "query_build") {
$tema = isset($_POST['tema']) ? FormChars($_POST['tema']) : '';
$A1 = isset($_POST['A1']) ? FormChars($_POST['A1']) : '';
$A2 = isset($_POST['A2']) ? FormChars($_POST['A2']) : '';
$A2plus = isset($_POST['A2plus']) ? FormChars($_POST['A2plus']) : '';
$B1 = isset($_POST['B1']) ? FormChars($_POST['B1']) : '';
$B2 = isset($_POST['B2']) ? FormChars($_POST['B2']) : '';
$C1 = isset($_POST['C1']) ? FormChars($_POST['C1']) : '';
$WL = isset($_POST['WL']) ? FormChars($_POST['WL']) : '';
$part_of_speech = isset($_POST['part_of_speech']) ? FormChars($_POST['part_of_speech']) : '';
$word = isset($_POST['word']) ? FormChars($_POST['word']) : '';
$letter = isset($_POST['letter']) ? FormChars($_POST['letter']) : '';


// Formirovanie zaprosa

$my_Query='SELECT words.id,words.word,words.word_view,use.level FROM `words` JOIN `use` ON words.id = use.word_id  '; //начальный запрос
query_build($tema, $A1, $A2, $A2plus, $B1, $B2, $C1, $WL, $part_of_speech, $word, $letter, $my_Query);
}

//zapis

if (isset($_POST['name']) && $_POST['name'] === "upg_word1") { //zapis WORD
	$word = FormChars($_POST['p1']);
	$c_id = FormChars($_POST['id']);
	$sql="UPDATE `words` SET `word`='".$word."' WHERE `id`=".$c_id;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] ===  "upg_word2") { //zapis WORD_VIEW
	$word = FormChars($_POST['p1']);
	$c_id = FormChars($_POST['id']);
	$sql="UPDATE `words` SET `word_view`='".$word."' WHERE `id`=".$c_id;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] ===  "upg_speech") { //zapis PART_OF_SPEECH
	$p1 = FormChars($_POST['p1']);
	$c_id = FormChars($_POST['id']); //echo $p1."<br />".$c_id;
	global $CONNECT; 
	$sql="SELECT `part_of_speech_id` FROM `words` WHERE `id`=".$c_id;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
	$row=mysqli_fetch_array($result);
	$p_old=$row['part_of_speech_id'];
	
	$sql="UPDATE `words` SET `part_of_speech_id`='".$p1."' WHERE `id`=".$c_id;
	//echo $sql;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
	 //echo $p_old."<br />";
	switch ($p_old) {
        case 1:
		$sql="DELETE  FROM `noun` WHERE `word_ID`=".$c_id;
		break;
        case 2:
		$sql="DELETE FROM `adjective` WHERE `word_ID`=".$c_id;
		break;
        case 3:
		$sql="DELETE FROM `numeral` WHERE `word_ID`=".$c_id;
		break;
        case 4:
		$sql="DELETE FROM `pronoun` WHERE `word_ID`=".$c_id;
		break;
        case 5:
		$sql="DELETE FROM `verb` WHERE `word_ID`=".$c_id;
		break;
        case 6:
		$sql="DELETE FROM `verbnoun` WHERE `word_ID`=".$c_id;
		break;
        case 7:
		$sql="DELETE FROM `participle` WHERE `word_ID`=".$c_id;
		break;
        case 8:
		$sql="DELETE FROM `adverb` WHERE `word_ID`=".$c_id;
		break;
        case 9:
		$sql="DELETE FROM `particle` WHERE `word_ID`=".$c_id;
		break;
        case 10:
		$sql="DELETE FROM `conjunction` WHERE `word_ID`=".$c_id;
		break;
        case 11:
		$sql="DELETE FROM `postposition` WHERE `word_ID`=".$c_id;
		break;
        case 12:
		$sql="DELETE FROM `interjection` WHERE `word_ID`=".$c_id;
		break;
	}
	//echo $sql."<br />";
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
	// echo $p1;
		switch ($p1) {
        case 1:
		$sql="INSERT INTO  `noun`(`word_ID`) VALUES (".$c_id.")";
		break;
        case 2:
		$sql="INSERT INTO  `adjective`(`word_ID`) VALUES (".$c_id.")";
		break;
        case 3:
		$sql="INSERT INTO  `numeral`(`word_ID`) VALUES (".$c_id.")";
		break;
        case 4:
		$sql="INSERT INTO  `pronoun`(`word_ID`) VALUES (".$c_id.")";
		break;
        case 5:
		$sql="INSERT INTO  `verb`(`word_ID`) VALUES (".$c_id.")";
		break;
        case 6:
		$sql="INSERT INTO  `verbnoun`(`word_ID`) VALUES (".$c_id.")";
		break;
        case 7:
		$sql="INSERT INTO  `participle`(`word_ID`) VALUES (".$c_id.")";
		break;
        case 8:
		$sql="INSERT INTO  `adverb`(`word_ID`) VALUES (".$c_id.")";
		break;
        case 9:
		$sql="INSERT INTO  `particle`(`word_ID`) VALUES (".$c_id.")";
		break;
        case 10:
		$sql="INSERT INTO  `conjunction`(`word_ID`) VALUES (".$c_id.")";
		break;
        case 11:
		$sql="INSERT INTO  `postposition`(`word_ID`) VALUES (".$c_id.")";
		break;
        case 12:
		$sql="INSERT INTO  `interjection`(`word_ID`) VALUES (".$c_id.")";
		break;
	}
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] ===  "upg_level") { //zapis Level
	$word = FormChars($_POST['p1']);
	$c_id = FormChars($_POST['id']);
	$sql="UPDATE `use` SET `level`='".$word."' WHERE `id`=".$c_id;
	echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}
if (isset($_POST['name']) && $_POST['name'] ===  "upg_interp") { //zapis TOLKOVANIE INTERPRETATION
	$word = FormChars($_POST['p1']);
	$c_id = FormChars($_POST['id']);
	$sql="UPDATE `use` SET `interpretation`='".$word."' WHERE `id`=".$c_id;
	echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}
if (isset($_POST['name']) && $_POST['name'] ===  "upg_use") { //zapis ISPOLZOVANIE USE
	$word = FormChars($_POST['p1']);
	$c_id = FormChars($_POST['id']);
	$sql="UPDATE `use` SET `use`='".$word."' WHERE `id`=".$c_id;
	echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] ===  "upg_translate") { //zapis PEREVOD TRANSLATE
	$word = FormChars($_POST['p1']);
	$c_id = FormChars($_POST['id']);
	$sql="UPDATE `use` SET `translate`='".$word."' WHERE `id`=".$c_id;
	echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] ===  "upg_tema1") { //zapis TEMA1
	$word = FormChars($_POST['p1']);
	$c_id = FormChars($_POST['id']);
	$sql="UPDATE `use` SET `tema1`='".$word."' WHERE `id`=".$c_id;
	echo $word."<br />".$c_id."<br />".$sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] ===  "upg_tema2") { //zapis TEMA2
	$word = FormChars($_POST['p1']);
	$c_id = FormChars($_POST['id']);
	$sql="UPDATE `use` SET `tema2`='".$word."' WHERE `id`=".$c_id;
	echo $word."<br />".$c_id."<br />".$sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] ===  "upg_tema3") { //zapis TEMA3
	$word = FormChars($_POST['p1']);
	$c_id = FormChars($_POST['id']);
	$sql="UPDATE `use` SET `tema3`='".$word."' WHERE `id`=".$c_id;
	echo $word."<br />".$c_id."<br />".$sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] ===  "del_use") { //udalenie Tolkovanie
	$c_id = FormChars($_POST['id']);
	$sql="DELETE  FROM `use` WHERE `id`=".$c_id;
	//echo $word."<br />".$c_id."<br />".$sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
	
		$sql="SELECT * FROM `dictionary_antonim`  WHERE `use_id`=".$c_id;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
	if (mysqli_num_rows($result)>0) {
		$sql="DELETE  FROM `dictionary_antonim` WHERE `id`=".$c_id;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
	}
	
		$sql="SELECT * FROM `dictionary_sinonim`  WHERE `use_id`=".$c_id;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
	if (mysqli_num_rows($result)>0) {
		$sql="DELETE  FROM `dictionary_sinonim` WHERE `id`=".$c_id;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
	}
	
		$sql="SELECT * FROM `idiom`  WHERE `use_id`=".$c_id;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
	if (mysqli_num_rows($result)>0) {
		$sql="DELETE  FROM `idiom` WHERE `use_id`=".$c_id;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
	}
}
if (isset($_POST['name']) && $_POST['name'] ===  "upg_sin") { //KOrrektirovka SINONIM
	$word = FormChars($_POST['p1']);
	$id = FormChars($_POST['p2']);
	$sql="UPDATE `dictionary_sinonim` SET `sinonim`='".$word."' WHERE `id`=".$id;
	//echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}
if (isset($_POST['name']) && $_POST['name'] ===  "upg_ant") { //KORREKTIROVKA ANTONIM
	$word = FormChars($_POST['p1']);
	$id = FormChars($_POST['p2']);
	$sql="UPDATE `dictionary_antonim` SET `antonim`='".$word."' WHERE `id`=".$id;
	//echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}
if (isset($_POST['name']) && $_POST['name'] ===  "del_sin") { //UDALENIE SINONIM
	$id = FormChars($_POST['p1']);
	$sql="DELETE FROM `dictionary_sinonim` WHERE `id`=".$id;
	//echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}
if (isset($_POST['name']) && $_POST['name'] ===  "antonim_delete") { //UDALENIE ANTONIM
	$id = FormChars($_POST['p1']);
	$sql="DELETE FROM `dictionary_antonim` WHERE `id`=".$id;
	//echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}
if (isset($_POST['name']) && $_POST['name'] ===  "idiom_delete") { //UDALENIE IDIOM
	$id = FormChars($_POST['p1']);
	$sql="DELETE FROM `idiom` WHERE `id`=".$id;
	echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}
if (isset($_POST['name']) && $_POST['name'] ===  "add_sin") { //DOBAVLENIE SINONIM
	$use_id = FormChars($_POST['p1']);
	$sql="INSERT INTO `dictionary_sinonim`(`use_ID`) VALUES (".$use_id.")";
	//echo "<br />".$sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}
if (isset($_POST['name']) && $_POST['name'] ===  "add_antonim") { //DOBAVLENIE Antonim
	$use_id = FormChars($_POST['p1']);
	$sql="INSERT INTO `dictionary_antonim`(`use_ID`) VALUES (".$use_id.")";
	//echo "<br />".$sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}
if (isset($_POST['name']) && $_POST['name'] ===  "add_idiom") { //DOBAVLENIE IDIOM
	$use_id = FormChars($_POST['p1']);
	$sql="INSERT `idiom` (`use_ID`) VALUES (".$use_id.")";
	echo "<br />".$sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}
if (isset($_POST['name']) && $_POST['name'] ===  "add_use") { //DOBAVLENIE USE
	$use_id = FormChars($_POST['p1']);
	$sql="INSERT `use` (`word_ID`) VALUES (".$use_id.")";
	//echo "<br />".$sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}
if (isset($_POST['name']) && $_POST['name'] ===  "upg_idiom") { //zapis IDIOM
	$word = FormChars($_POST['p1']);
	$id_idiom = FormChars($_POST['p2']);
	$sql="UPDATE `idiom` SET `idiom`='".$word."' WHERE `id`=".$id_idiom;
	echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}
if (isset($_POST['name']) && $_POST['name'] ===  "upg_idiom_in") { //zapis IDIOM INTERPRETATION
	$word = FormChars($_POST['p1']);
	$id_idiom = FormChars($_POST['p2']);
	$sql="UPDATE `idiom` SET `idiom_interpretation`='".$word."' WHERE `id`=".$id_idiom;
	echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}
if (isset($_POST['name']) && $_POST['name'] ===  "upg_idiom_use") { //zapis IDIOM USE
	$word = FormChars($_POST['p1']);
	$id_idiom = FormChars($_POST['p2']);
	$sql="UPDATE `idiom` SET `idiom_use`='".$word."' WHERE `id`=".$id_idiom;
	echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] ===  "upg_noun") { //zapis PADEJI SUSHESTVITELNOE
	$p1 = FormChars($_POST['p1']);
	$p2 = FormChars($_POST['p2']);
	$p3 = FormChars($_POST['p3']);
	$p4 = FormChars($_POST['p4']);
	$p5 = FormChars($_POST['p5']);
	$p6 = FormChars($_POST['p6']);
	$p7 = FormChars($_POST['p7']);
	$p8 = FormChars($_POST['p8']);
	$p9 = FormChars($_POST['p9']);
	$p10 = FormChars($_POST['p10']);
	$p11 = FormChars($_POST['p11']);
	$p12 = FormChars($_POST['p12']);
	$p13 = FormChars($_POST['p13']);
	$id = FormChars($_POST['id']);

	$sql="UPDATE `noun` SET `ergative_s`='".$p2."',`dative_s`='".$p4."',`genetive_s`='".$p6."',`instrumental_s`='".$p8."',`transformative_s`='".$p10."',`vocative_s`='".$p12."',`nominative_p`='".$p1."',`ergative_p`='".$p3."',`dative_p`='".$p5."',`genetive_p`='".$p7."',`instrumental_p`='".$p9."',`transformative_p`='".$p11."',`vacative_p`='".$p13."' WHERE `word_ID`=".$id;
	//echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] ===  "upg_numeral") { //zapis PADEJI Chislitelnoe
	$p1 = FormChars($_POST['p1']);
	$p2 = FormChars($_POST['p2']);
	$p3 = FormChars($_POST['p3']);
	$p4 = FormChars($_POST['p4']);
	$p5 = FormChars($_POST['p5']);
	$p6 = FormChars($_POST['p6']);
	$p7 = FormChars($_POST['p7']);
	$id = FormChars($_POST['id']);

	$sql="UPDATE `numeral` SET `kind`='".$p1."',`ergative_s`='".$p2."',`dative_s`='".$p3."',`genetive_s`='".$p4."',`instrumental_s`='".$p5."',`transformative_s`='".$p6."',`vocative_s`='".$p7."' WHERE `word_ID`=".$id;
	echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] ===  "upg_adjec") { //zapis PADEJI Prilagatelnoe
	$id = FormChars($_POST['id']);
	$p1 = FormChars($_POST['p1']);
	$p2 = FormChars($_POST['p2']);
	$p3 = FormChars($_POST['p3']);
	$p4 = FormChars($_POST['p4']);
	$p5 = FormChars($_POST['p5']);
	$p6 = FormChars($_POST['p6']);
	$p7 = FormChars($_POST['p7']);
	$p8 = FormChars($_POST['p8']);
	$p9 = FormChars($_POST['p9']);
	$p10 = FormChars($_POST['p10']);
	$p11 = FormChars($_POST['p11']);
	$p12 = FormChars($_POST['p12']);
	$p13 = FormChars($_POST['p13']);
	$p14 = FormChars($_POST['p14']);
	$p15 = FormChars($_POST['p15']);
	$p16 = FormChars($_POST['p16']);

	$sql="UPDATE `adjective` SET `normal_degree`='".$p1."', `comparative_degree`='".$p2."', `superlative_degree`='".$p3."', `ergative_s`='".$p4."', `dative_s`='".$p5."', `genetive_s`='".$p6."', `instrumental_s`='".$p7."', `transformative_s`='".$p8."', `vocative_s`='".$p9."', `nominative_p`='".$p10."', `ergative_p`='".$p11."', `dative_p`='".$p12."', `genetive_p`='".$p13."', `instrumental_p`='".$p14."', `transformative_p`='".$p15."', `vocative_p`='".$p16."' WHERE `word_ID`=".$id;
	//echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}


if (isset($_POST['name']) && $_POST['name'] ===  "upg_pronoun") { //zapis PADEJI MESTOIMENIE
	$id = FormChars($_POST['id']);
	$p1 = FormChars($_POST['p1']);
	$p2 = FormChars($_POST['p2']);
	$p3 = FormChars($_POST['p3']);
	$p4 = FormChars($_POST['p4']);
	$p5 = FormChars($_POST['p5']);
	$p6 = FormChars($_POST['p6']);
	$p7 = FormChars($_POST['p7']);
	$p8 = FormChars($_POST['p8']);
	$p9 = FormChars($_POST['p9']);
	$p10 = FormChars($_POST['p10']);
	$p11 = FormChars($_POST['p11']);
	$p12 = FormChars($_POST['p12']);
	$p13 = FormChars($_POST['p13']);
	$p14 = FormChars($_POST['p14']);

	$sql="UPDATE `pronoun` SET `characteristic`='".$p1."', `ergative_s`='".$p2."', `dative_s`='".$p3."', `genetive_s`='".$p4."', `instrumental_s`='".$p5."', `transformative_s`='".$p6."', `vocative_s`='".$p7."', `nominative_p`='".$p8."', `ergative_p`='".$p9."', `dative_p`='".$p10."', `genetive_p`='".$p11."', `instrumental_p`='".$p12."', `transformative_p`='".$p13."',`vocative_p`='".$p14."' WHERE `word_ID`=".$id;
	//echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] ===  "upg_verbnoun") { //zapis satskisi
	$id = FormChars($_POST['id']);
	$p1 = FormChars($_POST['p1']);
	$p2 = FormChars($_POST['p2']);
	$p3 = FormChars($_POST['p3']);
	$p4 = FormChars($_POST['p4']);
	$p5 = FormChars($_POST['p5']);
	$p6 = FormChars($_POST['p6']);
	$p7 = FormChars($_POST['p7']);

	$sql="UPDATE `verbnoun` SET `verb`='".$p1."', `ergative_s`='".$p2."', `dative_s`='".$p3."', `genetive_s`='".$p4."', `instrumental_s`='".$p5."', `transformative_s`='".$p6."', `vocative_s`='".$p7."' WHERE `word_ID`=".$id;
	//echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] ===  "upg_verb") { //zapis PADEJI GLAGOL
	$id = FormChars($_POST['id']);
	$p1 = FormChars($_POST['p1']);
	$p2 = FormChars($_POST['p2']);
	$p3 = FormChars($_POST['p3']);
	$p4 = FormChars($_POST['p4']);
	$p5 = FormChars($_POST['p5']);
	$p6 = FormChars($_POST['p6']);
	$p7 = FormChars($_POST['p7']);
	$p8 = FormChars($_POST['p8']);
	$p9 = FormChars($_POST['p9']);
	$p10 = FormChars($_POST['p10']);
	$p11 = FormChars($_POST['p11']);
	$p12 = FormChars($_POST['p12']);
	$p13 = FormChars($_POST['p13']);
	$p14 = FormChars($_POST['p14']);
	$p15 = FormChars($_POST['p15']);
	
	$sql="UPDATE `verb` SET `transilive_intransilive`='".$p2."', `peculiarity`='".$p15."', `infinitive`='".$p1."', `voice`='".$p3."', `present_lindicative`='".$p4."',`imperfect`='".$p5."', `present_stubjunctive`='".$p6."', `future`='".$p7."',`conditional`='".$p8."', `future_subjunctive`='".$p9."', `aorist`='".$p10."', `conjuctive_II`='".$p11."', `resultative_I`='".$p12."',`resultative_II`='".$p13."',`conjuctive_III`='".$p14."' WHERE `word_ID`=".$id;
	//echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] ===  "upg_participle") { //zapis PADEJI PRICHSTIE
	$id = FormChars($_POST['id']);
	$p1 = FormChars($_POST['p1']);
	$p2 = FormChars($_POST['p2']);
	$p3 = FormChars($_POST['p3']);
	$p4 = FormChars($_POST['p4']);
	$p5 = FormChars($_POST['p5']);
	$p6 = FormChars($_POST['p6']);
	$p7 = FormChars($_POST['p7']);
	$p8 = FormChars($_POST['p8']);
	$p9 = FormChars($_POST['p9']);
	$p10 = FormChars($_POST['p10']);
	$p11 = FormChars($_POST['p11']);
	$p12 = FormChars($_POST['p12']);
	$p13 = FormChars($_POST['p13']);
	$p14 = FormChars($_POST['p14']);
	$p15 = FormChars($_POST['p15']);
	$sql="UPDATE `participle` SET `verb`='".$p1."',`voice`='".$p2."',`ergative_s`='".$p3."',`dative_s`='".$p4."',`genetive_s`='".$p5."',`instrumental_s`='".$p6."',`transformative_s`='".$p7."',`vocative_s`='".$p8."',`nominative_p`='".$p9."',`ergative_p`='".$p10."',`dative_p`='".$p11."',`genetive_p`='".$p12."',`instrumental_p`='".$p13."',`transformative_p`='".$p14."',`vocative_p`='".$p15."' WHERE `word_ID`=".$id;
	
	//echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] ===  "upg_adverb") { //zapis PADEJI otglagol sush
	$id = FormChars($_POST['id']);
	$p1 = FormChars($_POST['p1']);
	
	$sql="UPDATE `adverb` SET `semantic_group`='".$p1."' WHERE `word_ID`=".$id;
//echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}
if (isset($_POST['name']) && $_POST['name'] ===  "upg_postposition") { //zapis PADEJI 
	$id = FormChars($_POST['id']);
	$p1 = FormChars($_POST['p1']);
	
	$sql="UPDATE `postposition` SET `case`=".$p1." WHERE `word_ID`=".$id;
//echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] ===  "upg_conjunction") { //zapis PADEJI SOUZ
	$id = FormChars($_POST['id']);
	$p1 = FormChars($_POST['p1']);
	
	$sql="UPDATE `conjunction` SET `semantic_group`=".$p1." WHERE `word_ID`=".$id;
//echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] ===  "upg_particle") { //zapis PADEJI 
	$id = FormChars($_POST['id']);
	$p1 = FormChars($_POST['p1']);
	
	$sql="UPDATE `particle` SET `semantic_group`='".$p1."' WHERE `word_ID`=".$id;
//echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}

if (isset($_POST['name']) && $_POST['name'] === "upg_interjection") { //zapis PADEJI MEJDOMETIA
	$id = FormChars($_POST['id']);
	$p1 = FormChars($_POST['p1']);

	$sql="UPDATE `interjection` SET `semantic_group`='".$p1."' WHERE `word_ID`=".$id;
//echo $sql;
	global $CONNECT;
	$result = mysqli_query($CONNECT, $sql) or die ("Oshibka<br />" . mysqli_error($CONNECT));
}
if (isset($_POST['name']) && $_POST['name'] === "edit_word") {
	$c_id = FormChars($_POST['id']);
edit_word($c_id);	
}

function edit_word($c_id) {
	$full_query='SELECT * FROM words  WHERE `id`='.$c_id; //echo $full_query."<br />";
	global $CONNECT;
	$full_result = mysqli_query($CONNECT, $full_query) or die ("Oshibka<br />" . mysqli_error($CONNECT));
	$row=mysqli_fetch_array($full_result);
	
	echo '<input type="text" maxlength="25" name="'.$c_id.'" class="cf_word1" value='.$row['word'].'><br /><br />';
	echo '<input type="text" maxlength="25" name="'.$c_id.'" class="cf_word2" value='.$row['word_view'].'><br /><br />';
// Chast rechi
	echo '		<select size="1" name="'.$c_id.'" id="cf_speech">';	
	switch ($row['part_of_speech_id']) {
        case 0:
 	echo '		<option value="0" name="0" selected="selected">-</option>';
 	echo '		<option value="1" name="1">არსებითი სახელი</option>';
	echo '		<option value="2" name="2">ზედსართავი სახელი</option>';
	echo '		<option value="3" name="3">რიცხვითი სახელი</option>';
	echo '		<option value="4" name="4">ნაცვალსახელი</option>';
	echo '		<option value="5" name="5" >ზმნა</option>';
	echo '		<option value="6" name="6">საწყისი</option>';
	echo '		<option value="7" name="7">მიმღეობა</option>';
	echo '		<option value="8" name="8">ზმნიზედა</option>';
	echo '		<option value="9" name="9">ნაწილაკი</option>';
	echo '		<option value="10" name="10">კავშირი</option>';
	echo '		<option value="11" name="11">თანდებული</option>';
	echo '		<option value="12" name="12">შორისდებული</option>';
        break;
		case 1:
 	echo '		<option value="0" name="0">-</option>';		
 	echo '		<option value="1" name="1" selected="selected">არსებითი სახელი</option>';
	echo '		<option value="2" name="2">ზედსართავი სახელი</option>';
	echo '		<option value="3" name="3">რიცხვითი სახელი</option>';
	echo '		<option value="4" name="4">ნაცვალსახელი</option>';
	echo '		<option value="5" name="5" >ზმნა</option>';
	echo '		<option value="6" name="6">საწყისი</option>';
	echo '		<option value="7" name="7">მიმღეობა</option>';
	echo '		<option value="8" name="8">ზმნიზედა</option>';
	echo '		<option value="9" name="9">ნაწილაკი</option>';
	echo '		<option value="10" name="10">კავშირი</option>';
	echo '		<option value="11" name="11">თანდებული</option>';
	echo '		<option value="12" name="12">შორისდებული</option>';
        break;
    case 2:
 	echo '		<option value="0" name="0">-</option>';		
	echo '		<option value="1" name="1">არსებითი სახელი</option>';
	echo '		<option value="2" name="2" selected="selected">ზედსართავი სახელი</option>';
	echo '		<option value="3" name="3">რიცხვითი სახელი</option>';
	echo '		<option value="4" name="4">ნაცვალსახელი</option>';
	echo '		<option value="5" name="5" >ზმნა</option>';
	echo '		<option value="6" name="6">საწყისი</option>';
	echo '		<option value="7" name="7">მიმღეობა</option>';
	echo '		<option value="8" name="8">ზმნიზედა</option>';
	echo '		<option value="9" name="9">ნაწილაკი</option>';
	echo '		<option value="10" name="10">კავშირი</option>';
	echo '		<option value="11" name="11">თანდებული</option>';
	echo '		<option value="12" name="12">შორისდებული</option>';
        break;
    case 3:
 	echo '		<option value="0" name="0">-</option>';		
	echo '		<option value="1" name="1">არსებითი სახელი</option>';
	echo '		<option value="2" name="2">ზედსართავი სახელი</option>';
	echo '		<option value="3" name="3" selected="selected">რიცხვითი სახელი</option>';
	echo '		<option value="4" name="4">ნაცვალსახელი</option>';
	echo '		<option value="5" name="5" >ზმნა</option>';
	echo '		<option value="6" name="6">საწყისი</option>';
	echo '		<option value="7" name="7">მიმღეობა</option>';
	echo '		<option value="8" name="8">ზმნიზედა</option>';
	echo '		<option value="9" name="9">ნაწილაკი</option>';
	echo '		<option value="10" name="10">კავშირი</option>';
	echo '		<option value="11" name="11">თანდებული</option>';
	echo '		<option value="12" name="12">შორისდებული</option>';
        break;
    case 4:
 	echo '		<option value="0" name="0">-</option>';		
	echo '		<option value="1" name="1">არსებითი სახელი</option>';
	echo '		<option value="2" name="2">ზედსართავი სახელი</option>';
	echo '		<option value="3" name="3">რიცხვითი სახელი</option>';
	echo '		<option value="4" name="4" selected="selected">ნაცვალსახელი</option>';
	echo '		<option value="5" name="5" >ზმნა</option>';
	echo '		<option value="6" name="6">საწყისი</option>';
	echo '		<option value="7" name="7">მიმღეობა</option>';
	echo '		<option value="8" name="8">ზმნიზედა</option>';
	echo '		<option value="9" name="9">ნაწილაკი</option>';
	echo '		<option value="10" name="10">კავშირი</option>';
	echo '		<option value="11" name="11">თანდებული</option>';
	echo '		<option value="12" name="12">შორისდებული</option>';
        break;
    case 5:
 	echo '		<option value="0" name="0">-</option>';		
	echo '		<option value="1" name="1">არსებითი სახელი</option>';
	echo '		<option value="2" name="2">ზედსართავი სახელი</option>';
	echo '		<option value="3" name="3">რიცხვითი სახელი</option>';
	echo '		<option value="4" name="4">ნაცვალსახელი</option>';
	echo '		<option value="5" name="5" selected="selected" >ზმნა</option>';
	echo '		<option value="6" name="6">საწყისი</option>';
	echo '		<option value="7" name="7">მიმღეობა</option>';
	echo '		<option value="8" name="8">ზმნიზედა</option>';
	echo '		<option value="9" name="9">ნაწილაკი</option>';
	echo '		<option value="10" name="10">კავშირი</option>';
	echo '		<option value="11" name="11">თანდებული</option>';
	echo '		<option value="12" name="12">შორისდებული</option>';
        break;
    case 6:
 	echo '		<option value="0" name="0">-</option>';		
	echo '		<option value="1" name="1">არსებითი სახელი</option>';
	echo '		<option value="2" name="2">ზედსართავი სახელი</option>';
	echo '		<option value="3" name="3">რიცხვითი სახელი</option>';
	echo '		<option value="4" name="4">ნაცვალსახელი</option>';
	echo '		<option value="5" name="5" >ზმნა</option>';
	echo '		<option value="6" name="6" selected="selected">საწყისი</option>';
	echo '		<option value="7" name="7">მიმღეობა</option>';
	echo '		<option value="8" name="8">ზმნიზედა</option>';
	echo '		<option value="9" name="9">ნაწილაკი</option>';
	echo '		<option value="10" name="10">კავშირი</option>';
	echo '		<option value="11" name="11">თანდებული</option>';
	echo '		<option value="12" name="12">შორისდებული</option>';
        break;
    case 7:
 	echo '		<option value="0" name="0">-</option>';		
	echo '		<option value="1" name="1">არსებითი სახელი</option>';
	echo '		<option value="2" name="2">ზედსართავი სახელი</option>';
	echo '		<option value="3" name="3">რიცხვითი სახელი</option>';
	echo '		<option value="4" name="4">ნაცვალსახელი</option>';
	echo '		<option value="5" name="5" >ზმნა</option>';
	echo '		<option value="6" name="6">საწყისი</option>';
	echo '		<option value="7" name="7" selected="selected">მიმღეობა</option>';
	echo '		<option value="8" name="8">ზმნიზედა</option>';
	echo '		<option value="9" name="9">ნაწილაკი</option>';
	echo '		<option value="10" name="10">კავშირი</option>';
	echo '		<option value="11" name="11">თანდებული</option>';
	echo '		<option value="12" name="12">შორისდებული</option>';
        break;
    case 8:
 	echo '		<option value="0" name="0">-</option>';		
	echo '		<option value="1" name="1">არსებითი სახელი</option>';
	echo '		<option value="2" name="2">ზედსართავი სახელი</option>';
	echo '		<option value="3" name="3">რიცხვითი სახელი</option>';
	echo '		<option value="4" name="4">ნაცვალსახელი</option>';
	echo '		<option value="5" name="5" >ზმნა</option>';
	echo '		<option value="6" name="6">საწყისი</option>';
	echo '		<option value="7" name="7">მიმღეობა</option>';
	echo '		<option value="8" name="8" selected="selected">ზმნიზედა</option>';
	echo '		<option value="9" name="9">ნაწილაკი</option>';
	echo '		<option value="10" name="10">კავშირი</option>';
	echo '		<option value="11" name="11">თანდებული</option>';
	echo '		<option value="12" name="12">შორისდებული</option>';
        break;
    case 9:
 	echo '		<option value="0" name="0">-</option>';		
	echo '		<option value="1" name="1">არსებითი სახელი</option>';
	echo '		<option value="2" name="2">ზედსართავი სახელი</option>';
	echo '		<option value="3" name="3">რიცხვითი სახელი</option>';
	echo '		<option value="4" name="4">ნაცვალსახელი</option>';
	echo '		<option value="5" name="5" >ზმნა</option>';
	echo '		<option value="6" name="6">საწყისი</option>';
	echo '		<option value="7" name="7">მიმღეობა</option>';
	echo '		<option value="8" name="8">ზმნიზედა</option>';
	echo '		<option value="9" name="9" selected="selected">ნაწილაკი</option>';
	echo '		<option value="10" name="10">კავშირი</option>';
	echo '		<option value="11" name="11">თანდებული</option>';
	echo '		<option value="12" name="12">შორისდებული</option>';
        break;
    case 10:
 	echo '		<option value="0" name="0">-</option>';		
	echo '		<option value="1" name="1">არსებითი სახელი</option>';
	echo '		<option value="2" name="2">ზედსართავი სახელი</option>';
	echo '		<option value="3" name="3">რიცხვითი სახელი</option>';
	echo '		<option value="4" name="4">ნაცვალსახელი</option>';
	echo '		<option value="5" name="5" >ზმნა</option>';
	echo '		<option value="6" name="6">საწყისი</option>';
	echo '		<option value="7" name="7">მიმღეობა</option>';
	echo '		<option value="8" name="8">ზმნიზედა</option>';
	echo '		<option value="9" name="9">ნაწილაკი</option>';
	echo '		<option value="10" name="10" selected="selected">კავშირი</option>';
	echo '		<option value="11" name="11">თანდებული</option>';
	echo '		<option value="12" name="12">შორისდებული</option>';
        break;
    case 11:
 	echo '		<option value="0" name="0">-</option>';		
	echo '		<option value="1" name="1">არსებითი სახელი</option>';
	echo '		<option value="2" name="2">ზედსართავი სახელი</option>';
	echo '		<option value="3" name="3">რიცხვითი სახელი</option>';
	echo '		<option value="4" name="4">ნაცვალსახელი</option>';
	echo '		<option value="5" name="5" >ზმნა</option>';
	echo '		<option value="6" name="6">საწყისი</option>';
	echo '		<option value="7" name="7">მიმღეობა</option>';
	echo '		<option value="8" name="8">ზმნიზედა</option>';
	echo '		<option value="9" name="9">ნაწილაკი</option>';
	echo '		<option value="10" name="10">კავშირი</option>';
	echo '		<option value="11" name="11" selected="selected">თანდებული</option>';
	echo '		<option value="12" name="12">შორისდებული</option>';
        break;
    case 12:
 	echo '		<option value="0" name="0">-</option>';		
	echo '		<option value="1" name="1">არსებითი სახელი</option>';
	echo '		<option value="2" name="2">ზედსართავი სახელი</option>';
	echo '		<option value="3" name="3">რიცხვითი სახელი</option>';
	echo '		<option value="4" name="4">ნაცვალსახელი</option>';
	echo '		<option value="5" name="5" >ზმნა</option>';
	echo '		<option value="6" name="6">საწყისი</option>';
	echo '		<option value="7" name="7">მიმღეობა</option>';
	echo '		<option value="8" name="8">ზმნიზედა</option>';
	echo '		<option value="9" name="9">ნაწილაკი</option>';
	echo '		<option value="10" name="10">კავშირი</option>';
	echo '		<option value="11" name="11">თანდებული</option>';
	echo '		<option value="12" name="12" selected="selected">შორისდებული</option>';
        break;		
}
	echo '		</select><br /><br />'; //konec chast rechi	


		$full_query_use="SELECT * FROM `use` WHERE `word_Id`=".$c_id;
		$full_result_use = mysqli_query($CONNECT, $full_query_use) or die ("Oshibka " . mysqli_error($CONNECT));
		$num_use=0;
				while($row_use=mysqli_fetch_array($full_result_use)){
					if (mysqli_num_rows($full_result_use)>1) {
					$num_use=$num_use+1;
					echo "<hr>";
					echo '<p class="blue">განმარტება '.$num_use.'</p>';					
					echo '<p class="del_use" name="'.$row_use['id'].'"><span class="red_big">DELETE</span></p><br />';
					}
					//echo $row_use['level'];
					echo '<select size="1" class="cf_level" name=AA'.$row_use['id'].'>'; 

					if ($row_use['level']=='') {
					echo '		<option value="0" name="0" selected="selected"></option>';	
					} else {
					echo '<option value="0" name="0"></option>';	
					}
					if ($row_use['level']=='A1') {
					echo '		<option value="1" name="1" selected="selected">A1</option>';	
					} else {
					echo '<option value="1" name="1">A1</option>';	
					}
					
					if ($row_use['level']=='A2') {
					echo '		<option value="2" name="2" selected="selected">A2</option>';	
					} else {
					echo '<option value="2" name="2">A2</option>';	
					}
					
					if ($row_use['level']=='A2+') { // добавила, исправить value, name, разобраться
					echo '		<option value="3" name="3" selected="selected">A2+</option>';	
					} else {
					echo '<option value="3" name="3">A2+</option>';	
					}
					
					if ($row_use['level']=='B1') {
					echo '		<option value="4" name="4" selected="selected">B1</option>';	
					} else {
					echo '<option value="4" name="4">B1</option>';	
					}					
					if ($row_use['level']=='B2') {
					echo '		<option value="5" name="5" selected="selected">B2</option>';	
					} else {
					echo '<option value="5" name="5">B2</option>';	
					}
					if ($row_use['level']=='C1') {
					echo '		<option value="56" name="6" selected="selected">C1</option>';	
					} else {
					echo '<option value="6" name="6">C1</option>';	
					}
echo '		</select>';					
echo '		<br /><br />';

				
	echo '<textarea rows="4" cols="100" class="cf_interp" name="'.$row_use['id'].'">'.$row_use['interpretation'].'</textarea><br />';
	echo '<p class="blue">მაგალითები:</p>';
	echo '<textarea rows="4" cols="100" class="cf_use" name="'.$row_use['id'].'">'.$row_use['use'].'</textarea><br />';
	echo '<p class="blue">ინგლისური:</p>';					
	echo '<textarea rows="2" cols="100" class="cf_translate" name="'.$row_use['id'].'">'.$row_use['translate'].'</textarea><br />';
	
	
		$full_query_tema="SELECT * FROM `tema` ORDER BY `tema`";
	$full_result_tema = mysqli_query($CONNECT, $full_query_tema) or die ("Oshibka " . mysqli_error($CONNECT));
	echo '<p class="blue">თემა 1:</p>';
	
    echo '		<select size="1" class="cf_tema1" name="'.$row_use['id'].'">';
		echo '		<option value="0" name="0">-</option>';
				while($row_tem=mysqli_fetch_array($full_result_tema)){
					if ($row_tem['id']==$row_use['tema1']) {
					echo '		<option value="'.$row_tem['id'].'" name="'.$row_use['id'].'" selected="selected">'.$row_tem['tema'].'</option>';
				} else {
					echo '		<option value="'.$row_tem['id'].'" name="'.$row_use['id'].'">'.$row_tem['tema'].'</option>';
				}
				}
	echo '		</select><br /><br />';			
					
	echo '<p class="blue">თემა 2:</p>';		// tema2				

	$full_result_tema = mysqli_query($CONNECT, $full_query_tema) or die ("Oshibka " . mysqli_error($CONNECT));
	    echo '		<select size="1" class="cf_tema2" name="'.$row_use['id'].'">';
if ($row_tem['id']==0) {
	echo '		<option value="0" name="0">-</option>';
	}
			while($row_tem=mysqli_fetch_array($full_result_tema)){
					if ($row_tem['id']==$row_use['tema2']) {
					echo '		<option value="'.$row_tem['id'].'" name="'.$row_use['id'].'" selected="selected">'.$row_tem['tema'].'</option>';
				} else {
					echo '		<option value="'.$row_tem['id'].'" name="'.$row_use['id'].'">'.$row_tem['tema'].'</option>';
				}
				}
				
	echo '		</select><br /><br />';
	echo '<p class="blue">თემა 3:</p>';	// tema3

	$full_result_tema = mysqli_query($CONNECT, $full_query_tema) or die ("Oshibka " . mysqli_error($CONNECT));
	    echo '		<select size="1" class="cf_tema3" name="'.$row_use['id'].'">';
if ($row_tem['id']==0) {
	echo '		<option value="0" name="0">-</option>';
	}			
				while($row_tem=mysqli_fetch_array($full_result_tema)){
					if ($row_tem['id']==$row_use['tema3']) {
					echo '		<option value="'.$row_tem['id'].'" name="'.$row_use['id'].'" selected="selected">'.$row_tem['tema'].'</option>';
				} else {
					echo '		<option value="'.$row_tem['id'].'" name="'.$row_use['id'].'">'.$row_tem['tema'].'</option>';
				}
				}
	echo '		</select><br />';
	
		$num_sinonim=0;
		$full_query_sinonim="SELECT * FROM `dictionary_sinonim` WHERE `use_ID`=".$row_use['id'];
					$full_result_sinonim = mysqli_query($CONNECT, $full_query_sinonim) or die ("Oshibka " . mysqli_error($CONNECT));
						echo '<p class="blue MTop6">სინონიმი:</p>';
					if (mysqli_num_rows($full_result_sinonim)>0) {
						echo '<table>';
						while($row_sin=mysqli_fetch_array($full_result_sinonim)){
							$num_sinonim=$num_sinonim+1;
							echo '<tr><td>'.$num_sinonim.'. </td><td><input type="text" class="cf_sin" id="'.$row_sin['id'].'" value="'.$row_sin['sinonim'].'"></td><td  class="red"><span class="sin_delete" id="'.$row_sin['id'].'">DELETE</span></td></tr>';
						}
						echo '</table>';
					echo "<br />";
					}
					echo "<p class='sin_add' id=".$row_use['id']."><span class='green'>ADD SINONIM</span></p>";
					
					
					
		$num_antonim=0;
		$full_query_sinonim="SELECT * From `dictionary_antonim` WHERE `use_ID`=".$row_use['id'];
					$full_result_sinonim = mysqli_query($CONNECT, $full_query_sinonim) or die ("Oshibka " . mysqli_error($CONNECT));
					echo '<p class="blue MTop6">ანტონიმი:</p>';
					if (mysqli_num_rows($full_result_sinonim)>0) {
						echo '<table>';
						while($row_sin=mysqli_fetch_array($full_result_sinonim)){
							$num_antonim=$num_antonim+1;
							echo '<tr><td>'.$num_antonim.'. </td><td><input type="text" class="cf_ant" id="'.$row_sin['id'].'" value="'.$row_sin['antonim'].'"></td><td  class="red"><span class="antonim_delete" id="'.$row_sin['id'].'">DELETE</span></td></tr>';
						}
						echo '</table>';
					echo "<br />";
					}
					echo "<p class='antonim_add' id=".$row_use['id']."><span class='green'>ADD ANTONIM</span></p>";
					
					$num_idiom=0;
					$full_query_idiom="SELECT * FROM `idiom` WHERE `use_ID`=".$row_use['id'];
					$full_result_idiom = mysqli_query($CONNECT, $full_query_idiom) or die ("Oshibka " . mysqli_error($CONNECT));
					if (mysqli_num_rows($full_result_idiom)>0) {
						while($row_idiom=mysqli_fetch_array($full_result_idiom)) {
							$num_idiom=$num_idiom+1;
	echo "<hr class='line_thin'>";
	echo '<p class="blue_big">იდიომი '.$num_idiom.'<span class="idiom_delete" id="'.$row_idiom['id'].'"> DELETE</span></p>';			
	echo '<textarea rows="5" cols="100" class="cf_idiom" name="'.$row_idiom['id'].'">'.$row_idiom['idiom'].'</textarea><br />';
	echo '<p class="blue">იდ. განმარტება:</p>';
	echo '<textarea rows="5" cols="100" class="cf_idiom_in" name="'.$row_idiom['id'].'">'.$row_idiom['idiom_interpretation'].'</textarea><br />';
	echo '<p class="blue">იდ. მაგალითი:</p>';					
	echo '<textarea rows="5" cols="100" class="cf_idiom_use" name="'.$row_idiom['id'].'">'.$row_idiom['idiom_use'].'</textarea><br />';

							}
					}
					
			echo "<p class='green MTop25'><span class='idiom_add' id=".$row_use['id'].">ADD IDIOM</span></p>";
				echo "<br />";} //KONEC TOLKOVANIE
			echo "<hr>";	
			echo "<p class='use_add' id=".$c_id."><span class='green'>ADD USE</span></p>";
				
			if ($row['part_of_speech_id']==1) Noun($row['id'],$row['word']);
			if ($row['part_of_speech_id']==2) Adjective($row['id'],$row['word']);
			if ($row['part_of_speech_id']==3) Numeral($row['id'],$row['word']);
			if ($row['part_of_speech_id']==4) Pronoun($row['id'],$row['word']);
			if ($row['part_of_speech_id']==5) Verb($row['id'],$row['word']);
			if ($row['part_of_speech_id']==6) Verbnoun($row['id'],$row['word']);
			if ($row['part_of_speech_id']==7) Participle($row['id'],$row['word']);
			if ($row['part_of_speech_id']==8) Adverb($row['id'],$row['word']);
			if ($row['part_of_speech_id']==9) Particle($row['id'],$row['word']);
			if ($row['part_of_speech_id']==10) Conjunction($row['id'],$row['word']);
			if ($row['part_of_speech_id']==11) Postposition($row['id'],$row['word']);
			if ($row['part_of_speech_id']==12) Interjection($row['id'],$row['word']);					
			echo "<br />";

}
function Noun($p1,$p2) {
global $CONNECT;
	$full_query_noun="SELECT * FROM `noun` WHERE `word_Id`=".$p1;
	$full_result_noun = mysqli_query($CONNECT, $full_query_noun) or die ("Oshibka " . mysqli_error($CONNECT));
				$row_noun=mysqli_fetch_array($full_result_noun);

				echo "<hr>";
				echo "<p class='blue'>ბრუნება</p>";
				echo "<table class='table70'>";
				echo "<tr><td>&nbsp;</td><td>მხოლობითი რიცხვი</td><td>მრავლობითი რიცხვი</td></tr>";
echo "<tr><td>სახელობითი</td><td>".$p2."</td><td><input type='text' maxlength='45' class='cf_noun2' value='".$row_noun['nominative_p']."'></td></tr>";
echo "<tr><td>მოთხრობითი</td><td><input type='text' maxlength='45' class='cf_noun3' value='".$row_noun['ergative_s']."'></td><td>       <input type='text' maxlength='45' class='cf_noun4' value='".$row_noun['ergative_p']."'></td></tr>";
echo "<tr><td>მიცემითი</td><td>   <input type='text' maxlength='45' class='cf_noun5' value='".$row_noun['dative_s']."'></td><td>         <input type='text' maxlength='45' class='cf_noun6' value='".$row_noun['dative_p']."'></td></tr>";
echo "<tr><td>ნათესაობითი</td><td><input type='text' maxlength='45' class='cf_noun7' value='".$row_noun['genetive_s']."'></td><td>       <input type='text' maxlength='45' class='cf_noun8' value='".$row_noun['genetive_p']."'></td></tr>";
echo "<tr><td>მოქმედებითი</td><td><input type='text' maxlength='45' class='cf_noun9' value='".$row_noun['instrumental_s']."'></td><td>   <input type='text' maxlength='45' class='cf_noun10' value='".$row_noun['instrumental_p']."'></td></tr>";
echo "<tr><td>ვითარებითი</td><td> <input type='text' maxlength='45' class='cf_noun11' value='".$row_noun['transformative_s']."'></td><td><input type='text' maxlength='45' class='cf_noun12' value='".$row_noun['transformative_p']."'></td></tr>";
//echo "<tr><td>წოდებითი</td><td>   <input type='text' maxlength='45' class='cf_noun13' value='".$row_noun['vocative_s']."'></td><td>      <input type='text' maxlength='45' class='cf_noun14' value='".$row_noun['vacative_p']."'></td></tr>";
				echo "</table><br /><br />";
				echo '<p class="noun" name="'.$p1.'"><span class="red">ZAPISAT</span></p><br />';
}
function Numeral($p1,$p2) {
global $CONNECT;
	$full_query_numeral="SELECT * FROM `numeral` WHERE `word_Id`=".$p1;
	$full_result_numeral = mysqli_query($CONNECT, $full_query_numeral) or die ("Oshibka " . mysqli_error($CONNECT));
				$row_numeral=mysqli_fetch_array($full_result_numeral);
				
				echo "<hr>";

				echo "<p class='blue'>გრამატიკული დახასიათება</p>";
				//if ($row_numeral['kind']<>"–") echo "ჯგუფი - <input type='text' maxlength='45' class='cf_numeral1' value='".$row_numeral['kind']."'><br />";
				
echo "<span class='bold'>ჯგუფი - </span>";
	echo '<select size="1" class="cf_numeral1">';	
	switch ($row_numeral['kind']) {
        case 0:
	echo '		<option value="0" name="0" selected="selected">-</option>';	
 	echo '		<option value="1" name="1">რაოდენობითი</option>';
	echo '		<option value="2" name="2">რიგობითი</option>';
	echo '		<option value="3" name="3">წილობითი</option>';
	echo '		<option value="4" name="4">განუსაზღვრელობითი</option>';
        break;		
        case 1:
	echo '		<option value="0" name="0">-</option>';	
 	echo '		<option value="1" name="1" selected="selected">რაოდენობითი</option>';
	echo '		<option value="2" name="2">რიგობითი</option>';
	echo '		<option value="3" name="3">წილობითი</option>';
	echo '		<option value="4" name="4">განუსაზღვრელობითი</option>';
        break;
    case 2:
	echo '		<option value="0" name="0">-</option>';	
 	echo '		<option value="1" name="1">რაოდენობითი</option>';
	echo '		<option value="2" name="2" selected="selected">რიგობითი</option>';
	echo '		<option value="3" name="3">წილობითი</option>';
	echo '		<option value="4" name="4">განუსაზღვრელობითი</option>';
        break;
    case 3:
	echo '		<option value="0" name="0">-</option>';	
 	echo '		<option value="1" name="1">რაოდენობითი</option>';
	echo '		<option value="2" name="2">რიგობითი</option>';
	echo '		<option value="3" name="3" selected="selected">წილობითი</option>';
	echo '		<option value="4" name="4">განუსაზღვრელობითი</option>';
        break;
    case 4:
	echo '		<option value="0" name="0">-</option>';	
 	echo '		<option value="1" name="1">რაოდენობითი</option>';
	echo '		<option value="2" name="2">რიგობითი</option>';
	echo '		<option value="3" name="3">წილობითი</option>';
	echo '		<option value="4" name="4" selected="selected">განუსაზღვრელობითი</option>';
        break;		
	};
	echo '</select><br /><br />';				
				
				
				echo "<p class='blue'>ბრუნება</p>";
				echo "<table class='table70'>";
				echo "<tr><td>&nbsp;</td><td>მხოლობითი რიცხვი</td></tr>";
				echo "<tr><td>სახელობითი</td><td><span class='bold'>".$p2."</span></td></tr>";
				echo "<tr><td>მოთხრობითი</td><td><input type='text' maxlength='45' class='cf_numeral2' value='".$row_numeral['ergative_s']."'</td></tr>";
				echo "<tr><td>მიცემითი</td><td><input type='text' maxlength='45' class='cf_numeral3' value='".$row_numeral['dative_s']."'</td></tr>";
				echo "<tr><td>ნათესაობითი</td><td><input type='text' maxlength='45' class='cf_numeral4' value='".$row_numeral['genetive_s']."'</td></tr>";
				echo "<tr><td>მოქმედებითი</td><td><input type='text' maxlength='45' class='cf_numeral5' value='".$row_numeral['instrumental_s']."'</td></tr>";
				echo "<tr><td>ვითარებითი</td><td><input type='text' maxlength='45' class='cf_numeral6' value='".$row_numeral['transformative_s']."'</td></tr>";
				//echo "<tr><td>წოდებითი</td><td><input type='text' maxlength='45' class='cf_numeral7' value='".$row_numeral['vocative_s']."'</td></tr>";
				echo "</table>";
				echo '<p class="numeral" name="'.$p1.'"><span class="red">ZAPISAT</span></p><br />';
				
			
}

function Adjective($p1,$p2) {
global $CONNECT;
	$full_query_noun="SELECT * FROM `adjective` WHERE `word_Id`=".$p1;
	$full_result_noun = mysqli_query($CONNECT, $full_query_noun) or die ("Oshibka " . mysqli_error($CONNECT));
				$row_noun=mysqli_fetch_array($full_result_noun);
				
				echo "<hr>";				
				echo "<p class='blue'>გრამატიკული დახასიათება</p>";
				
				echo "<p class='blue'>ხარისხის ფორმები</p>";
				echo "<span class='bold'>დადებითი - </span><input type='text' maxlength='45' class='cf_adjec1' value='".$row_noun['normal_degree']."'></span><br />";
				echo "<span class='bold'>ოდნაობითი - </span><input type='text' maxlength='45' class='cf_adjec2' value='".$row_noun['comparative_degree']."'><br />";
				echo "<span class='bold'>უფროობითი - </span><input type='text' maxlength='45' class='cf_adjec3' value='".$row_noun['superlative_degree']."'></div>";
			
				echo "<p class='blue'>ბრუნება</p>";
				echo "<table class='table70'>";
				echo "<tr><td>&nbsp;</td><td>მხოლობითი რიცხვი</td><td>ზედსართავი და არსებითი</td></tr>";
				echo "<tr><td>სახელობითი</td><td><span class='bold'>".$p2."</span></td><td><input type='text' maxlength='45' class='cf_adjec10' value='".$row_noun['nominative_p']."'></td></tr>";
				echo "<tr><td>მოთხრობითი</td><td><input type='text' maxlength='45' class='cf_adjec4' value='".$row_noun['ergative_s']."'</td><td><input type='text' maxlength='45' class='cf_adjec11' value='".$row_noun['ergative_p']."'></td></tr>";
				echo "<tr><td>მიცემითი</td><td><input type='text' maxlength='45' class='cf_adjec5' value='".$row_noun['dative_s']."'</td><td><input type='text' maxlength='45' class='cf_adjec12' value='".$row_noun['dative_p']."'></td></tr>";
				echo "<tr><td>ნათესაობითი</td><td><input type='text' maxlength='45' class='cf_adjec6' value='".$row_noun['genetive_s']."'</td><td><input type='text' maxlength='45' class='cf_adjec13' value='".$row_noun['genetive_p']."'></td></tr>";
				echo "<tr><td>მოქმედებითი</td><td><input type='text' maxlength='45' class='cf_adjec7' value='".$row_noun['instrumental_s']."'</td><td><input type='text' maxlength='45' class='cf_adjec14' value='".$row_noun['instrumental_p']."'></td></tr>";
				echo "<tr><td>ვითარებითი</td><td><input type='text' maxlength='45' class='cf_adjec8' value='".$row_noun['transformative_s']."'</td><td><input type='text' maxlength='45' class='cf_adjec15' value='".$row_noun['transformative_p']."'</td></tr>";
				//echo "<tr><td>წოდებითი</td><td><input type='text' maxlength='45' class='cf_adjec9' value='".$row_noun['vocative_s']."'</td><td><input type='text' maxlength='45' class='cf_adjec16' value='".$row_noun['vocative_p']."'</td></tr>";
				echo "</table>";
				echo '<p class="adjective" name="'.$p1.'"><span class="red">ZAPISAT</span></p><br />';				
			
}

function Pronoun($p1,$p2) {
global $CONNECT;
	$full_query_noun="SELECT * FROM `pronoun` WHERE `word_Id`=".$p1;
	$full_result_noun = mysqli_query($CONNECT, $full_query_noun) or die ("Oshibka " . mysqli_error($CONNECT));
				$row_noun=mysqli_fetch_array($full_result_noun);
				
				echo "<hr>";
				echo "<p class='blue'>გრამატიკული დახასიათება</p>";
				echo "<span class='bold'>ჯგუფი - </span><input type='text' maxlength='45' class='cf_pronoun1' value='".$row_noun['characteristic']."'><br />";
				echo "<p class='blue'>ბრუნება</p>";
				
				echo "<table class='table70'>";
				echo "<tr><td>&nbsp;</td><td>მხოლობითი რიცხვი</td><td>მრავლობითი რიცხვი</td></tr>";
				echo "<tr><td>სახელობითი</td><td><span class='bold'>".$p2."</span></td><td><input type='text' maxlength='45' class='cf_pronoun8' value='".$row_noun['nominative_p']."'></td></tr>";
				echo "<tr><td>მოთხრობითი</td><td><input type='text' maxlength='45' class='cf_pronoun2' value='".$row_noun['ergative_s']."'></td><td><input type='text' maxlength='45' class='cf_pronoun9' value='".$row_noun['ergative_p']."'></td></tr>";
				echo "<tr><td>მიცემითი</td><td><input type='text' maxlength='45' class='cf_pronoun3' value='".$row_noun['dative_s']."'></td><td><input type='text' maxlength='45' class='cf_pronoun10' value='".$row_noun['dative_p']."'</td></tr>";
				echo "<tr><td>ნათესაობითი</td><td><input type='text' maxlength='45' class='cf_pronoun4' value='".$row_noun['genetive_s']."'></td><td><input type='text' maxlength='45' class='cf_pronoun11' value='".$row_noun['genetive_p']."'></td></tr>";
				echo "<tr><td>მოქმედებითი</td><td><input type='text' maxlength='45' class='cf_pronoun5' value='".$row_noun['instrumental_s']."'></td><td><input type='text' maxlength='45' class='cf_pronoun12' value='".$row_noun['instrumental_p']."'></td></tr>";
				echo "<tr><td>ვითარებითი</td><td><input type='text' maxlength='45' class='cf_pronoun6' value='".$row_noun['transformative_s']."'></td><td><input type='text' maxlength='45' class='cf_pronoun13' value='".$row_noun['transformative_p']."'></td></tr>";
				//echo "<tr><td>წოდებითი</td><td><input type='text' maxlength='45' class='cf_pronoun7' value='".$row_noun['vocative_s']."'></td><td><input type='text' maxlength='45' class='cf_pronoun14' value='".$row_noun['vocative_p']."'></td></tr>";
				echo "</table>";
				echo '<p class="pronoun" name="'.$p1.'"><span class="red">ZAPISAT</span></p><br />';				
				
}

function Verbnoun($p1,$p2) {
global $CONNECT;
	$full_query_noun="SELECT * FROM `verbnoun` WHERE `word_Id`=".$p1;
	$full_result_noun = mysqli_query($CONNECT, $full_query_noun) or die ("Oshibka " . mysqli_error($CONNECT));
				$row_noun=mysqli_fetch_array($full_result_noun);
				
				echo "<hr>";
				echo "<p class='blue'>გრამატიკული დახასიათება</p>";
				echo "<span class='bold'>ზმნ(ებ)ისა - </span><input type='text' maxlength='100' class='cf_verbnoun1' value='".$row_noun['verb']."'><br />";
				echo "<p class='blue'>ბრუნება</p>";
				echo "<table class='table50'>";
				echo "<tr><td>&nbsp;</td><td>მხოლობითი რიცხვი</td></tr>";
				echo "<tr><td>სახელობითი</td><td><span class='bold'>".$p2."</span></td></tr>";
				echo "<tr><td>მოთხრობითი</td><td><input type='text' maxlength='45' class='cf_verbnoun2' value='".$row_noun['ergative_s']."'></td></tr>";
				echo "<tr><td>მიცემითი</td><td><input type='text' maxlength='45' class='cf_verbnoun3' value='".$row_noun['dative_s']."'></td></tr>";
				echo "<tr><td>ნათესაობითი</td><td><input type='text' maxlength='45' class='cf_verbnoun4' value='".$row_noun['genetive_s']."'></td></tr>";
				echo "<tr><td>მოქმედებითი</td><td><input type='text' maxlength='45' class='cf_verbnoun5' value='".$row_noun['instrumental_s']."'></td></tr>";
				echo "<tr><td>ვითარებითი</td><td><input type='text' maxlength='45' class='cf_verbnoun6' value='".$row_noun['transformative_s']."'></td></tr>";
				//echo "<tr><td>წოდებითი</td><td><input type='text' maxlength='45' class='cf_verbnoun7' value='".$row_noun['vocative_s']."'></td></tr>";
				echo "</table>";
				echo '<p class="verbnoun" name="'.$p1.'"><span class="red">ZAPISAT</span></p><br />';
				
}


function Verb($p1,$p2) {
global $CONNECT;
	$full_query_noun = "SELECT * FROM `verb` WHERE `word_Id`=" . $p1;
	$full_result_noun = mysqli_query($CONNECT, $full_query_noun) or die ("Oshibka " . mysqli_error($CONNECT));
	$row_noun=mysqli_fetch_array($full_result_noun);
				
							
				echo "<hr>";
				
echo "<p class='blue'>გრამატიკული დახასიათება</p>";
echo "<span class='bold'>საწყისი - </span><input type='text' maxlength='100' class='cf_verb1' value='".$row_noun['infinitive']."'><br />";
echo "<span class='bold'>გარდამავლობა - </span>";
echo '<select size="1" id="cf_verb2">';	
	switch ($row_noun['transilive_intransilive']) {
        case 0:
	echo '		<option value="0" name="0" selected="selected">-</option>';	
 	echo '		<option value="1" name="1">გარდამავალი</option>';
	echo '		<option value="2" name="2">გარდაუვალი</option>';
	echo '		<option value="3" name="3">პირნაკლი</option>';
        break;		
        case 1:
	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1" selected="selected">გარდამავალი</option>';
	echo '		<option value="2" name="2">გარდაუვალი</option>';
	echo '		<option value="3" name="3">პირნაკლი</option>';
        break;
    case 2:
	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">გარდამავალი</option>';
	echo '		<option value="2" name="2" selected="selected">გარდაუვალი</option>';
	echo '		<option value="3" name="3">პირნაკლი</option>';
        break;
    case 3:
	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">გარდამავალი</option>';
	echo '		<option value="2" name="2">გარდაუვალი</option>';
	echo '		<option value="3" name="3" selected="selected">პირნაკლი</option>';
        break;		
	};
	echo '</select><br />';
	
	echo "<span class='bold'>გვარი - </span>";
	echo '<select size="1" id="cf_verb3">';	
	switch ($row_noun['voice']) {
		 case 0:
 	echo '		<option value="0" name="0" selected="selected">-</option>';
 	echo '		<option value="1" name="1">მოქმედებითი</option>';
	echo '		<option value="2" name="2">დინამიკური ვნებითი</option>';
	echo '		<option value="3" name="3">სტატიკური ვნებითი</option>';
	echo '		<option value="4" name="4">საშუალ-მოქმედებითი</option>';
 	echo '		<option value="5" name="5">საშუალ-ვნებითი</option>';
	echo '		<option value="6" name="6">უგვარო</option>';
       break;
        case 1:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1" selected="selected">მოქმედებითი</option>';
	echo '		<option value="2" name="2">დინამიკური ვნებითი</option>';
	echo '		<option value="3" name="3">სტატიკური ვნებითი</option>';
	echo '		<option value="4" name="4">საშუალ-მოქმედებითი</option>';
 	echo '		<option value="5" name="5">საშუალ-ვნებითი</option>';
	echo '		<option value="6" name="6">უგვარო</option>';
       break;
    case 2:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">მოქმედებითი</option>';
	echo '		<option value="2" name="2" selected="selected">დინამიკური ვნებითი</option>';
	echo '		<option value="3" name="3">სტატიკური ვნებითი</option>';
	echo '		<option value="4" name="4">საშუალ-მოქმედებითი</option>';
 	echo '		<option value="5" name="5">საშუალ-ვნებითი</option>';
	echo '		<option value="6" name="6">უგვარო</option>';
        break;
    case 3:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">მოქმედებითი</option>';
	echo '		<option value="2" name="2">დინამიკური ვნებითი</option>';
	echo '		<option value="3" name="3" selected="selected">სტატიკური ვნებითი</option>';
	echo '		<option value="4" name="4">საშუალ-მოქმედებითი</option>';
 	echo '		<option value="5" name="5">საშუალ-ვნებითი</option>';
	echo '		<option value="6" name="6">უგვარო</option>';
        break;
    case 4:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">მოქმედებითი</option>';
	echo '		<option value="2" name="2">დინამიკური ვნებითი</option>';
	echo '		<option value="3" name="3">სტატიკური ვნებითი</option>';
	echo '		<option value="4" name="4" selected="selected">საშუალ-მოქმედებითი</option>';
 	echo '		<option value="5" name="5">საშუალ-ვნებითი</option>';
	echo '		<option value="6" name="6">უგვარო</option>';
        break;
   		case 5:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">მოქმედებითი</option>';
	echo '		<option value="2" name="2">დინამიკური ვნებითი</option>';
	echo '		<option value="3" name="3">სტატიკური ვნებითი</option>';
	echo '		<option value="4" name="4">საშუალ-მოქმედებითი</option>';
 	echo '		<option value="5" name="5" selected="selected">საშუალ-ვნებითი</option>';
	echo '		<option value="6" name="6">უგვარო</option>';
        break;
		case 6:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">მოქმედებითი</option>';
	echo '		<option value="2" name="2">დინამიკური ვნებითი</option>';
	echo '		<option value="3" name="3">სტატიკური ვნებითი</option>';
	echo '		<option value="4" name="4">საშუალ-მოქმედებითი</option>';
 	echo '		<option value="5" name="5">საშუალ-ვნებითი</option>';
	echo '		<option value="6" name="6" selected="selected">უგვარო</option>';
        break;
	
	};
	echo '</select><br />';

	echo "<span class='bold'>თავისებურება - </span>";
	echo '<select size="1" id="cf_verb15">';	
	switch ($row_noun['peculiarity']) {
        case 0:
			echo '		<option value="0" name="0" selected="selected">-</option>';	
			echo '		<option value="1" name="1">არ არის გამონაკლისი</option>';
			echo '		<option value="2" name="2">გამონაკლისი</option>';
        break;		
        case 1:
			echo '		<option value="0" name="0">-</option>';	
			echo '		<option value="1" name="1" selected="selected">არ არის გამონაკლისი</option>';
			echo '		<option value="2" name="2">გამონაკლისი</option>';
        break;
        case 2:
			echo '		<option value="0" name="0">-</option>';	
			echo '		<option value="1" name="1">არ არის გამონაკლისი</option>';
			echo '		<option value="2" name="2" selected="selected">გამონაკლისი</option>';
        break;
	};
	echo '</select><br />';









echo "<p class='blue'>უღლება</p>";

				echo "<table class='table70'>";

				echo "<tr><td>აწმყო</td><td><input type='text' maxlength='250' class='cf_verb4' value='".$row_noun['present_lindicative']."'></td></tr>";
				echo "<tr><td>უწყვეტელი</td><td><input type='text' maxlength='250' class='cf_verb5' value='".$row_noun['imperfect']."'></td></tr>";
				echo "<tr><td>აწმყოს კავშირებითი</td><td><input type='text' maxlength='250' class='cf_verb6' value='".$row_noun['present_stubjunctive']."'></td></tr>";
				echo "<tr><td>მყოფადი</td><td><input type='text' maxlength='250' class='cf_verb7' value='".$row_noun['future']."'></td></tr>";
				echo "<tr><td>ხოლმეობითი</td><td><input type='text' maxlength='250' class='cf_verb8' value='".$row_noun['conditional']."'></td></tr>";
				echo "<tr><td>მყოფადის კავშირებითი</td><td><input type='text' maxlength='250' class='cf_verb9' value='".$row_noun['future_subjunctive']."'></td></tr>";
				echo "<tr><td>წყვეტილი</td><td><input type='text' maxlength='250' class='cf_verb10' value='".$row_noun['aorist']."'></td></tr>";
				echo "<tr><td>II კავშირებითი</td><td><input type='text' maxlength='250' class='cf_verb11' value='".$row_noun['conjuctive_II']."'></td></tr>";
				echo "<tr><td>I თურმეობითი</td><td><input type='text' maxlength='250' class='cf_verb12' value='".$row_noun['resultative_I']."'></td></tr>";
				echo "<tr><td>II თურმეობითი</td><td><input type='text' maxlength='250' class='cf_verb13' value='".$row_noun['resultative_II']."'></td></tr>";
				echo "<tr><td>III კავშირებითი</td><td><input type='text' maxlength='250' class='cf_verb14' value='".$row_noun['conjuctive_III']."'></td></tr>";
				echo "</table>'";
				echo '<p class="verb" name="'.$p1.'"><span class="red">ZAPISAT</span></p><br />';
}
function Participle($p1,$p2) {
global $CONNECT;
	$full_query_noun="SELECT * FROM `participle` WHERE `word_Id`=".$p1;
	$full_result_noun = mysqli_query($CONNECT, $full_query_noun) or die ("Oshibka " . mysqli_error($CONNECT));
				$row_noun=mysqli_fetch_array($full_result_noun);
				
				echo "<hr>";				
				echo "<p class='blue'>გრამატიკული დახასიათება</p>";
			
				echo "<span class='bold'>ზმნ(ებ)ისა - </span><input size='30' type='text' maxlength='250' class='cf_participle1' value='".$row_noun['verb']."'><br />";
				echo "<span class='bold'>გვარი -  </span>";
		//<input type='text' maxlength='45' class='cf_participle2' value='".$row_noun['voice']."'><br />";
		
	echo '<select size="1" class="cf_participle2">';	
	switch ($row_noun['voice']) {
		case 0:
 	echo '		<option value="0" name="0" selected="selected">-</option>';
 	echo '		<option value="1" name="1">მოქმედებითი</option>';
	echo '		<option value="2" name="2">ვნებითი,წარსული დრო (1)</option>';
	echo '		<option value="3" name="3">ვნებითი,წარსული დრო (2)</option>';
	echo '		<option value="4" name="4">ვნებითი, მომავალი დრო</option>';
 	echo '		<option value="5" name="5">ვნებითი, უარყოფითი ფორმა</option>';
	echo '		<option value="6" name="6">საშუალი</option>';
       break;		
		case 1:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1" selected="selected">მოქმედებითი</option>';
	echo '		<option value="2" name="2">ვნებითი,წარსული დრო (1)</option>';
	echo '		<option value="3" name="3">ვნებითი,წარსული დრო (2)</option>';
	echo '		<option value="4" name="4">ვნებითი, მომავალი დრო</option>';
 	echo '		<option value="5" name="5">ვნებითი, უარყოფითი ფორმა</option>';
	echo '		<option value="6" name="6">საშუალი</option>';
       break;		
		case 2:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">მოქმედებითი</option>';
	echo '		<option value="2" name="2" selected="selected">ვნებითი,წარსული დრო (1)</option>';
	echo '		<option value="3" name="3">ვნებითი,წარსული დრო (2)</option>';
	echo '		<option value="4" name="4">ვნებითი, მომავალი დრო</option>';
 	echo '		<option value="5" name="5">ვნებითი, უარყოფითი ფორმა</option>';
	echo '		<option value="6" name="6">საშუალი</option>';
       break;		
		case 3:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">მოქმედებითი</option>';
	echo '		<option value="2" name="2">ვნებითი,წარსული დრო (1)</option>';
	echo '		<option value="3" name="3" selected="selected">ვნებითი,წარსული დრო (2)</option>';
	echo '		<option value="4" name="4">ვნებითი, მომავალი დრო</option>';
 	echo '		<option value="5" name="5">ვნებითი, უარყოფითი ფორმა</option>';
	echo '		<option value="6" name="6">საშუალი</option>';
       break;		
		case 4:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">მოქმედებითი</option>';
	echo '		<option value="2" name="2">ვნებითი,წარსული დრო (1)</option>';
	echo '		<option value="3" name="3">ვნებითი,წარსული დრო (2)</option>';
	echo '		<option value="4" name="4" selected="selected">ვნებითი, მომავალი დრო</option>';
 	echo '		<option value="5" name="5">ვნებითი, უარყოფითი ფორმა</option>';
	echo '		<option value="6" name="6">საშუალი</option>';
       break;		
		case 5:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">მოქმედებითი</option>';
	echo '		<option value="2" name="2">ვნებითი,წარსული დრო (1)</option>';
	echo '		<option value="3" name="3">ვნებითი,წარსული დრო (2)</option>';
	echo '		<option value="4" name="4">ვნებითი, მომავალი დრო</option>';
 	echo '		<option value="5" name="5" selected="selected">ვნებითი, უარყოფითი ფორმა</option>';
	echo '		<option value="6" name="6">საშუალი</option>';
       break;		
		case 6:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">მოქმედებითი</option>';
	echo '		<option value="2" name="2">ვნებითი,წარსული დრო (1)</option>';
	echo '		<option value="3" name="3">ვნებითი,წარსული დრო (2)</option>';
	echo '		<option value="4" name="4">ვნებითი, მომავალი დრო</option>';
 	echo '		<option value="5" name="5">ვნებითი, უარყოფითი ფორმა</option>';
	echo '		<option value="6" name="6" selected="selected">საშუალი</option>';
       break;		
	}
	echo '</select><br />';		
				echo "<p class='blue'>ბრუნება</p>";
				echo "<table class='table70'>";
				echo "<tr><td>&nbsp;</td><td>მხოლობითი რიცხვი</td><td>მრავლობითი რიცხვი</td></tr>";
				echo "<tr><td>სახელობითი</td><td><span class='bold'>".$p2."</span></td><td><input type='text' maxlength='45' class='cf_participle9' value='".$row_noun['nominative_p']."'></td></tr>";
				echo "<tr><td>მოთხრობითი</td><td><input type='text' maxlength='45' class='cf_participle3' value='".$row_noun['ergative_s']."'></td><td><input type='text' maxlength='45' class='cf_participle10' value='".$row_noun['ergative_p']."'></td></tr>";
				echo "<tr><td>მიცემითი</td><td><input type='text' maxlength='45' class='cf_participle4' value='".$row_noun['dative_s']."'></td><td><input type='text' maxlength='45' class='cf_participle11' value='".$row_noun['dative_p']."'></td></tr>";
				echo "<tr><td>ნათესაობითი</td><td><input type='text' maxlength='45' class='cf_participle5' value='".$row_noun['genetive_s']."'></td><td><input type='text' maxlength='45' class='cf_participle12' value='".$row_noun['genetive_p']."'></td></tr>";
				echo "<tr><td>მოქმედებითი</td><td><input type='text' maxlength='45' class='cf_participle6' value='".$row_noun['instrumental_s']."'></td><td><input type='text' maxlength='45' class='cf_participle13' value='".$row_noun['instrumental_p']."'></td></tr>";
				echo "<tr><td>ვითარებითი</td><td><input type='text' maxlength='45' class='cf_participle7' value='".$row_noun['transformative_s']."'></td><td><input type='text' maxlength='45' class='cf_participle14' value='".$row_noun['transformative_p']."'></td></tr>";
				//echo "<tr><td>წოდებითი</td><td><input type='text' maxlength='45' class='cf_participle8' value='".$row_noun['vocative_s']."'></td><td><input type='text' maxlength='45' class='cf_participle15' value='".$row_noun['vocative_p']."'></td></tr>";
				echo "</table>";
				echo '<p class="participle" name="'.$p1.'"><span class="red">ZAPISAT</span></p><br />';

}
function Adverb($p1,$p2) {
global $CONNECT;
	$full_query_noun="SELECT * FROM `adverb` WHERE `word_Id`=".$p1;
	$full_result_noun = mysqli_query($CONNECT, $full_query_noun) or die ("Oshibka " . mysqli_error($CONNECT));
				$row_noun=mysqli_fetch_array($full_result_noun);

				echo "<hr>";				
		echo "<p class='blue'>სემანტიკური ჯგუფი</p>";
		echo "<input size='50' type='text' maxlength='200' class='cf_adverb' value='".$row_noun['semantic_group']."'></div>";
		echo '<p class="adverb" name="'.$p1.'"><span class="red">ZAPISAT</span></p><br />';
}


function Postposition($p1,$p2) {
global $CONNECT;
	$full_query_noun="SELECT * FROM `postposition` WHERE `word_Id`=".$p1;
	$full_result_noun = mysqli_query($CONNECT, $full_query_noun) or die ("Oshibka " . mysqli_error($CONNECT));
				$row_noun=mysqli_fetch_array($full_result_noun);
		
				echo "<hr>";
echo "<p class='blue'>გრამატიკული დახასიათება</p>";
echo "<span class='bold'>გამოიყენება </span>";

	echo '<select size="1" id="cf_postposition">';	
	switch ($row_noun['case']) {
		case 0:
 	echo '		<option value="0" name="0" selected="selected">-</option>';
 	echo '		<option value="1" name="1">სახელობით ბრუნვასთან</option>';
	echo '		<option value="2" name="2">სახელობით/მიცემით ბრუნვებთან</option>';
	echo '		<option value="3" name="3">მიცემით ბრუნვასთან</option>';
	echo '		<option value="4" name="4">მიცემით/ნათესაობით ბრუნვებთან</option>';
 	echo '		<option value="5" name="5">ნათესაობით ბრუნვასთან</option>';
	echo '		<option value="6" name="6">ნათესაობით/მოქმედებით ბრუნვებთან</option>';
	echo '		<option value="7" name="7">მოქმედებით ბრუნვასთან</option>';
	echo '		<option value="8" name="8">ვითარებით ბრუნვასთან</option>';
       break;
        case 1:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1" selected="selected">სახელობით ბრუნვასთან</option>';
	echo '		<option value="2" name="2">სახელობით/მიცემით ბრუნვებთან</option>';
	echo '		<option value="3" name="3">მიცემით ბრუნვასთან</option>';
	echo '		<option value="4" name="4">მიცემით/ნათესაობით ბრუნვებთან</option>';
 	echo '		<option value="5" name="5">ნათესაობით ბრუნვასთან</option>';
	echo '		<option value="6" name="6">ნათესაობით/მოქმედებით ბრუნვებთან</option>';
	echo '		<option value="7" name="7">მოქმედებით ბრუნვასთან</option>';
	echo '		<option value="8" name="8">ვითარებით ბრუნვასთან</option>';
       break;
    case 2:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">სახელობით ბრუნვასთან</option>';
	echo '		<option value="2" name="2" selected="selected">სახელობით/მიცემით ბრუნვებთან</option>';
	echo '		<option value="3" name="3">მიცემით ბრუნვასთან</option>';
	echo '		<option value="4" name="4">მიცემით/ნათესაობით ბრუნვებთან</option>';
 	echo '		<option value="5" name="5">ნათესაობით ბრუნვასთან</option>';
	echo '		<option value="6" name="6">ნათესაობით/მოქმედებით ბრუნვებთან</option>';
	echo '		<option value="7" name="7">მოქმედებით ბრუნვასთან</option>';
	echo '		<option value="8" name="8">ვითარებით ბრუნვასთან</option>';
        break;
    case 3:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">სახელობით ბრუნვასთან</option>';
	echo '		<option value="2" name="2">სახელობით/მიცემით ბრუნვებთან</option>';
	echo '		<option value="3" name="3" selected="selected">მიცემით ბრუნვასთან</option>';
	echo '		<option value="4" name="4">მიცემით/ნათესაობით ბრუნვებთან</option>';
 	echo '		<option value="5" name="5">ნათესაობით ბრუნვასთან</option>';
	echo '		<option value="6" name="6">ნათესაობით/მოქმედებით ბრუნვებთან</option>';
	echo '		<option value="7" name="7">მოქმედებით ბრუნვასთან</option>';
	echo '		<option value="8" name="8">ვითარებით ბრუნვასთან</option>';
        break;
    case 4:
 	echo '		<option value="0" name="0">-</option>';
  	echo '		<option value="1" name="1">სახელობით ბრუნვასთან</option>';
	echo '		<option value="2" name="2">სახელობით/მიცემით ბრუნვებთან</option>';
	echo '		<option value="3" name="3">მიცემით ბრუნვასთან</option>';
	echo '		<option value="4" name="4" selected="selected">მიცემით/ნათესაობით ბრუნვებთან</option>';
 	echo '		<option value="5" name="5">ნათესაობით ბრუნვასთან</option>';
	echo '		<option value="6" name="6">ნათესაობით/მოქმედებით ბრუნვებთან</option>';
	echo '		<option value="7" name="7">მოქმედებით ბრუნვასთან</option>';
	echo '		<option value="8" name="8">ვითარებით ბრუნვასთან</option>';
        break;
    case 5:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">სახელობით ბრუნვასთან</option>';
	echo '		<option value="2" name="2">სახელობით/მიცემით ბრუნვებთან</option>';
	echo '		<option value="3" name="3">მიცემით ბრუნვასთან</option>';
	echo '		<option value="4" name="4">მიცემით/ნათესაობით ბრუნვებთან</option>';
 	echo '		<option value="5" name="5" selected="selected">ნათესაობით ბრუნვასთან</option>';
	echo '		<option value="6" name="6">ნათესაობით/მოქმედებით ბრუნვებთან</option>';
	echo '		<option value="7" name="7">მოქმედებით ბრუნვასთან</option>';
	echo '		<option value="8" name="8">ვითარებით ბრუნვასთან</option>';
        break;
    case 6:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">სახელობით ბრუნვასთან</option>';
	echo '		<option value="2" name="2">სახელობით/მიცემით ბრუნვებთან</option>';
	echo '		<option value="3" name="3">მიცემით ბრუნვასთან</option>';
	echo '		<option value="4" name="4">მიცემით/ნათესაობით ბრუნვებთან</option>';
 	echo '		<option value="5" name="5">ნათესაობით ბრუნვასთან</option>';
	echo '		<option value="6" name="6" selected="selected">ნათესაობით/მოქმედებით ბრუნვებთან</option>';
	echo '		<option value="7" name="7">მოქმედებით ბრუნვასთან</option>';
	echo '		<option value="8" name="8">ვითარებით ბრუნვასთან</option>';
        break;
    case 7:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">სახელობით ბრუნვასთან</option>';
	echo '		<option value="2" name="2">სახელობით/მიცემით ბრუნვებთან</option>';
	echo '		<option value="3" name="3">მიცემით ბრუნვასთან</option>';
	echo '		<option value="4" name="4">მიცემით/ნათესაობით ბრუნვებთან</option>';
 	echo '		<option value="5" name="5">ნათესაობით ბრუნვასთან</option>';
	echo '		<option value="6" name="6">ნათესაობით/მოქმედებით ბრუნვებთან</option>';
	echo '		<option value="7" name="7" selected="selected">მოქმედებით ბრუნვასთან</option>';
	echo '		<option value="8" name="8">ვითარებით ბრუნვასთან</option>';
        break;
    case 8:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">სახელობით ბრუნვასთან</option>';
	echo '		<option value="2" name="2">სახელობით/მიცემით ბრუნვებთან</option>';
	echo '		<option value="3" name="3">მიცემით ბრუნვასთან</option>';
	echo '		<option value="4" name="4">მიცემით/ნათესაობით ბრუნვებთან</option>';
 	echo '		<option value="5" name="5">ნათესაობით ბრუნვასთან</option>';
	echo '		<option value="6" name="6">ნათესაობით/მოქმედებით ბრუნვებთან</option>';
	echo '		<option value="7" name="7">მოქმედებით ბრუნვასთან</option>';
	echo '		<option value="8" name="8" selected="selected">ვითარებით ბრუნვასთან</option>';
        break;
	}
	echo '</select><br />';
	echo '<p class="postposition" name="'.$p1.'"><span class="red">ZAPISAT</span></p><br />';

}

function Conjunction($p1,$p2) {
global $CONNECT;
	$full_query_noun="SELECT * FROM `conjunction` WHERE `word_Id`=".$p1;
	$full_result_noun = mysqli_query($CONNECT, $full_query_noun) or die ("Oshibka " . mysqli_error($CONNECT));
				$row_noun=mysqli_fetch_array($full_result_noun);
			
				echo "<hr>";
echo "<p class='blue'>გრამატიკული დახასიათება</p>";
//echo "<span class='bold'>ჯგუფი - </span><input type='text' maxlength='200' class='cf_conjunction' value='".$row_noun['semantic_group']."'>";

	echo '<select size="1" class="cf_conjunction">';	
	switch ($row_noun['semantic_group']) {
		case 0:
 	echo '		<option value="0" name="0" selected="selected">-</option>';
 	echo '		<option value="1" name="1">მაერთებელი</option>';
	echo '		<option value="2" name="2">მაქვემდებარებელი</option>';
       break;
        case 1:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1" selected="selected">მაერთებელი</option>';
	echo '		<option value="2" name="2">მაქვემდებარებელი</option>';
       break;
    case 2:
 	echo '		<option value="0" name="0">-</option>';
 	echo '		<option value="1" name="1">მაერთებელი</option>';
	echo '		<option value="2" name="2" selected="selected">მაქვემდებარებელი</option>';
        break;
	}
	echo '</select><br /><br />';
	echo '<p class="conjunction" name="'.$p1.'"><span class="red">ZAPISAT</span></p><br />';
}
function Particle($p1,$p2) {
global $CONNECT;
	$full_query_noun="SELECT * FROM `particle` WHERE `word_Id`=".$p1;
	$full_result_noun = mysqli_query($CONNECT, $full_query_noun) or die ("Oshibka " . mysqli_error($CONNECT));
				$row_noun=mysqli_fetch_array($full_result_noun);
			
				echo "<hr>";
echo "<p class='blue'>სემანტიკური ჯგუფი</p>";
echo "<input size='50' type='text' maxlength='200' class='cf_particle' value='".$row_noun['semantic_group']."'>";
echo '<p class="particle" name="'.$p1.'"><span class="red">ZAPISAT</span></p><br />';
}

function Interjection($p1,$p2) {
global $CONNECT;
	$full_query_noun="SELECT * FROM `interjection` WHERE `word_Id`=".$p1;
	$full_result_noun = mysqli_query($CONNECT, $full_query_noun) or die ("Oshibka " . mysqli_error($CONNECT));
				$row_noun=mysqli_fetch_array($full_result_noun);
				
				
				echo "<hr>";

echo "<p class='blue'>სემანტიკური ჯგუფი</p>";
echo "<input size='50' type='text' maxlength='200' class='cf_interjection' value='".$row_noun['semantic_group']."'>";
echo '<p class="interjection" name="'.$p1.'"><span class="red">ZAPISAT</span></p><br />';
}









function query_build($tema, $A1, $A2, $A2plus, $B1, $B2, $C1, $WL, $part_of_speech, $word, $letter, $my_Query) { //echo "query_build";
	
	if (trim($part_of_speech) != "აირჩიეთ მეტყველების ნაწილი" and $part_of_speech !=13) { //проверяем часть речи
			if (!strpos($my_Query, "WHERE")) { //echo '<br />'."+ where chast rechi"; проверка есль ли WHERE
				$my_Query=$my_Query.' WHERE ';
				} else {
					$my_Query=$my_Query.' and ';
				};
		$my_Query=$my_Query.'`part_of_speech_id`='.$part_of_speech;
	}

	if (trim($tema) != "43") { //проверяем tema
			if (!strpos($my_Query, "WHERE")) { //echo '<br />'."+ where chast rechi"; проверка есль ли WHERE
				$my_Query=$my_Query.' WHERE ';
				} else {
					$my_Query=$my_Query.' and ';
				};
		$my_Query=$my_Query.'(`tema1`='.$tema.' OR `tema2`='.$tema.' OR `tema3`='.$tema.')' ;
	}

//echo "<br />= ".$my_Query."<br/>";
if ($A1 or $A2 or $A2plus or $B1 or $B2 or $C1 or $WL) { //провеояем уровни (все)
	if (!strpos($my_Query, "WHERE")) { // проверка есль ли WHERE
		$my_Query=$my_Query.' WHERE (';
		} else {
			$my_Query=$my_Query.' and (';
			};
			$my_Query=$my_Query.' (';
	if ($A1) $my_Query=$my_Query." `level`='A1'";
	if ($A1 and $A2) $my_Query=$my_Query.' or '; // проверка есль ли lev
	if ($A2) $my_Query=$my_Query." `level`='A2'";
	
	if (($A1 or $A2) and $A2plus) $my_Query=$my_Query.' or '; // проверка есль ли lev
	if ($A2plus) $my_Query=$my_Query." `level`='A2+'";
	
	
	if (($A1 or $A2 or $A2plus) and $B1) $my_Query=$my_Query.' or '; // проверка есль ли lev
	if ($B1) $my_Query=$my_Query." `level`='B1'";
	if (($A1 or $A2 or $A2plus or $B1) and $B2) $my_Query=$my_Query.' or '; // проверка есль ли lev
	if ($B2) $my_Query=$my_Query." `level`='B2'";
	if (($A1 or $A2 or $A2plus or $B1 or $B2) and $C1) $my_Query=$my_Query.' or '; // проверка есль ли lev
	if ($C1) $my_Query=$my_Query." `level`='C1'";
	if (($A1 or $A2 or $A2plus or $B1 or $B2 or $C1) and $WL) $my_Query=$my_Query.' or '; // проверка есль ли lev
	if ($WL) $my_Query=$my_Query." `level`=''";
//	if (strpos($my_Query, "lev") and $_POST['Originname']) $my_Query=$my_Query.' or '; // проверка есль ли lev
	
		//if (strpos($my_Query, "lev")) {//echo "проверка есль ли lev";
		$my_Query=$my_Query." )) ";
		//} 

	
}
	if ($word or $letter) { //проверяем отмечены ли буква или слово (запрос одинаковый)
			if (!strpos($my_Query, "WHERE")) { // проверка есль ли WHERE
				$my_Query=$my_Query.' WHERE ';
				} else {
					$my_Query=$my_Query.' and ';
				};
			
			if ($letter AND $letter!='ყველა ასო') $word=$letter;
			
		$my_Query=$my_Query.'( `word` LIKE "'.$word.'%" OR `word_view` LIKE "'.$word.'%")';
	}
			
$my_Query= $my_Query." GROUP BY words.word Order BY words.word_view"; //echo $my_Query;	 

// Formirovanie zaprosa konec

//echo "<br />= ".$my_Query."<br/>";
Word_Table($my_Query);
}



function FormChars($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}


function Head($page_title) {
	echo '<!DOCTYPE html><html><head><meta charset="utf-8" /><title>'.$page_title.'</title><meta name="keywords" content="" /><meta name="description" content="" /><link href="../resource/css/style.css" rel="stylesheet"><script src="../resource/js/jquery-1.7.1.js"></script><script src="../resource/js/myscripts.js"></script></head>';

}
function Head_er(){

	echo '<div class="header">
	<div class="shadow-head">
        <a href="#!/page_home"><img src="../resource/img/icon/logo.png" alt="" class="logo" /></a>
        <img src="../resource/img/header/saswavlo-leqsikoni.png" alt="" class="slogan" />
    </div>
	</div>';

}


function Alhpabet() {
	echo '<div class="abc">
		  <div class="let">ა</div>
		  <div class="let">ბ</div>
		  <div class="let">გ</div>
		  <div class="let">დ</div>
		  <div class="let">ე</div>
		  <div class="let">ვ</div>
		  <div class="let">ზ</div>
		  <div class="let">თ</div>
		  <div class="let">ი</div>
		  <div class="let">კ</div>
		  <div class="let">ლ</div>
		  <div class="let">მ</div>
		  <div class="let">ნ</div>
		  <div class="let">ო</div>
		  <div class="let">პ</div>
		  <div class="let">ჟ</div>
		  <div class="let">რ</div>
		  <div class="let">ს</div>
		  <div class="let">ტ</div>
		  <div class="let">უ</div>
		  <div class="let">ფ</div>
		  <div class="let">ქ</div>
		  <div class="let">ღ</div>
		  <div class="let">ყ</div>
		  <div class="let">შ</div>
		  <div class="let">ჩ</div>
		  <div class="let">ც</div>
		  <div class="let">ძ</div>
		  <div class="let">წ</div>
		  <div class="let">ჭ</div>
		  <div class="let">ხ</div>
		  <div class="let">ჯ</div>
		  <div class="let">ჰ</div>
		  <div class="let" style="width:100px; color: gray;">ყველა ასო</div>
		  </div>';
}
function Dictionary_Input_Word() {
		echo '<input type="search" placeholder="სიტყვა" id="form_Search">';
}
function Level() { // блок уровни А1-В2
		echo '<div class="for_choose">
			  <label>აირჩიეთ დონე<br />
        <input type="checkbox" id="form_A1" name="A1">A1
        <input type="checkbox" id="form_A2" name="A2">A2
				<input type="checkbox" id="form_A2plus" name="A2plus">A2+
        <input type="checkbox" id="form_B1" name="B1">B1
        <input type="checkbox" id="form_B2" name="B2">B2<br>
		<input type="checkbox" id="form_C1" name="C1">C1
		<input type="checkbox" id="form_WL" name="WL">
			  </label>
			  </div>';
}
function Part_of_Speech() { // блок "Часть речи"
			echo '<div class="for_choose">
			<label>აირჩიეთ მეტყველების ნაწილი<br />
			<select size="1" id="form_part_of_speech">
			<option value="13" name="13" selected="selected" ><p class="border_bott">ყველა მეტყველების ნაწილი</p></option>
			<option value="0" name="0">-</option>
			<option value="1" name="1">არსებითი სახელი</option>
			<option value="2" name="2">ზედსართავი სახელი</option>
			<option value="3" name="3">რიცხვითი სახელი</option>
			<option value="4" name="4">ნაცვალსახელი</option>
			<option value="5" name="5" >ზმნა</option>
			<option value="6" name="6">საწყისი</option>
			<option value="7" name="7">მიმღეობა</option>
			<option value="8" name="8">ზმნიზედა</option>
			<option value="9" name="9">ნაწილაკი</option>
			<option value="10" name="10">კავშირი</option>
			<option value="11" name="11">თანდებული</option>
			<option value="12" name="12">შორისდებული</option>
			</select>
			</label>
			</div>';
}
function Tema() { // блок "tema"
			echo '<div class="for_choose">
			<label>აირჩიეთ თემატური ჯგუფი<br />
			<select size="1" id="form_tema">
			<option  selected="selected" value="43" name="short_for">ყველა თემატური ჯგუფი</option>
			<option value="37" name="37">ადამიანის გარეგნობა, თვისებები, ხასიათი</option>
			<option value="38" name="38">ავეჯი, ჭურჭელი, საოჯახო ნივთები</option>
			<option value="7" name="7">განათლება, მეცნიერება</option>
			<option value="25" name="25">გარესამყარო</option>
			<option value="28" name="28" >გეოგრაფიული სახელები</option>
			<option value="34" name="34">გრძნობა-აღქმა, განწყობა</option>
			<option value="41" name="41">დრო, ადგილი, სივრცე, ზომა-წონა</option>
			<option value="30" name="30">ეტიკეტი</option>
			<option value="32" name="32">ზოგადი ცნებები</option>
			<option value="14" name="14">თავისუფალი დრო, გართობა</option>
			<option value="31" name="31">თვისებები და მახასიათებლები</option>
			<option value="1" name="1">იდენტიფიკაცია</option>
			<option value="12" name="12">კვება, საჭმელ-სასმელი</option>
			<option value="40" name="40">კითხვითი სიტყვები</option>
			<option value="17" name="17">კულტურა და ხელოვნება</option>
			<option value="18" name="18">მგზავრობა, მოგზაურობა</option>
			<option value="20" name="20">მომსახურების სფერო</option>
			<option value="2" name="2">ოჯახი, ნათესავები</option>
			<option value="36" name="36">პირთა სახელები და გვარები</option>
			<option value="10" name="10">პიროვნული და საზოგადოებრივი ურთიერთობები</option>
			<option value="22" name="22">პოლიტიკა, სახელმწიფო მოწყობა და მართვა</option>
			<option value="9" name="9">პროფესიები, საქმიანობა</option>
			<option value="42" name="42">რაოდენობა, რიგი, ნაწილი</option>
			<option value="23" name="23">რელიგია</option>
			<option value="21" name="21">საგანგებო სიტუაცია</option>
			<option value="24" name="24">საინფორმაციო საშუალებები</option>
			<option value="29" name="29">სამეტყველო ინსტრუმენტები</option>
			<option value="8" name="8">საქმიანი ურთიერთობა</option>
			<option value="13" name="13">საყიდლები</option>
			<option value="6" name="6">საცხოვრებელი გარემო</option>
			<option value="16" name="16">სპორტი</option>
			<option value="4" name="4">სხეულის ნაწილები და შინაგანი ორგანოები</option>
			<option value="35" name="35">სხვადასხვა</option>
			<option value="39" name="39">ტანსაცმელი, ფეხსაცმელი, აქსესუარები</option>
			<option value="19" name="19">ტრანსპორტი</option>
			<option value="15" name="15">უნარები, ჰობი</option>
			<option value="27" name="27">ფაუნა</option>
			<option value="26" name="26">ფლორა</option>
			<option value="33" name="33">ქმედება და მდგომარეობა, მიმართული მოძრაობა</option>
			<option value="11" name="11">ყოველდღიურობა</option>
			<option value="3" name="3">წარმომავლობის სახელები</option>
			<option value="5" name="5">ჯანმრთელობა, ჰიგიენა</option>
			</select>
			</label>
			</div>';
}
function Word_Table($p1) {
				global $CONNECT;
				if (mysqli_connect_errno()) { //echo "<br /><br /><br /><br />net soedinenia";
    			throw new Exception(mysqli_connect_error(), mysqli_connect_errno());
				}
		
		//echo $p1."<br>";

	$result = mysqli_query($CONNECT,$p1) or die ("Oshibka " . mysqli_error($CONNECT));
	
	if	(mysqli_num_rows($result)<>0) { //echo mysqli_num_rows($result);
	
		echo '<table class="show_words">';
		while($row=mysqli_fetch_array($result)){
			echo "<tr title=".$row['id'].">";
			echo "<td class='word'>".$row['word_view']."</td>";
			

			echo "<td class='icon'>".$row['level']."</td>";
			
			
			echo "<td class='icon_voise'><img src='resource/img/icon/audio.png' class='flRigth' alt=''/></td></tr>";
		}
		echo '</table>'; 
		
	} else {
	
	echo "<p class='bold Left20'>ამ პარამეტრით ძიებისას სიტყვები არ იძებნება</p>";
	
	}
}

?>
