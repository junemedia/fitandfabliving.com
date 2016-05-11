<?php
/**
 * LiveRamp Match Partner tags
 */

$lr_ei_tagid = '425156';
$lr_rc_tagid = '425116';

// open the tag...
$lr_tag = '<iframe name="_rlcdn" width=0 height=0 frameborder=0 src="';

// if user is logged in, serve match partner tag
if ($user && isset($user->email)) {

  $lr_tag .= '//ei.rlcdn.com/' . $lr_ei_tagid . '.html';
  $lr_tag .= '?s=' . sha1(strtolower($user->email));
}
else {
  // otherwise serve recookier tag
  $lr_tag .= '//rc.rlcdn.com/' . $lr_rc_tagid . '.html';
}

// ...close the tag
$lr_tag .= '"></iframe>';

echo $lr_tag;
