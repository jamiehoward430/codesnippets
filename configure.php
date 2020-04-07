  
  <?php

if(!in_array($user->data()->id,$master_account)){ Redirect::to($us_url_root.'users/admin.php');} //only allow master accounts to manage plugins! ?>

<?php
include "plugin_info.php";
pluginActive($plugin_name);

$snippetName = Input::get('snippetName');
$snippetDescription = Input::get('snippetDescription');
$language = Input::get('language');
$code = Input::get('NewCode');
$edit = Input::get('edit');
$sview = Input::get('sview');
$hidden = Input::get('hidden');
$sid = Input::get('snippet');
$save = Input::get('save');

if($edit != ''){
  if(is_numeric($edit)){
    $info = $db->query("SELECT * FROM plg_snippets WHERE id = ?", array($edit))->first();
    $snippetName = $info->name;
    $snippetDescription = $info->description;
    $language = $info->syntax;
    $code = $info->code;
  }
}


if(!empty($_POST)){
  if ($snippetName != '' && $snippetDescription != '' && $code != '' && $language != '' && $hidden != '' && $edit == '') {
		$fields = array(
      'name'=>$snippetName,
      'description'=>$snippetDescription,
      'syntax'=>$language,
      'code'=>$code
		);
    $db->insert('plg_snippets',$fields);
  }elseif($snippetName != '' && $snippetDescription != '' && $code != '' && $language != '' && $hidden != '' && $edit != '') {
		$fields = array(
      'name'=>$snippetName,
      'description'=>$snippetDescription,
      'syntax'=>$language,
      'code'=>$code
		);
    $db->update('plg_snippets',$edit,$fields);
  }
}
?>


<link rel="stylesheet" href="<?=$us_url_root?>usersc/plugins/codesnippets/assets/s_prism1.css">

<script src="<?=$us_url_root?>usersc/plugins/codesnippets/assets/s_prism1.js" charset="utf-8"></script>
<link rel="stylesheet" href="<?=$us_url_root?>usersc/plugins/codesnippets/assets/prism-live.css">
<script src="<?=$us_url_root?>usersc/plugins/codesnippets/assets/prism-live.js?load=python,javascript,html,php,regex,sql,css" charset="utf-8"></script>


<div id="page-wrapper"><!-- .wrapper -->
	<div class="container-fluid"><!-- .container -->	
		<div class="row">
 			<div class="col-sm-12">
       <a href="<?=$us_url_root?>users/admin.php?view=plugins">Return to the Plugin Manager</a><br><br>

<?php 
if($sview == ''){
  $snippets = $db->query("SELECT id, name, description, syntax FROM plg_snippets")->results();
?>
      
      <form method="post" action="admin.php?view=plugins_config&plugin=codesnippets&sview=new">
        <input type="submit" name="submit" value="+ Add New Snippet" class="btn btn-primary">
      </form>

      <table  id="paginate" class='table table-striped paginate'>
				<thead>
					<th>Name</th>
          <th>Description</th>
          <th>Language</th>
				</thead>
				<tbody>	
          <?php		
            $t = 'Tables_in_'	. Config::get('mysql/db');	
						foreach($snippets as $snippet){?>
						<tr>
								<td><a href="admin.php?view=plugins_config&plugin=codesnippets&sview=viewsnippet&snippet=<?=$snippet->id?>"><?=$snippet->name?></a></td>
                <td><?=$snippet->description?></td>
                <td><?=str_replace('language-','',$snippet->syntax);?></td>
						</tr>	
					<?php } ?>
				</tbody>
      </table>  

<?php 
}elseif($sview == 'new'){
?>

<a href="<?=$us_url_root?>users/admin.php?view=plugins_config&plugin=codesnippets"><-Return to Snippets</a><br><br>
<form method="post" action="admin.php?view=plugins_config&plugin=codesnippets&sview=new">
          <label for="">Snippet Name*</label>
          <input type="text" name="snippetName" class="form-control" value="<?php if($snippetName != ''){ echo $snippetName;}?>">
          <label for="">Snippet Description*</label>
          <input type="text" name="snippetDescription" class="form-control" value="<?php if($snippetDescription != ''){ echo $snippetDescription;}?>">
          <label for="">Language*</label>
          <select onchange="this.form.submit()" class="form-control l_select" name="language">
					  <option value="">--Choose Language--</option>
						<option <?php if($language == 'language-python'){ echo "selected";}?> value="language-python">python</option>
            <option <?php if($language == 'language-javascript'){ echo "selected";}?> value="language-javascript">javascript</option>
            <option <?php if($language == 'language-html'){ echo "selected";}?> value="language-html">html</option>
            <option <?php if($language == 'language-php'){ echo "selected";}?> value="language-php">php</option>
            <option <?php if($language == 'language-sql'){ echo "selected";}?> value="language-sql">sql</option>
            <option <?php if($language == 'language-css'){ echo "selected";}?> value="language-css">css</option>
          </select>
          <?php if($language != ''){ ?>
          <input type="hidden" name="hidden" value="1">
          <label for="">Code*</label>      
<textarea name="NewCode" spellcheck="false" class="prism-live <?= $language ?>">

<?php if($code != ''){ echo $code;}?>

</textarea><br>
        
          <?php if($edit == ''){ ?>
            <input type="button" onclick="this.form.submit()" name="btnsave" value="Add New Snippet" class="btn btn-primary">
					<?php }else{ ?>
            <input type="button" onclick="this.form.submit()" name="btnedit" value="Edit Snippet" class="btn btn-primary">

					<?php } ?>
          <?php } ?>
        </form>


        <?php 
}elseif($sview == 'viewsnippet'){
  $snippet = $db->query("SELECT * FROM plg_snippets WHERE id = ?", array($sid))->first();
?>
<a href="<?=$us_url_root?>users/admin.php?view=plugins_config&plugin=codesnippets"><-Return to Snippets</a><br><br>
<h1 class="text-center"><?=$snippet->name?></h1><br>
<h2 class="text-center"><?=$snippet->description?></h2><br><br>
<pre><code class="<?=$snippet->syntax?>"><?=$snippet->code?></code></pre><br>
<button type="button" onclick="copyToClipboard('<?php echo $snippet->code; ?>')" class="btn btn-primary">Copy to Clipboard</button><br><br>
<form method="post" action="admin.php?view=plugins_config&plugin=codesnippets&sview=new&edit=<?=$snippet->id?>">
        <input type="submit" name="submit" value="Edit Snippet" class="btn btn-primary">
      </form>




<?php 
}
?>
<script>
function copyToClipboard(str) {
    var $temp = $("<textarea>");
    var brRegex = /<br\s*[\/]?>/gi;

    $("body").append($temp);
    
    // = str.replace(/<br>/g, "\r\n"); // or \r\n
     var html = str.replace(/\n/g, "\r\n");
    $temp.val(html).select();
   // $temp.val((str).html().replace(brRegex, "\r\n")).select();
    document.execCommand("copy");
    $temp.remove();
}
</script>
      </div>
    </div>
 	</div> <!-- /.col -->
</div> <!-- /.row -->
