<?php

class FeaturedVideo extends DataObject {

  static $db = array(
    'Name' => 'Text',
    'Day' => 'Int',
    'YouTubeID' => 'Varchar',
    'Description' => 'Text',
    'URLSegment' => 'Text'
  );

  static $has_one = array(
    'PresentationCategoryPage' => 'PresentationCategoryPage'
  );

}


?>
