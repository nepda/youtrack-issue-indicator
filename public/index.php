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
 * PHP Version >= 5.4
 *
 * @category  >
 * @package   >
 * @author    Nepomuk Fraedrich <info@nepda.eu>
 * @copyright 2015 Nepomuk Fraedrich
 * @license   >
 * @link      >
 */

include '../vendor/autoload.php';

if (!isset($_GET['issue'])) {
    return;
}

include_once '../.auth.php';

$issueId = $_GET['issue'];

$client = new YouTrack\Connection(YOUTRACK_URL, YOUTRACK_USERNAME, YOUTRACK_PASSWORD);

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
        default:
            $style = 'color: black;text-decoration: none;';
    }

    echo '<span class="state_'.$state.'">';
    echo '<a href="'.YOUTRACK_URL.'/issue/'.$issue->getId().'" style="font-size:small;font-family:arial;'.$style.'">';
    echo '<img style="display:inline;margin-bottom:-3px;" src="'.YOUTRACK_URL.'/_classpath/images/youtrack16.png">';
    echo '<span title="(State: '.$state.')"> ' . $issue->getId() . '</span>';
    echo '</a>';
    echo '</span>';

} catch (\Exception $e) {
    echo 'Error';
}
