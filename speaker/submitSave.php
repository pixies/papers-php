<?

require_once 'include/auth.inc.php';
require_once 'include/basic.inc.php';
require_once 'include/mysql.inc.php';
require_once 'include/persons.inc.php';
require_once 'include/pathinfo.inc.php';
require_once 'include/event_dates.inc.php';
require_once 'include/proposals.inc.php';
require_once 'include/tracks.inc.php';
require_once 'include/config.inc.php';

$mysql = new Mysql;
$person = Persons::find($mysql, $user);

if ($PERIOD_SUBMISSION) {

  $fields = $_POST;

  $error = null;

  if ($event['agreement'] &&  !$fields['accept']) {
    $error = 'youMustAcceptTheTerms';
  }

  $mandatoryMissing = false;
  foreach (array('titulo','tema','idioma','descricao','resumo','publicoalvo') as $f) {
    if (! $fields[$f]) {
      $error = 'mandatoryFieldsMissing';
    }
  }

  if ($error) {
    $smarty->assign('message', $error);
    $smarty->assign('proposal', $fields);
    $smarty->assign('content', 'submit.tpl');
    $smarty->assign('tracks', Tracks::findAllAssoc($mysql, $language));
    $smarty->display('index.tpl');
    return;
  }

  
  if ($fields['cod']) {
    // existing proposal

    // TODO: check if the person saving the proposal is its owner
    $cod = $fields['cod'];

    $proposal = Proposals::find($mysql,$cod);
    if (!Proposals::owns($person,$proposal, $mysql)) {
      $smarty->fatal('onlyProposalOwnerCanUpdate');
    }
    
    unset($fields['cod']);
    Proposals::real_update($mysql, $cod, $fields);
  } else {
    // new proposal
    $fields['pessoa'] =  $person['cod'];

    $fields['ip']       = $_SERVER['REMOTE_ADDR'];
    $fields['ip_proxy'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
    $fields['browser']  = $_SERVER["HTTP_USER_AGENT"];
    $fields['dthora']   = time();

    
    Proposals::create($mysql, $fields);
  }

} else {
  $smarty->fatal('notInSubmissionPeriod');
  return;
}

header('Location: .');

?>
