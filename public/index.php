<?php
/**
 * nepda Internetdienstleistungen
 * Nepomuk Fraedrich
 * Zschopauer Str. 159
 * 09126 Chemnitz
 * info@nepda.eu
 * http://nepda.eu/
 * tel 0176 270 68 7 68
 * USt-IdNr.: DE287190901
 *
 * PHP Version >= 5.6
 *
 * @author    Nepomuk Fraedrich <info@nepda.eu>
 * @copyright 2015 Nepomuk Fraedrich
 */

include '../vendor/autoload.php';

if (!isset($_GET['issue'])) {
    echo 'no issue';
    die();
}

include_once '../.auth.php';

$issueId = $_GET['issue'];

$type = isset($_GET['type']) ? $_GET['type'] : 'html';

$client = new YouTrack\Connection(YOUTRACK_URL, YOUTRACK_USERNAME, YOUTRACK_PASSWORD);

$out = '';

try {
    $issue = $client->getIssue($issueId);

    $state = $issue->__get('State');

    switch ($state) {
        case 'Submitted':
        case 'Open':
            $style = 'color: green;text-decoration: none;';
            break;
        case 'Fixed':
            $style = 'color:grey;text-decoration: line-through;';
            break;
        case 'To be discussed':
            $style = 'color:red;';
            break;
        default:
            $style = 'color: black;text-decoration: none;';
    }

    $title = $issue->__get('summary');
    $title = htmlspecialchars($title, ENT_QUOTES);

    $stateCls = str_replace(' ', '', $state);

    $issueUrl = YOUTRACK_URL . '/issue/' . $issue->getId();

    $out .= '<span class="youtrack-issue state_' . $stateCls . '">';
    $out .= '<a href="' . $issueUrl . '" style="font-size:small;font-family:arial;' . $style . '">';
    $out .= '<img style="display:inline;" src="' . YOUTRACK_URL . '/_classpath/images/youtrack16.png">';
    $out .= '<span title="' . $title . ' (State: ' . $state . ')"> ' . $issue->getId() . '</span>';
    $out .= '</a>';
    $out .= '</span>';

} catch (\Exception $e) {
    $out .= 'Error (' . $e->getMessage() . ')';
}

if ($type == 'html') {
    echo $out;
} elseif ($type == 'js') {

    header('content-type: text/javascript');

    if (isset($_GET['callback'])) {
        echo 'var issueData = ' . json_encode($out) . ';';
        echo $_GET['callback'] . '(issueData);';
    } else {
        $data = [
            $issueId => $out
        ];
        $json = json_encode($data);
        echo $json;
    }
} else {
    echo 'unknown type';
}
