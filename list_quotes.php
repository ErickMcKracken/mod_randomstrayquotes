<?php
require_once('../../config.php');
global $CFG, $PAGE;

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('List Quotes');
$PAGE->set_heading('List Quotes');
$PAGE->set_url($CFG->wwwroot.'/mod/randomstrayquotes/list_quotes.php');
echo $OUTPUT->header();
defined('MOODLE_INTERNAL') || die();
$mform = $this->_form; // Don't forget the underscore! 
?>

<form name="bulkform" id="bulkform" method="post" action="list_quotes.php"> 
<div class="tablenav">
<div class="alignleft actions" style="margin-right:10px">   
        
<-- Combo box for actions  --!>      
<select name="bulk" id="bulkselect" style="vertical-align:middle;max-width:110px" onchange="javascript:disable_enable()" />
		<option value="noaction" ><?php _e('Bulk Actions','stray-quotes'); ?></option>
		<option value="multidelete"><?php _e('delete','stray-quotes'); ?></option>
		<option value="togglevisible"><?php _e('toggle visibility','stray-quotes'); ?></option>
		<option value="changecategory"><?php _e('change category','stray-quotes'); ?></option>
		</select>
                      
<?php 
        // Combobox with categories
        $mform->addElement('select', 'category', get_string('category'),$selectArray);
        $selectArray = array();
        $selectArray[0] = "Toutes les categories";
        $catquery = "Select distinct * from {block_strayquotes_categories}";
        $category_arr = $DB->get_records_sql($catquery);

        foreach($category_arr as $category) {
            $key = $category->id;
            $value = $category->category_name;
            $selectArray[$key] = $value;
        }
?>   
		
<input type="submit" value="<?php _e('Apply','stray-quotes'); ?>" class="button-secondary action" />
</div>
        
// How many quote per page?
<div class="alignleft actions"> 
    
    <span style="color:#666; font-size:11px;white-space:nowrap;">display  </span>
    <select name="lines" onchange="switchpage(this)"  style="vertical-align:middle">
    <option value=list_quotes.php?page=stray_manage&qo=quoteID&qc=all&qs=DESC&qp=1&qr=10 selected >10 quotes</option>
    <option value=list_quotes.php?page=stray_manage&qo=quoteID&qc=all&qs=DESC&qp=1&qr=15 >15 quotes</option>
    <option value=list_quotes.php?page=stray_manage&qo=quoteID&qc=all&qs=DESC&qp=1&qr=20 >20 quotes</option>
    <option value=list_quotes.php?page=stray_manage&qo=quoteID&qc=all&qs=DESC&qp=1&qr=30 >30 quotes</option>
    <option value=list_quotes.php?page=stray_manage&qo=quoteID&qc=all&qs=DESC&qp=1&qr=50 >50 quotes</option>
    <option value=list_quotes.php?page=stray_manage&qo=quoteID&qc=all&qs=DESC&qp=1&qr=100 >100 quotes</option>
    </select> 

<!--<span style="color:#666; font-size:11px;white-space:nowrap;"> from  </span>-->

    <select name="categories" onchange="switchpage(this)"  style="vertical-align:middle;max-width:120px"> 
    <option value="listquotes.php?page=stray_manage&qo=quoteID&qp=1&qr=10&qs=DESC&qc=all" selected>all categories</option>
    <option value="list_quotes.php?page=stray_manage&qo=quoteID&qp=1&qr=10&qs=DESC&qc=default"  >default</option>
    <option value="list_quotes.php?page=stray_manage&qo=quoteID&qp=1&qr=10&qs=DESC&qc=Socialism"  >Socialism</option>
    <option value="list_quotes.php?page=stray_manage&qo=quoteID&qp=1&qr=10&qs=DESC&qc=Communism"  >Communism</option>
    </select>
</div>

// pager
<div class="tablenav-pages">
    <span class="displaying-num">Page 1 of 87</span><strong>&nbsp;&nbsp;1 <a href="listquotes.php?page=stray_manage&qo=quoteID&qr=10&qc=all&qs=DESC&qp=2">2</a>  . <a href="listquotes.php?page=stray_manage&qo=quoteID&qr=10&qc=all&qs=DESC&qp=87"> 87</a>  <a href="listquotes.php?page=stray_manage&qo=quoteID&qr=10&qc=all&qs=DESC&qp=2" title=" Next 10">&raquo;</a> </strong>
</div>
</div>

<table class="widefat" id="straymanage">         
<thead>
<tr>
<th scope="col" style="padding-left: 0; margin-left:0">
<input type="checkbox" style="padding-left:0" /></th>   				
<th scope="col" style="white-space: nowrap;"> ID<a href="listquotes.php??page=stray_manage&qo=quoteID&qp=1&qr=10&qc=all&qs=ASC">
					<img src= http://culturalmarxism.net/wp-content/plugins/stray-quotes/img/s_desc.png alt="Ascending" title="Ascending" /></a>
</th>
<th scope="col"> Quote </th>				
<th scope="col" style="white-space: nowrap;"><a href="list_quotes.php?page=stray_manage&qp=1&qr=10&qc=all&qs=DESC&qo=author">Author</a>				            
</th>				
<th scope="col" style="white-space: nowrap;"><a href="list_quotes.php?page=stray_manage&qp=1&qr=10&qc=all&qs=DESC&qo=source">Source</a>
</th>
<th scope="col" style="white-space: nowrap;"><a href="list_quotes.php?page=stray_manage&qp=1&qr=10&qc=all&qs=DESC&qo=category">Category</a>
</th>			
<th scope="col" style="white-space: nowrap;"><a href="list_quotes.php?page=stray_manage&qp=1&qr=10&qc=all&qs=DESC&qo=visible">Visible</a>
</th>           
<th scope="col">&nbsp;</th>
<th scope="col">&nbsp;</th>            
</tr>
</thead>
                
<tbody>		
<tr  class="alternate"  >      				
<td scope="col" style="white-space: nowrap;"><input type="checkbox" name="check_select0" value="868" /> </td> 
<th scope="row">868</th>
<td>Yeah, well, you know, that’s just, like, your opinion, man.</td>
<td>The Dude</td>
<td>Big Lebowski - The Movie</td>
<td>Nihilism</td>
<td>yes</td>
        
<td align="center">
<a href="listquotes.php?page=stray_manage&qo=quoteID&qp=1&qr=10&qc=all&qs=DESC&qa=edit&qi=868">
Edit</a></td>
<td align="center">
<a href="listquotes.php?page=stray_manage&qo=quoteID&qp=1&qr=10&qc=all&qs=DESC&qa=delete&qi=868"
					onclick="if ( confirm('You are about to delete quote 868.\n\'Cancel\' to stop, \'OK\' to delete.') ) { return true;}return false;">Delete</a></td>			
</tr>				
</tbody>
         
<span class="displaying-num">Page 1 of 87</span><strong>&nbsp;&nbsp;1 <a href="listquotes.php?page=stray_manage&qo=quoteID&qr=10&qc=all&qs=DESC&qp=2">2</a>  . <a href="listquotes.php?page=stray_manage&qo=quoteID&qr=10&qc=all&qs=DESC&qp=87"> 87</a>  <a href="listquotes.php?page=stray_manage&qo=quoteID&qr=10&qc=all&qs=DESC&qp=2" title=" Next 10">&raquo;</a> </strong>            
</div></div>
</form>
</div>
</body>
</html>