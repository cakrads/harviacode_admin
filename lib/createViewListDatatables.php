<!--
Author : Hari Prasetyo
Website : harviacode.com
Create Date : 08/05/2015

You may edit this code, but please do not remove original information. Thanks :D
-->
<?php

$path = $target."views/". $folder_admin .''. $list_file;
        
$createList = fopen($path, "w") or die("Unable to open file!");

$result2 = mysql_query("SELECT COLUMN_NAME,COLUMN_KEY FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$database' AND TABLE_NAME='$table' AND COLUMN_KEY = 'PRI'");
$row = mysql_fetch_assoc($result2);
$primary = $row['COLUMN_NAME'];

$string = "
<section class=\"content\">

    <!-- Default box -->
    <div class=\"box\">
        <div class=\"box-header\">

        </div>
        <div class=\"box-header row\" style=\"margin-bottom: 10px\">
            <div class=\"col-md-4\">
            </div>
            <div class=\"col-md-4 text-center\">
                <div style=\"margin-top: 4px\"  id=\"message\">
                    <?php echo \$this->session->userdata('message') <> '' ? \$this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class=\"col-md-4 text-right\">
                <?php echo anchor(site_url(\$folder_admin .'". $controller ."/create'), 'Create', 'class=\"btn btn-primary\"'); ?>";
                    if ($excel == 'create') {
                        $string .= "\n\t\t<?php echo anchor(site_url(\$folder_admin .'". $controller."/excel'), 'Excel', 'class=\"btn btn-primary\"'); ?>";
                    }
                    if ($word == 'create') {
                        $string .= "\n\t\t<?php echo anchor(site_url(\$folder_admin .'". $controller."/word'), 'Word', 'class=\"btn btn-primary\"'); ?>";
                    }
$string .= "\n\t    </div>
        </div><!-- /.box-header-->
        <div class=\"box-body\">
            <table class=\"table table-bordered table-striped\" id=\"mytable\">
            <thead>
                <tr>
                    <th>No</th>";

$result2 = mysql_query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$database' AND TABLE_NAME='$table' AND COLUMN_KEY <> 'PRI'");
if (mysql_num_rows($result2) > 0)
{
    while ($row1 = mysql_fetch_assoc($result2))
    {
        $string .= "\n\t\t    <th>" . str_replace('_',' ',$row1['COLUMN_NAME']) . "</th>";
    }
}
$string .= "\n\t\t    <th>Action</th>
                </tr>
            </thead>";
$string .= "\n\t    <tbody>
            <?php
            \$start = 0;
            foreach ($" . $controller . "_data as \$$controller)
            {
                ?>
                <tr>";

$string .= "\n\t\t    <td><?php echo ++\$start ?></td>";

$result2 = mysql_query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$database' AND TABLE_NAME='$table' AND COLUMN_KEY <> 'PRI'");
if (mysql_num_rows($result2) > 0)
{
    while ($row1 = mysql_fetch_assoc($result2))
    {
        $string .= "\n\t\t    <td><?php echo $" . $controller ."->". $row1['COLUMN_NAME'] . " ?></td>";
    }
}

$string .= "\n\t\t    <td style=\"text-align:center\">" 
        . "\n\t\t\t<?php "
        . "\n\t\t\techo anchor(site_url(\$folder_admin.'". $controller."/read/'.$".$controller."->".$primary."),'<span class=\"fa fa-history\"></span>'); "
        . "\n\t\t\techo ' | '; "
        . "\n\t\t\techo anchor(site_url(\$folder_admin.'". $controller."/update/'.$".$controller."->".$primary."),'<span class=\"fa fa-edit\"></span>'); "
        . "\n\t\t\techo ' | '; "
        . "\n\t\t\techo anchor(site_url(\$folder_admin.'". $controller."/delete/'.$".$controller."->".$primary."),'<span class=\"fa fa-trash\"></span>','onclick=\"javasciprt: return confirm(\\'Are You Sure ?\\')\"'); "
        . "\n\t\t\t?>"
        . "\n\t\t    </td>";

$string .=  "\n\t        </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        </div><!-- /.box-body -->
        <div class=\"box-footer\">
            
        </div><!-- /.box-footer-->
    </div><!-- /.box -->

</section><!-- /.content -->
";


fwrite($createList, $string);
fclose($createList);

$list_res = "<p>" . $path . "</p>";
?>