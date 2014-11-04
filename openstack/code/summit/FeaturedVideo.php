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

function onBeforeWrite() {
  parent::onBeforeWrite();


  // If there is no URLSegment set, generate one from Title
  if((!$this->URLSegment || $this->URLSegment == 'new-presentation') && $this->Title != 'New Presentation')
  {
    $this->URLSegment = SiteTree::generateURLSegment($this->Title);
  }
  else if($this->isChanged('URLSegment'))
  {
    // Make sure the URLSegment is valid for use in a URL
    $segment = preg_replace('/[^A-Za-z0-9]+/','-',$this->URLSegment);
    $segment = preg_replace('/-+/','-',$segment);

    // If after sanitising there is no URLSegment, give it a reasonable default
    if(!$segment) {
      $segment = "presentation-".$this->ID;
    }
    $this->URLSegment = $segment;
  }

  // Ensure that this object has a non-conflicting URLSegment value by appending number if needed
  $count = 2;
  while($this->LookForExistingURLSegment($this->URLSegment))
  {
    $this->URLSegment = preg_replace('/-[0-9]+$/', null, $this->URLSegment) . '-' . $count;
    $count++;
  }

}

//Test whether the URLSegment exists already on another Video
function LookForExistingURLSegment($URLSegment)
{
  return (DataObject::get_one('Company', "URLSegment = '" . $URLSegment ."' AND ID != " . $this->ID));
}




}




?>
