<?

include_once('include/config.inc.php');

// indique aqui o período de submissão (use essas variáveis pra mostrar nos
// templates):
$SUBMISSION_FIRST_DAY = strtotime($papers['event']['submission_first_day']);
$SUBMISSION_LAST_DAY = strtotime($papers['event']['submission_last_day']);
$RESULT_PUBLICATION = strtotime($papers['event']['result_publication']);

// sanity check:
if ($SUBMISSION_LAST_DAY < $SUBMISSION_START || $RESULT_PUBLICATION < $SUBMISSION_LAST_DAY) {
  echo "Error in configuration: deadlines are crazy, they must be in chronological order.";
  exit;
}

/* não altere daqui pra baixo! */ 
/* * * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * */

// auxiliar
$temp = getdate($SUBMISSION_LAST_DAY);

// pra testar:
$SUBMISSION_START = $SUBMISSION_FIRST_DAY;
$SUBMISSION_END = mktime( $temp['hours'], $temp['minutes'], $temp['seconds'], $temp['mon'], $temp['mday'] + 1,  $temp['year'] ); // zero hora do "dia seguinte"

// current period is ...
$today = time();
$PERIOD_BEFORE_SUBMISSION = ($today < $SUBMISSION_START);
$PERIOD_SUBMISSION        = ($today >= $SUBMISSION_START && $today <= $SUBMISSION_END);
$PERIOD_REVIEW            = ($today > $SUBMISSION_END && $today <= $RESULT_PUBLICATION);
$PERIOD_RESULT            = ($today > $RESULT_PUBLICATION);

if ($smarty) {
  $smarty->assign('submissionFirstDay', $SUBMISSION_FIRST_DAY);
  $smarty->assign('submissionLastDay', $SUBMISSION_LAST_DAY);
  $smarty->assign('resultPublicationDay', $RESULT_PUBLICATION);

  $smarty->assign("PERIOD_BEFORE_SUBMISSION", $PERIOD_BEFORE_SUBMISSION);
  $smarty->assign("PERIOD_SUBMISSION", $PERIOD_SUBMISSION);
  $smarty->assign("PERIOD_REVIEW", $PERIOD_REVIEW);
  $smarty->assign("PERIOD_RESULT", $PERIOD_RESULT);
}

// header("Content-Type: text/plain");
// echo "\nPERIOD_BEFORE_SUBMISSION:";
// echo $PERIOD_BEFORE_SUBMISSION;
// echo "\nPERIOD_SUBMISSION:";
// echo $PERIOD_SUBMISSION;
// echo "\nPERIOD_REVIEW:";
// echo $PERIOD_REVIEW;
// echo "\nPERIOD_RESULT:";
// echo $PERIOD_RESULT;
// exit;


?>
