<?

require_once 'include/basic.inc.php';
require_once 'include/mysql.inc.php';
require_once 'include/persons.inc.php';
require_once 'include/mysmarty.inc.php';

$smarty = new MySmarty;
require_once 'include/language.inc.php';

require_once 'include/config.inc.php';
$subject = $papers['event']['codename'];

if (isset($_POST['email'])) {
  $mysql = new Mysql;
  $person = Persons::find($mysql, $_POST['email']);
  if ($person) {
    $passwd = Persons::newPassword($mysql, $person['cod']);
    $smarty->assign('person', $person);
    $smarty->assign('passwd', $passwd);

    $headers = 'From: ' . $papers['event']['contact_email'] . "\r\n" .
               "Content-Type: text/plain; charset=iso-8859-1\r\n";
    
    mail($person['email'] , $subject, $smarty->fetch("newPassword.$language.tpl"), $headers);
    $smarty->assign('content', "lostPassword2.$language.tpl");
  } else {
    $smarty->assign('message', 'noSuchUser');
  }
} else {
  $smarty->assign('content', 'lostPassword1.tpl');
}

$smarty->display('index.tpl');

?>
