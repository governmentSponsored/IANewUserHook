<?php
if ( ! defined( 'MEDIAWIKI' ) )
    die();

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name'           => 'IA New User Hook',
	'version'        => '2',
	'author'         => 'IAIT - DID',
	'url'            => 'http://www.mediawiki.org/wiki/Extension:IA_AddUserHook',
	'description'    => 'This custom extension tests hooking into the add new user event',
	'descriptionmsg' => 'This custom extension creates a profile and populates it with LDAP data on first login',
);

$wgHooks['AuthPluginAutoCreate'][] = 'efIANewUserHook';
#$wgHooks['AutoAuthenticate'][] = 'efIANewUserHook';
$dir = dirname(__FILE__) . '/';

/**
 * Hook account creation
 *
 * @param User $user User account that was created
 * @return bool
 */

function efIANewUserHook() {
	global $wgUser;

   	$user = $wgUser -> getname(); #Grabs the username on first log in
	$user = strtolower($user); #Converts name to lower case
	$LogonSize = strlen($user); #gets the length of the user's login
	$IndexOfTilda = strpos($user, "~"); #finds the position of the ~ in the login
	$ParsedLogOn = substr($user,0,$IndexOfTilda); #finds the user to the left of the ~
	$Bureau = substr($user,$IndexOfTilda+1); #finds bureau to right of ~
	$text = "<!--DO NOT EDIT BELOW THIS LINE -->
		{{Profile
		|name=" . $ParsedLogOn . "
		|bureau=" . $Bureau . "
		}}
<!--DO NOT EDIT ABOVE THIS LINE -->


==Official Biography==
Enter your Official Biography here
==Personal Biography==
Enter your Personal Biography here 

[[Category: Employees]]";

$title = Title::newFromText('User:' . $user);

//Create the actual user profile and bring in the text for contact, personal bio, and professional bio
if( !$title->exists() ) {
$article = new Article( $title);
$article->doEdit( $text, 'Original creation of profile based on LDAP data', EDIT_NEW | EDIT_FORCE_BOT );
}
	return true;

}

?>
