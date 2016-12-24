<!--
Author : Hari Prasetyo
Website : harviacode.com
Create Date : 08/05/2015

You may edit this code, but please do not remove original information. Thanks :D
-->
<?php

$path = $target."views/". $folder_admin .''. $form_file;

$createForm = fopen($path, "w") or die("Unable to open file!");

$result2 = mysql_query("SELECT COLUMN_NAME,COLUMN_KEY FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$database' AND TABLE_NAME='$table' AND COLUMN_KEY = 'PRI'");
$row = mysql_fetch_assoc($result2);
$primary = $row['COLUMN_NAME'];

$string = "
<section class=\"content\">

    <!-- Default box -->
    <div class=\"box\">
        <div class=\"box-header\">
            
        </div><!-- /.box-header-->
        <div class=\"box-body\">
            
            <form action=\"<?php echo \$action; ?>\" method=\"post\">";
$result2 = mysql_query("SELECT COLUMN_NAME,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$database' AND TABLE_NAME='$table' AND COLUMN_KEY <> 'PRI'");
if (mysql_num_rows($result2) > 0)
{
    while ($row1 = mysql_fetch_assoc($result2))
    {   
        if($row1["DATA_TYPE"] == "enum")
        {   
            $data = mysql_fetch_array(mysql_query("SELECT COLUMN_TYPE FROM information_schema.`COLUMNS` WHERE TABLE_NAME = '$table' AND COLUMN_NAME = '".$row1["COLUMN_NAME"]."'"));
            // echo "SELECT COLUMN_TYPE FROM information_schema.`COLUMNS` WHERE TABLE_NAME = 'table' AND COLUMN_NAME = '".$row1["COLUMN_NAME"]."'";
            $data0 = str_replace("'","",$data["COLUMN_TYPE"]);
            $data0 = str_replace("enum(","",$data0);
            $data0 = str_replace(")","",$data0);
            $data0 = str_replace(",","|",$data0);
            $data3 = explode("|", $data0);
            $jlh_data = count($data3);
            $string .= "\n\t    <div class=\"form-group\">
                            <label for=\"".$row1["COLUMN_NAME"]."\">".str_replace("_"," ",$row1["COLUMN_NAME"])." <?php echo form_error('".$row1["COLUMN_NAME"]."') ?></label><br> \n\t";
            for ($i=0; $i < $jlh_data; $i++) {
                if($i == 0)
                {
                    $string .= "<input type='radio'  name='".$data3[$i]."' value='".$data3[$i]."'
                                    <?php if($".$row1["COLUMN_NAME"]." == \"".$data3[$i]."\" OR $".$row1["COLUMN_NAME"]." == \"\"){    echo \"checked\";    } ?> > ".$data3[$i]." &nbsp;&nbsp; \n\t";
                    }
                else
                {
                    $string .= "<input type='radio'  name='".$data3[$i]."' value='".$data3[$i]."'
                                    <?php if($".$row1["COLUMN_NAME"]." == \"".$data3[$i]."\" ){    echo \"checked\";    } ?> > ".$data3[$i]." &nbsp;&nbsp; \n\t";
                }
            }

            $string .="</div>";
        }

        elseif ($row1["DATA_TYPE"] == 'text')
        {
        $string .= "\n\t    <div class=\"form-group\">
                <label for=\"".$row1["COLUMN_NAME"]."\">".str_replace("_"," ",$row1["COLUMN_NAME"])." <?php echo form_error('".$row1["COLUMN_NAME"]."') ?></label>
                <textarea class=\"form-control\" rows=\"3\" id=\"loko\" name=\"".$row1["COLUMN_NAME"]."\" placeholder=\"".str_replace("_"," ",$row1["COLUMN_NAME"])."\"><?php echo $".$row1["COLUMN_NAME"]."; ?></textarea>
            </div>";
        }
        elseif (strpos($row1["COLUMN_NAME"], 'pass') !== false) {
            $string .= "\n\t    <div class=\"form-group\">
                <label for=\"".$row1["DATA_TYPE"]."\">".str_replace("_"," ",$row1["COLUMN_NAME"])." <?php echo form_error('".$row1["COLUMN_NAME"]."') ?></label>
                <input type=\"password\" class=\"form-control\" name=\"".$row1["COLUMN_NAME"]."\" id=\"date\" placeholder=\"".str_replace("_"," ",$row1["COLUMN_NAME"])."\" />
            </div>";
        }
        else if ($row1["DATA_TYPE"] == 'date')
        {
            $string .= "\n\t    <div class=\"form-group\">
                <label for=\"".$row1["DATA_TYPE"]."\">".str_replace("_"," ",$row1["COLUMN_NAME"])." <?php echo form_error('".$row1["COLUMN_NAME"]."') ?></label>
                <input type=\"text\" class=\"form-control\" name=\"".$row1["COLUMN_NAME"]."\" id=\"date\" placeholder=\"".str_replace("_"," ",$row1["COLUMN_NAME"])."\" value=\"<?php echo $".$row1["COLUMN_NAME"]."; ?>\" />
            </div>";
        }
        else
        {
        $string .= "\n\t    <div class=\"form-group\">
                <label for=\"".$row1["DATA_TYPE"]."\">".str_replace("_"," ",$row1["COLUMN_NAME"])." <?php echo form_error('".$row1["COLUMN_NAME"]."') ?></label>
                <input type=\"text\" class=\"form-control\" name=\"".$row1["COLUMN_NAME"]."\" id=\"".$row1["COLUMN_NAME"]."\" placeholder=\"".str_replace("_"," ",$row1["COLUMN_NAME"])."\" value=\"<?php echo $".$row1["COLUMN_NAME"]."; ?>\" />
            </div>";
        }
    }
}
$string .= "\n\t    <input type=\"hidden\" name=\"".$primary."\" value=\"<?php echo $".$primary."; ?>\" /> ";
$string .= "\n\t    <button type=\"submit\" class=\"btn btn-primary\"><?php echo \$button ?></button> ";
$string .= "\n\t    <a href=\"<?php echo site_url(\$folder_admin.'".$controller."') ?>\" class=\"btn btn-default\">Cancel</a>";
$string .= "\n\t</form>

        </div><!-- /.box-body -->
        <div class=\"box-footer\">
            
        </div><!-- /.box-footer-->
    </div><!-- /.box -->

</section><!-- /.content -->

";


fwrite($createForm, $string);
fclose($createForm);

$form_res = "<p>" . $path . "</p>";
?>