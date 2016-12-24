<!--
Author : Hari Prasetyo
Website : harviacode.com
Create Date : 08/05/2015

You may edit this code, but please do not remove original information. Thanks :D
-->
<?php

$path = $target."controllers/". $folder_admin .''.$controller_file;

$createController = fopen($path, "w") or die("Unable to open file!");

$result2 = mysql_query("SELECT COLUMN_NAME,COLUMN_KEY FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$database' AND TABLE_NAME='$table' AND COLUMN_KEY = 'PRI'");
$row = mysql_fetch_assoc($result2);
$primary = $row['COLUMN_NAME'];

$string = "<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class " . ucfirst($controller) . " extends CI_Controller
{   
    public \$admin_folder;
    function __construct()
    {
        parent::__construct();
        \$this->load->model('$model');
        \$this->load->library('form_validation');
        \$this->admin_folder = \$this->config->item('admin_folder');
    }";

if ($jenistabel == 'regtable') {
    
$string .= "\n\n    public function index()
    {   
        \$this->auth->is_jabatan(\$this->router->fetch_class());

        \$keyword = '';
        \$this->load->library('pagination');

        \$config['base_url'] = base_url() . '$controller/index/';
        \$config['total_rows'] = \$this->" . $model . "->total_rows();
        \$config['per_page'] = 10;
        \$config['uri_segment'] = 3;
        \$config['suffix'] = '.html';
        \$config['first_url'] = base_url() . '$controller.html';
        \$this->pagination->initialize(\$config);

        \$start = \$this->uri->segment(3, 0);
        \$$controller = \$this->" . $model . "->index_limit(\$config['per_page'], \$start);

        \$data = array(
            '" . $controller . "_data' => \$$controller,
            'keyword' => \$keyword,
            'pagination' => \$this->pagination->create_links(),
            'total_rows' => \$config['total_rows'],
            'start' => \$start,
        );

        \$this->load->view('$list', \$data);
    }
    
    public function search() 
    {   
        \$this->auth->is_jabatan(\$this->router->fetch_class());
        
        
        \$keyword = \$this->uri->segment(3, \$this->input->post('keyword', TRUE));
        \$this->load->library('pagination');
        
        if (\$this->uri->segment(2)=='search') {
            \$config['base_url'] = base_url() . '$controller/search/' . \$keyword;
        } else {
            \$config['base_url'] = base_url() . '$controller/index/';
        }

        \$config['total_rows'] = \$this->" . $model . "->search_total_rows(\$keyword);
        \$config['per_page'] = 10;
        \$config['uri_segment'] = 4;
        \$config['suffix'] = '.html';
        \$config['first_url'] = base_url() . '$controller/search/'.\$keyword.'.html';
        \$this->pagination->initialize(\$config);

        \$start = \$this->uri->segment(4, 0);
        \$$controller = \$this->" . $model . "->search_index_limit(\$config['per_page'], \$start, \$keyword);

        \$data = array(
            '" . $controller . "_data' => \$$controller,
            'keyword' => \$keyword,
            'pagination' => \$this->pagination->create_links(),
            'total_rows' => \$config['total_rows'],
            'start' => \$start,
        );
        \$this->load->view('$list', \$data);
    }";

} else {
    
$string .="\n\n    public function index()
    {   
        \$this->auth->is_jabatan(\$this->router->fetch_class());

        \$$controller = \$this->" . $model . "->get_all();
        
        //DATA
        \$data['".$controller."_data']         = \$$controller;
        \$data['folder_admin']            = \$this->admin_folder;
        \$data['content_header']          = '".ucfirst(str_replace("_"," ",$controller))." List';
        \$data['content_header_small']    = '';
        \$data['breadcrumb_active']       = '<li class=active>".ucfirst(str_replace("_"," ",$controller))."</li>';
        \$data['show_username']           = \$this->session->userdata('namauser');
        

        \$element = \$this->admin_folder.\"$list\";
        //View
        template_lib(\$element, \$data, '', \$this->admin_folder);
    }";

}
    
$string .= "\n\n    public function read(\$id) 
    {   
        \$this->auth->is_jabatan(\$this->router->fetch_class());

        \$row = \$this->" . $model . "->get_by_id(\$id);
        if (\$row) {
            \$data = array(";
$result2 = mysql_query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$database' AND TABLE_NAME='$table'");
if (mysql_num_rows($result2) > 0)
{
    while ($row1 = mysql_fetch_assoc($result2))
    {
        $string .= "\n\t\t'" . $row1['COLUMN_NAME'] . "' => \$row->" . $row1['COLUMN_NAME'] . ",";
    }
}

$string .= "\n\t    );
            //DATA
            \$data['folder_admin']            = \$this->admin_folder;
            \$data['content_header']          = '".ucfirst(str_replace("_"," ",$controller))."';
            \$data['content_header_small']    = '';
            \$data['breadcrumb_active']       = '<li class=active>".ucfirst(str_replace("_"," ",$controller))."</li>';
            \$data['show_username']           = \$this->session->userdata('namauser');
            

            \$element = \$this->admin_folder.\"$read\";
            //View
            template_lib(\$element, \$data, '', \$this->admin_folder);

        } else {
            \$this->session->set_flashdata('message', 'Data Tidak Ditemukan');
            redirect(site_url(\$this->admin_folder.'$controller'));
        }
    }
    
    public function create() 
    {   
        \$this->auth->is_jabatan(\$this->router->fetch_class());

        \$data = array(
            'button' => 'Create',
            'action' => site_url(\$this->admin_folder.'$controller/create_action'),";
$result2 = mysql_query("SELECT COLUMN_NAME,COLUMN_KEY FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$database' AND TABLE_NAME='$table'");
if (mysql_num_rows($result2) > 0)
{
    while ($row2 = mysql_fetch_assoc($result2))
    {
        $string .= "\n\t    '" . $row2['COLUMN_NAME'] . "' => set_value('" . $row2['COLUMN_NAME'] . "'),";
    }
}
$string .= "\n\t);
        //DATA
        \$data['folder_admin']            = \$this->admin_folder;
        \$data['content_header']          = '".ucfirst(str_replace("_"," ",$controller))." Create';
        \$data['content_header_small']    = '';
        \$data['breadcrumb_active']       = '<li class=active>".ucfirst(str_replace("_"," ",$controller))."</li>';
        \$data['show_username']           = \$this->session->userdata('namauser');
        

        \$element = \$this->admin_folder.\"$form\";
        //View
        template_lib(\$element, \$data, '', \$this->admin_folder);
    }
    
    public function create_action() 
    {   
        \$this->auth->is_jabatan(\$this->router->fetch_class());

        \$this->_rules();

        if (\$this->form_validation->run() == FALSE) {
            \$this->create();
        } else {
            \$data = array(";
$result2 = mysql_query("SELECT COLUMN_NAME,COLUMN_KEY FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$database' AND TABLE_NAME='$table' AND COLUMN_KEY <> 'PRI'");
if (mysql_num_rows($result2) > 0)
{
    while ($row2 = mysql_fetch_assoc($result2))
    {
        $string .= "\n\t\t'" . $row2['COLUMN_NAME'] . "' => \$this->input->post('" . $row2['COLUMN_NAME'] . "',TRUE),";
    }
}
$string .= "\n\t    );

            \$this->".$model."->insert(\$data);
            \$this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url(\$this->admin_folder.'$controller'));

        }
    }
    
    public function update(\$id) 
    {   
        \$this->auth->is_jabatan(\$this->router->fetch_class());

        \$row = \$this->".$model."->get_by_id(\$id);

        if (\$row) {
            \$data = array(
                'button' => 'Update',
                'action' => site_url(\$this->admin_folder.'$controller/update_action'),";
$result2 = mysql_query("SELECT COLUMN_NAME,COLUMN_KEY FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$database' AND TABLE_NAME='$table'");
if (mysql_num_rows($result2) > 0)
{
    while ($row2 = mysql_fetch_assoc($result2))
    {
        $string .= "\n\t\t'" . $row2['COLUMN_NAME'] . "' => set_value('" . $row2['COLUMN_NAME'] . "', \$row->". $row2['COLUMN_NAME']."),";
    }
}
$string .= "\n\t    );
            //DATA
            \$data['folder_admin']            = \$this->admin_folder;
            \$data['content_header']          = '".ucfirst(str_replace("_"," ",$controller))." Update';
            \$data['content_header_small']    = '';
            \$data['breadcrumb_active']       = '<li class=active>".ucfirst(str_replace("_"," ",$controller))."</li>';
            \$data['show_username']           = \$this->session->userdata('namauser');
            

            \$element = \$this->admin_folder.\"$form\";
            //View
            template_lib(\$element, \$data, '', \$this->admin_folder);
        } else {
            \$this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url(\$this->admin_folder.'$controller'));
        }
    }
    
    public function update_action() 
    {   
        \$this->auth->is_jabatan(\$this->router->fetch_class());

        \$this->_rules();

        if (\$this->form_validation->run() == FALSE) {
            \$this->update(\$this->input->post('$primary', TRUE));
        } else {
            \$data = array(";
$result2 = mysql_query("SELECT COLUMN_NAME,COLUMN_KEY FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$database' AND TABLE_NAME='$table' AND COLUMN_KEY <> 'PRI'");
if (mysql_num_rows($result2) > 0)
{
    while ($row2 = mysql_fetch_assoc($result2))
    {
        $string .= "\n\t\t'" . $row2['COLUMN_NAME'] . "' => \$this->input->post('" . $row2['COLUMN_NAME'] . "',TRUE),";
    }
}
$string .= "\n\t    );

            \$this->".$model."->update(\$this->input->post('$primary', TRUE), \$data);
            \$this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url(\$this->admin_folder.'$controller'));
        }
    }
    
    public function delete(\$id) 
    {   
        \$this->auth->is_jabatan(\$this->router->fetch_class());

        \$row = \$this->".$model."->get_by_id(\$id);

        if (\$row) {
            \$this->".$model."->delete(\$id);
            \$this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url(\$this->admin_folder.'$controller'));
        } else {
            \$this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url(\$this->admin_folder.'$controller'));
        }
    }

    public function _rules() 
    {
        \$this->auth->is_jabatan(\$this->router->fetch_class());
        ";

$result2 = mysql_query("SELECT COLUMN_NAME,COLUMN_KEY,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$database' AND TABLE_NAME='$table' AND COLUMN_KEY <> 'PRI'");
if (mysql_num_rows($result2) > 0)
{
    while ($row3 = mysql_fetch_assoc($result2))
    {
        $int = $row3['DATA_TYPE'] == 'int' || $row3['DATA_TYPE'] == 'double' || $row3['DATA_TYPE'] == 'decimal' ? '|numeric' : '';
        $string .= "\n\t\$this->form_validation->set_rules('".$row3['COLUMN_NAME']."', ' ', 'trim|required$int');";
    }
}
$string .= "\n\n\t\$this->form_validation->set_rules('$primary', '$primary', 'trim');";
$string .= "\n\t\$this->form_validation->set_error_delimiters('<span class=\"text-danger\">', '</span>');
    }";

if ($excel == 'create') {
    $string .= "\n\n    public function excel()
    {   
        \$this->auth->is_jabatan(\$this->router->fetch_class());

        \$this->load->helper('exportexcel');
        \$namaFile = \"$table.xls\";
        \$judul = \"$table\";
        \$tablehead = 2;
        \$tablebody = 3;
        \$nourut = 1;
        //penulisan header
        header(\"Pragma: public\");
        header(\"Expires: 0\");
        header(\"Cache-Control: must-revalidate, post-check=0,pre-check=0\");
        header(\"Content-Type: application/force-download\");
        header(\"Content-Type: application/octet-stream\");
        header(\"Content-Type: application/download\");
        header(\"Content-Disposition: attachment;filename=\" . \$namaFile . \"\");
        header(\"Content-Transfer-Encoding: binary \");

        xlsBOF();

        xlsWriteLabel(0, 0, \$judul);

        \$kolomhead = 0;
        xlsWriteLabel(\$tablehead, \$kolomhead++, \"no\");";

$result2 = mysql_query("SELECT COLUMN_NAME,COLUMN_KEY,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$database' AND TABLE_NAME='$table' AND COLUMN_KEY <> 'PRI'");
if (mysql_num_rows($result2) > 0)
{
    while ($row2 = mysql_fetch_assoc($result2))
    {
        $namakolom = $row2['COLUMN_NAME'];
        $string .= "\n\txlsWriteLabel(\$tablehead, \$kolomhead++, \"$namakolom\");";
    }
}

$string .= "\n\n\tforeach (\$this->" . $model . "->get_all() as \$data) {
            \$kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber(\$tablebody, \$kolombody++, \$nourut);";
$result2 = mysql_query("SELECT COLUMN_NAME,COLUMN_KEY,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$database' AND TABLE_NAME='$table' AND COLUMN_KEY <> 'PRI'");
if (mysql_num_rows($result2) > 0)
{
    while ($row2 = mysql_fetch_assoc($result2))
    {
        $namakolom = $row2['COLUMN_NAME'];
        $xlsWrite = $row2['DATA_TYPE'] == 'int' || $row2['DATA_TYPE'] == 'double' || $row2['DATA_TYPE'] == 'decimal' ? 'xlsWriteNumber' : 'xlsWriteLabel';
        $string .= "\n\t    " . $xlsWrite . "(\$tablebody, \$kolombody++, \$data->$namakolom);";
    }
}

$string .= "\n\n\t    \$tablebody++;
            \$nourut++;
        }

        xlsEOF();
        exit();
    }";
}

if ($word == 'create') {
    $string .= "\n\n    public function word()
    {   
        \$this->auth->is_jabatan(\$this->router->fetch_class());

        header(\"Content-type: application/vnd.ms-word\");
        header(\"Content-Disposition: attachment;Filename=$table.doc\");

        \$data = array(
            '" . $table . "_data' => \$this->" . $model . "->get_all(),
            'start' => 0
        );
        
        \$this->load->view('" . $table . "_html',\$data);
    }";
}

$string .= "\n\n};\n\n/* End of file $controller_file */
/* Location: ./application/controllers/$controller_file */";


fwrite($createController, $string);
fclose($createController);

$controller_res = "<p>" . $path . "</p>";
?>