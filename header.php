<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Parent theme: Bootstrapbase by Bas Brands
 * Built on: Essential by Julian Ridden
 *
 * @package   theme_uqam
 * @copyright 2016 Uqam
 *
 */
require_once($CFG ->dirroot.'/local/uqamlib/uqamlib.php');
$hasheading = ($PAGE->heading);

$haslogo = (!empty($PAGE->theme->settings->logo));

$hasheaderprofilepic = (empty($PAGE->theme->settings->headerprofilepic)) ? false : $PAGE->theme->settings->headerprofilepic;

$checkuseragent = '';
if (!empty($_SERVER['HTTP_USER_AGENT'])) {
    $checkuseragent = $_SERVER['HTTP_USER_AGENT'];
}
$username = get_string('username');
if (strpos($checkuseragent, 'MSIE 8')) {$username = str_replace("'", "&prime;", $username);}
?>

<?php if(isset($PAGE->theme->settings->socials_position) && $PAGE->theme->settings->socials_position==1) { ?>
    	<div class="container-fluid socials-header"> 
    	<?php require_once(dirname(__FILE__).'/socials.php');?>
        </div>
<?php
} ?>

    <header id="page-header" class="clearfix">
       
    <div class="container-fluid">    
    <div class="row-fluid">
    <!-- HEADER: LOGO AREA -->
        	
            <?php if (!$haslogo) { ?>
            	<div class="span6">
              		<h1 id="title" style="line-height: 2em"><?php echo $SITE->shortname; ?></h1>
                </div>
            <?php } else { ?>
                <div class="logo-header">
                	<!--  <a class="logo" href="<?php echo $CFG->wwwroot; ?>" title="<?php print_string('home'); ?>"> commentaire JT-->
                	<!-- <a class="logo" href="<?php echo "http://www.uqam.ca"; ?>" target="_blank" title="<?php print_string('home'); ?>">  -->
                    <?php 
					//echo html_writer::empty_tag('img', array('src'=>$PAGE->theme->setting_file_url('logo', 'logo'), 'class'=>'logo', 'alt'=>'logo'));
                    echo html_writer::empty_tag('img', array('src'=>$PAGE->theme->setting_file_url('logo', 'logo'), 'class'=>'logo', 'alt'=>'logo','usemap'=>'#uqthememap'));
					?>
					<map name="uqthememap">
					<?php 
					//one use map here so one could have many links in a single logo image when needed (ex: ESG UQAM could have 2 links: esg and uqam)
					$themename=$PAGE->theme->name;
					if(strcasecmp($themename,"uqam")==0){
						?>
						<area shape="default" class="logoarea"  href="http://www.uqam.ca" target="_blank">
						<?php 
					}
					 elseif(strcasecmp($themename,"uqamesg")==0){
					 	?>
					 	<area shape="default" class="logoarea"  href="http://www.esg.uqam.ca" target="_blank">
					 	<?php 
					 }
					 elseif(strcasecmp($themename,"uqamseb")==0){
					 	?>
					 	<area shape="default" class="logoarea"><!-- the logo should'nt be clicable on uqamseb theme -->
					 	<?php
					 }
					 else{
					 ?>
					 <area shape="default" class="logoarea"  href="http://www.uqam.ca" target="_blank">
					 <?php
					 }
					?>
					</map>
                    <!--  </a> -->
                </div>
                <?php
            } ?>

        <?php if ($hasheading) {
            $sigle=sigle( $PAGE->course -> shortname);
            $theme = $PAGE->theme;
            $titre=description_cours ($sigle);
            $sigle_titre = "$sigle - $titre";
            if (trim($titre) ==='') $sigle_titre = $sigle;
            $ecole_title = '';
            if (isset($theme->settings->ecole) && $theme->settings->ecole!== '') {
                $ecole_title = $theme->settings->ecole;
            }
            else {
                $ecole_title = get_string('uqam', 'theme_uqam');
            }

            ?>
            <div class="entete-cours">
                <span class="unite-uqam"><?php echo "$ecole_title"  ?></span>
                <span class="titre-cours"><?php echo "$sigle_titre"  ?></span>
                    <span class="groupe-cours">
                        <?php
                        $fullname=$PAGE->course -> fullname;
                        $p="/$sigle gr./";
                        if ($p) {
                            $fullname=preg_replace($p,'Groupe ',$fullname);
                        }
                        echo  $fullname;
                        ?>
                    </span>
            </div>

        <?php } ?>

        <div class="login-header">
            <div class="profileblock">

                <?php
                if (empty($CFG->loginhttps)) {
                    $wwwroot = $CFG->wwwroot;
                } else {
                    $wwwroot = str_replace("http://", "https://", $CFG->wwwroot);
                }

                if (!isloggedin() or isguestuser()) {
                    ?>

                    <!-- <div id="block-login"> -->
                    <div id="uqam-login-button" class="btn pull-right">
                        <a href="<?php echo "{$wwwroot}/login/index.php"; ?>" >
                            <i class="fa fa-sign-in">Connexion</i> 
                        </a>
                    </div>
                    <!-- </div>-->

                <?php } else {

                    echo '<div id="loggedin-user">';
                    if(strcasecmp($PAGE->theme->name,"uqamseb")!=0){//do not display user profile link on uqamseb theme
                    	echo $OUTPUT->user_menu();
                    echo $OUTPUT->user_picture($USER, array('size' => 80, 'class' => 'welcome_userpicture'));
                    }else{//uqamseb
                    	echo $OUTPUT->user_menu($USER,FALSE);
                    	echo $OUTPUT->user_picture($USER, array('size' => 80, 'class' => 'welcome_userpicture','link' => FALSE));
                    }
                    echo '</div>';

                }?>

            </div>
        </div>

    </div>
    </div>
               
</header>

<header role="banner" class="navbar">
    <nav role="navigation" class="navbar-inner">
    
        <div class="container-fluid">
          <?php //no navigation element un uqamseb
          if(strcasecmp($PAGE->theme->name,"uqamseb")!=0){ ?>
            <a class="brand" href="<?php echo $CFG->wwwroot;?>"><?php echo $SITE->shortname; ?></a>
            <!-- debut JT -->
            <?php // insert help link and modal  window here to garantee a better display on mobile
                $themename=$PAGE->theme->name;
                if(strcasecmp($themename,"uqamseb")!=0){ // display theme link on other theme than uqamseb
                if(file_exists($CFG ->dirroot.'/theme/uqam/uqamhelp.php')){
                require_once($CFG ->dirroot.'/theme/uqam/uqamhelp.php');
                theme_uqam_uqamhelp('uqhelpmobile');
                theme_uqam_uqamhelp_modal_windows();
                theme_uqam_uqamhelp('uqhelpdesktop');
                }
                }
                ?>
            <!-- Fin JT -->
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="nav-collapse collapse">
                <?php echo $OUTPUT->custom_menu(); ?>
                <div class="nav-divider-right"></div>
                <ul class="nav pull-right">
                    <li><?php echo $OUTPUT->page_heading_menu(); ?></li>
                </ul>
                
                <form id="search" action="<?php echo $CFG->wwwroot;?>/course/search.php" method="GET">
                <div class="nav-divider-left"></div>
                   							
					<input id="coursesearchbox" type="text" onFocus="if(this.value =='<?php echo get_string('searchcourses'); ?>' ) this.value=''" onBlur="if(this.value=='') this.value='<?php echo get_string('searchcourses'); ?>'" value="<?php echo get_string('searchcourses'); ?>" name="search">
					<input type="submit" value=""><span>
										
				</form>
                
            </div>
            <?php 
             }else{//if uqamseb theme display the shortname without link
             	?>
             	<a class="brand"><?php echo $SITE->shortname; ?></a>
             	<?php 
             }
            
            ?>
        </div>
        
    </nav>
</header>