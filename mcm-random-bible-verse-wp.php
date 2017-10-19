<?php
/*
Plugin Name: MCM Random Bible Verse for WordPress
Description: Widget and shortcode that show a random Bible verse from the KJV.  Use the shortcode [mcm-random-bible-verse-wp] 
Version: 1.0
Author: MCM Web Solutions, LLC
Author URI: http://www.mcmwebsite.com
License: GPL v. 2
*/
       
// TODO - test on multiple PHP versions, e.g. 5.2, 5.3, 5.4, 5.5, 7.1  (main test env is 5.6.29, also test on 7.0) also test with php7cc (PHP7 only) and WP Engine's WP plugin tester (on 5.3-7.0)


$mcmRandomBibleVerseWP = new MCM_Random_Bible_Verse_WP();                                       

class MCM_Random_Bible_Verse_WP {        
  
  private static $pluginName = 'MCM Random Bible Verse for WordPress';
  private static $pluginCode = 'mcm_random_bible_verse_wp';
  private static $shortCodeStr = '[mcm-random-bible-verse-wp]';
       
  function __construct() {                 
    
    //add a filter for the shortcode
    add_filter('the_content', array(&$this, 'filter'), 10);
    
    // TODO - add the widget
    
    // TODO - create the Bible verse database tables (on install)
    
    // TODO - on uninstall, remove the Bible verse database tables
      
  } // end __construct()
    
    
  function filter($content = '') {
  
    if ( strpos($content, self::$shortCodeStr) === FALSE ) {  // if shortcode not in $content, just return $content     
      print ' no short code! '; // debug - remove!
      return $content; 
    }
    
	  // replace shortcode with a random Bible verse  
    $newContent = str_replace( self::$shortCodeStr, $this->getRandomBibleVerseText(), $content );

    return $newContent;
  
  }  
    
  function getRandomBibleVerseText() {
  
    global $wpdb, $table_prefix;
    
    $quoteVerse = '';
  
    $qs = "SELECT b.name, 
                  v.verseNum, 
                  v.verseChapter, 
                  v.verseText
           FROM ".$table_prefix."mcm_bible_Verse v,
                ".$table_prefix."mcm_bible_Book b
           WHERE v.bookID=b.id   
           ORDER BY RAND()
           LIMIT 1";                               
    $rows = $wpdb->get_results($qs, ARRAY_A);  
    if ( count($rows) ) {
       $row = $rows[0];   
       $bookName = $row['name'];
       $verseNum = $row['verseNum']; 
       $verseChapter = $row['verseChapter']; 
       $quoteText = $row['verseText']; 
       $quoteVerse = $quoteText.' - '.$bookName.' '.$verseChapter.':'.$verseNum;
    }
    
    return $quoteVerse;
  
  }
  
  
} // end class
?>
